<div class="row g-4">
    @if($paginatedProducts->count() > 0)
        @foreach ($paginatedProducts as $product)
        <!-- Product Card -->
        <div class="col-md-3">
            <div class="card product-card">
                <img src="{{ $product['image'] }}" class="card-img-top product-img" alt="{{ $product['name'] }}">
                <div class="card-body text-right bg-light">
                    <p class="tag mb-0">{{ $product['category'] }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mt-2 product-title">{{ $product['name'] }}</h5>
                        <i class="fa-regular fa-heart fs-5 add-wishlist"></i>
                    </div>
                    <p class="card-price">{{ $product['price'] }}</p>
                    <a href="#" class="btn btn-primary px-4 py-2 d-inline-block custom-btn w-100 add-cart">Add to Cart</a>
                </div>
            </div>
        </div>
        @endforeach
    @else
        <p class="text-center text-muted">No products available.</p>
    @endif
</div>

<!-- Pagination -->
<div class="d-flex justify-content-center mt-4">
    {{ $paginatedProducts->links('vendor.pagination.custom-bootstrap') }}
</div>
