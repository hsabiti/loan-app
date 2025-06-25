<?php

namespace Database\Factories;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Carbon\Carbon;

class LoanFactory extends Factory
{
    protected $model = Loan::class;

    public function definition(): array
    {
        $start = Carbon::parse('2025-06-01');
        $end = Carbon::parse('2025-11-30');

        return [
            'user_id' => User::factory(),
            'name' => 'Demo Loan',
            'amount' => 100000.00,
            'annual_interest_rate' => 0.09,
            'repayment_type' => 'repayment',
            'start_date' => $start,
            'end_date' => $end,
        ];
    }
}
