<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;


class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_name_is_required_on_registration()
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'test@test.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]);


        $response->assertSessionHasErrors(['name']);
    }

    public function test_email_is_required_on_registration()
    {
        $response = $this->post('/register', [
            'name' => 'テスト太郎',
            'email' => '',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]);


        $response->assertSessionHasErrors(['email']);
    }

    public function test_password_is_required_on_registration()
    {
        $response = $this->post('/register', [
            'name' => 'テスト太郎',
            'email' => 'test@test.com',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors(['password']);
    }
    public function test_password_confirmation_must_match()
    {
        $response = $this->post('/register', [
            'name' => 'テスト次郎',
            'email' => 'mismatch@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different123',
        ]);


        $response->assertSessionHasErrors(['password']);
    }


    public function test_user_can_register_successfully()
    {
        $response = $this->post('/register', [
            'name' => 'テスト太郎',
            'email' => 'test@test.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@test.com',
        ]);

        $response->assertRedirect('/mypage/profile');
    }
}
