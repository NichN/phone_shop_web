@extends('Layout.headerfooter')

@section('title', 'ProductDetail')

@section('header')
    <div class="container mt-3">
        <nav>
            <a href="{{ route('homepage') }}" style="text-decoration: none; color: inherit;">Home</a> •
            <a href="{{ route('product') }}" style="text-decoration: none; color: inherit;">Smartphone</a>
        </nav>
    </div>
@endsection
@section('content')
    <link rel="stylesheet" href="{{ asset('css/productdetail.css') }}">

    <div class="container my-3">
        <div class="row mt-3">
            <!-- Product Images -->
            <div class="col-md-6">
                <div class="main-image-container border p-3">
                    <img id="mainImage" src="{{ asset('storage/' . $product['image'][0]) }}" alt="{{ $product['name'] }}" class="img-fluid">
                </div>
                <div class="row thumbnail mt-3">
                    @foreach ($product['image'] as $index => $img)
                        <div class="col-3">
                            <img src="{{ asset('storage/' . $img) }}"
                                 class="thumbnail-img img-fluid {{ $index === 0 ? 'selected-thumbnail' : '' }}"
                                 onclick="changeImage(this)" alt="Thumbnail {{ $index + 1 }}">
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-md-6">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h4 class="mb-0">{{ $product['name'] }}</h4>
                </div>
                <h3 class="text-danger mb-4"><strong>{{ $product['price'] }}</strong></h3>

                <!-- Color Selection -->
                <div class="choose-color mb-4">
                    <h5 class="mb-4 fw-bold">CHOOSE YOUR COLOR</h5>
                    <div class="d-flex gap-3">
                        @foreach ($colors as $index => $color)
                            <input type="radio" class="btn-check" name="color" id="color{{ $index }}" autocomplete="off" value="{{ $color }}" {{ $index === 0 ? 'checked' : '' }}>
                            <label class="btn btn-outline-primary d-flex flex-column align-items-center justify-content-center" for="color{{ $index }}">
                                <span class="rounded-circle d-block" style="width: 20px; height: 20px; background-color: {{ strtolower($color) }};"></span>
                                {{ ucfirst($color) }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Storage Selection -->
                <div class="choose-storage">
                    <h5 class="mb-4 fw-bold">CHOOSE YOUR STORAGE</h5>
                    <div class="d-flex gap-3">
                        @foreach ($sizes as $index => $size)
                            <input type="radio" class="btn-check" name="storage" id="storage{{ $index }}" autocomplete="off" value="{{ $size }}" {{ $index === 0 ? 'checked' : '' }}>
                            <label class="btn btn-outline-success" for="storage{{ $index }}">{{ $size }}</label>
                        @endforeach
                    </div>
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-between align-items-center add-to-cart mt-4 mb-4 gap-3">
                    <a href="#" class="btn btn-primary px-4 py-2 custom-btn w-100 add-cart"
                       data-title="{{ $product['name'] }}"
                       data-price="{{ $product['price'] }}"
                       data-img="{{ asset('storage/' . $product['image'][0]) }}">
                        Add to Cart
                    </a>
                    <a href="#" class="btn btn-primary px-4 py-2 custom-btn w-100 d-flex align-items-center justify-content-center add-wishlist">
                        <i class="fa-regular fa-heart fs-5 me-2"></i> Add to Wishlist
                    </a>
                </div>

                <!-- Specification Accordion -->
                <div class="mt-4">
                    <h5 class="fw-bold">SPECIFICATION</h5>
                    <div class="accordion" id="specAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#launchCollapse">
                                    LAUNCH
                                </button>
                            </h2>
                            <div id="launchCollapse" class="accordion-collapse collapse" data-bs-parent="#specAccordion">
                                <div class="accordion-body">
                                    <p><strong>Announced:</strong> {{ $product['created_at'] }}</p>
                                    <p><strong>Status:</strong> Available</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>
    <section>
        <div class="container my-5 scroll-animate">
            <h2 class="text-center mb-4">Similar Product</h2>
            <div class="row g-4">
                @foreach ($products as $product)
                    <div class="col-md-3">
                        <div class="card product-card">
                            {{-- <a href="{{ route('product.show', $product->id) }}"> --}}
                            <img src="{{ asset('storage/' . $product['image']) }}" class="card-img-top product-img" alt="{{ $product['name'] }}">
                            {{-- </a> --}}
                            <div class="card-body text-right bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mt-2 product-title">{{ $product['name'] }}</h5>
                                    <i class="fa-regular fa-heart fs-5 add-wishlist"></i>
                                </div>
                                <p class="card-price">{{ $product['price'] }}</p>
                                <a href="#" class="btn btn-primary px-4 py-2 d-inline-block custom-btn w-100 add-cart"data-product-id="{{ $product->id }}">Add to Cart</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <script src="{{ asset('js/productdetail.js') }}"></script>
@endsection
