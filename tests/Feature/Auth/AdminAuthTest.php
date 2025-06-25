<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;
  

    protected User $admin;
    protected User $user; 

    protected function setUp(): void 
    {   
        parent::setUp(); 
        
        putenv('ADMIN_SECRET_CODE=TestSecret123'); // Set the admin passcode for testing
        
         $this->admin = User::factory()->create([
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $this->user = User::factory()->create([
            'email' => 'user@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'student',
        ]);

    }


    // test success login
    //--------------------------
 
    public function test_it_returns_temp_token_when_admin_logs_in_correctly()
    {
        $response = $this->postJson(route('admin.login'), [
            'email' => $this->admin->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'temp_token',
            ]); 
    }

    public function test_it_returns_token_when_passcode_is_correct()
    {
        $login = $this->postJson(route('admin.login'), [
            'email' => $this->admin->email,
            'password' => 'password'
        ]);


        $tempToken = $login->json('temp_token');

        $res = $this->postJson(route('admin.verify'), [
            'temp_token' => $tempToken,
            'admin_passcode' => 'TestSecret123'
        ]);

        $res->assertStatus(200)
            ->assertJsonStructure(['message', 'token']);
    }



    // check failure 
    //-----------------
    public function test_it_fails_if_credentials_are_wrong()
    {
        $res = $this->postJson(route('admin.login'), [
            'email' => 'admin@gmail.com',
            'password' => 'wrongpass'
        ]);

        $res->assertStatus(401)
            ->assertJson(['message' => 'Invalid credentials']);
    }


    
    public function test_it_fails_if_user_is_not_admin()
    {
        $res = $this->postJson(route('admin.login'), [
            'email' => 'user@gmail.com',
            'password' => 'password'
        ]);

        $res->assertStatus(403)
            ->assertJson(['message' => 'Unauthorized']);
    }


     
    public function test_it_fails_if_passcode_is_wrong()
    {
        $login = $this->postJson(route('admin.login'), [
            'email' => 'admin@gmail.com',
            'password' => 'password'
        ]);

        $tempToken = $login->json('temp_token');

        $res = $this->postJson(route('admin.verify'), [
            'temp_token' => $tempToken,
            'admin_passcode' => 'WrongCode'
        ]);

        $res->assertStatus(403)
            ->assertJson(['message' => 'Invalid admin passcode']);
    }

    
    public function test_it_fails_with_invalid_temp_token()
    {
        $res = $this->postJson(route('admin.verify'), [
            'temp_token' => 'invalid-encrypted-id',
            'admin_passcode' => 'TestSecret123'
        ]);

        $res->assertStatus(400)
            ->assertJson(['message' => 'Invalid token']);
    }

    
    public function test_it_requires_temp_token_and_passcode_in_second_step()
    {
        $response = $this->postJson(route('admin.verify'), []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['temp_token', 'admin_passcode']);
    }
}
