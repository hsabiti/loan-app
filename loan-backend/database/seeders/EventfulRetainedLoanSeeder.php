<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Loan;
use App\Models\LoanEvent;

class EventfulRetainedLoanSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'eventful@example.com'],
            ['name' => 'Eventful Borrower', 'password' => bcrypt('password')]
        );

        $loan = Loan::create([
            'user_id' => $user->id,
            'amount' => 100000,
            'annual_interest_rate' => 0.09,
            'repayment_type' => 'interest-retained',
            'start_date' => '2025-06-01',
            'end_date' => '2025-11-30',
        ]);

       $loan->events()->createMany([
            ['type' => 'part_payment', 'event_date' => '2025-09-15', 'amount' => 20000],
            ['type' => 'rate_change', 'event_date' => '2025-10-01', 'rate' => 0.12],
            ['type' => 'early_payoff', 'event_date' => '2025-10-26'],
        ]);

    }
}
