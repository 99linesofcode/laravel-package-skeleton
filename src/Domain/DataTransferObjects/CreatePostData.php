<?php

declare(strict_types=1);

namespace Lines\Skeleton\Domain\DataTransferObjects;

final readonly class CreatePostData
{
    public function __construct(
        public string $title,
        public string $body,
        public int $author_id,
    ) {}
}
