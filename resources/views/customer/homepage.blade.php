<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Tay Meng Phone Shop') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/homepage.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
        <div class="container">
            <!-- Brand Name -->
            <a href="#" class="navbar-brand">TayMeng</a>

            <!-- Icons -->
            <div class="order-lg-2 nav-btns"> 
                <button type = "button" class="btn position-relative">
                    <a class="custom-link" href="#"><i class="fa-solid fa-heart"></i><span class="d-none d-lg-inline">My Wishlist</span></a>
                </button>
                <button type = "button" class="btn position-relative">
                    <a class="custom-link" href="#"><i class="fa-solid fa-cart-shopping"></i><span class="d-none d-lg-inline">My Cart</span></a>
                </button>
                <button type = "button" class="btn position-relative">
                    <a class="custom-link" href="#"><i class="fa-solid fa-user"></i><span class="d-none d-lg-inline">Hello Guest</span></a>
                </button>
            </div>

            <!-- Navbar Toggler Button -->
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Collapsible Navbar Links -->
            <div class="collapse navbar-collapse order-lg-1" id="navbarNav">
                <ul class="navbar-nav mx-auto text-center">
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
    </nav>

    <!-- header -->
    <header>
        <div id="carouselExampleCaptions" class="carousel slide custom-carousel-bg" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="container-fluid d-flex align-items-center" style="height: 75vh;">
                        <div class="container d-flex justify-content-between align-items-center">
                            <div class="text-container pt-5">
                                <h2 class="display-4 fw-bold">Welcome to <br> TayMeng Phone Shop</h2>
                                <p>Power Up Your Life with the Latest Electronic!</p>
                                <a href="#" class="btn btn-primary px-4 py-2 d-inline-block custom-btn">Shop Now</a>
                            </div>
                            <div class="image-container">
                                <img src="{{ asset('image/img1.jpg') }}" alt="" class="img-fluid rounded-circle border border-dark border-3">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="container-fluid d-flex align-items-center" style="height: 75vh;">
                        <div class="container d-flex justify-content-between align-items-center">
                            <div class="text-container pt-5">
                                <h2 class="display-4 fw-bold">Welcome to <br> TayMeng Phone Shop</h2>
                                <p>Power Up Your Life with the Latest Electronic!</p>
                                <a href="#" class="btn btn-primary px-4 py-2 d-inline-block custom-btn">Shop Now</a>
                            </div>
                            <div class="image-container">
                                <img src="{{ asset('image/img1.jpg') }}" alt="" class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="container-fluid d-flex align-items-center" style="height: 75vh;">
                        <div class="container d-flex justify-content-between align-items-center">
                            <div class="text-container pt-5">
                                <h2 class="display-4 fw-bold">Welcome to <br> TayMeng Phone Shop</h2>
                                <p>Power Up Your Life with the Latest Electronic!</p>
                                <a href="#" class="btn btn-primary px-4 py-2 d-inline-block custom-btn">Shop Now</a>
                            </div>
                            <div class="image-container">
                                <img src="{{ asset('image/img1.jpg') }}" alt="" class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev custom-carousel" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                <i class="fa-solid fa-circle-chevron-left fa-2x" aria-hidden="true"></i>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next custom-carousel" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                <i class="fa-solid fa-circle-chevron-right fa-2x" aria-hidden="true"></i>
                <span class="visually-hidden">Next</span>
            </button>           
        </div>
    </header>

    <!-- Smartphone Accessories -->
    <section>
        <div class="container my-5">
            <h2 class="text-right mb-4">Popular Category</h2>
            <div class="row">
                <!-- Smartphone -->
                <div class="col-12 col-md-6">
                    <div class="category-card bg-dark text-white">
                        <img src="{{ asset('image/phone.jpg') }}" class="card-img" alt="...">
                        <h5 class="card-title">Card title</h5>
                    </div>
                </div>
                <!-- Smartphone -->
                
            </div>
        </div>
    </section>

    <!-- Feature Products -->
    <section>
        <div class="container my-5">
            <h2 class="text-right mb-4">Feature Products</h2>
            <div class="row g-4">
                <!-- Product Card 1 -->
                <div class="col-md-3">
                    <div class="card product-card">
                        <img src="{{ asset('image/Iphone16.jpg') }}" class="card-img-top" alt="...">
                        <div class="card-body text-right bg-light">
                            <p class="tag mb-0">Smartphone, IPhone</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mt-2">IPhone 16</h5>
                                <i class="fa-regular fa-heart fs-5"></i>
                            </div>
                            <p class="card-price">$1059.00</p>
                            <a href="#" class="btn btn-primary px-4 py-2 d-inline-block custom-btn w-100">Add to Cart</a>
                        </div>
                      </div>
                </div>
                <!-- Product Card 2 -->
                <div class="col-md-3">
                    <div class="card product-card">
                        <img src="{{ asset('image/Iphone16.jpg') }}" class="card-img-top" alt="...">
                        <div class="card-body text-right bg-light">
                            <p class="tag mb-0">Smartphone, IPhone</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mt-2">IPhone 16</h5>
                                <i class="fa-regular fa-heart fs-5"></i>
                            </div>
                            <p class="card-price">$1059.00</p>
                            <a href="#" class="btn btn-primary px-4 py-2 d-inline-block custom-btn w-100">Add to Cart</a>
                        </div>
                      </div>
                </div>

                <!-- Product Card 3 -->
                <div class="col-md-3">
                    <div class="card product-card">
                        <img src="{{ asset('image/Iphone16.jpg') }}" class="card-img-top" alt="...">
                        <div class="card-body text-right bg-light">
                            <p class="tag mb-0">Smartphone, IPhone</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mt-2">IPhone 16</h5>
                                <i class="fa-regular fa-heart fs-5"></i>
                            </div>
                            <p class="card-price">$1059.00</p>
                            <a href="#" class="btn btn-primary px-4 py-2 d-inline-block custom-btn w-100">Add to Cart</a>
                        </div>
                      </div>
                </div>

                <!-- Product Card 4 -->
                <div class="col-md-3">
                    <div class="card product-card">
                        <img src="{{ asset('image/Iphone16.jpg') }}" class="card-img-top" alt="...">
                        <div class="card-body text-right bg-light">
                            <p class="tag mb-0">Smartphone, IPhone</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mt-2">IPhone 16</h5>
                                <i class="fa-regular fa-heart fs-5"></i>
                            </div>
                            <p class="card-price">$1059.00</p>
                            <a href="#" class="btn btn-primary px-4 py-2 d-inline-block custom-btn w-100">Add to Cart</a>
                        </div>
                      </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Banner Products -->
    <section>
        <div class="container my-5">
            <div class="row g-3">
                <!-- New Arrivals Section -->
                <div class="col-md-6 position-relative">
                    <img src="{{ asset('image/Mobile.jpg') }}" alt="" class="img-fluid" style="filter: brightness(65%);">
                    <div class="position-absolute top-50 start-20 translate-middle-y text-white text-start p-3">
                        <h3 class="fw-bold mb-3">Pre-Order Now!</h3>
                        <p class="mb-4">Special launch offers available for a limited time.</p>
                        <a href="#" class="btn btn-primary px-4 py-2 d-inline-block custom-btn">Shop Now</a>
                    </div>
                </div>
                <!-- New Arrivals Section -->
                <div class="col-md-6 position-relative">
                    <img src="{{ asset('image/airpods.jpg') }}" alt="" class="img-fluid" style="filter: brightness(65%);">
                    <div class="position-absolute top-50 start-20 translate-middle-y text-white text-start p-3">
                        <h3 class="fw-bold mb-3">Pre-Order Now!</h3>
                        <p class="mb-4">Special launch offers available for a limited time.</p>
                        <a href="#" class="btn btn-primary px-4 py-2 d-inline-block custom-btn">Shop Now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- New Products -->
    <section>
        <div class="container my-5">
            <h2 class="text-right mb-4">New Products</h2>
            <div class="row g-4">
                <!-- Product Card 1 -->
                <div class="col-md-3">
                    <div class="card product-card">
                        <img src="{{ asset('image/Iphone16.jpg') }}" class="card-img-top" alt="...">
                        <div class="card-body text-right bg-light">
                            <p class="tag mb-0">Smartphone, IPhone</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mt-2">IPhone 16</h5>
                                <i class="fa-regular fa-heart fs-5"></i>
                            </div>
                            <p class="card-price">$1059.00</p>
                            <a href="#" class="btn btn-primary px-4 py-2 d-inline-block custom-btn w-100">Add to Cart</a>
                        </div>
                      </div>
                </div>
                <!-- Product Card 2 -->
                <div class="col-md-3">
                    <div class="card product-card">
                        <img src="{{ asset('image/Iphone16.jpg') }}" class="card-img-top" alt="...">
                        <div class="card-body text-right bg-light">
                            <p class="tag mb-0">Smartphone, IPhone</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mt-2">IPhone 16</h5>
                                <i class="fa-regular fa-heart fs-5"></i>
                            </div>
                            <p class="card-price">$1059.00</p>
                            <a href="#" class="btn btn-primary px-4 py-2 d-inline-block custom-btn w-100">Add to Cart</a>
                        </div>
                      </div>
                </div>

                <!-- Product Card 3 -->
                <div class="col-md-3">
                    <div class="card product-card">
                        <img src="{{ asset('image/Iphone16.jpg') }}" class="card-img-top" alt="...">
                        <div class="card-body text-right bg-light">
                            <p class="tag mb-0">Smartphone, IPhone</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mt-2">IPhone 16</h5>
                                <i class="fa-regular fa-heart fs-5"></i>
                            </div>
                            <p class="card-price">$1059.00</p>
                            <a href="#" class="btn btn-primary px-4 py-2 d-inline-block custom-btn w-100">Add to Cart</a>
                        </div>
                      </div>
                </div>

                <!-- Product Card 4 -->
                <div class="col-md-3">
                    <div class="card product-card">
                        <img src="{{ asset('image/Iphone16.jpg') }}" class="card-img-top" alt="...">
                        <div class="card-body text-right bg-light">
                            <p class="tag mb-0">Smartphone, IPhone</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mt-2">IPhone 16</h5>
                                <i class="fa-regular fa-heart fs-5"></i>
                            </div>
                            <p class="card-price">$1059.00</p>
                            <a href="#" class="btn btn-primary px-4 py-2 d-inline-block custom-btn w-100">Add to Cart</a>
                        </div>
                      </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Large Banner -->
    <section>
        <div class="container my-5">
            <div class="container-fluid text-black custom-bg">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="d-flex justify-content-center">
                            <div class="position-relative">
                                <img src="{{ asset('image/oppo2.jpg') }}" alt="...">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 text-center text-md-start">
                        <h2 class="fw-bold">Upgrade to a Fully-fledged <span class="text-uppercase">Electromo!</span></h2>
                        <p class="lead">Featuring additional pages, plugins, beautiful pictures, and full functionality!</p>
                        <a href="#" class="btn btn-primary px-4 py-2 d-inline-block custom-btn">Shop Now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

        <!-- footer -->
    <footer class="custom-footer text-white py-4">
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
                    <h5 class="fw-bold py-2">Help</h5>
                    <ul class="list-unstyled">
                        <li class="py-2"><a href="#" class="text-white text-decoration-none">Privacy Policy</a></li>
                        <li class="py-2"><a href="#" class="text-white text-decoration-none">Terms of Service</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
