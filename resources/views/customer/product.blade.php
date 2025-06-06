@extends('Layout.headerfooter')

@section('title', 'Product')

@section('header')
    <link rel="stylesheet" href="{{ asset('css/product.css') }}">
    <div class="container mt-3">
        <nav>
            <a href="{{ route('homepage') }}" style="text-decoration: none; color: inherit;">Home</a> •
            <a href="{{ route('product') }}" style="text-decoration: none; color: inherit;">Smartphone</a>
        </nav>
    </div>
@endsection

@section('content')
    <div class="container mt-4">
        <!-- Title & Search Bar -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Smartphone</h2>
            <div class="d-flex w-50">
                <form action="{{ route('product') }}" method="GET" class="d-flex w-100">
                    <input type="text" name="search" class="form-control w-100" placeholder="Search for product"
                        value="{{ request('search') }}">
                    <button type="submit" class="btn btn-dark ms-2">Search</button>
                </form>
            </div>
        </div>

        <!-- Filters: Categories, Price, Sorting -->
        <div class="d-flex align-items-center mb-3">
            <!-- Smartphone Category Dropdown -->
            <div class="dropdown me-3">
                <button class="btn btn-dark dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    Smartphone
                </button>
                <ul class="dropdown-menu p-3">
                    <li class="mb-4"><input type="radio" name="category" class="form-check-input me-2" checked>All Item
                    </li>
                    <li class="mb-4"><input type="radio" name="category" class="form-check-input me-2">IPhone</li>
                    <li class="mb-4"><input type="radio" name="category" class="form-check-input me-2">Samsung</li>
                    <li class="mb-4"><input type="radio" name="category" class="form-check-input me-2">Oppo</li>
                    <li class="mb-4"><input type="radio" name="category" class="form-check-input me-2">Vivo</li>
                    <li class="mb-4"><input type="radio" name="category" class="form-check-input me-2">LG</li>
                    <li class="mb-4"><button class="btn btn-secondary mt-2 w-100">Apply</button></li>
                </ul>
            </div>

            <!-- Sort by Category Dropdown -->
            <div class="dropdown me-3">
                <button class="btn btn-dark dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    Sort By
                </button>
                <ul class="dropdown-menu p-3" style="min-width: 210px;">
                    <li class="mb-4"><input type="radio" name="category" class="form-check-input me-2">Most Favorite
                    </li>
                    <li class="mb-4"><input type="radio" name="category" class="form-check-input me-2">Best Selling</li>
                    <li class="mb-4"><input type="radio" name="category" class="form-check-input me-2">Recently
                        Published</li>
                    <li class="mb-4"><input type="radio" name="category" class="form-check-input me-2">Price(High to
                        Low)</li>
                    <li class="mb-4"><input type="radio" name="category" class="form-check-input me-2">Price(Low to
                        High)</li>
                    <li class="mb-4"><button class="btn btn-secondary mt-2 w-100">Apply</button></li>
                </ul>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="row g-4">
            @if ($paginatedProducts->count() > 0)
                @foreach ($paginatedProducts as $product)
                    <!-- Product Card -->
                    @php
                        $images = json_decode($product->images, true);
                    @endphp
                    <div class="col-md-3">
                        <div class="card product-card">
                            @if (!empty($images) && isset($images[0]))
                                <img src="{{ asset('storage/' . $images[0]) }}" class="card-img-top product-img"
                                    alt="Product Image">
                            @endif
                            <div class="card-body text-right bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mt-2 product-title">{{ $product->name }}</h5>
                                    <i class="add-wishlist fa-regular fa-heart fs-5" data-product-id="{{ $product->id }}"></i>
                                </div>
                                <p class="card-price">{{ $product->price }}</p>
                                <a href="#"
                                    class="btn btn-primary px-4 py-2 d-inline-block custom-btn w-100 add-cart">Add to
                                    Cart</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-center text-muted">No products available.</p>
            @endif
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $paginatedProducts->links('vendor.pagination.bootstrap-5') }}
        </div>
    </div>
@endsection
