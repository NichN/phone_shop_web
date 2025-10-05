

@include('Admin.component.sidebar')

<link href="{{ asset('css/dashboard.css')}}" rel="stylesheet">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="w3-main">
    <div class="container-fluid py-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white p-2 rounded shadow-sm">
                <li class="breadcrumb-item"><a href="{{ route('products.product_index') }}">Products</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add New Product</li>
            </ol>
        </nav>

        <h4 class="fw-bold mb-4">Add New Product</h4>

        <!-- Form Card -->
        <form method="POST" enctype="multipart/form-data" action="{{ route('products.productstore') }}">
            @csrf
            <input type="hidden" name="name" id="Name">
                    <input type="hidden" name="size_id" id="size_hidden_id">
                    <input type="hidden" name="color_id" id="color_hidden_id">
                    <input type="hidden" name="stock" id="stock">
            <div class="row">

                <!-- Left Column (Main Info) -->
                <div class="col-md-12">
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">

                            <h5 class=" card-heade mb-3 fw-semibold">Basic Information</h5>

                            <!-- Product Name -->
                            <div class="form-group mb-3">
                                <label>Product Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" placeholder="Enter product name">
                            </div>

                            <!-- Description -->
                            <div class="form-group mb-3">
                                <label>Description</label>
                                <textarea name="description" class="form-control" rows="4" placeholder="Describe your product"></textarea>
                            </div>

                            <!-- Category -->
                            <div class="form-group mb-3">
                                <label>Category <span class="text-danger">*</span></label>
                                <select name="cat_id" class="form-select">
                                    <option value="">Select category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Tags -->
                            <div class="form-group mb-3">
                                <label>Brand <span class="text-danger">*</span></label>
                                <select name="brand_id" class="form-select">
                                    <option value="">Select Brand</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                          
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
                    <span id="variant-count-text" class="badge bg-light text-primary fw-semibold px-3 py-2">0 Variants Added</span>
                </div>
            </div>

            <div class="card-body" id="variant-wrapper">
                <!-- Hidden Variant Template -->
                

                <!-- Container for dynamically added variants -->
                <div id="variant-container"></div>

                <!-- Variant Summary Section -->
                <div class="mt-4">
                    <div class="d-flex justify-content-between align-items-center mb-2 variant-summary-header p-2 rounded">
                        <h6 class="fw-semibold mb-0">Variants Summary</h6>
                        <span class="variant-summary-count text-muted" id="variant-summary-count">0 variants</span>
                    </div>
                    <ul class="list-group" id="variant-summary-list" style="max-height: 220px; overflow-y: auto; min-height: 50px;">
                        <li class="list-group-item variant-summary-empty">
                            <i class="fas fa-box-open"></i>
                            <div>No variants added yet.</div>
                            <small class="text-muted">Add your first variant to see it here</small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>


            <!-- Submit Button -->
            <div class="text-end mt-4">
                <button type="submit" class="btn btn-primary">Save Product</button>
            </div>
        </form>
        <div class="variant-card border rounded p-4 mb-3 position-relative" id="variant-template" style="display:none;">
                    <button type="button" class="btn btn-danger btn-sm position-absolute top-2 end-2 remove-variant" title="Remove Variant" style="z-index:2;">
                        <i class="fas fa-times"></i>
                    </button>
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge bg-primary variant-counter fs-6 me-3">1</span>
                        <h6 class="mb-0">Variant Details</h6>
                    </div>
                    <div class="col-md-10">
                            <label for="images" class="form-label fw-bold">Product Images <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*" required onchange="previewImages(this)">
                        </div>
                        <div id="imagePreview" class="d-flex flex-wrap gap-3 mt-3"></div>

                    <!-- Color Options -->
                    <div class="mb-3">
                        <label class="form-label d-block">Color <span class="text-danger">*</span></label>
                        
                        <div class="d-flex gap-3 flex-wrap color-options-visible">
                            @foreach($colors->take(9) as $color)
                                <div class="text-center">
                                    <input 
                                        type="radio" 
                                        class="btn-check" 
                                        name="variants[__index__][color]" 
                                        id="{{ Str::slug($color->name) }}-__index__" 
                                        autocomplete="off" 
                                        value="{{ $color->name }}">
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
                                                name="variants[__index__][color]" 
                                                id="{{ Str::slug($color->name) }}-__index__" 
                                                autocomplete="off" 
                                                value="{{ $color->name }}">
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
                    </div>



                    <!-- Storage Dropdown -->
                    <div class="mb-3">
                               <label class="form-label">Storage <span class="text-danger">*</span></label>
                                <select name="variants[__index__][size_id]" class="form-select">
                                    <div class="col-md-3">
                                        <option value="">Select Storage</option>
                                    @foreach($size as $sizes)
                                        <option value="{{ $sizes->id }}">{{ $sizes->size }}</option>
                                    @endforeach
                                </select>
                          
                            </div>

                    <!-- State Dropdown -->
                    <div class="mb-3">
                        <label for="state-__index__" class="form-label fw-semibold">State</label>
                        <select name="variants[__index__][type]" id="state-__index__" class="form-select form-select-sm" required>
                            <option value="" disabled selected>Select state</option>
                            <option value="new">New</option>
                            <option value="refurbished">Refurbished</option>
                        </select>
                    </div>

                    <!-- Warranty Input -->
                    <div class="mb-3">
                        <label for="warranty-__index__" class="form-label fw-semibold">Warranty (months)</label>
                        <input type="number" min="0" step="1" name="variants[__index__][warranty]" id="warranty-__index__" class="form-control form-control-sm" placeholder="e.g., 12">
                    </div>

                    <!-- Price Input -->
                    <div class="mb-3">
                        <label for="price-__index__" class="form-label fw-semibold"> Cost Price (USD)</label>
                        <input type="number" min="0" step="0.01" name="variants[__index__][cost_price]" id="price-__index__" class="form-control form-control-sm" placeholder="e.g., 499.99" required>
                    </div>
                    <div class="mb-3">
                        <label for="price-__index__" class="form-label fw-semibold"> Sell Price (USD)</label>
                        <input type="number" min="0" step="0.01" name="variants[__index__][price]" id="price-sell__index__" class="form-control form-control-sm" placeholder="e.g., 499.99" required>
                    </div>
                    <div class="col-md-4">
                            <label for="is_active" class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="is_active" name="is_featured" required>
                                <option value="" disabled selected>Select Status</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                </div>
    </div>
</div>
<script>
        $(document).ready(function() {
            let variantIndex = 0;

            function addVariant() {
                // Clone template and replace __index__ placeholders
                let templateHtml = $('#variant-template').prop('outerHTML').replace(/__index__/g, variantIndex);

                let $variant = $(templateHtml.replace(/__index__/g, variantIndex));
                    $variant.attr('data-index', variantIndex);
                    $variant.show(); // it was hidden in the template
                    $('#variant-container').append($variant);


                $('#variant-container').append($variant);
               

                updateVariantCounters();
                attachHandlers($variant);
                checkShowMoreButton($variant);
                updateVariantSummary();

                // Highlight the newly added variant
                $variant.addClass('highlight');
                setTimeout(() => {
                    $variant.removeClass('highlight');
                }, 1500);

                variantIndex++;
            }

            function updateVariantCounters() {
                let count = $('#variant-container > .variant-card').length;
                $('#variant-count-text').text(`${count} Variant${count !== 1 ? 's' : ''} Added`);
                
                // Update variant number badges
                $('#variant-container > .variant-card').each(function(i) {
                    $(this).find('.variant-counter').text(i + 1);
                });
            }

            function attachHandlers($variant) {
                // Remove button
                $variant.find('.remove-variant').click(function() {
                    let $card = $(this).closest('.variant-card');
                    $card.slideUp(300, function() {
                        $(this).remove();
                        updateVariantCounters();
                        updateVariantSummary();
                    });
                });

                // Show More button for colors
                $variant.find('.show-more-btn').click(function() {
                    let targetId = $(this).data('target');
                    let $colorContainer = $variant.find('#' + targetId);

                    if ($colorContainer.hasClass('expanded')) {
                        $colorContainer.removeClass('expanded');
                        $(this).text('Show More');
                    } else {
                        $colorContainer.addClass('expanded');
                        $(this).text('Show Less');
                    }
                });
            }

            function checkShowMoreButton($variant) {
                // Check if color options overflow the 48px max-height
                let $colorContainer = $variant.find('.color-options-container');
                let $showMoreBtn = $variant.find('.show-more-btn');

                // Temporarily remove max-height to measure full height
                $colorContainer.css('max-height', 'none');
                let fullHeight = $colorContainer.height();

                // Set max-height back to 48px and measure again
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

                    // Find checked color input label text and color code
                    let $colorInput = $(this).find(`input[name="variants[${index}][color]"]:checked`);
                    let colorName = $colorInput.length ? $colorInput.next('label').find('small').text() : 'Not selected';
                    let colorCode = $colorInput.length ? $colorInput.val() : '#ccc';

                    // Storage
                    let size = $(this).find(`select[name="variants[${index}][size]"] option:selected`).text();
                    if (!size|| size === 'Select storage') size = 'Not selected';

                    // State
                    let state = $(this).find(`select[name="variants[${index}][type]"] option:selected`).text();
                    if (!state || state === 'Select state') state = 'Not selected';

                    // Warranty
                    let warranty = $(this).find(`input[name="variants[${index}][warranty]"]`).val() || 'N/A';

                    // Price
                    let price = $(this).find(`input[name="variants[${index}][price]"]`).val() || 'N/A';

                    // Build list item
                    let $li = $('<li>', { 
                        class: 'list-group-item variant-summary-item d-flex justify-content-between align-items-center flex-wrap',
                        'data-index': index
                    });

                    let $content = $('<div>', { class: 'd-flex align-items-center gap-3 flex-wrap flex-grow-1' });
                    
                    let $colorCircle = $('<span>', { 
                        class: 'variant-summary-color',
                        css: { 'background-color': colorCode }
                    });
                    
                    let $details = $('<div>', { class: 'd-flex align-items-center gap-3 flex-wrap' });
                    
                    $details.append(`<span class="fw-medium">${colorName}</span>`);
                    $details.append(`<span class="badge bg-secondary variant-summary-badge">${size}</span>`);
                    $details.append(`<span class="badge bg-light text-dark variant-summary-badge">${state}</span>`);
                    $details.append(`<span class="text-muted">${warranty} months warranty</span>`);
                    
                    $content.append($colorCircle);
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
                
                // Attach event handlers to summary items
                $('.remove-summary-variant').click(function() {
                    let index = $(this).closest('.variant-summary-item').data('index');
                    $(`#variant-container .variant-card[data-index="${index}"] .remove-variant`).click();
                });
                
                $('.edit-variant').click(function() {
                    let index = $(this).closest('.variant-summary-item').data('index');
                    let $variantCard = $(`#variant-container .variant-card[data-index="${index}"]`);
                    
                    // Scroll to the variant card
                    $('html, body').animate({
                        scrollTop: $variantCard.offset().top - 100
                    }, 500);
                    
                    // Highlight the variant card
                    $variantCard.addClass('highlight');
                    setTimeout(() => {
                        $variantCard.removeClass('highlight');
                    }, 1500);
                });
            }

            // Add first variant on page load
            addVariant();

            // Add variant button click
            $('#add-variant').click(function() {
                addVariant();
            });

            // Update summary on input/select change inside variants
            $('#variant-container').on('change input', 'input, select', function() {
                updateVariantSummary();
            });
        });
        function previewImages(input) {
    const previewContainer = document.getElementById('imagePreview');
    previewContainer.innerHTML = '';

    if (input.files && input.files.length > 0) {
        Array.from(input.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewDiv = document.createElement('div');
                previewDiv.className = 'image-preview-item position-relative';
                previewDiv.dataset.index = index;
                previewDiv.innerHTML = `
                    <img src="${e.target.result}" class="img-thumbnail" style="height: 120px; width: auto;">
                    <button type="button" class="btn btn-danger btn-sm remove-image" style="position: absolute; top: 5px; right: 5px;">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                previewContainer.appendChild(previewDiv);
                previewDiv.querySelector('.remove-image').addEventListener('click', () => removeImage(index));
            };
            reader.readAsDataURL(file);
        });
    }
}

function removeImage(index) {
    const input = document.getElementById('images');
    const files = Array.from(input.files);
    const newFiles = files.filter((_, i) => i !== index);
    const dataTransfer = new DataTransfer();
    newFiles.forEach(file => dataTransfer.items.add(file));
    input.files = dataTransfer.files;
    previewImages(input);
}
    </script>
    <style>
        .card-heade  {
        font-weight: 700;
        font-size: 1.1rem;
        background-color: #e6d2d2;
        background-color: ;
        padding: 1rem;
        color:black;
       
        border-radius: 10px 10px 0 0 !important;
    }
   
    </style>

