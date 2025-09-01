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
        modalBody.innerHTML = `
            <div class="text-center p-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Loading product details...</p>
            </div>
        `;
        
        // Show the modal
        const modal = new bootstrap.Modal(document.getElementById('productDetailModal'));
        modal.show();
        
        // Fetch product details
        const response = await fetch(`/api/product-detail/${productItemId}`);
        
        if (!response.ok) {
            throw new Error('Failed to load product details');
        }
        
        const productData = await response.json();
        
        // Render product details
        renderProductDetail(productData);
        
    } catch (error) {
        console.error('Error loading product details:', error);
        const modalBody = document.getElementById('productDetailModalBody');
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

// Function to render product details in modal
function renderProductDetail(productData) {
    const modalBody = document.getElementById('productDetailModalBody');
    
    // Create product detail HTML
    const productHTML = `
        <div class="row">
            <div class="col-md-6">
                <div class="main-image-container border">
                    <img id="modalMainImage" src="${productData.mainImage}" alt="${productData.name}" 
                         class="img-fluid" style="max-height: 400px; object-fit: contain;">
                </div>
                <div class="thumbnail-scroll-container mt-3">
                    <div class="d-flex gap-2">
                        ${productData.images.map((img, index) => `
                            <div class="thumbnail-wrapper">
                                <img src="${img}" class="thumbnail-img ${index === 0 ? 'selected-thumbnail' : ''}" 
                                     onclick="changeModalImage(this)" data-full-image="${img}" 
                                     style="width: 60px; height: 60px; object-fit: cover; cursor: pointer; border: 2px solid ${index === 0 ? '#007bff' : '#dee2e6'};">
                            </div>
                        `).join('')}
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h3 class="fw-bold text-primary">${productData.name}</h3>
                    <span class="badge fs-6" style="background-color: #90EE90; color: #000;">
                        ${productData.type}<span>*</span>
                    </span>
                </div>
                
                <h5 class="text-danger mb-4">
                    <strong id="modalProductPrice">${productData.price} USD</strong>
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
                        ${productData.colors.map((color, index) => `
                            <input type="radio" class="btn-check" name="modalColor" id="modalColor_${index}" 
                                   value="${color}" autocomplete="off" ${index === 0 ? 'checked' : ''}>
                            <label class="btn btn-light d-flex flex-column align-items-center justify-content-center" 
                                   for="modalColor_${index}">
                                <span class="rounded-circle d-block" 
                                      style="width: 20px; height: 20px; background-color: ${color.toLowerCase()};"></span>
                            </label>
                        `).join('')}
                    </div>
                </div>
                
                <div class="choose-storage mb-4">
                    <h5 class="mb-4 fs-6">CHOOSE STORAGE</h5>
                    <div class="d-flex gap-3">
                        ${productData.sizes.map((size, index) => `
                            <input type="radio" class="btn-check" name="modalStorage" id="modalStorage_${index}" 
                                   autocomplete="off" value="${size}" ${index === 0 ? 'checked' : ''}>
                            <label class="btn btn-outline-success" for="modalStorage_${index}">${size}</label>
                        `).join('')}
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
                            data-product-item-id="${productData.productItemId}" 
                            data-title="${productData.name}" 
                            data-price="${productData.price}" 
                            data-img="${productData.mainImage}">
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

// Function to setup modal event listeners
function setupModalEventListeners(productData) {
    // Handle color and storage selection
    const colorInputs = document.querySelectorAll('input[name="modalColor"]');
    const storageInputs = document.querySelectorAll('input[name="modalStorage"]');
    
    colorInputs.forEach(input => {
        input.addEventListener('change', () => {
            // Re-enable all options first
            document.querySelectorAll('.option-unavailable').forEach(el => {
                el.classList.remove('option-unavailable');
            });
            updateModalVariant(productData);
        });
    });
    
    storageInputs.forEach(input => {
        input.addEventListener('change', () => {
            // Re-enable all options first
            document.querySelectorAll('.option-unavailable').forEach(el => {
                el.classList.remove('option-unavailable');
            });
            updateModalVariant(productData);
        });
    });
    
    // Handle add to cart button
    const addCartBtn = document.querySelector('.add-cart-modal');
    if (addCartBtn) {
        addCartBtn.addEventListener('click', function(e) {
            // Prevent click if button is disabled
            if (this.disabled) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
            
            const selectedColor = document.querySelector('input[name="modalColor"]:checked')?.value;
            const selectedSize = document.querySelector('input[name="modalStorage"]:checked')?.value;
            
            if (!selectedColor || !selectedSize) {
                alert('Please select both color and storage.');
                return;
            }
            
            // Check if the selected combination is available
            const matchingVariant = productData.variants.find(v => 
                v.color_code.toLowerCase() === selectedColor.toLowerCase() && 
                v.size.toLowerCase() === selectedSize.toLowerCase()
            );
            
            if (!matchingVariant) {
                alert('This combination is not available.');
                return;
            }
            
            // Use the existing cart functionality
            if (typeof handleAddToCart === 'function') {
                handleAddToCart(this.dataset.productItemId, selectedSize, selectedColor);
                
                // Show success message on button
                this.innerHTML = '<i class="fas fa-check me-2"></i>Added to Cart';
                this.classList.remove('btn-dark');
                this.classList.add('btn-success');
                
                // Show global notification
                if (typeof showCartSuccessNotification === 'function') {
                    showCartSuccessNotification();
                }
                
                setTimeout(() => {
                    this.innerHTML = 'Add to Cart';
                    this.classList.remove('btn-success');
                    this.classList.add('btn-dark');
                }, 2000);
            } else {
                alert('Cart functionality not available.');
            }
        });
    }
    
    // Check all combinations on modal load to show unavailable options immediately
    checkModalCombinations(productData);
    updateModalVariant(productData);
}

// Function to check all combinations and mark unavailable options
function checkModalCombinations(productData) {
    const allColors = Array.from(document.querySelectorAll('input[name="modalColor"]')).map(input => input.value);
    const allSizes = Array.from(document.querySelectorAll('input[name="modalStorage"]')).map(input => input.value);
    
    // First, remove all unavailable classes to reset
    document.querySelectorAll('.option-unavailable').forEach(el => {
        el.classList.remove('option-unavailable');
    });
    
    // Check which colors are completely unavailable (no variants for any size)
    allColors.forEach(color => {
        const hasAnyVariantForColor = productData.variants.some(v => 
            v.color_code.toLowerCase() === color.toLowerCase()
        );
        
        if (!hasAnyVariantForColor) {
            const colorInput = document.querySelector(`input[name="modalColor"][value="${color}"]`);
            if (colorInput) {
                const colorLabel = colorInput.nextElementSibling;
                if (colorLabel) {
                    colorLabel.classList.add('option-unavailable');
                }
            }
        }
    });
    
    // Check which sizes are completely unavailable (no variants for any color)
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
                }
            }
        }
    });
}

// Function to update modal variant based on selection
function updateModalVariant(productData) {
    const selectedColor = document.querySelector('input[name="modalColor"]:checked')?.value;
    const selectedSize = document.querySelector('input[name="modalStorage"]:checked')?.value;
    
    if (selectedColor && selectedSize && productData.variants) {
        const variant = productData.variants.find(v => 
            v.color_code.toLowerCase() === selectedColor.toLowerCase() && 
            v.size.toLowerCase() === selectedSize.toLowerCase()
        );
        
        if (variant) {
            document.getElementById('modalProductPrice').innerHTML = `<strong>${variant.price} USD</strong>`;
            document.getElementById('modalStockDisplay').textContent = variant.stock || 'N/A';
            document.getElementById('modalStockDisplay').className = 'fw-bold text-primary';
            
            const addCartBtn = document.querySelector('.add-cart-modal');
            if (addCartBtn) {
                addCartBtn.dataset.productItemId = variant.id;
                addCartBtn.dataset.price = variant.price;
                addCartBtn.disabled = false;
                addCartBtn.classList.remove('btn-secondary');
                addCartBtn.classList.add('btn-dark');
                addCartBtn.style.cursor = 'pointer';
            }
        } else {
            // No matching variant found - show N/A
            document.getElementById('modalProductPrice').innerHTML = `<strong class="text-danger">N/A</strong>`;
            document.getElementById('modalStockDisplay').textContent = 'N/A';
            document.getElementById('modalStockDisplay').className = 'fw-bold text-danger';
            
            const addCartBtn = document.querySelector('.add-cart-modal');
            if (addCartBtn) {
                addCartBtn.dataset.productItemId = '';
                addCartBtn.dataset.price = '';
                addCartBtn.disabled = true;
                addCartBtn.classList.remove('btn-dark');
                addCartBtn.classList.add('btn-secondary');
                addCartBtn.style.cursor = 'not-allowed';
            }
            
            // Mark the currently selected options as unavailable
            if (selectedColor) {
                const selectedColorInput = document.querySelector(`input[name="modalColor"][value="${selectedColor}"]`);
                if (selectedColorInput) {
                    const selectedColorLabel = selectedColorInput.nextElementSibling;
                    if (selectedColorLabel) {
                        selectedColorLabel.classList.add('option-unavailable');
                    }
                }
            }
            if (selectedSize) {
                const selectedSizeInput = document.querySelector(`input[name="modalStorage"][value="${selectedSize}"]`);
                if (selectedSizeInput) {
                    const selectedSizeLabel = selectedSizeInput.nextElementSibling;
                    if (selectedSizeLabel) {
                        selectedSizeLabel.classList.add('option-unavailable');
                    }
                }
            }
        }
    }
}
