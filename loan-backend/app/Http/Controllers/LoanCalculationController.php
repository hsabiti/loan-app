<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use Carbon\Carbon;

class LoanCalculationController extends Controller
{
    public function calculate(Request $request)
    {
        // If a loan_id is passed, use the saved loan for calculation
        if ($request->has('loan_id')) {
            $loan = $request->user()->loans()->findOrFail($request->loan_id);
            return $this->calculateFromLoan($loan);
        }

        // Otherwise, validate incoming data and calculate on the fly
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'annual_interest_rate' => 'required|numeric|min:0.0001',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'repayment_type' => 'required|in:repayment,interest-only,interest-retained',
        ]);

        return $this->calculateFromInput(
            $validated['amount'],
            $validated['annual_interest_rate'],
            Carbon::parse($validated['start_date']),
            Carbon::parse($validated['end_date']),
            $validated['repayment_type']
        );
    }

    public function recalculate(Request $request)
    {
        $validated = $request->validate([
            'loan_id' => 'required|exists:loans,id',
        ]);

        $loan = $request->user()->loans()->with('events')->findOrFail($validated['loan_id']);

        $amount = $loan->amount;
        $rate = $loan->annual_interest_rate;
        $start = Carbon::parse($loan->start_date ?? now());
        $end = Carbon::parse($loan->end_date ?? now());

        // Check for early payoff event
        $payoffEvent = $loan->events->firstWhere('type', 'early_payoff');
        if ($payoffEvent) {
            $end = Carbon::parse($payoffEvent->event_date);
        }

        $dailyBalance = $amount;
        $dailyRate = $rate;
        $totalInterest = 0;
        $days = $start->diffInDays($end) + 1;

        $eventsByDate = $loan->events->groupBy(fn($e) => Carbon::parse($e->event_date)->toDateString());

        for ($date = $start->copy(); $date <= $end; $date->addDay()) {
            $eventDay = $date->toDateString();

            if ($eventsByDate->has($eventDay)) {
                foreach ($eventsByDate[$eventDay] as $event) {
                    if ($event->type === 'part_payment') {
                        $dailyBalance -= $event->amount;
                    }
                    if ($event->type === 'rate_change') {
                        $dailyRate = $event->rate;
                    }
                }
            }

            $totalInterest += $dailyBalance * ($dailyRate / 365);
        }

        return response()->json([
            'summary' => [
                'actual_interest_to_' . $end->toDateString() => round($totalInterest, 2),
                'days' => $days,
                'balance_on_' . $end->toDateString() => round($dailyBalance, 2),
            ]
        ]);
    }

    private function calculateFromLoan(Loan $loan)
    {
        return $this->calculateFromInput(
            $loan->amount,
            $loan->annual_interest_rate,
            Carbon::parse($loan->start_date),
            Carbon::parse($loan->end_date),
            $loan->repayment_type
        );
    }

    private function calculateFromInput($amount, $annualRate, Carbon $start, Carbon $end, $type)
    {
        $days = $start->diffInDays($end) + 1;
        $summary = [];

        switch ($type) {
            case 'repayment':
                $months = $start->diffInMonths($end);
                $monthlyRate = $annualRate / 12;

                $monthlyPayment = $amount * ($monthlyRate / (1 - pow(1 + $monthlyRate, -$months)));
                $totalPaid = $monthlyPayment * $months;
                $totalInterest = $totalPaid - $amount;

                $summary = [
                    'monthly_payment' => round($monthlyPayment, 2),
                    'total_interest' => round($totalInterest, 2),
                    'total_paid' => round($totalPaid, 2),
                    'final_payment' => $end->toDateString(),
                ];
                break;

            case 'interest-only':
                $interestPerDay = $amount * ($annualRate / 365);
                $totalInterest = $interestPerDay * $days;
                $monthlyInterest = $totalInterest / ($days / 30);

                $summary = [
                    'interest_per_day' => round($interestPerDay, 2),
                    'days' => $days,
                    'total_interest' => round($totalInterest, 2),
                    'monthly_interest_bill' => round($monthlyInterest, 2),
                    'balloon_principal' => round($amount, 2),
                    'final_payment' => $end->toDateString(),
                ];
                break;

            case 'interest-retained':
                $interestPerDay = $amount * ($annualRate / 365);
                $totalInterest = $interestPerDay * $days;
                $netAdvance = $amount - $totalInterest;

                $summary = [
                    'interest_retained_day1' => round($totalInterest, 2),
                    'net_advance_to_borrower' => round($netAdvance, 2),
                    'payments_during_term' => 0.00,
                    'amount_due_on_' . $end->format('d_M_Y') => round($amount, 2),
                    'interest_refund' => 0.00,
                ];
                break;
        }

        return response()->json(['summary' => $summary]);
    }
}
