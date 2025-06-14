
document.addEventListener('DOMContentLoaded', function () {
    updateCartCountLocal();
    loadCartFromLocalStorage();

    document.querySelectorAll('.add-cart').forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault();

            const productCard = this.closest('.product-card');

            if (productCard) {
                const title = productCard.querySelector('.product-title').innerText;
                const price = productCard.querySelector('.card-price').innerText;
                const imgSrc = productCard.querySelector('.product-img').src;

                addProductToCart(title, price, imgSrc);
            } else {
                const title = this.getAttribute('data-title');
                const price = this.getAttribute('data-price');
                const imgSrc = this.getAttribute('data-img');

                addProductToCart(title, price, imgSrc);
            }

            alert("Added to cart.");
        });
    });

    const cartLink = document.getElementById("cartLink");
    const cartSidebar = document.getElementById("cartSidebar");
    const closeCart = document.getElementById("close-card");

    if (cartLink && cartSidebar && closeCart) {
        cartLink.addEventListener("click", function (e) {
            e.preventDefault();
            cartSidebar.classList.add("active");
            loadCartFromLocalStorage();
        });

        closeCart.addEventListener("click", function () {
            cartSidebar.classList.remove("active");
        });
    }
});


function addProductToCart(title, price, imgSrc) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    const existing = cart.find(item => item.title === title);

    if (existing) {
        existing.quantity += 0;
    } else {
        cart.push({ title, price, imgSrc, quantity: 1 });
    }

    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCountLocal();
    loadCartFromLocalStorage();
}


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
                    <span class="cart-quantity" style="color:red;">qty: ${item.quantity}</span>
                </div>
            </div>
        `;
        cartContent.insertAdjacentHTML('beforeend', itemHtml);
        // total += parseFloat(item.price.replace(/[^\d.]/g, '')) * item.quantity;
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
    updateCartBadge(cart.reduce((sum, item) => sum + item.quantity, 0));
}

function updateCartBadge(count) {
    const badge = document.getElementById('count_cart');
    if (badge) {
        badge.textContent = count;
        badge.style.display = count > 0 ? 'inline-block' : 'none';
    }
}

