<?php

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/{slug}', [CatalogController::class, 'show'])->name('catalog.show');

// Google Auth Routes
Route::get('/auth/google', [\App\Http\Controllers\Auth\SocialiteController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [\App\Http\Controllers\Auth\SocialiteController::class, 'handleGoogleCallback']);

Route::get('/customizer', function () {
    return view('customizer');
})->name('customizer');

Route::view('/visi-misi', 'visi-misi')->name('visi-misi');
Route::view('/portfolio', 'portfolio')->name('portfolio');
Route::view('/privacy-policy', 'privacy')->name('privacy');
Route::view('/terms-of-service', 'terms')->name('terms');

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
    
    // Return Request Route
    Route::post('/pesanan/{order}/return', [App\Http\Controllers\ReturnRequestController::class, 'store'])->name('customer.orders.return');
    
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

    // Shipping Routes (RajaOngkir)
    Route::get('/shipping/provinces', [App\Http\Controllers\ShippingController::class, 'getProvinces'])->name('shipping.provinces');
    Route::get('/shipping/cities/{province}', [App\Http\Controllers\ShippingController::class, 'getCities'])->name('shipping.cities');
    Route::post('/shipping/cost', [App\Http\Controllers\ShippingController::class, 'calculateCost'])->name('shipping.cost');
    Route::post('/shipping/auto-calculate', [App\Http\Controllers\ShippingController::class, 'autoCalculate'])->name('shipping.auto-calculate');

    // User Address Routes
    Route::get('/user/addresses', [App\Http\Controllers\UserAddressController::class, 'index'])->name('user.addresses.index');
    Route::post('/user/addresses', [App\Http\Controllers\UserAddressController::class, 'store'])->name('user.addresses.store');
    Route::post('/user/addresses/{address}/set-default', [App\Http\Controllers\UserAddressController::class, 'setDefault'])->name('user.addresses.set-default');
    Route::delete('/user/addresses/{address}', [App\Http\Controllers\UserAddressController::class, 'destroy'])->name('user.addresses.destroy');

    // Checkout Routes
    Route::get('/checkout', [App\Http\Controllers\CheckoutController::class, 'index'])->name('customer.checkout.index');
    Route::post('/checkout/process', [App\Http\Controllers\CheckoutController::class, 'process'])->name('checkout.process');

    // Notification Routes
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
});

Route::get('/media/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);
    if (!file_exists($fullPath)) {
        abort(404);
    }
    
    $mimeType = \Illuminate\Support\Facades\File::mimeType($fullPath);
    return response()->file($fullPath, ['Content-Type' => $mimeType]);
})->where('path', '.*')->name('media.file');

// Temporary route to clear cache
Route::get('/clear-cache', function() {
    $configCache = base_path('bootstrap/cache/config.php');
    if (file_exists($configCache)) {
        unlink($configCache);
        return 'Cache config dihapus!';
    }
    return 'Cache config sudah bersih!';
});

// Keep the old designs route just in case it's hardcoded somewhere
Route::get('/images/designs/{filename}', function ($filename) {
    $path = storage_path('app/public/designs/' . $filename);
    if (!file_exists($path)) {
        abort(404);
    }
    return response()->file($path);
})->name('images.designs');

// Chatbot Endpoint (Proxy & Live Chat)
Route::post('/chatbot', [\App\Http\Controllers\ChatbotController::class, 'handleChat'])->name('chatbot.handle');
Route::get('/chatbot/poll', [\App\Http\Controllers\ChatbotController::class, 'pollMessages'])->name('chatbot.poll');

require __DIR__.'/auth.php';

// Webhook for Midtrans (No Authentication Required)
Route::post('/midtrans/callback', [App\Http\Controllers\PaymentController::class, 'callback'])->name('midtrans.callback');
