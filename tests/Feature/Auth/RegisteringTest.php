<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisteringTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_student_can_register(): void
    {
        $studentData = [
            'name' => 'Test Student',
            'email' => 'test@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'student',
        ];

        $response = $this->postJson(route('api.register'), $studentData);
        $response->assertStatus(201)
                    ->assertJson(['message' => 'Registration successful!']);

        $this->assertDatabaseHas('users', [
            'email' => $studentData['email']]);
    }


    public function test_instructor_can_register()
    {
        $instructorData = [
            'name' => 'Test Instructor',
            'email' => 'instructor@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'instructor',
        ];
        $response = $this->postJson(route('api.register'), $instructorData);

        $response->assertStatus(201)
                    ->assertJson(['message' => 'Registration pending admin approval.']);

        $this->assertDatabaseHas('users', ['email' => $instructorData['email']]);
    }
}
