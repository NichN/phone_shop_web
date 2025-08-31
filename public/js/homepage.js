
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
    scrollHandler();
});
$(document).ready(function () {
    $('#searchForm').on('submit', function (e) {
        e.preventDefault();
        let query = $('#searchInput').val();
        if (query.trim() === '') {
            $('#resultsContainer').html('');
            return;
        }

        $.ajax({
            url: '/search',
            method: 'GET',
            data: { query: query },
            success: function (products) {
                if (products.length === 0) {
                    $('#resultsContainer').html('<p>No products found.</p>');
                    return;
                }
                let html = '<ul class="list-group">';
                products.forEach(product => {
                    html += `<li class="list-group-item">
                                <strong>${product.name}</strong> - $${product.price}
                             </li>`;
                });
                html += '</ul>';
                $('#resultsContainer').html(html);
            },
            error: function () {
                $('#resultsContainer').html('<div class="alert alert-danger">Search failed. Please try again.</div>');
            }
        });
    });
});

// Brand grid enhancement
document.addEventListener("DOMContentLoaded", function() {
    // Add click tracking for brand cards
    const brandCards = document.querySelectorAll('.brand-card');
    
    brandCards.forEach(card => {
        card.addEventListener('click', function() {
            // Add a subtle click effect
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
        
        // Add hover sound effect (optional)
        card.addEventListener('mouseenter', function() {
            // You can add a subtle sound effect here if desired
            // For now, we'll just add a visual feedback
            this.style.cursor = 'pointer';
        });
    });
    
    // Smooth scroll to brand section when coming from other pages
    if (window.location.hash === '#brands') {
        const brandSection = document.querySelector('.brand-section');
        if (brandSection) {
            brandSection.scrollIntoView({ behavior: 'smooth' });
        }
    }
    
    // Brand toggle functionality
    const toggleBtn = document.getElementById('brand-toggle-btn');
    const hiddenBrands = document.querySelectorAll('.brand-hidden');
    
    if (toggleBtn && hiddenBrands.length > 0) {
        console.log('Toggle button found, hidden brands:', hiddenBrands.length);
        
        toggleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Toggle button clicked');
            
            const isExpanded = this.classList.contains('expanded');
            
            if (!isExpanded) {
                // Show hidden brands
                console.log('Showing hidden brands');
                hiddenBrands.forEach((brand, index) => {
                    setTimeout(() => {
                        brand.classList.add('show');
                        console.log('Added show class to brand', index);
                    }, index * 100);
                });
                
                this.innerHTML = '<i class="fas fa-chevron-up me-1"></i>See Less Brands';
                this.classList.add('expanded');
            } else {
                // Hide brands
                console.log('Hiding brands');
                hiddenBrands.forEach((brand, index) => {
                    setTimeout(() => {
                        brand.classList.remove('show');
                        console.log('Removed show class from brand', index);
                    }, index * 50);
                });
                
                this.innerHTML = '<i class="fas fa-chevron-down me-1"></i>See More Brands <span class="badge bg-secondary ms-1">' + hiddenBrands.length + '</span>';
                this.classList.remove('expanded');
            }
        });
    } else {
        console.log('Toggle button or hidden brands not found');
    }
});