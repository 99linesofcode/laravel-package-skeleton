<?php

declare(strict_types=1);

namespace Lines\Skeleton\Domain\DataTransferObjects;

final readonly class PostData
{
    public function __construct(
        public int $author_id,
        public string $title,
        public string $body,
        public ?string $id = null,
        public ?string $published_at = null,
        public ?string $created_at = null,
        public ?string $updated_at = null,
        public ?string $deleted_at = null,
    ) {}
}
