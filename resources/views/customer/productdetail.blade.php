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
            <!-- Main Product Image -->
            <div class="col-md-6">
                <div class="main-image-container border p-3">
                    {{-- <img id="mainImage" src="{{ asset('image/iphone16.jpg') }}" alt="Main Image" class="img-fluid"> --}}
                    <img id="mainImage" src="{{ $product['image'] }}" alt="{{ $product['name'] }}">
                </div>

                <!-- Thumbnails Row -->
                <div class="row thumbnail mt-3">
                    <div class="col-3">
                        <img src="{{ asset('image/iphone16.jpg') }}" class="thumbnail-img img-fluid selected-thumbnail"
                            onclick="changeImage(this)" alt="Thumbnail 1">
                    </div>
                    <div class="col-3">
                        <img src="{{ asset('image/download.png') }}" class="thumbnail-img img-fluid"
                            onclick="changeImage(this)" alt="Thumbnail 2">
                    </div>
                    <div class="col-3">
                        <img src="{{ asset('image/iphone16.jpg') }}" class="thumbnail-img img-fluid"
                            onclick="changeImage(this)" alt="Thumbnail 3">
                    </div>
                    <div class="col-3">
                        <img src="{{ asset('image/iphone16.jpg') }}" class="thumbnail-img img-fluid"
                            onclick="changeImage(this)" alt="Thumbnail 4">
                    </div>
                </div>
            </div>

            <!-- DetailProduct -->
            <div class="col-md-6">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    {{-- <h2 class="mb-0">iPhone 13</h2>  --}}
                    <h2 class="mb-0">{{ $product['name'] }}</h2>
                </div>

                <div class="d-flex align-items-center mb-4 gap-2">
                    <a href="" class="badge mr-1 p-2 text-white"
                        style="background: #70000E !important; text-decoration: none;">Smartphone</a>
                    <a href="" class="badge mr-1 p-2 text-white"
                        style="background: #70000E !important; text-decoration: none;">iPhone</a>
                    {{-- <a href="#" class="badge mr-1 p-2 text-white" style="background: #70000E !important;">{{ $product['category'] }}</a> --}}
                </div>

                {{-- <h3 class="text-danger mb-4"><strong>$590.00</strong></h3> --}}
                <h3 class="text-danger mb-4"><strong>{{ $product['price'] }}</strong></h3>

                <div class="choose-color mb-4">
                    <h5 class="mb-4 fw-bold">CHOOSE YOUR COLOR</h5>
                    <div class="d-flex gap-3">
                        <input type="radio" class="btn-check" name="color" id="blue" autocomplete="off" checked>
                        <label class="btn btn-outline-primary d-flex flex-column align-items-center justify-content-center"
                            for="blue">
                            <span class="bg-primary"></span> Blue
                        </label>

                        <input type="radio" class="btn-check" name="color" id="green" autocomplete="off">
                        <label class="btn btn-outline-success d-flex flex-column align-items-center justify-content-center"
                            for="green">
                            <span class="bg-success"></span> Green
                        </label>
                    </div>
                </div>

                <div class="choose-storage">
                    <h5 class="mb-4 fw-bold">CHOOSE YOUR STORAGE</h5>
                    <div class="d-flex gap-3">
                        <input type="radio" class="btn-check" name="storage" id="storage256" autocomplete="off" checked>
                        <label class="btn btn-outline-primary d-flex flex-column align-items-center justify-content-center"
                            for="storage256">
                            256GB
                        </label>

                        <input type="radio" class="btn-check" name="storage" id="storage512" autocomplete="off">
                        <label class="btn btn-outline-success d-flex flex-column align-items-center justify-content-center"
                            for="storage512">
                            512GB
                        </label>
                    </div>
                </div>

                <!-- Add to Cart -->
                <div class="d-flex justify-content-between align-items-center add-to-cart mt-4 mb-4 gap-3">
                    {{-- <a href="#" class="btn btn-primary px-4 py-2 d-inline-block custom-btn w-100 add-cart">Add to Cart</a> --}}
                    <a href="#" class="btn btn-primary px-4 py-2 d-inline-block custom-btn w-100 add-cart"
                        data-title="{{ $product['name'] }}" data-price="{{ $product['price'] }}"
                        data-img="{{ asset('images/products/' . $product['image']) }}">
                        Add to Cart
                    </a>
                    <a href="#"
                        class="btn btn-primary px-4 py-2 d-inline-block custom-btn w-100 add-wishlist d-flex align-items-center justify-content-center">
                        <i class="fa-regular fa-heart fs-5 me-2 add-wishlist"></i> Add to Wishlist
                    </a>
                </div>

                <!-- Description -->
                <div class="mt-4">
                    <h5 class="fw-bold">SPECIFICATION</h5>
                    <div class="accordion" id="specAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#networkCollapse">
                                    NETWORK
                                </button>
                            </h2>
                            <div id="networkCollapse" class="accordion-collapse collapse"
                                data-bs-parent="#specAccordion">
                                <div class="accordion-body">
                                    <!-- Network details go here -->
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#launchCollapse">
                                    LAUNCH
                                </button>
                            </h2>
                            <div id="launchCollapse" class="accordion-collapse collapse" data-bs-parent="#specAccordion">
                                <div class="accordion-body">
                                    <p><strong>Announced:</strong> 2021, September 14</p>
                                    <p><strong>Status:</strong> Available. Released 2021, September 24</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#displayCollapse">
                                    DISPLAY
                                </button>
                            </h2>
                            <div id="displayCollapse" class="accordion-collapse collapse"
                                data-bs-parent="#specAccordion">
                                <div class="accordion-body">
                                    <!-- display details go here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- New Products Section -->
    <section>
        <div class="container my-5 scroll-animate">
            <h2 class="text-center mb-4">Similar Product</h2>
            <div class="row g-4">
                @foreach ($products as $product)
                    <!-- Product Card -->
                    <div class="col-md-3">
                        <div class="card product-card">
                            <img src="{{ $product['image'] }}" class="card-img-top product-img"
                                alt="{{ $product['name'] }}">
                            <div class="card-body text-right bg-light">
                                <p class="tag mb-0">{{ $product['category'] }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mt-2 product-title">{{ $product['name'] }}</h5>
                                    <i class="fa-regular fa-heart fs-5 add-wishlist"></i>
                                </div>
                                <p class="card-price">{{ $product['price'] }}</p>
                                <a href="#"
                                    class="btn btn-primary px-4 py-2 d-inline-block custom-btn w-100 add-cart">Add to
                                    Cart</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <script src="{{ asset('js/productdetail.js') }}"></script>
@endsection
