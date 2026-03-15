<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::livewire('/posts', 'skeleton::post-index');
});
