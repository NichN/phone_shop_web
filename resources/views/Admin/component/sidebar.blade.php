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
    <div class="sidebar-title">Menu</div>
    <a href="{{ route('dashboard.show') }}" class="w3-bar-item w3-button dashboard-link"><i class="fa fa-television"></i> Dashboard</a>
    <div class="w3-bar-item  w3-dropdown-click" onclick="toggleDropdown('productDropdown')">
        <i class="fa fa-product-hunt"></i> Product <i class="fa fa-caret-down" style="margin-left: 113px"></i>
    </div><div id="productDropdown" class="w3-dropdown-content" style="display: none;">
        <a href="{{ route('products.product_index')}}" class="w3-bar-item w3-button"><i class="fa fa-list"></i> Product List</a>
        <a href="{{ route('pr_detail.index')}}" class="w3-bar-item w3-button"><i class="fa-solid fa-circle-info"></i> Product Detail</a>
        <a href="{{ route('pr_detail.add')}}" class="w3-bar-item w3-button"><i class="fa-solid fa-circle-info"></i> Add Product</a>
        <a href="{{ route('color.colorlist') }}" class="w3-bar-item w3-button"><i class="fa-solid fa-palette"></i> Color</a>
        <a href="{{ route('size.index') }}" class="w3-bar-item w3-button"><i class="fa-solid fa-expand"></i> Size</a>
        <a href="{{ route('photo.index') }}" class="w3-bar-item w3-button"><i class="fa-solid fa-image"></i> Photo</a>     
    </div>
    <div class="w3-bar-item  w3-dropdown-click" onclick="toggleDropdown('brandDropdown')">
        <i class="fa fa-qrcode"></i> Brand <i class="fa fa-caret-down" style="margin-left: 120px"></i>
    </div>
    <div id="brandDropdown" class="w3-dropdown-content" style="display: none;">
        <a href="{{ route('brand.index') }}" class="w3-bar-item w3-button"><i class="fa fa-list"></i> Brand List</a>
        <a href="{{route('brand.new')}}" class="w3-bar-item w3-button"><i class="fa fa-plus-circle"></i> Add Brand</a>
    </div>
    <div class="w3-bar-item  w3-dropdown-click" onclick="toggleDropdown('categoryDropdown')">
        <i class="fa fa-qrcode"></i> Category <i class="fa fa-caret-down" style="margin-left: 100px"></i>
    </div>
    <div id="categoryDropdown" class="w3-dropdown-content" style="display: none;">
        <a href="{{ route('category.index') }}" class="w3-bar-item w3-button"><i class="fa fa-list"></i> List Categories</a>
        <a href="{{route('category.new')}}" class="w3-bar-item w3-button"><i class="fa fa-plus-circle"></i> Add Category</a>
    </div>
    <div class="w3-bar-item  w3-dropdown-click" onclick="toggleDropdown('purchasesDropdown')">
        <i class="fas fa-box"></i> Purchases <i class="fa fa-caret-down" style="margin-left: 85px"></i>
    </div>
    <div id="purchasesDropdown" class="w3-dropdown-content" style="display: none;">
        <a href="{{ route('purchase.add')}}" class="w3-bar-item w3-button"><i class="fa fa-list"></i> List Purchases</a>
        <a href="{{ route('purchase.index')}}" class="w3-bar-item w3-button"><i class="fa fa-list"></i> ADD Purchases </a>
    </div>
    <div class="w3-bar-item  w3-dropdown-click" onclick="toggleDropdown('supplierDropdown')">
        <i class="fa fa-qrcode"></i> Supplier <i class="fa fa-caret-down" style="margin-left: 100px"></i>
    </div>
    <a href="#" class="w3-bar-item w3-button stock-link"><i class="fas fa-box"></i> Delivery</a>
    <div id="supplierDropdown" class="w3-dropdown-content" style="display: none;">
        <a href="{{ route('supplier.index') }}" class="w3-bar-item w3-button"><i class="fa fa-list"></i> List Supplier</a>
    </div>
    <a href="#" class="w3-bar-item w3-button order-link"><i class="fas fa-shopping-cart"></i> Order</a>
    <a href="#" class="w3-bar-item w3-button customer-link"><i class="fas fa-user"></i> Customer</a>
    <a href="#" class="w3-bar-item w3-button payment-link"><i class="fas fa-money-bill-wave"></i> Payment</a>
    <a href="#" class="w3-bar-item w3-button report-link"><i class="fas fa-file-alt"></i> Report</a>

    <div class="sidebar-title mt-">Settings</div>
    <a href="{{ route('user.index') }}" class="w3-bar-item w3-button user-link"><i class="fas fa-user-cog"></i> Manage User</a>
    <button type="button" class="btn btn-warning logout-btn">Logout</button>
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