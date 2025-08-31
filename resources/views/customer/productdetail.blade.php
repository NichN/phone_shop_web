@extends('Layout.headerfooter')

@section('title', 'ProductDetail')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/productdetail.css') }}">

    <div class="container my-5">
        <!-- Filter Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm filter-section">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-filter me-2"></i>Filter & Sort Options
                            <button class="btn btn-sm btn-outline-primary float-end" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </h5>
                    </div>
                    <div class="collapse show" id="filterCollapse">
                    <div class="card-body p-4">
                        <!-- Filter Grid -->
                        <div class="row g-4">
                            <!-- Price Range Filter -->
                            <div class="col-lg-6 col-md-12">
                                <div class="filter-group">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-dollar-sign me-1"></i>Price Range
                                    </label>
                                    <div class="d-flex gap-3">
                                        <div class="flex-fill">
                                            <input type="number" class="form-control" id="minPrice" placeholder="Min Price" min="0">
                                        </div>
                                        <div class="flex-fill">
                                            <input type="number" class="form-control" id="maxPrice" placeholder="Max Price" min="0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Color Filter -->
                            <div class="col-lg-3 col-md-6">
                                <div class="filter-group">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-palette me-1"></i>Color
                                    </label>
                                    <select class="form-select" id="colorFilter">
                                        <option value="">All Colors</option>
                                        @foreach ($color_code as $color)
                                            <option value="{{ $color }}">{{ ucfirst($color) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Size Filter -->
                            <div class="col-lg-3 col-md-6">
                                <div class="filter-group">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-hdd me-1"></i>Storage
                                    </label>
                                    <select class="form-select" id="sizeFilter">
                                        <option value="">All Sizes</option>
                                        @foreach ($sizes as $size)
                                            <option value="{{ $size }}">{{ $size }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Type Filter -->
                            <div class="col-lg-3 col-md-6">
                                <div class="filter-group">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-tag me-1"></i>Type
                                    </label>
                                    <select class="form-select" id="typeFilter">
                                        <option value="">All Types</option>
                                        @foreach ($type as $typeItem)
                                            <option value="{{ $typeItem }}">{{ $typeItem }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Sort Filter -->
                            <div class="col-lg-3 col-md-6">
                                <div class="filter-group">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-sort me-1"></i>Sort By
                                    </label>
                                    <select class="form-select" id="sortFilter">
                                        <option value="default">Default</option>
                                        <option value="price_low_high">Price: Low to High</option>
                                        <option value="price_high_low">Price: High to Low</option>
                                        <option value="name_a_z">Name: A-Z</option>
                                        <option value="name_z_a">Name: Z-A</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Filter Actions -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex gap-3 justify-content-center">
                                    <button class="btn btn-primary" id="applyFilters">
                                        <i class="fas fa-search me-2"></i>Apply Filters
                                    </button>
                                    <button class="btn btn-outline-secondary" id="clearFilters">
                                        <i class="fas fa-times me-2"></i>Clear All
                                    </button>
                                    <button class="btn btn-outline-info" id="resetFilters">
                                        <i class="fas fa-undo me-2"></i>Reset to Default
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row mt-3">
            <div class="col-md-6">
                <div class="main-image-container border">
                    <img id="mainImage" src="{{ asset('storage/' . $product['image'][0]) }}" alt="{{ $product['name'] }}"
                        class="img-fluid" onerror="this.src='{{ asset('images/default-product.jpg') }}'">
                </div>
                {{-- <div class="row thumbnail mt-3">
                    @foreach ($images as $index => $img)
                        <div class="col-3">
                            <img src="{{ asset('storage/' . $img) }}"
                                class="thumbnail-img img-fluid {{ $index === 0 ? 'selected-thumbnail' : '' }}"
                                onclick="changeImage(this)" alt="Thumbnail {{ $index + 1 }}"
                                data-full-image="{{ asset('storage/' . $img) }}"
                                onerror="this.src='{{ asset('images/default-product.jpg') }}'">
                        </div>
                    @endforeach
                </div> --}}
                
                <div class="thumbnail-scroll-container mt-3">
                    <div class="d-flex gap-2">
                        @foreach ($images as $index => $img)
                            <div class="thumbnail-wrapper">
                                <img src="{{ asset('storage/' . $img) }}"
                                    class="thumbnail-img {{ $index === 0 ? 'selected-thumbnail' : '' }}"
                                    onclick="changeImage(this)" data-full-image="{{ asset('storage/' . $img) }}"
                                    onerror="this.src='{{ asset('images/default-product.jpg') }}'"
                                    alt="Thumbnail {{ $index + 1 }}">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h3 class="fw-bold text-primary">{{ $product['name'] }}</h3>
                    {{-- <span class="badge bg-success fs-6" id="product-type" >
                            {{ $product['type'] }}<span class="text-danger">*</span>
                        </span> --}}
                    <span class="badge fs-6" id="product-type" style="background-color: #90EE90; color: #000;">
                        {{ $product['type'] }}<span>*</span>
                    </span>

                </div>
                {{-- <div class="d-flex align-items-center justify-content-between mb-4">
                    <h4 class="mb-0 fw-bold">{{ $product['name'] }}</h4>
                    <h4 id="product-type" style="color: green;">
                        {{ $product['type'] }}<span style="color: red;">*</span>
                    </h4>
                </div> --}}
                <h5 class="text-danger mb-4">
                    <strong id="product-price">{{ $product['price'] }} USD</strong>
                </h5>
                <div class="mb-4">
    <h5 class="fs-6">Warranty</h5>
    <div class="card shadow-sm border-0">
        <div class="card-body bg-light text-dark rounded">
            {{ $product['warranty'] ?? 'No warranty information available.' }}
        </div>
    </div>
</div>


                <div class="choose-color mb-4">
                    <h5 class="mb-4 fs-6">CHOOSE COLOR</h5>
                    <div class="d-flex gap-3">
                        @foreach ($color_code as $index => $color)
                            @php $colorId = 'color_' . $index; @endphp
                            <input type="radio" class="btn-check" name="color" id="{{ $colorId }}"
                                value="{{ $color }}" autocomplete="off" {{ $index === 0 ? 'checked' : '' }}>
                            <label class="btn btn-light d-flex flex-column align-items-center justify-content-center"
                                for="{{ $colorId }}">
                                <span class="rounded-circle d-block"
                                    style="width: 20px; height: 20px; background-color: {{ strtolower($color) }};"></span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="choose-storage">
                    <h5 class="mb-4 fs-6">CHOOSE STORAGE</h5>
                    <div class="d-flex gap-3">
                        @foreach ($sizes as $index => $size)
                            <input type="radio" class="btn-check" name="storage" id="storage{{ $index }}"
                                autocomplete="off" value="{{ $size }}" {{ $index === 0 ? 'checked' : '' }}>
                            <label class="btn btn-outline-success"
                                for="storage{{ $index }}">{{ $size }}</label>
                        @endforeach
                    </div>
                </div>
                <div class="mt-3">
                    <a href="#" class="btn btn-dark px-4 py-2 custom-btn w-100 add-cart" data-product-item-id=""
                        data-title="{{ $product['name'] }}" data-price="{{ $product['price'] }}"
                        data-img="{{ asset('storage/' . $product['image'][0]) }}" onclick="return addToCart(event)">
                        Add to Cart
                    </a>
                </div>
                <br>
                <div class="mb-4">
                    <h5 class="fs-6">Description</h5>
                    <div class="card shadow-sm border-0">
                        <div class="card-body bg-light text-dark rounded">
                            {{ $productDescription }}
                        </div>
                    </div>
                </div>
                <div class="mt-">
                    <h5 class="fs-6">SPECIFICATION</h5>
                    <div class="accordion" id="specAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#launchCollapse">
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
        
        <!-- Filtered Results Section -->
        <div class="row mt-5" id="filteredResults" style="display: none;">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>Filtered Variants
                            <span class="badge bg-primary ms-2" id="resultCount">0</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row" id="variantsContainer">
                            <!-- Filtered variants will be displayed here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/cart.js') }}"></script>
    <script>
        window.changeImage = function(thumbnail) {
            document.querySelectorAll('.thumbnail-img').forEach(img => {
                img.classList.remove('selected-thumbnail');
            });
            thumbnail.classList.add('selected-thumbnail');
            const mainImage = document.getElementById('mainImage');
            if (mainImage) {
                mainImage.src = thumbnail.getAttribute('data-full-image');
                document.querySelector('.add-cart').dataset.img = mainImage.src;
            }
        };

        document.addEventListener('DOMContentLoaded', function() {
            const variants = @json($variants);
            const priceDisplay = document.getElementById('product-price');
            const typeDisplay = document.getElementById('product-type');
            const addCartBtn = document.querySelector('.add-cart');

            let selectedColor = document.querySelector('input[name="color"]:checked')?.value;
            let selectedSize = document.querySelector('input[name="storage"]:checked')?.value;

            function updateVariantInfo() {
                // Find all variants matching the selected color and size
                const matchingVariants = variants.filter(v =>
                    v.color_code.toLowerCase() === selectedColor?.toLowerCase() &&
                    v.size.toLowerCase() === selectedSize?.toLowerCase()
                );

                if (matchingVariants.length > 0) {
                    // Just pick the first matching variant to show price and update button
                    const variant = matchingVariants[0];

                    // Update price and cart data
                    priceDisplay.innerHTML = `<strong>${variant.price} USD</strong>`;
                    typeDisplay.innerHTML = `${variant.type}<span class="text-danger">*</span>`;
                    addCartBtn.dataset.productItemId = variant.id;
                    addCartBtn.dataset.price = variant.price;

                    // OPTIONAL: show other available types with same size and color
                    if (matchingVariants.length > 1) {
                        let typeList = matchingVariants.map(v => v.type).join(' / ');
                        typeDisplay.innerHTML = `${typeList}<span class="text-danger">*</span>`;
                    }
                } else {
                    // No matching variant found
                    priceDisplay.innerHTML = `<strong>N/A</strong>`;
                    typeDisplay.innerHTML = `<span class="text-danger">Unavailable</span>`;
                    addCartBtn.dataset.productItemId = '';
                    addCartBtn.dataset.price = '';
                }
            }
            document.querySelectorAll('input[name="color"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    selectedColor = this.value;
                    updateVariantInfo();
                });
            });

            document.querySelectorAll('input[name="storage"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    selectedSize = this.value;
                    updateVariantInfo();
                });
            });

            updateVariantInfo();

            // Filter functionality
            const applyFiltersBtn = document.getElementById('applyFilters');
            const clearFiltersBtn = document.getElementById('clearFilters');
            const resetFiltersBtn = document.getElementById('resetFilters');
            const filteredResults = document.getElementById('filteredResults');
            const variantsContainer = document.getElementById('variantsContainer');
            const resultCount = document.getElementById('resultCount');

            function applyFilters() {
                const minPrice = parseFloat(document.getElementById('minPrice').value) || 0;
                const maxPrice = parseFloat(document.getElementById('maxPrice').value) || Infinity;
                const selectedColorFilter = document.getElementById('colorFilter').value;
                const selectedSizeFilter = document.getElementById('sizeFilter').value;
                const selectedTypeFilter = document.getElementById('typeFilter').value;
                const selectedSortFilter = document.getElementById('sortFilter').value;

                // Filter variants based on criteria
                let filteredVariants = variants.filter(variant => {
                    const price = parseFloat(variant.price);
                    const colorMatch = !selectedColorFilter || variant.color_code.toLowerCase() === selectedColorFilter.toLowerCase();
                    const sizeMatch = !selectedSizeFilter || variant.size.toLowerCase() === selectedSizeFilter.toLowerCase();
                    const typeMatch = !selectedTypeFilter || variant.type.toLowerCase() === selectedTypeFilter.toLowerCase();
                    const priceMatch = price >= minPrice && price <= maxPrice;

                    return colorMatch && sizeMatch && typeMatch && priceMatch;
                });

                // Sort variants based on selected sort option
                filteredVariants = sortVariants(filteredVariants, selectedSortFilter);

                // Display results
                displayFilteredResults(filteredVariants);
            }

            function sortVariants(variants, sortOption) {
                switch(sortOption) {
                    case 'price_low_high':
                        return variants.sort((a, b) => parseFloat(a.price) - parseFloat(b.price));
                    case 'price_high_low':
                        return variants.sort((a, b) => parseFloat(b.price) - parseFloat(a.price));
                    case 'name_a_z':
                        return variants.sort((a, b) => a.type.localeCompare(b.type));
                    case 'name_z_a':
                        return variants.sort((a, b) => b.type.localeCompare(a.type));
                    default:
                        return variants; // Default order (as loaded from database)
                }
            }

            function displayFilteredResults(filteredVariants) {
                const sortOption = document.getElementById('sortFilter').value;
                const sortText = getSortText(sortOption);
                
                if (filteredVariants.length === 0) {
                    variantsContainer.innerHTML = '<div class="col-12 text-center"><p class="text-muted">No variants match your filter criteria.</p></div>';
                } else {
                    let html = '';
                    filteredVariants.forEach((variant, index) => {
                        html += `
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="card-title mb-0">${variant.type}</h6>
                                            <span class="badge bg-success">$${variant.price}</span>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted">Color: ${variant.color_code}</small><br>
                                            <small class="text-muted">Storage: ${variant.size}</small><br>
                                            <small class="text-muted">Stock: ${variant.stock}</small>
                                        </div>
                                        <button class="btn btn-primary btn-sm w-100" onclick="selectVariant(${variant.id})">
                                            Select This Variant
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    variantsContainer.innerHTML = html;
                }

                resultCount.textContent = filteredVariants.length;
                filteredResults.style.display = 'block';
                
                // Update the header to show sort info
                const header = document.querySelector('#filteredResults .card-header h5');
                header.innerHTML = `<i class="fas fa-list me-2"></i>Filtered Variants <span class="badge bg-primary ms-2">${filteredVariants.length}</span> <small class="text-light ms-2">(${sortText})</small>`;
            }

            function getSortText(sortOption) {
                switch(sortOption) {
                    case 'price_low_high': return 'Price: Low to High';
                    case 'price_high_low': return 'Price: High to Low';
                    case 'name_a_z': return 'Name: A-Z';
                    case 'name_z_a': return 'Name: Z-A';
                    default: return 'Default Order';
                }
            }

            function clearFilters() {
                document.getElementById('minPrice').value = '';
                document.getElementById('maxPrice').value = '';
                document.getElementById('colorFilter').value = '';
                document.getElementById('sizeFilter').value = '';
                document.getElementById('typeFilter').value = '';
                document.getElementById('sortFilter').value = 'default';
                filteredResults.style.display = 'none';
            }

            function resetFilters() {
                clearFilters();
                // Reset to default variant selection
                const firstColor = document.querySelector('input[name="color"]');
                const firstSize = document.querySelector('input[name="storage"]');
                if (firstColor) firstColor.checked = true;
                if (firstSize) firstSize.checked = true;
                selectedColor = firstColor?.value;
                selectedSize = firstSize?.value;
                updateVariantInfo();
            }

            // Event listeners
            applyFiltersBtn.addEventListener('click', applyFilters);
            clearFiltersBtn.addEventListener('click', clearFilters);
            resetFiltersBtn.addEventListener('click', resetFilters);

            // Auto-apply filters on input change
            document.getElementById('minPrice').addEventListener('input', applyFilters);
            document.getElementById('maxPrice').addEventListener('input', applyFilters);
            document.getElementById('colorFilter').addEventListener('change', applyFilters);
            document.getElementById('sizeFilter').addEventListener('change', applyFilters);
            document.getElementById('typeFilter').addEventListener('change', applyFilters);
            document.getElementById('sortFilter').addEventListener('change', applyFilters);

            // Handle collapse button icon change
            const filterCollapse = document.getElementById('filterCollapse');
            const collapseBtn = document.querySelector('[data-bs-toggle="collapse"]');
            
            filterCollapse.addEventListener('show.bs.collapse', function() {
                collapseBtn.querySelector('i').className = 'fas fa-chevron-up';
            });
            
            filterCollapse.addEventListener('hide.bs.collapse', function() {
                collapseBtn.querySelector('i').className = 'fas fa-chevron-down';
            });
        });

        // Global function to select a variant
        function selectVariant(variantId) {
            // Find the variant and update the main product display
            const variants = @json($variants);
            const variant = variants.find(v => v.id === variantId);
            
            if (variant) {
                // Update price display
                document.getElementById('product-price').innerHTML = `<strong>${variant.price} USD</strong>`;
                
                // Update type display
                document.getElementById('product-type').innerHTML = `${variant.type}<span class="text-danger">*</span>`;
                
                // Update cart button
                const addCartBtn = document.querySelector('.add-cart');
                addCartBtn.dataset.productItemId = variant.id;
                addCartBtn.dataset.price = variant.price;
                
                // Update color and size selection
                const colorInput = document.querySelector(`input[name="color"][value="${variant.color_code}"]`);
                const sizeInput = document.querySelector(`input[name="storage"][value="${variant.size}"]`);
                
                if (colorInput) colorInput.checked = true;
                if (sizeInput) sizeInput.checked = true;
                
                // Scroll to top of product details
                document.querySelector('.container').scrollIntoView({ behavior: 'smooth' });
            }
        }
    </script>
@endsection
