document.addEventListener("DOMContentLoaded", function () {
    let wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
    const wishlistIcons = document.querySelectorAll('.add-wishlist');
    const isAuthenticated = window.isAuthenticated || false;

    updateWishlistCount();
    updateWishlistModal();
    syncWishlistIcons();

    wishlistIcons.forEach(icon => {
        icon.addEventListener('click', function () {
            const productItemId = icon.getAttribute('data-product-item-id');
            const proId = icon.getAttribute('data-product-pro-id');

            if (!productItemId || !proId) {
                return; // Removed the alert
            }

            const productCard = icon.closest('.product-card') || icon.closest('.card-body');
            const title = productCard.querySelector('.product-title')?.innerText || 'Untitled';
            const imgSrc = productCard.closest('.product-card')?.querySelector('.product-img')?.src || '';
            const price = productCard.querySelector('.card-price')?.innerText || '$0.00';

            toggleWishlist(productItemId, proId, title, price, imgSrc, icon);
        });
    });

    // Toggle Wishlist: Add/Remove Items from Wishlist in LocalStorage
    function toggleWishlist(productItemId, proId, title, price, imgSrc, icon) {
        productItemId = String(productItemId);
        proId = String(proId);

        // Check for duplicates by title (case insensitive)
        const existingIndex = wishlist.findIndex(item => 
            item.title.toLowerCase().trim() === title.toLowerCase().trim()
        );

        if (existingIndex === -1) {
            wishlist.push({ productItemId, proId, title, price, imgSrc });
            icon.classList.remove('fa-regular');
            icon.classList.add('fa-solid');
        } else {
            wishlist.splice(existingIndex, 1);
            icon.classList.remove('fa-solid');
            icon.classList.add('fa-regular');
        }

        localStorage.setItem('wishlist', JSON.stringify(wishlist));
        updateWishlistCount();
        updateWishlistModal();
        syncWishlistIcons();
    }

    // Update the Wishlist Count
    function updateWishlistCount() {
        const wishlistCount = document.getElementById('count_heart_cart');
        if (wishlistCount) wishlistCount.textContent = wishlist.length;
    }

    // Fetch product options (size/color)
    async function fetchProductOptions(productItemId) {
        try {
            const response = await fetch(`/product-items/${productItemId}`);
            if (!response.ok) throw new Error('Failed to load product options');
            return await response.json();
        } catch (error) {
            console.error("Error fetching product options:", error);
            return { sizes: [], colors: [] };
        }
    }

    function removeFromWishlist(productItemId) {
        wishlist = wishlist.filter(item => item.productItemId !== productItemId);
        localStorage.setItem('wishlist', JSON.stringify(wishlist));
        updateWishlistCount();
        updateWishlistModal();
        syncWishlistIcons();
    }

    async function updateWishlistModal() {
        const listwish = document.getElementById('listwish');
        if (!listwish) return;

        listwish.innerHTML = '';

        if (wishlist.length === 0) {
            listwish.innerHTML = `
                <div class="text-center p-4">
                    <i class="fa-regular fa-heart fs-1 text-muted mb-2"></i>
                    <p class="text-muted mb-0">Your wishlist is empty.</p>
                    <small class="text-muted">Start adding items you love ❤️</small>
                </div>`;
            return;
        }

        for (const item of wishlist) {
            if (!item.productItemId) continue;

            const { sizes, colors } = await fetchProductOptions(item.productItemId);

            const sizeOptions = `<option value="">Select size</option>` + sizes.map(s => `<option value="${s}">${s}</option>`).join('');
            const colorOptions = `<option value="">Select color</option>` + colors.map(c => `<option value="${c.code}">${c.name}</option>`).join('');

            const priceNum = typeof item.price === 'number' ? item.price : parseFloat(item.price.replace(/[^\d.-]/g, '')) || 0;
            const formattedPrice = priceNum.toFixed(2);

            const listItem = document.createElement('li');
            listItem.className = 'list-group-item wishlist-item-card d-flex justify-content-between align-items-center p-4 mb-3 rounded shadow-sm border-0 position-relative';

            listItem.innerHTML = `
                <div class="d-flex align-items-center">
                    <img src="${item.imgSrc}" alt="${item.title}" class="img-fluid rounded-3" style="width: 80px; height: 80px; object-fit: cover;">
                    <div class="ms-3" style="flex: 1;">
                        <h5 class="mb-1 fw-bold product-title">${item.title}</h5>
                        <p class="mb-1 text-muted card-price fw-bold" style="color:blue;">$${formattedPrice}</p>
                        <div style="display: flex; gap: 1rem; margin-bottom: 0.5rem;">
                            <div style="flex: 1;">
                                <label class="form-label small mb-1">Size:</label>
                                <select class="form-select form-select-sm size-select">
                                    ${sizeOptions}
                                </select>
                            </div>
                            <div style="flex: 1;">
                                <label class="form-label small mb-1">Color:</label>
                                <select class="form-select form-select-sm color-select">
                                    ${colorOptions}
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-column align-items-end">
                    <button class="btn btn-danger btn-sm remove-wishlist-btn position-absolute top-0 end-0" style="z-index: 1; padding: 0.25rem 0.5rem; font-size: 1.1rem;" data-product-id="${item.productItemId}">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                    <button class="btn btn-outline-primary btn-sm move-to-bag-btn mt-3" data-product-id="${item.productItemId}">
                        Move To Bag
                    </button>
                </div>`;

            listwish.appendChild(listItem);

            listItem.querySelector('.remove-wishlist-btn').addEventListener('click', () => {
                removeFromWishlist(item.productItemId);
            });

            listItem.querySelector('.move-to-bag-btn').addEventListener('click', () => {
                const size = listItem.querySelector('.size-select').value;
                const color = listItem.querySelector('.color-select').value;
                console.log(size, color);

                if (!size || !color) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Selection Required',
                        text: 'Please select both size and color.',
                        confirmButtonText: 'OK',
                        customClass: {
                            popup: 'swal2-popup-above-modal',
                            confirmButton: 'btn btn-warning'
                        },
                        didOpen: () => {
                            const popup = Swal.getPopup();
                            popup.style.zIndex = '1080'; // Adjust based on your modal's z-index
                        }
                    });
                    return;
                }

                fetch(`/get-product-item-id?pro_id=${item.proId}&size=${size}&color_code=${encodeURIComponent(color)}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error("Product variation fetch failed.");
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            const finalProductItemId = data.product_item_id;
                            removeFromWishlist(item.productItemId);
                            moveToBag(item.title, item.price, item.imgSrc, finalProductItemId, size, color);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Product Error',
                                text: 'Error: Product variation not found.',
                                confirmButtonText: 'OK',
                                customClass: {
                                    popup: 'swal2-popup-above-modal',
                                    confirmButton: 'btn btn-danger'
                                },
                                didOpen: () => {
                                    const popup = Swal.getPopup();
                                    popup.style.zIndex = '1080';
                                }
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching product_item_id:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Availability Error',
                            text: 'Error: The product you selected is not available.',
                            confirmButtonText: 'OK',
                            customClass: {
                                popup: 'swal2-popup-above-modal',
                                confirmButton: 'btn btn-danger'
                            },
                            didOpen: () => {
                                const popup = Swal.getPopup();
                                popup.style.zIndex = '1080';
                            }
                        });
                    });
            });
        }
    }

    // Remove Item from Wishlist (localStorage)
    function removeItemFromLocal(productItemId) {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        cart = cart.filter(item => item.productItemId !== productItemId);
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartCountLocal();
        loadCartFromLocalStorage();
    }

    // Sync Wishlist Icons
    function syncWishlistIcons() {
        wishlistIcons.forEach(icon => {
            const productItemId = icon.getAttribute('data-product-item-id');
            const isInWishlist = wishlist.some(item => item.productItemId === productItemId);
            icon.classList.toggle('fa-regular', !isInWishlist);
            icon.classList.toggle('fa-solid', isInWishlist);
        });
    }

    // Move to Cart and Clear from Wishlist
    function moveToBag(title, price, imgSrc, productItemId, size, color) {
        addProductToCart(title, price, imgSrc, productItemId, size, color);
        removeFromWishlist(productItemId);

        // If logged in, sync with backend
        if (window.isAuthenticated) {
            if (typeof handleAddToCart === 'function') {
                handleAddToCart(productItemId, size, color);
            } else {
                console.error('handleAddToCart function is not defined.');
                alert('Error: Unable to sync cart with server.');
            }
        }
    }

    // Add Product to Cart (localStorage)
    function addProductToCart(title, price, imgSrc, productItemId, size, color) {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        const priceNumeric = parseFloat(price.replace(/[^\d.]/g, '')) || 0;

        const existing = cart.find(item => item.id === productItemId && item.size === size && item.color === color);
        if (existing) {
            existing.quantity += 1;
        } else {
            cart.push({
                id: productItemId,
                title,
                price: priceNumeric,
                imgSrc,
                size,
                color,
                quantity: 1
            });
        }

        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartCountLocal();
        loadCartFromLocalStorage();
    }

    // Update Cart Count
    function updateCartCountLocal() {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        const totalQty = cart.reduce((sum, item) => sum + item.quantity, 0);
        updateCartBadge(totalQty);
    }

    function updateCartBadge(count) {
        const badge = document.getElementById('count_cart');
        if (badge) {
            badge.textContent = count;
            badge.style.display = count > 0 ? 'inline-block' : 'none';
        }
    }

    function loadCartFromLocalStorage() {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        const cartContent = document.querySelector('.cart-content');
        if (!cartContent) return;

        cartContent.innerHTML = '';
        let total = 0;

        cart.forEach(item => {
            const priceNum = Number(item.price) || 0;
            const itemHtml = `
                <div class="cart-box d-flex align-items-start mb-3">
                    <img src="${item.imgSrc}" class="cart-img me-2" alt="${item.title}" style="width: 60px; height: 60px; object-fit: cover;">
                    <div class="detail-box flex-grow-1">
                        <div class="cart-product-title fw-bold">${item.title}</div>
                        <div class="cart-price">$${priceNum.toFixed(2)}</div>
                        <div>Size: ${item.size}</div>
                        <div>Color: ${item.color}</div>
                        <div class="text-danger">Qty: ${item.quantity}</div>
                    </div>
                    <i class="fa-solid fa-trash-can cart-remove text-danger" onclick="removeItemFromLocal(${item.productItemId})" style="cursor: pointer;"></i>
                </div>`;
            cartContent.insertAdjacentHTML('beforeend', itemHtml);
            total += priceNum * item.quantity;
        });

        const totalPriceElement = document.querySelector('.total-price') || document.getElementById('totalPrice');
        if (totalPriceElement) {
            totalPriceElement.innerText = `$${total.toFixed(2)}`;
        }
    }

    function removeItemFromLocal(index) {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        cart.splice(index, 1);
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartCountLocal();
        loadCartFromLocalStorage();
    }

    // Function to clear duplicates (run this in browser console)
    window.clearDuplicates = function() {
        const uniqueItems = [];
        const seenTitles = new Set();
        
        wishlist.forEach(item => {
            const titleKey = item.title.toLowerCase().trim();
            if (!seenTitles.has(titleKey)) {
                seenTitles.add(titleKey);
                uniqueItems.push(item);
            }
        });
        
        wishlist = uniqueItems;
        localStorage.setItem('wishlist', JSON.stringify(wishlist));
        updateWishlistCount();
        updateWishlistModal();
        syncWishlistIcons();
        console.log('Duplicates cleared!');
    };
});