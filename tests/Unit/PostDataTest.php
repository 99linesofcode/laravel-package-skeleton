<?php

declare(strict_types=1);

uses(\Lines\Skeleton\Tests\TestCase::class);

use Carbon\CarbonImmutable;
use Lines\Skeleton\Domain\DataTransferObjects\PostData;

describe('PostData', function () {
    describe('fromArray', function () {
        describe('published_at', function () {
            it('casts to CarbonImmutable', function () {
                $dto = PostData::fromArray([
                    'author_id' => 1,
                    'title' => fake()->words(asText: true),
                    'body' => fake()->paragraphs(asText: true),
                    'published_at' => '2026-01-01 00:00:00',
                ]);

                expect($dto->published_at)
                    ->toBeInstanceOf(CarbonImmutable::class)
                    ->and($dto->published_at->format('Y-m-d'))
                    ->toBe('2026-01-01');
            });

            it('casts to null when absent', function () {
                $dto = PostData::fromArray([
                    'author_id' => 1,
                    'title' => fake()->words(asText: true),
                    'body' => fake()->paragraphs(asText: true),
                ]);

                expect($dto->published_at)->toBeNull();
            });
        });

        describe('created_at', function () {
            it('casts to CarbonImmutable', function () {
                $dto = PostData::fromArray([
                    'author_id' => 1,
                    'title' => fake()->words(asText: true),
                    'body' => fake()->paragraphs(asText: true),
                    'created_at' => '2026-01-01 00:00:00',
                ]);

                expect($dto->created_at)
                    ->toBeInstanceOf(CarbonImmutable::class)
                    ->and($dto->created_at->format('Y-m-d'))
                    ->toBe('2026-01-01');
            });

            it('casts to null when absent', function () {
                $dto = PostData::fromArray([
                    'author_id' => 1,
                    'title' => fake()->words(asText: true),
                    'body' => fake()->paragraphs(asText: true),
                ]);

                expect($dto->created_at)->toBeNull();
            });
        });

        describe('updated_at', function () {
            it('casts to CarbonImmutable', function () {
                $dto = PostData::fromArray([
                    'author_id' => 1,
                    'title' => fake()->words(asText: true),
                    'body' => fake()->paragraphs(asText: true),
                    'updated_at' => '2026-01-01 00:00:00',
                ]);

                expect($dto->updated_at)
                    ->toBeInstanceOf(CarbonImmutable::class)
                    ->and($dto->updated_at->format('Y-m-d'))
                    ->toBe('2026-01-01');
            });

            it('casts to null when absent', function () {
                $dto = PostData::fromArray([
                    'author_id' => 1,
                    'title' => fake()->words(asText: true),
                    'body' => fake()->paragraphs(asText: true),
                ]);

                expect($dto->updated_at)->toBeNull();
            });
        });

        describe('deleted_at', function () {
            it('casts to CarbonImmutable', function () {
                $dto = PostData::fromArray([
                    'author_id' => 1,
                    'title' => fake()->words(asText: true),
                    'body' => fake()->paragraphs(asText: true),
                    'deleted_at' => '2026-01-01 00:00:00',
                ]);

                expect($dto->deleted_at)
                    ->toBeInstanceOf(CarbonImmutable::class)
                    ->and($dto->deleted_at->format('Y-m-d'))
                    ->toBe('2026-01-01');
            });

            it('casts to null when absent', function () {
                $dto = PostData::fromArray([
                    'author_id' => 1,
                    'title' => fake()->words(asText: true),
                    'body' => fake()->paragraphs(asText: true),
                ]);

                expect($dto->deleted_at)->toBeNull();
            });
        });
    });
});
