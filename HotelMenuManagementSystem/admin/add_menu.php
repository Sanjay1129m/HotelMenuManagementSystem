<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../admin_login.php');
    exit();
}

// Fetch hotels for dropdown
$hotels_sql = "SELECT * FROM hotels ORDER BY name";
$hotels_result = mysqli_query($conn, $hotels_sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $hotel_id = mysqli_real_escape_string($conn, $_POST['hotel_id']);
    $is_vegetarian = isset($_POST['is_vegetarian']) ? intval($_POST['is_vegetarian']) : 1;
    
    $sql = "INSERT INTO menu_items (name, description, price, hotel_id, is_vegetarian) 
            VALUES ('$name', '$description', '$price', '$hotel_id', '$is_vegetarian')";
    
    if (mysqli_query($conn, $sql)) {
        $success = "Menu item added successfully!";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Menu Item - Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .admin-container { max-width: 1200px; margin: 0 auto; padding: 20px; min-height: 100vh; }
        .admin-header { display: flex; justify-content: space-between; align-items: center; background: rgba(255,255,255,0.1); padding: 20px; border-radius: 15px; margin-bottom: 30px; }
        .admin-content { display: grid; grid-template-columns: 250px 1fr; gap: 30px; }
        .admin-sidebar { background: rgba(255,255,255,0.08); border-radius: 15px; padding: 20px; }
        .admin-sidebar a { display: flex; align-items: center; gap: 15px; padding: 15px; color: white; text-decoration: none; border-radius: 10px; margin-bottom: 10px; transition: background 0.3s; }
        .admin-sidebar a:hover, .admin-sidebar a.active { background: rgba(255,159,67,0.2); }
        .form-container { background: rgba(255,255,255,0.08); border-radius: 15px; padding: 30px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; color: #ffda79; font-weight: 500; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 12px; background: rgba(30, 55, 153, 0.9); border: 2px solid #48dbfb; border-radius: 8px; color: #ffffff; font-size: 16px; }
        .form-group select option { background: #1e3799; color: #ffffff; padding: 10px; }
        .form-group textarea { min-height: 100px; resize: vertical; }
        .submit-btn { background: linear-gradient(135deg, #1dd1a1 0%, #10ac84 100%); color: white; border: none; padding: 15px 30px; border-radius: 8px; font-size: 16px; cursor: pointer; display: flex; align-items: center; gap: 10px; transition: transform 0.3s; }
        .submit-btn:hover { transform: translateY(-3px); }
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .alert-success { background: rgba(29,209,161,0.2); border: 1px solid #1dd1a1; color: #1dd1a1; }
        .alert-error { background: rgba(255,107,107,0.2); border: 1px solid #ff6b6b; color: #ff6b6b; }
        .price-input { position: relative; }
        .price-input:before { content: '₹'; position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #aaa; }
        .price-input input { padding-left: 30px; }
        .veg-options { display: flex; gap: 20px; margin-top: 10px; }
        .veg-option { display: flex; align-items: center; gap: 10px; cursor: pointer; }
        .veg-option input[type="radio"] { cursor: pointer; }
        .veg-symbol { font-size: 24px; text-align: center; width: 30px; }
        .veg-symbol.veg { color: #1dd1a1; }
        .veg-symbol.non-veg { color: #ff6b6b; }
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
                <span>Add Menu Item - Admin Panel</span>
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
                <a href="add_menu.php" class="active"><i class="fas fa-utensils"></i> Add Menu</a>
                <a href="manage_menu.php"><i class="fas fa-edit"></i> Manage Menu</a>
                <a href="view_orders.php"><i class="fas fa-shopping-bag"></i> View Orders</a>
            </div>
            
            <div class="form-container">
                <h2><i class="fas fa-utensils"></i> Add New Menu Item</h2>
                
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
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="name"><i class="fas fa-signature"></i> Item Name</label>
                        <input type="text" id="name" name="name" required 
                               placeholder="Enter menu item name">
                    </div>
                    
                    <div class="form-group">
                        <label for="description"><i class="fas fa-file-alt"></i> Description</label>
                        <textarea id="description" name="description" 
                                  placeholder="Enter item description"></textarea>
                    </div>
                    
                    <div class="form-group price-input">
                        <label for="price"><i class="fas fa-tag"></i> Price</label>
                        <input type="number" id="price" name="price" step="0.01" min="0" required 
                               placeholder="0.00">
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-leaf"></i> Item Type</label>
                        <div class="veg-options">
                            <label class="veg-option">
                                <input type="radio" name="is_vegetarian" value="1" checked>
                                <div class="veg-symbol veg">🌱</div>
                                <span>Vegetarian</span>
                            </label>
                            <label class="veg-option">
                                <input type="radio" name="is_vegetarian" value="0">
                                <div class="veg-symbol non-veg">🍗</div>
                                <span>Non-Vegetarian</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="hotel_id"><i class="fas fa-hotel"></i> Hotel</label>
                        <select id="hotel_id" name="hotel_id" required>
                            <option value="">Select Hotel</option>
                            <?php while($hotel = mysqli_fetch_assoc($hotels_result)): ?>
                                <option value="<?php echo $hotel['id']; ?>">
                                    <?php echo $hotel['name']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <button type="submit" class="submit-btn">
                        <i class="fas fa-plus-circle"></i> Add Menu Item
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
<?php mysqli_close($conn); ?>