<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Tay Meng Phone Shop') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/footerheader.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/about.css') }}" rel="stylesheet">
    <link href="{{ asset('css/mainstyle.css')}}" rel="stylesheet">
    @yield('head')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
        <div class="container">
            <!-- Brand Name -->
            <a href="#" class="navbar-brand">TayMeng</a>

            <!-- Icons -->
            <div class="order-lg-2 nav-btns"> 
                <button type="button" class="btn position-relative">
                    <a class="custom-link" href="#"><i class="fa-solid fa-heart"></i><span class="d-none d-lg-inline">My Wishlist</span></a>
                </button>
                <button type="button" class="btn position-relative">
                    <a class="custom-link" href="#"><i class="fa-solid fa-cart-shopping"></i><span class="d-none d-lg-inline">My Cart</span></a>
                </button>
                <button type="button" class="btn position-relative">
                    <a class="custom-link" href="#"><i class="fa-solid fa-user"></i><span class="d-none d-lg-inline">Hello Guest</span></a>
                </button>
            </div>

            <!-- Navbar Toggler Button -->
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Collapsible Navbar Links -->
            <div class=" navbar-collape" id="navbarNav">
                <ul class="navbar-nav mx-auto ">
                    <li class="nav-item mx-3">
                        <a class="nav-link text-uppercase" href="#">Home</a>
                    </li>
                    <li class="nav-item mx-3">
                        <a class="nav-link text-uppercase" href="#">About Us</a>
                    </li>
                    <li class="nav-item mx-3">
                        <a class="nav-link text-uppercase" href="#">Contact Us</a>
                    </li>
                    <li class="nav-item mx-3">
                        <a class="nav-link text-uppercase" href="#">FAQ</a>
                    </li>
                </ul>
            </div>
        </div>
        </div>
    </nav>

    <!-- Header Section -->
    <header>
        @yield('header')
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="custom-footer text-white">
        <div class="container">
            <div class="row">
                <!-- Category Column -->
                <div class="col-md-2 my-3">
                    <h5 class="fw-bold py-2">Category</h5>
                    <ul class="list-unstyled">
                        <li class="py-2"><a href="#" class="text-white text-decoration-none">Smartphones</a></li>
                        <li class="py-2"><a href="#" class="text-white text-decoration-none">Accessories</a></li>
                    </ul>
                </div>
                <!-- Quick Links Column -->
                <div class="col-md-2 my-3">
                    <h5 class="fw-bold py-2">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li class="py-2"><a href="#" class="text-white text-decoration-none">Home</a></li>
                        <li class="py-2"><a href="#" class="text-white text-decoration-none">About Us</a></li>
                        <li class="py-2"><a href="#" class="text-white text-decoration-none">Contact Us</a></li>
                        <li class="py-2"><a href="#" class="text-white text-decoration-none">FAQ</a></li>
                    </ul>
                </div>
                <!-- Get In Touch Column -->
                <div class="col-md-3 my-3">
                    <h5 class="fw-bold py-2">Get In Touch</h5>
                    <ul class="list-unstyled">
                        <li class="py-2"><i class="fa-solid fa-location-pin"></i> <span class="ms-2">78Eo St13 Phsar Kondal Ti 1</span></li>
                        <li class="py-2"><i class="fa-solid fa-phone"></i> <span class="ms-2">096 841 2222</span></li>
                        <li class="py-2"><i class="fa-solid fa-envelope"></i></i> <span class="ms-2">taymeng@gmail.com</span></li>
                        <li class="py-2"><i class="fa-brands fa-telegram"></i> <span class="ms-2">t.me/taymeng</span></li>
                    </ul>
                </div>
                <!-- Follow Us Column -->
                <div class="col-md-2 my-3">
                    <h5 class="fw-bold py-2">Follow Us</h5>
                    <ul class="list-unstyled">
                        <li class="py-2"><a href="#" class="text-white text-decoration-none"><i class="fa-brands fa-facebook"></i> Facebook</a></li>
                        <li class="py-2"><a href="#" class="text-white text-decoration-none"><i class="fa-solid fa-globe"></i> Taymeng.com</a></li>
                    </ul>
                </div>
                <!-- We Accept Column -->
                <div class="col-md-2 my-3">
                    <h5 class="fw-bold py-2">We Accept</h5>
                    <ul class="d-flex align-items-center">
                        <img src="{{ asset('image/aba.jpg') }}" alt="..." style="max-width: 100px;">
                        <img src="{{ asset('image/delivery.jpg') }}" alt="..." style="max-width: 100px;">
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
