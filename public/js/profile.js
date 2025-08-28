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

    // Function to show a specific section
    function showSection(active) {
        // First hide all sections
        Object.values(sections).forEach(section => {
            if (section) {
                section.style.display = 'none';
            }
        });

        // Remove active class from all links
        Object.values(links).forEach(link => {
            if (link) {
                link.classList.remove('active');
            }
        });

        // Show the active section and highlight its link
        if (sections[active]) {
            sections[active].style.display = 'block';
        }
        if (links[active]) {
            links[active].classList.add('active');
        }
    }

    // Add click event listeners to all links
    Object.entries(links).forEach(([key, link]) => {
        if (link) {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                showSection(key);
            });
        }
    });

    // Show edit profile section by default
    showSection('edit');
});

// Form submission handling
document.querySelectorAll("form").forEach(function(form) {
    form.addEventListener("submit", function(e) {
        const successBox = document.getElementById("successMessage");
        const errorBox = document.getElementById("errorMessage");

        // Hide both alerts first
        if (successBox) successBox.classList.add("d-none");
        if (errorBox) errorBox.classList.add("d-none");

        // Check if the form is valid
        if (!form.checkValidity()) {
            e.preventDefault(); // Only prevent submission if form is invalid
            if (errorBox) errorBox.classList.remove("d-none");
            return;
        }
    });
});

// Handle password change form submission
document.getElementById('passwordChangeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    console.log('Password change form submitted');
    
    // Clear any existing alerts
    clearAlerts();
    
    // Get form values
    const currentPassword = this.querySelector('input[name="current_password"]').value.trim();
    const newPassword = this.querySelector('input[name="new_password"]').value.trim();
    const confirmPassword = this.querySelector('input[name="password_confirmation"]').value.trim();
    
    console.log('Form values:', { currentPassword: !!currentPassword, newPassword: !!newPassword, confirmPassword: !!confirmPassword });
    
    // Validate current password is not empty
    if (!currentPassword) {
        showAlert('danger', 'Please enter your current password.');
        return;
    }
    
    // Validate new password is not empty
    if (!newPassword) {
        showAlert('danger', 'Please enter a new password.');
        return;
    }
    
    // Validate passwords match
    if (newPassword !== confirmPassword) {
        showAlert('danger', 'New password and confirmation password do not match.');
        return;
    }
    
    // Validate password length
    if (newPassword.length < 8) {
        showAlert('danger', 'New password must be at least 8 characters long.');
        return;
    }
    
                // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...';
            
            // Disable all form inputs during submission
            const formInputs = this.querySelectorAll('input');
            formInputs.forEach(input => input.disabled = true);

    // Get form data
    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    formData.append('current_password', currentPassword);
    formData.append('new_password', newPassword);
    formData.append('password_confirmation', confirmPassword);
    
    // Debug log
    console.log('Sending password update request...');
    
    // Send request to update password
    console.log('Sending fetch request to /profile/password');
    console.log('FormData contents:', Array.from(formData.entries()));
    
    fetch('/profile/password', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        
        if (data.success) {
            // Reset form
            this.reset();
            
            // Show success message
            showAlert('success', 'Your password has been successfully updated.');
            
            // Log out user after successful password change
            setTimeout(() => {
                // Create and submit a logout form instead of direct redirect
                const logoutForm = document.createElement('form');
                logoutForm.method = 'POST';
                logoutForm.action = '/logout';
                
                // Add CSRF token
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;
                logoutForm.appendChild(csrfInput);
                
                // Submit the form
                document.body.appendChild(logoutForm);
                logoutForm.submit();
            }, 2000);
        } else {
            // Show specific error message if available
            if (data.message) {
                showAlert('danger', data.message);
            } else if (data.errors) {
                // Handle validation errors
                const errorMessages = Object.values(data.errors).flat();
                showAlert('danger', errorMessages.join('<br>'));
            } else {
                showAlert('danger', 'An error occurred while updating your password.');
            }
            
            // If current password is incorrect, focus on that field
            if (data.message && data.message.includes('current password')) {
                this.querySelector('input[name="current_password"]').focus();
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'Network error occurred. Please check your connection and try again.');
    })
    .finally(() => {
        // Reset button state
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
        
        // Re-enable all form inputs
        const formInputs = this.querySelectorAll('input');
        formInputs.forEach(input => input.disabled = false);
    });
});

// Function to show alert message
function showAlert(type, message) {
    clearAlerts();
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    // Find the profile modal body and insert the alert
    const modalBody = document.querySelector('#profileModal .modal-body');
    if (modalBody) {
        modalBody.insertBefore(alertDiv, modalBody.firstChild);
    }
}

// Function to clear all alerts
function clearAlerts() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => alert.remove());
}

// Password visibility toggle functions
function setupPasswordToggles() {
    // Current password toggle
    document.getElementById('toggleCurrentPassword').addEventListener('click', function() {
        const input = document.getElementById('current_password');
        const icon = this.querySelector('i');
        if (input.type === 'text') {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        } else {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    });

    // New password toggle
    document.getElementById('toggleNewPassword').addEventListener('click', function() {
        const input = document.getElementById('new_password');
        const icon = this.querySelector('i');
        if (input.type === 'text') {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        } else {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    });

    // Confirm password toggle
    document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
        const input = document.getElementById('new_password_confirmation');
        const icon = this.querySelector('i');
        if (input.type === 'text') {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        } else {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    });
}
document.querySelector('a[href="#changePasswordContent"]').addEventListener('click', function() {
    setTimeout(setupPasswordToggles, 100);
});



