<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(), // Nom de l'item
            'cost' => $this->faker->numberBetween(100, 5000), // En centimes
            'price' => $this->faker->numberBetween(500, 10000), // En centimes
            'is_active' => $this->faker->boolean(), // Booléen pour activer ou non
            'category_id' => Category::all()->random()->id, // Lier chaque item à une catégorie existante
        ];
    }
}
