<?php

declare(strict_types=1);

namespace Lines\Skeleton\Domain\Actions;

use Lines\Skeleton\Domain\DataTransferObjects\PostData;
use Lines\Skeleton\Domain\Models\Post;

final class CreatePostAction
{
    public function __invoke(PostData $postData): Post
    {
        return Post::create([
            'author_id' => $postData->author_id,
            'title' => $postData->title,
            'body' => $postData->body,
            'status' => $postData->status->value,
            'published_at' => $postData->published_at,
        ]);
    }
}
