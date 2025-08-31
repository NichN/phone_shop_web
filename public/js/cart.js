window.isAuthenticated = window.isAuthenticated || false;

document.addEventListener('DOMContentLoaded', function () {
    updateCartCount();
    loadCartItems();

    const cartLink = document.getElementById("cartLink");
    const cartSidebar = document.getElementById("cartSidebar");
    const closeCart = document.getElementById("close-card");
    const cartBackdrop = document.getElementById("cartBackdrop");

    if (cartLink) {
        cartLink.addEventListener("click", function (e) {
            e.preventDefault();
            cartSidebar?.classList.add("active");
            cartBackdrop?.classList.add("active");
            document.body.classList.add("cart-open");
            loadCartItems();
        });
    }

    if (closeCart) closeCart.addEventListener("click", closeCartSidebar);
    if (cartBackdrop) cartBackdrop.addEventListener("click", closeCartSidebar);

    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape" && cartSidebar?.classList.contains("active")) {
            closeCartSidebar();
        }
    });

    document.querySelectorAll('.add-cart').forEach(button => {
        button.removeEventListener('click', addToCartHandler);
        button.addEventListener('click', addToCartHandler);
    });
});

function addToCartHandler(event) {
    event.preventDefault();
    const button = event.currentTarget;
    const productItemId = button.getAttribute('data-product-item-id');
    const quantity = parseInt(document.getElementById('productQuantity')?.value) || 1;
    const stock = parseInt(button.getAttribute('data-stock')) || 0;

    // // Validate quantity
    // if (isNaN(quantity) || quantity < 1) {
    //     showCartMessage('Please enter a valid quantity.', 'alert-danger');
    //     return;
    // }
    // if (quantity > stock) {
    //     showCartMessage(`Only ${stock} items available in stock.`, 'alert-danger');
    //     return;
    // }

    handleAddToCart(productItemId, null, null, quantity);
}

function closeCartSidebar() {
    document.getElementById("cartSidebar")?.classList.remove("active");
    document.getElementById("cartBackdrop")?.classList.remove("active");
    document.body.classList.remove("cart-open");
}

function handleAddToCart(productItemId, size = null, color = null, quantity = 1) {
    const id = parseInt(productItemId, 10);
    if (!id || isNaN(id)) {
        showCartMessage("Invalid product selection.", 'alert-danger');
        return;
    }
    const button = document.querySelector(`[data-product-item-id="${id}"]`);
    const title = button?.getAttribute('data-title') || 'Untitled';
    const price = parseFloat(button?.getAttribute('data-price')) || 0.00;
    const imgSrc = button?.getAttribute('data-img') || '';
    const stock = parseInt(button?.getAttribute('data-stock')) || 0;

    // Use provided size/color if available, otherwise fall back to DOM
    const finalSize = size || document.querySelector('input[name="storage"]:checked')?.value;
    const finalColor = color || document.querySelector('input[name="color"]:checked')?.value;

    if (!finalSize || !finalColor) {
        showCartMessage("Please select both size and color.", 'alert-danger');
        return;
    }

    // if (quantity > stock) {
    //     showCartMessage(`Only ${stock} items available in stock.`, 'alert-danger');
    //     return;
    // }

    if (window.isAuthenticated) {
        $.ajax({
            url: "/store-cart",
            type: "POST",
            data: {
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                product_item_id: id,
                quantity: quantity,
                size: finalSize,
                color: finalColor
            },
            success: function (response) {
                if (response.success) {
                    addOrUpdateLocalCart(id, title, price, imgSrc, finalSize, finalColor, quantity);
                    updateCartCount();
                    loadCartFromLocalStorage();
                    showCartMessage(`(x${quantity}) added to cart!`, 'alert-success');
                } else {
                    showCartMessage("Failed: " + response.message, 'alert-danger');
                }
            },
            error: function (xhr) {
                showCartMessage("Error: " + (xhr.responseJSON?.message || 'Unknown error'), 'alert-danger');
            }
        });
    } else {
        addOrUpdateLocalCart(id, title, price, imgSrc, finalSize, finalColor, quantity);
        updateCartCountLocal();
        loadCartFromLocalStorage();
        showCartMessage(`${title} (x${quantity}) added to cart!`, 'alert-success');
    }
}

function addOrUpdateLocalCart(id, title, price, imgSrc, size, color, quantity) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    const existing = cart.find(item => item.id === id && item.size === size && item.color === color);

    if (existing) {
        existing.quantity += quantity; // Increment quantity for existing item
    } else {
        cart.push({ id, title, price, imgSrc, size, color, quantity });
    }

    localStorage.setItem('cart', JSON.stringify(cart));
}

function loadCartItems() {
    loadCartFromLocalStorage();
}

function updateCartCount() {
    updateCartCountLocal();
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
    const cartContainer = document.querySelector('.cart-content');
    const totalEl = document.querySelector('.total-price');

    cartContainer.innerHTML = '';
    let total = 0;

    if (cart.length === 0) {
        const emptyCartHtml = `
        <div class="empty-cart-container">
            <div class="empty-cart-icon">
                <i class="fas fa-shopping-bag"></i>
                <div class="empty-cart-emoji">😊</div>
            </div>
            <div class="empty-cart-content">
                <h4 class="empty-cart-title">Your cart is waiting for you!</h4>
                <p class="empty-cart-subtitle">Let's find something amazing together</p>
                <div class="empty-cart-actions">
                    ${!isAuthenticated ? `
                        <a href="/login" class="empty-cart-btn signin-btn">
                            <i class="fas fa-user me-2"></i>Sign In
                        </a>
                    ` : ''}
                    <a href="/product_category/16" class="empty-cart-btn shop-btn">
                        <i class="fas fa-shopping-bag me-2"></i>Start Shopping
                    </a>
                </div>
            </div>
        </div>
        `;
        cartContainer.insertAdjacentHTML('beforeend', emptyCartHtml);
        totalEl.textContent = '0.00 $';
        
        const checkoutForm = document.getElementById('checkoutRedirectForm');
        if (checkoutForm) {
            checkoutForm.style.display = 'none';
        }
        return;
    }

    const checkoutForm = document.getElementById('checkoutRedirectForm');
    if (checkoutForm) {
        checkoutForm.style.display = 'block';
    }

    cart.forEach((item, index) => {
        const subtotal = item.price * item.quantity;
        total += subtotal;

        const itemHtml = `
        <div class="cart-box d-flex align-items-start mb-3">
            <img src="${item.imgSrc}" class="cart-img me-2" style="width: 60px; height: 60px; object-fit: cover;">
            <div class="detail-box flex-grow-1">
                <div class="cart-product-title fw-bold">${item.title}</div>
                <div class="text-muted">Size: ${item.size}</div>
                <div class="text-muted d-flex align-items-center gap-2">
                    Color:
                    <span style="width: 16px; height: 16px; border-radius: 50%; display: inline-block; background-color: ${item.color}; border: 1px solid #ccc;"></span>
                </div>
                <div class="cart-price">${item.price} × ${item.quantity} = <strong>${subtotal.toFixed(2)}</strong></div>
                <div class="text-danger">Qty: ${item.quantity}</div>
            </div>
            <i class="fa-solid fa-trash-can cart-remove text-danger" onclick="removeItemFromLocal(${index})" style="cursor: pointer;"></i>
        </div>
        `;
        cartContainer.insertAdjacentHTML('beforeend', itemHtml);
    });

    totalEl.textContent = total.toFixed(2) + ' $';
}

function removeItemFromLocal(index) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    cart.splice(index, 1);
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCountLocal();
    loadCartFromLocalStorage();
}

function submitCheckoutForm() {
    const cartRaw = localStorage.getItem('cart');
    const userId = document.getElementById('checkoutUserId').value || 'Guest';

    if (!cartRaw) {
        showCartMessage(`User ID: ${userId}\nCart is empty or not found.`, 'alert-danger');
        return;
    }

    let cart;
    try {
        cart = JSON.parse(cartRaw);
    } catch(e) {
        showCartMessage(`User ID: ${userId}\nCart data corrupted.`, 'alert-danger');
        return;
    }

    if (!cart.length) {
        showCartMessage(`User ID: ${userId}\nCart is empty.`, 'alert-danger');
        return;
    }
    document.getElementById('checkoutCartData').value = JSON.stringify(cart);
    document.getElementById('checkoutRedirectForm').submit();
}

function showCartMessage(message, type = 'success') {
    // Create message container if it doesn't exist
    let messageBox = document.getElementById('cartMessageBox');
    if (!messageBox) {
        messageBox = document.createElement('div');
        messageBox.id = 'cartMessageBox';
        messageBox.style.position = 'fixed';
        messageBox.style.top = '20px';
        messageBox.style.right = '20px';
        messageBox.style.zIndex = '9999';
        messageBox.style.maxWidth = '300px';
        document.body.appendChild(messageBox);
    }

    // Create the toast
    const toast = document.createElement('div');
    toast.textContent = message;
    toast.style.padding = '12px 16px';
    toast.style.marginBottom = '10px';
    toast.style.borderRadius = '8px';
    toast.style.color = '#fff';
    toast.style.fontSize = '14px';
    toast.style.boxShadow = '0 4px 6px rgba(0,0,0,0.1)';
    toast.style.opacity = '0';
    toast.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
    
    // Different colors for success / error
    if (type === 'success') {
        toast.style.backgroundColor = '#28a745'; // green
    } else if (type === 'error') {
        toast.style.backgroundColor = '#dc3545'; // red
    } else {
        toast.style.backgroundColor = '#28a745'; // gray
    }

    messageBox.appendChild(toast);

    // Animate in
    setTimeout(() => {
        toast.style.opacity = '1';
        toast.style.transform = 'translateY(0)';
    }, 50);

    // Auto remove after 3 seconds
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-20px)';
        setTimeout(() => {
            toast.remove();
        }, 500);
    }, 3000);
}
