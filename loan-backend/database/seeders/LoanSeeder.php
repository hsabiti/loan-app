<?php

namespace Database\Seeders;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Database\Seeder;

class LoanSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'test@example.com')->first();

        if ($user) {
            $types = ['repayment', 'interest-only', 'interest-retained'];

            foreach ($types as $type) {
                Loan::factory()->create([
                    'user_id' => $user->id,
                    'repayment_type' => $type,
                    'name' => ucfirst(str_replace('-', ' ', $type)) . ' Loan',
                ]);
            }
        }

        // Optionally: Add 2 more random loans for demo purposes
        Loan::factory()->count(2)->create();
    }
}
