@extends('Layout.headerfooter')

@section('title', 'Homepage')

@section('content')
{{-- Slide --}}
<header>
    <div id="carouselExampleCaptions" class="carousel slide custom-carousel-bg" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>

        <div class="carousel-inner">
            {{-- Slide 1 --}}
            <div class="carousel-item active">
                <div class="container-fluid d-flex align-items-center" style="height: 75vh;">
                    <div class="container d-flex justify-content-between align-items-center">
                        <div class="text-container pt-5">
                            <h2 class="display-4 fw-bold">Welcome to <br> TayMeng Phone Shop</h2>
                            <p>Power Up Your Life with the Latest Electronic!</p>
                        </div>
                        <div class="image-container">
                            <img src="{{ asset('image/slide.jpg') }}" alt="" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Slide 2 --}}
            <div class="carousel-item">
                <div class="container-fluid d-flex align-items-center" style="height: 75vh;">
                    <div class="container d-flex justify-content-between align-items-center">
                        <div class="text-container pt-5">
                            <h2 class="display-4 fw-bold">Welcome to <br> TayMeng Phone Shop</h2>
                            <p>Power Up Your Life with the Latest Electronic!</p>
                        </div>
                        <div class="image-container">
                            <img src="{{ asset('image/img1.jpg') }}" alt="" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Slide 3 --}}
            <div class="carousel-item">
                <div class="container-fluid d-flex align-items-center" style="height: 75vh;">
                    <div class="container d-flex justify-content-between align-items-center">
                        <div class="text-container pt-5">
                            <h2 class="display-4 fw-bold">Welcome to <br> TayMeng Phone Shop</h2>
                            <p>Power Up Your Life with the Latest Electronic!</p>
                        </div>
                        <div class="image-container">
                            <img src="{{ asset('image/slide2.jpg') }}" alt="" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Next & Previous Button --}}
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

{{-- Wishlist --}}
<section>
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
</section>

<!-- Category Products -->
<section>
    <div class="container my-5 scroll-animate">
        <div class="row d-flex justify-content-center">
            <div class="col-md-6 d-flex justify-content-center">
                <div class="shop-card">
                    <img src="/image/phone_ps2.png" alt="Smartphone">
                    <div class="shop-overlay d-flex align-items-center justify-content-center">
                        <a href="{{ route('product', 'smartphone') }}" class="btn btn-primary px-4 py-2 d-inline-block custom-btn">Shop Now</a>
                    </div>
                    <h3 class="shop-title text-uppercase fs-4 fw-semibold">Smartphones</h3>
                </div>
            </div>
            <div class="col-md-6 d-flex justify-content-center">
                <div class="shop-card">
                    <img src="image/accessories1.jpg" alt="Accessories">
                    <div class="shop-overlay d-flex align-items-center justify-content-center">
                        <a href="{{ route('product', 'accessories') }}" class="btn btn-primary px-4 py-2 d-inline-block custom-btn">Shop Now</a>
                    </div>
                    <h3 class="shop-title text-uppercase fs-4 fw-semibold">Accessories</h3>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Feature Products -->
<section>
    <div class="container my-5 scroll-animate">
        <h2 class="text-right mb-4">Feature Products</h2>
        <div class="row g-4">
            @foreach ($products as $product)
            <!-- Product Card -->
            <div class="col-md-3">
                <div class="card product-card">
                    <img src="{{ $product['image'] }}" class="card-img-top product-img" alt="{{ $product['name'] }}">
                    <div class="card-body text-right bg-light">
                        <p class="tag mb-0">{{ $product['category'] }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mt-2 product-title">{{ $product['name'] }}</h5>
                            <i class="fa-regular fa-heart fs-5 add-wishlist"></i>
                        </div>
                        <p class="card-price">{{ $product['price'] }}</p>
                        <a href="#" class="btn btn-primary px-4 py-2 d-inline-block custom-btn w-100 add-cart">Add to Cart</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Banner Products -->
<section>
<div class="container my-5">
    <div class="row g-3">
        <!-- First Column -->
        <div class="col-md-6 position-relative scroll-animate">
            <img src="{{ asset('image/Mobile.jpg') }}" alt="" class="img-fluid" 
                 style="object-fit: cover; height: 100%; width: 100%; filter: brightness(65%);">
            <div class="position-absolute top-50 start-20 translate-middle-y text-white text-start p-3">
                <h3 class="fw-bold mb-3">Let's Order Now!</h3>
                <p class="mb-4">Special launch offers available for a limited time.</p>
            </div>
        </div>

        <!-- Second Column -->
        <div class="col-md-6 position-relative scroll-animate">
            <img src="{{ asset('image/airpods.jpg') }}" alt="" class="img-fluid" 
                 style="object-fit: cover; height: 100%; width: 100%; filter: brightness(65%);">
            <div class="position-absolute top-50 start-20 translate-middle-y text-white text-start p-3">
                <h3 class="fw-bold mb-3">Let's Order Now!</h3>
                <p class="mb-4">Special launch offers available for a limited time.</p>
            </div>
        </div>
    </div>
</div>
</section>

<!-- New Products -->
<section>
    <div class="container my-5 scroll-animate">
        <h2 class="text-right mb-4">New Products</h2>
        <div class="row g-4">
            @foreach ($products as $product)
            <!-- Product Card -->
            <div class="col-md-3">
                <div class="card product-card">
                    <img src="{{ $product['image'] }}" class="card-img-top product-img" alt="{{ $product['name'] }}">
                    <div class="card-body text-right bg-light">
                        <p class="tag mb-0">{{ $product['category'] }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mt-2 product-title">{{ $product['name'] }}</h5>
                            <i class="fa-regular fa-heart fs-5 add-wishlist"></i>
                        </div>
                        <p class="card-price">{{ $product['price'] }}</p>
                        <a href="#" class="btn btn-primary px-4 py-2 d-inline-block custom-btn w-100 add-cart">Add to Cart</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Large Banner -->
<section>
    <div class="container my-5 scroll-animate">
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
                    <a href="#"class="btn btn-primary px-4 py-2 d-inline-block custom-btn">Shop Now</a>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="{{ asset('js/wishlist.js') }}"></script>
<script src="{{ asset('js/homepage.js') }}"></script>
@endsection