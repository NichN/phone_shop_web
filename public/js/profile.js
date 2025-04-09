// const hamBurger = document.querySelector(".toggle-btn");

// hamBurger.addEventListener("click", function () {
//   document.querySelector("#sidebar").classList.toggle("expand");
// });

document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.querySelector('.toggle-btn');
    const menuLinks = document.querySelectorAll('.sidebar-link');
    
    // 1. Toggle sidebar when grid icon is clicked (collapse/expand)
    toggleBtn.addEventListener('click', function() {
        sidebar.classList.toggle('expand');
    });
    
    // 2. Expand sidebar when any menu item is clicked
    menuLinks.forEach(link => {
        link.addEventListener('click', function() {
            // Only expand if sidebar is currently collapsed
            if (!sidebar.classList.contains('expand')) {
                sidebar.classList.add('expand');
            }
        });
    });
});



