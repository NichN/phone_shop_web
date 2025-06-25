document.addEventListener("DOMContentLoaded", function () {
    let wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
    const wishlistIcons = document.querySelectorAll('.add-wishlist');
    const isAuthenticated = window.isAuthenticated || false;

    updateWishlistCount();
    updateWishlistModal();
    syncWishlistIcons();

    wishlistIcons.forEach(icon => {
        icon.addEventListener('click', function () {
            const productItemId = icon.getAttribute('data-product-id');
            const productCard = icon.closest('.product-card');
            const productName = productCard.querySelector('.product-title').innerText;
            const productImage = productCard.querySelector('.product-img').src;
            const productPrice = productCard.querySelector('.card-price').innerText;

            toggleWishlist(productItemId, productName, productPrice, productImage, icon);
        });
    });

    function toggleWishlist(productItemId, productName, productPrice, productImage, icon) {
        productItemId = String(productItemId);
        const existingIndex = wishlist.findIndex(item => item.productItemId === productItemId);

        if (existingIndex === -1) {
            wishlist.push({ productItemId, productName, productPrice, productImage });
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
    }

    function updateWishlistCount() {
        const wishlistCount = document.getElementById('count_heart_cart');
        if (wishlistCount) wishlistCount.textContent = wishlist.length;
    }

    async function fetchProductOptions(productItemId) {
        try {
            const response = await fetch(`/product-items/${productItemId}`);
            if (!response.ok) throw new Error('Failed to load product options');
            return await response.json();
        } catch (error) {
            console.error(error);
            return { sizes: [], colors: [] };
        }
    }

    function buildOptionsHtml(options) {
        return options.map(opt => `<option value="${opt}">${opt}</option>`).join('');
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
            const listItem = document.createElement('li');
            listItem.className = 'list-group-item wishlist-item-card d-flex justify-content-between align-items-center p-4 mb-3 rounded shadow-sm border-0';

            const { sizes, colors } = await fetchProductOptions(item.productItemId);
            const sizeOptions = `<option value="">Select size</option>` + buildOptionsHtml(sizes);
            const colorOptions = `<option value="">Select color</option>` + buildOptionsHtml(colors);

            listItem.innerHTML = `
                <div class="d-flex align-items-center">
                    <img src="${item.productImage}" alt="${item.productName}" class="img-fluid rounded-3" style="width: 80px; height: 80px; object-fit: cover;">
                    <div class="ms-3" style="flex: 1;">
                        <h5 class="mb-1 fw-bold product-title">${item.productName}</h5>
                        <p class="mb-1 text-muted card-price fw-bold" style="color:blue;">${item.productPrice} $</p>
                        <div style="display: flex; gap: 1rem; margin-bottom: 0.5rem;">
                            <div style="flex: 1;">
                                <label for="sizeSelect_${item.productItemId}" class="form-label small mb-1">Size:</label>
                                <select id="sizeSelect_${item.productItemId}" class="form-select form-select-sm">
                                    ${sizeOptions}
                                </select>
                            </div>
                            <div style="flex: 1;">
                                <label for="colorSelect_${item.productItemId}" class="form-label small mb-1">Color:</label>
                                <select id="colorSelect_${item.productItemId}" class="form-select form-select-sm">
                                    ${colorOptions}
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-column align-items-end">
                    <button class="btn btn-danger btn-sm mb-3 position-absolute top-0 end-0 remove-wishlist-btn" data-product-id="${item.productItemId}">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                    <button class="btn btn-outline-primary btn-sm move-to-bag-btn" data-product-id="${item.productItemId}">
                        Move To Bag
                    </button>
                </div>`;

            listwish.appendChild(listItem);

            listItem.querySelector('.remove-wishlist-btn').addEventListener('click', () => {
                removeFromWishlist(item.productItemId);
            });

            listItem.querySelector('.move-to-bag-btn').addEventListener('click', () => {
                const size = document.getElementById(`sizeSelect_${item.productItemId}`).value;
                const color = document.getElementById(`colorSelect_${item.productItemId}`).value;

                if (!size || !color) {
                    alert('Please select size and color before moving to bag.');
                    return;
                }

                moveToBag(item.productName, item.productPrice, item.productImage, item.productItemId, size, color);
            });
        }
    }

    function removeFromWishlist(productItemId) {
        wishlist = wishlist.filter(item => item.productItemId !== productItemId);
        localStorage.setItem('wishlist', JSON.stringify(wishlist));
        updateWishlistCount();
        updateWishlistModal();
        syncWishlistIcons();
    }

    function syncWishlistIcons() {
        wishlistIcons.forEach(icon => {
            const productItemId = icon.getAttribute('data-product-id');
            if (wishlist.some(item => item.productItemId === productItemId)) {
                icon.classList.remove('fa-regular');
                icon.classList.add('fa-solid');
            } else {
                icon.classList.remove('fa-solid');
                icon.classList.add('fa-regular');
            }
        });
    }

    function moveToBag(productName, productPrice, productImage, productItemId, size, color) {
        removeFromWishlist(productItemId);
        if (window.isAuthenticated) {
            handleAddToCart(productItemId);
        } else {
            addProductToCart(productName, productPrice, productImage, productItemId, size, color);
        }
    }

    function handleAddToCart(productItemId) {
        const quantity = 1;
        const id = parseInt(productItemId, 10);
        if (!id || isNaN(id)) {
            alert("Invalid product selection.");
            return;
        }

        $.ajax({
            url: "/store-cart",
            type: "POST",
            data: {
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                product_item_id: id,
                quantity: quantity
            },
            success: function (response) {
                if (response.success) {
                    alert(response.message);
                    updateCartCount();
                    loadCartItems();
                } else {
                    alert("Failed: " + response.message);
                }
            },
            error: function (xhr) {
                alert("Error: " + (xhr.responseJSON?.message || 'Unknown error'));
            }
        });
    }

    window.addProductToCart = function (productName, productPrice, productImage, productItemId, size, color) {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        const existing = cart.find(item =>
            item.productName === productName &&
            item.size === size &&
            item.color === color
        );

        if (existing) {
            existing.quantity += 1;
        } else {
            cart.push({
                productItemId,
                productName,
                productPrice,
                productImage,
                size,
                color,
                quantity: 1
            });
        }

        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartCountLocal();
        loadCartFromLocalStorage();
    };

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

        cart.forEach((item, index) => {
            const itemHtml = `
                <div class="cart-box d-flex align-items-start mb-3">
                    <img src="${item.productImage}" class="cart-img me-2" alt="${item.productName}" style="width: 60px; height: 60px; object-fit: cover;">
                    <div class="detail-box flex-grow-1">
                        <div class="cart-product-title fw-bold">${item.productName}</div>
                        <div class="cart-price">${item.productPrice}</div>
                        <div>Size: ${item.size}</div>
                        <div>Color: ${item.color}</div>
                        <div class="text-danger">Qty: ${item.quantity}</div>
                    </div>
                    <i class="fa-solid fa-trash-can cart-remove text-danger" onclick="removeItemFromLocal(${index})" style="cursor: pointer;"></i>
                </div>
            `;
            cartContent.insertAdjacentHTML('beforeend', itemHtml);
            total += parseFloat(item.productPrice.replace(/[^\d.]/g, '')) * item.quantity;
        });

        const totalPriceElement = document.querySelector('.total-price') || document.getElementById('totalPrice');
        if (totalPriceElement) {
            totalPriceElement.innerText = total.toFixed(2) + "$";
        }
    }

    window.removeItemFromLocal = function (index) {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        cart.splice(index, 1);
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartCountLocal();
        loadCartFromLocalStorage();
    };
});
