<?php

namespace Database\Factories\V1;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\V1\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\v1\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'slug' => $this->faker->slug,
            'meta' => $this->faker->text,
            'image' => $this->faker->imageUrl(),
            'description' => $this->faker->text,
            'price' => $this->faker->randomFloat(2, 1, 100),
            'stock' => $this->faker->randomNumber(2),
            'status' => $this->faker->randomElement(['draft', 'published', 'archived']),
            'category_id' => Category::factory(),
        ];
    }
}
