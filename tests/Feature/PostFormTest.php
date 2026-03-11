<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use Illuminate\Support\Str;
use Lines\Skeleton\App\Filament\Pages\CreatePost;

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

    describe('published_at', function () {
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
    });

    describe('published_at', function () {
        it('is required if should_publish toggle is true', function () {
            livewire(CreatePost::class)
                ->fillForm([
                    'should_publish' => false,
                    'published_at' => '',
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

        it('defaults to the current date', function () {
            livewire(CreatePost::class)
                ->fillForm([
                    'should_publish' => true,
                ])
                ->assertSchemaStateSet([
                    'published_at' => now()->toDateString(),
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
