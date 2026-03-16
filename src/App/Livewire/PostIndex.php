<?php

declare(strict_types=1);

namespace Lines\Skeleton\App\Livewire;

use Lines\Skeleton\Domain\Enums\PostStatus;
use Lines\Skeleton\Domain\Models\Post;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Posts')]
class PostIndex extends Component
{
    public function render()
    {
        return view('skeleton::pages.posts.index', [
            'posts' => Post::query()->whereStatus(PostStatus::Published)->get(),
        ]);
    }
}
