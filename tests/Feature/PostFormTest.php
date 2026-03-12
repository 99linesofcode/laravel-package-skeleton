<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use Illuminate\Support\Str;
use Lines\Skeleton\App\Filament\Pages\CreatePost;
use Lines\Skeleton\App\Filament\Pages\EditPost;
use Lines\Skeleton\Domain\Models\Post;

use function Pest\Livewire\livewire;

describe('PostForm', function () {
    beforeEach(function () {
        Filament::setCurrentPanel(
            Filament::getPanel('admin')
        );
    });

    describe('title', function () {
        it('requires title to be between 8 and 128 characters', function () {
            livewire(CreatePost::class)
                ->fillForm([
                    'title' => '',
                ])
                ->call('create')
                ->assertHasFormErrors([
                    'title' => 'required',
                ])
                ->fillForm([
                    'title' => Str::random(7),
                ])
                ->call('create')
                ->assertHasFormErrors([
                    'title' => 'min',
                ])
                ->fillForm([
                    'title' => Str::random(129),
                ])
                ->call('create')
                ->assertHasFormErrors([
                    'title' => 'max',
                ]);
        });
    });

    describe('body', function () {
        it('is required', function () {
            livewire(CreatePost::class)
                ->fillForm([
                    'body' => '',
                ])
                ->call('create')
                ->assertHasFormErrors([
                    'body' => 'required',
                ]);
        });
    });

    describe('should_publish', function () {
        describe('when published_at is unset', function () {
            it('sets published_at to current date', function () {
                $original = Post::factory()->create();

                livewire(EditPost::class, [
                    'record' => $original->id,
                ])
                    ->assertSchemaStateSet([
                        'should_publish' => false,
                        'published_at' => null,
                    ])
                    ->fillForm([
                        'should_publish' => true,
                    ])
                    ->assertSchemaStateSet([
                        'published_at' => now()->toDateString(),
                    ]);
            });
        });

        describe('when published_at is set', function () {
            it('sets published_at correctly', function () {
                $original = Post::factory()->published()->create();
                $published_at = $original->published_at->toDateString();

                expect($published_at)->not()->toEqual(now()->toDateString());

                livewire(EditPost::class, [
                    'record' => $original->id,
                ])
                    ->assertSchemaStateSet([
                        'should_publish' => true,
                        'published_at' => $published_at,
                    ]);
            });
        });

        it('toggles the published_at field', function () {
            livewire(CreatePost::class)
                ->fillForm([
                    'should_publish' => false,
                ])
                ->assertSchemaComponentHidden('published_at')
                ->fillForm([
                    'should_publish' => true,
                ])
                ->assertSchemaComponentVisible('published_at');
        });

        it('toggles when published_at is set', function () {
            livewire(CreatePost::class)
                ->fillForm([
                    'should_publish' => false,
                ])
                ->assertSchemaComponentHidden('published_at')
                ->fillForm([
                    'published_at' => now()->toDateString(),
                ])
                ->assertSchemaComponentVisible('should_publish');
        });
    });

    describe('published_at', function () {
        it('is required if should_publish is true', function () {
            livewire(CreatePost::class)
                ->fillForm([
                    'should_publish' => false,
                ])
                ->call('create')
                ->assertHasNoFormErrors([
                    'published_at' => 'required',
                ])
                ->fillForm([
                    'should_publish' => true,
                    'published_at' => '',
                ])
                ->call('create')
                ->assertHasFormErrors([
                    'published_at' => 'required',
                ]);
        });

        it('can be any date, past or future', function () {
            livewire(CreatePost::class)
                ->fillForm([
                    'should_publish' => true,
                    'published_at' => now()->yesterday(),
                ])
                ->call('create')
                ->assertHasNoFormErrors([
                    'published_at' => 'after_or_equal',
                ])
                ->fillForm([
                    'should_publish' => true,
                    'published_at' => now(),
                ])
                ->call('create')
                ->assertHasNoFormErrors([
                    'published_at' => 'after_or_equal',
                ]);
        });
    });
});
