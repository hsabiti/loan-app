<?php

namespace Database\Factories;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LoanFactory extends Factory
{
    protected $model = Loan::class;

    public function definition(): array
    {
        $amount = $this->faker->numberBetween(1000, 10000);
        $term = $this->faker->numberBetween(6, 24);
        $rate = 10.0;

        return [
            'user_id' => User::factory(), // creates a user and attaches loan
            'amount' => $amount,
            'term' => $term,
            'interest_rate' => $rate,
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
        ];
    }
}

