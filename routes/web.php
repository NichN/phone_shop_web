<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductDetailController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\CustomerController;


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



// Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');



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




Route::get('/profile', function () {
    return view('profile_detail.profile');
})->name('profile');

Route::get('/email', function () {
    return view('profile_detail.email');
})->name('email');

Route::get('/password', function () {
    return view('profile_detail.password');
})->name('password');

Route::get('/address', function () {
    return view('profile_detail.address');
})->name('address');

Route::get('/product1', function () {
    return view('customer.product1');
})->name('product1');

Route::put('/password/update', [PasswordController::class, 'update'])->name('password.update');

Route::post('/address', [AddressController::class, 'store'])->name('address.store');

Route::get('/productdetail', [ProductDetailController::class, 'index'])->name('productdetail');

