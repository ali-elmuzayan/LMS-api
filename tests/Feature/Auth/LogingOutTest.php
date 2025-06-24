<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LogingOutTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_loging_out_student_user_successfully(): void
    {
        $student = User::factory()->create(['role' => 'student']);
    $token = $student->createToken('auth-token')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->postJson(route('api.logout'));

    $response->assertStatus(200)
             ->assertJson(['message' => 'Logged out successfully.']);

             
    $this->assertDatabaseMissing('personal_access_tokens', [
        'tokenable_id' => $student->id,
        'tokenable_type' => User::class,
    ]);
    }
}
