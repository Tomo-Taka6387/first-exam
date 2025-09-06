<?php

namespace Tests\Unit;

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;
use App\Models\SoldItem;
use App\Models\Like;
use App\Models\Comment;

class MyListTest extends TestCase
{
    public function test_only_liked_items_are_displayed()
    {
        $user = User::factory()->create();

        $otherItem = Item::factory()->create(['name' => '他人の商品']);
        $myItem = Item::factory()->create(['user_id' => $user->id, 'name' => '自分の商品']);


        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $otherItem->id,
        ]);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertSee('他人の商品');
        $response->assertDontSee('自分の商品');
    }

    public function test_sold_items_show_sold_label_in_mylist()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();

        $soldItem = Item::factory()->create(['user_id' => $seller->id, 'name' => '購入済み商品']);


        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $soldItem->id,
        ]);


        SoldItem::factory()->create([
            'item_id' => $soldItem->id,
            'user_id' => $user->id,
            'sending_postcode' => '123-4567',
            'sending_address' => '東京都新宿区',
            'sending_building' => 'ビル101',
            'paymethod' => 'card',
        ]);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertSee('購入済み商品');
        $response->assertSee('SOLD');
    }

    public function test_mylist_is_empty_for_guest()
    {
        $response = $this->get('/?tab=mylist');

        $response->assertDontSee('商品名');
    }
}
