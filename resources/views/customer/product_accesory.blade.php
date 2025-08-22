@extends('Layout.headerfooter')
@section('title', 'Product')
@section('header')
    <link rel="stylesheet" href="{{ asset('css/product.css') }}">
    <style>
        .filter-sidebar {
            background: #f8f9fa;
            border-radius: 16px;
            border: 1px solid #e0e0e0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            padding: 24px 18px 18px 18px;
            position: sticky;
            top: 24px;
        }

        .filter-title {
            font-weight: 700;
            font-size: 1.15rem;
            margin-bottom: 1.2rem;
            text-align: left;
            color: #2e3b56;
        }

        .form-check {
            margin-bottom: 1rem;
        }

        .form-check-input.brand-checkbox {
            width: 1.3em;
            height: 1.3em;
            margin-right: 0.6em;
            accent-color: #2e3b56;
            cursor: pointer;
            transition: box-shadow 0.2s;
        }

        .form-check-input.brand-checkbox:focus {
            box-shadow: 0 0 0 2px #b3c6e0;
        }

        .form-check-label {
            font-size: 1rem;
            cursor: pointer;
            transition: color 0.2s;
        }

        .form-check-input.brand-checkbox:hover+.form-check-label,
        .form-check-label:hover {
            color: #1a2233;
        }

        #clearAllBrands {
            font-size: 0.95rem;
            color: #2e3b56;
            border: 1px solid #2e3b56;
            border-radius: 6px;
            padding: 2px 14px;
            background: #fff;
            margin-top: 0.5rem;
            transition: background 0.2s, color 0.2s;
        }

        #clearAllBrands:hover {
            background: #2e3b56;
            color: #fff;
            text-decoration: none;
        }

        @media (max-width: 991.98px) {
            .filter-sidebar {
                position: static;
                margin-bottom: 18px;
                padding: 18px 10px 10px 10px;
            }
        }
    </style>
@endsection
@section('content')
    <div class="container mt-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
            <div class="d-flex align-items-center gap-2">
                <!-- Brand Filter Dropdown -->
                <div class="dropdown me-2">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" id="brandDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <span id="selectedBrand">All Brands</span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="brandDropdown">
                        <li><a class="dropdown-item brand-option active" href="#" data-brand="All Brands">All
                                Brands</a></li>
                        @foreach ($brands as $brand)
                            <li><a class="dropdown-item brand-option" href="#"
                                    data-brand="{{ $brand->name }}">{{ $brand->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <!-- Sort Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <span id="selectedSort">Sort: Default</span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                        <li><a class="dropdown-item sort-option active" href="#" data-sort="default">Default</a></li>
                        <li><a class="dropdown-item sort-option" href="#" data-sort="price-asc">Price: Low to High</a>
                        </li>
                        <li><a class="dropdown-item sort-option" href="#" data-sort="price-desc">Price: High to
                                Low</a></li>
                        <li><a class="dropdown-item sort-option" href="#" data-sort="name-asc">Name: A-Z</a></li>
                        <li><a class="dropdown-item sort-option" href="#" data-sort="name-desc">Name: Z-A</a></li>
                    </ul>
                </div>
            </div>
            <div class="d-flex w-50">
                <form id="searchForm" class="d-flex w-100" onsubmit="return false;">
                    <input type="text" id="searchInput" class="form-control w-100" placeholder="Search for accessory">
                    <button type="submit" class="btn btn-dark ms-2">Search</button>
                </form>
            </div>
        </div>
        <section class="my-5">
            <div class="container scroll-animate">
                <h2 class="text-right mb-4" style="font-size: 25px;"><b>{{ $category->name }}</b></h2>
                <div class="row g-4">
                    @if ($products->isNotEmpty())
                        @foreach ($products as $product)
                            @php
                                $images = json_decode($product->images, true);
                                $brandName = null;
                                foreach ($brands as $brand) {
                                    if (isset($product->brand_id) && $brand->id == $product->brand_id) {
                                        $brandName = $brand->name;
                                        break;
                                    }
                                }
                            @endphp
                            <div class="col-md-3 product-item" data-brand="{{ $brandName }}">
                                <div class="card product-card">
                                    @if (!empty($images) && isset($images[0]))
                                        <a href="{{ route('product.show', $product->id) }}">
                                            <img src="{{ asset('storage/' . $images[0]) }}" class="card-img-top product-img"
                                                style="height: 250px; object-fit: cover; object-position: center; width: 100%;">
                                        </a>
                                    @endif
                                    <div class="card-body text-righ" style="background-color: #ecdceb;">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <p class="card-title mt-2 product-title fs-6">{{ $product->name }}</p>
                                            <i class="fa-regular fa-heart fs-5 add-wishlist"></i>
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
                    @else
                        <div class="text-center w-100" style="height: 400px; line-height: 400px; font-size: 1.5rem; color: #555;">
                            No products found in this category.
                        </div>
                    @endif

                    <!-- No product found message -->
                    <div id="noProductsMessage" class="text-center w-100"
                        style="display: none; height: 400px; line-height: 400px; font-size: 1.5rem; color: #555;">
                        No product found
                    </div>
                </div>
            </div>
        </section>
    </div>
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
            // Search
            const searchInput = document.getElementById('searchInput');
            const searchForm = document.getElementById('searchForm');

            let currentBrand = 'All Brands';
            let currentSort = 'default';
            let currentSearch = '';

            // function filterAndSort() {
            //     // Filter
            //     productItems.forEach(item => {
            //         const brand = item.getAttribute('data-brand');
            //         const name = item.querySelector('.product-title').textContent.trim().toLowerCase();
            //         const matchesBrand = (currentBrand === 'All Brands' || brand === currentBrand);
            //         const matchesSearch = (!currentSearch || name.includes(currentSearch));
            //         if (matchesBrand && matchesSearch) {
            //             item.style.display = '';
            //         } else {
            //             item.style.display = 'none';
            //         }
            //     });
            //     // Sort
            //     let visibleItems = productItems.filter(item => item.style.display !== 'none');
            //     let container = visibleItems.length ? visibleItems[0].parentElement : null;
            //     if (container) {
            //         let sorted = [...visibleItems];
            //         if (currentSort === 'price-asc' || currentSort === 'price-desc') {
            //             sorted.sort((a, b) => {
            //                 let priceA = parseFloat(a.querySelector('.card-price').textContent.replace(/[^\d.]/g, ''));
            //                 let priceB = parseFloat(b.querySelector('.card-price').textContent.replace(/[^\d.]/g, ''));
            //                 return currentSort === 'price-asc' ? priceA - priceB : priceB - priceA;
            //             });
            //         } else if (currentSort === 'name-asc' || currentSort === 'name-desc') {
            //             sorted.sort((a, b) => {
            //                 let nameA = a.querySelector('.product-title').textContent.trim().toLowerCase();
            //                 let nameB = b.querySelector('.product-title').textContent.trim().toLowerCase();
            //                 if (nameA < nameB) return currentSort === 'name-asc' ? -1 : 1;
            //                 if (nameA > nameB) return currentSort === 'name-asc' ? 1 : -1;
            //                 return 0;
            //             });
            //         }
            //         // Re-append sorted items
            //         sorted.forEach(item => container.appendChild(item));
            //     }
            // }
            function filterAndSort() {
                // Filter
                productItems.forEach(item => {
                    const brand = item.getAttribute('data-brand');
                    const name = item.querySelector('.product-title').textContent.trim().toLowerCase();
                    const matchesBrand = (currentBrand === 'All Brands' || brand === currentBrand);
                    const matchesSearch = (!currentSearch || name.includes(currentSearch));
                    if (matchesBrand && matchesSearch) {
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
                            let priceA = parseFloat(a.querySelector('.card-price').textContent.replace(
                                /[^\d.]/g, ''));
                            let priceB = parseFloat(b.querySelector('.card-price').textContent.replace(
                                /[^\d.]/g, ''));
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

                // Show "No product found" if no visible items
                const noMessage = document.getElementById('noProductsMessage');
                if (visibleItems.length === 0) {
                    noMessage.style.display = 'block';
                } else {
                    noMessage.style.display = 'none';
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
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    currentSearch = searchInput.value.trim().toLowerCase();
                    filterAndSort();
                });
                searchForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    currentSearch = searchInput.value.trim().toLowerCase();
                    filterAndSort();
                });
            }
        });
    </script>
@endsection
