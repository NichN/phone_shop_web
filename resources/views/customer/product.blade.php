@extends('Layout.headerfooter')

@section('title', isset($brand) ? $brand->name . ' Products' : 'Product')

@section('header')
    <link rel="stylesheet" href="{{ asset('css/product.css') }}">
@endsection

@section('content')
    <div class="container mt-4">

        
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
            <div class="d-flex align-items-center gap-2">
                <!-- Sort Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
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
                <form id="searchForm" class="d-flex w-100" onsubmit="return false;">
                    <input type="text" id="searchInput" class="form-control w-100" placeholder="Search for smartphone">
                    <button type="submit" class="btn btn-dark ms-2">Search</button>
                </form>
            </div>
        </div>
        <!-- Product Count -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="text-muted mb-0">
                        <i class="fas fa-list me-1"></i>
                        <span id="productCount">
                            @php
                                $productsToShow = isset($products) ? $products : (isset($phone) ? $phone : collect());
                            @endphp
                            {{ $productsToShow->count() }} products
                        </span>
                    </h6>
                </div>
            </div>
        </div>

        <!-- Product Grid -->
        <section class="my-5">
            <div class="container scroll-animate">
                <div class="row g-4">
                    @php
                        $productsToShow = isset($products) ? $products : (isset($phone) ? $phone : collect());
                    @endphp
                    
                    @if ($productsToShow->isNotEmpty())
                        @foreach ($productsToShow as $product)
                            @php
                                $images = json_decode($product->images, true);
                                $brandId = $product->brand_id ?? '';
                            @endphp
                            <div class="col-md-3 product-item" data-brand="{{ $brandId }}">
                                <div class="card product-card" style="height:400px;">
                                    @if (!empty($images) && isset($images[0]))
                                        <a href="{{ route('product.show', $product->id) }}">
                                            <img src="{{ asset('storage/' . $images[0]) }}" class="card-img-top product-img"
                                                style="height: 250px; object-fit: cover; object-position: center; width: 100%;">
                                        </a>
                                    @endif
                                    <div class="card-body text-right" style="background-color: #ecdceb;">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <p class="card-title mt-2 product-title">
                                                <a href="{{ route('product.show', $product->id) }}"
                                                    class="text-decoration-none text-dark fs-6">
                                                    {{ $product->name }}
                                                </a>
                                            </p>
                                            <div class="d-flex gap-2">
                                                <i class="fa-solid fa-cart-plus fs-5 add-cart-quick"
                                                    data-product-pro-id="{{ $product->id }}"
                                                    data-product-item-id="{{ $product->product_item_id }}"
                                                    style="cursor: pointer; color: #007bff;"></i>
                                                <i class="fa-regular fa-heart fs-5 add-wishlist"></i>
                                            </div>
                                        </div>
                                        <p class="card-price">{{ $product->price }}</p>
                                        <p>
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
                    @else
                        <div class="col-12">
                            <div class="d-flex align-items-center justify-content-center"
                                style="height:400px;">
                                <p class="fs-4 text-muted m-0">
                                    @if(isset($brand))
                                        No {{ $brand->name }} products found
                                    @else
                                        No product found
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
        {{-- <div class="d-flex justify-content-center mt-4">
            {{ $phone->links('vendor.pagination.bootstrap-5') }}
        </div> --}}
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get all product cards
            const productItems = Array.from(document.querySelectorAll('.product-item'));
            const originalProducts = [...productItems];
            
            // Store current filter states
            let currentSort = 'default';
            let currentSearch = '';

            function applyFilters() {
                let filteredProducts = originalProducts.filter(card => {
                    // Get product data
                    const productName = card.querySelector('.product-title a').textContent.toLowerCase();
                    
                    // Apply search filter
                    const searchMatch = !currentSearch || productName.includes(currentSearch.toLowerCase());
                    
                    return searchMatch;
                });

                // Sort products
                filteredProducts = sortProducts(filteredProducts, currentSort);

                // Update display
                displayProducts(filteredProducts);
            }

            function sortProducts(products, sortOption) {
                return products.sort((a, b) => {
                    const nameA = a.querySelector('.product-title a').textContent;
                    const nameB = b.querySelector('.product-title a').textContent;
                    const priceA = parseFloat(a.querySelector('.card-price').textContent.replace(/[^\d.]/g, '')) || 0;
                    const priceB = parseFloat(b.querySelector('.card-price').textContent.replace(/[^\d.]/g, '')) || 0;

                    switch(sortOption) {
                        case 'price-asc':
                            return priceA - priceB;
                        case 'price-desc':
                            return priceB - priceA;
                        case 'name-asc':
                            return nameA.localeCompare(nameB);
                        case 'name-desc':
                            return nameB.localeCompare(nameA);
                        default:
                            return 0; // Keep original order
                    }
                });
            }

            function displayProducts(products) {
                const productsContainer = document.querySelector('.row.g-4');
                if (!productsContainer) return;

                // Clear current display
                productsContainer.innerHTML = '';

                if (products.length === 0) {
                    productsContainer.innerHTML = `
                        <div class="col-12">
                            <div class="d-flex align-items-center justify-content-center" style="height:400px;">
                                <p class="fs-4 text-muted m-0">No products found.</p>
                            </div>
                        </div>
                    `;
                } else {
                    // Add filtered products
                    products.forEach(card => {
                        productsContainer.appendChild(card.cloneNode(true));
                    });
                }

                // Update product count
                updateProductCount(products.length);
            }

            function updateProductCount(count) {
                const countElement = document.getElementById('productCount');
                if (countElement) {
                    countElement.textContent = `${count} products`;
                }
            }

            // Sort filter event listeners
            document.querySelectorAll('.sort-option').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Remove active class from all sort items
                    document.querySelectorAll('.sort-option').forEach(i => i.classList.remove('active'));
                    
                    // Add active class to clicked item
                    this.classList.add('active');
                    
                    // Update button text
                    const sortButton = document.querySelector('#selectedSort');
                    if (sortButton) {
                        sortButton.textContent = 'Sort: ' + this.textContent;
                    }
                    
                    // Update current sort
                    currentSort = this.getAttribute('data-sort');
                    
                    // Apply filters
                    applyFilters();
                });
            });

            // Search functionality
            const searchInput = document.getElementById('searchInput');
            const searchForm = document.getElementById('searchForm');

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    currentSearch = this.value;
                    applyFilters();
                });
            }

            if (searchForm) {
                searchForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    currentSearch = searchInput.value;
                    applyFilters();
                });
            }

            // Initialize
            updateProductCount(originalProducts.length);
        });
    </script>
@endsection
