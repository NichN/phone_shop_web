@extends('Layout.headerfooter')

@section('title', 'ProductDetail')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/productdetail.css') }}">

    <div class="container my-3">
        <div class="row mt-3">
            <!-- Product Images -->
            <div class="col-md-6">
                <div class="main-image-container border p-3">
                    <img id="mainImage" src="{{ asset('storage/' . $product['image'][0]) }}" alt="{{ $product['name'] }}" class="img-fluid">
                </div>
                <div class="row thumbnail mt-3">
                    @foreach ($images as $index => $img)
                        <div class="col-3">
                            <img src="{{ asset('storage/' . $img) }}"
                                 class="thumbnail-img img-fluid {{ $index === 0 ? 'selected-thumbnail' : '' }}"
                                 onclick="changeImage(this)" alt="Thumbnail {{ $index + 1 }}">
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-md-6">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h4 class="mb-0">{{ $product['name'] }}</h4>
                    <h5 id="product-type" style="color: green;">
                        {{ $product['type'] }}<span style="color: green;">*</span>
                    </h5>
                </div>
                <h3 class="text-danger mb-4">
                    <strong id="product-price">{{ $product['price'] }} USD</strong>
                </h3>

                <div class="choose-color mb-4">
                    <h5 class="mb-4 fw-bold">CHOOSE YOUR COLOR</h5>
                    <div class="d-flex gap-3">
                        @foreach ($color_code as $index => $color)
                            @php $colorId = 'color_' . $index; @endphp
                            <input type="radio" class="btn-check" name="color" id="{{ $colorId }}" value="{{ $color }}" autocomplete="off" {{ $index === 0 ? 'checked' : '' }}>
                            <label class="btn d-flex flex-column align-items-center justify-content-center" for="{{ $colorId }}">
                                <span class="rounded-circle d-block" style="width: 20px; height: 20px; background-color: {{ strtolower($color) }};"></span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="choose-storage">
                    <h5 class="mb-4 fw-bold">CHOOSE YOUR STORAGE</h5>
                    <div class="d-flex gap-3">
                        @foreach ($sizes as $index => $size)
                            <input type="radio" class="btn-check" name="storage" id="storage{{ $index }}" autocomplete="off" value="{{ $size }}" {{ $index === 0 ? 'checked' : '' }}>
                            <label class="btn btn-outline-success" for="storage{{ $index }}">{{ $size }}</label>
                        @endforeach
                    </div>
                </div>

                <!-- Add to Cart Button -->
                <div class="d-flex justify-content-between align-items-center mt-4 mb-4 gap-3">
                    <a href="#" class="btn btn-primary px-4 py-2 custom-btn w-100 add-cart"
                       data-title="{{ $product['name'] }}"
                       data-price="{{ $product['price'] }}"
                       data-img="{{ asset('storage/' . $product['image'][0]) }}">
                        Add to Cart
                    </a>
                </div>

                <!-- Specification Accordion -->
                <div class="mt-4">
                    <h5 class="fw-bold">SPECIFICATION</h5>
                    <div class="accordion" id="specAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#launchCollapse">
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

    <!-- Add to cart JS -->
    <script src="{{ asset('js/cart.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const productItems = @json($variants);

            const priceDisplay = document.getElementById('product-price');
            const typeDisplay = document.getElementById('product-type');

            let selectedColor = document.querySelector('input[name="color"]:checked')?.value;
            let selectedSize = document.querySelector('input[name="storage"]:checked')?.value;

            function updateDisplay() {
                const selectedItem = productItems.find(item =>
                    item.color_code.toLowerCase() === selectedColor.toLowerCase() &&
                    item.size.toLowerCase() === selectedSize.toLowerCase()
                );

                if (selectedItem) {
                    priceDisplay.innerHTML = `<strong>${selectedItem.price} USD</strong>`;
                    typeDisplay.innerHTML = `${selectedItem.type}<span style="color: green;">*</span>`;
                }
            }

            document.querySelectorAll('input[name="color"]').forEach(radio => {
                radio.addEventListener('change', function () {
                    selectedColor = this.value;
                    updateDisplay();
                });
            });

            document.querySelectorAll('input[name="storage"]').forEach(radio => {
                radio.addEventListener('change', function () {
                    selectedSize = this.value;
                    updateDisplay();
                });
            });

            updateDisplay();
        });
    </script>
@endsection
