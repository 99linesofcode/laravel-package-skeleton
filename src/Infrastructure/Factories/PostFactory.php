<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Blog\Domain\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'author_id' => 1,
            'title' => fake()->sentence(),
            'body' => fake()->sentences(),
        ];
    }

    /**
     * Indicate that the post was published.
     */
    public function published(): Factory
    {
        return $this->state(function (): array {
            return [
                'published_at' => now(),
            ];
        });
    }
}
