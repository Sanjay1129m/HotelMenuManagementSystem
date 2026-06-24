<?php
include '../config.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: user_login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - HMMS</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
    </div>
    
    <div class="dashboard-container">
        <div class="dashboard-header">
            <div class="logo">
                <i class="fas fa-concierge-bell"></i>
                <span>HMMS Dashboard</span>
            </div>
            <div class="user-info">
                <span>Welcome, <?php echo $_SESSION['user_name']; ?></span>
                <a href="../backend/logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
        
        <div class="dashboard-content">
            <div class="sidebar">
                <a href="user_dashboard.php" class="active">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="menu.php">
                    <i class="fas fa-utensils"></i> View Menu
                </a>
                <a href="cart.php">
                    <i class="fas fa-shopping-cart"></i> My Cart
                </a>
                <a href="order_history.php">
                    <i class="fas fa-history"></i> Order History
                </a>
                <a href="place_order.php">
                    <i class="fas fa-shopping-bag"></i> Place Order
                </a>
            </div>
            
            <div class="main-panel">
                <h2>User Dashboard</h2>
                <div class="dashboard-cards">
                    <div class="dashboard-card">
                        <i class="fas fa-utensils"></i>
                        <h3>View Menu</h3>
                        <p>Browse available food items</p>
                        <a href="menu.php" class="card-btn">View Menu</a>
                    </div>
                    <div class="dashboard-card">
                        <i class="fas fa-shopping-cart"></i>
                        <h3>My Cart</h3>
                        <p>View your selected items</p>
                        <a href="cart.php" class="card-btn">View Cart</a>
                    </div>
                    <div class="dashboard-card">
                        <i class="fas fa-history"></i>
                        <h3>Order History</h3>
                        <p>Check your past orders</p>
                        <a href="order_history.php" class="card-btn">View History</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
            min-height: 100vh;
        }
        
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 30px;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .logout-btn {
            background: #ff6b6b;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            transition: background 0.3s;
        }
        
        .logout-btn:hover {
            background: #ff5252;
        }
        
        .dashboard-content {
            display: grid;
            grid-template-columns: 250px 1fr;
            gap: 30px;
        }
        
        .sidebar {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 15px;
            padding: 20px;
        }
        
        .sidebar a {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            margin-bottom: 10px;
            transition: background 0.3s;
        }
        
        .sidebar a:hover,
        .sidebar a.active {
            background: rgba(255, 159, 67, 0.2);
        }
        
        .main-panel {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 15px;
            padding: 30px;
        }
        
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        
        .dashboard-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            transition: transform 0.3s;
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        
        .dashboard-card i {
            font-size: 40px;
            color: #ff9f43;
            margin-bottom: 15px;
        }
        
        .dashboard-card h3 {
            margin-bottom: 10px;
            color: white;
        }
        
        .dashboard-card p {
            color: #e0e0e0;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .card-btn {
            display: inline-block;
            background: #1dd1a1;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            transition: background 0.3s;
        }
        
        .card-btn:hover {
            background: #10ac84;
        }
    </style>
</body>
</html>