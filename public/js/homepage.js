
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



