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
        $orders = Auth::user()->orders()->with('orderItems.package')->latest()->get();
        return view('customer.orders.index', compact('orders'));
    })->name('customer.orders');
    
    Route::get('/pesanan/{order:order_number}', [App\Http\Controllers\OrderController::class, 'show'])->name('customer.orders.show');
    Route::get('/pesanan/{order:order_number}/edit', [App\Http\Controllers\OrderController::class, 'edit'])->name('customer.orders.edit');
    Route::post('/pesanan/{order}/update-detailed', [App\Http\Controllers\OrderController::class, 'updateDetailed'])->name('customer.orders.update-detailed');
    Route::post('/pesanan/{order}/cancel', [App\Http\Controllers\OrderController::class, 'cancel'])->name('customer.orders.cancel');
    Route::patch('/order-item/{item}/roster', [App\Http\Controllers\OrderController::class, 'updateRoster'])->name('customer.order-item.update-roster');
    Route::patch('/pesanan/{order}/address', [App\Http\Controllers\OrderController::class, 'updateAddress'])->name('customer.orders.update-address');
    
    Route::post('/payment/{order}/create', [App\Http\Controllers\PaymentController::class, 'createPayment'])->name('payment.create');
    Route::post('/payment/{order}/sync', [App\Http\Controllers\PaymentController::class, 'syncStatus'])->name('payment.sync');

    Route::get('/desain', [App\Http\Controllers\DesignController::class, 'index'])->name('customer.designs');
    Route::post('/desain', [App\Http\Controllers\DesignController::class, 'store'])->name('customer.designs.store');
    Route::get('/desain/{design}/edit', [App\Http\Controllers\DesignController::class, 'edit'])->name('customer.designs.edit');
    Route::patch('/desain/{design}', [App\Http\Controllers\DesignController::class, 'update'])->name('customer.designs.update');
    Route::delete('/desain/{design}', [App\Http\Controllers\DesignController::class, 'destroy'])->name('customer.designs.destroy');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Cart Routes
    Route::get('/cart', [App\Http\Controllers\CartController::class, 'index'])->name('customer.cart.index');
    Route::post('/cart/add/{package}', [App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{cartItem}', [App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [App\Http\Controllers\CartController::class, 'destroy'])->name('cart.destroy');
    Route::get('/cart/counts', [App\Http\Controllers\CartController::class, 'getCounts'])->name('cart.counts');

    // Checkout Routes
    Route::get('/checkout', [App\Http\Controllers\CheckoutController::class, 'index'])->name('customer.checkout.index');
    Route::post('/checkout/process', [App\Http\Controllers\CheckoutController::class, 'process'])->name('checkout.process');
});

// Chatbot Endpoint (Proxy & Live Chat)
Route::post('/chatbot', [\App\Http\Controllers\ChatbotController::class, 'handleChat'])->name('chatbot.handle');
Route::get('/chatbot/poll', [\App\Http\Controllers\ChatbotController::class, 'pollMessages'])->name('chatbot.poll');

require __DIR__.'/auth.php';

// Webhook for Midtrans (No Authentication Required)
Route::post('/midtrans/callback', [App\Http\Controllers\PaymentController::class, 'callback'])->name('midtrans.callback');
