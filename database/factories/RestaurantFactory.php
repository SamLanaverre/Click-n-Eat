<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Restaurant>
 */
class RestaurantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'address' => fake()->address(),
            'phone' => fake()->phoneNumber(),
            'description' => fake()->text(),
            'owner_id' => User::factory()->create(['role' => 'restaurateur'])->id,
            'opening_hours' => json_encode([
                'monday' => ['09:00-12:00', '14:00-22:00'],
                'tuesday' => ['09:00-12:00', '14:00-22:00'],
                'wednesday' => ['09:00-12:00', '14:00-22:00'],
                'thursday' => ['09:00-12:00', '14:00-22:00'],
                'friday' => ['09:00-12:00', '14:00-22:00'],
                'saturday' => ['10:00-23:00'],
                'sunday' => ['10:00-23:00']
            ]),
        ];
    }
}