<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Loan;
use App\Models\LoanEvent;

class LoanCalculator
{
    protected Loan $loan;
    protected array $events;
    protected ?Carbon $payoffDate;

    public function __construct(Loan $loan, array $events = [], ?string $payoffDate = null)
    {
        $this->loan = $loan;
        $this->events = collect($events)->sortBy('event_date')->values()->all();
        $this->payoffDate = $payoffDate ? Carbon::parse($payoffDate) : Carbon::parse($loan->end_date);
    }

    public function calculate(): array
    {
        return match ($this->loan->repayment_type) {
            'repayment' => $this->calculateRepayment(),
            'interest-only' => $this->calculateInterestOnly(),
            'interest-retained' => $this->calculateInterestRetained(),
        };
    }

    protected function calculateRepayment(): array
    {
        $start = Carbon::parse($this->loan->start_date);
        $end = $this->payoffDate;
        $days = $start->diffInDays($end) + 1;
        $months = $start->diffInMonths($end);

        $monthlyRate = $this->loan->annual_interest_rate / 12;
        $amount = $this->loan->amount;

        $monthlyPayment = ($amount * $monthlyRate) / (1 - pow(1 + $monthlyRate, -$months));
        $totalPaid = $monthlyPayment * $months;
        $totalInterest = $totalPaid - $amount;

        return [
            'summary' => [
                'monthly_payment' => round($monthlyPayment, 2),
                'total_interest' => round($totalInterest, 2),
                'total_paid' => round($totalPaid, 2),
                'final_payment' => $end->toDateString(),
            ],
        ];
    }

    protected function calculateInterestOnly(): array
    {
        $start = Carbon::parse($this->loan->start_date);
        $end = $this->payoffDate;
        $days = $start->diffInDays($end) + 1;

        $dailyInterest = $this->loan->amount * ($this->loan->annual_interest_rate / 365);
        $monthlyInterest = $dailyInterest * 30;

        return [
            'summary' => [
                'interest_per_day' => round($dailyInterest, 2),
                'days' => $days,
                'total_interest' => round($dailyInterest * $days, 2),
                'monthly_interest_bill' => round($monthlyInterest, 2),
                'balloon_principal' => round($this->loan->amount, 2),
                'final_payment' => $end->toDateString(),
            ],
        ];
    }

    protected function calculateInterestRetained(): array
    {
        $start = Carbon::parse($this->loan->start_date);
        $end = Carbon::parse($this->loan->end_date);
        $days = $start->diffInDays($end) + 1;

        $dailyInterest = $this->loan->amount * ($this->loan->annual_interest_rate / 365);
        $retainedInterest = $dailyInterest * $days;

        return [
            'summary' => [
                'interest_retained_day1' => round($retainedInterest, 2),
                'net_advance_to_borrower' => round($this->loan->amount - $retainedInterest, 2),
                'payments_during_term' => 0.00,
                'amount_due_on_' . $end->format('d_M_y') => round($this->loan->amount, 2),
                'interest_refund' => 0.00, // for now
            ],
        ];
    }
}
