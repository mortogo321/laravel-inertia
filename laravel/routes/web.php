<?php

use App\Http\Controllers\DeferredPropsController;
use App\Http\Controllers\HistoryEncryptionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InfiniteScrollController;
use App\Http\Controllers\MergePropsController;
use App\Http\Controllers\PollingController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\WhenVisibleController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class);

Route::resource('posts', PostController::class);

Route::get('/deferred', DeferredPropsController::class);
Route::get('/infinite-scroll', InfiniteScrollController::class);
Route::get('/polling', PollingController::class);
Route::get('/when-visible', WhenVisibleController::class);
Route::get('/merge-props', MergePropsController::class);
Route::get('/history', HistoryEncryptionController::class);
