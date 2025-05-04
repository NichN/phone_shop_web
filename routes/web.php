<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\dashboardcontroller;
use App\Http\Controllers\categoryController;
use App\Http\Controllers\brandController;
use App\Http\Controllers\imageController;
use App\Http\Controllers\productAdminController;
use App\Http\Controllers\sizeController;
use App\Http\Controllers\suppilerController;

Route::get('/', function () {
    return view('welcome');
});
// Route::get('/customer/homepage', function () {
//     return view('customer.homepage2');
// });
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
Route::get('/productdetail',function(){
    return view('customer.productdetail');
})->name('productdetail');


Route::get('/', [HomeController::class, 'index'])->name('homepage');
Route::get('/product', [ProductController::class, 'index'])->name('product');
Route::get('/product/{id}', [HomeController::class, 'show'])->name('product.show');
Route::get('/products', [ProductController::class, 'index'])->name('product.index');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');


//Dashboard
Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/sidebar', [dashboardcontroller::class, 'index'])->name('index');
    Route::get('/', [dashboardcontroller::class, 'show'])->name('show');
});
Route::prefix('products')->name('products.')->group(function () {
    Route::get('/color', [productAdminController::class, 'index'])->name('colorlist');
    Route::post('/store', [productAdminController::class, 'storecolor'])->name('colorstore');
    Route::delete('/delete/{id}', [productAdminController ::class, 'delete'])->name('delete');
    Route::get('/edit/{id}', [productAdminController ::class, 'editcolor'])->name('editcolor');
    Route::post('/update/{id}', [productAdminController ::class, 'updatecolor'])->name('updatecolor');
});
Route::prefix('category')->name('category.')->group(function () {
    Route::get('/', [categoryController ::class, 'index'])->name('index');
    Route::get('/new', [categoryController ::class, 'show'])->name('new');
    Route::post('/store', [categoryController ::class, 'store'])->name('store');
    Route::get('/edit/{id}', [categoryController ::class, 'edit'])->name('edit');
    Route::post('/update/{id}', [categoryController ::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [categoryController ::class, 'delete'])->name('delete');
    // Route::get('/list', [ProductController::class, 'show'])->name('list');
});
Route::prefix('brand')->name('brand.')->group(function () {
    Route::get('/', [brandController::class, 'index'])->name('index');
    Route::get('/new', [brandController::class, 'show'])->name('new');
    Route::post('/store', [brandController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [brandController::class, 'edit'])->name('edit');
    Route::post('/update/{id}', [brandController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [brandController::class, 'delete'])->name('delete');
});
Route::prefix('supplier')->name('supplier.')->group(function(){
    Route::get('/', [suppilerController::class, 'index'])->name('index');
    Route::post('/store', [suppilerController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [suppilerController::class, 'edit'])->name('edit');
    Route::post('/update/{id}', [suppilerController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [suppilerController::class, 'delete'])->name('delete');
});
Route::prefix('photo')->name('photo.')->group(function(){
    Route::get('/',[imageController::class,'index'])->name('index');
    Route::post('/store',[imageController::class,'store'])->name('store');
    Route::delete('/delete/{id}',[imageController::class,'delete'])->name('delete');
    // Route::delete('/',[imageController::class,'delete'])->name('delete'); 
});
Route::prefix('size')->name('size.')->group(function(){
    Route::get('/',[sizeController::class,'index'])->name('index');
    Route::post('/store',[sizeController::class,'store'])->name('store');
});
?>