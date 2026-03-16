<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Lines\Skeleton\Domain\Models\Post;
use Workbench\Database\Factories\UserFactory;

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
