let cartIcon = document.querySelector('#cart-icon');
let cart = document.querySelector('.cart');
let closecard = document.querySelector('#close-card');

cartIcon.onclick = () => {
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
    var removeCartItemButtons = document.getElementsByClassName('cart-remove');
    for (var i = 0; i < removeCartItemButtons.length; i++) {
        var button = removeCartItemButtons[i];
        button.addEventListener('click', removeCartItem);
    }

    // Handle quantity change
    var quantityInputs = document.getElementsByClassName('cart-quantity');
    for (var i = 0; i < quantityInputs.length; i++) {
        var input = quantityInputs[i];
        input.addEventListener('change', quantityChange);
    }
}

// Handle quantity changes
function quantityChange(event) {
    var input = event.target;
    if (isNaN(input.value) || input.value <= 0) {
        input.value = 1;
    }
    updateTotal();
}

// Remove item from the cart
function removeCartItem(event) {
    var buttonClicked = event.target;
    buttonClicked.parentElement.remove();
    updateTotal();
}

// Update the total price
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
// Add to cart functionality
var addcart = document.getElementsByClassName('add-cart');
for (var i = 0; i < addcart.length; i++) {
    var button = addcart[i];
    button.addEventListener('click', addCardClicked);
}
// button checkout clicked
document.getElementsByClassName('btn-buy')[0].addEventListener('click', checkoutClicked);

function checkoutClicked() {
    // alert('Thank you for your purchase');
    // Redirect to the checkout page
    window.location.href = 'checkout';

    // Optionally, clear the cart items and update the total if needed
    var cartItems = document.getElementsByClassName('cart-content')[0];
    while (cartItems.hasChildNodes()) {
        cartItems.removeChild(cartItems.firstChild);
    }
    updateTotal();
}
// function addCardClicked(event) {
//     var button = event.target;
//     var shopproduct = button.closest('.product-card');
//     var title = shopproduct.getElementsByClassName('product-title')[0].innerText;
//     var price = shopproduct.getElementsByClassName('card-price')[0].innerText;
//     var productImgElement = shopproduct.querySelector('.product-img');

//     if (productImgElement) {
//         var productImgSrc = productImgElement.src;
//         addProductToCart(title, price, productImgSrc);
//         updateTotal();
//     } else {
//         console.error("Image element not found.");
//     }
// }
function addCardClicked(event) {
    event.preventDefault(); // Prevents default behavior (like scrolling)

    var button = event.target;
    var shopproduct = button.closest('.product-card');
    var title = shopproduct.getElementsByClassName('product-title')[0].innerText;
    var price = shopproduct.getElementsByClassName('card-price')[0].innerText;
    var productImgElement = shopproduct.querySelector('.product-img');

    if (productImgElement) {
        var productImgSrc = productImgElement.src;
        addProductToCart(title, price, productImgSrc);
        updateTotal();
    } else {
        console.error("Image element not found.");
    }
}

// cart count
    const increase = document.getElementsByClassName('add-cart');
    const countLabel = document.getElementById("count_cart");
    let count = 0;
    for (let i = 0; i < increase.length; i++) {
        increase[i].addEventListener('click', () => {
            count += 1;
            countLabel.innerHTML = count;
        });
};
function addProductToCart(title, price, productImgSrc) {
    var cartItem = document.getElementsByClassName('cart-content')[0];
    var cartItemNames = cartItem.getElementsByClassName('cart-product-title');

    for (var i = 0; i < cartItemNames.length; i++) {
        if (cartItemNames[i].innerText == title) {
            alert('This item is already added to the cart');
            return;
        }
    }
    var cartbox = document.createElement('div');
    cartbox.classList.add('cart-box');
    var cartboxContent = `
        <img src="${productImgSrc}" alt="" class="img-fluid cart-img">
        <div class="cart-detial">
            <div class="cart-product-title">${title}</div>
            <div class="cart-price">${price}</div>
            <input type="number" value="1" min="1" max="10" class="cart-quantity text-center">
        </div>
        <i class="fa-solid fa-trash cart-remove"></i>`;

    cartbox.innerHTML = cartboxContent;
    cartItem.append(cartbox);
    cartbox.getElementsByClassName('cart-remove')[0].addEventListener('click', removeCartItem);
    cartbox.getElementsByClassName('cart-quantity')[0].addEventListener('change', quantityChange);
}

//scroll animation
document.addEventListener("DOMContentLoaded", function() {
    const elements = document.querySelectorAll(".scroll-animate");

    function scrollHandler() {
        elements.forEach((el) => {
            const position = el.getBoundingClientRect().top;
            const windowHeight = window.innerHeight;

            if (position < windowHeight - 50) {
                el.classList.add("active");
            }
        });
    }

    window.addEventListener("scroll", scrollHandler);
    scrollHandler(); // Run initially
});


