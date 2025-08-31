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

    // Simple Product Filtering and Sorting Functionality
    const productsContainer = document.querySelector('.row.g-4');
    
    if (productsContainer) {
        // Get all product cards
        const productCards = productsContainer.querySelectorAll('.col-md-3');
        const originalProducts = Array.from(productCards);
        
        // Store current filter states
        let currentBrand = '';
        let currentSort = 'default';
        let currentSearch = '';

        function applyFilters() {
            let filteredProducts = originalProducts.filter(card => {
                // Get product data
                const productName = card.querySelector('.product-title a').textContent.toLowerCase();
                const brandId = card.getAttribute('data-brand') || '';

                // Apply brand filter (if brand filter exists)
                const brandMatch = !currentBrand || brandId === currentBrand;
                
                // Apply search filter
                const searchMatch = !currentSearch || productName.includes(currentSearch.toLowerCase());

                return brandMatch && searchMatch;
            });

            // Sort products
            filteredProducts = sortProducts(filteredProducts, currentSort);

            // Update display
            displayProducts(filteredProducts);
        }

        function sortProducts(products, sortOption) {
            return products.sort((a, b) => {
                const nameA = a.querySelector('.product-title a').textContent;
                const nameB = b.querySelector('.product-title a').textContent;
                const priceA = parseFloat(a.querySelector('.card-price').textContent.replace(/[^0-9.]/g, '')) || 0;
                const priceB = parseFloat(b.querySelector('.card-price').textContent.replace(/[^0-9.]/g, '')) || 0;

                switch(sortOption) {
                    case 'price_low_high':
                        return priceA - priceB;
                    case 'price_high_low':
                        return priceB - priceA;
                    case 'name_a_z':
                        return nameA.localeCompare(nameB);
                    case 'name_z_a':
                        return nameB.localeCompare(nameA);
                    default:
                        return 0; // Keep original order
                }
            });
        }

        function displayProducts(products) {
            // Clear current display
            productsContainer.innerHTML = '';

            if (products.length === 0) {
                productsContainer.innerHTML = `
                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-center" style="height:400px;">
                            <p class="fs-4 text-muted m-0">No products found.</p>
                        </div>
                    </div>
                `;
            } else {
                // Add filtered products
                products.forEach(card => {
                    productsContainer.appendChild(card.cloneNode(true));
                });
            }

            // Update product count
            updateProductCount(products.length);
        }

        function updateProductCount(count) {
            const totalCount = originalProducts.length;
            const countElement = document.getElementById('productCount');
            
            if (countElement) {
                countElement.textContent = `${count} products`;
            }
        }

        // Brand filter event listeners
        document.querySelectorAll('[data-brand]').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Remove active class from all brand items
                document.querySelectorAll('[data-brand]').forEach(i => i.classList.remove('active'));
                
                // Add active class to clicked item
                this.classList.add('active');
                
                // Update button text
                const brandButton = document.querySelector('.btn-outline-primary');
                brandButton.textContent = this.textContent;
                
                // Update current brand
                currentBrand = this.getAttribute('data-brand');
                
                // Apply filters
                applyFilters();
            });
        });

        // Sort filter event listeners
        document.querySelectorAll('[data-sort]').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Remove active class from all sort items
                document.querySelectorAll('[data-sort]').forEach(i => i.classList.remove('active'));
                
                // Add active class to clicked item
                this.classList.add('active');
                
                // Update button text
                const sortButton = document.querySelector('.btn-outline-secondary');
                if (sortButton) {
                    sortButton.textContent = 'Sort: ' + this.textContent;
                }
                
                // Update current sort
                currentSort = this.getAttribute('data-sort');
                
                // Apply filters
                applyFilters();
            });
        });

        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const searchForm = document.getElementById('searchForm');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                currentSearch = this.value;
                applyFilters();
            });
        }

        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                currentSearch = searchInput.value;
                applyFilters();
            });
        }

        // Enter key search
        if (searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    currentSearch = this.value;
                    applyFilters();
                }
            });
        }

        // Initialize with all products
        updateProductCount(originalProducts.length);
    }
});

