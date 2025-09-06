<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class LogoutTest extends TestCase
{

    use RefreshDatabase;

    public function test_user_can_logout()
    {
        $user = User::factory()->create();

        // ログイン状態を作る
        $this->actingAs($user);

        // ログアウト実行
        $response = $this->post('/logout');

        // ログアウト後にリダイレクトされる先を確認（例: /login）
        $response->assertRedirect('/login');

        // ログアウトできていることを確認
        $this->assertGuest();
    }
}
