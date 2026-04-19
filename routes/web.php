<?php

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/{slug}', [CatalogController::class, 'show'])->name('catalog.show');

Route::get('/customizer', function () {
    return view('customizer');
})->name('customizer');

Route::view('/visi-misi', 'visi-misi')->name('visi-misi');
Route::view('/portfolio', 'portfolio')->name('portfolio');

Route::get('/dashboard', function () {
    return redirect('/');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/pesanan', function () {
        return view('customer.orders.index');
    })->name('customer.orders');

    Route::get('/desain', function () {
        return view('customer.designs.index');
    })->name('customer.designs');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Cart Routes
    Route::get('/cart', [App\Http\Controllers\CartController::class, 'index'])->name('customer.cart.index');
    Route::post('/cart/add/{package}', [App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
    Route::get('/cart/counts', [App\Http\Controllers\CartController::class, 'getCounts'])->name('cart.counts');
});

require __DIR__.'/auth.php';
