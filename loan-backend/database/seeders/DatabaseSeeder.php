<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Loan;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        // Only add new loans if the user doesn't already have them
        if ($user->loans()->count() === 0) {
            Loan::factory()->count(3)->for($user)->create();
        }

        // Optionally, seed unrelated demo loans (without users or using random users)
        Loan::factory()->count(5)->create();
    }
}
