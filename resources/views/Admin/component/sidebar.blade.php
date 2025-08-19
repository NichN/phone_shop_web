<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="{{ asset('css/mainstyle.css')}}" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css')}}" rel="stylesheet">
</head>
<body>
<div class="w3-sidebar w3-bar-block w3-collapse w3-card w3-animate-left" style="width:250px;" id="mySidebar">
    <button class="w3-bar-item w3-button w3-large w3-hide-large text-white" onclick="w3_close()">Close &times;</button>
    @if(Auth::check())
    <div class="sidebar-profile align-items-center justify-content-center py-3 mb-3" style="background: inherit; margin: 10px 0 15px 0;">
        <img src="{{ asset('image/tay_meng_logo.jpg') }}" alt="Logo" height="70" style="border-radius: 50%; object-fit: cover; width: 70px; height: 70px; border: 3px solid gold;"><br>
        <h4>Tay Meng</h4>
    </div>
    @endif

    {{-- Sidebar navigation with role-based visibility --}}
    @if(Auth::check())
        @php $roleId = Auth::user()->role_id; @endphp
    @endif

    {{-- Dashboard tab (all except Delivery) --}}
    @if(!isset($roleId) || $roleId != 3)
    <a href="{{ route('dashboard.show') }}" class="w3-bar-item w3-button dashboard-link"><i class="fa fa-television"></i> Dashboard</a>
    @endif

    {{-- All tabs except Delivery: Product, Brand, Category, Purchases, Supplier, Order, Customer, Payment, Setting, Report --}}
    @if(!isset($roleId) || $roleId != 3)
        <div class="w3-bar-item  w3-dropdown-click" onclick="toggleDropdown('productDropdown')">
            <i class="fa fa-product-hunt"></i> Product <i class="fa fa-caret-down" style="float:right;"></i>
        </div>
        <div id="productDropdown" class="w3-dropdown-content" style="display: none;">
            <a href="{{ route('products.product_index')}}" class="w3-bar-item w3-button"><i class="fa fa-list"></i> Product List</a>
            <a href="{{ route('pr_detail.index')}}" class="w3-bar-item w3-button"><i class="fa-solid fa-circle-info"></i> Product Variant</a>
            {{-- <a href="{{ route('pr_detail.add')}}" class="w3-bar-item w3-button"><i class="fa-solid fa-circle-info"></i> Add Product</a> --}}
            <a href="{{ route('color.colorlist') }}" class="w3-bar-item w3-button"><i class="fa-solid fa-palette"></i> Color</a>
            <a href="{{ route('size.index') }}" class="w3-bar-item w3-button"><i class="fa-solid fa-expand"></i> Size</a>   
        </div>
        <div class="w3-bar-item  w3-dropdown-click" onclick="toggleDropdown('brandDropdown')">
            <i class="fa fa-qrcode"></i> Brand <i class="fa fa-caret-down" style="float:right;"></i>
        </div>
        <div id="brandDropdown" class="w3-dropdown-content" style="display: none;">
            <a href="{{ route('brand.index') }}" class="w3-bar-item w3-button"><i class="fa fa-list"></i> Brand List</a>
            <a href="{{route('brand.new')}}" class="w3-bar-item w3-button"><i class="fa fa-plus-circle"></i> Add Brand</a>
        </div>
        <div class="w3-bar-item  w3-dropdown-click" onclick="toggleDropdown('categoryDropdown')">
            <i class="fa fa-qrcode"></i> Category <i class="fa fa-caret-down" style="float:right;"></i>
        </div>
        <div id="categoryDropdown" class="w3-dropdown-content" style="display: none;">
            <a href="{{ route('category.index') }}" class="w3-bar-item w3-button"><i class="fa fa-list"></i> List Categories</a>
            <a href="{{route('category.new')}}" class="w3-bar-item w3-button"><i class="fa fa-plus-circle"></i> Add Category</a>
        </div>
        <div class="w3-bar-item  w3-dropdown-click" onclick="toggleDropdown('purchasesDropdown')">
            <i class="fas fa-box"></i> Purchases <i class="fa fa-caret-down" style="float:right;"></i>
        </div>
        <div id="purchasesDropdown" class="w3-dropdown-content" style="display: none;">
            <a href="{{ route('purchase.add')}}" class="w3-bar-item w3-button"><i class="fa fa-list"></i> List Purchases</a>
            <a href="{{ route('purchase.index')}}" class="w3-bar-item w3-button"><i class="fa fa-list"></i> ADD Purchases </a>
        </div>
        <a href="{{ route('supplier.index') }}" class="w3-bar-item w3-button"><i class="fa fa-list"></i> Supplier</a>

        {{-- Delivery tab: only for Delivery role --}}
        <a href="{{ route('order_dashboard.index')}}" class="w3-bar-item w3-button order-link"><i class="fas fa-shopping-cart"></i> Order</a>
             @if(isset($roleId) && $roleId == 3)
            <div class="w3-bar-item  w3-dropdown-click" onclick="toggleDropdown('deliveryDropdown')">
                <i class="fa-solid fa-truck"></i> Delivery <i class="fa fa-caret-down" style="float:right;"></i>
            </div>
            <div id="deliveryDropdown" class="w3-dropdown-content" style="display: none;">
                <a href="{{ route('delivery_option.index') }}" class="w3-bar-item w3-button"><i class="fa-solid fa-truck"></i>Order list</a>
            </div>
        @elseif(!isset($roleId) || $roleId != 3)
            <div class="w3-bar-item  w3-dropdown-click" onclick="toggleDropdown('deliveryDropdown')">
                <i class="fa-solid fa-truck"></i> Delivery <i class="fa fa-caret-down" style="float:right;"></i>
            </div>
            <div id="deliveryDropdown" class="w3-dropdown-content" style="display: none;">
                <a href="{{ route('delivery_option.index') }}" class="w3-bar-item w3-button"><i class="fa-solid fa-truck"></i>Order list</a>
            </div>
        @endif
        <a href="{{ route('pick_up.index')}}" class="w3-bar-item w3-button order-link"><i class="fas fa-shopping-cart"></i> Pick Up</a>
        <a href="{{ route('customer_admin.index') }}" class="w3-bar-item w3-button"><i class="fa-solid fa-person"></i> Customer</a>
        <a href="{{ route('payment.index')}}" class="w3-bar-item w3-button payment-link"><i class="fas fa-money-bill-wave"></i> Payment</a>
        <div class="w3-bar-item w3-dropdown-click" onclick="toggleDropdown('setting')" style="cursor:pointer;">
        <i class="fa fa-qrcode"></i> Setting
        <i class="fa fa-caret-down" style="float:right;"></i>
        </div>
        <div id="setting" class="w3-dropdown-content" style="display: none;">
            <a href="{{ route('faq.index') }}" class="w3-bar-item w3-button"><i class="fa-solid fa-person-circle-question"></i> FAQ</a>
            <a href="{{ route('delivery.index') }}" class="w3-bar-item w3-button"><i class="fa-solid fa-truck"></i> Delivery Fee</a>
            {{-- <a href="{{ route('photo.index') }}" class="w3-bar-item w3-button"><i class="fa-solid fa-image"></i> Photo</a>   --}}
            <a href="{{ route('exchange.exchange_index')}}" class="w3-bar-item w3-button"><i class="fa-solid fa-money-bill-wave"></i> Exchange Rate</a>
        </div>
        {{-- report --}}
        <div class="w3-bar-item w3-dropdown-click" onclick="toggleDropdown('reportDropdown')">
        <i class="fa fa-qrcode"></i> Report <i class="fa fa-caret-down" style="float:right;"></i>
        </div>
        <div id="reportDropdown" class="w3-dropdown-content" style="display: none;">
            <a href="{{ route('report.product_report') }}" class="w3-bar-item w3-button">
                <i class="fa-solid fa-flag"></i> Product Report
            </a>
            <a href="{{ route('report.purchase_report') }}" class="w3-bar-item w3-button">
                <i class="fa fa-list"></i> Purchase Report
            </a>
              <a href="{{ route('report.supplier') }}" class="w3-bar-item w3-button">
                <i class="fa fa-calendar"></i> Supplier Report
            </a>
            <a href="{{ route('report.daily_sale') }}" class="w3-bar-item w3-button">
                <i class="fa-solid fa-bag-shopping"></i> Order Report
            </a>
            <a href="{{ route('report.sale_completed') }}" class="w3-bar-item w3-button">
                <i class="fa fa-calendar"></i> Sale report Completed
            </a>
    
            {{-- <a href="{{ route('report.product_chart') }}" class="w3-bar-item w3-button">
                <i class="fa fa-calendar"></i> Product Performance
            </a> --}}
            {{-- <a href="{{ route('report.profit') }}" class="w3-bar-item w3-button">
                <i class="fa fa-calendar"></i> Profit
            </a> --}}
            <a href="{{ route('report.income_expense') }}" class="w3-bar-item w3-button">
                <i class="fa fa-calendar"></i> Income Vs Expense
            </a>

        </div>
    @endif
    {{-- People tab: only for Admin (role_id==1) --}}
    @if(isset($roleId) && $roleId == 1)
        <div class="sidebar-title mt-">User</div>
        <div class="w3-bar-item  w3-dropdown-click" onclick="toggleDropdown('userDropdown')">
            <i class="fas fa-user-cog"></i> People <i class="fa fa-caret-down" style="float:right;"></i>
        </div>
        <div id="userDropdown" class="w3-dropdown-content" style="display: none;">
            <a href="{{ route('user.index') }}" class="w3-bar-item w3-button"><i class="fa fa-list"></i> List User</a>
            <a href="{{ route('role.index') }}" class="w3-bar-item w3-button"><i class="fa fa-key"></i> Role Permission</a>
        </div>
    @endif

    <form method="POST" action="{{ route('logout') }}" class="w-100 d-flex justify-content-center">
        @csrf
        <button type="submit" class="btn logout-btn" style="background-color: red; color:white;">Logout</button>
    </form>
</div>
<script>
    function w3_open() {
        document.getElementById("mySidebar").style.display = "block";
    }
    function w3_close() {
        document.getElementById("mySidebar").style.display = "none";
    }
    function toggleDropdown(id){
        var dropdown = document.getElementById(id);
        dropdown.style.display = dropdown.style.display === "block" ? "none" :"block";
    }
</script>
</body>
</html>