<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Tay Meng Phone Shop - Your one-stop shop for the latest smartphones and accessories.">
    <meta name="keywords" content="smartphones, accessories, phone shop, online shop">
    <meta name="author" content="Tay Meng">
    <title>{{ config('app.name', 'Tay Meng Phone Shop') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/homepage.css') }}">
    <link rel="stylesheet" href="{{ asset('css/wishlist.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
    {{-- <link href="{{ asset('css/about.css') }}" rel="stylesheet">
    <link href="{{ asset('css/mainstyle.css')}}" rel="stylesheet"> --}}
    @yield('head')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
        <div class="container">
            <!-- Brand Name -->
            <a class="navbar-brand" style="color:#70000E">TayMeng</a>
    
            <!-- Icons -->
            <div class="order-lg-2 nav-btns">
                <!-- Wishlist Button -->
                <button type="button" class="btn position-relative" id="wishlist-link">
                    <a class="custom-link" href="#" data-bs-toggle="modal" data-bs-target="#wishlistModal">
                        <i class="fa-solid fa-heart"></i>
                        <span class="d-none d-lg-inline">My Wishlist</span>
                        <span id="count_heart_cart" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">0</span>
                    </a>
                </button>
    
                <!-- Cart Button -->
                <button type="button" class="btn position-relative">
                    <a class="custom-link" href="#">
                        <i class="fa-solid fa-cart-shopping" id="cart-icon"></i>
                        <span class="d-none d-lg-inline">My Cart</span>
                        <span id="count_cart" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">0</span>
                    </a>
                </button>
    
                <!-- User Button -->
                    <button class="btn position-relative dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-user"></i> <span class="d-none d-lg-inline">Hello Guest</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                        <li><a class="dropdown-item" href="#"><i class="fa-solid fa-box"></i> Order History</a></li>
                        <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="fa-solid fa-user-circle"></i> My Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#"><i class="fa-solid fa-sign-out-alt"></i> Logout</a></li>
                    </ul>
                </div>
    
            <!-- Navbar Toggler Button -->
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
    
            <!-- Collapsible Navbar Links -->
            <div class="collapse navbar-collapse order-lg-1" id="navbarNav">
                <ul class="navbar-nav mx-auto text-center">
                    <li class="nav-item mx-3">
                        <a class="nav-link text-uppercase" href="{{ route('homepage') }}">Home</a>
                    </li>
                    <li class="nav-item mx-3">
                        <a class="nav-link text-uppercase" href="{{ route('faq') }}">FAQ</a>
                    </li>
                    <li class="nav-item mx-3">
                        <a class="nav-link text-uppercase" href="{{ route('aboutus') }}">About Us</a>
                    </li>
                    <li class="nav-item mx-3">
                        <a class="nav-link text-uppercase" href="{{ route('contact_us') }}">Contact Us</a>
                    </li>
                </ul>
                
                <!-- Cart Details -->
                <div class="cart">
                    <i class="fa-solid fa-xmark" id="close-card"></i>
                    <h4 class="cart-title">My Cart</h4>
                    <div class="cart-content">
                        {{-- <div class="cart-box"> </div> --}}
                    </div>
                    <div class="total">
                        <div class="total-text">Total</div>
                        <div class="total-price">25$</div>
                    </div>
                    <div>
                        <button class="btn btn-buy">Checkout</button>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    
    <div class="modal" id="wishlistModal" aria-labelledby="wishlistModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="wishlistModalLabel">My Wishlist</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul id="listwish" class="list-group">
                        <!-- Wishlist item card template -->
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Header Section -->
    <header>
        @yield('header')
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- footer -->
    <footer class="custom-footer">
        <div class="container">
            <div class="row">
                <!-- Category Column -->
                <div class="col-md-2 my-3">
                    <h5 class="fw-bold py-2" style="color:#70000E">Category</h5>
                    <ul class="list-unstyled">
                        <li class="py-2"><a href="#" class="text-decoration-none">Smartphones</a></li>
                        <li class="py-2"><a href="#" class="text-decoration-none">Accessories</a></li>
                    </ul>
                </div>
                <!-- Quick Links Column -->
                <div class="col-md-2 my-3">
                    <h5 class="fw-bold py-2" style="color:#70000E">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li class="py-2"><a href="{{ route('homepage') }}" class=" text-decoration-none">Home</a></li>
                        <li class="py-2"><a href="{{ route('aboutus') }}" class="text-decoration-none">About Us</a></li>
                        <li class="py-2"><a href="{{ route('contact_us') }}" class="text-decoration-none">Contact Us</a></li>
                        <li class="py-2"><a href="{{ route('faq') }}" class="text-decoration-none">FAQ</a></li>
                    </ul>
                </div>
                <!-- Get In Touch Column -->
                <div class="col-md-3 my-3">
                    <h5 class="fw-bold py-2" style="color:#70000E">Get In Touch</h5>
                    <ul class="list-unstyled">
                        <li class="py-2"><i class="fa-solid fa-location-pin"></i> <span class="ms-2">78Eo St13 Phsar Kondal Ti 1</span></li>
                        <li class="py-2"><i class="fa-solid fa-phone"></i> <span class="ms-2">096 841 2222</span></li>
                        <li class="py-2"><i class="fa-solid fa-envelope"></i></i> <span class="ms-2">taymeng@gmail.com</span></li>
                        <li class="py-2"><i class="fa-brands fa-telegram"></i> <span class="ms-2">t.me/taymeng</span></li>
                    </ul>
                </div>
                <!-- Follow Us Column -->
                <div class="col-md-2 my-3">
                    <h5 class="fw-bold py-2" style="color:#70000E">Follow Us</h5>
                    <ul class="list-unstyled">
                        <li class="py-2"><a href="#" class="text-decoration-none"><i class="fa-brands fa-facebook"></i> Facebook</a></li>
                        <li class="py-2"><a href="#" class="text-decoration-none"><i class="fa-solid fa-globe"></i> Taymeng.com</a></li>
                    </ul>
                </div>
                <!-- Help Column -->
                <div class="col-md-2 my-3">
                    <h5 class="fw-bold py-2" style="color:#70000E">Help</h5>
                    <ul class="list-unstyled">
                        <li class="py-2"><a href="#" class="text-decoration-none">Privacy Policy</a></li>
                        <li class="py-2"><a href="#" class="text-decoration-none">Terms of Service</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    <script src="{{ asset('js/wishlist.js') }}"></script>
    <script src="{{ asset('js/homepage.js') }}"></script>
</body>
</html>
