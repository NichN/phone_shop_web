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

            <!-- Quantity -->
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

<!-- STYLES -->
<style>
.option-unavailable {
    opacity: 0.5;
    pointer-events: none;
    text-decoration: line-through;
}
.color-selected {
    border: 2px solid #007bff !important;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    border-radius: 5px;
}
</style>

<script src="{{ asset('js/cart.js') }}"></script>

<!-- SCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const variants = @json($variants);
    const priceDisplay = document.getElementById('product-price');
    const typeDisplay = document.getElementById('product-type');
    const addCartBtn = document.querySelector('.add-cart');
    const qtyInput = document.getElementById('productQuantity');
    const decreaseBtn = document.getElementById('decreaseQty');
    const increaseBtn = document.getElementById('increaseQty');
    const stockDisplay = document.getElementById('stock-display');
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

    decreaseBtn.addEventListener('click', () => {
        let currentVal = parseInt(qtyInput.value) || 1;
        if (currentVal > 1) qtyInput.value = currentVal - 1;
    });

    increaseBtn.addEventListener('click', () => {
        let currentVal = parseInt(qtyInput.value) || 1;
        let maxStock = parseInt(stockDisplay.textContent) || 999;
        if (currentVal < maxStock) qtyInput.value = currentVal + 1;
    });

    function checkAllCombinations() {
        const allColors = Array.from(document.querySelectorAll('input[name="color"]')).map(input => input.value);
        const allSizes = Array.from(document.querySelectorAll('input[name="storage"]')).map(input => input.value);
        document.querySelectorAll('.option-unavailable').forEach(el => el.classList.remove('option-unavailable'));

        allColors.forEach(color => {
            if (!variants.some(v => v.color_code.toLowerCase() === color.toLowerCase())) {
                document.querySelector(`input[name="color"][value="${color}"]`)?.nextElementSibling?.classList.add('option-unavailable');
            }
        });

        allSizes.forEach(size => {
            if (!variants.some(v => v.size.toLowerCase() === size.toLowerCase())) {
                document.querySelector(`input[name="storage"][value="${size}"]`)?.nextElementSibling?.classList.add('option-unavailable');
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

            priceDisplay.innerHTML = `<strong>${variant.price} USD</strong>`;
            typeDisplay.innerHTML = `${variant.type}<span class="text-danger">*</span>`;
            addCartBtn.dataset.productItemId = variant.id;
            addCartBtn.dataset.price = variant.price;
            addCartBtn.disabled = false;
            addCartBtn.classList.remove('btn-secondary');
            addCartBtn.classList.add('btn-dark');
            addCartBtn.style.cursor = 'pointer';
            addCartBtn.textContent = 'Add to Cart';

            stockDisplay.textContent = variant.stock || 'N/A';
            stockDisplay.className = 'fw-bold text-primary';

            const stock = parseInt(variant.stock);
            if (!stock || stock === 0) {
                qtyInput.value = 0;
                qtyInput.disabled = true;
                increaseBtn.disabled = true;
                decreaseBtn.disabled = true;
                addCartBtn.disabled = true;
                addCartBtn.classList.remove('btn-dark');
                addCartBtn.classList.add('btn-secondary');
                addCartBtn.textContent = 'Out of Stock';
                addCartBtn.style.cursor = 'not-allowed';
            } else {
                qtyInput.value = 1;
                qtyInput.disabled = false;
                increaseBtn.disabled = false;
                decreaseBtn.disabled = false;
            }

        } else {
            priceDisplay.innerHTML = `<strong class="text-danger">N/A</strong>`;
            typeDisplay.innerHTML = `<span class="text-danger">Unavailable</span>`;
            addCartBtn.dataset.productItemId = '';
            addCartBtn.dataset.price = '';
            addCartBtn.disabled = true;
            addCartBtn.classList.remove('btn-dark');
            addCartBtn.classList.add('btn-secondary');
            addCartBtn.textContent = 'Add to Cart';
            addCartBtn.style.cursor = 'not-allowed';

            stockDisplay.textContent = 'N/A';
            stockDisplay.className = 'fw-bold text-danger';

            qtyInput.value = 0;
            qtyInput.disabled = true;
            increaseBtn.disabled = true;
            decreaseBtn.disabled = true;
        }
    }

    // Highlight selected color
    document.querySelectorAll('input[name="color"]').forEach(radio => {
        radio.addEventListener('change', function () {
            selectedColor = this.value;
            document.querySelectorAll('label[for^="color_"]').forEach(label => label.classList.remove('color-selected'));
            this.nextElementSibling?.classList.add('color-selected');
            updateVariantInfo();
        });
    });

    document.querySelectorAll('input[name="storage"]').forEach(radio => {
        radio.addEventListener('change', function () {
            selectedSize = this.value;
            updateVariantInfo();
        });
    });

    // Highlight default selected color on page load
    const defaultColorInput = document.querySelector('input[name="color"]:checked');
    if (defaultColorInput) {
        defaultColorInput.nextElementSibling?.classList.add('color-selected');
    }

    checkAllCombinations();
    updateVariantInfo();

    addCartBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const productItemId = this.dataset.productItemId;
        const selectedSize = document.querySelector('input[name="storage"]:checked')?.value;
        const selectedColor = document.querySelector('input[name="color"]:checked')?.value;
        const quantity = parseInt(document.getElementById('productQuantity')?.value);

        handleAddToCart(productItemId, selectedSize, selectedColor, quantity);
        updateCartCountLocal();
    });
});
</script>
@endsection
