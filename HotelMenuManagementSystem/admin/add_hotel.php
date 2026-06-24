<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../admin_login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    $sql = "INSERT INTO hotels (name, address, phone, email) 
            VALUES ('$name', '$address', '$phone', '$email')";
    
    if (mysqli_query($conn, $sql)) {
        $success = "Hotel added successfully!";
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
    <title>Add Hotel - Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            min-height: 100vh;
        }
        
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 30px;
        }
        
        .admin-content {
            display: grid;
            grid-template-columns: 250px 1fr;
            gap: 30px;
        }
        
        .admin-sidebar {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 15px;
            padding: 20px;
        }
        
        .admin-sidebar a {
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
        
        .admin-sidebar a:hover,
        .admin-sidebar a.active {
            background: rgba(255, 159, 67, 0.2);
        }
        
        .form-container {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 15px;
            padding: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #ffda79;
            font-weight: 500;
        }
        
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            color: white;
            font-size: 16px;
        }
        
        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }
        
        .submit-btn {
            background: linear-gradient(135deg, #1dd1a1 0%, #10ac84 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: transform 0.3s;
        }
        
        .submit-btn:hover {
            transform: translateY(-3px);
        }
        
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background: rgba(29, 209, 161, 0.2);
            border: 1px solid #1dd1a1;
            color: #1dd1a1;
        }
        
        .alert-error {
            background: rgba(255, 107, 107, 0.2);
            border: 1px solid #ff6b6b;
            color: #ff6b6b;
        }
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
                <span>Add Hotel - Admin Panel</span>
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
                <a href="admin_dashboard.php">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="add_hotel.php" class="active">
                    <i class="fas fa-hotel"></i> Add Hotel
                </a>
                <a href="add_menu.php">
                    <i class="fas fa-utensils"></i> Add Menu
                </a>
                <a href="manage_menu.php">
                    <i class="fas fa-edit"></i> Manage Menu
                </a>
                <a href="view_orders.php">
                    <i class="fas fa-shopping-bag"></i> View Orders
                </a>
            </div>
            
            <div class="form-container">
                <h2><i class="fas fa-hotel"></i> Add New Hotel</h2>
                
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
                        <label for="name"><i class="fas fa-signature"></i> Hotel Name</label>
                        <input type="text" id="name" name="name" required 
                               placeholder="Enter hotel name">
                    </div>
                    
                    <div class="form-group">
                        <label for="address"><i class="fas fa-map-marker-alt"></i> Address</label>
                        <textarea id="address" name="address" required 
                                  placeholder="Enter hotel address"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone"><i class="fas fa-phone"></i> Phone Number</label>
                        <input type="tel" id="phone" name="phone" 
                               placeholder="Enter phone number">
                    </div>
                    
                    <div class="form-group">
                        <label for="email"><i class="fas fa-envelope"></i> Email</label>
                        <input type="email" id="email" name="email" 
                               placeholder="Enter hotel email">
                    </div>
                    
                    <button type="submit" class="submit-btn">
                        <i class="fas fa-plus-circle"></i> Add Hotel
                    </button>
                </form>
                
                <div style="margin-top: 40px;">
                    <h3><i class="fas fa-list"></i> Existing Hotels</h3>
                    <?php
                    $hotels_sql = "SELECT * FROM hotels ORDER BY name";
                    $hotels_result = mysqli_query($conn, $hotels_sql);
                    
                    if (mysqli_num_rows($hotels_result) > 0): ?>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 20px;">
                            <?php while($hotel = mysqli_fetch_assoc($hotels_result)): ?>
                                <div style="background: rgba(255,255,255,0.05); padding: 20px; border-radius: 10px;">
                                    <h4><?php echo $hotel['name']; ?></h4>
                                    <p><i class="fas fa-map-marker-alt"></i> <?php echo $hotel['address']; ?></p>
                                    <?php if($hotel['phone']): ?>
                                        <p><i class="fas fa-phone"></i> <?php echo $hotel['phone']; ?></p>
                                    <?php endif; ?>
                                    <?php if($hotel['email']): ?>
                                        <p><i class="fas fa-envelope"></i> <?php echo $hotel['email']; ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <p style="color: #aaa; text-align: center;">No hotels added yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php mysqli_close($conn); ?>