<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use App\Models\Like;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_like_and_unlike_item_and_icon_changes()
    {
        $user = User::factory()->create();
        $condition = Condition::factory()->create(['condition' => '新品']);
        $item = Item::factory()->create([
            'user_id' => $user->id,
            'condition_id' => $condition->id,
        ]);

        $this->actingAs($user);

        $response = $this->get(route('items.show', $item));
        $response->assertStatus(200);
        $response->assertDontSee('liked');


        $this->post(route('items.like', $item));
        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);


        $response = $this->get(route('items.show', $item));
        $response->assertSee('liked');

        $this->delete(route('items.unlike', $item));
        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);


        $response = $this->get(route('items.show', $item));
        $response->assertDontSee('liked');
    }
}
