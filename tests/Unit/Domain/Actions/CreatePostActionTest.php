<?php

declare(strict_types=1);

uses(\Lines\Skeleton\Tests\TestCase::class);

use Lines\Skeleton\Domain\Actions\CreatePostAction;
use Lines\Skeleton\Domain\DataTransferObjects\PostData;
use Lines\Skeleton\Domain\Models\Post;

use function Pest\Laravel\assertDatabaseHas;

describe('CreatePostAction', function () {
    it('creates a post', function () {
        $post = Post::factory()->make()->except('id');

        (new CreatePostAction)(PostData::fromArray($post));

        assertDatabaseHas(Post::class, $post);
    });
});
