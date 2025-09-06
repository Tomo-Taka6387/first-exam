<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\SoldItem;

class ItemListTest extends TestCase
{
    use RefreshDatabase;

    public function test_sold_items_are_marked_sold()
    {
        $buyer = User::factory()->create();
        $this->actingAs($buyer);

        $seller = User::factory()->create();

        $soldItem = Item::factory()->create([
            'user_id' => $seller->id,
            'name' => '購入済み商品',
        ]);

        SoldItem::factory()->create([
            'item_id' => $soldItem->id,
            'user_id' => $buyer->id,
            'sending_postcode' => '123-4567',
            'sending_address' => '東京都新宿区',
            'sending_building' => 'ビル101',
            'paymethod' => 'card',
        ]);

        $response = $this->get('/mypage?page=buy');

        $response->assertSee('購入済み商品');
        $response->assertSee('SOLD');
    }

    public function test_own_items_are_not_displayed()
    {

        $user = User::factory()->create();


        $otherUser = User::factory()->create();

        $otherItem = Item::factory()->create([
            'user_id' => $otherUser->id,
            'name' => '他人の商品',
        ]);

        SoldItem::factory()->create([
            'item_id' => $otherItem->id,
            'user_id' => $user->id,
            'sending_postcode' => '123-4567',
            'sending_address' => '東京都新宿区',
            'sending_building' => 'ビル101',
            'paymethod' => 'card',
        ]);

        $myItem = Item::factory()->create([
            'user_id' => $user->id,
            'name' => '自分の商品',
        ]);


        $response = $this->actingAs($user)
            ->get('/mypage?page=buy');


        $response->assertSee('他人の商品');


        $response->assertDontSee('自分の商品');
    }
}
