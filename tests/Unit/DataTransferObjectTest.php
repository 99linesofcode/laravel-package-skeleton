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
            'status' => fn ($v) => TestStatus::from($v),
            'created_at' => fn ($v) => CarbonImmutable::parse($v),
        ];
    }

    public function __construct(
        public int $id,
        public string $name,
        public TestStatus $status,
        public ?CarbonImmutable $created_at,
        public ?string $optional = null,
    ) {}
}

enum TestStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
}

describe('DataTransferObject', function () {
    describe('fromArray', function () {
        it('correctly maps primitive properties', function () {
            $dto = ConcreteDataTransferObject::fromArray([
                'id' => 1,
                'name' => 'Test',
                'status' => 'active',
                'created_at' => '2026-01-01 00:00:00',
            ]);

            expect($dto->id)
                ->toBe(1)
                ->and($dto->name)
                ->toBe('Test');
        });

        it('casts a backed enum', function () {
            $dto = ConcreteDataTransferObject::fromArray([
                'id' => 1,
                'name' => 'Test',
                'status' => 'active',
                'created_at' => '2026-01-01 00:00:00',
            ]);

            expect($dto->status)
                ->toBeInstanceOf(TestStatus::class)
                ->and($dto->status)
                ->toBe(TestStatus::Active);
        });

        it('casts a datetime string to CarbonImmutable', function () {
            $dto = ConcreteDataTransferObject::fromArray([
                'id' => 1,
                'name' => 'Test',
                'status' => 'active',
                'created_at' => '2026-01-01 00:00:00',
            ]);

            expect($dto->created_at)
                ->toBeInstanceOf(CarbonImmutable::class)
                ->and($dto->created_at->format('Y-m-d'))
                ->toBe('2026-01-01');
        });

        it('resolves null for a nullable property without casting', function () {
            $dto = ConcreteDataTransferObject::fromArray([
                'id' => 1,
                'name' => 'Test',
                'status' => 'active',
                'created_at' => '2026-01-01 00:00:00',
                'optional' => null,
            ]);

            expect($dto->optional)->toBeNull();
        });

        it('resolves null for a nullable property when property is absent', function () {
            $dto = ConcreteDataTransferObject::fromArray([
                'id' => 1,
                'name' => 'Test',
                'status' => 'active',
                'created_at' => '2026-01-01 00:00:00',
            ]);

            expect($dto->optional)->toBeNull();
        });

        it('returns the correct concrete type', function () {
            $dto = ConcreteDataTransferObject::fromArray([
                'id' => 1,
                'name' => 'Test',
                'status' => 'active',
                'created_at' => null,
            ]);

            expect($dto)->toBeInstanceOf(ConcreteDataTransferObject::class);
        });

    });
});
