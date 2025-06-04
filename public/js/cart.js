let cartLink = document.querySelector('#cart-link');
let cart = document.querySelector('.cart');
let closecard = document.querySelector('#close-card');

cartLink.onclick = (e) => {
    e.preventDefault();
    cart.classList.add('active');
};

closecard.onclick = () => {
    cart.classList.remove('active');
};


if (document.readyState == 'loading') {
    document.addEventListener('DOMContentLoaded', ready);
} else {
    ready();
}

function ready() {
    loadCartFromLocalStorage();

    var removeCartItemButtons = document.getElementsByClassName('cart-remove');
    for (var i = 0; i < removeCartItemButtons.length; i++) {
        var button = removeCartItemButtons[i];
        button.addEventListener('click', removeCartItem);
    }

    var quantityInputs = document.getElementsByClassName('cart-quantity');
    for (var i = 0; i < quantityInputs.length; i++) {
        var input = quantityInputs[i];
        input.addEventListener('change', quantityChange);
    }

    // Checkout button listener
    document.getElementsByClassName('btn-buy')[0].addEventListener('click', checkoutClicked);

    // Add to cart button listener
    var addcart = document.getElementsByClassName('add-cart');
    for (let i = 0; i < addcart.length; i++) {
        addcart[i].addEventListener('click', addCartClicked);
    }
}

// Load cart from localStorage and rebuild UI
// function loadCartFromLocalStorage() {
//     const cartDataJSON = localStorage.getItem('cart');
//     if (!cartDataJSON) return;

//     const cartData = JSON.parse(cartDataJSON);
//     const cartItemContainer = document.querySelector('.cart-content');

//     cartData.forEach(item => {
//         // Check if already added to avoid duplicates (if page reloads)
//         let alreadyAdded = false;
//         const existingTitles = cartItemContainer.getElementsByClassName('cart-product-title');
//         for (let i = 0; i < existingTitles.length; i++) {
//             if (existingTitles[i].innerText === item.title) {
//                 alreadyAdded = true;
//                 break;
//             }
//         }
//         if (!alreadyAdded) {
//             addProductToCart(item.title, item.price, item.imgSrc, item.quantity);
//         }
//     });
//     updateTotal();
//     updateCartCount();
// }

function loadCartFromLocalStorage() {
    const cartDataJSON = localStorage.getItem('cart');
    if (!cartDataJSON) return;

    const cartData = JSON.parse(cartDataJSON);

    cartData.forEach(item => {
        // Just add everything from localStorage, even duplicates
        addProductToCart(item.title, item.price, item.imgSrc, item.quantity);
    });

    updateTotal();
    updateCartCount();
}


// Add product to cart, with optional quantity (default 1)
function addProductToCart(title, price, productImgSrc, quantity = 1) {
    var cartItem = document.getElementsByClassName('cart-content')[0];
    var cartItemNames = cartItem.getElementsByClassName('cart-product-title');

    // for (var i = 0; i < cartItemNames.length; i++) {
    //     if (cartItemNames[i].innerText === title) {
    //         alert('This item is already added to the cart');
    //         return;
    //     }
    // }

    var cartbox = document.createElement('div');
    cartbox.classList.add('cart-box');
    var cartboxContent = `
        <img src="${productImgSrc}" alt="" class="img-fluid cart-img">
        <div class="cart-detial">
            <div class="cart-product-title">${title}</div>
            <div class="cart-price">${price}</div>
            <input type="number" value="${quantity}" min="1" max="10" class="cart-quantity text-center">
        </div>
        <i class="fa-solid fa-trash cart-remove"></i>`;

    cartbox.innerHTML = cartboxContent;
    cartItem.append(cartbox);

    cartbox.querySelector('.cart-remove').addEventListener('click', removeCartItem);
    cartbox.querySelector('.cart-quantity').addEventListener('change', quantityChange);

    updateTotal();
    updateCartCount();
    saveCartToLocalStorage();
}

// Save current cart data to localStorage
function saveCartToLocalStorage() {
    const cartItemContainer = document.querySelector('.cart-content');
    const cartBoxes = cartItemContainer.querySelectorAll('.cart-box');
    const cartData = [];

    cartBoxes.forEach(box => {
        const title = box.querySelector('.cart-product-title').innerText;
        const price = box.querySelector('.cart-price').innerText;
        const quantity = box.querySelector('.cart-quantity').value;
        const imgSrc = box.querySelector('img.cart-img').src;

        cartData.push({ title, price, quantity, imgSrc });
    });

    localStorage.setItem('cart', JSON.stringify(cartData));
}

// Handle quantity input changes
function quantityChange(event) {
    var input = event.target;
    if (isNaN(input.value) || input.value <= 0) {
        input.value = 1;
    }
    updateTotal();
    updateCartCount();
    saveCartToLocalStorage();
}

// Remove item from cart UI & storage
function removeCartItem(event) {
    var buttonClicked = event.target;
    buttonClicked.parentElement.remove();
    updateTotal();
    updateCartCount();
    saveCartToLocalStorage();
}

// Update total price
function updateTotal() {
    var cartItemContainer = document.getElementsByClassName('cart-content')[0];
    var cartBoxes = cartItemContainer.getElementsByClassName('cart-box');
    var total = 0;

    for (var i = 0; i < cartBoxes.length; i++) {
        var cartBox = cartBoxes[i];
        var priceElement = cartBox.getElementsByClassName('cart-price')[0];
        var quantityElement = cartBox.getElementsByClassName('cart-quantity')[0];

        var price = parseFloat(priceElement.innerText.replace('$', ''));
        var quantity = quantityElement.value;

        total += (price * quantity);
    }

    document.getElementsByClassName('total-price')[0].innerText = '$' + total.toFixed(2);
}

// Update the cart count badge
function updateCartCount() {
    const countLabel = document.getElementById("count_cart");
    const cartItemContainer = document.querySelector('.cart-content');
    const cartBoxes = cartItemContainer.querySelectorAll('.cart-box');
    countLabel.textContent = cartBoxes.length;
}

// When the "Add to Cart" button is clicked on a product
function addCartClicked(event) {
    event.preventDefault();

    var button = event.target;
    var shopproduct = button.closest('.product-card');
    var title = shopproduct.querySelector('.product-title').innerText;
    var price = shopproduct.querySelector('.card-price').innerText;
    var productImgElement = shopproduct.querySelector('.product-img');

    if (productImgElement) {
        var productImgSrc = productImgElement.src;
        addProductToCart(title, price, productImgSrc);
        updateTotal();

        // Optionally remove from wishlist (if you have this function)
        if (typeof removeProductFromWishlistByName === "function") {
            removeProductFromWishlistByName(title);
        }
    } else {
        console.error("Image element not found.");
    }
}

// Checkout button handler
// function checkoutClicked() {
//     // Redirect to checkout page
//     window.location.href = 'checkout';

//     // Clear cart UI
//     var cartItems = document.getElementsByClassName('cart-content')[0];
//     while (cartItems.hasChildNodes()) {
//         cartItems.removeChild(cartItems.firstChild);
//     }
//     updateTotal();
//     updateCartCount();

//     // Clear saved cart in localStorage
//     localStorage.removeItem('cart');
// }
function checkoutClicked() {
    // Just navigate to checkout page, do NOT clear the cart here
    window.location.href = 'checkout';
}

// Make addProductToCart globally accessible if needed
window.addProductToCart = addProductToCart;
