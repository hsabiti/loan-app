<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Loan;

class InterestOnlyLoanSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'interestonly@example.com'],
            ['name' => 'Interest-Only Borrower', 'password' => bcrypt('password')]
        );

        Loan::create([
            'user_id' => $user->id,
            'amount' => 100000,
            'annual_interest_rate' => 0.09,
            'repayment_type' => 'interest-only',
            'start_date' => '2025-06-01',
            'end_date' => '2025-11-30',
        ]);
    }
}
