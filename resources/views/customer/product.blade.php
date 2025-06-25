@extends('Layout.headerfooter')

@section('title', 'Product')

@section('header')
<link rel="stylesheet" href="{{ asset('css/product.css') }}">
@endsection

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
       <div class="d-flex align-items-center mb-3">
        <div class="dropdown me-3">
            <button class="btn dropdown-toggle" style="background-color: #2e3b56; color:white;" type="button" data-bs-toggle="dropdown">
                Find by
            </button>
            <ul class="dropdown-menu p-3">
                <li class="mb-4"><input type="radio" name="category" class="form-check-input me-2" checked>All Item</li>
                @foreach ($brands as $brand)
                    <li class="mb-4">
                        <input type="radio" name="category" class="form-check-input me-2" value="{{ $brand->id }}" id="brand_{{ $brand->id }}">
                        <label for="brand_{{ $brand->id }}">{{ $brand->name }}</label>
                    </li>
                @endforeach
                <li class="mb-4"><button class="btn mt-2 w-100" style="background-color: #2e3b56; color:white;">Apply</button></li>
            </ul>
        </div>
    </div>
        <div class="d-flex w-50">
            <form action="{{ route('product.index') }}" method="GET" class="d-flex w-100">
                <input type="text" name="search" class="form-control w-100" placeholder="Search for product" value="{{ request('search') }}">
                <button type="submit" class="btn btn-dark ms-2">Search</button>
            </form>
        </div>  
    </div>
   <!-- Product Grid -->
    <section class="my-5">
    <div class="container scroll-animate">
    <div class="row g-4">
        @if ($phone->isNotEmpty())
            @foreach ($phone as $product)
                @php
                    $images = json_decode($product->images, true);
                @endphp
                <div class="col-md-3">
                    <div class="card product-card">
                        @if (!empty($images) && isset($images[0]))
                            <a href="{{ route('product.show', $product->id) }}">
                               <img src="{{ asset('storage/' . $images[0]) }}" class="card-img-top product-img" style="height: 270px; object-fit: cover; object-position: center; width: 100%;">
                            </a>
                        @endif
                        <div class="card-body text-right" style="background-color: #ecdceb;">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mt-2 product-title">
                                    <a href="{{ route('product.show', $product->id) }}" class="text-decoration-none text-dark">
                                        {{ $product->name }}
                                    </a>
                                </h5>
                                <i class="fa-regular fa-heart fs-5 add-wishlist"></i>
                            </div>
                            <p class="card-price">{{ $product->price }}</p>
                            <p> @foreach ($product->colors as $color)
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
   {{-- <div class="d-flex justify-content-center mt-4">
            {{ $phone->links('vendor.pagination.bootstrap-5') }}
        </div> --}}
</div>
<script src="{{ asset('js/product.js') }}"></script>
@endsection
