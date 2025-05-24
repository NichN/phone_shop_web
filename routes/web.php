<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductDetailController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\TwoFactorController;

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

Route::get('/products/{id}', [ProductController::class, 'show'])->name('product.show');

Route::get('/history', [HistoryController::class, 'index'])->name('history');

Route::get('/invoice/{id}', [OrderController::class, 'showInvoice'])->name('invoice.show');

Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');






// auth_form
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

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


Route::get('/productdetail', [ProductDetailController::class, 'index'])->name('productdetail');



Route::middleware('auth')->group(function () {
    Route::get('/two-factor', [TwoFactorController::class, 'index'])->name('two_factor.index');
    Route::post('/two-factor', [TwoFactorController::class, 'verify'])->name('two_factor.verify');
});

