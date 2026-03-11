<?php

declare(strict_types=1);

namespace Lines\Skeleton\Domain\DataTransferObjects;

use ReflectionClass;

abstract readonly class DataTransferObject
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, mixed>
     */
    protected static function casts(): array
    {
        return [];
    }

    /**
     * Get a concrete instance of a DTO with values passed in through an array.
     *
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): static
    {
        $reflection = new ReflectionClass(static::class);
        $parameters = $reflection->getConstructor()->getParameters();
        $casts = static::casts();

        $arguments = [];
        foreach ($parameters as $p) {
            $k = $p->getName();
            $v = $data[$k] ?? null;

            $arguments[$k] = isset($casts[$k]) ? $casts[$k]($v) : $v;
        }

        return new static(...$arguments);
    }
}
