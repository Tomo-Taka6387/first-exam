<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;

class ItemSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_mylist_search_returns_only_liked_items_matching_keyword()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $item1 = Item::factory()->create([
            'user_id' => $otherUser->id,
            'name' => '他人のノートPC',
        ]);
        $item2 = Item::factory()->create([
            'user_id' => $otherUser->id,
            'name' => '他人のタブレット',
        ]);
        $item3 = Item::factory()->create([
            'user_id' => $user->id,
            'name' => '自分のスマホ',
        ]);


        Like::factory()->create(['user_id' => $user->id, 'item_id' => $item1->id]);
        Like::factory()->create(['user_id' => $user->id, 'item_id' => $item2->id]);
        Like::factory()->create(['user_id' => $user->id, 'item_id' => $item3->id]);

        $response = $this->actingAs($user)->get('/?tab=mylist&keyword=タブレット');

        $response->assertSee('他人のタブレット');
        $response->assertDontSee('他人のノートPC');
        $response->assertDontSee('自分のスマホ');
    }
}
