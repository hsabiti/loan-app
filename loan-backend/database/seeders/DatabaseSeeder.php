<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Loan;
use Illuminate\Database\Seeder;
use Database\Seeders\InterestOnlyLoanSeeder;
use Database\Seeders\RetainedInterestLoanSeeder;
use Database\Seeders\EventfulRetainedLoanSeeder;


class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            LoanSeeder::class,
            RepaymentLoanSeeder::class,
            InterestOnlyLoanSeeder::class,
            RetainedInterestLoanSeeder::class,
            EventfulRetainedLoanSeeder::class,
        ]);
    }

}
