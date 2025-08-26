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
                                                id="new_password_confirmation" name="new_password_confirmation"
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

            // Handle form submission
            // document.getElementById('guestOrderForm').addEventListener('submit', function(e) {
            //     e.preventDefault();
            //     this.submit();
            // });
        });
    </script>
</body>

</html>
