<?php

namespace Database\Seeders;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Database\Seeder;

class RepaymentLoanSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'repayment@example.com'],
            ['name' => 'Repayment Borrower', 'password' => bcrypt('password')]
        );

        Loan::create([
            'user_id' => $user->id,
            'amount' => 100000,
            'annual_interest_rate' => 0.09,
            'repayment_type' => 'repayment',
            'start_date' => '2025-06-01',
            'end_date' => '2025-11-30',
        ]);
    }
}
