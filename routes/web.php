<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\DiningController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\LongStayController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VillaController;
use App\Http\Controllers\WellnessController;
use App\Http\Controllers\AboutController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/villas', [VillaController::class, 'index'])->name('villas.index');
Route::get('/villas/{slug}', [VillaController::class, 'show'])->name('villas.show');
Route::get('/experiences', [ExperienceController::class, 'index'])->name('experiences.index');
Route::get('/wellness', [WellnessController::class, 'index'])->name('wellness.index');
Route::get('/dining', [DiningController::class, 'index'])->name('dining.index');
Route::get('/long-stay', [LongStayController::class, 'index'])->name('longstay.index');
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/special-offers', [OfferController::class, 'index'])->name('offers.index');
Route::get('/about', [HomeController::class, 'about'])->name('about.index'); 
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');

Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');

// These sections live on the home page for now, so the short URLs
// (bookmarks, emails, future menu links) land on the right section.
Route::redirect('/about', '/#about')->name('about.index');
Route::redirect('/contact', '/#contact')->name('contact.index');

require __DIR__.'/admin.php';
