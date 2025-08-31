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
use App\Http\Controllers\delivery_dashboard_controller;
use App\Models\purchase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\paymentController;
use App\Http\Controllers\exchangeController;
use App\Http\Controllers\pick_upController;
use App\Models\delivery;
use App\Models\payment;
use PhpParser\Node\Expr\FuncCall;

Route::redirect('/', '/homepage');

Route::get('/homepage', [HomeController::class, 'index'])->name('homepage');
Route::get('/get-product-item-id', [HomeController::class, 'getProductItemId']);

// Authenticated homepage route
Route::get('/dashboard/homepage', [HomeController::class, 'index'])->middleware(['auth', 'twofactor'])->name('dashboard.homepage');

// Route::get('/product/{id}', [HomeController::class, 'show'])->name('product.show');

Route::get('/aboutus', [CustomerController::class, 'aboutUs'])->name('aboutus');

// Route::get('/policy')
Route::get('/contactus', [CustomerController::class, 'contact'])->name('contact_us');
// by nich
Route::get('/product', [ProductController::class, 'index'])->name('product');
Route::get('/product_acessory', [ProductController::class, 'product_acessory'])->name('product_acessory');
Route::get('/products_admin', [ProductController::class, 'index'])->name('product.index');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
Route::get('/product_category/{id}', [HomeController::class, 'getByCategory'])
    ->name('product_by_category');
Route::get('/product_brand/{id}', [ProductController::class, 'getByBrand'])->name('product_by_brand');


// Route::prefix('history')->middleware(['auth', 'twofactor'])->controller(HistoryController::class)->group(function () {
//     Route::get('/', 'index')->name('history'); 
//     Route::delete('/{id}', 'destroy')->name('history.destroy');
// });

Route::get('/invoice', [InvoiceController::class, 'showStaticInvoice'])->name('invoice');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register');
Route::get('/register/verify', [RegisterController::class, 'showVerificationForm'])->name('register.verify');
Route::post('/register/verify', [RegisterController::class, 'verifyRegistration'])->name('register.verify');
Route::post('/register/resend', [RegisterController::class, 'resendCode'])->name('register.resend');


Route::get('/login',function(){
    return view('authentication_form.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        $user = Auth::user();
        
        // Check if user has completed two-factor authentication
        if (!session('two_factor_authenticated')) {
            return redirect()->route('two_factor.index');
        }
        
        // Role-based redirect after successful two-factor verification
        if ($user->role_id == 1) {
            return redirect()->route('dashboard.show');
        } elseif ($user->role_id == 2) {
            return redirect()->route('dashboard.show'); // Staff can access dashboard
        } elseif ($user->role_id == 3) {
            return redirect()->route('delivery_option.index'); // Delivery users go to delivery dashboard
        } elseif ($user->role_id == 4) {
            return redirect()->route('homepage');
        } else {
            // For users without role or any other role, redirect to homepage
            return redirect()->route('homepage');
        }
    }

    return back()->withErrors([
        'email' => 'Invalid credentials.',
    ]);
})->name('login');


Route::post('/logout', function (Request $request) {
    // Clear two-factor session before logout
    session()->forget(['two_factor_authenticated', 'two_factor_expires_at']);
    
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    
    return redirect('/login');
})->name('logout');

// Password Reset Routes
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');

// Check if email belongs to admin (for login page)
Route::post('/check-admin-email', function (Request $request) {
    $request->validate(['email' => 'required|email']);
    
    $user = User::where('email', $request->email)->first();
    $isAdmin = $user && $user->role_id == 1;
    
    return response()->json(['is_admin' => $isAdmin]);
})->name('check.admin.email');

Route::get('/productdetail',function(){
    return view('customer.productdetail');
})->name('productdetail');

Route::get('/faq', [CustomerController::class, 'faq'])->name('faq');
Route::get('/Privacy',[CustomerController::class,'privacy'])->name('privacy');
Route::get('/terms',[CustomerController::class,'terms'])->name('terms');


//Dashboard
Route::prefix('dashboard')->middleware(['auth', 'twofactor'])->name('dashboard.')->group(function () {
    Route::get('/sidebar', function() {
        if (auth()->user()->role_id != 1 && auth()->user()->role_id != 2) abort(403, 'Unauthorized');
        return app(\App\Http\Controllers\dashboardcontroller::class)->index();
    })->name('index');
    Route::get('/', function() {
        $roleId = auth()->user()->role_id;
        if ($roleId == 3) {
            // Redirect delivery users to their specific dashboard
            return redirect()->route('delivery_option.index');
        }
        if ($roleId != 1 && $roleId != 2) abort(403, 'Unauthorized');
        return app(\App\Http\Controllers\dashboardcontroller::class)->show();
    })->name('show');
    Route::get('/product', function() {
        if (auth()->user()->role_id != 1 && auth()->user()->role_id != 2) abort(403, 'Unauthorized');
        return app(\App\Http\Controllers\dashboardcontroller::class)->product_count();
    })->name('product_count');
    Route::get('/purchase', function() {
        if (auth()->user()->role_id != 1 && auth()->user()->role_id != 2) abort(403, 'Unauthorized');
        return app(\App\Http\Controllers\dashboardcontroller::class)->purchase();
    })->name('purchase');
    Route::get('/customer', function() {
        if (auth()->user()->role_id != 1 && auth()->user()->role_id != 2) abort(403, 'Unauthorized');
        return app(\App\Http\Controllers\dashboardcontroller::class)->customer();
    })->name('customer');
     Route::get('/order', function() {
        if (auth()->user()->role_id != 1 && auth()->user()->role_id != 2) abort(403, 'Unauthorized');
        return app(\App\Http\Controllers\dashboardcontroller::class)->get_order();
    })->name('getorder');
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
    //    Route::get('/show_product/{pro_id}',[productAdminController::class,'show_product'])->name('product_items');
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
    Route::get('/show_product/{pro_id}',[product_detailCotroller::class,'show_product'])->name('product_items');
});
Route::post('/update-featured-status/{id}', [product_detailCotroller::class, 'updateFeaturedStatus']);


Route::prefix('delivery')->name('delivery.')->group(function(){
    Route::get('/',[delivery_feeController::class,'index'])->name('index');
    Route::post('/edit/{id}',[delivery_feeController::class,'edit_fee'])->name('edit_fee');
    Route::post('/update/{id}',[delivery_feeController::class,'update'])->name('update');
    // Route::delete('/deleteExchange',[exchangeController::class,'deleteExchange'])->name('deleteExchange')->middleware('permission:manage users');
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
    Route::get('/purchase_invoice/{id}',[purchaseController::class,'purchase_invoice'])->name('purchase_invoice');
});
Route::prefix('user')->name('user.')->group(function(){
    Route::get('/',[Admin_user_controller::class,'index'])->name('index');
    Route::get('/add',[Admin_user_controller::class,'add'])->name('new');
});
Route::prefix('report')->name('report.')->group(function(){
    Route::get('/',[reportController::class,'product_report'])->name('product_report');
    Route::get('/purchase',[reportController::class,'purchase_report'])->name('purchase_report');
    Route::get('/sale',[reportController::class,'daily_sale'])->name('daily_sale');
    Route::get('/supplier',[reportController::class,'supplier'])->name('supplier');
    Route::get('/supplier/{id}',[reportController::class,'supplier_view'])->name('supplier_view');
    Route::get('/sale_completed',[reportController::class,'sale_completed'])->name('sale_completed');
    Route::get('/profit',[reportController::class,'profit'])->name('profit');
    Route::get('/product-chart',[reportController::class,'product_chart'])->name('product_chart');
    Route::get('/income-expense',[reportController::class,'income_expense'])->name('income_expense');
    Route::get('/delivery',[reportController::class,'delivery'])->name('delivery');
    Route::get('/delivery/detail/{status}',[reportController::class,'delivery_detail'])->name('delivery.detail');
    // Route::get('/')
    // Route::get('/')
});

//homepage
Route::get('/homepage', [HomeController::class, 'index'])->name('homepage');

// Search routes
Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::get('/search-suggestions', [HomeController::class, 'searchSuggestions'])->name('search.suggestions');
Route::get('/product-items/{productId}', [HomeController::class, 'getOptions']);

// Test route for search functionality
Route::get('/test-search', function() {
    return response()->json([
        'message' => 'Search routes are working',
        'routes' => [
            'search' => route('search'),
            'search_suggestions' => route('search.suggestions')
        ]
    ]);
});

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

// nich->middleware(['auth', 'twofactor'])
Route::prefix('checkout')->name('checkout.')->group(function(){
    Route::post('/', [CheckoutController::class, 'showCheckout'])->name('show');
    Route::post('/store',[CheckoutController::class,'storeCheckout'])->name('store');
    Route::get('/payment/{orderId}', [CheckoutController::class, 'processPayment'])->name('payment');
    Route::post('/payment/store', [CheckoutController::class, 'storePayment'])->name('payment_store');
    Route::get('/history', [CheckoutController::class, 'orderHistory'])->name('history');
    Route::get('/history/{id}', [CheckoutController::class, 'orderDetails'])->name('history_details');
    Route::get('/returns/{id}', [CheckoutController::class, 'returns'])->name('returns');
    Route::post('/returns/{id}', [CheckoutController::class, 'processReturn'])->name('process_return');
    // Route::delete('/{id}', [CheckoutController::class, 'destroy'])->name('destroy');
    Route::post('/{order}/accept', [CheckoutController::class, 'acceptOrder'])->name('accept');
    Route::post('/{order}/decline', [CheckoutController::class, 'declineOrder'])->name('decline');
    Route::post('/verify-code', [CheckoutController::class, 'verifyCode'])->name('verify');
    Route::post('/{order}/confirm', [CheckoutController::class, 'confirmPayment'])->name('confirm');
    Route::post('/{order}/declinepayment', [CheckoutController::class, 'declinePayment'])->name('declinepay');
});
Route::prefix('order_dashboard')->name('order_dashboard.')  ->middleware('auth')->group(function () {
    Route::get('/', [Order_dashboard_controller::class, 'index'])->name('index');
    Route::get('/data', [Order_dashboard_controller::class, 'getData'])->name('data');
    Route::get('/order_total/{id}', [Order_dashboard_controller::class, 'order_detail'])->name('order_detail');
});

Route::prefix('deliveries')->middleware(['auth', 'twofactor'])->name('delivery_option.')->group(function () {
    Route::get('/', function() {
        // Only allow admin, staff, and delivery users
        if (!in_array(auth()->user()->role_id, [1, 2, 3])) abort(403, 'Unauthorized');
        return app(\App\Http\Controllers\delivery_dashboard_controller::class)->index();
    })->name('index');
    Route::get('/data', function(Request $request) {
        if (!in_array(auth()->user()->role_id, [1, 2, 3])) abort(403, 'Unauthorized');
        return app(\App\Http\Controllers\delivery_dashboard_controller::class)->getData($request);
    })->name('data');
    Route::get('/showorder/{id}', function($id) {
        if (!in_array(auth()->user()->role_id, [1, 2, 3])) abort(403, 'Unauthorized');
        return app(\App\Http\Controllers\delivery_dashboard_controller::class)->show($id);
    })->name('show');
    Route::get('/invoice/{id}', function($id) {
        if (!in_array(auth()->user()->role_id, [1, 2, 3])) abort(403, 'Unauthorized');
        return app(\App\Http\Controllers\delivery_dashboard_controller::class)->invoice($id);
    })->name('invoice');
    Route::get('/confirm/{id}', function(Request $request, $id) {
        if (!in_array(auth()->user()->role_id, [1, 2, 3])) abort(403, 'Unauthorized');
        return app(\App\Http\Controllers\delivery_dashboard_controller::class)->confirm($request, $id);
    })->name('confirm');
    Route::post('/store', function(Request $request) {
        if (!in_array(auth()->user()->role_id, [1, 2, 3])) abort(403, 'Unauthorized');
        return app(\App\Http\Controllers\delivery_dashboard_controller::class)->store($request);
    })->name('store');
    // Route::get('/show_pick_up/{id}',[delivery_dashboard_controller::class ,'show_pickup'])->name('show_pickup');
});

Route::prefix('pick_up')->name('pick_up.')->group(function (){
    Route::get('/',[pick_upController::class, 'index'])->name('index');
    Route::post('/finish-order/{id}', [pick_upController::class, 'finish'])->name('finish');

});

Route::prefix('payment')->name('payment.')->group(function () {
    Route::get('/', [paymentController::class, 'index'])->name('index');
    Route::get('/order_detail/{id}', [paymentController::class, 'order_detail'])->name('order_detail');
});
Route::prefix('exchange')->name('exchange.')->group(function(){
    Route::get('/exchnage',[exchangeController::class,'exchange_index'])->name('exchange_index');
    Route::post('/edit_exchange/{id}',[exchangeController::class,'edit_rate'])->name('edit_exchange');
    Route::post('/updateExchange/{id}',[exchangeController::class,'updateExchange'])->name('updateExchange');
    Route::post('/storeExchange',[exchangeController::class,'storeExchange'])->name('storeExchange');
});



// Cart by nich
Route::post('/store-cart', [cartController::class, 'storeCart'])->name('cart.store');
    // ->middleware('auth')->name('cart.store');
// Route::get('/',[cartController::class, 'index'])->middleware('auth')->name('cart.index');
Route::get('/countcart',[cartController::class,'countCart'])->name('cart.number');
Route::get('/checkcart',[cartController::class,'checkcart'])->name('cart.check');
Route::delete('/remove',[cartController::class,'remove'])->middleware('auth')->name('check');

Route::prefix('customer_admin')->name('customer_admin.')->group(function () {
    Route::get('/', [customer_admincontroller::class, 'index'])->name('index');
    Route::get('/add', [customer_admincontroller::class, 'add'])->name('new');
    Route::get('/show/{id}', [customer_admincontroller::class, 'show'])->name('show');
});

Route::prefix('admin')->middleware(['auth', 'twofactor'])->group(function () {
    Route::get('/users', [Admin_user_controller::class, 'index'])->name('user.index');
    Route::get('/users/data', [Admin_user_controller::class, 'getData'])->name('user.data');
    Route::get('/users/create', [Admin_user_controller::class, 'create'])->name('user.new');
    Route::post('/users', [Admin_user_controller::class, 'store'])->name('user.store');
    Route::get('/users/{user}/edit', [Admin_user_controller::class, 'edit'])->name('user.edit');
    Route::put('/users/{user}', [Admin_user_controller::class, 'update'])->name('user.update');
    Route::delete('/users/{user}', [Admin_user_controller::class, 'destroy'])->name('user.destroy');
    Route::get('/roles', [RoleController::class, 'index'])->name('role.index');
    Route::get('/roles/data', [RoleController::class, 'getData']);
    Route::post('/roles', [RoleController::class, 'store']);
    Route::get('/roles/{role}/edit', [RoleController::class, 'edit']);
    Route::put('/roles/{role}', [RoleController::class, 'update']);
    Route::delete('/roles/{role}', [RoleController::class, 'destroy']);
});

Route::middleware(['auth', 'twofactor'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');
    Route::post('/profile/address', [ProfileController::class, 'updateAddress'])->name('profile.address');
});

// Debug route to check 2FA session status (remove in production)
Route::get('/debug/2fa-status', function () {
    if (!auth()->check()) {
        return 'Not logged in';
    }
    
    $status = [
        'user' => auth()->user()->email,
        'two_factor_authenticated' => session('two_factor_authenticated'),
        'two_factor_expires_at' => session('two_factor_expires_at'),
        'current_time' => now()->toDateTimeString(),
    ];
    
    if (session('two_factor_expires_at')) {
        $expiresAt = \Carbon\Carbon::parse(session('two_factor_expires_at'));
        $status['expires_at_parsed'] = $expiresAt->toDateTimeString();
        $status['is_expired'] = now()->isAfter($expiresAt);
        $status['days_remaining'] = now()->diffInDays($expiresAt, false);
    }
    
    return response()->json($status);
})->middleware('auth')->name('debug.2fa');



?>


