// admin.js - Admin side JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // ===== ADMIN PASSWORD TOGGLE =====
    const toggleAdminPassword = document.getElementById('toggleAdminPassword');
    
    if (toggleAdminPassword) {
        toggleAdminPassword.addEventListener('click', function() {
            const passwordInput = document.getElementById('adminPassword');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            const icon = this.querySelector('i');
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    }
    
    // ===== ADMIN LOGIN FORM =====
    const adminLoginForm = document.getElementById('adminLoginForm');
    if (adminLoginForm) {
        adminLoginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const adminUsername = document.getElementById('adminUsername').value.trim();
            const adminPassword = document.getElementById('adminPassword').value.trim();
            
            if (!adminUsername || !adminPassword) {
                showAlert('Please fill in all fields', 'error');
                return;
            }
            
            // Simulate admin login
            showAlert('Verifying admin credentials...', 'success');
            
            setTimeout(() => {
                showAlert('Admin authentication successful! Redirecting to dashboard...', 'success');
                // Redirect to admin dashboard
                setTimeout(() => {
                    window.location.href = 'admin_dashboard.php';
                }, 1500);
            }, 1500);
        });
    }
});