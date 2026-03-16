<?php

use Illuminate\Support\Facades\Route;

Route::name('skeleton.')->group(function () {
    Route::livewire('/posts', 'skeleton::PostIndex')->name('post.index');
});
