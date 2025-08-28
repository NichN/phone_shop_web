<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Tay Meng Phone Shop - Your one-stop shop for the latest smartphones and accessories.">
    <meta name="keywords" content="smartphones, accessories, phone shop, online shop">
    <meta name="author" content="Tay Meng">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('Tay Meng Phone Shop') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/homepage.css') }}">
    <link rel="stylesheet" href="{{ asset('css/wishlist.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footerheader.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    @yield('head')
    <style>
        .custom-footer {
            font-size: 0.85rem;
        }

        /* Order History Modal Styles */
        #orderHistoryModal .modal-content {
            border-radius: 10px;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        #orderHistoryModal .modal-header {
            border-bottom: 1px solid #eee;
            padding: 1.5rem;
        }

        #orderHistoryModal .modal-title {
            font-weight: 600;
        }

        #orderHistoryModal .modal-body {
            padding: 1.5rem;
        }

        #orderHistoryModal .form-control {
            padding: 10px 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        #orderHistoryModal .form-control:focus {
            border-color: #70000E;
            box-shadow: 0 0 0 0.25rem rgba(112, 0, 14, 0.1);
        }

        #close-card {
            position: absolute;
            top: 15px;
            left: 15px;
            z-index: 1000;
        }

        #orderHistoryModal label {
            font-weight: 500;
            margin-bottom: 8px;
        }

        /* Minimalist Search Modal Styles */
        #searchModal .modal-content {
            border: none;
            border-radius: 12px;
            background: white;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        #searchModal .modal-dialog {
            max-width: 600px;
            margin: 1rem auto;
        }

        /* Search Header */
        .search-header-content {
            flex: 1;
        }

        .search-input-container {
            position: relative;
            background: #f8f9fa;
            border-radius: 8px;
            padding: 0.75rem;
            transition: all 0.2s ease;
        }

        .search-input-container:focus-within {
            background: white;
            box-shadow: 0 0 0 2px #70000E;
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            font-size: 1rem;
        }

        .search-input {
            border: none;
            background: transparent;
            padding: 0.5rem 0.5rem 0.5rem 2.5rem;
            font-size: 1rem;
            width: 100%;
            outline: none;
        }

        .search-input::placeholder {
            color: #adb5bd;
        }

        /* Search Suggestions */
        .search-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border-radius: 8px;
            margin-top: 0.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
            border: 1px solid #e9ecef;
        }

        .suggestion-item {
            padding: 0.75rem 1rem;
            cursor: pointer;
            border-bottom: 1px solid #f8f9fa;
            transition: all 0.15s ease;
            font-size: 0.9rem;
        }

        .suggestion-item:hover {
            background: #f8f9fa;
        }

        .suggestion-item:last-child {
            border-bottom: none;
        }

        /* Quick Categories */
        .quick-categories {
            background: white;
        }

        .search-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .search-tag {
            background: #f8f9fa;
            color: #495057;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.9rem;
            border: 1px solid #e9ecef;
        }

        .search-tag:hover {
            background: #70000E;
            color: white;
            border-color: #70000E;
        }

        /* Search Results Section */
        .search-results-section {
            background: white;
        }

        .results-header {
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }

        .results-header h6 {
            color: #6c757d;
            font-weight: 500;
        }

        /* Loading State */
        .loading-state {
            background: white;
        }

        .loading-spinner {
            width: 24px;
            height: 24px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #70000E;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Results List */
        .results-list {
            background: white;
        }

        .result-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #f8f9fa;
            transition: all 0.2s ease;
        }

        .result-item:hover {
            background: #f8f9fa;
        }

        .result-item:last-child {
            border-bottom: none;
        }

        .result-image {
            width: 50px;
            height: 50px;
            background: #f8f9fa;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.5rem;
        }

        .result-info {
            flex: 1;
        }

        .result-info h6 {
            color: #212529;
            font-weight: 500;
            margin-bottom: 0.25rem;
            font-size: 0.95rem;
        }

        .result-category {
            color: #6c757d;
            font-size: 0.8rem;
            margin-bottom: 0.25rem;
        }

        .result-rating {
            color: #ffc107;
            font-size: 0.8rem;
            margin-bottom: 0.25rem;
        }

        .result-details {
            line-height: 1.3;
        }

        .result-price {
            color: #70000E;
            font-weight: 600;
            font-size: 1rem;
        }

        /* No Results */
        .no-results {
            background: white;
        }

        .no-results i {
            color: #dee2e6;
        }

        /* Modal Animation */
        #searchModal .modal-dialog {
            transform: scale(0.95);
            opacity: 0;
            transition: all 0.2s ease;
        }

        #searchModal.show .modal-dialog {
            transform: scale(1);
            opacity: 1;
        }

        /* Search Icon Button */
        .search-icon-btn {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 50%;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .search-icon-btn:hover {
            background: linear-gradient(135deg, #70000E 0%, #8a0012 100%);
            color: white !important;
            transform: scale(1.1);
            box-shadow: 0 4px 15px rgba(112, 0, 14, 0.3);
        }

        .search-icon-btn:focus {
            box-shadow: 0 0 0 0.25rem rgba(112, 0, 14, 0.2);
        }

        /* Modern Minimalist Search Modal - Nike Inspired */
        #minimalistSearchModal {
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(8px);
        }

        #minimalistSearchModal .modal-content {
            border: none;
            border-radius: 20px;
            background: #ffffff;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            max-height: 80vh;
        }

        #minimalistSearchModal .modal-dialog {
            margin: 5rem auto;
            max-width: 650px;
            width: 90%;
        }

        #minimalistSearchModal .modal-header {
            border: none;
            padding: 0;
            background: transparent;
            position: absolute;
            top: 0;
            right: 0;
            z-index: 10;
        }

        #minimalistSearchModal .modal-body {
            padding: 0;
            max-height: 75vh;
            display: flex;
            flex-direction: column;
        }

        /* Search Header Section */
        .modern-search-header {
            padding: 2rem 2rem 1.5rem 2rem;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-bottom: 1px solid #f1f3f4;
        }

        .search-brand-text {
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #6c757d;
            margin-bottom: 0.5rem;
        }

        .search-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 0.25rem;
            line-height: 1.2;
        }

        .search-subtitle {
            font-size: 0.9rem;
            color: #6c757d;
            font-weight: 300;
            margin-bottom: 1.5rem;
        }

        /* Search Input Container */
        .minimalist-search-container {
            position: relative;
            padding: 0 2rem;
        }

        .search-input-wrapper {
            position: relative;
            background: #ffffff;
            border: 2px solid #f1f3f4;
            border-radius: 50px;
            padding: 0.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .search-input-wrapper:focus-within {
            border-color: #70000E;
            box-shadow: 0 8px 30px rgba(112, 0, 14, 0.15);
            transform: translateY(-2px);
        }

        .minimalist-search-input {
            width: 100%;
            border: none;
            background: transparent;
            font-size: 1.25rem;
            font-weight: 400;
            color: #2d3748;
            outline: none;
            padding: 1rem 1.5rem 1rem 4rem;
            font-family: 'Poppins', sans-serif;
        }

        .minimalist-search-input::placeholder {
            color: #a0aec0;
            font-weight: 300;
        }

        .search-input-icon {
            position: absolute;
            left: 1.5rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }

        .search-input-wrapper:focus-within .search-input-icon {
            color: #70000E;
            transform: translateY(-50%) scale(1.1);
        }

        /* Search Close Button */
        .search-close-btn {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            font-size: 1rem;
            color: #6c757d;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .search-close-btn:hover {
            background-color: #70000E;
            color: white;
            transform: scale(1.1) rotate(90deg);
            box-shadow: 0 4px 15px rgba(112, 0, 14, 0.3);
        }

        /* Search Content Area */
        .search-content-area {
            flex: 1;
            padding: 1rem 2rem 2rem 2rem;
            overflow-y: auto;
            max-height: 400px;
        }

        /* Quick Suggestions */
        .quick-suggestions {
            margin-bottom: 2rem;
        }

        .quick-suggestions h6 {
            font-size: 0.875rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #6c757d;
            margin-bottom: 1rem;
        }

        .suggestion-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .suggestion-tag {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 25px;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            color: #2d3748;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 500;
        }

        .suggestion-tag:hover {
            background: #70000E;
            color: white;
            border-color: #70000E;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(112, 0, 14, 0.3);
        }

        /* Search Suggestions in Modal */
        .minimalist-search-suggestions {
            background: transparent;
        }

        .suggestions-section h6 {
            font-size: 0.875rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #6c757d;
            margin-bottom: 1rem;
        }

        .minimalist-suggestion-item {
            padding: 1rem 0;
            cursor: pointer;
            border-bottom: 1px solid #f1f3f4;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            font-family: 'Poppins', sans-serif;
            border-radius: 12px;
            margin-bottom: 0.5rem;
        }

        .minimalist-suggestion-item:hover {
            background-color: #f8f9fa;
            transform: translateX(8px);
            border-color: transparent;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .minimalist-suggestion-item:last-child {
            border-bottom: none;
        }

        .minimalist-suggestion-item i {
            margin-right: 1rem;
            color: #a0aec0;
            width: 20px;
            text-align: center;
            font-size: 1rem;
        }

        .minimalist-suggestion-item:hover i {
            color: #70000E;
        }

        /* Trending Searches */
        .trending-section {
            margin-bottom: 2rem;
        }

        .trending-section h6 {
            font-size: 0.875rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #6c757d;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }

        .trending-section h6 i {
            margin-right: 0.5rem;
            color: #70000E;
        }

        .trending-item {
            padding: 0.75rem 0;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-radius: 8px;
            padding-left: 1rem;
            padding-right: 1rem;
            margin-bottom: 0.25rem;
        }

        .trending-item:hover {
            background-color: #f8f9fa;
            transform: translateX(4px);
        }

        .trending-item-text {
            font-weight: 500;
            color: #2d3748;
        }

        .trending-item-arrow {
            color: #a0aec0;
            transition: all 0.3s ease;
        }

        .trending-item:hover .trending-item-arrow {
            color: #70000E;
            transform: translateX(4px);
        }

        /* Modal Animation */
        #minimalistSearchModal.fade .modal-dialog {
            transform: scale(0.8) translateY(-50px);
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #minimalistSearchModal.show .modal-dialog {
            transform: scale(1) translateY(0);
            opacity: 1;
        }

        .btn-link.text-dark:hover {
            background: linear-gradient(135deg, #70000E 0%, #8a0012 100%);
            color: white !important;
            transform: scale(1.1);
            box-shadow: 0 8px 20px rgba(112, 0, 14, 0.3);
        }

        /* Button active state */
        .search-container .btn-primary:active {
            transform: scale(0.95);
        }

        /* Enhanced result item styling */
        .result-item {
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .result-item:hover {
            background: #f8f9fa;
            transform: translateX(5px);
        }

        .result-image {
            overflow: hidden;
            flex-shrink: 0;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand" href="{{ route('homepage') }}">
                <img src="{{ asset('image/tay_meng_logo.jpg') }}" alt="Logo" height="70">
                Taymeng
            </a>

            <!-- Mobile Toggle Button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Content -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Main Navigation -->
                <ul class="navbar-nav mx-auto justify-content-center" style="flex: 1 1 0;">
                    <li class="nav-item mx-2">
                        <a class="nav-link text-uppercase" href="{{ route('homepage') }}">Home</a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link text-uppercase" href="{{ route('faq') }}">FAQ</a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link text-uppercase" href="{{ route('aboutus') }}">About Us</a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link text-uppercase" href="{{ route('contact_us') }}">Contact Us</a>
                    </li>
                </ul>

                <!-- Search Icon -->
                <div class="navbar-nav mx-3">
                    <button class="btn btn-link text-dark p-2 search-icon-btn" type="button" data-bs-toggle="modal" data-bs-target="#minimalistSearchModal">
                        <i class="fa-solid fa-search fs-5"></i>
                    </button>
                </div>

                <!-- Right Side Items -->
                <ul class="navbar-nav ms-auto d-flex align-items-center">
                    <!-- Wishlist -->
                    <li class="nav-item">
                        <button type="button" class="btn position-relative" id="wishlist-link">
                            <a class="custom-link" href="#" data-bs-toggle="modal"
                                data-bs-target="#wishlistModal">
                                <i class="fa-solid fa-heart"></i>
                                <span class="d-none d-lg-inline" style="font-size: 1rem;">My Wishlist</span>
                                <span id="count_heart_cart"
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">0</span>
                            </a>
                        </button>
                    </li>
                    <!-- Cart -->
                    <li class="nav-item">
                        <button type="button" class="btn position-relative">
                            <a class="custom-link" href="#" id="cartLink">
                                <i class="fa-solid fa-cart-shopping cart-icon" id="cart-icon"></i>
                                <span class="d-none d-lg-inline" style="font-size: 1rem;">My Cart</span>
                                <span id="count_cart"
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">0</span>
                            </a>
                        </button>
                    </li>
                    <!-- User Button -->
                    <li class="nav-item">
                        <button class="btn position-relative dropdown-toggle d-flex align-items-center gap-2 px-3"
                            type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="profile-image-container position-relative">
                                <img src="{{ Auth::user() && Auth::user()->profile_image ? asset('storage/' . Auth::user()->profile_image) : asset('image/smphone.png') }}"
                                    class="rounded-circle border border-2"
                                    style="width: 35px; height: 35px; object-fit: cover; box-shadow: 0 2px 4px rgba(0,0,0,0.1);"
                                    alt="Profile Picture">
                                <span
                                    class="position-absolute bottom-0 end-0 bg-success rounded-circle border border-2 border-white"
                                    style="width: 10px; height: 10px;"></span>
                            </div>
                            <div class="d-flex flex-column align-items-start">
                                <h8 class="modal-title mb-0 fw-bold" id="profileModalLabel">
                                    {{ Auth::check() ? Auth::user()->name : 'Guest' }}
                                </h8>
                                <small class="text-muted" style="font-size: 0.75rem;">My Account</small>
                            </div>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="profileDropdown"
                            style="min-width: 200px;">
                            <li class="px-3 py-2 border-bottom">
                                <div class="d-flex align-items-center">
                                    <img src="{{ Auth::user() && Auth::user()->profile_image ? asset('storage/' . Auth::user()->profile_image) : asset('image/smphone.png') }}"
                                        class="rounded-circle me-2"
                                        style="width: 40px; height: 40px; object-fit: cover;" alt="Profile Picture">
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ Auth::check() ? Auth::user()->name : 'Guest' }}
                                        </h6>
                                        <small
                                            class="text-muted">{{ Auth::check() ? Auth::user()->email : '' }}</small>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <a class="dropdown-item py-2" href="#" id="orderHistoryLink">
                                    <i class="fa-solid fa-box me-2 text-dark"></i>Order History
                                </a>
                            </li>
                            <li><a class="dropdown-item py-2" href="#" data-bs-toggle="modal"
                                    data-bs-target="#profileModal">
                                    <i class="fa-solid fa-user me-1"></i> My Profile
                                </a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                @auth
                                    <a class="dropdown-item py-2 text-danger" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fa-solid fa-sign-out-alt me-2"></i>Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        class="d-none">
                                        @csrf
                                    </form>
                                @else
                                    <a class="dropdown-item py-2" href="{{ route('login') }}">
                                        <i class="fa-solid fa-sign-in-alt me-2"></i>Login
                                    </a>
                                @endauth
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="cart-backdrop" id="cartBackdrop"></div>
    <div class="cart" id="cartSidebar">
        <i class="fa-solid fa-xmark" id="close-card"></i>
        <div class="cart-content"></div>
        <div class="total">
            <div class="total-text">Total</div>
            <div class="total-price">0.00</div>
        </div>
        <div>
            <form id="checkoutRedirectForm" method="POST" action="{{ route('checkout.show') }}">
                @csrf
                <input type="hidden" name="cart_data" id="checkoutCartData">
                <input type="hidden" name="user_id" id="checkoutUserId"
                    value="{{ Auth::check() ? Auth::user()->id : '' }}">
                @if (!Auth::check())
                    <input type="hidden" name="is_guest" value="1">
                @endif

                <button type="button" class="btn mt-4 w-100" style="background-color: black; color:white;"
                    onclick="submitCheckoutForm()">
                    Checkout
                </button>
            </form>

        </div>
    </div>
    </div>
    <div class="modal" id="wishlistModal" aria-labelledby="wishlistModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="wishlistModalLabel">
                        <i class="fas fa-heart text-danger me-1"></i> My Wishlist
                    </h5>
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

    <!-- Order History Modal for Guests -->
    <div class="modal fade" id="orderHistoryModal" tabindex="-1" aria-labelledby="orderHistoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderHistoryModalLabel">Check Order History</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="guestOrderForm" action="{{ route('checkout.history') }}" method="GET">
                        <div class="mb-3">
                            <label for="guestEmail" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="guestEmail" name="guest_eamil" required
                                value="{{ request('guest_email') }}">
                            @error('guest_eamil')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="orderNumber" class="form-label">Order Number</label>
                            <input type="text" class="form-control" id="orderNumber" name="order_num" required
                                value="{{ request('guest_token') }}">
                            @error('order_num')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-dark w-100">View Order</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- My Profile Modal -->
    <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header align-items-center">
                    <div class="d-flex align-items-center">
                        <img src="{{ Auth::user() && Auth::user()->profile_image ? asset('storage/' . Auth::user()->profile_image) : asset('image/smphone.png') }}"
                            alt="User Photo" class="rounded-circle me-3" width="35" height="35">
                        <h5 class="modal-title mb-0" id="profileModalLabel">
                            Hello, {{ Auth::check() ? Auth::user()->name : 'Guest' }}
                        </h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <!-- Sidebar -->
                        <div class="col-md-3 border-end">
                            <div class="list-group">
                                <a href="#edit-profile" class="list-group-item active">
                                    <i class="bi bi-person me-2"></i> Edit Profile
                                </a>
                                <a href="#change-password" class="list-group-item">
                                    <i class="bi bi-shield-lock me-2"></i> Change Password
                                </a>
                                <a href="#address" class="list-group-item ">
                                    <i class="bi bi-geo-alt me-2"></i> Address
                                </a>
                            </div>
                        </div>

                        <!-- Main content -->
                        <div class="col-md-9">
                            <div id="profileContent">
                                <!-- Success Alert -->
                                <div id="successMessage"
                                    class="alert alert-success alert-dismissible fade show d-none" role="alert">
                                    <strong>Success!</strong> Changes saved successfully.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>

                                <div id="errorMessage" class="alert alert-danger alert-dismissible fade show d-none"
                                    role="alert">
                                    <strong>Error!</strong> Please fill in all required fields.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>

                                <!-- Edit Profile Section -->
                                <div id="editProfileContent">
                                    <form action="{{ route('profile.update') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="text-center mb-4">
                                            <img src="{{ Auth::user() && Auth::user()->profile_image ? asset('storage/' . Auth::user()->profile_image) : asset('image/smphone.png') }}"
                                                class="rounded-circle mb-2" width="120" height="120"
                                                alt="Profile Picture">
                                            <input type="file" name="profile_image" class="form-control mt-2"
                                                accept="image/*">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Full Name</label>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ Auth::user()->name ?? 'Guest' }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Phone Number</label>
                                            <input type="text" name="phone_number" class="form-control"
                                                value="{{ Auth::user()->phone_number ?? '' }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Email</label>
                                            <input type="email" name="email" class="form-control"
                                                value="{{ Auth::user()->email ?? '' }}" required>
                                        </div>

                                        <button type="submit" class="btn btn-dark w-100">Save Changes</button>
                                    </form>
                                </div>

                                <!-- Change Password Section -->
                                <div id="changePasswordContent" class="content-section" style="display: none;">
                                    <form id="passwordChangeForm" class="needs-validation" novalidate>
                                        @csrf
                                        <div class="mb-3">
                                            <label for="current_password" class="form-label">Current Password</label>
                                            <input type="password" class="form-control" id="current_password"
                                                name="current_password" required>
                                            <div class="invalid-feedback">Please enter your current password.</div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="new_password" class="form-label">New Password</label>
                                            <input type="password" class="form-control" id="new_password"
                                                name="new_password" required minlength="8">
                                            <div class="invalid-feedback">Password must be at least 8 characters long.
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="new_password_confirmation" class="form-label">Confirm New
                                                Password</label>
                                            <input type="password" class="form-control"
                                                id="new_password_confirmation" name="password_confirmation"
                                                required>
                                            <div class="invalid-feedback">Passwords do not match.</div>
                                        </div>
                                        <div id="passwordAlert" class="alert" style="display: none;"></div>
                                        <button type="submit" class="btn btn-dark">Update Password</button>
                                    </form>
                                </div>

                                <!-- Address Section -->
                                <div id="addressContent" style="display: none;">
                                    <div class="text-center mb-4">
                                        <h3>My Address</h3>
                                        <p class="text-muted">Update your shipping or contact address here.</p>
                                    </div>
                                    <form action="{{ route('profile.address') }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label">Street Address Line 1</label>
                                            <input type="text" name="address_line1" class="form-control"
                                                value="{{ Auth::user()->address_line1 ?? '' }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Street Address Line 2</label>
                                            <input type="text" name="address_line2" class="form-control"
                                                value="{{ Auth::user()->address_line2 ?? '' }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">City</label>
                                            <input type="text" name="city" class="form-control"
                                                value="{{ Auth::user()->city ?? '' }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">State/Province</label>
                                            <input type="text" name="state" class="form-control"
                                                value="{{ Auth::user()->state ?? '' }}" required>
                                        </div>
                                        <button type="submit" class="btn btn-dark w-100">Save Address</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Modal -->
    <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0">
                <div class="modal-header border-0 p-4">
                    <div class="search-header-content w-100">
                        <div class="search-input-container">
                            <i class="fa-solid fa-search search-icon"></i>
                            <input type="text" 
                                   class="form-control search-input" 
                                   id="searchModalInput" 
                                   placeholder="Search products..." 
                                   autocomplete="off">
                            <div class="search-suggestions" id="searchSuggestions"></div>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body p-0">
                    <!-- Initial State - Quick Categories -->
                    <div class="quick-categories p-4" id="quickCategories">
                        <h6 class="text-muted mb-3">Quick Search</h6>
                        <div class="search-tags">
                            <span class="search-tag" data-search="iPhone">iPhone</span>
                            <span class="search-tag" data-search="Samsung">Samsung</span>
                            <span class="search-tag" data-search="Headphones">Headphones</span>
                            <span class="search-tag" data-search="Charger">Charger</span>
                        </div>
                    </div>
                    
                    <!-- Search Results Section -->
                    <div class="search-results-section" id="searchResultsSection" style="display: none;">
                        <div class="results-header p-4 border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 text-muted">
                                    Results for "<span id="searchQueryDisplay"></span>"
                                </h6>
                                <button class="btn btn-link btn-sm p-0" id="backToSearch">
                                    <i class="fa-solid fa-arrow-left me-1"></i>Back
                                </button>
                            </div>
                        </div>
                        
                        <div class="results-content">
                            <!-- Loading State -->
                            <div class="loading-state text-center p-5" id="loadingState">
                                <div class="loading-spinner"></div>
                                <p class="mt-3 text-muted small">Searching...</p>
                            </div>
                            
                            <!-- Results List -->
                            <div class="results-list p-4" id="resultsList" style="display: none;">
                                <!-- Dynamic results will be populated here -->
                            </div>
                            
                            <!-- No Results -->
                            <div class="no-results text-center p-5" id="noResults" style="display: none;">
                                <i class="fa-solid fa-search text-muted mb-3" style="font-size: 2rem;"></i>
                                <h6 class="text-muted">No products found</h6>
                                <p class="text-muted small">Try different keywords</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modern Search Modal -->
    <div class="modal fade" id="minimalistSearchModal" tabindex="-1" aria-labelledby="minimalistSearchModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="search-close-btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Search Header -->
                    <div class="modern-search-header">
                        <div class="search-brand-text">TayMeng Shop</div>
                        <h2 class="search-title">What are you<br>looking for?</h2>
                        <p class="search-subtitle">Discover our collection of phones and accessories</p>
                        
                        <!-- Search Input -->
                        <div class="minimalist-search-container">
                            <form action="/search" method="GET" id="minimalistSearchForm">
                                <div class="search-input-wrapper">
                                    <i class="fa-solid fa-search search-input-icon"></i>
                                    <input type="text" 
                                           class="minimalist-search-input" 
                                           id="minimalistSearchInput"
                                           name="query"
                                           placeholder="Search products..."
                                           autocomplete="off">
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Search Content Area -->
                    <div class="search-content-area">
                        <!-- Quick Suggestions (Default State) -->
                        <div class="quick-suggestions" id="quickSuggestions">
                            <h6>Popular Categories</h6>
                            <div class="suggestion-tags">
                                <div class="suggestion-tag" onclick="searchByCategory('iPhone')">iPhone</div>
                                <div class="suggestion-tag" onclick="searchByCategory('Samsung')">Samsung</div>
                                <div class="suggestion-tag" onclick="searchByCategory('Accessories')">Accessories</div>
                                <div class="suggestion-tag" onclick="searchByCategory('Cases')">Cases</div>
                                <div class="suggestion-tag" onclick="searchByCategory('Chargers')">Chargers</div>
                                <div class="suggestion-tag" onclick="searchByCategory('Headphones')">Headphones</div>
                            </div>
                        </div>
                        
                        <!-- Trending Searches -->
                        <div class="trending-section">
                            <h6><i class="fa-solid fa-fire"></i>Trending Now</h6>
                            <div class="trending-item" onclick="searchByTrend('iPhone 15 Pro')">
                                <span class="trending-item-text">iPhone 15 Pro</span>
                                <i class="fa-solid fa-arrow-right trending-item-arrow"></i>
                            </div>
                            <div class="trending-item" onclick="searchByTrend('Samsung Galaxy S24')">
                                <span class="trending-item-text">Samsung Galaxy S24</span>
                                <i class="fa-solid fa-arrow-right trending-item-arrow"></i>
                            </div>
                            <div class="trending-item" onclick="searchByTrend('AirPods Pro')">
                                <span class="trending-item-text">AirPods Pro</span>
                                <i class="fa-solid fa-arrow-right trending-item-arrow"></i>
                            </div>
                            <div class="trending-item" onclick="searchByTrend('Wireless Charger')">
                                <span class="trending-item-text">Wireless Charger</span>
                                <i class="fa-solid fa-arrow-right trending-item-arrow"></i>
                            </div>
                        </div>
                        
                        <!-- Dynamic Search Suggestions -->
                        <div class="minimalist-search-suggestions" id="minimalistSearchSuggestions" style="display: none;">
                            <div class="suggestions-section">
                                <h6>Suggestions</h6>
                                <div id="dynamicSuggestions">
                                    <!-- Dynamic suggestions will be loaded here -->
                                </div>
                            </div>
                        </div>
                        
                        <!-- Search Results -->
                        <div class="minimalist-search-results" id="minimalistSearchResults" style="display: none;">
                            <!-- Results will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                    <h5 class="fw-bold py-2 fs-6" style="color:white">Category</h5>
                    <ul class="list-unstyled">
                        <li class="py-2"><a href="/product" class="text-decoration-none">Smartphones</a></li>
                        <li class="py-2"><a href="/product_acessory" class="text-decoration-none">Accessories</a>
                        </li>
                    </ul>
                </div>
                <!-- Quick Links Column -->
                <div class="col-md-2 my-3">
                    <h5 class="fw-bold py-2 fs-6" style="color:white">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li class="py-2"><a href="{{ route('homepage') }}" class=" text-decoration-none">Home</a>
                        </li>
                        <li class="py-2"><a href="{{ route('aboutus') }}" class="text-decoration-none">About
                                Us</a></li>
                        <li class="py-2"><a href="{{ route('contact_us') }}" class="text-decoration-none">Contact
                                Us</a></li>
                        <li class="py-2"><a href="{{ route('faq') }}" class="text-decoration-none">FAQ</a></li>
                    </ul>
                </div>
                <!-- Get In Touch Column -->
                <div class="col-md-3 my-3 text-white">
    <h5 class="fw-bold py-2 fs-6">Get In Touch</h5>
    <ul class="list-unstyled">
        <li class="py-2"><i class="fa-solid fa-location-pin"></i> <span class="ms-2">78Eo St13
                Phsar Kondal Ti 1</span></li>
        <li class="py-2"><i class="fa-solid fa-phone"></i> <span class="ms-2">096 841 2222</span>
        </li>
        <li class="py-2"><i class="fa-solid fa-envelope"></i> <span
                class="ms-2">taymeng@gmail.com</span></li>
        <li class="py-2"><i class="fa-brands fa-telegram"></i> <span
                class="ms-2">t.me/taymeng</span></li>
    </ul>
</div>

                <!-- Follow Us Column -->
                <div class="col-md-2 my-3">
                    <h5 class="fw-bold py-2 fs-6" style="color:white">Follow Us</h5>
                    <ul class="list-unstyled">
                        <li class="py-2">
                            <a href="https://www.facebook.com/TayMeng13?mibextid=wwXIfr" class="text-decoration-none">
                                <i class="fa-brands fa-facebook"></i> Facebook
                            </a>
                        </li>

                        <li class="py-2"><a href="#" class="text-decoration-none"><i
                                    class="fa-solid fa-globe"></i> Taymeng.com</a></li>
                    </ul>
                </div>
                <!-- Help Column -->
                <div class="col-md-2 my-3">
                    <h5 class="fw-bold py-2 fs-6" style="color:white">Help</h5>
                    <ul class="list-unstyled">
                        <li class="py-2"><a href="/Privacy" class="text-decoration-none">Privacy Policy</a></li>
                        <li class="py-2"><a href="/terms" class="text-decoration-none">Terms of Service</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    <script src="{{ asset('js/wishlist.js') }}"></script>
    <script src="{{ asset('js/homepage.js') }}"></script>
    <script src="{{ asset('js/profile.js') }}"></script>
    <script src="{{ asset('js/cart.js') }}"></script>
    <!-- Load jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.isAuthenticated = {{ Auth::check() ? 'true' : 'false' }};

        document.addEventListener('DOMContentLoaded', function() {
            const orderHistoryLink = document.getElementById('orderHistoryLink');
            const orderHistoryModal = new bootstrap.Modal(document.getElementById('orderHistoryModal'));

            orderHistoryLink.addEventListener('click', function(e) {
                e.preventDefault();

                if (window.isAuthenticated === true) {
                    window.location.href = "{{ route('checkout.history') }}";
                } else {
                    orderHistoryModal.show();
                }
            });

            // Minimalist Search Modal functionality
            const searchModal = document.getElementById('searchModal');
            const searchModalInput = document.getElementById('searchModalInput');
            const searchSuggestions = document.getElementById('searchSuggestions');
            const quickCategories = document.getElementById('quickCategories');
            const searchResultsSection = document.getElementById('searchResultsSection');
            const searchQueryDisplay = document.getElementById('searchQueryDisplay');
            const loadingState = document.getElementById('loadingState');
            const resultsList = document.getElementById('resultsList');
            const noResults = document.getElementById('noResults');
            const backToSearch = document.getElementById('backToSearch');

            // Focus on search input when modal opens
            searchModal.addEventListener('shown.bs.modal', function() {
                searchModalInput.focus();
                showQuickCategories();
            });

            // Handle search input typing
            let searchTimeout;
            searchModalInput.addEventListener('input', function() {
                const query = this.value.trim();
                
                clearTimeout(searchTimeout);
                
                if (query.length > 2) {
                    searchTimeout = setTimeout(() => {
                        showSearchSuggestions(query);
                    }, 300);
                } else {
                    hideSearchSuggestions();
                }
            });

            // Handle Enter key in search input
            searchModalInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    performSearch();
                }
            });

            // Handle search tags
            document.querySelectorAll('.search-tag').forEach(tag => {
                tag.addEventListener('click', function() {
                    const searchTerm = this.getAttribute('data-search');
                    searchModalInput.value = searchTerm;
                    performSearch();
                });
            });

            // Handle back to search button
            backToSearch.addEventListener('click', function() {
                showSearchHero();
            });

            // Function to perform search
            function performSearch() {
                const query = searchModalInput.value.trim();
                if (query) {
                    showSearchResults();
                    searchQueryDisplay.textContent = query;
                    
                    // Simulate search loading
                    setTimeout(() => {
                        // Replace this with actual API call to your backend
                        searchProducts(query);
                    }, 1000);
                }
            }

            // Function to show quick categories
            function showQuickCategories() {
                quickCategories.style.display = 'block';
                searchResultsSection.style.display = 'none';
                searchModalInput.value = '';
                hideSearchSuggestions();
            }

            // Function to show search results
            function showSearchResults() {
                searchResultsSection.style.display = 'block';
                loadingState.style.display = 'block';
                resultsGrid.style.display = 'none';
                noResults.style.display = 'none';
            }

            // Function to show search suggestions
            function showSearchSuggestions(query) {
                // Generate suggestions based on query
                const suggestions = generateSuggestions(query);
                
                if (suggestions.length > 0) {
                    const suggestionsHTML = suggestions.map(suggestion => 
                        `<div class="suggestion-item" onclick="selectSuggestion('${suggestion}')">${suggestion}</div>`
                    ).join('');
                    
                    searchSuggestions.innerHTML = suggestionsHTML;
                    searchSuggestions.style.display = 'block';
                } else {
                    hideSearchSuggestions();
                }
            }

            // Function to hide search suggestions
            function hideSearchSuggestions() {
                searchSuggestions.style.display = 'none';
            }

            // Function to select suggestion
            window.selectSuggestion = function(suggestion) {
                searchModalInput.value = suggestion;
                hideSearchSuggestions();
                performSearch();
            };

            // Function to get suggestions from your database
            async function generateSuggestions(query) {
                try {
                    const response = await fetch(`/search-suggestions?query=${encodeURIComponent(query)}`);
                    if (response.ok) {
                        const suggestions = await response.json();
                        return suggestions;
                    }
                } catch (error) {
                    console.error('Error fetching suggestions:', error);
                }
                return [];
            }

            // Function to search products from your database
            async function searchProducts(query) {
                try {
                    // Make API call to your Laravel backend
                    const response = await fetch(`/search?query=${encodeURIComponent(query)}&json=1`);
                    
                    if (!response.ok) {
                        throw new Error('Search request failed');
                    }
                    
                    const data = await response.json();
                    
                    if (data.success && data.products) {
                        displaySearchResults(data.products);
                    } else {
                        showNoResults();
                    }
                } catch (error) {
                    console.error('Search error:', error);
                    showNoResults();
                }
            }

            // Function to display search results
            function displaySearchResults(results) {
                loadingState.style.display = 'none';
                
                if (results.length > 0) {
                    resultsList.style.display = 'block';
                    noResults.style.display = 'none';
                    
                    const resultsHTML = results.map(product => `
                        <div class="result-item" onclick="goToProduct(${product.id})">
                            <div class="result-image">
                                ${product.image.startsWith('http') || product.image.startsWith('/') 
                                    ? `<img src="${product.image}" alt="${product.name}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">` 
                                    : product.image}
                            </div>
                            <div class="result-info">
                                <h6>${product.name}</h6>
                                <div class="result-category">${product.brand} • ${product.category}</div>
                                <div class="result-rating">
                                    ${'★'.repeat(Math.floor(product.rating))}${'☆'.repeat(5 - Math.floor(product.rating))} ${product.rating}
                                </div>
                                <div class="result-details">
                                    <small class="text-muted">
                                        ${product.colors.length > 0 ? `Colors: ${product.colors.join(', ')} • ` : ''}
                                        Stock: ${product.stock}
                                    </small>
                                </div>
                            </div>
                            <div class="result-price">${product.price}</div>
                        </div>
                    `).join('');
                    
                    resultsList.innerHTML = resultsHTML;
                } else {
                    showNoResults();
                }
            }

            // Function to show no results
            function showNoResults() {
                loadingState.style.display = 'none';
                resultsList.style.display = 'none';
                noResults.style.display = 'block';
            }

            // Function to go to product page
            window.goToProduct = function(productId) {
                // Close the search modal
                const modal = bootstrap.Modal.getInstance(searchModal);
                modal.hide();
                
                        // Navigate to product page
        window.location.href = `/product/${productId}`;
    };

    // Test search functionality
    window.testSearch = function() {
        console.log('Testing search functionality...');
        
        // Test search suggestions
        fetch('/search-suggestions?query=phone')
            .then(response => response.json())
            .then(data => {
                console.log('Search suggestions test:', data);
                alert('Search suggestions working! Found: ' + data.length + ' suggestions');
            })
            .catch(error => {
                console.error('Search suggestions test failed:', error);
                alert('Search suggestions test failed: ' + error.message);
            });
        
        // Test main search
        fetch('/search?query=phone&json=1')
            .then(response => response.json())
            .then(data => {
                console.log('Main search test:', data);
                if (data.success) {
                    alert('Main search working! Found: ' + data.count + ' products');
                } else {
                    alert('Main search failed: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Main search test failed:', error);
                alert('Main search test failed: ' + error.message);
            });
    };

            // Close suggestions when clicking outside
            document.addEventListener('click', function(e) {
                if (!searchModalInput.contains(e.target) && !searchSuggestions.contains(e.target)) {
                    hideSearchSuggestions();
                }
            });

            // Handle form submission
            // document.getElementById('guestOrderForm').addEventListener('submit', function(e) {
            //     e.preventDefault();
            //     this.submit();
            // });
        });

        // Modern Search Modal Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const minimalistSearchModal = document.getElementById('minimalistSearchModal');
            const minimalistSearchInput = document.getElementById('minimalistSearchInput');
            const minimalistSearchSuggestions = document.getElementById('minimalistSearchSuggestions');
            const quickSuggestions = document.getElementById('quickSuggestions');
            const dynamicSuggestions = document.getElementById('dynamicSuggestions');
            const minimalistSearchForm = document.getElementById('minimalistSearchForm');
            let searchTimeout;

            // Focus on search input when modal opens
            minimalistSearchModal.addEventListener('shown.bs.modal', function() {
                minimalistSearchInput.focus();
                minimalistSearchInput.value = '';
                showDefaultState();
            });

            // Handle search input changes
            minimalistSearchInput.addEventListener('input', function() {
                const query = this.value.trim();
                
                clearTimeout(searchTimeout);
                
                if (query.length > 2) {
                    searchTimeout = setTimeout(() => {
                        fetchMinimalistSearchSuggestions(query);
                    }, 300);
                } else if (query.length === 0) {
                    showDefaultState();
                } else {
                    hideAllSuggestions();
                }
            });

            // Handle Enter key
            minimalistSearchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    performMinimalistSearch();
                }
            });

            // Handle form submission
            minimalistSearchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                performMinimalistSearch();
            });

            // Show default state (categories and trending)
            function showDefaultState() {
                quickSuggestions.style.display = 'block';
                minimalistSearchSuggestions.style.display = 'none';
            }

            // Hide all suggestions
            function hideAllSuggestions() {
                quickSuggestions.style.display = 'none';
                minimalistSearchSuggestions.style.display = 'none';
            }

            // Fetch search suggestions
            function fetchMinimalistSearchSuggestions(query) {
                fetch(`/search-suggestions?query=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(suggestions => {
                        showMinimalistSuggestions(suggestions);
                    })
                    .catch(error => {
                        console.error('Search suggestions error:', error);
                        hideAllSuggestions();
                    });
            }

            // Show suggestions
            function showMinimalistSuggestions(suggestions) {
                quickSuggestions.style.display = 'none';
                
                if (suggestions.length === 0) {
                    minimalistSearchSuggestions.style.display = 'none';
                    return;
                }

                const html = suggestions.map(suggestion => 
                    `<div class="minimalist-suggestion-item" onclick="selectMinimalistSuggestion('${suggestion.replace(/'/g, "\\'")}')">
                        <i class="fa-solid fa-search"></i>
                        ${suggestion}
                    </div>`
                ).join('');

                dynamicSuggestions.innerHTML = html;
                minimalistSearchSuggestions.style.display = 'block';
            }

            // Perform search
            function performMinimalistSearch() {
                const query = minimalistSearchInput.value.trim();
                if (query) {
                    // Close modal and redirect to search results
                    const modal = bootstrap.Modal.getInstance(minimalistSearchModal);
                    modal.hide();
                    
                    // Redirect to search results page
                    window.location.href = `/search?query=${encodeURIComponent(query)}`;
                }
            }

            // Global functions for category and trending searches
            window.searchByCategory = function(category) {
                minimalistSearchInput.value = category;
                performMinimalistSearch();
            };

            window.searchByTrend = function(trend) {
                minimalistSearchInput.value = trend;
                performMinimalistSearch();
            };

            // Global function to select suggestion
            window.selectMinimalistSuggestion = function(suggestion) {
                minimalistSearchInput.value = suggestion;
                performMinimalistSearch();
            };

            // Handle ESC key to close modal
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && minimalistSearchModal.classList.contains('show')) {
                    const modal = bootstrap.Modal.getInstance(minimalistSearchModal);
                    modal.hide();
                }
            });
        });
    </script>
</body>

</html>
