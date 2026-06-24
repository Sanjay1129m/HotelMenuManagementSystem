<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: user_login.html');
    exit();
}

// Fetch hotels for selection
$hotels_sql = "SELECT * FROM hotels ORDER BY name";
$hotels_result = mysqli_query($conn, $hotels_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Place Order - User</title>
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
        .order-summary { background: rgba(255,255,255,0.05); border-radius: 10px; padding: 25px; margin-bottom: 30px; }
        .summary-item { display: flex; justify-content: space-between; margin-bottom: 15px; }
        .summary-total { font-size: 1.3rem; font-weight: bold; color: #ff9f43; border-top: 2px solid rgba(255,255,255,0.1); padding-top: 15px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; color: #ffda79; font-weight: 500; }
        .form-group select, .form-group textarea { width: 100%; padding: 12px; background: rgba(30, 55, 153, 0.9); border: 2px solid #48dbfb; border-radius: 8px; color: #ffffff; font-size: 16px; }
        .form-group select option { background: #1e3799; color: #ffffff; padding: 10px; }
        .form-group textarea { min-height: 100px; resize: vertical; background: rgba(30, 55, 153, 0.9); border: 2px solid #48dbfb; }
        .place-order-btn { background: linear-gradient(135deg, #1dd1a1 0%, #10ac84 100%); color: white; border: none; padding: 15px 30px; border-radius: 8px; font-size: 16px; cursor: pointer; display: block; width: 100%; text-align: center; }
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .alert-success { background: rgba(29,209,161,0.2); border: 1px solid #1dd1a1; color: #1dd1a1; }
        .alert-error { background: rgba(255,107,107,0.2); border: 1px solid #ff6b6b; color: #ff6b6b; }
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
                <span>Place Order - HMMS</span>
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
                <a href="order_history.php"><i class="fas fa-history"></i> Order History</a>
                <a href="place_order.php" class="active"><i class="fas fa-shopping-bag"></i> Place Order</a>
            </div>
            
            <div class="main-content">
                <h2><i class="fas fa-shopping-bag"></i> Place Order</h2>
                
                <div id="message"></div>
                
                <div class="order-summary">
                    <h3><i class="fas fa-receipt"></i> Order Summary</h3>
                    <div class="summary-item">
                        <span>Subtotal:</span>
                        <span id="summarySubtotal">₹0.00</span>
                    </div>
                    <div class="summary-item">
                        <span>Tax (10%):</span>
                        <span id="summaryTax">₹0.00</span>
                    </div>
                    <div class="summary-item summary-total">
                        <span>Total Amount:</span>
                        <span id="summaryTotal">₹0.00</span>
                    </div>
                </div>
                
                <form id="orderForm">
                    <div class="form-group">
                        <label for="hotel_id"><i class="fas fa-hotel"></i> Select Hotel</label>
                        <select id="hotel_id" name="hotel_id" required>
                            <option value="">Select Hotel</option>
                            <?php while($hotel = mysqli_fetch_assoc($hotels_result)): ?>
                                <option value="<?php echo $hotel['id']; ?>">
                                    <?php echo $hotel['name']; ?> - <?php echo $hotel['address']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="special_instructions"><i class="fas fa-sticky-note"></i> Special Instructions (Optional)</label>
                        <textarea id="special_instructions" name="special_instructions" 
                                  placeholder="Any special instructions for your order..."></textarea>
                    </div>
                    
                    <button type="submit" class="place-order-btn" id="placeOrderBtn">
                        <i class="fas fa-check-circle"></i> Confirm Order
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Load cart summary
        document.addEventListener('DOMContentLoaded', function() {
            const subtotal = localStorage.getItem('cartSubtotal') || '0.00';
            const tax = localStorage.getItem('cartTax') || '0.00';
            const total = localStorage.getItem('cartTotal') || '0.00';
            
            document.getElementById('summarySubtotal').textContent = `₹${subtotal}`;
            document.getElementById('summaryTax').textContent = `₹${tax}`;
            document.getElementById('summaryTotal').textContent = `₹${total}`;
        });
        
        // Handle order submission
        document.getElementById('orderForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            if (cart.length === 0) {
                showMessage('Your cart is empty!', 'error');
                return;
            }
            
            const hotelId = document.getElementById('hotel_id').value;
            if (!hotelId) {
                showMessage('Please select a hotel', 'error');
                return;
            }
            
            const total = localStorage.getItem('cartTotal');
            const userId = '<?php echo $_SESSION['user_id']; ?>';
            
            // Disable button and show loading
            const btn = document.getElementById('placeOrderBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            
            // Extract numeric value from total (remove ₹ symbol)
            const totalAmount = total.replace('₹', '').trim();
            
            // Send order to server
            fetch('../backend/add_order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    user_id: userId,
                    hotel_id: hotelId,
                    total_amount: totalAmount,
                    items: JSON.stringify(cart)
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(text => {
                try {
                    const data = JSON.parse(text);
                    if (data.success) {
                        showMessage(data.message + ' Order ID: ' + data.order_id, 'success');
                        
                        // Clear cart
                        localStorage.removeItem('cart');
                        localStorage.removeItem('cartSubtotal');
                        localStorage.removeItem('cartTax');
                        localStorage.removeItem('cartTotal');
                        
                        // Update button
                        btn.innerHTML = '<i class="fas fa-check"></i> Order Placed Successfully!';
                        
                        // Redirect to order history after 3 seconds
                        setTimeout(() => {
                            window.location.href = 'order_history.php';
                        }, 3000);
                    } else {
                        showMessage(data.message, 'error');
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-check-circle"></i> Confirm Order';
                    }
                } catch (error) {
                    console.error('Response text:', text);
                    console.error('Parse error:', error);
                    showMessage('Server error: ' + text.substring(0, 100), 'error');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-check-circle"></i> Confirm Order';
                }
            })
            .catch(error => {
                showMessage('Error placing order: ' + error.message, 'error');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-check-circle"></i> Confirm Order';
            });
        });
        
        function showMessage(message, type) {
            const messageDiv = document.getElementById('message');
            messageDiv.className = `alert alert-${type}`;
            messageDiv.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                ${message}
            `;
            messageDiv.style.display = 'block';
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                messageDiv.style.display = 'none';
            }, 5000);
        }
    </script>
</body>
</html>
<?php mysqli_close($conn); ?>