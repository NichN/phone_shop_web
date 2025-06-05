document.addEventListener("DOMContentLoaded", function () {
    let wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];

    const wishlistIcons = document.querySelectorAll('.add-wishlist');

    // Initial UI sync
    updateWishlistCount();
    updateWishlistModal();
    syncWishlistIcons(); // ← Make sure this comes after you select the icons

    wishlistIcons.forEach(icon => {
        icon.addEventListener('click', function () {
            const productId = icon.getAttribute('data-product-id');
            const productCard = icon.closest('.product-card');
            const productName = productCard.querySelector('.product-title').innerText;
            const productImage = productCard.querySelector('.product-img').src;
            const productPrice = productCard.querySelector('.card-price').innerText;

            toggleWishlist(productId, icon, productName, productPrice, productImage);
        });
    });

    function toggleWishlist(productId, icon, productName, productPrice, productImage) {
        productId = String(productId);

        const existingIndex = wishlist.findIndex(item => item.productId === productId);
        if (existingIndex === -1) {
            wishlist.push({ productId, productName, productPrice, productImage });
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
        wishlistCount.textContent = wishlist.length;
    }

    function updateWishlistModal() {
        const listwish = document.getElementById('listwish');
        listwish.innerHTML = '';

        wishlist.forEach(item => {
            const listItem = document.createElement('li');
            listItem.classList.add('list-group-item', 'wishlist-item-card', 'd-flex', 'justify-content-between', 'align-items-center', 'p-4', 'mb-3', 'rounded', 'shadow-sm', 'border-0');

            listItem.innerHTML = `
                <div class="d-flex align-items-center">
                    <img src="${item.productImage}" alt="${item.productName}" class="img-fluid rounded-3" style="width: 80px; height: 80px; object-fit: cover;">
                    <div class="ms-3">
                        <h5 class="mb-1 fw-bold product-title">${item.productName}</h5>
                        <p class="mb-1 text-muted card-price">${item.productPrice}</p>
                    </div>
                </div>
                <div class="d-flex flex-column align-items-end">
                    <button class="btn btn-danger btn-sm mb-3 position-absolute top-0 end-0" data-product-id="${item.productId}">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                    <button class="btn btn-outline-primary btn-sm" onclick="moveToBag('${item.productId}', '${item.productName}', '${item.productPrice}', '${item.productImage}')">
                        Move To Bag
                    </button>
                </div>
            `;

            listwish.appendChild(listItem);

            const removeButton = listItem.querySelector('.btn-danger');
            removeButton.addEventListener('click', function () {
                removeFromWishlist(item.productId);
            });
        });
    }

    function removeFromWishlist(productId) {
        wishlist = wishlist.filter(item => item.productId !== productId);
        localStorage.setItem('wishlist', JSON.stringify(wishlist));
        updateWishlistCount();
        updateWishlistModal();
        syncWishlistIcons(); // Reflect changes on icons
    }

    function syncWishlistIcons() {
        wishlistIcons.forEach(icon => {
            const productId = icon.getAttribute('data-product-id');
            if (wishlist.some(item => item.productId === productId)) {
                icon.classList.remove('fa-regular');
                icon.classList.add('fa-solid');
            } else {
                icon.classList.remove('fa-solid');
                icon.classList.add('fa-regular');
            }
        });
    }

    // Update the moveToBag function to properly add to cart
    window.moveToBag = function (productId, productName, productPrice, productImage) {
        removeFromWishlist(productId);
        window.addProductToCart(productName, productPrice, productImage);
    };


    window.addProductToCart(productName, productPrice, productImage);
});

