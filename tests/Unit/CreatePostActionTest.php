<?php

declare(strict_types=1);

uses(\Lines\Skeleton\Tests\TestCase::class);

use Lines\Skeleton\Domain\Actions\CreatePostAction;
use Lines\Skeleton\Domain\DataTransferObjects\CreatePostData;
use Lines\Skeleton\Domain\Models\Post;

test('invoke', function () {
    (new CreatePostAction)(new CreatePostData(
        author_id: 1,
        title: 'Domain Driven and Modular Laravel Packages',
        body: 'lorem ipsum dolor sit amat',
    ));

    self::assertModelExists(Post::class);
});
