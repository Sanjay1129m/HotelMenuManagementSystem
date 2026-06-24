<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../admin_login.php');
    exit();
}

// Handle status update
if (isset($_POST['update_status'])) {
    $order_id = mysqli_real_escape_string($conn, $_POST['order_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    $sql = "UPDATE orders SET status = '$status' WHERE id = '$order_id'";
    if (mysqli_query($conn, $sql)) {
        $success = "Order status updated successfully!";
    } else {
        $error = "Error updating status: " . mysqli_error($conn);
    }
}

// Fetch all orders with user and hotel info
$sql = "SELECT o.*, u.fullname as user_name, u.email as user_email, 
               h.name as hotel_name, h.address as hotel_address
        FROM orders o
        LEFT JOIN users u ON o.user_id = u.id
        LEFT JOIN hotels h ON o.hotel_id = h.id
        ORDER BY o.order_date DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Orders - Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .admin-container { max-width: 1400px; margin: 0 auto; padding: 20px; min-height: 100vh; }
        .admin-header { display: flex; justify-content: space-between; align-items: center; background: rgba(255,255,255,0.1); padding: 20px; border-radius: 15px; margin-bottom: 30px; }
        .admin-content { display: grid; grid-template-columns: 250px 1fr; gap: 30px; }
        .admin-sidebar { background: rgba(255,255,255,0.08); border-radius: 15px; padding: 20px; }
        .admin-sidebar a { display: flex; align-items: center; gap: 15px; padding: 15px; color: white; text-decoration: none; border-radius: 10px; margin-bottom: 10px; transition: background 0.3s; }
        .admin-sidebar a:hover, .admin-sidebar a.active { background: rgba(255,159,67,0.2); }
        .main-content { background: rgba(255,255,255,0.08); border-radius: 15px; padding: 30px; }
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .alert-success { background: rgba(29,209,161,0.2); border: 1px solid #1dd1a1; color: #1dd1a1; }
        .alert-error { background: rgba(255,107,107,0.2); border: 1px solid #ff6b6b; color: #ff6b6b; }
        .orders-grid { display: grid; gap: 20px; }
        .order-card { background: rgba(255,255,255,0.05); border-radius: 10px; padding: 20px; }
        .order-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .order-id { font-size: 18px; font-weight: bold; color: #ff9f43; }
        .order-date { color: #aaa; font-size: 14px; }
        .order-info { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 20px; }
        .info-item label { display: block; color: #ffda79; font-size: 14px; margin-bottom: 5px; }
        .info-item span { display: block; color: white; }
        .order-items { margin: 20px 0; }
        .items-table { width: 100%; border-collapse: collapse; }
        .items-table th, .items-table td { padding: 10px; text-align: left; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .items-table th { background: rgba(255,255,255,0.1); color: #ffda79; }
        .status-badge { display: inline-block; padding: 5px 15px; border-radius: 20px; font-size: 14px; }
        .status-pending { background: rgba(255,193,7,0.2); color: #ffc107; }
        .status-preparing { background: rgba(33,150,243,0.2); color: #2196f3; }
        .status-ready { background: rgba(76,175,80,0.2); color: #4caf50; }
        .status-delivered { background: rgba(139,195,74,0.2); color: #8bc34a; }
        .status-cancelled { background: rgba(244,67,54,0.2); color: #f44336; }
        .status-form { display: flex; gap: 10px; align-items: center; }
        .status-select { padding: 8px 15px; background: rgba(30, 55, 153, 0.9); border: 2px solid #48dbfb; border-radius: 5px; color: #ffffff; }
        .status-select option { background: #1e3799; color: #ffffff; padding: 8px; }
        .update-btn { padding: 8px 20px; background: #48dbfb; color: white; border: none; border-radius: 5px; cursor: pointer; }
        .total-amount { text-align: right; font-size: 18px; font-weight: bold; color: #ff9f43; margin-top: 20px; }
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
                <span>View Orders - Admin Panel</span>
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
                <a href="admin_dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
                <a href="add_hotel.php"><i class="fas fa-hotel"></i> Add Hotel</a>
                <a href="add_menu.php"><i class="fas fa-utensils"></i> Add Menu</a>
                <a href="manage_menu.php"><i class="fas fa-edit"></i> Manage Menu</a>
                <a href="view_orders.php" class="active"><i class="fas fa-shopping-bag"></i> View Orders</a>
            </div>
            
            <div class="main-content">
                <h2><i class="fas fa-shopping-bag"></i> All Orders</h2>
                
                <?php if (isset($success)): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <div class="orders-grid">
                        <?php while($order = mysqli_fetch_assoc($result)): 
                            // Fetch order items for this order
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
                                    <label><i class="fas fa-user"></i> Customer</label>
                                    <span><?php echo $order['user_name']; ?></span>
                                </div>
                                <div class="info-item">
                                    <label><i class="fas fa-envelope"></i> Email</label>
                                    <span><?php echo $order['user_email']; ?></span>
                                </div>
                                <div class="info-item">
                                    <label><i class="fas fa-hotel"></i> Hotel</label>
                                    <span><?php echo $order['hotel_name']; ?></span>
                                </div>
                                <div class="info-item">
                                    <label><i class="fas fa-map-marker-alt"></i> Hotel Address</label>
                                    <span><?php echo $order['hotel_address']; ?></span>
                                </div>
                            </div>
                            
                            <div class="order-status">
                                <label>Current Status:</label>
                                <?php 
                                $status_class = 'status-' . $order['status'];
                                $status_text = ucfirst($order['status']);
                                ?>
                                <span class="status-badge <?php echo $status_class; ?>">
                                    <?php echo $status_text; ?>
                                </span>
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
                                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                                            <td>$<?php echo number_format($subtotal, 2); ?></td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="total-amount">
                                Total Amount: $<?php echo number_format($order['total_amount'], 2); ?>
                            </div>
                            
                            <form method="POST" class="status-form">
                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                <select name="status" class="status-select" required>
                                    <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="preparing" <?php echo $order['status'] == 'preparing' ? 'selected' : ''; ?>>Preparing</option>
                                    <option value="ready" <?php echo $order['status'] == 'ready' ? 'selected' : ''; ?>>Ready</option>
                                    <option value="delivered" <?php echo $order['status'] == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                    <option value="cancelled" <?php echo $order['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                                <button type="submit" name="update_status" class="update-btn">
                                    <i class="fas fa-sync-alt"></i> Update Status
                                </button>
                            </form>
                        </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <p style="text-align: center; color: #aaa; padding: 40px;">No orders found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
<?php mysqli_close($conn); ?>