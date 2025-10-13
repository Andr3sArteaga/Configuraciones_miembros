<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Notification;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition()
    {
        return [
            'id' => (string) Str::uuid(),
            'user_id' => null, // lo rellenamos cuando la usemos en el seeder/route
            'message' => $this->faker->sentence(),
            'read' => false,
            'created_at' => now()->subMinutes(rand(0, 120)),
            'updated_at' => now(),
        ];
    }
}
