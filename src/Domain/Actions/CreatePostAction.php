<?php

declare(strict_types=1);

namespace Lines\Skeleton\Domain\Actions;

use Lines\Skeleton\Domain\DataTransferObjects\CreatePostData;
use Lines\Skeleton\Domain\Models\Post;

final class CreatePostAction
{
    public function __invoke(CreatePostData $createPostData): Post
    {
        return Post::create([
            'author_id' => $createPostData->author_id,
            'title' => $createPostData->title,
            'body' => $createPostData->body,
        ]);
    }
}
