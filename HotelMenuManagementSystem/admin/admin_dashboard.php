<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../admin_login.php');
    exit();
}

// Get statistics
$users_count = mysqli_query($conn, "SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$hotels_count = mysqli_query($conn, "SELECT COUNT(*) as count FROM hotels")->fetch_assoc()['count'];
$menu_count = mysqli_query($conn, "SELECT COUNT(*) as count FROM menu_items")->fetch_assoc()['count'];
$orders_count = mysqli_query($conn, "SELECT COUNT(*) as count FROM orders")->fetch_assoc()['count'];

// Get recent orders
$recent_orders_sql = "SELECT o.*, u.fullname as user_name, h.name as hotel_name 
                     FROM orders o
                     LEFT JOIN users u ON o.user_id = u.id
                     LEFT JOIN hotels h ON o.hotel_id = h.id
                     ORDER BY o.order_date DESC LIMIT 5";
$recent_orders = mysqli_query($conn, $recent_orders_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - HMMS</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .admin-container { max-width: 1400px; margin: 0 auto; padding: 20px; min-height: 100vh; }
        .admin-header { display: flex; justify-content: space-between; align-items: center; background: rgba(255,255,255,0.1); padding: 20px; border-radius: 15px; margin-bottom: 30px; }
        .admin-content { display: grid; grid-template-columns: 250px 1fr; gap: 30px; }
        .admin-sidebar { background: rgba(255,255,255,0.08); border-radius: 15px; padding: 20px; }
        .admin-sidebar a { display: flex; align-items: center; gap: 15px; padding: 15px; color: white; text-decoration: none; border-radius: 10px; margin-bottom: 10px; transition: background 0.3s; }
        .admin-sidebar a:hover, .admin-sidebar a.active { background: rgba(255,159,67,0.2); }
        .dashboard-content { background: rgba(255,255,255,0.08); border-radius: 15px; padding: 30px; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 40px; }
        .stat-card { background: rgba(255,255,255,0.1); border-radius: 15px; padding: 25px; text-align: center; transition: transform 0.3s; }
        .stat-card:hover { transform: translateY(-5px); background: rgba(255,255,255,0.15); }
        .stat-icon { font-size: 40px; margin-bottom: 15px; }
        .stat-card:nth-child(1) .stat-icon { color: #1dd1a1; }
        .stat-card:nth-child(2) .stat-icon { color: #48dbfb; }
        .stat-card:nth-child(3) .stat-icon { color: #ff9f43; }
        .stat-card:nth-child(4) .stat-icon { color: #ff6b6b; }
        .stat-number { font-size: 2.5rem; font-weight: bold; color: white; margin-bottom: 10px; }
        .stat-label { color: #ffda79; font-size: 1rem; }
        .recent-orders { margin-top: 40px; }
        .orders-table { width: 100%; border-collapse: collapse; }
        .orders-table th, .orders-table td { padding: 15px; text-align: left; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .orders-table th { background: rgba(255,255,255,0.1); color: #ffda79; }
        .orders-table tr:hover { background: rgba(255,255,255,0.05); }
        .status-badge { display: inline-block; padding: 5px 15px; border-radius: 20px; font-size: 14px; }
        .status-pending { background: rgba(255,193,7,0.2); color: #ffc107; }
        .status-preparing { background: rgba(33,150,243,0.2); color: #2196f3; }
        .status-ready { background: rgba(76,175,80,0.2); color: #4caf50; }
        .status-delivered { background: rgba(139,195,74,0.2); color: #8bc34a; }
        .status-cancelled { background: rgba(244,67,54,0.2); color: #f44336; }
        .quick-actions { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 30px; }
        .action-btn { background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white; padding: 15px; border-radius: 10px; text-decoration: none; text-align: center; transition: all 0.3s; }
        .action-btn:hover { background: rgba(255,159,67,0.2); transform: translateY(-3px); }
    </style>
</head>
<body>
    <div class="bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
    </div>
    
    <div class="admin-container">
        <div class="admin-header">
            <div class="logo">
                <i class="fas fa-concierge-bell"></i>
                <span>Admin Dashboard - HMMS</span>
            </div>
            <div class="user-info">
                <span>Welcome, <?php echo $_SESSION['admin_name']; ?></span>
                <a href="../backend/logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
        
        <div class="admin-content">
            <div class="admin-sidebar">
                <a href="admin_dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a>
                <a href="add_hotel.php"><i class="fas fa-hotel"></i> Add Hotel</a>
                <a href="add_menu.php"><i class="fas fa-utensils"></i> Add Menu</a>
                <a href="manage_menu.php"><i class="fas fa-edit"></i> Manage Menu</a>
                <a href="view_orders.php"><i class="fas fa-shopping-bag"></i> View Orders</a>
            </div>
            
            <div class="dashboard-content">
                <h2><i class="fas fa-tachometer-alt"></i> Dashboard Overview</h2>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-users"></i></div>
                        <div class="stat-number"><?php echo $users_count; ?></div>
                        <div class="stat-label">Total Users</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-hotel"></i></div>
                        <div class="stat-number"><?php echo $hotels_count; ?></div>
                        <div class="stat-label">Hotels</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-utensils"></i></div>
                        <div class="stat-number"><?php echo $menu_count; ?></div>
                        <div class="stat-label">Menu Items</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-shopping-bag"></i></div>
                        <div class="stat-number"><?php echo $orders_count; ?></div>
                        <div class="stat-label">Total Orders</div>
                    </div>
                </div>
                
                <div class="quick-actions">
                    <a href="add_hotel.php" class="action-btn">
                        <i class="fas fa-plus-circle"></i> Add New Hotel
                    </a>
                    <a href="add_menu.php" class="action-btn">
                        <i class="fas fa-plus-circle"></i> Add Menu Item
                    </a>
                    <a href="manage_menu.php" class="action-btn">
                        <i class="fas fa-edit"></i> Manage Menu
                    </a>
                    <a href="view_orders.php" class="action-btn">
                        <i class="fas fa-eye"></i> View All Orders
                    </a>
                </div>
                
                <div class="recent-orders">
                    <h3><i class="fas fa-history"></i> Recent Orders</h3>
                    <?php if (mysqli_num_rows($recent_orders) > 0): ?>
                        <div style="overflow-x: auto;">
                            <table class="orders-table">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Hotel</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($order = mysqli_fetch_assoc($recent_orders)): ?>
                                    <tr>
                                        <td>#<?php echo $order['id']; ?></td>
                                        <td><?php echo $order['user_name']; ?></td>
                                        <td><?php echo $order['hotel_name']; ?></td>
                                        <td>₹<?php echo number_format($order['total_amount'], 2); ?></td>
                                        <td>
                                            <?php 
                                            $status_class = 'status-' . $order['status'];
                                            $status_text = ucfirst($order['status']);
                                            ?>
                                            <span class="status-badge <?php echo $status_class; ?>">
                                                <?php echo $status_text; ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('M d, Y', strtotime($order['order_date'])); ?></td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p style="text-align: center; color: #aaa; padding: 20px;">No recent orders found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php mysqli_close($conn); ?>