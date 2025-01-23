<?php

use Illuminate\Support\Facades\Route;

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
});
Route::get('/faq',function(){
    return view('customer.FAQ');
});
Route::get('/contactus',function(){
    return view('customer.contact');
});
Route::get('/wishlist',function(){
    return view('customer.wishlist');
});
