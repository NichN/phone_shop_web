

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

    // Single delegated listener for quantity controls and modal cart button
    document.addEventListener('click', function (e) {
        const qtyInput = document.getElementById('modalProductQuantity');

        if (e.target.id === 'increaseQty' && qtyInput) {
            e.preventDefault();
            qtyInput.value = Math.min(parseInt(qtyInput.value ), 999);
        }

        if (e.target.id === 'decreaseQty' && qtyInput) {
            e.preventDefault();
            qtyInput.value = Math.max(parseInt(qtyInput.value), 1);
        }

        if (e.target.classList.contains('add-cart-modal')) {
            e.preventDefault();
            const quantity = parseInt(qtyInput?.value) || 1;
            const productId = e.target.dataset.productItemId;
            const selectedColor = document.querySelector('input[name="modalColor"]:checked')?.value;
            const selectedSize = document.querySelector('input[name="modalStorage"]:checked')?.value;

            handleAddToCart(productId, selectedSize, selectedColor, quantity);
        }

        // Regular (non-modal) add-cart buttons
        if (e.target.classList.contains('add-cart')) {
            e.preventDefault();
            const productItemId = e.target.getAttribute('data-product-item-id');
            if (!productItemId) {
                alert('Please select a valid product variant.');
                return;
            }
            handleAddToCart(productItemId);
        }
    });
});

function closeCartSidebar() {
    document.getElementById("cartSidebar")?.classList.remove("active");
    document.getElementById("cartBackdrop")?.classList.remove("active");
    document.body.classList.remove("cart-open");
}

function handleAddToCart(productItemId, size = null, color = null, quantity = 1) {
    const id = parseInt(productItemId, 10);
    if (!id || isNaN(id)) {
        showCartMessage("Invalid product selection.", 'error');
        return;
    }

    const button = document.querySelector(`[data-product-item-id="${id}"]`);
    if (button && button.disabled) {
        alert("This combination is not available.");
        return;
    }

    const title = button?.getAttribute('data-title') || 'Untitled';
    const price = parseFloat(button?.getAttribute('data-price')) || 0;
    const imgSrc = button?.getAttribute('data-img') || '';
    const stock = parseInt(button?.getAttribute('data-stock')) || 0;

    const finalSize = size || document.querySelector('input[name="storage"]:checked')?.value;
    const finalColor = color || document.querySelector('input[name="color"]:checked')?.value;

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
                    updateCartCountLocal();
                    loadCartFromLocalStorage();
                    if (typeof showCartSuccessNotification === 'function') showCartSuccessNotification();
                } else {
                    showCartMessage("Failed: " + response.message, 'error');
                }
            },
            error: function (xhr) {
                showCartMessage("Error: " + (xhr.responseJSON?.message || 'Unknown error'), 'error');
            }
        });
    } else {
        addOrUpdateLocalCart(id, title, price, imgSrc, finalSize, finalColor, quantity);
        updateCartCountLocal();
        loadCartFromLocalStorage();
        if (typeof showCartSuccessNotification === 'function') showCartSuccessNotification();
    }
}

function addOrUpdateLocalCart(id, title, price, imgSrc, size, color, quantity) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    const existing = cart.find(item => item.id === id && item.size === size && item.color === color);

    if (existing) existing.quantity += quantity;
    else cart.push({ id, title, price, imgSrc, size, color, quantity });

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
                    ${!isAuthenticated ? `<a href="/login" class="empty-cart-btn signin-btn"><i class="fas fa-user me-2"></i>Sign In</a>` : ''}
                    <a href="/homepage" class="empty-cart-btn shop-btn"><i class="fas fa-shopping-bag me-2"></i>Start Shopping</a>
                </div>
            </div>
        </div>`;
        cartContainer.insertAdjacentHTML('beforeend', emptyCartHtml);
        totalEl.textContent = '0.00 $';
        const checkoutForm = document.getElementById('checkoutRedirectForm');
        if (checkoutForm) checkoutForm.style.display = 'none';
        return;
    }

    const checkoutForm = document.getElementById('checkoutRedirectForm');
    if (checkoutForm) checkoutForm.style.display = 'block';

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

        <div class="d-flex align-items-center justify-content-between mt-2">
            <div class="choose-quantity">
                <h5 class="mb-2 fs-6 fw-bold">Quantity</h5>
                <div class="d-flex gap-3 align-items-center">
                    <button type="button" class="btn btn-outline-secondary btn-sm changeQtyBtn" data-index="${index}" data-action="decrease">-</button>
                    <input type="number" class="form-control form-control-sm text-center cartQtyInput" 
                        value="${item.quantity}" min="1" max="999" data-index="${index}" style="width: 60px;">
                    <button type="button" class="btn btn-outline-secondary btn-sm changeQtyBtn" data-index="${index}" data-action="increase">+</button>
                </div>
            </div>
        </div>

        <div class="text-muted mt-2 subtotal-line" data-index="${index}">
            ${item.price} × ${item.quantity} = <strong class="subtotal-amount">${(item.price * item.quantity).toFixed(2)} $</strong>
        </div>
    </div>
    <i class="fa-solid fa-trash-can cart-remove text-danger" onclick="removeItemFromLocal(${index})" style="cursor: pointer;"></i>
</div>`;

        cartContainer.insertAdjacentHTML('beforeend', itemHtml);
    });

    totalEl.textContent = total.toFixed(2) + ' $';
}
function updateCartUIItem(index, item) {
    const subtotalEl = document.querySelector(`.subtotal-line[data-index="${index}"] .subtotal-amount`);
    const qtyInput = document.querySelector(`.cartQtyInput[data-index="${index}"]`);

    if (subtotalEl) {
        const subtotal = item.price * item.quantity;
        subtotalEl.textContent = `${subtotal.toFixed(2)} $`;
    }

    if (qtyInput) {
        qtyInput.value = item.quantity;
    }
}

function updateCartTotalOnly() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const total = cart.reduce((sum, item) => sum + item.quantity * item.price, 0);
    const totalEl = document.querySelector('.total-price');
    if (totalEl) totalEl.textContent = total.toFixed(2) + ' $';
}

function updateCartItemQty(index, action = null, setTo = null) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    if (!cart[index]) return;

    if (action === 'increase') {
        cart[index].quantity = Math.min(cart[index].quantity + 1, 999);
    } else if (action === 'decrease') {
        cart[index].quantity = Math.max(cart[index].quantity - 1, 1);
    } else if (setTo !== null) {
        cart[index].quantity = Math.max(1, Math.min(setTo, 999));
    }

    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCountLocal();
    updateCartUIItem(index, cart[index]);
    updateCartTotalOnly();
}

document.addEventListener('click', function (e) {
    if (e.target.classList.contains('changeQtyBtn')) {
        const index = parseInt(e.target.dataset.index);
        const action = e.target.dataset.action;
        updateCartItemQty(index, action);
    }
});

document.addEventListener('input', function (e) {
    if (e.target.classList.contains('cartQtyInput')) {
        const index = parseInt(e.target.dataset.index);
        let newQty = parseInt(e.target.value);
        if (isNaN(newQty)) newQty = 1;
        newQty = Math.max(1, Math.min(999, newQty));
        updateCartItemQty(index, null, newQty);
    }
});


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
        showCartMessage(`User ID: ${userId}\nCart is empty or not found.`, 'error');
        return;
    }

    let cart;
    try { cart = JSON.parse(cartRaw); } 
    catch(e) { showCartMessage(`User ID: ${userId}\nCart data corrupted.`, 'error'); return; }

    if (!cart.length) {
        showCartMessage(`User ID: ${userId}\nCart is empty.`, 'error');
        return;
    }

    document.getElementById('checkoutCartData').value = JSON.stringify(cart);
    document.getElementById('checkoutRedirectForm').submit();
}

function showCartMessage(message, type = 'success') {
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
    toast.style.backgroundColor = type === 'success' ? '#28a745' : '#dc3545';

    messageBox.appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = '1';
        toast.style.transform = 'translateY(0)';
    }, 50);

    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-20px)';
        setTimeout(() => { toast.remove(); }, 500);
    }, 3000);
}


