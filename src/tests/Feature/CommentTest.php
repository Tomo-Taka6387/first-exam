<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Comment;
use App\Models\Condition;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_logged_in_user_can_post_comment()
    {
        $user = User::factory()->create();
        $condition = Condition::factory()->create();
        $item = Item::factory()->create(['condition_id' => $condition->id]);

        $this->actingAs($user)
            ->post(route('items.comment', ['item' => $item->id]), [
                'content' => '素晴らしい商品です！'
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'content' => '素晴らしい商品です！',
        ]);
    }

    public function test_guest_cannot_post_comment()
    {
        $condition = Condition::factory()->create();
        $item = Item::factory()->create(['condition_id' => $condition->id]);

        $response = $this->post(route('items.comment', ['item' => $item->id]), [
            'content' => '素晴らしい商品です！'
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseCount('comments', 0);
    }

    public function test_comment_cannot_be_empty()
    {
        $user = User::factory()->create();
        $condition = Condition::factory()->create();
        $item = Item::factory()->create(['condition_id' => $condition->id]);

        $response = $this->actingAs($user)
            ->post(route('items.comment', ['item' => $item->id]), [
                'content' => ''
            ]);

        $response->assertSessionHasErrors(['content']);
        $this->assertDatabaseCount('comments', 0);
    }

    public function test_comment_cannot_exceed_255_characters()
    {
        $user = User::factory()->create();
        $condition = Condition::factory()->create();
        $item = Item::factory()->create(['condition_id' => $condition->id]);

        $longComment = str_repeat('あ', 256);

        $response = $this->actingAs($user)
            ->post(route('items.comment', ['item' => $item->id]), [
                'content' => $longComment
            ]);

        $response->assertSessionHasErrors(['content']);
        $this->assertDatabaseCount('comments', 0);
    }
}
