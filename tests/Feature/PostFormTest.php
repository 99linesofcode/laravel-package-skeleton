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

    it('requires body text', function () {
        livewire(CreatePost::class)
            ->fillForm([
                'body' => '',
            ])
            ->call('create')
            ->assertHasFormErrors([
                'body' => 'required',
            ]);
    });

    it('requires published_at if should_publish toggle is true', function () {
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

    it('hides published_at if should_publish toggle is false', function () {
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

    // TODO: published_at should be today or in the future when creating a new post but can be a date in the past when editing an existing post. What do?
    it('requires published_at to be equal to the current or a future date', function () {
        livewire(CreatePost::class)
            ->fillForm([
                'should_publish' => true,
                'published_at' => now()->yesterday(),
            ])
            ->call('create')
            ->assertHasFormErrors([
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
