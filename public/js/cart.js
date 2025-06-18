document.addEventListener('DOMContentLoaded', function () {
    if (window.isAuthenticated) {
        updateCartCount();        // Server-side user
        loadCartItems();          // Placeholder
    } else {
        updateCartCountLocal();   // Guest user
        loadCartFromLocalStorage();
    }

    const cartLink = document.getElementById("cartLink");
    const cartSidebar = document.getElementById("cartSidebar");
    const closeCart = document.getElementById("close-card");
    const cartBackdrop = document.getElementById("cartBackdrop");

    function openCart() {
        cartSidebar?.classList.add("active");
        cartBackdrop?.classList.add("active");
        document.body.classList.add("cart-open");

        if (window.isAuthenticated) {
            loadCartItems();
            loadCartFromLocalStorage(); 
        }

    }

    function closeCartSidebar() {
        cartSidebar?.classList.remove("active");
        cartBackdrop?.classList.remove("active");
        document.body.classList.remove("cart-open");
    }

    if (cartLink) {
        cartLink.addEventListener("click", function (e) {
            e.preventDefault();
            openCart();
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
        button.addEventListener('click', function (event) {
            event.preventDefault();

            const title = this.getAttribute('data-title');
            const price = this.getAttribute('data-price');
            const imgSrc = this.getAttribute('data-img');

            const size = document.querySelector('input[name="storage"]:checked')?.value;
            const color = document.querySelector('input[name="color"]:checked')?.value;

            if (!size || !color) {
                alert("Please select both size and color.");
                return;
            }

            if (title && price && imgSrc) {
                addProductToCart(title, price, imgSrc, size, color);
                alert("Added to cart.");
            } else {
                console.error("Missing product info.");
            }
        });
    });
});

function addProductToCart(id, title, price, imgSrc, size, color) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    const existing = cart.find(item =>
        item.id === id &&
        item.size === size &&
        item.color === color
    );

    if (existing) {
        existing.quantity += 1;
    } else {
        cart.push({ id, title, price, imgSrc, size, color, quantity: 1 });
    }

    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCountLocal();
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
                <img src="${item.imgSrc}" class="cart-img me-2" alt="${item.title}" style="width: 60px; height: 60px; object-fit: cover;">
                <div class="detail-box flex-grow-1">
                    <div class="cart-product-title fw-bold">${item.title}</div>
                    <div class="text-muted">Size: ${item.size}</div>
                    <div class="text-muted d-flex align-items-center gap-2">
                    Color:
                    <span style="width: 16px; height: 16px; border-radius: 50%; display: inline-block; background-color: ${item.color}; border: 1px solid #ccc;"></span>
                </div>

                    <div class="cart-price">${item.price}</div>
                    <div class="text-danger">Qty: ${item.quantity}</div>
                </div>
                <i class="fa-solid fa-trash-can cart-remove text-danger" data-id="${index}" onclick="removeItemFromLocal(${index})" style="cursor: pointer;"></i>
            </div>
        `;
        cartContent.insertAdjacentHTML('beforeend', itemHtml);
        total += parseFloat(item.price.replace(/[^\d.]/g, '')) * item.quantity;
    });

    const totalPriceElement = document.querySelector('.total-price') || document.getElementById('totalPrice');
    if (totalPriceElement) {
        totalPriceElement.innerText = total.toFixed(2) + "$";
    }
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

function updateCartCount() {
    const dummyCount = 0;
    updateCartBadge(dummyCount);
}

function loadCartItems() {
    console.log("Fetching server cart...");
}
function changeImage(element) {
    const newSrc = element.getAttribute('src');
    document.getElementById('mainImage').setAttribute('src', newSrc);
    document.querySelectorAll('.thumbnail-img').forEach(img => {
        img.classList.remove('selected-thumbnail');
    });
    element.classList.add('selected-thumbnail');
}

//​សម្រាប់​ move product from card to chec out
document.addEventListener('DOMContentLoaded', function () {
    const checkoutForm = document.getElementById('checkoutForm');

    if (!checkoutForm) return;

    checkoutForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const cart = localStorage.getItem('cart');
        if (!cart || JSON.parse(cart).length === 0) {
            alert("Your cart is empty!");
            return;
        }
        document.getElementById('cartData').value = cart;
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        fetch('/checkout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({
                cart_data: cart
            })
        })
        .then(res => res.text())
        .then(html => {
            document.open();
            document.write(html);
            document.close();
        })
        .catch(error => {
            console.error(error);
            alert('Checkout failed');
        });
    });
});


