<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LoanTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_loan(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $payload = [
            'name' => 'Test Loan',
            'amount' => 5000,
            'annual_interest_rate' => 0.09,
            'repayment_type' => 'repayment',
            'start_date' => '2025-06-01',
            'end_date' => '2025-12-01',
        ];

        $response = $this->postJson('/api/loans', $payload);

        $response->assertStatus(201)
                ->assertJsonFragment([
                    'amount' => 5000,
                    'repayment_type' => 'repayment',
                ]);
    }

}
