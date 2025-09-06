<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserInfoTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_profile_information_can_be_retrieved()
    {

        $user = User::factory()->create([
            'name' => '山本太郎',
        ]);

        $item = Item::factory()->create([
            'user_id' => $user->id,
            'name' => '出品商品1',
        ]);

        $response = $this->actingAs($user)
            ->get('/mypage?page=sell');

        $response->assertStatus(200);
        $response->assertSee('出品商品1');
        $response->assertSee($user->name);


        $response = $this->actingAs($user)
            ->get(route('mypage.edit'));

        $response->assertStatus(200);
        $response->assertSee('プロフィール設定');
        $response->assertSee('山本太郎');
        $response->assertSee('郵便番号');
    }
}
