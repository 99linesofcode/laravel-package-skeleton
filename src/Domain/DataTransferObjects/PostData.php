<?php

declare(strict_types=1);

namespace Lines\Skeleton\Domain\DataTransferObjects;

use Carbon\CarbonImmutable;

final readonly class PostData extends DataTransferObject
{
    protected static function casts()
    {
        return [
            'published_at' => fn ($v) => self::castCarbonOrNull($v),
            'created_at' => fn ($v) => self::castCarbonOrNull($v),
            'updated_at' => fn ($v) => self::castCarbonOrNull($v),
            'deleted_at' => fn ($v) => self::castCarbonOrNull($v),
        ];
    }

    protected function __construct(
        public ?string $id,
        public int $author_id,
        public string $title,
        public string $body,
        public ?CarbonImmutable $published_at,
        public ?CarbonImmutable $created_at,
        public ?CarbonImmutable $updated_at,
        public ?CarbonImmutable $deleted_at,
    ) {}

    private static function castCarbonOrNull(?string $value)
    {
        return ! is_null($value) ? CarbonImmutable::parse($value) : null;
    }
}
