<?php

use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\InvoiceController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/homepage', [HomeController::class, 'index'])->name('homepage');

Route::get('/product/{id}', [HomeController::class, 'show'])->name('product.show');

Route::get('/aboutus', [CustomerController::class, 'aboutUs'])->name('aboutus');

Route::get('/faq', [CustomerController::class, 'faq'])->name('faq');

Route::get('/contactus', [CustomerController::class, 'contact'])->name('contact_us');

Route::get('/product', [ProductController::class, 'index'])->name('product');

Route::get('/products', [ProductController::class, 'index'])->name('product.index');

Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');

Route::get('/history', [HistoryController::class, 'index'])->name('history');

Route::get('/invoice', [InvoiceController::class, 'showStaticInvoice'])->name('invoice');

Route::get('/checkout', [CheckoutController::class, 'showCheckout'])->name('checkout.show');

Route::post('/checkout', [CheckoutController::class, 'processCheckout'])->name('checkout.process');

Route::get('/payment/invoice', function () {
    // Provide static data directly to the view
    return view('customer.invoice', [
        'date' => now()->format('Y-m-d H:i:s'),
        'invoice_number' => 'INV' . rand(1000, 9999),
        'customer' => 'John Doe',

        'items' => [
            ['name' => 'iPhone 16', 'price' => 1299.00],
            ['name' => 'OPPO', 'price' => 799.00],
        ],

        'total_usd' => 1299.00 + 799.00 + 1.50, // subtotal + delivery
        'total_khr' => number_format((1299.00 + 799.00 + 1.50) * 4100, 0),
        'cash' => 2100.00,
        'change_usd' => number_format(2100.00 - (1299.00 + 799.00 + 1.50), 2),
        'change_khr' => number_format((2100.00 - (1299.00 + 799.00 + 1.50)) * 4100, 0),
    ]);
})->name('payment.invoice');

Route::get('/payment/card', [CheckoutController::class, 'showCardPayment'])->name('payment.card');

Route::post('/payment/process', [CheckoutController::class, 'processPayment'])->name('payment.process');

// Route::get('/payment/card', function () {
//     return view('customer.card'); // Create this file too
// })->name('payment.card');

// Route::get('/order-success', [CheckoutController::class, 'success'])->name('checkout.success');

// auth_form
Route::get('/register',function(){
    return view('authentication_form.register');
})->name('register');

Route::get('/login',function(){
    return view('authentication_form.login');
})->name('login');

Route::get('/verifyemail',function(){
    return view('authentication_form.forgetpw');
})->name('verifyemail');

Route::get('/resetpassword',function(){
    return view('authentication_form.resetpw');
})->name('resetpassword');



Route::get('/wishlist',function(){
    return view('customer.wishlist');
})->name('wishlist');

Route::get('/productdetail',function(){
    return view('customer.productdetail');
})->name('productdetail');


