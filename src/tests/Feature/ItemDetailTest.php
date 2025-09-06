<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Profile;
use App\Models\Like;
use App\Models\Comment;

class ItemDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_item_detail_displays_all_information()
    {

        $user = User::factory()->create();
        Profile::factory()->create(['user_id' => $user->id]);

        $otherUser = User::factory()->create();
        Profile::factory()->create(['user_id' => $otherUser->id]);

        $condition = Condition::factory()->create(['condition' => '新品']);
        $category1 = Category::factory()->create(['category' => '家電']);
        $category2 = Category::factory()->create(['category' => 'スマホ']);

        $item = Item::factory()->create([
            'user_id' => $user->id,
            'name' => 'iPhone 14',
            'brand' => 'Apple',
            'price' => 120,
            'img_url' => 'images/iphone14.png',
            'description' => '最新モデルのiPhoneです',
            'condition_id' => $condition->id,
        ]);

        $item->categories()->attach([$category1->id, $category2->id]);


        Like::factory()->create(['user_id' => $otherUser->id, 'item_id' => $item->id]);


        Comment::factory()->create([
            'user_id' => $otherUser->id,
            'item_id' => $item->id,
            'content' => '素晴らしい商品です！'
        ]);

        $response = $this->get(route('items.show', ['item' => $item->id]));


        $response->assertSee('iPhone 14');
        $response->assertSee('Apple');
        $response->assertSee('120');
        $response->assertSee('最新モデルのiPhoneです');
        $response->assertSee('新品');


        $response->assertSee('家電');
        $response->assertSee('スマホ');


        $response->assertSee('1');


        $response->assertSee('1');

        $response->assertSee('素晴らしい商品です！');
        $response->assertSee($otherUser->name);

        $response->assertSee('images/iphone14.png');
    }
}
