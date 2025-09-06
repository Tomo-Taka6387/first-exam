<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use App\Models\SoldItem;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_purchase_item()
    {
        $user = User::factory()->create();

        $user->profile()->create([
            'postcode' => '123-4567',
            'address' => '東京都港区',
            'building' => 'テストビル',
        ]);

        $condition = Condition::factory()->create();
        $item = Item::factory()->create(['condition_id' => $condition->id]);

        $response = $this->actingAs($user)
            ->post(route('purchase.store', ['item' => $item->id]), [
                'paymethod' => 'card',
                'sending_postcode' => $user->profile->postcode,
                'sending_address' => $user->profile->address,
                'sending_building' => $user->profile->building,
            ]);

        $response->assertRedirect('/');

        $this->assertDatabaseHas('sold_items', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    public function test_purchased_item_is_marked_sold_in_item_list()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();
        $condition = Condition::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'condition_id' => $condition->id,
            'name' => 'iPhone14',
            'img_url' => 'images/iphone14.png',
        ]);

        SoldItem::create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'sending_postcode' => '123-4567',
            'sending_address' => '東京都港区',
            'sending_building' => 'テストビル',
            'paymethod' => 'card',
        ]);

        $response = $this->actingAs($seller)->get(route('mypage', ['page' => 'sell']));

        $response->assertSee('SOLD');
        $response->assertSee($item->name);
    }



    public function test_purchased_item_appears_in_profile_purchase_list()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();

        $condition = Condition::factory()->create();
        $item = Item::factory()->create([
            'condition_id' => $condition->id,
            'user_id' => $seller->id,
            'img_url' => 'images/iphone14.png',
            'name' => 'iPhone14'
        ]);



        SoldItem::create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'sending_postcode' => '123-4567',
            'sending_address' => '東京都港区',
            'sending_building' => 'テストビル',
            'paymethod' => 'card',
        ]);

        $response = $this->actingAs($seller)->get(route('mypage', ['page' => 'sell']));

        $response->assertSee('SOLD');
        $response->assertSee($item->name);
    }
}
