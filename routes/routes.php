<?php

use Illuminate\Support\Facades\Route;

Route::name('skeleton.')->middleware('web')->group(function () {
    Route::livewire('/posts', 'skeleton::PostIndex')->name('post.index');
});
