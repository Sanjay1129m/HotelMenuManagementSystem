<?php
include 'config.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Menu Management System</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
        <div class="shape shape-4"></div>
    </div>
    
    <div class="floating-menu-items">
        <div class="floating-item"><i class="fas fa-utensils"></i></div>
        <div class="floating-item"><i class="fas fa-coffee"></i></div>
        <div class="floating-item"><i class="fas fa-wine-glass-alt"></i></div>
        <div class="floating-item"><i class="fas fa-hamburger"></i></div>
        <div class="floating-item"><i class="fas fa-pizza-slice"></i></div>
        <div class="floating-item"><i class="fas fa-ice-cream"></i></div>
    </div>
    
    <div class="container">
        <header class="header">
            <div class="logo">
                <i class="fas fa-concierge-bell"></i>
                <span>HMMS</span>
            </div>
            <nav class="nav">
                <a href="#features">Features</a>
                <a href="#about">About</a>
                <a href="#contact">Contact</a>
            </nav>
        </header>
        
        <main class="main-content">
            <div class="hero">
                <h1 class="title">
                    <span class="title-main">Hotel Menu</span>
                    <span class="title-sub">Management System</span>
                </h1>
                <p class="description">
                    Streamline your hotel's menu management with our intuitive platform. 
                    Efficiently manage, update, and organize your culinary offerings.
                </p>
                <div class="role-selector">
                    <p class="role-prompt">Please select your role to continue</p>
                    
                    <div class="buttons">
                        <a href="user/user_login.php" class="btn user">
                            <div class="btn-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="btn-content">
                                <h3>User Login</h3>
                                <p>Browse menus, place orders, view recommendations</p>
                            </div>
                            <div class="btn-arrow">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </a>
                        
                        <a href="admin_login.php" class="btn admin">
                            <div class="btn-icon">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <div class="btn-content">
                                <h3>Admin Login</h3>
                                <p>Manage menus, track orders, analyze performance</p>
                            </div>
                            <div class="btn-arrow">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </a>
                    </div>
                </div>
                
                <div class="stats">
                    <div class="stat-item">
                        <span class="stat-number" data-count="500">0</span>
                        <span class="stat-label">Menu Items</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number" data-count="120">0</span>
                        <span class="stat-label">Hotels</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number" data-count="45">0</span>
                        <span class="stat-label">Categories</span>
                    </div>
                </div>
            </div>
            
            <div class="features" id="features">
                <h2><i class="fas fa-star"></i> Key Features</h2>
                <div class="feature-grid">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-sync-alt"></i>
                        </div>
                        <h3>Real-time Updates</h3>
                        <p>Menu changes instantly reflect across all platforms</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <h3>Analytics Dashboard</h3>
                        <p>Track popular items and customer preferences</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h3>Mobile Friendly</h3>
                        <p>Accessible on all devices and screen sizes</p>
                    </div>
                </div>
            </div>
            
            <div class="about" id="about">
                <h2><i class="fas fa-info-circle"></i> About System</h2>
                <div class="about-content">
                    <div class="about-text">
                        <p>A comprehensive hotel menu management solution that helps hotels:</p>
                        <ul>
                            <li><i class="fas fa-check"></i> Manage menu items efficiently</li>
                            <li><i class="fas fa-check"></i> Process customer orders quickly</li>
                            <li><i class="fas fa-check"></i> Track inventory and sales</li>
                            <li><i class="fas fa-check"></i> Generate reports and analytics</li>
                        </ul>
                    </div>
                    <div class="about-image">
                        <i class="fas fa-laptop-code"></i>
                    </div>
                </div>
            </div>
        </main>
        
        <footer class="footer" id="contact">
            <p>Hotel Menu Management System &copy; 2026</p>
            <div class="footer-contact">
                <p><i class="fas fa-envelope"></i> support@hotelmenu.com</p>
                <p><i class="fas fa-phone"></i> +0123456789</p>
            </div>
        </footer>
    </div>

    <script src="js/script.js"></script>
</body>
</html>