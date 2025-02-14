document.addEventListener("DOMContentLoaded", function () {
    let wishlist = [];
    const wishlistIcons = document.querySelectorAll('.add-wishlist');
    wishlistIcons.forEach(icon => {
        icon.addEventListener('click', function () {
            const productId = icon.getAttribute('data-product-id');
            const productName = icon.closest('.product-card').querySelector('.product-title').innerText; // Get the product name
            const productImage = icon.closest('.product-card').querySelector('.product-img').src; // Get the product image
            const productPrice = icon.closest('.product-card').querySelector('.card-price').innerText; // Get the product price
            addToWishlist(productId, icon, productName, productPrice, productImage);
        });
    });

    function addToWishlist(productId, icon, productName, productPrice, productImage) {
        if (!wishlist.some(item => item.productId === productId)) {
            wishlist.push({ productId, productName, productPrice, productImage });
            icon.classList.remove('fa-regular');
            icon.classList.add('fa-solid');
            updateWishlistCount();
            updateWishlistModal();
        } else {
            alert("This item is already in your wishlist.");
        }
    }

    function updateWishlistCount() {
        const wishlistCount = document.getElementById('count_heart_cart');
        wishlistCount.textContent = wishlist.length;
    }

    function updateWishlistModal() {
        const listwish = document.getElementById('listwish');
        listwish.innerHTML = ''; // Clear existing wishlist items

        wishlist.forEach(item => {
            // Create list item for each product in the wishlist
            const listItem = document.createElement('li');
            listItem.classList.add('list-group-item', 'wishlist-item-card', 'd-flex', 'justify-content-between', 'align-items-center', 'p-4', 'mb-3', 'rounded', 'shadow-sm', 'border-0');

            const wishlistContent = `
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
            
            listItem.innerHTML = wishlistContent;
            listwish.appendChild(listItem);
            const removeButton = listItem.querySelector('.btn-danger');
            removeButton.addEventListener('click', function () {
                removeFromWishlist(item.productId);
            });
        });
    }

    function removeFromWishlist(productId) {
        wishlist = wishlist.filter(item => item.productId !== productId);
        updateWishlistCount();
        updateWishlistModal();
    }

    function moveToBag(productId, productName, productPrice, productImage) {
        removeFromWishlist(productId); 
        addProductToCart(productName, productPrice, productImage); 
    }

    function addProductToCart(productName, productPrice, productImage) {
        console.log("Added to cart:", productName, productPrice, productImage);
        alert(`${productName} has been moved to the shopping bag.`);
    }
});
