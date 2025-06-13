
document.addEventListener('DOMContentLoaded', function () {
    if (window.isAuthenticated) {
        updateCartCount();
        loadCartItems();
    } else {
        updateCartCountLocal();
        loadCartFromLocalStorage();
    }
    document.querySelectorAll('.add-cart').forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault();

            const productId = this.getAttribute('data-product-id');
            const quantity = 0;

            if (window.isAuthenticated) {
                $.ajax({
                    url: "/store-cart",
                    type: "POST",
                    data: {
                        _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        product_id: productId,
                        quantity: quantity
                    },
                    success: function (response) {
                        if (response.success) {
                            alert(response.message);
                            updateCartCount();
                            loadCartItems();
                        } else {
                            alert("Failed to add to cart.");
                        }
                    },
                    error: function (xhr) {
                        alert("Error: " + (xhr.responseJSON?.message || 'Unknown error'));
                    }
                });
            } else {
                const productCard = this.closest('.product-card');
                const title = productCard.querySelector('.product-title').innerText;
                const price = productCard.querySelector('.card-price').innerText;
                const imgSrc = productCard.querySelector('.product-img').src;

                addProductToCart(title, price, imgSrc);
                alert("Added to cart.");
            }
        });
    });

    // Sidebar toggle
    const cartLink = document.getElementById("cartLink");
    const cartSidebar = document.getElementById("cartSidebar");
    const closeCart = document.getElementById("close-card");

    cartLink.addEventListener("click", function (e) {
        e.preventDefault();
        cartSidebar.classList.add("active");

        if (window.isAuthenticated) {
            loadCartItems();
        } else {
            loadCartFromLocalStorage();
        }
    });

    closeCart.addEventListener("click", function () {
        cartSidebar.classList.remove("active");
    });
});


function loadCartFromLocalStorage() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const cartContent = document.querySelector('.cart-content');
    cartContent.innerHTML = '';
    let total = 0;

    cart.forEach((item, index) => {
        const itemHtml = `
            <div class="cart-box">
                <img src="${item.imgSrc}" class="cart-img" alt="${item.title}">
                <div class="detail-box">
                    <div class="cart-product-title">${item.title}</div>
                    <div class="cart-price">${item.price}</div>
                    <input type="number" value="${item}" class="cart-quantity" disabled>
                </div>
                <i class="bx bxs-trash-alt cart-remove" data-id="${index}" onclick="removeItemFromLocal(${index})"></i>
            </div>
        `;
        cartContent.insertAdjacentHTML('beforeend', itemHtml);
        total += parseFloat(item.price.replace(/[^\d.]/g, '')) * item.quantity;
    });

    document.getElementById('totalPrice').innerText = total.toFixed(2) + "$";
}

function addProductToCart(title, price, imgSrc) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    const existing = cart.find(item => item.title === title);

    if (existing) {
        existing.quantity++;
    } else {
        cart.push({ title, price, imgSrc, quantity: 1 });
    }

    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCountLocal();
    loadCartFromLocalStorage();
}

function removeItemFromLocal(index) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    cart.splice(index, 1);
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCountLocal();
    loadCartFromLocalStorage();
}

function updateCartCountLocal() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    updateCartBadge(cart.length);
}

function updateCartCount() {
    $.ajax({
        url: "/countcart",
        method: "GET",
        success: function (response) {
            updateCartBadge(response.cart_count || 0);
        },
        error: function () {
            console.error("Failed to count cart items");
        }
    });
}

function loadCartItems() {
    $.ajax({
        url: "/checkcart",
        method: "GET",
        success: function (response) {
            const cartContent = document.querySelector('.cart-content');
            cartContent.innerHTML = '';
            let total = 0;

            response.cartItems.forEach(item => {
                const itemHtml = `
                    <div class="cart-box" style="display: flex; align-items: center; gap: 15px; padding: 10px; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 8px; background-color: #f9f9f9;">
                        <img src="${item.image}" alt="${item.name}" class="product-img" style="height: 80px; width: 80px; object-fit: cover; border-radius: 6px;">
                        <div class="detail-box" style="flex: 1;">
                            <div class="cart-product-title" style="font-weight: 600; font-size: 1rem;">${item.name}</div>
                            <div class="cart-price" style="color: red; font-size: 0.95rem; margin: 4px 0;">${item.price} $</div>
                            <div style="display: flex; align-items: center; gap: 6px;">
                                <span style="font-size: 0.85rem; color: #555;">Qty:</span>
                                <input type="number" value="${item.quantity}" class="cart-quantity" disabled style="width: 60px; padding: 4px; font-size: 0.85rem;">
                            </div>
                        </div>
                        <button class="cart-remove-btn" data-id="${item.id}" style="background-color: #dc3545; color: white; border: none; border-radius: 4px; padding: 6px 10px; font-size: 0.85rem; cursor: pointer;">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                `;
                cartContent.insertAdjacentHTML('beforeend', itemHtml);
                total += parseFloat(item.price.replace(/[^\d.]/g, '')) * item.quantity;
            });

            const totalPriceElement = document.querySelector('.total-price');
            if (totalPriceElement) {
                totalPriceElement.innerText = total.toFixed(2) + "$";
            }
        },
        error: function () {
            console.error("Failed to load cart items");
        }
    });
}

$(document).ready(function () {
    $(document).on('click', '.cart-remove-btn', function () {
        const cartId = $(this).data('id');
        removeItemFromServerCart(cartId);
    });
});

function removeItemFromServerCart(cartId) {
    $.ajax({
        url: '/remove/' + cartId,
        type: 'DELETE',
        data: {
            id: cartId,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            if (response.success) {
                alert('Item removed successfully');
                loadCartItems();
            } else {
                alert('Failed to remove item.');
            }
        },
        error: function (xhr) {
            console.error('Error removing item:', xhr.responseText);
            alert('Something went wrong while removing the item.');
        }
    });
}
function updateCartBadge(count) {
    const badge = document.getElementById('count_cart');
    if (badge) {
        badge.textContent = count;
        badge.style.display = count > 0 ? 'inline-block' : 'none';
    }
}
