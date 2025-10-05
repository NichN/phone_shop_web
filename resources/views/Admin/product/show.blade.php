@include('Admin.component.sidebar')

<!-- Styles -->
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Hanuman:wght@400;700&display=swap" rel="stylesheet">
<link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">

<style>
    .card-heade  {
        font-weight: 700;
        font-size: 1.1rem;
        background-color: #e6d2d2;
        background-color: ;
        padding: 1rem;
       
        border-radius: 10px 10px 0 0 !important;
    }
    .product-image {
        cursor: pointer;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 0.25rem;
        border: 1px solid #ddd;
        max-height: 150px;
        object-fit: cover;
        width: 100%;
    }
    .product-image:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        border-color: #007bff;
    }

    /* Table responsiveness */
    .table-responsive {
        overflow-x: auto;
    }

    /* Badge spacing */
    .badge {
        font-size: 0.85rem;
        padding: 0.4em 0.7em;
    }

    /* Quick Stats Cards */
    .quick-stats .card {
        cursor: default;
        transition: box-shadow 0.2s ease;
    }
    .quick-stats .card:hover {
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }

    /* Breadcrumb */
    .breadcrumb a {
        text-decoration: none;
    }
</style>

<div class="w3-main p-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-white p-2 rounded shadow-sm">
            <li class="breadcrumb-item"><a href="{{ route('products.product_index') }}">Products</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $products->name }}</li>
        </ol>
    </nav>

    <h6 class="text-muted mb-4">Product ID: {{ $products->id }}</h6>

    <div class="row">
        <!-- Left side: Product Images and Information -->
        <div class="col-md-8">
            <!-- Product Images -->
            <div class="card mb-4">
                <div class="card-heade"><strong>Product Images</strong></div>
                <div class="card-body">
                    <div class="row">
                        @forelse ($images as $image)
                        <div class="col-6 col-md-3 mb-3">
                            <img src="{{ asset('storage/' . $image) }}" alt="Product Image" class="product-image">
                        </div>
                        @empty
                        <p class="text-muted">No images available for this product.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Product Information -->
            <div class="card mb-4">
                <div class="card-heade"><strong>Product Information</strong></div>
                <div class="card-body">
                    <p><strong>Description:</strong></p>
                    <p>{{ $products->description }}</p>
                </div>
            </div>
        </div>

        <!-- Right side: Status and Model -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-heade"><strong>Status</strong></div>
                <div class="card-body">
                    <p>
                        <strong>Current Status:</strong> 
                        <span class="badge {{ $productDetails->is_featured ? 'badge-success' : 'badge-secondary' }}">
                            {{ $productDetails->is_featured ? 'Active' : 'Inactive' }}
                        </span>
                    </p>
                    <p><strong>Total Stock:</strong> {{ $total_stock }}</p>
                    <p><strong>Variants:</strong> {{ $variants_count }}</p>
                </div>
            </div>

            <!-- Model Info -->
            <div class="card mb-4">
                <div class="card-heade"><strong>Model</strong></div>
                <div class="card-body">
                    <p><strong>Category:</strong> {{ $products->category->name ?? 'N/A' }}</p>
                <p><strong>Brand:</strong> {{ $products->brand->name ?? 'N/A' }}</p>
                <p><strong>Price Range:</strong>
                    @if($min_price === $max_price)
                        ${{ number_format($min_price, 2) }}
                    @else
                        ${{ number_format($min_price, 2) }} - ${{ number_format($max_price, 2) }}
                    @endif
                </p>
                <p><strong>Launching at:</strong> {{ $products->created_at ? $products->created_at->format('F j, Y') : 'N/A' }}</p>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Product Variants Table -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-heade d-flex justify-content-between align-items-center">
                    <strong>Product Variants</strong>
                    <a href="{{ route('products.product_edit', ['id' => $products->id]) }}" class="btn btn-sm btn-light">+ Add Variant</a>

                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table mb-0 table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Id</th>
                                <th>Price</th>
                                <th>Size</th>
                                <th>Color</th>
                                <th>Stock</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($variants as $variant)
                            <tr>
                                <td>{{ $variant->id }}</td>
                                <td>${{ number_format($variant->cost_price, 2) }}</td>
                                <td>{{ $variant->size }}</td>
                                <td>{{ $variant->color->name }}</td>
                                <td>{{ $variant->stock }}</td>
                                <td>
                                    @if ($variant->stock > 10)
                                        <span class="badge badge-success">In Stock</span>
                                    @elseif ($variant->stock > 0)
                                        <span class="badge badge-warning">Low Stock</span>
                                    @else
                                        <span class="badge badge-danger">Out of Stock</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="col-md-4">
            <div class="quick-stats">
      
                <div class="card p-3 mb-4 text-center" style="background-color: #74db60;">
                    <h3 class="mb-0">{{ $variants->where('stock', '>', 0)->count() }}</h3>
                    <small class="text-muted">Variants in Stock</small>
                </div>
                <div class="card p-3 mb-4 text-center bg-warning text-dark">
                    <h3 class="mb-0">{{ $variants->where('stock', '<=', 5)->where('stock', '>', 0)->count() }}</h3>
                    <small>Low Stock Variants</small>
                </div>
                <div class="card p-3 text-center bg-danger text-white">
                    <h3 class="mb-0">{{ $variants->where('stock', 0)->count() }}</h3>
                    <small>Out of Stock Variants</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Load Scripts -->
@include('Admin.product.script')
