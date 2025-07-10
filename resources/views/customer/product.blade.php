@extends('Layout.headerfooter')

@section('title', 'Product')

@section('header')
<link rel="stylesheet" href="{{ asset('css/product.css') }}">
@endsection

@section('content')
<div class="container mt-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <div class="d-flex align-items-center gap-2">
            <!-- Brand Filter Dropdown -->
            <div class="dropdown me-2">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" id="brandDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <span id="selectedBrand">All Brands</span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="brandDropdown">
                    <li><a class="dropdown-item brand-option active" href="#" data-brand="All Brands">All Brands</a></li>
                    @foreach ($brands as $brand)
                        <li><a class="dropdown-item brand-option" href="#" data-brand="{{ $brand->name }}">{{ $brand->name }}</a></li>
                    @endforeach
                </ul>
            </div>
            <!-- Sort Dropdown -->
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <span id="selectedSort">Sort: Default</span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                    <li><a class="dropdown-item sort-option active" href="#" data-sort="default">Default</a></li>
                    <li><a class="dropdown-item sort-option" href="#" data-sort="price-asc">Price: Low to High</a></li>
                    <li><a class="dropdown-item sort-option" href="#" data-sort="price-desc">Price: High to Low</a></li>
                    <li><a class="dropdown-item sort-option" href="#" data-sort="name-asc">Name: A-Z</a></li>
                    <li><a class="dropdown-item sort-option" href="#" data-sort="name-desc">Name: Z-A</a></li>
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
                    // Find the brand name for this product
                    $brandName = null;
                    foreach ($brands as $brand) {
                        if (isset($product->brand_id) && $brand->id == $product->brand_id) {
                            $brandName = $brand->name;
                            break;
                        }
                    }
                @endphp
                <div class="col-md-3 product-item" data-brand="{{ $brandName ?? 'Unknown' }}">
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Brand filter
    const brandOptions = document.querySelectorAll('.brand-option');
    const selectedBrandSpan = document.getElementById('selectedBrand');
    // Sort
    const sortOptions = document.querySelectorAll('.sort-option');
    const selectedSortSpan = document.getElementById('selectedSort');
    // Products
    const productItems = Array.from(document.querySelectorAll('.product-item'));

    let currentBrand = 'All Brands';
    let currentSort = 'default';

    function filterAndSort() {
        // Filter
        productItems.forEach(item => {
            const brand = item.getAttribute('data-brand');
            if (currentBrand === 'All Brands' || brand === currentBrand) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
        // Sort
        let visibleItems = productItems.filter(item => item.style.display !== 'none');
        let container = visibleItems.length ? visibleItems[0].parentElement : null;
        if (container) {
            let sorted = [...visibleItems];
            if (currentSort === 'price-asc' || currentSort === 'price-desc') {
                sorted.sort((a, b) => {
                    let priceA = parseFloat(a.querySelector('.card-price').textContent.replace(/[^\d.]/g, ''));
                    let priceB = parseFloat(b.querySelector('.card-price').textContent.replace(/[^\d.]/g, ''));
                    return currentSort === 'price-asc' ? priceA - priceB : priceB - priceA;
                });
            } else if (currentSort === 'name-asc' || currentSort === 'name-desc') {
                sorted.sort((a, b) => {
                    let nameA = a.querySelector('.product-title').textContent.trim().toLowerCase();
                    let nameB = b.querySelector('.product-title').textContent.trim().toLowerCase();
                    if (nameA < nameB) return currentSort === 'name-asc' ? -1 : 1;
                    if (nameA > nameB) return currentSort === 'name-asc' ? 1 : -1;
                    return 0;
                });
            }
            // Re-append sorted items
            sorted.forEach(item => container.appendChild(item));
        }
    }

    brandOptions.forEach(option => {
        option.addEventListener('click', function(e) {
            e.preventDefault();
            brandOptions.forEach(o => o.classList.remove('active'));
            this.classList.add('active');
            currentBrand = this.getAttribute('data-brand');
            selectedBrandSpan.textContent = currentBrand;
            filterAndSort();
        });
    });
    sortOptions.forEach(option => {
        option.addEventListener('click', function(e) {
            e.preventDefault();
            sortOptions.forEach(o => o.classList.remove('active'));
            this.classList.add('active');
            currentSort = this.getAttribute('data-sort');
            selectedSortSpan.textContent = 'Sort: ' + this.textContent;
            filterAndSort();
        });
    });
});
</script>
@endsection
