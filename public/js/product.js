document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('productSearchInput');
    const productList = document.getElementById('productList');
    
    // Check if elements exist before adding event listener
    if (input && productList) {
        const products = productList.getElementsByTagName('li');

        input.addEventListener('input', function() {
            const searchTerm = input.value.toLowerCase();
            for (let product of products) {
                const productName = product.textContent.toLowerCase();
                if (productName.includes(searchTerm)) {
                    product.style.display = '';
                } else {
                    product.style.display = 'none';
                }
            }
        });
    }
});
