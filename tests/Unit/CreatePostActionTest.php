<?php

declare(strict_types=1);

namespace Blog\Tests\Unit;

use Blog\Domain\Actions\CreatePostAction;
use Blog\Domain\DataTransferObjects\CreatePostData;
use Blog\Domain\Models\Post;
use Blog\Tests\TestCase;
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
