<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShippingAddressTest extends TestCase
{
    use RefreshDatabase;

    public function test_shipping_address_saved_after_purchase()
    {

        $user = User::factory()->create();

        $user->profile()->create([
            'postcode' => '123-4567',
            'address' => '東京都',
            'building' => '建物名',
        ]);


        $item = Item::factory()->create();


        $this->actingAs($user)
            ->post(route('purchase.address.update', ['item' => $item->id]), [
                'sending_postcode' => '987-6543',
                'sending_address' => '大阪府',
                'sending_building' => '新建物',
            ]);

        $this->actingAs($user)
            ->post(route('purchase.store', ['item' => $item->id]), [
                'paymethod' => 'card',
            ]);

        $this->assertDatabaseHas('sold_items', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'sending_postcode' => '987-6543',
            'sending_address' => '大阪府',
            'sending_building' => '新建物',
        ]);
    }
}
