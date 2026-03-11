<?php

declare(strict_types=1);

uses(\Lines\Skeleton\Tests\TestCase::class);

use Lines\Skeleton\Domain\Actions\UpdatePostAction;
use Lines\Skeleton\Domain\DataTransferObjects\PostData;
use Lines\Skeleton\Domain\Models\Post;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;

describe('UpdatePostAction', function () {
    it('updates an existing post', function () {
        $original = Post::factory()->draft()->create();
        $updated = Post::factory()->existing($original)->make()->toArray();

        (new UpdatePostAction)(PostData::fromArray($updated));

        assertDatabaseCount(Post::class, 1);
        assertDatabaseHas(Post::class, $updated);
    });
});
