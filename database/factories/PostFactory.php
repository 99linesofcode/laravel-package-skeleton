<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Lines\Skeleton\Domain\Models\Post;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Lines\Skeleton\Domain\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Post::class;

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
            'body' => fake()->sentences(asText: true),
        ];
    }

    /**
     * Indicate that the post was published.
     */
    public function published(): static
    {
        return $this->state(fn () => [
            'published_at' => now(),
        ]);
    }
}
