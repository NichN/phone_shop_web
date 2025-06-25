<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\cartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin_user_controller;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\customer_admincontroller;
use App\Http\Controllers\categoryController;
use App\Http\Controllers\dashboardcontroller;
use App\Http\Controllers\productAdminController;
use App\Http\Controllers\brandController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\faqController;
use App\Http\Controllers\suppilerController;
use App\Http\Controllers\imageController;
use App\Http\Controllers\sizeController;
use App\Http\Controllers\product_detailCotroller;
use App\Http\Controllers\colorcontroller;
use App\Http\Controllers\purchaseController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\delivery_feeController;
use App\Http\Controllers\reportController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Order_dashboard_controller;
use App\Models\purchase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::redirect('/', '/homepage');

Route::get('/aboutus', [CustomerController::class, 'aboutUs'])->name('aboutus');


Route::get('/contactus', [CustomerController::class, 'contact'])->name('contact_us');
// by nich
Route::get('/product', [ProductController::class, 'index'])->name('product');
Route::get('/product_acessory', [ProductController::class, 'product_acessory'])->name('product_acessory');
Route::get('/products_admin', [ProductController::class, 'index'])->name('product.index');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
Route::get('/invoice', [InvoiceController::class, 'showStaticInvoice'])->name('invoice');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register');


Route::get('/login',function(){
    return view('authentication_form.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('/');
    }

    return back()->withErrors([
        'email' => 'Invalid credentials.',
    ]);
})->name('login');


Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');



Route::get('/verifyemail',function(){
    return view('authentication_form.forgetpw');
})->name('verifyemail');

Route::get('/resetpassword',function(){
    return view('authentication_form.resetpw');
})->name('resetpassword');

// Route::get('/wishlist',function(){
//     return view('customer.wishlist');
// })->name('wishlist');
// Route::get('/wishlist',function(){
//     return view('customer.wishlist');
// })->name('wishlist');

Route::get('/productdetail',function(){
    return view('customer.productdetail');
})->name('productdetail');

Route::get('/faq', [CustomerController::class, 'faq'])->name('faq');


//Dashboard
Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/sidebar', [dashboardcontroller::class, 'index'])->name('index');
    Route::get('/', [dashboardcontroller::class, 'show'])->name('show');
    Route::get('/product',[dashboardcontroller::class, 'product_count'])->name('product_count');
    Route::get('/purchase',[dashboardcontroller::class, 'purchase'])->name('purchase');
    Route::get('/customer',[dashboardcontroller::class, 'customer'])->name('customer');
    Route::get('/order',[dashboardcontroller::class,'order'])->name('order');
    Route::get('/order_dashboard', [dashboardcontroller::class, 'get_order'])->name('order_dashboard');
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

Route::prefix('delivery')->name('delivery.')->group(function(){
    Route::get('/',[delivery_feeController::class,'index'])->name('index');
    Route::post('/edit/{id}',[delivery_feeController::class,'edit_fee'])->name('edit_fee');
    Route::post('/update/{id}',[delivery_feeController::class,'update'])->name('update');
    // Route::delete('/deleteExchange',[SettingController::class,'deleteExchange'])->name('deleteExchange')->middleware('permission:manage users');
    Route::post('/store',[delivery_feeController::class,'store'])->name('store');
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
Route::prefix('report')->name('report.')->group(function(){
    Route::get('/',[reportController::class,'product_report'])->name('product_report');
    Route::get('/purchase',[reportController::class,'purchase_report'])->name('purchase_report');
});

//homepage
Route::get('/homepage', [HomeController::class, 'index'])->name('homepage');
Route::get('/product-items/{productId}', [HomeController::class, 'getProductOptions']);
Route::get('/search', [ProductController::class, 'search'])->name('search');

Route::middleware('auth')->group(function () {
    Route::get('/two-factor', [TwoFactorController::class, 'index'])->name('two_factor.index');
    Route::post('/two-factor', [TwoFactorController::class, 'verify'])->name('two_factor.verify');
});
Route::prefix('faq')->name('faq.')->group(function(){
    Route::get('/add',[faqController::class,'index'])->name('index');
    Route::post('/store',[faqController::class,'store'])->name('store');
    Route::delete('/delete/{id}',[faqController::class,'delete'])->name('delete');
    Route::post('/update/{id}',[faqController::class,'update'])->name('update');
    Route::get('/edit/{id}',[faqController::class,'edit'])->name('edit');
});

// nich
Route::prefix('checkout')->name('checkout.')->group(function(){
    Route::post('/', [CheckoutController::class, 'showCheckout'])->name('show');
    Route::post('/store',[CheckoutController::class,'storeCheckout'])->name('store');
    Route::get('/payment', [CheckoutController::class, 'processPayment'])->name('payment');
    Route::post('/payment/store', [CheckoutController::class, 'storePayment'])->name('payment_store');
    Route::get('/history', [CheckoutController::class, 'orderHistory'])->name('history');
    Route::get('/history/{id}', [CheckoutController::class, 'orderDetails'])->name('history_details');
    Route::get('/returns/{id}', [CheckoutController::class, 'returns'])->name('returns');
    Route::post('/returns/{id}', [CheckoutController::class, 'processReturn'])->name('process_return');
});


// Cart by nich
Route::post('/store-cart', [cartController::class, 'storeCart'])
    ->middleware('auth')->name('cart.store');
// Route::get('/',[cartController::class, 'index'])->middleware('auth')->name('cart.index');
Route::get('/countcart',[cartController::class,'countCart'])->name('cart.number');
Route::get('/checkcart',[cartController::class,'checkcart'])->name('cart.check');
Route::delete('/remove',[cartController::class,'remove'])->middleware('auth')->name('check');

Route::prefix('customer_admin')->name('customer_admin.')->group(function () {
    Route::get('/', [customer_admincontroller::class, 'index'])->name('index');
    Route::get('/add', [customer_admincontroller::class, 'add'])->name('new');
});

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/users', [Admin_user_controller::class, 'index'])->name('user.index');
    Route::get('/users/data', [Admin_user_controller::class, 'getData'])->name('user.data');
    Route::get('/users/create', [Admin_user_controller::class, 'create'])->name('user.new');
    Route::post('/users', [Admin_user_controller::class, 'store'])->name('user.store');
    Route::get('/users/{user}/edit', [Admin_user_controller::class, 'edit'])->name('user.edit');
    Route::put('/users/{user}', [Admin_user_controller::class, 'update'])->name('user.update');
    Route::delete('/users/{user}', [Admin_user_controller::class, 'destroy'])->name('user.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');
    Route::post('/profile/address', [ProfileController::class, 'updateAddress'])->name('profile.address');
});

Route::prefix('order_dashboard')->name('order_dashboard.')->group(function () {
    Route::get('/', [Order_dashboard_controller::class, 'index'])->name('index');
    // Route::get('/show/{id}', [OrderController::class, 'show'])->name('show');
    // Route::post('/update/{id}', [OrderController::class, 'update'])->name('update');
    // Route::delete('/delete/{id}', [OrderController::class, 'destroy'])->name('destroy');
});

?>


