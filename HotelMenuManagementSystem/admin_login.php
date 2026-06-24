<?php
include 'config.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Hotel Menu Management System</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="bg-shapes">
        <div class="shape shape-3"></div>
        <div class="shape shape-4"></div>
    </div>
    
    <div class="login-container admin-login">
        <div class="login-header">
            <a href="index.php" class="back-home">
                <i class="fas fa-arrow-left"></i> Back to Home
            </a>
            <div class="logo">
                <i class="fas fa-concierge-bell"></i>
                <span>HMMS Admin</span>
            </div>
        </div>

         <div class="login-card">
            <div class="login-icon admin-icon">
                <i class="fas fa-user-shield"></i>
                <div class="secure-badge">
                    <i class="fas fa-shield-alt"></i> Secure Login
                </div>
            </div>

             <h2>Administrator Login</h2>
            <p class="login-subtitle">Access the management dashboard</p>
            
            <form id="adminLoginForm" class="login-form" action="backend/admin_auth.php" method="post">
                <div class="form-group">
                    <label for="adminUsername">
                        <i class="fas fa-user-tie"></i> Admin ID
                    </label>
                    <input type="text" id="adminUsername" name="adminUsername" required 
                           placeholder="Enter admin ID">
                </div>
                
                <div class="form-group">
                    <label for="adminPassword">
                        <i class="fas fa-key"></i> Password
                    </label>
                    <input type="password" id="adminPassword" name="adminPassword" required 
                           placeholder="Enter admin password">
                    <button type="button" class="toggle-password" id="toggleAdminPassword">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>

                <div class="form-options">
                    <label class="checkbox">
                        <input type="checkbox" name="remember">
                        <span>Keep me logged in</span>
                    </label>
                    <a href="#" class="forgot-password">Reset Credentials</a>
                </div>
                
                <button type="submit" class="submit-btn admin-submit">
                    <i class="fas fa-lock"></i> Secure Login
                </button>
                
                <div class="admin-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p>For authorized personnel only. Unauthorized access is prohibited.</p>
                </div>
            </form>
        </div>
        
        <div class="admin-features">
            <h3>Admin Panel Features</h3>
            <div class="features-grid">
                <div class="admin-feature">
                    <i class="fas fa-edit"></i>
                    <h4>Menu Management</h4>
                    <p>Add, edit, or remove menu items</p>
                </div>
                <div class="admin-feature">
                    <i class="fas fa-chart-line"></i>
                    <h4>Analytics</h4>
                    <p>View sales and customer data</p>
                </div>
                <div class="admin-feature">
                    <i class="fas fa-users-cog"></i>
                    <h4>User Management</h4>
                    <p>Manage user accounts and permissions</p>
                </div>
                <div class="admin-feature">
                    <i class="fas fa-cogs"></i>
                    <h4>System Settings</h4>
                    <p>Configure system preferences</p>
                </div>
            </div>
        </div>
         <footer class="footer">
            <p>Hotel Menu Management System &copy; 2026 | Streamlining Culinary Operations</p>
        </footer>
    </div>

    <script src="js/script.js"></script>
</body>
</html>
