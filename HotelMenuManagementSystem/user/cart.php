<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: user_login.html');
    exit();
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart - User</title>
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
        .cart-items { margin-bottom: 30px; }
        .cart-table { width: 100%; border-collapse: collapse; }
        .cart-table th, .cart-table td { padding: 15px; text-align: left; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .cart-table th { background: rgba(255,255,255,0.1); color: #ffda79; }
        .quantity-input { width: 60px; padding: 8px; background: rgba(30, 55, 153, 0.9); border: 2px solid #48dbfb; border-radius: 5px; color: #ffffff; text-align: center; }
        .remove-btn { background: #ff6b6b; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; }
        .cart-summary { background: rgba(255,255,255,0.05); border-radius: 10px; padding: 25px; }
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 15px; }
        .summary-total { font-size: 1.3rem; font-weight: bold; color: #ff9f43; border-top: 2px solid rgba(255,255,255,0.1); padding-top: 15px; }
        .checkout-btn { background: linear-gradient(135deg, #1dd1a1 0%, #10ac84 100%); color: white; border: none; padding: 15px 30px; border-radius: 8px; font-size: 16px; cursor: pointer; display: block; width: 100%; margin-top: 20px; text-align: center; text-decoration: none; }
        .empty-cart { text-align: center; padding: 40px; color: #aaa; }
        .cart-actions { display: flex; gap: 10px; }
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
                <span>My Cart - HMMS</span>
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
                <a href="cart.php" class="active"><i class="fas fa-shopping-cart"></i> My Cart</a>
                <a href="order_history.php"><i class="fas fa-history"></i> Order History</a>
                <a href="place_order.php"><i class="fas fa-shopping-bag"></i> Place Order</a>
            </div>
            
            <div class="main-content">
                <h2><i class="fas fa-shopping-cart"></i> My Cart</h2>
                
                <div class="cart-items">
                    <div id="cartContent">
                        <!-- Cart content will be loaded by JavaScript -->
                    </div>
                </div>
                
                <div class="cart-summary">
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span id="subtotal">₹0.00</span>
                    </div>
                    <div class="summary-row">
                        <span>Tax (10%):</span>
                        <span id="tax">₹0.00</span>
                    </div>
                    <div class="summary-row summary-total">
                        <span>Total:</span>
                        <span id="total">₹0.00</span>
                    </div>
                    
                    <a href="place_order.php" class="checkout-btn" id="checkoutBtn">
                        <i class="fas fa-shopping-bag"></i> Proceed to Checkout
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        
        function loadCart() {
            const cartContent = document.getElementById('cartContent');
            const subtotalEl = document.getElementById('subtotal');
            const taxEl = document.getElementById('tax');
            const totalEl = document.getElementById('total');
            const checkoutBtn = document.getElementById('checkoutBtn');
            
            if (cart.length === 0) {
                cartContent.innerHTML = `
                    <div class="empty-cart">
                        <i class="fas fa-shopping-cart fa-3x" style="margin-bottom: 20px;"></i>
                        <h3>Your cart is empty</h3>
                        <p>Add some delicious items from the menu!</p>
                        <a href="menu.php" style="display: inline-block; margin-top: 20px; background: #1dd1a1; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none;">
                            <i class="fas fa-utensils"></i> Browse Menu
                        </a>
                    </div>
                `;
                subtotalEl.textContent = '₹0.00';
                taxEl.textContent = '₹0.00';
                totalEl.textContent = '₹0.00';
                checkoutBtn.style.display = 'none';
                return;
            }
            
            let tableHTML = `
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
            
            let subtotal = 0;
            
            cart.forEach((item, index) => {
                const itemSubtotal = item.price * item.quantity;
                subtotal += itemSubtotal;
                
                tableHTML += `
                    <tr>
                        <td>${item.name}</td>
                        <td>₹${item.price.toFixed(2)}</td>
                        <td>
                            <input type="number" class="quantity-input" 
                                   value="${item.quantity}" min="1" max="10"
                                   onchange="updateQuantity(${index}, this.value)">
                        </td>
                        <td>₹${itemSubtotal.toFixed(2)}</td>
                        <td>
                            <div class="cart-actions">
                                <button class="remove-btn" onclick="removeItem(${index})">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });
            
            tableHTML += `</tbody></table>`;
            cartContent.innerHTML = tableHTML;
            
            const tax = subtotal * 0.10;
            const total = subtotal + tax;
            
            subtotalEl.textContent = `₹${subtotal.toFixed(2)}`;
            taxEl.textContent = `₹${tax.toFixed(2)}`;
            totalEl.textContent = `₹${total.toFixed(2)}`;
            
            // Save totals to localStorage for checkout
            localStorage.setItem('cartSubtotal', subtotal.toFixed(2));
            localStorage.setItem('cartTax', tax.toFixed(2));
            localStorage.setItem('cartTotal', total.toFixed(2));
            
            checkoutBtn.style.display = 'block';
        }
        
        function updateQuantity(index, newQuantity) {
            newQuantity = parseInt(newQuantity);
            if (newQuantity < 1) newQuantity = 1;
            if (newQuantity > 10) newQuantity = 10;
            
            cart[index].quantity = newQuantity;
            localStorage.setItem('cart', JSON.stringify(cart));
            loadCart();
        }
        
        function removeItem(index) {
            if (confirm('Are you sure you want to remove this item?')) {
                cart.splice(index, 1);
                localStorage.setItem('cart', JSON.stringify(cart));
                loadCart();
            }
        }
        
        // Load cart on page load
        document.addEventListener('DOMContentLoaded', loadCart);
    </script>
</body>
</html>
<?php mysqli_close($conn); ?>