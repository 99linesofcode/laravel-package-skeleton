<?php

namespace Lines\Skeleton\App\Filament\Pages;

use Filament\Resources\Pages\CreateRecord;
use Lines\Skeleton\App\Filament\Resources\PostResource;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return [
            ...$data,
            'author_id' => auth()->user()->id,
            'published_at' => $data['published_at'] ?? null,
        ];
    }
}
