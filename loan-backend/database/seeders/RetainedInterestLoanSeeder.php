<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Loan;
use App\Models\User;

class RetainedInterestLoanSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'retained@example.com'],
            ['name' => 'Retained Interest Borrower', 'password' => bcrypt('password')]
        );

        Loan::create([
            'user_id' => $user->id,
            'amount' => 100000,
            'annual_interest_rate' => 0.09,
            'repayment_type' => 'interest-retained',
            'start_date' => '2025-06-01',
            'end_date' => '2025-11-30',
        ]);
    }
}
