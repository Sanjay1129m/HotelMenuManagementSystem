<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: user_login.html');
    exit();
}

// Fetch hotels for filter
$hotels_sql = "SELECT * FROM hotels ORDER BY name";
$hotels_result = mysqli_query($conn, $hotels_sql);

// Get selected hotel from GET or default to first hotel
$selected_hotel = isset($_GET['hotel_id']) ? mysqli_real_escape_string($conn, $_GET['hotel_id']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Menu - User</title>
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
        .hotel-filter { margin-bottom: 30px; }
        .hotel-select { padding: 12px 20px; background: rgba(30, 55, 153, 0.9); border: 2px solid #48dbfb; border-radius: 8px; color: #ffffff; font-size: 16px; width: 100%; }
        .hotel-select option { background: #1e3799; color: #ffffff; padding: 10px; }
        .menu-items { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 25px; }
        .menu-card { background: rgba(255,255,255,0.1); border-radius: 15px; padding: 25px; transition: transform 0.3s; border: 1px solid rgba(255,255,255,0.1); }
        .menu-card:hover { transform: translateY(-5px); background: rgba(255,255,255,0.15); }
        .menu-header { display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px; }
        .menu-name { font-size: 1.3rem; font-weight: 600; color: white; }
        .menu-price { font-size: 1.2rem; color: #ff9f43; font-weight: bold; }
        .menu-category { display: inline-block; background: rgba(72, 219, 251, 0.2); color: #48dbfb; padding: 5px 15px; border-radius: 20px; font-size: 14px; margin-bottom: 15px; }
        .menu-description { color: #e0e0e0; line-height: 1.6; margin-bottom: 20px; font-size: 0.95rem; }
        .add-to-cart { display: flex; gap: 10px; align-items: center; }
        .quantity-input { width: 60px; padding: 8px; background: rgba(30, 55, 153, 0.9); border: 2px solid #48dbfb; border-radius: 5px; color: #ffffff; text-align: center; }
        .add-btn { background: #1dd1a1; color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; transition: background 0.3s; }
        .add-btn:hover { background: #10ac84; }
        .no-items { text-align: center; color: #aaa; padding: 40px; grid-column: 1 / -1; }
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
                <span>View Menu - HMMS</span>
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
                <a href="menu.php" class="active"><i class="fas fa-utensils"></i> View Menu</a>
                <a href="cart.php"><i class="fas fa-shopping-cart"></i> My Cart</a>
                <a href="order_history.php"><i class="fas fa-history"></i> Order History</a>
                <a href="place_order.php"><i class="fas fa-shopping-bag"></i> Place Order</a>
            </div>
            
            <div class="main-content">
                <h2><i class="fas fa-utensils"></i> Menu Items</h2>
                
                <div class="hotel-filter">
                    <select id="hotelSelect" class="hotel-select" onchange="window.location.href='?hotel_id=' + this.value">
                        <option value="">Select a Hotel</option>
                        <?php while($hotel = mysqli_fetch_assoc($hotels_result)): ?>
                            <option value="<?php echo $hotel['id']; ?>" 
                                    <?php echo ($selected_hotel == $hotel['id']) ? 'selected' : ''; ?>>
                                <?php echo $hotel['name']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="menu-items" id="menuItems">
                    <?php
                    if ($selected_hotel) {
                        // Fetch menu items for selected hotel
                        $menu_sql = "SELECT mi.*, c.name as category_name 
                                   FROM menu_items mi
                                   LEFT JOIN categories c ON mi.category_id = c.id
                                   WHERE mi.hotel_id = '$selected_hotel' AND mi.is_available = 1
                                   ORDER BY c.name, mi.name";
                        $menu_result = mysqli_query($conn, $menu_sql);
                        
                        if (mysqli_num_rows($menu_result) > 0) {
                            while($item = mysqli_fetch_assoc($menu_result)): ?>
                                <div class="menu-card">
                                    <div class="menu-header">
                                        <div class="menu-name">
                                            <?php echo $item['name']; ?>
                                            <span class="veg-indicator" style="font-size: 16px; margin-left: 8px;">
                                                <?php echo $item['is_vegetarian'] ? '🌱' : '🍗'; ?>
                                            </span>
                                        </div>
                                        <div class="menu-price">₹<?php echo number_format($item['price'], 2); ?></div>
                                    </div>
                                    <div class="menu-category"><?php echo $item['category_name']; ?></div>
                                    <div class="menu-description">
                                        <?php echo $item['description'] ?: 'No description available.'; ?>
                                    </div>
                                    <div class="add-to-cart">
                                        <input type="number" class="quantity-input" 
                                               id="qty_<?php echo $item['id']; ?>" 
                                               value="1" min="1" max="10">
                                        <button class="add-btn" 
                                                onclick="addToCart(<?php echo $item['id']; ?>, '<?php echo addslashes($item['name']); ?>', <?php echo $item['price']; ?>)">
                                            <i class="fas fa-cart-plus"></i> Add to Cart
                                        </button>
                                    </div>
                                </div>
                            <?php endwhile;
                        } else {
                            echo '<div class="no-items">No menu items available for this hotel.</div>';
                        }
                    } else {
                        echo '<div class="no-items">Please select a hotel to view menu items.</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        
        function addToCart(itemId, itemName, price) {
            const quantity = parseInt(document.getElementById('qty_' + itemId).value);
            
            // Check if item already in cart
            const existingItem = cart.find(item => item.id === itemId);
            
            if (existingItem) {
                existingItem.quantity += quantity;
            } else {
                cart.push({
                    id: itemId,
                    name: itemName,
                    price: price,
                    quantity: quantity
                });
            }
            
            // Save to localStorage
            localStorage.setItem('cart', JSON.stringify(cart));
            
            // Show success message
            alert(`${quantity} ${itemName} added to cart!`);
            
            // Update cart badge
            updateCartBadge();
        }
        
        function updateCartBadge() {
            const totalItems = cart.reduce((total, item) => total + item.quantity, 0);
            // You can add code here to update a cart badge in the header
        }
        
        // Initialize cart badge
        updateCartBadge();
    </script>
</body>
</html>
<?php mysqli_close($conn); ?>