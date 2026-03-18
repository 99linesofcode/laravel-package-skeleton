<?php

declare(strict_types=1);

namespace Lines\Skeleton\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Lines\Auth\Database\Factories\UserFactory;
use Lines\Skeleton\Domain\Models\Post;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserFactory::new()->create([
            'name' => 'Jordy Schreuders',
            'email' => 'jordy@schreuders.it',
            'password' => Hash::make('pass1234'),
        ]);

        Post::factory()->count(5)->create();
        Post::factory()->scheduled()->count(5)->create();
        Post::factory()->published()->count(5)->create();
    }
}
