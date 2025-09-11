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
    @if(Auth::check())
        @php $roleId = Auth::user()->role_id; @endphp
    @endif

    {{-- Dashboard tab (all except Delivery-only users) --}}
    @if(!isset($roleId) || $roleId != 3)
    <a href="{{ route('dashboard.show') }}" class="w3-bar-item w3-button dashboard-link"><i class="fa fa-television"></i> Dashboard</a>
    {{-- Hide Income Vs Expense for Staff (role_id = 2) --}}
    @if(!isset($roleId) || ($roleId != 3 && $roleId != 2))
        <a href="{{ route('report.income_expense') }}" class="w3-bar-item w3-button">
                    <i class="fa-solid fa-chart-simple"></i> Income Vs Expense
                </a>
    @endif
    @endif

    {{-- Delivery Dashboard for delivery-only users --}}
    @if(isset($roleId) && $roleId == 3)
    <a href="{{ route('delivery_option.index') }}" class="w3-bar-item w3-button dashboard-link"><i class="fa fa-television"></i> Delivery Dashboard</a>
    
    {{-- Delivery-specific menu items --}}
    <div class="w3-bar-item  w3-dropdown-click" onclick="toggleDropdown('deliveryDropdown')">
        <i class="fa-solid fa-truck"></i> Delivery Orders <i class="fa fa-caret-down" style="float:right;"></i>
    </div>
    <div id="deliveryDropdown" class="w3-dropdown-content" style="display: none;">
        <a href="{{ route('delivery_option.index') }}" class="w3-bar-item w3-button"><i class="fa-solid fa-truck"></i>Order List</a>
    </div>
    @endif


    {{-- Hide Manage Product for Staff (role_id = 2) and Delivery (role_id = 3) --}}
    @if(!isset($roleId) || ($roleId != 3 && $roleId != 2))
        <div class="w3-bar-item  w3-dropdown-click" onclick="toggleDropdown('productDropdown')">
            <i class="fa fa-product-hunt"></i> Manage Product <i class="fa fa-caret-down" style="float:right;"></i>
        </div>
       <div id="productDropdown" class="w3-dropdown-content" style="display: none; min-width: 220px;">
    <!-- Product List (top-level) -->
            <a href="{{ route('products.product_index') }}" class="w3-bar-item w3-button">
                <i class="fa fa-boxes" style="width:20px;"></i> Product List
            </a>
            
            <!-- Categories and Brand (also top-level classification) -->
            <a href="{{ route('category.index') }}" class="w3-bar-item w3-button">
                <i class="fa fa-tags" style="width:20px;"></i> Categories
            </a>
            <a href="{{ route('brand.index') }}" class="w3-bar-item w3-button">
                <i class="fa fa-copyright" style="width:20px;"></i> Brand
            </a>

            <!-- Product Variants -->
            <a href="{{ route('pr_detail.index') }}" class="w3-bar-item w3-button">
                <i class="fa-solid fa-layer-group" style="width:20px;"></i> Product Variant
            </a>

            <!-- Attributes -->
            <a href="{{ route('color.colorlist') }}" class="w3-bar-item w3-button">
                <i class="fa-solid fa-palette" style="width:20px;"></i> Color
            </a>
            <a href="{{ route('size.index') }}" class="w3-bar-item w3-button">
                <i class="fa-solid fa-ruler-horizontal" style="width:20px;"></i> Size
            </a>
        </div>
    @endif

    {{-- // purchases tab - Hide for Staff (role_id = 2) and Delivery (role_id = 3) --}}
    @if(!isset($roleId) || ($roleId != 3 && $roleId != 2))
        <a href="{{ route('purchase.add') }}" class="w3-bar-item w3-button"><i class="fa fa-shopping-cart"></i> Purchases</a>
    @endif

    {{-- supplier - Hide for Staff (role_id = 2) and Delivery (role_id = 3) --}}
    @if(!isset($roleId) || ($roleId != 3 && $roleId != 2))
        <a href="{{ route('supplier.index') }}" class="w3-bar-item w3-button"><i class="fa fa-handshake"></i> Supplier</a>
    @endif

    {{-- Order - Show for Admin and Staff (role_id = 1,2) but not Delivery-only (role_id = 3) --}}
    @if(!isset($roleId) || $roleId != 3)
        <a href="{{ route('order_dashboard.index')}}" class="w3-bar-item w3-button order-link"><i class="fas fa-shopping-bag"></i> Order</a>
    @endif

    {{-- Delivery tab: show for admin and staff users only --}}
    @if(isset($roleId) && ($roleId == 1 || $roleId == 2))
            <a href="{{ route('delivery_option.index') }}" class="w3-bar-item w3-button"><i class="fa-solid fa-truck"></i> Delivery Order</a>
        {{-- <div id="deliveryDropdownAdmin" class="w3-dropdown-content" style="display: none;">
            <a href="{{ route('delivery_option.index') }}" class="w3-bar-item w3-button"><i class="fa-solid fa-truck"></i>Order list</a>
        </div> --}}
    @endif

    {{-- Pick Up - Show for Admin and Staff (role_id = 1,2) but not Delivery-only (role_id = 3) --}}
    {{-- @if(!isset($roleId) || $roleId != 3)
        <a href="{{ route('pick_up.index')}}" class="w3-bar-item w3-button order-link"><i class="fas fa-shopping-cart"></i> Pick Up</a>
    @endif --}}

    {{-- Customer - Show for Admin and Staff (role_id = 1,2) but not Delivery-only (role_id = 3) --}}
    @if(!isset($roleId) || $roleId != 3)
        <a href="{{ route('customer_admin.index') }}" class="w3-bar-item w3-button"><i class="fa-solid fa-person"></i> Customer</a>
    @endif

    {{-- Payment - Show for Admin and Staff (role_id = 1,2) but not Delivery-only (role_id = 3) --}}
    @if(!isset($roleId) || $roleId != 3)
        <a href="{{ route('payment.index')}}" class="w3-bar-item w3-button payment-link"><i class="fas fa-money-bill-wave"></i> Payment</a>
    @endif
        {{-- Hide Setting for Staff (role_id = 2) --}}
        @if(!isset($roleId) || ($roleId != 3 && $roleId != 2))
            <div class="w3-bar-item w3-dropdown-click" onclick="toggleDropdown('setting')" style="cursor:pointer;">
            <i class="fa fa-qrcode"></i> Setting
            <i class="fa fa-caret-down" style="float:right;"></i>
            </div>
            <div id="setting" class="w3-dropdown-content" style="display: none;">
                <a href="{{ route('faq.index') }}" class="w3-bar-item w3-button"><i class="fa-solid fa-person-circle-question"></i> FAQ</a>
                <a href="{{ route('delivery.index') }}" class="w3-bar-item w3-button"><i class="fa-solid fa-truck"></i> Delivery Fee</a>
                <a href="{{ route('photo.index') }}" class="w3-bar-item w3-button"><i class="fa-solid fa-image"></i> Banner </a>  
                <a href="{{ route('exchange.exchange_index')}}" class="w3-bar-item w3-button"><i class="fa-solid fa-money-bill-wave"></i> Exchange Rate</a>
            </div>
        @endif
        {{-- report - Hide for Staff (role_id = 2) --}}
        @if(!isset($roleId) || ($roleId != 3 && $roleId != 2))
            <div class="w3-bar-item w3-dropdown-click" onclick="toggleDropdown('reportDropdown')">
            <i class="fa fa-qrcode"></i> Report <i class="fa fa-caret-down" style="float:right;"></i>
            </div>
            <div id="reportDropdown" class="w3-dropdown-content" style="display: none; min-width: 230px;">
                <!-- Product-related reports -->
                <a href="{{ route('report.product_report') }}" class="w3-bar-item w3-button">
                    <i class="fa-solid fa-boxes" style="width:20px;"></i> Product Report
                </a>
                
                <!-- Purchase & Supplier -->
                <a href="{{ route('report.purchase_report') }}" class="w3-bar-item w3-button">
                    <i class="fa-solid fa-file-invoice-dollar" style="width:20px;"></i> Purchase Report
                </a>
                <a href="{{ route('report.supplier') }}" class="w3-bar-item w3-button">
                    <i class="fa-solid fa-industry" style="width:20px;"></i> Supplier Report
                </a>

                <!-- Sales Reports -->
                <a href="{{ route('report.daily_sale') }}" class="w3-bar-item w3-button">
                    <i class="fa-solid fa-chart-line" style="width:20px;"></i> Order Report
                </a>
                <a href="{{ route('report.sale_completed') }}" class="w3-bar-item w3-button">
                    <i class="fa-solid fa-check-circle" style="width:20px;"></i> Sale Completed
                </a>
                <a href="{{ route('report.delivery') }}" class="w3-bar-item w3-button">
                   <i class="fa-solid fa-truck" style="width:20px;"></i> Delivery Report
                </a>

                <!-- Optional: Uncomment if needed -->
                {{--
                <a href="{{ route('report.product_chart') }}" class="w3-bar-item w3-button">
                    <i class="fa-solid fa-chart-pie" style="width:20px;"></i> Product Performance
                </a>
                <a href="{{ route('report.profit') }}" class="w3-bar-item w3-button">
                    <i class="fa-solid fa-dollar-sign" style="width:20px;"></i> Profit Report
                </a>
                <a href="{{ route('report.income_expense') }}" class="w3-bar-item w3-button">
                    <i class="fa-solid fa-scale-balanced" style="width:20px;"></i> Income vs Expense
                </a>
                --}}
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