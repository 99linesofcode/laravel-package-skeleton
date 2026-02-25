<?php

namespace Lines\Skeleton\App\Filament\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;
use Lines\Skeleton\App\Filament\Resources\PostResource;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return [
            ...$data,
            'should_publish' => $data['published_at'] ? true : false,
            'published_at' => $data['published_at'] ??= now()->toDateString()
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return [
            ...$data,
            'author_id' => auth()->user()->id,
            'published_at' => $data['published_at'] ?? null,
        ];
    }
}
