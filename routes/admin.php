<?php

use App\Http\Controllers\Admin\AiBrainController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {

    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'create'])->name('login');
        Route::post('login', [AuthController::class, 'store']);
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('logout', [AuthController::class, 'destroy'])->name('logout');

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('content', ContentController::class)
            ->except(['show'])
            ->parameters(['content' => 'content']);

        // AI Brain: teach the website assistant, manage what it knows, see what guests ask, test it.
        Route::prefix('ai-brain')->name('ai-brain.')->controller(AiBrainController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('entries', 'storeEntry')->name('entries.store');
            Route::put('entries/{id}', 'updateEntry')->name('entries.update')->where('id', '[a-z0-9]+');
            Route::post('entries/{id}/toggle', 'toggleEntry')->name('entries.toggle')->where('id', '[a-z0-9]+');
            Route::delete('entries/{id}', 'destroyEntry')->name('entries.destroy')->where('id', '[a-z0-9]+');
            Route::post('questions/{id}/done', 'questionDone')->name('questions.done')->where('id', '[a-z0-9]+');
            Route::post('teach', 'teach')->name('teach')->middleware('throttle:20,1');
            Route::post('drafts', 'saveDrafts')->name('drafts');
            Route::post('test', 'test')->name('test')->middleware('throttle:30,1');
            Route::post('connection', 'saveConnection')->name('connection.save');
            Route::post('connection/check', 'checkConnection')->name('connection.check')->middleware('throttle:10,1');
            Route::delete('connection', 'removeConnection')->name('connection.remove');
        });

        // Reviews written by guests on the website: show, hide or delete them.
        Route::resource('reviews', AdminReviewController::class)
            ->only(['index', 'update', 'destroy']);
    });

});
