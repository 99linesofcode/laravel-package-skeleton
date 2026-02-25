<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Lines\Skeleton\Domain\Models\Post;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Post::factory()->count(20)->create();
    }
}
