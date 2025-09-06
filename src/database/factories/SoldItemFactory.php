<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\SoldItem;
use App\Models\Item;
use App\Models\User;

class SoldItemFactory extends Factory
{
    protected $model = SoldItem::class;

    public function definition()
    {
        return [
            'item_id' => Item::factory(),
            'user_id' => User::factory(),
            'sending_postcode' => $this->faker->postcode,
            'sending_address' => $this->faker->address,
            'sending_building' => $this->faker->secondaryAddress,
            'paymethod' => $this->faker->randomElement(['card', 'convenience_store']),
        ];
    }
}
