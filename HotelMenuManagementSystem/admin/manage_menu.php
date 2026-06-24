<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../admin_login.php');
    exit();
}

// Handle delete request
if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    $sql = "DELETE FROM menu_items WHERE id = '$id'";
    if (mysqli_query($conn, $sql)) {
        $success = "Menu item deleted successfully!";
    } else {
        $error = "Error deleting item: " . mysqli_error($conn);
    }
}

// Handle update request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $is_available = isset($_POST['is_available']) ? 1 : 0;
    $is_vegetarian = isset($_POST['is_vegetarian']) ? intval($_POST['is_vegetarian']) : 1;
    
    $sql = "UPDATE menu_items SET 
            name = '$name',
            description = '$description',
            price = '$price',
            is_available = '$is_available',
            is_vegetarian = '$is_vegetarian'
            WHERE id = '$id'";
    
    if (mysqli_query($conn, $sql)) {
        $success = "Menu item updated successfully!";
    } else {
        $error = "Error updating item: " . mysqli_error($conn);
    }
}

// Fetch all menu items with hotel and category info
$sql = "SELECT mi.*, h.name as hotel_name, c.name as category_name 
        FROM menu_items mi
        LEFT JOIN hotels h ON mi.hotel_id = h.id
        LEFT JOIN categories c ON mi.category_id = c.id
        ORDER BY mi.created_at DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Menu - Admin</title>
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
        .menu-table { width: 100%; border-collapse: collapse; }
        .menu-table th, .menu-table td { padding: 15px; text-align: left; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .menu-table th { background: rgba(255,255,255,0.1); color: #ffda79; }
        .menu-table tr:hover { background: rgba(255,255,255,0.05); }
        .status-available { color: #1dd1a1; }
        .status-unavailable { color: #ff6b6b; }
        .action-btns { display: flex; gap: 10px; }
        .edit-btn, .delete-btn { padding: 8px 15px; border-radius: 5px; text-decoration: none; font-size: 14px; }
        .edit-btn { background: #48dbfb; color: white; }
        .delete-btn { background: #ff6b6b; color: white; }
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 1000; }
        .modal-content { background: #1e3799; margin: 5% auto; padding: 30px; width: 500px; border-radius: 15px; }
        .close-modal { float: right; font-size: 24px; cursor: pointer; color: #aaa; }
        .close-modal:hover { color: white; }
        .modal-form .form-group { margin-bottom: 20px; }
        .modal-form label { display: block; margin-bottom: 8px; color: #ffda79; }
        .modal-form input, .modal-form textarea { width: 100%; padding: 10px; background: rgba(30, 55, 153, 0.9); border: 2px solid #48dbfb; border-radius: 5px; color: #ffffff; }
        .modal-form select { width: 100%; padding: 10px; background: rgba(30, 55, 153, 0.9); border: 2px solid #48dbfb; border-radius: 5px; color: #ffffff; }
        .modal-form select option { background: #1e3799; color: #ffffff; padding: 8px; }
        .modal-form textarea { min-height: 80px; resize: vertical; }
        .checkbox-group { display: flex; align-items: center; gap: 10px; }
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
                <span>Manage Menu - Admin Panel</span>
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
                <a href="manage_menu.php" class="active"><i class="fas fa-edit"></i> Manage Menu</a>
                <a href="view_orders.php"><i class="fas fa-shopping-bag"></i> View Orders</a>
            </div>
            
            <div class="main-content">
                <h2><i class="fas fa-edit"></i> Manage Menu Items</h2>
                
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
                    <div style="overflow-x: auto;">
                        <table class="menu-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Item Name</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Hotel</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($item = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo $item['id']; ?></td>
                                    <td><?php echo $item['name']; ?></td>
                                    <td style="font-size: 20px; text-align: center;">
                                        <?php echo $item['is_vegetarian'] ? '🌱' : '🍗'; ?>
                                    </td>
                                    <td><?php echo $item['description'] ?: 'No description'; ?></td>
                                    <td>₹<?php echo number_format($item['price'], 2); ?></td>
                                    <td><?php echo $item['hotel_name']; ?></td>
                                    <td><?php echo $item['category_name']; ?></td>
                                    <td class="<?php echo $item['is_available'] ? 'status-available' : 'status-unavailable'; ?>">
                                        <?php echo $item['is_available'] ? 'Available' : 'Unavailable'; ?>
                                    </td>
                                    <td>
                                        <div class="action-btns">
                                            <a href="#" class="edit-btn" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($item), ENT_QUOTES, 'UTF-8'); ?>)">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="?delete=<?php echo $item['id']; ?>" class="delete-btn" 
                                               onclick="return confirm('Are you sure you want to delete this item?')">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p style="text-align: center; color: #aaa; padding: 40px;">No menu items found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeEditModal()">&times;</span>
            <h3><i class="fas fa-edit"></i> Edit Menu Item</h3>
            <form id="editForm" class="modal-form" method="POST">
                <input type="hidden" name="id" id="edit_id">
                <input type="hidden" name="update" value="1">
                
                <div class="form-group">
                    <label for="edit_name">Item Name</label>
                    <input type="text" id="edit_name" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_description">Description</label>
                    <textarea id="edit_description" name="description"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="edit_price">Price (₹)</label>
                    <input type="number" id="edit_price" name="price" step="0.01" min="0" required>
                </div>
                
                <div class="form-group checkbox-group">
                    <label>Item Type:</label>
                    <div style="display: flex; gap: 20px; margin-top: 10px;">
                        <label>
                            <input type="radio" name="is_vegetarian" value="1" id="edit_veg"> 🌱 Vegetarian
                        </label>
                        <label>
                            <input type="radio" name="is_vegetarian" value="0" id="edit_non_veg"> 🍗 Non-Vegetarian
                        </label>
                    </div>
                </div>
                
                <div class="form-group checkbox-group">
                    <input type="checkbox" id="edit_is_available" name="is_available" value="1">
                    <label for="edit_is_available">Available for ordering</label>
                </div>
                
                <button type="submit" class="submit-btn" style="width: 100%;">
                    <i class="fas fa-save"></i> Update Item
                </button>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(item) {
            document.getElementById('edit_id').value = item.id;
            document.getElementById('edit_name').value = item.name;
            document.getElementById('edit_description').value = item.description || '';
            document.getElementById('edit_price').value = item.price;
            document.getElementById('edit_veg').checked = item.is_vegetarian == 1;
            document.getElementById('edit_non_veg').checked = item.is_vegetarian == 0;
            document.getElementById('edit_is_available').checked = item.is_available == 1;
            document.getElementById('editModal').style.display = 'block';
        }
        
        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            var modal = document.getElementById('editModal');
            if (event.target == modal) {
                closeEditModal();
            }
        }
    </script>
</body>
</html>
<?php mysqli_close($conn); ?>