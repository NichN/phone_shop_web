@include('Admin.component.sidebar')

<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="w3-main">
    <div class="container-fluid py-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white p-2 rounded shadow-sm">
                <li class="breadcrumb-item"><a href="{{ route('products.product_index') }}">Products</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Product</li>
            </ol>
        </nav>

        <h4 class="fw-bold mb-4">Edit Product</h4>

        <!-- Form Card -->
        <form method="POST" enctype="multipart/form-data" action="{{ route('products.product_update', $product->id) }}" novalidate>
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Left Column (Main Info) -->
                <div class="col-md-12">
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="card-heade mb-3 fw-semibold">Basic Information</h5>

                            <!-- Product Name -->
                            <div class="form-group mb-3">
                                <label>Product Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Enter product name" value="{{ $product->name }}" required>
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="form-group mb-3">
                                <label>Description</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" placeholder="Describe your product">{{ $product->description }}</textarea>
                                @error('description')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Category -->
                            <div class="form-group mb-3">
                                <label>Category <span class="text-danger">*</span></label>
                                <select name="cat_id" class="form-select @error('cat_id') is-invalid @enderror" required>
                                    <option value="" disabled>Select category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $product->cat_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('cat_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Brand -->
                            <div class="form-group mb-3">
                                <label>Brand <span class="text-danger">*</span></label>
                                <select name="brand_id" class="form-select @error('brand_id') is-invalid @enderror" required>
                                    <option value="" disabled>Select Brand</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                                @error('brand_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="form-group mb-3">
                                <label for="is_active" class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('is_featured') is-invalid @enderror" id="is_active" name="is_featured" required>
                                    <option value="" disabled>Select Status</option>
                                    <option value="1" {{ $product->is_featured ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ !$product->is_featured ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('is_featured')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Variants -->
            <div class="card shadow-sm border-0">
                <div class="card-heade d-flex justify-content-between align-items-center text-black">
                    <h5 class="mb-0 d-flex align-items-center gap-2">
                        <i class="fas fa-layer-group"></i> Product Variants
                    </h5>
                    <div class="d-flex align-items-center gap-3">
                        <button type="button" id="add-variant" class="btn btn-light btn-sm d-flex align-items-center gap-1">
                            <i class="fas fa-plus"></i> Add Variant
                        </button>
                        <span id="variant-count-text" class="badge bg-light text-primary fw-semibold px-3 py-2">{{ $product->items->count() }} Variants Added</span>
                    </div>
                </div>

                <!-- Container for dynamically added variants -->
                <div id="variant-container">
                    @foreach($product->items as $index => $variant)
                        <div class="variant-card border rounded p-4 mb-3 position-relative" data-index="{{ $index }}">
                            <input type="hidden" name="items[{{ $index }}][id]" value="{{ $variant->id }}">
                            <button type="button" class="btn btn-danger btn-sm position-absolute top-2 end-2 remove-variant" title="Remove Variant" style="z-index:2;">
                                <i class="fas fa-times"></i>
                            </button>
                            <div class="d-flex align-items-center mb-3">
                                <span class="badge bg-primary variant-counter fs-6 me-3">{{ $index + 1 }}</span>
                                <h6 class="mb-0">Variant Details</h6>
                            </div>
                            <div class="col-md-10">
                                <label for="images-{{ $index }}" class="form-label fw-bold">Product Images <span class="text-danger">*</span></label>
                                <input type="file" class="form-control @error('items.'.$index.'.images.*') is-invalid @enderror" 
                                       id="images-{{ $index }}" 
                                       name="items[{{ $index }}][images][]" 
                                       multiple 
                                       accept="image/*"
                                       onchange="previewImages(this, {{ $index }})">
                                @error('items.'.$index.'.images.*')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                <div id="imagePreview-{{ $index }}" class="d-flex flex-wrap gap-3 mt-3">
                                    @if (!empty($variant->images))
                                        @foreach ($variant->images as $key => $imagePath)
                                            <div class="image-preview-item position-relative" 
                                                 data-image-key="{{ $key }}" 
                                                 data-image-path="{{ $imagePath }}">
                                                <img src="{{ Storage::url($imagePath) }}" 
                                                     class="img-thumbnail" 
                                                     style="height: 120px; width: auto;">
                                                <button type="button" 
                                                        class="btn btn-danger btn-sm remove-image" 
                                                        style="position: absolute; top: 5px; right: 5px;"
                                                        onclick="removeExistingImage(this, {{ $index }})">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                            <input type="hidden" name="items[{{ $index }}][existing_images][]" value="{{ $imagePath }}">
                                        @endforeach
                                    @endif
                                </div>
                                <input type="hidden" name="items[{{ $index }}][delete_images][]" id="deleteImagesInput-{{ $index }}">
                            </div>

                            <!-- Color Options -->
                            <div class="mb-3">
                                <label class="form-label d-block">Color <span class="text-danger">*</span></label>
                                <div class="d-flex gap-3 flex-wrap color-options-visible @error('items.'.$index.'.color_id') is-invalid @enderror">
                                    @foreach($colors->take(9) as $color)
                                        <div class="text-center">
                                            <input 
                                                type="radio" 
                                                class="btn-check" 
                                                name="items[{{ $index }}][color_id]" 
                                                id="{{ Str::slug($color->name) }}-{{ $index }}" 
                                                autocomplete="off" 
                                                value="{{ $color->id }}"
                                                {{ $variant->color_id == $color->id ? 'checked' : '' }}>
                                            <label class="btn border rounded py-3 px-4" for="{{ Str::slug($color->name) }}-{{ $index }}">
                                                <div style="background-color: {{ $color->code }}; width: 24px; height: 24px; border-radius: 50%; margin: auto;"></div>
                                                <small class="d-block mt-2">{{ $color->name }}</small>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                @if($colors->count() > 9)
                                    <div class="collapse color-options-hidden mt-3" id="more-colors-{{ $index }}">
                                        <div class="d-flex gap-3 flex-wrap">
                                            @foreach($colors->slice(9) as $color)
                                                <div class="text-center">
                                                    <input 
                                                        type="radio" 
                                                        class="btn-check" 
                                                        name="items[{{ $index }}][color_id]" 
                                                        id="{{ Str::slug($color->name) }}-{{ $index }}" 
                                                        autocomplete="off" 
                                                        value="{{ $color->id }}"
                                                        {{ $variant->color_id == $color->id ? 'checked' : '' }}>
                                                    <label class="btn border rounded py-3 px-4" for="{{ Str::slug($color->name) }}-{{ $index }}">
                                                        <div style="background-color: {{ $color->code }}; width: 24px; height: 24px; border-radius: 50%; margin: auto;"></div>
                                                        <small class="d-block mt-2">{{ $color->name }}</small>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <button type="button" 
                                            class="btn btn-link p-0 mt-2 toggle-more-colors" 
                                            data-bs-toggle="collapse" 
                                            data-bs-target="#more-colors-{{ $index }}" 
                                            aria-expanded="false"
                                            aria-controls="more-colors-{{ $index }}">
                                        Show More Colors
                                    </button>
                                @endif
                                <!-- Hidden select for focusable validation -->
                                <select name="items[{{ $index }}][color_id_select]" class="form-select d-none">
                                    <option value="" disabled>Select Color</option>
                                    @foreach($colors as $color)
                                        <option value="{{ $color->id }}" {{ $variant->color_id == $color->id ? 'selected' : '' }}>{{ $color->name }}</option>
                                    @endforeach
                                </select>
                                @error('items.'.$index.'.color_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Storage Dropdown -->
                            <div class="mb-3">
                                <label class="form-label">Storage <span class="text-danger">*</span></label>
                                <select name="items[{{ $index }}][size_id]" class="form-select @error('items.'.$index.'.size_id') is-invalid @enderror" required>
                                    <option value="" disabled>Select Storage</option>
                                    @foreach($sizes as $size)
                                        <option value="{{ $size->id }}" {{ $variant->size_id == $size->id ? 'selected' : '' }}>{{ $size->size }}</option>
                                    @endforeach
                                </select>
                                @error('items.'.$index.'.size_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- State Dropdown -->
                            <div class="mb-3">
                                <label for="state-{{ $index }}" class="form-label fw-semibold">State <span class="text-danger">*</span></label>
                                <select name="items[{{ $index }}][type]" id="state-{{ $index }}" class="form-select form-select-sm @error('items.'.$index.'.type') is-invalid @enderror" required>
                                    <option value="" disabled>Select state</option>
                                    <option value="new" {{ $variant->type == 'new' ? 'selected' : '' }}>New</option>
                                    <option value="refurbished" {{ $variant->type == 'refurbished' ? 'selected' : '' }}>Refurbished</option>
                                </select>
                                @error('items.'.$index.'.type')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Warranty Input -->
                            <div class="mb-3">
                                <label for="warranty-{{ $index }}" class="form-label fw-semibold">Warranty (months)</label>
                                <input type="number" min="0" step="1" name="items[{{ $index }}][warranty]" id="warranty-{{ $index }}" class="form-control form-control-sm @error('items.'.$index.'.warranty') is-invalid @enderror" placeholder="e.g., 12" value="{{ $variant->warranty }}">
                                @error('items.'.$index.'.warranty')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Price Inputs -->
                            <div class="mb-3">
                                <label for="price-{{ $index }}" class="form-label fw-semibold">Cost Price (USD) <span class="text-danger">*</span></label>
                                <input type="number" min="0" step="0.01" name="items[{{ $index }}][cost_price]" id="price-{{ $index }}" class="form-control form-control-sm @error('items.'.$index.'.cost_price') is-invalid @enderror" placeholder="e.g., 499.99" required value="{{ $variant->cost_price }}">
                                @error('items.'.$index.'.cost_price')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="price-sell-{{ $index }}" class="form-label fw-semibold">Sell Price (USD) <span class="text-danger">*</span></label>
                                <input type="number" min="0" step="0.01" name="items[{{ $index }}][price]" id="price-sell-{{ $index }}" class="form-control form-control-sm @error('items.'.$index.'.price') is-invalid @enderror" placeholder="e.g., 499.99" required value="{{ $variant->price }}">
                                @error('items.'.$index.'.price')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Variant Template -->
                <template id="variant-template">
                    <div class="variant-card border rounded p-4 mb-3 position-relative" data-index="0">
                        <input type="hidden" name="items[__index__][id]" value="">
                        <button type="button" class="btn btn-danger btn-sm position-absolute top-2 end-2 remove-variant" title="Remove Variant" style="z-index:2;">
                            <i class="fas fa-times"></i>
                        </button>
                        <div class="d-flex align-items-center mb-3">
                            <span class="badge bg-primary variant-counter fs-6 me-3">1</span>
                            <h6 class="mb-0">Variant Details</h6>
                        </div>
                        <div class="col-md-10">
                            <label for="images-__index__" class="form-label fw-bold">Product Images <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="images-__index__" name="items[__index__][images][]" multiple accept="image/*" onchange="previewImages(this, __index__)" required>
                            <div id="imagePreview-__index__" class="d-flex flex-wrap gap-3 mt-3"></div>
                            <input type="hidden" name="items[__index__][delete_images][]" id="deleteImagesInput-__index__">
                        </div>

                        <!-- Color Options -->
                        <div class="mb-3">
                            <label class="form-label d-block">Color <span class="text-danger">*</span></label>
                            <div class="d-flex gap-3 flex-wrap color-options-visible">
                                @foreach($colors->take(9) as $color)
                                    <div class="text-center">
                                        <input 
                                            type="radio" 
                                            class="btn-check" 
                                            name="items[__index__][color_id]" 
                                            id="{{ Str::slug($color->name) }}-__index__" 
                                            autocomplete="off" 
                                            value="{{ $color->id }}">
                                        <label class="btn border rounded py-3 px-4" for="{{ Str::slug($color->name) }}-__index__">
                                            <div style="background-color: {{ $color->code }}; width: 24px; height: 24px; border-radius: 50%; margin: auto;"></div>
                                            <small class="d-block mt-2">{{ $color->name }}</small>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @if($colors->count() > 9)
                                <div class="collapse color-options-hidden mt-3" id="more-colors-__index__">
                                    <div class="d-flex gap-3 flex-wrap">
                                        @foreach($colors->slice(9) as $color)
                                            <div class="text-center">
                                                <input 
                                                    type="radio" 
                                                    class="btn-check" 
                                                    name="items[__index__][color_id]" 
                                                    id="{{ Str::slug($color->name) }}-__index__" 
                                                    autocomplete="off" 
                                                    value="{{ $color->id }}">
                                                <label class="btn border rounded py-3 px-4" for="{{ Str::slug($color->name) }}-__index__">
                                                    <div style="background-color: {{ $color->code }}; width: 24px; height: 24px; border-radius: 50%; margin: auto;"></div>
                                                    <small class="d-block mt-2">{{ $color->name }}</small>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <button type="button" 
                                        class="btn btn-link p-0 mt-2 toggle-more-colors" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#more-colors-__index__" 
                                        aria-expanded="false"
                                        aria-controls="more-colors-__index__">
                                    Show More Colors
                                </button>
                            @endif
                            <!-- Hidden select for color validation -->
                            <select name="items[__index__][color_id_select]" class="form-select d-none">
                                <option value="" disabled selected>Select Color</option>
                                @foreach($colors as $color)
                                    <option value="{{ $color->id }}">{{ $color->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Storage Dropdown -->
                        <div class="mb-3">
                            <label class="form-label">Storage <span class="text-danger">*</span></label>
                            <select name="items[__index__][size_id]" class="form-select" required>
                                <option value="" disabled selected>Select Storage</option>
                                @foreach($sizes as $size)
                                    <option value="{{ $size->id }}">{{ $size->size }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- State Dropdown -->
                        <div class="mb-3">
                            <label for="state-__index__" class="form-label fw-semibold">State <span class="text-danger">*</span></label>
                            <select name="items[__index__][type]" id="state-__index__" class="form-select form-select-sm" required>
                                <option value="" disabled selected>Select state</option>
                                <option value="new">New</option>
                                <option value="refurbished">Refurbished</option>
                            </select>
                        </div>

                        <!-- Warranty Input -->
                        <div class="mb-3">
                            <label for="warranty-__index__" class="form-label fw-semibold">Warranty (months)</label>
                            <input type="number" min="0" step="1" name="items[__index__][warranty]" id="warranty-__index__" class="form-control form-control-sm" placeholder="e.g., 12">
                        </div>

                        <!-- Price Inputs -->
                        <div class="mb-3">
                            <label for="price-__index__" class="form-label fw-semibold">Cost Price (USD) <span class="text-danger">*</span></label>
                            <input type="number" min="0" step="0.01" name="items[__index__][cost_price]" id="price-__index__" class="form-control form-control-sm" placeholder="e.g., 499.99" required>
                        </div>
                        <div class="mb-3">
                            <label for="price-sell-__index__" class="form-label fw-semibold">Sell Price (USD) <span class="text-danger">*</span></label>
                            <input type="number" min="0" step="0.01" name="items[__index__][price]" id="price-sell-__index__" class="form-control form-control-sm" placeholder="e.g., 499.99" required>
                        </div>
                    </div>
                </template>

                <!-- Variant Summary Section -->
                <div class="mt-4">
                    <div class="d-flex justify-content-between align-items-center mb-2 variant-summary-header p-2 rounded">
                        <h6 class="fw-semibold mb-0">Variants Summary</h6>
                        <span class="variant-summary-count text-muted" id="variant-summary-count">{{ $product->items->count() }} variants</span>
                    </div>
                    <ul class="list-group" id="variant-summary-list" style="max-height: 220px; overflow-y: auto; min-height: 50px;">
                        @if($product->items->isEmpty())
                            <li class="list-group-item variant-summary-empty">
                                <i class="fas fa-box-open"></i>
                                <div>No variants added yet.</div>
                                <small class="text-muted">Add your first variant to see it here</small>
                            </li>
                        @else
                            @foreach($product->items as $index => $variant)
                                <li class="list-group-item variant-summary-item d-flex justify-content-between align-items-center flex-wrap" data-index="{{ $index }}">
                                    <div class="d-flex align-items-center gap-3 flex-wrap flex-grow-1">
                                        @if(!empty($variant->images) && isset($variant->images[0]))
                                            <img src="{{ Storage::url($variant->images[0]) }}" class="variant-summary-image" alt="Variant Image">
                                        @else
                                            <img src="{{ asset('images/placeholder.png') }}" class="variant-summary-image" alt="No Image">
                                        @endif
                                        <div class="d-flex align-items-center gap-3 flex-wrap">
                                            <span class="fw-medium">{{ $variant->color->name ?? 'Color Not Set' }}</span> 
                                            <span class="badge bg-secondary variant-summary-badge">{{ $variant->size ?? 'Not selected' }}</span>
                                            <span class="badge bg-success text-dark variant-summary-badge">{{ $variant->type ?? 'Not selected' }}</span>
                                            <span class="text-muted">{{ $variant->warranty ?? 'N/A' }} months warranty</span>
                                        </div>
                                    </div>
                                    <span class="variant-summary-price">{{ $variant->price ? '$' . $variant->price : 'N/A' }}</span>
                                    <div class="summary-item-actions d-flex gap-1">
                                        <button type="button" class="btn btn-sm btn-outline-primary edit-variant" title="Edit variant"><i class="fas fa-edit"></i></button>
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-summary-variant" title="Remove variant"><i class="fas fa-trash"></i></button>
                                    </div>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="text-end mt-4">
                <button type="submit" class="btn btn-primary">Update Product</button>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    let variantIndex = {{ $product->items->count() }};

    // Add CSRF token to AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function addVariant() {
        const template = document.getElementById('variant-template').content.cloneNode(true);
        let $variant = $(template.querySelector('.variant-card'));
        $variant.attr('data-index', variantIndex);
        $variant.find('[id*="__index__"]').each(function() {
            $(this).attr('id', $(this).attr('id').replace('__index__', variantIndex));
        });
        $variant.find('[name*="__index__"]').each(function() {
            $(this).attr('name', $(this).attr('name').replace('__index__', variantIndex));
        });
        $variant.find('[for*="__index__"]').each(function() {
            $(this).attr('for', $(this).attr('for').replace('__index__', variantIndex));
        });
        $variant.find('[data-bs-target*="__index__"]').each(function() {
            $(this).attr('data-bs-target', $(this).attr('data-bs-target').replace('__index__', variantIndex));
        });
        $variant.find('[aria-controls*="__index__"]').each(function() {
            $(this).attr('aria-controls', $(this).attr('aria-controls').replace('__index__', variantIndex));
        });

        // Pre-select default values
        $variant.find(`input[name="items[${variantIndex}][color_id]"]`).first().prop('checked', true);
        $variant.find(`select[name="items[${variantIndex}][color_id_select]"]`).val($variant.find(`input[name="items[${variantIndex}][color_id]"]`).first().val());
        $variant.find(`select[name="items[${variantIndex}][size_id]"] option`).eq(1).prop('selected', true);
        $variant.find(`select[name="items[${variantIndex}][type]"] option`).eq(1).prop('selected', true);

        $('#variant-container').append($variant);
        updateVariantCounters();
        attachHandlers($variant);
        syncColorSelection($variant, variantIndex);
        checkShowMoreButton($variant);
        updateVariantSummary();

        $variant.addClass('highlight');
        setTimeout(() => {
            $variant.removeClass('highlight');
        }, 1500);

        variantIndex++;
    }

    function updateVariantCounters() {
        let count = $('#variant-container > .variant-card').length;
        $('#variant-count-text').text(`${count} Variant${count !== 1 ? 's' : ''} Added`);
        $('#variant-container > .variant-card').each(function(i) {
            $(this).attr('data-index', i);
            $(this).find('.variant-counter').text(i + 1);
        });
    }

    function attachHandlers($variant) {
        let index = $variant.data('index');
        $variant.find('.remove-variant').click(function() {
            let $card = $(this).closest('.variant-card');
            $card.slideUp(300, function() {
                $(this).remove();
                updateVariantCounters();
                updateVariantSummary();
            });
        });

        $variant.find('.toggle-more-colors').click(function() {
            let targetId = $(this).data('bs-target').substring(1);
            let $colorContainer = $variant.find(`#${targetId}`);
            $(this).text($colorContainer.hasClass('show') ? 'Show More Colors' : 'Show Less Colors');
        });

        $variant.find('.remove-image').click(function() {
            let $previewItem = $(this).closest('.image-preview-item');
            let imagePath = $previewItem.data('image-path');
            let $input = $variant.find(`input[id^="deleteImagesInput-"]`);
            if (imagePath) {
                $input.val($input.val() + ($input.val() ? ',' : '') + imagePath);
            }
            $previewItem.remove();
        });

        syncColorSelection($variant, index);
    }

    function syncColorSelection($variant, index) {
        $variant.find(`input[name="items[${index}][color_id]"]`).on('change', function() {
            let selectedValue = $(this).val();
            $variant.find(`select[name="items[${index}][color_id_select]"]`).val(selectedValue);
        });
        $variant.find(`select[name="items[${index}][color_id_select]"]`).on('change', function() {
            let selectedValue = $(this).val();
            $variant.find(`input[name="items[${index}][color_id]"][value="${selectedValue}"]`).prop('checked', true);
        });
    }

    function checkShowMoreButton($variant) {
        let $colorContainer = $variant.find('.color-options-visible');
        let $showMoreBtn = $variant.find('.toggle-more-colors');
        $colorContainer.css('max-height', 'none');
        let fullHeight = $colorContainer.height();
        $colorContainer.css('max-height', '48px');
        let limitedHeight = $colorContainer.height();
        if (fullHeight > limitedHeight) {
            $showMoreBtn.show();
        } else {
            $showMoreBtn.hide();
        }
    }

    function updateVariantSummary() {
        let $summaryList = $('#variant-summary-list');
        let $summaryCount = $('#variant-summary-count');
        $summaryList.empty();

        let variants = $('#variant-container > .variant-card');
        let variantCount = variants.length;
        $summaryCount.text(`${variantCount} variant${variantCount !== 1 ? 's' : ''}`);

        if (variantCount === 0) {
            $summaryList.append(`
                <li class="list-group-item variant-summary-empty">
                    <i class="fas fa-box-open"></i>
                    <div>No variants added yet.</div>
                    <small class="text-muted">Add your first variant to see it here</small>
                </li>
            `);
            return;
        }

        variants.each(function(i) {
            let index = $(this).data('index');
            let $colorInput = $(this).find(`input[name="items[${index}][color_id]"]:checked`);
            let colorName = $colorInput.length ? $colorInput.next('label').find('small').text() : 'Not selected';
            let size = $(this).find(`select[name="items[${index}][size_id]"] option:selected`).text();
            if (!size || size === 'Select Storage') size = 'Not selected';
            let state = $(this).find(`select[name="items[${index}][type]"] option:selected`).text();
            if (!state || state === 'Select state') state = 'Not selected';
            let warranty = $(this).find(`input[name="items[${index}][warranty]"]`).val() || 'N/A';
            let price = $(this).find(`input[name="items[${index}][price]"]`).val() || 'N/A';
            let $imagePreview = $(this).find(`[id^="imagePreview-"] img`).first();
            let imageSrc = $imagePreview.length ? $imagePreview.attr('src') : '{{ asset('images/placeholder.png') }}';

            let $li = $('<li>', { 
                class: 'list-group-item variant-summary-item d-flex justify-content-between align-items-center flex-wrap',
                'data-index': index
            });
            let $content = $('<div>', { class: 'd-flex align-items-center gap-3 flex-wrap flex-grow-1' });
            let $image = $('<img>', { 
                class: 'variant-summary-image',
                src: imageSrc,
                alt: 'Variant Image'
            });
            let $details = $('<div>', { class: 'd-flex align-items-center gap-3 flex-wrap' });
            $details.append(`<span class="fw-medium">${colorName}</span>`);
            $details.append(`<span class="badge bg-secondary variant-summary-badge">${size}</span>`);
            $details.append(`<span class="badge bg-light text-dark variant-summary-badge">${state}</span>`);
            $details.append(`<span class="text-muted">${warranty} months warranty</span>`);
            $content.append($image);
            $content.append($details);
            let $price = $('<span>', { 
                class: 'variant-summary-price',
                text: price === 'N/A' ? 'N/A' : `$${price}`
            });
            let $actions = $('<div>', { class: 'summary-item-actions d-flex gap-1' });
            $actions.append(`<button type="button" class="btn btn-sm btn-outline-primary edit-variant" title="Edit variant"><i class="fas fa-edit"></i></button>`);
            $actions.append(`<button type="button" class="btn btn-sm btn-outline-danger remove-summary-variant" title="Remove variant"><i class="fas fa-trash"></i></button>`);
            $li.append($content);
            $li.append($price);
            $li.append($actions);
            $summaryList.append($li);
        });

        $('.remove-summary-variant').click(function() {
            let index = $(this).closest('.variant-summary-item').data('index');
            $(`#variant-container .variant-card[data-index="${index}"] .remove-variant`).click();
        });

        $('.edit-variant').click(function() {
            let index = $(this).closest('.variant-summary-item').data('index');
            let $variantCard = $(`#variant-container .variant-card[data-index="${index}"]`);
            $('html, body').animate({
                scrollTop: $variantCard.offset().top - 100
            }, 500);
            $variantCard.addClass('highlight');
            setTimeout(() => {
                $variantCard.removeClass('highlight');
            }, 1500);
        });
    }

    function previewImages(input, index) {
        const previewContainer = document.getElementById(`imagePreview-${index}`);
        previewContainer.innerHTML = '';
        const files = input.files;

        Array.from(files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imageItem = document.createElement('div');
                imageItem.classList.add('image-preview-item', 'position-relative');
                imageItem.innerHTML = `
                    <img src="${e.target.result}" class="img-thumbnail" style="height: 120px; width: auto;">
                    <button type="button" class="btn btn-danger btn-sm remove-image" style="position: absolute; top: 5px; right: 5px;" onclick="removeNewImage(this)">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                previewContainer.appendChild(imageItem);
            };
            reader.readAsDataURL(file);
        });
    }

    function removeExistingImage(button, index) {
        const imageDiv = button.closest('.image-preview-item');
        const imagePath = imageDiv.getAttribute('data-image-path');
        if (imagePath) {
            const hiddenInputId = `deleteImagesInput-${index}`;
            const hiddenInput = document.getElementById(hiddenInputId);
            if (hiddenInput) {
                const existingValue = hiddenInput.value;
                hiddenInput.value = existingValue ? `${existingValue},${imagePath}` : imagePath;
            }
        }
        imageDiv.remove();
    }

    function removeNewImage(button) {
        const imageDiv = button.closest('.image-preview-item');
        imageDiv.remove();
    }

    // Client-side validation
    $('form').submit(function(e) {
        let isValid = true;
        $('#variant-container .variant-card').each(function() {
            let index = $(this).data('index');
            let $costPrice = $(this).find(`input[name="items[${index}][cost_price]"]`);
            let $sellPrice = $(this).find(`input[name="items[${index}][price]"]`);
            let $size = $(this).find(`select[name="items[${index}][size_id]"]`);
            let $colorSelect = $(this).find(`select[name="items[${index}][color_id_select]"]`);
            let $type = $(this).find(`select[name="items[${index}][type]"]`);
            let $imageInput = $(this).find(`input[name="items[${index}][images][]"]`);
            let $imagePreview = $(this).find(`[id^="imagePreview-"] img`);
            let $deleteImages = $(this).find(`input[name="items[${index}][delete_images][]"]`).val();
            let $existingImages = $(this).find(`input[name="items[${index}][existing_images][]"]`);
            let hasImages = $imagePreview.length > 0 || $imageInput[0].files.length > 0 || ($existingImages.length > 0 && (!$deleteImages || $deleteImages.split(',').length < $existingImages.length));

            if (!$costPrice.val()) {
                isValid = false;
                $costPrice.addClass('is-invalid');
            } else {
                $costPrice.removeClass('is-invalid');
            }

            if (!$sellPrice.val()) {
                isValid = false;
                $sellPrice.addClass('is-invalid');
            } else {
                $sellPrice.removeClass('is-invalid');
            }

            if (!$size.val()) {
                isValid = false;
                $size.addClass('is-invalid');
            } else {
                $size.removeClass('is-invalid');
            }

            if (!$colorSelect.val()) {
                isValid = false;
                $colorSelect.addClass('is-invalid');
                $(this).find('.color-options-visible').addClass('is-invalid');
            } else {
                $colorSelect.removeClass('is-invalid');
                $(this).find('.color-options-visible').removeClass('is-invalid');
            }

            if (!$type.val()) {
                isValid = false;
                $type.addClass('is-invalid');
            } else {
                $type.removeClass('is-invalid');
            }

            if (!hasImages) {
                isValid = false;
                $imageInput.addClass('is-invalid');
            } else {
                $imageInput.removeClass('is-invalid');
            }
        });

        if ($('#variant-container .variant-card').length === 0) {
            isValid = false;
            alert('At least one variant is required.');
        }

        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields for each variant.');
            let $firstInvalid = $('#variant-container .is-invalid').first();
            if ($firstInvalid.length) {
                $('html, body').animate({
                    scrollTop: $firstInvalid.offset().top - 100
                }, 500);
            }
        }
    });

    $('#add-variant').click(function() {
        addVariant();
    });

    $('#variant-container').on('change input', 'input, select', function() {
        updateVariantSummary();
    });

    $('.variant-card').each(function() {
        attachHandlers($(this));
        checkShowMoreButton($(this));
    });
});
</script>

<style>
.card-heade {
    font-weight: 700;
    font-size: 1.1rem;
    background-color: #e6d2d2;
    padding: 1rem;
    color: black;
    border-radius: 10px 10px 0 0 !important;
}
.highlight {
    background-color: #e6f3ff;
    transition: background-color 1.5s;
}
.is-invalid {
    border-color: #dc3545 !important;
    box-shadow: 0 0 5px rgba(220, 53, 69, 0.5);
}
.color-options-visible.is-invalid {
    border: 1px solid #dc3545;
    border-radius: 4px;
    padding: 5px;
}
.variant-summary-header {
    background-color: #f8f9fa;
    border: 1px solid #e9ecef;
    transition: background-color 0.3s ease;
}
.variant-summary-header:hover {
    background-color: #e9ecef;
}
.variant-summary-header h6 {
    font-size: 1.1rem;
    color: #1a1a1a;
    letter-spacing: 0.02em;
}
.variant-summary-count {
    font-size: 0.9rem;
    font-weight: 500;
    color: #6c757d;
    background-color: #e9ecef;
    padding: 0.3rem 0.8rem;
    border-radius: 12px;
}
.variant-summary-image {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 6px;
    border: 1px solid #dee2e6;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s ease;
}
.variant-summary-image:hover {
    transform: scale(1.1);
}
.variant-summary-item .d-flex.align-items-center {
    gap: 1rem;
}
@media (max-width: 768px) {
    .variant-summary-image {
        width: 32px;
        height: 32px;
    }
}
.list-group.variant-summary-list {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    background-color: #fff;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}
.variant-summary-item {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #f1f3f5;
    transition: background-color 0.2s ease, transform 0.2s ease;
}
.variant-summary-item:hover {
    background-color: #f8f9fa;
    transform: translateY(-2px);
}
.variant-summary-item:last-child {
    border-bottom: none;
}
.variant-summary-badge {
    font-size: 0.85rem;
    padding: 0.4rem 0.8rem;
    border-radius: 10px;
    font-weight: 500;
    transition: background-color 0.2s ease;
}
.variant-summary-badge.bg-secondary {
    background-color: #6c757d !important;
    color: #fff;
}
.variant-summary-badge.bg-light {
    background-color: #f1f3f5 !important;
    color: #495057;
}
.variant-summary-price {
    font-size: 0.95rem;
    font-weight: 600;
    color: #28a745;
}
.summary-item-actions .btn {
    padding: 0.3rem 0.6rem;
    border-radius: 6px;
    transition: all 0.2s ease;
}
.summary-item-actions .btn-outline-primary {
    border-color: #007bff;
    color: #007bff;
}
.summary-item-actions .btn-outline-primary:hover {
    background-color: #007bff;
    color: #fff;
    transform: translateY(-1px);
}
.summary-item-actions .btn-outline-danger {
    border-color: #dc3545;
    color: #dc3545;
}
.summary-item-actions .btn-outline-danger:hover {
    background-color: #dc3545;
    color: #fff;
    transform: translateY(-1px);
}
.variant-summary-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 1.5rem;
    color: #6c757d;
    font-size: 0.9rem;
    text-align: center;
    background-color: #f8f9fa;
    border-radius: 8px;
}
.variant-summary-empty i {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
    color: #adb5bd;
}
.variant-summary-empty small {
    font-size: 0.8rem;
    color: #868e96;
}
.variant-summary-list::-webkit-scrollbar {
    width: 8px;
}
.variant-summary-list::-webkit-scrollbar-track {
    background: #f1f3f5;
    border-radius: 8px;
}
.variant-summary-list::-webkit-scrollbar-thumb {
    background: #adb5bd;
    border-radius: 8px;
}
.variant-summary-list::-webkit-scrollbar-thumb:hover {
    background: #6c757d;
}
@media (max-width: 768px) {
    .variant-summary-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    .summary-item-actions {
        margin-top: 0.5rem;
        width: 100%;
        justify-content: flex-end;
    }
    .variant-summary-price {
        margin-top: 0.5rem;
    }
}
</style>