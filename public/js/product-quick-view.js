document.addEventListener('DOMContentLoaded', function() {
    console.log('Product Quick View JS loaded');
    
    // Handle cart icon clicks for quick product view
    const cartIcons = document.querySelectorAll('.add-cart-quick');
    console.log('Found cart icons:', cartIcons.length);
    
    cartIcons.forEach(icon => {
        icon.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const productItemId = this.getAttribute('data-product-item-id');
            const productProId = this.getAttribute('data-product-pro-id');
            
            console.log('Cart icon clicked:', { productItemId, productProId });
            
            if (productItemId && productProId) {
                console.log('Loading product details for:', { productItemId, productProId });
                loadProductDetail(productItemId, productProId);
            } else {
                console.error('Missing product data:', { productItemId, productProId });
                alert('Missing product data. Please refresh the page and try again.');
            }
        });
    });
});

// Function to load product details into modal
async function loadProductDetail(productItemId, productProId) {
    try {
        // Show loading state
        const modalBody = document.getElementById('productDetailModalBody');
        if (!modalBody) {
            throw new Error('Modal body element not found');
        }
        modalBody.innerHTML = `
            <div class="text-center p-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Loading product details...</p>
            </div>
        `;
        
        // Show the modal
        const modalElement = document.getElementById('productDetailModal');
        if (!modalElement) {
            throw new Error('Modal element not found');
        }
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
        
        // Fetch product details
        const response = await fetch(`/api/product-detail/${productItemId}`);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const productData = await response.json();
        
        // Validate product data
        if (!productData || !productData.name) {
            throw new Error('Invalid product data received');
        }
        
        // Render product details
        renderProductDetail(productData);
        
    } catch (error) {
        console.error('Error loading product details:', error);
        const modalBody = document.getElementById('productDetailModalBody');
        if (modalBody) {
            modalBody.innerHTML = `
                <div class="text-center p-4">
                    <i class="fas fa-exclamation-triangle text-warning fs-1 mb-3"></i>
                    <h5>Error Loading Product</h5>
                    <p class="text-muted">Error: ${error.message}</p>
                    <p class="text-muted">Unable to load product details. Please try again.</p>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                </div>
            `;
        }
    }
}

// Function to render product details in modal
function renderProductDetail(productData) {
    const modalBody = document.getElementById('productDetailModalBody');
    if (!modalBody) return;
    
    // Create product detail HTML
    const style = `
        <style>
            .selected-color {
                border: 2px solid #007bff !important;
                box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
            }
            .out-of-stock {
                border: 2px solid #dc3545 !important;
                opacity: 0.5;
                cursor: not-allowed;
            }
            .na-selection {
                border: 2px solid #dc3545 !important;
                box-shadow: 0 0 5px rgba(220, 53, 69, 0.5);
            }
        </style>
    `;
    const productHTML = `
        ${style}
        <div class="row">
            <div class="col-md-6">
                <div class="main-image-container border">
                    <img id="modalMainImage" src="${productData.mainImage || '/images/placeholder.jpg'}" alt="${productData.name || 'Product'}" 
                         class="img-fluid" style="max-height: 400px; object-fit: contain;">
                </div>
                <div class="thumbnail-scroll-container mt-3">
                    <div class="d-flex gap-2">
                        ${productData.images?.map((img, index) => `
                            <div class="thumbnail-wrapper">
                                <img src="${img}" class="thumbnail-img ${index === 0 ? 'selected-thumbnail' : ''}" 
                                     onclick="changeModalImage(this)" data-full-image="${img}" 
                                     style="width: 60px; height: 60px; object-fit: cover; cursor: pointer; border: 2px solid ${index === 0 ? '#007bff' : '#dee2e6'};">
                            </div>
                        `).join('') || ''}
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h3 class="fw-bold text-primary">${productData.name || 'Unnamed Product'}</h3>
                    <span class="badge fs-6" style="background-color: #90EE90; color: #000;">
                        ${productData.type || 'N/A'}<span>*</span>
                    </span>
                </div>
                
                <h5 class="text-danger mb-4">
                    <strong id="modalProductPrice">${productData.price || 'N/A'} USD</strong>
                </h5>
                
                <div class="mb-4">
                    <h5 class="fs-6">Warranty</h5>
                    <div class="card shadow-sm border-0">
                        <div class="card-body bg-light text-dark rounded">
                            ${productData.warranty || 'No warranty information available.'}
                        </div>
                    </div>
                </div>
                
                <div class="choose-color mb-4">
                    <h5 class="mb-4 fs-6">CHOOSE COLOR</h5>
                    <div class="d-flex gap-3">
                        ${productData.colors?.map((color, index) => `
                            <input type="radio" class="btn-check" name="modalColor" id="modalColor_${index}" 
                                   value="${color}" autocomplete="off" ${index === 0 ? 'checked' : ''}>
                            <label class="btn btn-light d-flex flex-column align-items-center justify-content-center" 
                                   for="modalColor_${index}">
                                <span class="rounded-circle d-block" 
                                      style="width: 20px; height: 20px; background-color: ${color.toLowerCase()};"></span>
                            </label>
                        `).join('') || '<span>No colors available</span>'}
                    </div>
                </div>
                
                <div class="choose-quantity mb-4">
                    <h5 class="mb-2 fs-6 fw-bold">Quantity</h5>
                    <div class="d-flex gap-3 align-items-center">
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="decreaseQty">-</button>
                        <input type="number" id="modalProductQuantity" class="form-control form-control-sm text-center" 
                               value="1" min="1" max="999" style="width: 60px;">
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="increaseQty">+</button>
                    </div>
                </div>
                
                <div class="choose-storage mb-4">
                    <h5 class="mb-4 fs-6">CHOOSE STORAGE</h5>
                    <div class="d-flex gap-3">
                        ${productData.sizes?.map((size, index) => `
                            <input type="radio" class="btn-check" name="modalStorage" id="modalStorage_${index}" 
                                   autocomplete="off" value="${size}" ${index === 0 ? 'checked' : ''}>
                            <label class="btn btn-outline-success" for="modalStorage_${index}">${size}</label>
                        `).join('') || '<span>No sizes available</span>'}
                    </div>
                </div>
                
                <div class="mt-3">
                    <div class="mb-2">
                        <span class="text-muted">
                            <i class="fas fa-box me-1"></i>
                            Stock Available: <span class="fw-bold text-primary" id="modalStockDisplay">${productData.stock || 'N/A'}</span>
                        </span>
                    </div>
                    <button class="btn btn-dark px-4 py-2 custom-btn w-100 add-cart-modal" 
                            data-product-item-id="${productData.productItemId || ''}" 
                            data-title="${productData.name || ''}" 
                            data-price="${productData.price || ''}" 
                            data-img="${productData.mainImage || ''}">
                        Add to Cart
                    </button>
                </div>
            </div>
        </div>
    `;
    
    modalBody.innerHTML = productHTML;
    
    // Add event listeners for the modal
    setupModalEventListeners(productData);
}

// Function to change modal image
function changeModalImage(thumbnail) {
    document.querySelectorAll('.thumbnail-img').forEach(img => {
        img.classList.remove('selected-thumbnail');
        img.style.border = '2px solid #dee2e6';
    });
    thumbnail.classList.add('selected-thumbnail');
    thumbnail.style.border = '2px solid #007bff';
    
    const mainImage = document.getElementById('modalMainImage');
    if (mainImage) {
        mainImage.src = thumbnail.getAttribute('data-full-image');
    }
}

// Handle quantity buttons
document.addEventListener('click', function(e) {
    const qtyInput = document.getElementById('modalProductQuantity');
    if (!qtyInput) return;
    
    if (e.target.id === 'increaseQty') {
        if (e.target.id === 'increaseQty') {
    qtyInput.value = Math.min(parseInt(qtyInput.value) + 1, 999);
}

    }
    if (e.target.id === 'decreaseQty') {
        qtyInput.value = Math.max(parseInt(qtyInput.value) - 1, 1);
    }
});

// Function to handle adding to cart
function handleAddToCart(productItemId, size = null, color = null, quantity = 1) {
    const id = parseInt(productItemId, 10);
    if (!id || isNaN(id)) {
        showCartMessage("Invalid product selection.", 'error');
        return;
    }

    const button = document.querySelector(`[data-product-item-id="${id}"]`);
    
    if (button.disabled) {
        alert("This combination is not available.");
        return;
    }

    const title = button.getAttribute('data-title') || 'Untitled';
    const price = parseFloat(button.getAttribute('data-price')) || 0;
    const imgSrc = button.getAttribute('data-img') || '';
    
    const finalSize = size || document.querySelector('input[name="modalStorage"]:checked')?.value;
    const finalColor = color || document.querySelector('input[name="modalColor"]:checked')?.value;

    // if (!finalSize || !finalColor) {
    //     showCartMessage("Please select both size and color.", 'error');
    //     return;
    // }

    // Authenticated user: send to server
    if (window.isAuthenticated) {
        $.ajax({
            url: "/store-cart",
            type: "POST",
            data: {
                _token: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                product_item_id: id,
                quantity: quantity,
                size: finalSize,
                color: finalColor
            },
            success: function(response) {
                if (response.success) {
                    addOrUpdateLocalCart(id, title, price, imgSrc, finalSize, finalColor, quantity);
                    updateCartCountLocal();
                    loadCartFromLocalStorage();
                    if (typeof showCartSuccessNotification === 'function') {
                        showCartSuccessNotification();
                    }
                } else {
                    showCartMessage("Failed: " + (response.message || 'Unknown error'), 'error');
                }
            },
            error: function(xhr) {
                showCartMessage("Error: " + (xhr.responseJSON?.message || 'Unknown error'), 'error');
            }
        });
    } else {
        // Guest user: localStorage
        addOrUpdateLocalCart(id, title, price, imgSrc, finalSize, finalColor, quantity);
        updateCartCountLocal();
        loadCartFromLocalStorage();
        if (typeof showCartSuccessNotification === 'function') {
            showCartSuccessNotification();
        }
    }
}

// Handle add to cart button click
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('add-cart-modal')) {
        const qty = parseInt(document.getElementById('modalProductQuantity')?.value) || 1;
        // console.log('Add to cart clicked. Quantity:', qty);
        const productId = e.target.dataset.productItemId;
        const selectedColor = document.querySelector('input[name="modalColor"]:checked')?.value;
        const selectedSize = document.querySelector('input[name="modalStorage"]:checked')?.value;
        
        // handleAddToCart(productId, selectedSize, selectedColor, qty);
        // console.log('Add to cart clicked. Quantity:', qty);

    }
});

// Setup modal event listeners
function setupModalEventListeners(productData) {
    const colorInputs = document.querySelectorAll('input[name="modalColor"]');
    const storageInputs = document.querySelectorAll('input[name="modalStorage"]');
    
    // Highlight selected color
    function highlightSelectedColor() {
        colorInputs.forEach(input => {
            const label = input.nextElementSibling;
            if (label) {
                if (input.checked && !input.disabled) {
                    label.classList.add('selected-color');
                    label.classList.remove('na-selection');
                } else {
                    label.classList.remove('selected-color');
                }
            }
        });
    }
    
    colorInputs.forEach(input => {
        input.addEventListener('change', () => {
            document.querySelectorAll('.option-unavailable').forEach(el => {
                el.classList.remove('option-unavailable');
            });
            highlightSelectedColor();
            updateModalVariant(productData);
        });
    });
    
    storageInputs.forEach(input => {
        input.addEventListener('change', () => {
            document.querySelectorAll('.option-unavailable').forEach(el => {
                el.classList.remove('option-unavailable');
            });
            updateModalVariant(productData);
        });
    });
    
    // Check all combinations on modal load
    checkModalCombinations(productData);
    highlightSelectedColor();
    updateModalVariant(productData);
}

// Function to check all combinations and mark unavailable options
function checkModalCombinations(productData) {
    if (!productData.variants) return;
    
    const allColors = Array.from(document.querySelectorAll('input[name="modalColor"]')).map(input => input.value);
    const allSizes = Array.from(document.querySelectorAll('input[name="modalStorage"]')).map(input => input.value);
    
    // Reset unavailable classes
    document.querySelectorAll('.option-unavailable, .out-of-stock').forEach(el => {
        el.classList.remove('option-unavailable', 'out-of-stock');
    });
    
    // Check unavailable colors and out-of-stock status
    allColors.forEach(color => {
        const hasAnyVariantForColor = productData.variants.some(v => 
            v.color_code.toLowerCase() === color.toLowerCase()
        );
        const hasStockForColor = productData.variants.some(v => 
            v.color_code.toLowerCase() === color.toLowerCase() && parseInt(v.stock) > 0
        );
        
        const colorInput = document.querySelector(`input[name="modalColor"][value="${color}"]`);
        if (colorInput) {
            const colorLabel = colorInput.nextElementSibling;
            if (colorLabel) {
                if (!hasAnyVariantForColor || !hasStockForColor) {
                    colorLabel.classList.add('out-of-stock');
                    colorInput.disabled = true;
                }
            }
        }
    });
    
    // Check unavailable sizes
    allSizes.forEach(size => {
        const hasAnyVariantForSize = productData.variants.some(v => 
            v.size.toLowerCase() === size.toLowerCase()
        );
        
        if (!hasAnyVariantForSize) {
            const sizeInput = document.querySelector(`input[name="modalStorage"][value="${size}"]`);
            if (sizeInput) {
                const sizeLabel = sizeInput.nextElementSibling;
                if (sizeLabel) {
                    sizeLabel.classList.add('option-unavailable');
                    sizeInput.disabled = true;
                }
            }
        }
    });
}

// Function to update modal variant based on selection
function updateModalVariant(productData) {
    if (!productData.variants) return;

    const selectedColor = document.querySelector('input[name="modalColor"]:checked')?.value;
    const selectedSize = document.querySelector('input[name="modalStorage"]:checked')?.value;

    const qtyInput = document.getElementById('modalProductQuantity');
    const increaseBtn = document.getElementById('increaseQty');
    const decreaseBtn = document.getElementById('decreaseQty');
    const addCartBtn = document.querySelector('.add-cart-modal');
    const selectedColorInput = document.querySelector('input[name="modalColor"]:checked');
    const selectedSizeInput = document.querySelector('input[name="modalStorage"]:checked');
    const selectedColorLabel = selectedColorInput?.nextElementSibling;
    const selectedSizeLabel = selectedSizeInput?.nextElementSibling;

    if (selectedColor && selectedSize) {
        const variant = productData.variants.find(v =>
            v.color_code.toLowerCase() === selectedColor.toLowerCase() &&
            v.size.toLowerCase() === selectedSize.toLowerCase()
        );

        if (variant && parseInt(variant.stock) > 0) {
            document.getElementById('modalProductPrice').innerHTML = `<strong>${variant.price || 'N/A'} USD</strong>`;
            document.getElementById('modalStockDisplay').textContent = variant.stock ?? 'N/A';
            document.getElementById('modalStockDisplay').className = 'fw-bold text-primary';

            // Update Add to Cart button
            if (addCartBtn) {
                addCartBtn.dataset.productItemId = variant.id || '';
                addCartBtn.dataset.price = variant.price || '';
                addCartBtn.dataset.img = variant.image || productData.mainImage || '';
                addCartBtn.disabled = false;
                addCartBtn.classList.remove('btn-secondary');
                addCartBtn.classList.add('btn-dark');
                addCartBtn.style.cursor = 'pointer';
            }

            // Enable quantity controls
            if (qtyInput && increaseBtn && decreaseBtn) {
                qtyInput.disabled = false;
                increaseBtn.disabled = false;
                decreaseBtn.disabled = false;
            }

            // Remove N/A selection highlight, keep normal selection highlight
            if (selectedColorLabel) {
                selectedColorLabel.classList.remove('na-selection');
                selectedColorLabel.classList.add('selected-color');
            }
            if (selectedSizeLabel) {
                selectedSizeLabel.classList.remove('na-selection');
            }
        } else {
            // Variant not found or out of stock
            document.getElementById('modalProductPrice').innerHTML = `<strong class="text-danger">N/A</strong>`;
            document.getElementById('modalStockDisplay').textContent = 'N/A';
            document.getElementById('modalStockDisplay').className = 'fw-bold text-danger';

            if (addCartBtn) {
                addCartBtn.dataset.productItemId = '';
                addCartBtn.dataset.price = '';
                addCartBtn.dataset.img = '';
                addCartBtn.disabled = true;
                addCartBtn.classList.remove('btn-dark');
                addCartBtn.classList.add('btn-secondary');
                addCartBtn.style.cursor = 'not-allowed';
            }

            // Disable quantity controls
            if (qtyInput && increaseBtn && decreaseBtn) {
                qtyInput.disabled = true;
                increaseBtn.disabled = true;
                decreaseBtn.disabled = true;
            }

            // Apply red border to selected size and color
            if (selectedColorLabel) {
                selectedColorLabel.classList.remove('selected-color');
                selectedColorLabel.classList.add('na-selection');
            }
            if (selectedSizeLabel) {
                selectedSizeLabel.classList.add('na-selection');
            }
        }
    }
}