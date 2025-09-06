<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use App\Models\SoldItem;

class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_select_payment_method()
    {

        $user = User::factory()->create();

        $user->profile()->create([
            'postcode' => '123-4567',
            'address' => '東京都',
            'building' => '建物名',
        ]);


        $condition = Condition::factory()->create();
        $item = Item::factory()->create([
            'condition_id' => $condition->id
        ]);

        $response = $this->actingAs($user)
            ->post(route('purchase.store', ['item' => $item->id]), [
                'paymethod' => 'card',
                'postcode' => $user->profile->postcode,
                'address' => $user->profile->address,
                'building' => $user->profile->building,
            ]);



        $response->assertRedirect('/');

        $this->assertDatabaseHas('sold_items', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'paymethod' => 'card',
        ]);
    }
}
