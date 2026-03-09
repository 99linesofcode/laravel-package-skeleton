<?php

namespace Lines\Skeleton\App\Filament\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Lines\Skeleton\App\Filament\Resources\PostResource;
use Lines\Skeleton\Domain\Actions\UpdatePostAction;
use Lines\Skeleton\Domain\DataTransferObjects\PostData;

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
            'published_at' => $data['published_at'] ??= now()->toDateString(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return app(UpdatePostAction::class)(new PostData(
            ...($data + $record->toArray())
        ));
    }
}
