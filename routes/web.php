<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\DiningController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\LongStayController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\YogaController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VillaController;
use App\Http\Controllers\WellnessController;
use App\Http\Controllers\WinterController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\AiChatController;
use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

Route::get('/currency-rates', function () {
    $currencies = ['PHP', 'USD', 'EUR', 'GBP', 'AUD', 'CAD', 'JPY', 'SGD', 'KRW', 'CNY', 'HKD', 'AED', 'INR', 'NZD'];
    $rates = \Illuminate\Support\Facades\Cache::remember('casa_currency_rates_v1', now()->addHours(12), function () use ($currencies) {
        try {
            $response = \Illuminate\Support\Facades\Http::timeout(6)->get('https://api.frankfurter.dev/v1/latest', ['base' => 'PHP']);
            if (! $response->successful()) return null;
            $data = $response->json('rates');
            if (! is_array($data) || ! isset($data['USD'])) return null;
            return array_intersect_key($data, array_flip($currencies)) + ['PHP' => 1];
        } catch (\Throwable $e) {
            return null;
        }
    });
    return response()->json(['base' => 'PHP', 'rates' => $rates ?: ['PHP' => 1], 'available' => (bool) $rates]);
})->middleware('throttle:30,1')->name('currency.rates');

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::post('/ai-chat', [AiChatController::class, 'reply'])
    ->middleware('throttle:20,1')
    ->name('ai-chat.reply');

Route::get('/villas', [VillaController::class, 'index'])->name('villas.index');
Route::get('/villas/{slug}', [VillaController::class, 'show'])->name('villas.show');
Route::get('/book', [BookingController::class, 'index'])->name('booking.index');
Route::get('/book/received', [BookingController::class, 'confirmed'])->name('booking.confirmed');
Route::get('/book/{slug}', [BookingController::class, 'index'])->name('booking.villa');
Route::post('/book/{slug}', [BookingController::class, 'store'])
    ->middleware('throttle:6,10')
    ->name('booking.store');

Route::get('/experiences', [ExperienceController::class, 'index'])->name('experiences.index');
Route::get('/wellness', [WellnessController::class, 'index'])->name('wellness.index');
Route::get('/dining', [DiningController::class, 'index'])->name('dining.index');
Route::get('/long-stay', [LongStayController::class, 'index'])->name('longstay.index');
Route::get('/winterresidenz', [WinterController::class, 'index'])->name('winter.index');
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
Route::get('/blog', [JournalController::class, 'index'])->name('journal.index');
Route::get('/blog/{slug}', [JournalController::class, 'show'])->where('slug', '[A-Za-z0-9\-_]+')->name('journal.show');
Route::post('/reviews', [ReviewController::class, 'store'])
    ->middleware('throttle:5,10')
    ->name('reviews.store');
Route::get('/yoga-reset', [YogaController::class, 'index'])->name('yoga.index');
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/special-offers', [OfferController::class, 'index'])->name('offers.index');
Route::get('/about', [AboutController::class, 'index'])->name('about.index'); 
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');

Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');



require __DIR__.'/admin.php';
