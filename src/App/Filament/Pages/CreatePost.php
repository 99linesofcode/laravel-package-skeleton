<?php

namespace Lines\Skeleton\App\Filament\Pages;

use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Lines\Skeleton\App\Filament\Resources\PostResource;
use Lines\Skeleton\Domain\Actions\CreatePostAction;
use Lines\Skeleton\Domain\DataTransferObjects\PostData;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): Model
    {
        return app(CreatePostAction::class)(new PostData(
            ...$data,
            author_id: auth()->user()->id,
        ));
    }
}
