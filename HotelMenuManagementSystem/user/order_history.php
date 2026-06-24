<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: user_login.html');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user's orders
$sql = "SELECT o.*, h.name as hotel_name, h.address as hotel_address
        FROM orders o
        LEFT JOIN hotels h ON o.hotel_id = h.id
        WHERE o.user_id = '$user_id'
        ORDER BY o.order_date DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History - User</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .user-container { max-width: 1400px; margin: 0 auto; padding: 20px; min-height: 100vh; }
        .user-header { display: flex; justify-content: space-between; align-items: center; background: rgba(255,255,255,0.1); padding: 20px; border-radius: 15px; margin-bottom: 30px; }
        .user-content { display: grid; grid-template-columns: 250px 1fr; gap: 30px; }
        .user-sidebar { background: rgba(255,255,255,0.08); border-radius: 15px; padding: 20px; }
        .user-sidebar a { display: flex; align-items: center; gap: 15px; padding: 15px; color: white; text-decoration: none; border-radius: 10px; margin-bottom: 10px; transition: background 0.3s; }
        .user-sidebar a:hover, .user-sidebar a.active { background: rgba(255,159,67,0.2); }
        .main-content { background: rgba(255,255,255,0.08); border-radius: 15px; padding: 30px; }
        .orders-grid { display: grid; gap: 25px; }
        .order-card { background: rgba(255,255,255,0.05); border-radius: 10px; padding: 25px; }
        .order-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .order-id { font-size: 1.2rem; font-weight: bold; color: #ff9f43; }
        .order-date { color: #aaa; font-size: 0.9rem; }
        .order-info { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px; }
        .info-item label { display: block; color: #ffda79; font-size: 0.9rem; margin-bottom: 5px; }
        .info-item span { display: block; color: white; }
        .order-items { margin: 20px 0; }
        .items-table { width: 100%; border-collapse: collapse; }
        .items-table th, .items-table td { padding: 12px; text-align: left; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .items-table th { background: rgba(255,255,255,0.1); color: #ffda79; }
        .status-badge { display: inline-block; padding: 6px 15px; border-radius: 20px; font-size: 0.9rem; margin-top: 10px; }
        .status-pending { background: rgba(255,193,7,0.2); color: #ffc107; }
        .status-preparing { background: rgba(33,150,243,0.2); color: #2196f3; }
        .status-ready { background: rgba(76,175,80,0.2); color: #4caf50; }
        .status-delivered { background: rgba(139,195,74,0.2); color: #8bc34a; }
        .status-cancelled { background: rgba(244,67,54,0.2); color: #f44336; }
        .total-amount { text-align: right; font-size: 1.2rem; font-weight: bold; color: #ff9f43; margin-top: 20px; }
        .no-orders { text-align: center; padding: 50px; color: #aaa; }
        .view-details-btn { background: #48dbfb; color: white; border: none; padding: 8px 20px; border-radius: 5px; cursor: pointer; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
    </div>
    
    <div class="user-container">
        <div class="user-header">
            <div class="logo">
                <i class="fas fa-concierge-bell"></i>
                <span>Order History - HMMS</span>
            </div>
            <div class="user-info">
                <span>Welcome, <?php echo $_SESSION['user_name']; ?></span>
                <a href="../backend/logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
        
        <div class="user-content">
            <div class="user-sidebar">
                <a href="user_dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
                <a href="menu.php"><i class="fas fa-utensils"></i> View Menu</a>
                <a href="cart.php"><i class="fas fa-shopping-cart"></i> My Cart</a>
                <a href="order_history.php" class="active"><i class="fas fa-history"></i> Order History</a>
                <a href="place_order.php"><i class="fas fa-shopping-bag"></i> Place Order</a>
            </div>
            
            <div class="main-content">
                <h2><i class="fas fa-history"></i> My Order History</h2>
                
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <div class="orders-grid">
                        <?php while($order = mysqli_fetch_assoc($result)): 
                            // Fetch order items
                            $items_sql = "SELECT oi.*, mi.name as item_name 
                                        FROM order_items oi
                                        JOIN menu_items mi ON oi.menu_item_id = mi.id
                                        WHERE oi.order_id = '{$order['id']}'";
                            $items_result = mysqli_query($conn, $items_sql);
                        ?>
                        <div class="order-card">
                            <div class="order-header">
                                <div class="order-id">Order #<?php echo $order['id']; ?></div>
                                <div class="order-date">
                                    <i class="far fa-clock"></i> 
                                    <?php echo date('M d, Y h:i A', strtotime($order['order_date'])); ?>
                                </div>
                            </div>
                            
                            <div class="order-info">
                                <div class="info-item">
                                    <label><i class="fas fa-hotel"></i> Hotel</label>
                                    <span><?php echo $order['hotel_name']; ?></span>
                                </div>
                                <div class="info-item">
                                    <label><i class="fas fa-map-marker-alt"></i> Address</label>
                                    <span><?php echo $order['hotel_address']; ?></span>
                                </div>
                            </div>
                            
                            <div>
                                <label>Status:</label>
                                <?php 
                                $status_class = 'status-' . $order['status'];
                                $status_text = ucfirst($order['status']);
                                ?>
                                <div class="status-badge <?php echo $status_class; ?>">
                                    <?php echo $status_text; ?>
                                </div>
                            </div>
                            
                            <div class="order-items">
                                <h4><i class="fas fa-list"></i> Order Items</h4>
                                <table class="items-table">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $items_total = 0;
                                        while($item = mysqli_fetch_assoc($items_result)): 
                                            $subtotal = $item['quantity'] * $item['price'];
                                            $items_total += $subtotal;
                                        ?>
                                        <tr>
                                            <td><?php echo $item['item_name']; ?></td>
                                            <td><?php echo $item['quantity']; ?></td>
                                            <td>₹<?php echo number_format($item['price'], 2); ?></td>
                                            <td>₹<?php echo number_format($subtotal, 2); ?></td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="total-amount">
                                Total Amount: ₹<?php echo number_format($order['total_amount'], 2); ?>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="no-orders">
                        <i class="fas fa-history fa-3x" style="margin-bottom: 20px;"></i>
                        <h3>No Orders Yet</h3>
                        <p>You haven't placed any orders yet.</p>
                        <a href="menu.php" style="display: inline-block; margin-top: 20px; background: #1dd1a1; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none;">
                            <i class="fas fa-utensils"></i> Start Ordering
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
<?php mysqli_close($conn); ?>