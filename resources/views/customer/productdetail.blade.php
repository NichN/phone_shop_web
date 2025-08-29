@extends('Layout.headerfooter')

@section('title', 'ProductDetail')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/productdetail.css') }}">

    <div class="container my-5">
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
        });
    </script>
@endsection
