@extends('Layout.headerfooter')
@section('title', 'Homepage')
@section('content')
    <header>
        <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
        <link href="{{ asset('css/search.css') }}" rel="stylesheet">
        <div id="carouselExampleCaptions" class="carousel slide custom-carousel-bg" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active"
                    aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"
                    aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"
                    aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                @foreach (['slide.jpg', 'img1.jpg', 'slide2.jpg'] as $index => $slide)
                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                        <div class="container-fluid d-flex align-items-center" style="height: 75vh;">
                            <div class="container d-flex justify-content-between align-items-center">

                                <div class="text-container pt-5">
                                    <h2 class="display-4 fw-bold">Welcome to <br> TayMeng Phone Shop</h2>
                                    <p>Power Up Your Life with the Latest Electronic!</p>
                                </div>
                                <div class="image-container">
                                    <img src="{{ asset('image/' . $slide) }}" alt="" class="img-fluid">
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <button class="carousel-control-prev custom-carousel" type="button" data-bs-target="#carouselExampleCaptions"
                data-bs-slide="prev">
                <i class="fa-solid fa-circle-chevron-left fa-2x"></i>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next custom-carousel" type="button" data-bs-target="#carouselExampleCaptions"
                data-bs-slide="next">
                <i class="fa-solid fa-circle-chevron-right fa-2x"></i>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </header>

    {{-- Wishlist Modal --}}
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
                            <!-- Wishlist items here -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="container my-5 scroll-animate position-relative">
    <div class="row d-flex justify-content-center">
        @foreach ($categories as $category)
    <div class="col-md-6 d-flex justify-content-center position-relative">
        <div class="shop-card">
            <img src="{{ asset('storage/' . $category->image) }}" 
                alt="{{ $category->name }}" 
                class="img-fluid">
            <div class="shop-overlay d-flex align-items-center justify-content-center">
                <a href="{{ route('product_by_category', $category->id) }}"
                   class="btn btn-primary px-4 py-2 d-inline-block custom-btn">Shop Now</a>
            </div>

            <h3 class="shop-title text-uppercase fs-5 fw-semibold">{{ $category->name ?? 'No Name' }}</h3>
        </div>
    </div>
@endforeach

    </div>
</div>

    </section>
    
    {{-- Brand Grid Section --}}
    <section class="brand-section">
        <div class="container my-4 scroll-animate">
            <h2 class="text-right mb-3" style="font-size: 22px;"><b>Shop by Brand</b></h2>
            <div class="row g-3" id="brand-grid">
                @foreach ($brands as $index => $brand)
                    <div class="col-lg-2 col-md-3 col-sm-4 col-6 brand-item {{ $index >= 6 ? 'brand-hidden' : '' }}" data-index="{{ $index }}">
                        <div class="brand-card" data-brand="{{ strtolower($brand->name) }}">
                            <a href="{{ route('product_by_brand', $brand->id) }}" class="brand-link">
                                <div class="brand-content">
                                    <h3 class="brand-name">{{ $brand->name }}</h3>
                                    <div class="brand-icon">
                                        @php
                                            $brandName = strtolower($brand->name);
                                            $iconClass = 'fas fa-mobile-alt'; // default icon
                                            
                                            if (strpos($brandName, 'apple') !== false || strpos($brandName, 'iphone') !== false) {
                                                $iconClass = 'fab fa-apple';
                                            } elseif (strpos($brandName, 'samsung') !== false) {
                                                $iconClass = 'fas fa-mobile-alt';
                                            } elseif (strpos($brandName, 'huawei') !== false) {
                                                $iconClass = 'fas fa-mobile-alt';
                                            } elseif (strpos($brandName, 'oppo') !== false) {
                                                $iconClass = 'fas fa-mobile-alt';
                                            } elseif (strpos($brandName, 'xiaomi') !== false) {
                                                $iconClass = 'fas fa-mobile-alt';
                                            } elseif (strpos($brandName, 'oneplus') !== false) {
                                                $iconClass = 'fas fa-mobile-alt';
                                            } elseif (strpos($brandName, 'google') !== false) {
                                                $iconClass = 'fab fa-google';
                                            } elseif (strpos($brandName, 'sony') !== false) {
                                                $iconClass = 'fas fa-mobile-alt';
                                            } elseif (strpos($brandName, 'lg') !== false) {
                                                $iconClass = 'fas fa-mobile-alt';
                                            } elseif (strpos($brandName, 'nokia') !== false) {
                                                $iconClass = 'fas fa-mobile-alt';
                                            }
                                        @endphp
                                        <i class="{{ $iconClass }}"></i>
                                    </div>
                                    <div class="brand-arrow">
                                        <i class="fas fa-arrow-right"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            
            @if(count($brands) > 6)
                <div class="text-center mt-3" id="brand-toggle-container">
                    <button class="btn btn-outline-primary px-3 py-1" id="brand-toggle-btn" onclick="toggleBrands()">
                        <i class="fas fa-chevron-down me-1"></i>See More Brands <span class="badge bg-secondary ms-1">{{ count($brands) - 6 }}</span>
                    </button>
                </div>
            @endif
            
            <script>
            function toggleBrands() {
                console.log('Toggle function called');
                const hiddenBrands = document.querySelectorAll('.brand-hidden');
                const toggleBtn = document.getElementById('brand-toggle-btn');
                const isExpanded = toggleBtn.classList.contains('expanded');
                
                console.log('Hidden brands found:', hiddenBrands.length);
                console.log('Is expanded:', isExpanded);
                
                if (!isExpanded) {
                    // Show hidden brands
                    hiddenBrands.forEach((brand, index) => {
                        setTimeout(() => {
                            brand.classList.add('show');
                            console.log('Showed brand', index);
                        }, index * 100);
                    });
                    
                    toggleBtn.innerHTML = '<i class="fas fa-chevron-up me-1"></i>See Less Brands';
                    toggleBtn.classList.add('expanded');
                } else {
                    // Hide brands
                    hiddenBrands.forEach((brand, index) => {
                        setTimeout(() => {
                            brand.classList.remove('show');
                            console.log('Hid brand', index);
                        }, index * 50);
                    });
                    
                    toggleBtn.innerHTML = '<i class="fas fa-chevron-down me-1"></i>See More Brands <span class="badge bg-secondary ms-1">' + hiddenBrands.length + '</span>';
                    toggleBtn.classList.remove('expanded');
                }
            }
            </script>
        </div>
    </section>
    
    <section>
        <div class="container my-5 scroll-animate">
            <h2 class="text-right mb-4" style="font-size: 25px;"><b>New Products</b></h2>
            <div class="row g-4">
                @foreach ($products->take(16) as $product)
                    @php $images = json_decode($product->images, true); @endphp
                    <div class="col-md-3">
                        <div class="card product-card" style="height:400px;">
                            @if (!empty($images[0]))
                                <a href="{{ route('product.show', $product->id) }}">
                                    <img src="{{ asset('storage/' . $images[0]) }}" class="card-img-top product-img"
                                        style="object-fit: cover; object-position: center; width: 100%; height:250px;">
                                </a>
                            @endif
                            <div class="card-body text-right" style="background-color: #ecdceb;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="card-title mt-2 product-title">{{ $product->name }}</h6>
                                    <i class="fa-regular fa-heart fs-5 add-wishlist"
                                        data-product-pro-id="{{ $product->id }}"
                                        data-product-item-id="{{ $product->product_item_id }}">
                                        </i>

                                </div>
                                <p class="card-price"> ${{ $product->price }}</p>
                                <p class="color" style="text-align: right;">
                                    @foreach ($product->colors as $color)
                                        <span class="rounded-circle d-inline-block mx-1"
                                            style="width: 20px; height: 20px; background-color: {{ strtolower($color) }}; margin-bottom: 20px;"
                                            title="{{ $color }}">
                                        </span>
                                    @endforeach
                                </p>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    {{-- Banner Section --}}
    {{-- <section>
        <div class="container my-5">
            <div class="row g-3">
                @foreach (['samsungzflip.png', 'airpods.jpg'] as $banner)
                    <div class="col-md-6 position-relative scroll-animate">
                        <img src="{{ asset('image/' . $banner) }}" class="img-fluid banner-img"
                            style="object-fit: cover; height: 100%; width: 100%; filter: brightness(80%);">
                        <div class="position-absolute top-50 start-20 translate-middle-y text-white text-start p-3">
                            <h3 class="fw-bold mb-3">Let's Order Now!</h3>
                            <p class="mb-4">Special launch offers available for a limited time.</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section> --}}

    <section>
        <div class="container my-5">
            <div class="row g-3">
                @foreach (['samsungzflip.png', 'galaxy.jpg'] as $banner)
                    <div class="col-md-6 position-relative scroll-animate">
                        <div class="banner-wrapper position-relative overflow-hidden shadow-sm">
                            <img src="{{ asset('image/' . $banner) }}" class="img-fluid banner-img"
                                style="object-fit: cover; height: 100%; width: 100%; transition: transform 0.5s ease, filter 0.5s ease;">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Accessories Section --}}
    <section class="my-5">
        <div class="container scroll-animate">
            <h2 class="text-right mb-4" style="font-size: 25px;"><b>Accessories</b></h2>
            <div class="row g-4">
                @if ($accessoryProducts->isNotEmpty())
                    @foreach ($accessoryProducts->shuffle()->take(4) as $product)
                        @php $images = json_decode($product->images, true); @endphp
                        <div class="col-md-3">
                            <div class="card product-card" style="height:400px;">
                                @if (!empty($images[0]))
                                    <a href="{{ route('product.show', $product->id) }}">
                                        <img src="{{ asset('storage/' . $images[0]) }}" class="card-img-top product-img"
                                            style="object-fit: cover; object-position: center; width: 100%; height:250px;">
                                    </a>
                                @endif
                                <div class="card-body text-right" style="background-color: #ecdceb;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="card-title mt-2 product-title">{{ $product->name }}</h6>
                                    <i class="fa-regular fa-heart fs-5 add-wishlist"
                                        data-product-pro-id="{{ $product->id }}"
                                        data-product-item-id="{{ $product->product_item_id }}">
                                        </i>

                                </div>
                                <p class="card-price"> ${{ $product->price }}</p>
                                <p class="color" style="text-align: right;">
                                    @foreach ($product->colors as $color)
                                        <span class="rounded-circle d-inline-block mx-1"
                                            style="width: 20px; height: 20px; background-color: {{ strtolower($color) }}; margin-bottom: 20px;"
                                            title="{{ $color }}">
                                        </span>
                                    @endforeach
                                </p>

                            </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="text-end mt-3">
                <a href="{{ route('product_acessory') }}" class="text-decoration-none btn btn-light"
                    style="color: black">
                    Shop More
                </a>
            </div>
        </div>
    </section>
    <section class="my-5">
        <div class="container scroll-animate">
            <h2 class="text-right mb-4" style="font-size: 25px;"><b>Smartphone</b></h2>
            <div class="row g-4">
                @if ($phone->isNotEmpty())
                    @foreach ($phone->shuffle()->take(4) as $product)
                        @php
                            $images = json_decode($product->images, true);
                        @endphp
                        <div class="col-md-3">
                            <div class="card product-card" style="height:400px;">
                                @if (!empty($images[0]))
                                    <a href="{{ route('product.show', $product->id) }}">
                                        <img src="{{ asset('storage/' . $images[0]) }}" class="card-img-top product-img"
                                            style="object-fit: cover; object-position: center; width: 100%; height:250px;">
                                    </a>
                                @endif
                                <div class="card-body text-right" style="background-color: #ecdceb;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="card-title mt-2 product-title">{{ $product->name }}</h6>
                                    <i class="fa-regular fa-heart fs-5 add-wishlist"
                                        data-product-pro-id="{{ $product->id }}"
                                        data-product-item-id="{{ $product->product_item_id }}">
                                        </i>

                                </div>
                                <p class="card-price"> ${{ $product->price }}</p>
                                <p class="color" style="text-align: right;">
                                    @foreach ($product->colors as $color)
                                        <span class="rounded-circle d-inline-block mx-1"
                                            style="width: 20px; height: 20px; background-color: {{ strtolower($color) }}; margin-bottom: 20px;"
                                            title="{{ $color }}">
                                        </span>
                                    @endforeach
                                </p>

                            </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="text-end mt-3">
                <a href="{{ route('product') }}" class="btn btn-light">
                    Shop More
                </a>
            </div>
        </div>
    </section>
    <section>
        <div class="container my-5 scroll-animate">
            <div class="container-fluid text-black custom-bg">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="d-flex justify-content-center">
                            {{-- <img src="{{ asset('image/oppo2.jpg') }}" alt="..." class="img-fluid"> --}}
                            <img src="{{ asset('image/huaweipura.png') }}" alt="..." class="img-fluid">
                        </div>
                    </div>
                    {{-- <div class="col-md-6 text-center text-md-start"> --}}
                    <div class="col-md-6 text-center text-md-start" style="padding: 50px;">
                        {{-- <h2 class="fw-bold">Upgrade to a Fully-fledged <span class="text-uppercase">Electromo!</span></h2> --}}
                        <h2 class="fw-bold">Huawei Pura 80 Serious</h2>
                        {{-- <p class="lead">Featuring additional pages, plugins, beautiful pictures, and full functionality!</p> --}}
                        <p class="lead text-justify" style="font-size: 1rem; text-align: justify; line-height: 1.8;">
                            Flagship Pura 80 Ultra
                            combines premium design with top-tier performance. It has a 6.8-inch LTPO OLED display and
                            runs on the Kirin 9020 chip with HarmonyOS 5.1. Its standout camera system includes a 50MP
                            1-inch
                            main sensor and dual switchable telephoto lens, plus a 40MP ultra-wide shooter. The 5,700 mAh
                            battery supports 100 W wired and 80 W wireless fast charging.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
