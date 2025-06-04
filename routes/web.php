<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductDetailController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\categoryController;
use App\Http\Controllers\dashboardcontroller;
use App\Http\Controllers\productAdminController;
use App\Http\Controllers\brandController;
use App\Http\Controllers\suppilerController;
use App\Http\Controllers\imageController;
use App\Http\Controllers\sizeController;
use App\Http\Controllers\product_detailCotroller;
use App\Http\Controllers\colorcontroller;
use App\Http\Controllers\purchaseController;
use App\Http\Controllers\Admin_user_controller;
use App\Http\Controllers\TwoFactorController;
use App\Models\purchase;

Route::get('/', function () {
    return view('welcome');
});
// Route::get('/customer/homepage', function () {
//     return view('customer.homepage2');
// });

Route::get('/homepage', [HomeController::class, 'index'])->name('homepage');

Route::get('/product/{id}', [HomeController::class, 'show'])->name('product.show');

Route::get('/aboutus', [CustomerController::class, 'aboutUs'])->name('aboutus');

Route::get('/faq', [CustomerController::class, 'faq'])->name('faq');

Route::get('/contactus', [CustomerController::class, 'contact'])->name('contact_us');
// by nich
Route::get('/product', [ProductController::class, 'index'])->name('product');
Route::get('/product_acessory', [ProductController::class, 'product_acessory'])->name('product_acessory');
Route::get('/products_admin', [ProductController::class, 'index'])->name('product.index');
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

//Dashboard
Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/sidebar', [dashboardcontroller::class, 'index'])->name('index');
    Route::get('/', [dashboardcontroller::class, 'show'])->name('show');
    Route::get('/product',[dashboardcontroller::class, 'product_count'])->name('product_count');
});
Route::prefix('products')->name('products.')->group(function () {
    Route::get('/color', [productAdminController::class, 'index'])->name('colorlist');
    Route::post('/store', [productAdminController::class, 'storecolor'])->name('colorstore');
    Route::delete('/delete/{id}', [productAdminController ::class, 'delete'])->name('delete');
    Route::get('/edit/{id}', [productAdminController ::class, 'editcolor'])->name('editcolor');
    Route::post('/update/{id}', [productAdminController ::class, 'updatecolor'])->name('updatecolor');
    Route::get('/',[productAdminController::class,'productindex'])->name('product_index');
    Route::get('/show',[productAdminController::class,'productshow'])->name('productlist');
    Route::post('/storeproduct',[productAdminController::class,'productstore'])->name('productstore');
    // Route::get('/showbrand/{id}', [productAdminController::class, 'getBrand'])->name('show');
    Route::get('edit/{id}',[productAdminController::class,'editproduct'])->name('product_edit');
    Route::get('update/{id}',[productAdminController::class,'updateproduct'])->name('product_update');
     Route::delete('/delete/{id}', [productAdminController ::class, 'deleteproduct'])->name('deleteproduct');
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
Route::prefix('colors')->name('color.')->group(function () {
    Route::get('/color', [colorcontroller::class, 'index'])->name('colorlist');
    Route::post('/store', [colorcontroller::class, 'storecolor'])->name('colorstore');
    Route::delete('/delete/{id}', [colorcontroller ::class, 'delete'])->name('delete');
    Route::get('/edit/{id}', [colorcontroller ::class, 'editcolor'])->name('editcolor');
    Route::post('/update/{id}', [colorcontroller ::class, 'updatecolor'])->name('updatecolor');
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
    Route::get('/edit/{id}', [sizeController::class, 'edit'])->name('edit');
    Route::post('/update/{id}', [sizeController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [sizeController::class, 'delete'])->name('delete');
});
Route::prefix('product_detail')->name('pr_detail.')->group(function(){
    Route::get('/',[product_detailCotroller::class,'index'])->name('index');
    Route::get('/add',[product_detailCotroller::class,'addproduct'])->name('add');
    Route::get('/search-product', [product_detailCotroller::class, 'search'])->name('search-product');
    Route::get('/getproduct/{id}',[productAdminController::class,'getproduct'])->name('get_product');
    Route::post('/store',[product_detailCotroller::class,'store'])->name('store');
    Route::get('/edit/{id}',[product_detailCotroller::class,'edit'])->name('edit');
    Route::post('/update/{id}',[product_detailCotroller::class,'update'])->name('update');
    Route::delete('/delete/{id}',[product_detailCotroller::class,'delete'])->name('delete');
    Route::get('/show_product/{pro_id}',[productAdminController::class,'show_product'])->name('product_items');
});
Route::prefix('purchase')->name('purchase.')->group(function(){
    Route::get('/',[purchaseController::class,'index'])->name('index');
    Route::get('/payment',[purchaseController::class,'payment'])->name('payment');
    Route::get('/add',[purchaseController::class,'show'])->name('add');
    Route::get('/get/{id}',[product_detailCotroller::class,'get_pr_item'])->name('get_product_item');
    Route::get('/search-product_it', [purchaseController::class, 'search'])->name('search-product');
    Route::post('/update/{id}',[purchaseController::class,'updatepayment'])->name('updatepayment');
    Route::post('/store',[purchaseController::class,'store'])->name('store');
    Route::delete('/delete/{id}',[purchaseController::class,'destroy'])->name('destroy');
    Route::post('/storepayment',[purchaseController::class,'storepayment'])->name('storepayment');
    Route::get('/showinvoice/{id}',[purchaseController::class,'showinvoice'])->name('invoiceshow');
    Route::delete('/deletepurchase/{id}',[purchaseController::class,'delete_purchases'])->name('delete_purchases');
    Route::get('/addpayment/{id}',[purchaseController::class,'addpayment'])->name('addpayment');
});
Route::prefix('user')->name('user.')->group(function(){
    Route::get('/',[Admin_user_controller::class,'index'])->name('index');
    Route::get('/add',[Admin_user_controller::class,'add'])->name('new');
});


Route::middleware('auth')->group(function () {
    Route::get('/two-factor', [TwoFactorController::class, 'index'])->name('two_factor.index');
    Route::post('/two-factor', [TwoFactorController::class, 'verify'])->name('two_factor.verify');
});
?>
