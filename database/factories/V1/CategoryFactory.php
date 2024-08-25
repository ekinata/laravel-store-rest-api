<?php

namespace Database\Factories\V1;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\v1\Category>
 */
class CategoryFactory extends Factory
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
            'cover' => $this->faker->imageUrl(),
            'meta' => $this->faker->text,
            'description' => $this->faker->text,
            'status' => $this->faker->boolean,
            'parent_id' => null
        ];
    }
}
