<?php

declare(strict_types=1);

namespace Lines\Skeleton\Tests\Unit;

use Lines\Skeleton\Domain\Actions\CreatePostAction;
use Lines\Skeleton\Domain\DataTransferObjects\CreatePostData;
use Lines\Skeleton\Domain\Models\Post;
use Lines\Skeleton\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class CreatePostActionTest extends TestCase
{
    #[Test]
    public function __invoke(): void
    {
        (new CreatePostAction)(new CreatePostData(
            author_id: 1,
            title: 'Domain Driven and Modular Laravel Packages',
            body: 'lorem ipsum dolor sit amat',
        ));

        $this->assertModelExists(Post::class);
    }
}
