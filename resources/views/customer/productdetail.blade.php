@extends('Layout.headerfooter')

@section('title', 'ProductDetail')

@section('content')
<link rel="stylesheet" href="{{ asset('css/productdetail.css') }}">

<div class="container my-5">

    <div class="row mt-3">
        <div class="col-md-6">
            <div class="main-image-container border">
                <img id="mainImage" src="{{ asset('storage/' . $product['image'][0]) }}" 
                    alt="{{ $product['name'] }}" class="img-fluid"
                    onerror="this.src='{{ asset('images/default-product.jpg') }}'">
            </div>

            <!-- Thumbnails -->
            <div class="thumbnail-scroll-container mt-3">
                <div class="d-flex gap-2">
                    @foreach ($images as $index => $img)
                        <div class="thumbnail-wrapper">
                            <img src="{{ asset('storage/' . $img) }}"
                                class="thumbnail-img {{ $index === 0 ? 'selected-thumbnail' : '' }}"
                                data-full-image="{{ asset('storage/' . $img) }}"
                                alt="Thumbnail {{ $index + 1 }}"
                                onerror="this.src='{{ asset('images/default-product.jpg') }}'">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <h3 class="fw-bold text-primary">{{ $product['name'] }}</h3>
                <span class="badge fs-6" id="product-type" style="background-color: #90EE90; color: #000;">
                    {{ $product['type'] }}<span>*</span>
                </span>
            </div>

            <h5 class="text-danger mb-4">
                <strong id="product-price">{{ $product['price'] }} USD</strong>
            </h5>

            <!-- Warranty -->
            <div class="mb-4">
                <h5 class="fs-6 fw-bold">Warranty</h5>
                <div class="card shadow-sm border-0">
                    <div class="card-body bg-light text-dark rounded">
                        {{ $product['warranty'] ?? 'No warranty information available.' }}
                    </div>
                </div>
            </div>

            <div class="choose-color mb-4">
                <h5 class="mb-4 fs-6 fw-bold">Quantity</h5>
                <div class="d-flex gap-3">
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="decreaseQty">-</button>
                <input type="number" id="productQuantity" class="form-control form-control-sm text-center" 
                       value="1" min="1" max="999" style="width: 60px;">
                <button type="button" class="btn btn-outline-secondary btn-sm" id="increaseQty">+</button>
                </div>
            </div>

            <!-- Colors -->
            <div class="choose-color mb-4">
                <h5 class="mb-4 fs-6 fw-bold">CHOOSE COLOR</h5>
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

            <!-- Storage -->
            <div class="choose-storage">
                <h5 class="mb-4 fs-6 fw-bold">CHOOSE STORAGE</h5>
                <div class="d-flex gap-3">
                    @foreach ($sizes as $index => $size)
                        <input type="radio" class="btn-check" name="storage" id="storage{{ $index }}"
                            autocomplete="off" value="{{ $size }}" {{ $index === 0 ? 'checked' : '' }}>
                        <label class="btn btn-outline-success"
                            for="storage{{ $index }}">{{ $size }}</label>
                    @endforeach
                </div>
            </div>

            <!-- Stock & Cart -->
            <div class="mt-3">
                <div class="mb-2">
                    <span class="text-muted fw-bold">
                        <i class="fas fa-box me-1"></i>
                        Stock Available: <span class="fw-bold text-primary" id="stock-display">{{ $stock[0] ?? 'N/A' }}</span>
                    </span>
                </div>
                <button type="button" class="btn btn-dark px-4 py-2 custom-btn w-100 add-cart" 
                    data-product-item-id="" data-title="{{ $product['name'] }}" 
                    data-price="{{ $product['price'] }}" 
                    data-img="{{ asset('storage/' . $product['image'][0]) }}">
                    Add to Cart
                </button>
            </div>

            <!-- Description -->
            <br>
            <div class="mb-4">
                <h5 class="fs-6 fw-bold">Description</h5>
                <div class="card shadow-sm border-0">
                    <div class="card-body bg-light text-dark rounded">
                        {{ $productDescription }}
                    </div>
                </div>
            </div>

            <!-- Specification -->
            <div class="mt-3">
                <h5 class="fs-6 fw-bold">SPECIFICATION</h5>
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

</div>

<style>
.option-unavailable {
    opacity: 0.5;
    pointer-events: none;
    text-decoration: line-through;
}
</style>

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

        // Global function to handle add to cart from product detail page
        window.addToCart = function(event) {
            event.preventDefault();
            event.stopPropagation();
            const button = event.target;
            
            // Check if button is disabled
            if (button.disabled) {
                return false;
            }
            
            const productItemId = button.getAttribute('data-product-item-id');
            if (!productItemId) {
                alert('Please select a valid product variant.');
                return false;
            }
            
            // Use the existing cart functionality
            if (typeof handleAddToCart === 'function') {
                const selectedSize = document.querySelector('input[name="storage"]:checked')?.value;
                const selectedColor = document.querySelector('input[name="color"]:checked')?.value;
                handleAddToCart(productItemId, selectedSize, selectedColor);
                
                // Show success message on button
                button.innerHTML = '<i class="fas fa-check me-2"></i>Added to Cart';
                button.classList.remove('btn-dark');
                button.classList.add('btn-success');
                
                // Show global notification
                if (typeof showCartSuccessNotification === 'function') {
                    showCartSuccessNotification();
                }
                
                setTimeout(() => {
                    button.innerHTML = 'Add to Cart';
                    button.classList.remove('btn-success');
                    button.classList.add('btn-dark');
                }, 2000);
            } else {
                alert('Cart functionality not available.');
            }
            
            return false;
        };

        document.addEventListener('DOMContentLoaded', function() {
            const variants = @json($variants);
            const priceDisplay = document.getElementById('product-price');
            const typeDisplay = document.getElementById('product-type');
            const addCartBtn = document.querySelector('.add-cart');

    let selectedColor = document.querySelector('input[name="color"]:checked')?.value;
    let selectedSize = document.querySelector('input[name="storage"]:checked')?.value;

    // Thumbnail click
    document.querySelectorAll('.thumbnail-img').forEach(thumbnail => {
        thumbnail.addEventListener('click', function() {
            document.querySelectorAll('.thumbnail-img').forEach(img => img.classList.remove('selected-thumbnail'));
            this.classList.add('selected-thumbnail');
            mainImage.src = this.dataset.fullImage;
            addCartBtn.dataset.img = this.dataset.fullImage;
        });
    });

    function checkAllCombinations() {
        const allColors = Array.from(document.querySelectorAll('input[name="color"]')).map(input => input.value);
        const allSizes = Array.from(document.querySelectorAll('input[name="storage"]')).map(input => input.value);

        document.querySelectorAll('.option-unavailable').forEach(el => el.classList.remove('option-unavailable'));

        allColors.forEach(color => {
            if (!variants.some(v => v.color_code.toLowerCase() === color.toLowerCase())) {
                document.querySelector(`input[name="color"][value="${color}"]`)
                    ?.nextElementSibling?.classList.add('option-unavailable');
            }
        });

        allSizes.forEach(size => {
            if (!variants.some(v => v.size.toLowerCase() === size.toLowerCase())) {
                document.querySelector(`input[name="storage"][value="${size}"]`)
                    ?.nextElementSibling?.classList.add('option-unavailable');
            }
        });
    }

    function updateVariantInfo() {
    checkAllCombinations();

    const matchingVariants = variants.filter(v =>
        v.color_code.toLowerCase() === selectedColor?.toLowerCase() &&
        v.size.toLowerCase() === selectedSize?.toLowerCase()
    );

    if (matchingVariants.length > 0) {
        const variant = matchingVariants[0];

                    // Update price and cart data
                    priceDisplay.innerHTML = `<strong>${variant.price} USD</strong>`;
                    typeDisplay.innerHTML = `${variant.type}<span class="text-danger">*</span>`;
                    addCartBtn.dataset.productItemId = variant.id;
                    addCartBtn.dataset.price = variant.price;
                    
                    // Enable the cart button
                    addCartBtn.disabled = false;
                    addCartBtn.classList.remove('btn-secondary');
                    addCartBtn.classList.add('btn-dark');
                    addCartBtn.style.cursor = 'pointer';
                    addCartBtn.textContent = 'Add to Cart';
                    
                    // Update stock display
                    const stockDisplay = document.getElementById('stock-display');
                    if (stockDisplay) {
                        stockDisplay.textContent = variant.stock || 'N/A';
                        stockDisplay.className = 'fw-bold text-primary';
                    }

                    // OPTIONAL: show other available types with same size and color
                    if (matchingVariants.length > 1) {
                        let typeList = matchingVariants.map(v => v.type).join(' / ');
                        typeDisplay.innerHTML = `${typeList}<span class="text-danger">*</span>`;
                    }
                } else {
                    // No matching variant found
                    priceDisplay.innerHTML = `<strong class="text-danger">N/A</strong>`;
                    typeDisplay.innerHTML = `<span class="text-danger">Unavailable</span>`;
                    addCartBtn.dataset.productItemId = '';
                    addCartBtn.dataset.price = '';
                    
                    // Disable the cart button
                    addCartBtn.disabled = true;
                    addCartBtn.classList.remove('btn-dark');
                    addCartBtn.classList.add('btn-secondary');
                    addCartBtn.style.cursor = 'not-allowed';
                    addCartBtn.textContent = 'Add to Cart';
                    
                    // Update stock display to show unavailable
                    const stockDisplay = document.getElementById('stock-display');
                    if (stockDisplay) {
                        stockDisplay.textContent = 'N/A';
                        stockDisplay.className = 'fw-bold text-danger';
                    }
                    
                    // Mark the currently selected options as unavailable
                    if (selectedColor) {
                        const selectedColorInput = document.querySelector(`input[name="color"][value="${selectedColor}"]`);
                        if (selectedColorInput) {
                            const selectedColorLabel = selectedColorInput.nextElementSibling;
                            if (selectedColorLabel) {
                                selectedColorLabel.classList.add('option-unavailable');
                            }
                        }
                    }
                    if (selectedSize) {
                        const selectedSizeInput = document.querySelector(`input[name="storage"][value="${selectedSize}"]`);
                        if (selectedSizeInput) {
                            const selectedSizeLabel = selectedSizeInput.nextElementSibling;
                            if (selectedSizeLabel) {
                                selectedSizeLabel.classList.add('option-unavailable');
                            }
                        }
                    }
                }
            }
            document.querySelectorAll('input[name="color"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    selectedColor = this.value;
                    // Re-enable all options first
                    document.querySelectorAll('.option-unavailable').forEach(el => {
                        el.classList.remove('option-unavailable');
                    });
                    updateVariantInfo();
                });
            });

    document.querySelectorAll('input[name="storage"]').forEach(radio => {
        radio.addEventListener('change', () => {
            selectedSize = radio.value;
            updateVariantInfo();
        });
    });

    // Initial load
    checkAllCombinations();
    updateVariantInfo();

    // Add-to-Cart button handler
    // Add-to-Cart button handler
addCartBtn.addEventListener('click', function(e) {
    e.preventDefault();

    const productItemId = this.dataset.productItemId;
    const title = this.dataset.title;
    const price = parseFloat(this.dataset.price) || 0;
    const img = this.dataset.img;
    const quantity = parseInt(document.getElementById('productQuantity')?.value) || 1;
    const stock = parseInt(stockDisplay.textContent) || 0;

    // Update stock in button dataset
    this.dataset.stock = stock;

    // Call the global addToCartHandler from cart.js
    addToCartHandler(productItemId, null, null, quantity);
});

});
</script>
@endsection
