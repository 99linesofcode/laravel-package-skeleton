<?php

declare(strict_types=1);

namespace Blog\Domain\Actions;

use Blog\Domain\DataTransferObjects\CreatePostData;
use Blog\Domain\Models\Post;

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
