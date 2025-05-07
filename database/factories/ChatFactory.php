<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
use App\Models\User;

class ChatFactory extends Factory
{
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'sender_id' => User::factory(),
            'message' => $this->faker->sentence,
        ];
    }
}
