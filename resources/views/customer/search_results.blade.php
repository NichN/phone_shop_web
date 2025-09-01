@extends('Layout.headerfooter')
@section('title', 'Search Results')
@section('content')
<link rel="stylesheet" href="{{ asset('css/homepage.css') }}">
<script src="{{ asset('js/wishlist.js') }}"></script>
<div class="container my-5">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Search Results for "{{ $query }}"</h2>
            
            @if($products->count() > 0)
                <p class="text-muted mb-4">Found {{ $products->count() }} product(s)</p>
                
                <div class="row g-4">
                    @foreach ($products as $product)
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
                                        <div class="d-flex gap-2">
                                            <i class="fa-solid fa-cart-plus fs-5 add-cart-quick"
                                                data-product-pro-id="{{ $product->id }}"
                                                data-product-item-id="{{ $product->product_item_id }}"
                                                style="cursor: pointer; color: #007bff;"></i>
                                            <i class="fa-regular fa-heart fs-5 add-wishlist"
                                                data-product-pro-id="{{ $product->id }}"
                                                data-product-item-id="{{ $product->product_item_id }}">
                                            </i>
                                        </div>
                                    </div>
                                    <p class="card-price"> ${{ $product->price }}</p>
                                    <p class="color" style="text-align: right;">
                                        @if(!empty($product->color_code))
                                            <span class="rounded-circle d-inline-block mx-1"
                                                style="width: 20px; height: 20px; background-color: {{ strtolower($product->color_code) }}; margin-bottom: 20px;"
                                                title="{{ $product->color_code }}">
                                            </span>
                                        @endif
                                    </p>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No products found</h4>
                    <p class="text-muted">Try searching with different keywords or browse our categories.</p>
                    <a href="{{ route('homepage') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-home me-2"></i>Back to Homepage
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@if($products->count() > 0)
<div class="container my-5">
    <div class="text-center">
        <h5 class="mb-3">Didn't find what you're looking for?</h5>
        <a href="{{ route('homepage') }}" class="btn btn-outline-primary me-2">
            <i class="fas fa-home me-1"></i>Browse All Products
        </a>
        <a href="{{ route('contact_us') }}" class="btn btn-outline-secondary">
            <i class="fas fa-envelope me-1"></i>Contact Us
        </a>
    </div>
</div>
@endif


@endsection
