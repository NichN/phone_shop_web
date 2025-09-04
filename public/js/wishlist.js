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

            if (!productItemId || !proId) return;

            const productCard = icon.closest('.product-card') || icon.closest('.card-body');
            const title = productCard.querySelector('.product-title')?.innerText || 'Untitled';
            const imgSrc = productCard.closest('.product-card')?.querySelector('.product-img')?.src || '';
            const price = productCard.querySelector('.card-price')?.innerText || '$0.00';

            toggleWishlist(productItemId, proId, title, price, imgSrc, icon);
        });
    });

    function toggleWishlist(productItemId, proId, title, price, imgSrc, icon) {
        productItemId = String(productItemId);
        proId = String(proId);

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
            const priceNum = typeof item.price === 'number' ? item.price : parseFloat(item.price.replace(/[^\d.-]/g, '')) || 0;
            const formattedPrice = priceNum.toFixed(2);

            const listItem = document.createElement('li');
            listItem.className = 'list-group-item wishlist-item-card d-flex justify-content-between align-items-center p-4 mb-3 rounded shadow-sm border-0 position-relative';

            listItem.innerHTML = `
                <div class="d-flex align-items-center w-100">
                    <img src="${item.imgSrc}" alt="${item.title}" class="img-fluid rounded-3" style="width: 80px; height: 80px; object-fit: cover;">
                    <div class="ms-3 flex-grow-1">
                        <h5 class="fw-bold mb-1 product-title">${item.title}</h5>
                        <p class="text-muted fw-bold mb-2" style="color:blue;">$${formattedPrice}</p>

                        <div class="mb-2">
                            <label class="form-label small mb-1">Choose Size:</label>
                            <div class="btn-group size-options" role="group">
                                ${sizes.map(s => `
                                    <button type="button" class="btn btn-outline-dark btn-sm size-btn" data-size="${s}">${s}</button>
                                `).join('')}
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label small mb-1">Choose Color:</label>
                            <div class="d-flex gap-2 color-options">
                                ${colors.map(c => `
                                    <button type="button" class="btn color-btn border" 
                                        style="background-color: ${c.code}; width: 30px; height: 30px; border-radius: 50%;" 
                                        title="${c.name}" data-color="${c.code}">
                                    </button>
                                `).join('')}
                            </div>
                        </div>

                        <div class="variant-error-msg mt-2 text-danger small d-none"></div>
                    </div>

                    <div class="d-flex flex-column align-items-end">
                        <button class="btn btn-danger btn-sm remove-wishlist-btn position-absolute top-0 end-0" style="z-index: 1;" data-product-id="${item.productItemId}">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                        <button class="btn btn-outline-primary btn-sm move-to-bag-btn mt-3" data-product-id="${item.productItemId}">
                            Move To Bag
                        </button>
                    </div>
                </div>`;

            listwish.appendChild(listItem);

            let selectedSize = null;
            let selectedColor = null;

            listItem.querySelectorAll('.size-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    listItem.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    selectedSize = btn.dataset.size;
                });
            });

            listItem.querySelectorAll('.color-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    listItem.querySelectorAll('.color-btn').forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    selectedColor = btn.dataset.color;
                });
            });

            listItem.querySelector('.remove-wishlist-btn').addEventListener('click', () => {
                removeFromWishlist(item.productItemId);
            });

            listItem.querySelector('.move-to-bag-btn').addEventListener('click', () => {
                const errorBox = listItem.querySelector('.variant-error-msg');
                errorBox.classList.add('d-none');
                errorBox.textContent = '';

                if (!selectedSize || !selectedColor) {
                    errorBox.textContent = 'Please select both size and color.';
                    errorBox.classList.remove('d-none');
                    return;
                }

                fetch(`/get-product-item-id?pro_id=${item.proId}&size=${selectedSize}&color_code=${encodeURIComponent(selectedColor)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success || !data.product_item_id) {
                            errorBox.textContent = 'This product variant is not available.';
                            errorBox.classList.remove('d-none');
                            return;
                        }

                        if (data.stock !== undefined && parseInt(data.stock) <= 0) {
                            errorBox.textContent = 'This item is currently out of stock.';
                            errorBox.classList.remove('d-none');
                            return;
                        }

                        const finalProductItemId = data.product_item_id;
                        removeFromWishlist(item.productItemId);
                        moveToBag(item.title, item.price, item.imgSrc, finalProductItemId, selectedSize, selectedColor);
                    })
                    .catch(() => {
                        errorBox.textContent = 'Failed to check product availability.';
                        errorBox.classList.remove('d-none');
                    });
            });
        }
    }

    function syncWishlistIcons() {
        wishlistIcons.forEach(icon => {
            const productItemId = icon.getAttribute('data-product-item-id');
            const isInWishlist = wishlist.some(item => item.productItemId === productItemId);
            icon.classList.toggle('fa-regular', !isInWishlist);
            icon.classList.toggle('fa-solid', isInWishlist);
        });
    }

    function moveToBag(title, price, imgSrc, productItemId, size, color) {
        addProductToCart(title, price, imgSrc, productItemId, size, color);
        removeFromWishlist(productItemId);

        if (typeof showCartSuccessNotification === 'function') {
            showCartSuccessNotification();
        }

        if (isAuthenticated && typeof handleAddToCart === 'function') {
            handleAddToCart(productItemId, size, color);
        }
    }

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

    // Helper to clear duplicates from wishlist (browser console)
    window.clearDuplicates = function () {
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
