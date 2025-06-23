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

        // Authenticate the user using Sanctum
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/loans', [
            'amount' => 5000,
            'term' => 12,
        ]);

        $response->assertCreated()
                 ->assertJsonStructure(['id', 'amount', 'term', 'interest_rate', 'status']);
    }
}
