@extends('Layout.headerfooter')
@section('title', 'Homepage')
@section('content')
<header>
    <div id="carouselExampleCaptions" class="carousel slide custom-carousel-bg" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            @foreach ([
                'slide.jpg',
                'img1.jpg',
                'slide2.jpg'
            ] as $index => $slide)
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
        <button class="carousel-control-prev custom-carousel" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
            <i class="fa-solid fa-circle-chevron-left fa-2x"></i>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next custom-carousel" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
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
        @foreach ([
            ['src' => 'image/phone_ps2.png', 'route' => 'product', 'name' => 'smartphone', 'label' => 'Smartphones'],
            ['src' => 'image/accessories1.jpg', 'route' => 'product_acessory', 'name' => 'accessories', 'label' => 'Accessories']
        ] as $item)
            <div class="col-md-6 d-flex justify-content-center position-relative">
                <div class="shop-card">
                    <img src="/{{ $item['src'] }}" alt="{{ $item['label'] }}" class="img-fluid">
                    <div class="shop-overlay d-flex align-items-center justify-content-center">
                        <a href="{{ route($item['route'], $item['name']) }}" class="btn btn-primary px-4 py-2 d-inline-block custom-btn">Shop Now</a>
                    </div>
                    <h3 class="shop-title text-uppercase fs-4 fw-semibold">{{ $item['label'] }}</h3>
                </div>
            </div>
        @endforeach
    </div>
</div>

</section>
<section>
    <div class="container my-5 scroll-animate">
        <h2 class="text-right mb-4 fs-4"><b>New Products</b></h2>
        <div class="row g-4">
            @foreach ($products->take(10) as $product)
                @php $images = json_decode($product->images, true); @endphp
                <div class="col-md-3">
                    <div class="card product-card" style="height:400px;">
                        @if (!empty($images[0]))
                            <a href="{{ route('product.show', $product->id) }}">
                                <img src="{{ asset('storage/' . $images[0]) }}" class="card-img-top product-img" style="height: 270px;">
                            </a>
                        @endif
                        <div class="card-body text-right" style="background-color: #ecdceb;">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mt-2 product-title">{{ $product->name }}</h5>
                                <i class="fa-regular fa-heart fs-5 add-wishlist" data-product-id="{{ $product->id }}"></i> 
                            </div>
                            <p class="card-price">{{ $product->price }}</p>
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
<section>
    <div class="container my-5">
        <div class="row g-3">
            @foreach ([
                'Mobile.jpg',
                'airpods.jpg'
            ] as $banner)
                <div class="col-md-6 position-relative scroll-animate">
                    <img src="{{ asset('image/' . $banner) }}" class="img-fluid" style="object-fit: cover; height: 100%; width: 100%; filter: brightness(65%);">
                    <div class="position-absolute top-50 start-20 translate-middle-y text-white text-start p-3">
                        <h3 class="fw-bold mb-3">Let's Order Now!</h3>
                        <p class="mb-4">Special launch offers available for a limited time.</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Accessories Section --}}
<section class="my-5">
    <div class="container scroll-animate">
        <h2 class="text-right mb-4 fs-4"><b>Accessories</b></h2>
        <div class="row g-4">
            @if ($accessoryProducts->isNotEmpty())
                @foreach ($accessoryProducts->take(4) as $product)
                    @php $images = json_decode($product->images, true); @endphp
                    <div class="col-md-3">
                    <div class="card product-card" style="height:400px;">
                        @if (!empty($images[0]))
                        <a href="{{ route('product.show', $product->id) }}">
                            <img src="{{ asset('storage/' . $images[0]) }}" class="card-img-top product-img" style="height: 270px;">
                        </a>
                        @endif
                        <div class="card-body text-right" style="background-color: #ecdceb;">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mt-2 product-title">{{ $product->name }}</h5>
                                <i class="fa-regular fa-heart fs-5 add-wishlist" data-product-id="{{ $product->id }}"></i>
                            </div>
                            <p class="card-price">{{ $product->price }}</p>
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
    </div>
</section>
<section class="my-5">
    <div class="container scroll-animate">
        <h2 class="text-right mb-4" style="font-size: 25px;"><b>Phone</b></h2>
        <div class="row g-4">
            @if ($phone->isNotEmpty())
                @foreach ($phone->take(4) as $product)
                    @php
                        $images = json_decode($product->images, true);
                    @endphp
                    <div class="col-md-3">
                    <div class="card product-card" style="height:400px;">
                        @if (!empty($images[0]))
                        <a href="{{ route('product.show', $product->id) }}">
                            <img src="{{ asset('storage/' . $images[0]) }}" class="card-img-top product-img" style="height: 270px;">
                        </a>
                        @endif
                        <div class="card-body text-right" style="background-color: #ecdceb;">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mt-2 product-title">{{ $product->name }}</h5>
                                <i class="fa-regular fa-heart fs-5 add-wishlist" data-product-id="{{ $product->id }}"></i>
                            </div>
                            <p class="card-price">{{ $product->price }}</p>
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
    </div>
</section>
<section>
    <div class="container my-5 scroll-animate">
        <div class="container-fluid text-black custom-bg">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex justify-content-center">
                        <img src="{{ asset('image/oppo2.jpg') }}" alt="..." class="img-fluid">
                    </div>
                </div>
                <div class="col-md-6 text-center text-md-start">
                    <h2 class="fw-bold">Upgrade to a Fully-fledged <span class="text-uppercase">Electromo!</span></h2>
                    <p class="lead">Featuring additional pages, plugins, beautiful pictures, and full functionality!</p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
