// document.addEventListener('DOMContentLoaded', function() {
//     const sidebar = document.getElementById('sidebar');
//     const toggleBtn = document.querySelector('.toggle-btn');
//     const menuLinks = document.querySelectorAll('.sidebar-link');
    
//     // 1. Toggle sidebar when grid icon is clicked (collapse/expand)
//     toggleBtn.addEventListener('click', function() {
//         sidebar.classList.toggle('expand');
//     });
    
//     // 2. Expand sidebar when any menu item is clicked
//     menuLinks.forEach(link => {
//         link.addEventListener('click', function() {
//             // Only expand if sidebar is currently collapsed
//             if (!sidebar.classList.contains('expand')) {
//                 sidebar.classList.add('expand');
//             }
//         });
//     });
// });

document.addEventListener('DOMContentLoaded', function () {
    const links = {
        edit: document.querySelector('.list-group-item[href="#edit-profile"]'),
        password: document.querySelector('.list-group-item[href="#change-password"]'),
        address: document.querySelector('.list-group-item[href="#address"]'),
    };

    const sections = {
        edit: document.getElementById('editProfileContent'),
        password: document.getElementById('changePasswordContent'),
        address: document.getElementById('addressContent'),
    };

    function showSection(active) {
        for (const key in sections) {
            sections[key].style.display = (key === active) ? 'block' : 'none';
            links[key].classList.toggle('active', key === active);
        }
    }

    links.edit.addEventListener('click', (e) => { e.preventDefault(); showSection('edit'); });
    links.password.addEventListener('click', (e) => { e.preventDefault(); showSection('password'); });
    links.address.addEventListener('click', (e) => { e.preventDefault(); showSection('address'); });
});

document.querySelectorAll("form").forEach(function(form) {
    form.addEventListener("submit", function(e) {
        e.preventDefault();

        const successBox = document.getElementById("successMessage");
        const errorBox = document.getElementById("errorMessage");

        // Hide both alerts first
        successBox.classList.add("d-none");
        errorBox.classList.add("d-none");

        // Check if the form is valid
        if (!form.checkValidity()) {
            // Show error
            errorBox.classList.remove("d-none");
            return;
        }

        // If valid, show success
        let message = "Changes saved successfully.";
        if (form.closest("#changePasswordContent")) {
            message = "Password updated successfully.";
        } else if (form.closest("#addressContent")) {
            message = "Address saved successfully.";
        } else if (form.closest("#editProfileContent")) {
            message = "Profile updated successfully.";
        }

        successBox.innerHTML = `
            <strong>Success!</strong> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        successBox.classList.remove("d-none");

        // Optionally reset the form
        // form.reset();

        // Auto-hide after 3 seconds
        setTimeout(() => {
            successBox.classList.add("d-none");
        }, 3000);
    });
});



