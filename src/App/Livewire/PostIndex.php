<?php

declare(strict_types=1);

namespace Lines\Skeleton\App\Livewire;

use Livewire\Component;

class PostIndex extends Component
{
    public ?string $title = null;

    public ?string $content = null;

    public function save(): void
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // TODO: implement CreatePostAction here

        $this->reset(['title', 'content']);

        session()->flash('success', 'Post created successfully!');
    }

    public function render()
    {
        return view('skeleton::pages.post-index');
    }
}
