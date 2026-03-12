<?php

declare(strict_types=1);

uses(\Lines\Skeleton\Tests\TestCase::class);

use Carbon\CarbonImmutable;
use Lines\Skeleton\Domain\DataTransferObjects\DataTransferObject;

final readonly class ConcreteDataTransferObject extends DataTransferObject
{
    protected static function casts(): array
    {
        return [
            'published_at' => fn ($v) => CarbonImmutable::parse($v),
            'status' => fn ($_, $data) => self::computeStatus($data),
        ];
    }

    public function __construct(
        public int $id,
        public string $name,
        public ?string $status,
        public ?CarbonImmutable $published_at,
        public ?string $optional = null,
    ) {}

    private static function computeStatus(array $data): string
    {
        if (isset($data['published_at'])) {
            $date = self::casts()['published_at']($data['published_at']);
        } else {
            $date = null;
        }

        return match (true) {
            is_null($date) => 'draft',
            $date->isPast() => 'published',
            $date->isFuture() => 'scheduled',
        };
    }
}

describe('DataTransferObject', function () {
    describe('fromArray', function () {
        it('returns the correct concrete instance', function () {
            $dto = ConcreteDataTransferObject::fromArray([
                'id' => fake()->randomNumber(),
                'name' => fake()->sentence(),
            ]);

            expect($dto)->toBeInstanceOf(ConcreteDataTransferObject::class);
        });

        it('correctly maps primitive properties', function () {
            $dto = ConcreteDataTransferObject::fromArray([
                'id' => 1,
                'name' => 'The man with the masterplan',
            ]);

            expect($dto->id)
                ->toBe(1)
                ->and($dto->name)
                ->toBe('The man with the masterplan');
        });

        it('can cast to a different type', function () {
            $dto = ConcreteDataTransferObject::fromArray([
                'id' => fake()->randomNumber(),
                'name' => fake()->sentence(),
                'published_at' => '2026-01-01 00:00:00',
            ]);

            expect($dto->published_at)->toBeInstanceOf(CarbonImmutable::class);
        });

        it('can set a property based on the value of another', function () {
            $dto = ConcreteDataTransferObject::fromArray([
                'id' => fake()->randomNumber(),
                'name' => fake()->sentence(),
                'published_at' => '2026-01-01 00:00:00',
            ]);

            expect($dto)->toHaveProperty('status', 'published');
        });

        it('resolves null for a nullable property without casting', function () {
            $dto = ConcreteDataTransferObject::fromArray([
                'id' => fake()->randomNumber(),
                'name' => fake()->sentence(),
                'optional' => null,
            ]);

            expect($dto->optional)->toBeNull();
        });

        it('resolves null for a nullable property when property is absent', function () {
            $dto = ConcreteDataTransferObject::fromArray([
                'id' => fake()->randomNumber(),
                'name' => fake()->sentence(),
            ]);

            expect($dto->optional)->toBeNull();
        });
    });
});
