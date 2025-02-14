<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/customer/homepage', function () {
    return view('/customer/homepage');
});






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

Route::get('/aboutus',function(){
    return view('customer.aboutus');
})->name('aboutus');
Route::get('/faq',function(){
    return view('customer.FAQ');
})->name('faq');
Route::get('/contactus',function(){
    return view('customer.contact');
})->name('conatact_us');
Route::get('/wishlist',function(){
    return view('customer.wishlist');
})->name('wishlist');
Route::get('/homepage', [ProductController::class, 'index'])->name('homepage');

