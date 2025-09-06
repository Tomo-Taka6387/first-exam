<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;



class FeatureHelloTest extends TestCase
{
    use RefreshDatabase;


    public function test_item_can_be_created()
    {
        $user = User::factory()->create();

        $condition = Condition::factory()->create(['condition' => '新品']);

        $itemData = [
            'user_id' => $user->id,
            'name' => 'iPhone 14',
            'brand' => 'Apple',
            'description' => '新品未使用のiPhoneです',
            'price' => 120,
            'condition_id' => $condition->id,
            'img_url' => 'dummy_image.png',
        ];

        $response = $this->actingAs($user)
            ->post(route('items.store'), $itemData);

        $response->assertStatus(302);

        $this->assertDatabaseHas('items', [
            'user_id' => $user->id,
            'name' => 'iPhone 14',
            'brand' => 'Apple',
            'description' => '新品未使用のiPhoneです',
            'price' => 120,

            'condition_id' => $condition->id,
            'img_url' => 'dummy_image.png',
        ]);
    }
}
