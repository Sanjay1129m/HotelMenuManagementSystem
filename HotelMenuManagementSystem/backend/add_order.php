<?php
header('Content-Type: application/json');
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get POST data
    $user_id = isset($_POST['user_id']) ? mysqli_real_escape_string($conn, $_POST['user_id']) : '';
    $hotel_id = mysqli_real_escape_string($conn, $_POST['hotel_id']);
    $total_amount = mysqli_real_escape_string($conn, $_POST['total_amount']);
    $items = json_decode($_POST['items'], true);
    
    // Start transaction
    mysqli_begin_transaction($conn);
    
    try {
        // Insert order
        $sql = "INSERT INTO orders (user_id, hotel_id, total_amount) 
                VALUES ('$user_id', '$hotel_id', '$total_amount')";
        
        if (mysqli_query($conn, $sql)) {
            $order_id = mysqli_insert_id($conn);
            
            // Insert order items
            foreach ($items as $item) {
                $menu_item_id = mysqli_real_escape_string($conn, $item['id']);
                $quantity = mysqli_real_escape_string($conn, $item['quantity']);
                $price = mysqli_real_escape_string($conn, $item['price']);
                
                $item_sql = "INSERT INTO order_items (order_id, menu_item_id, quantity, price) 
                            VALUES ('$order_id', '$menu_item_id', '$quantity', '$price')";
                mysqli_query($conn, $item_sql);
            }
            
            // Commit transaction
            mysqli_commit($conn);
            
            echo json_encode([
                'success' => true,
                'message' => 'Order placed successfully!',
                'order_id' => $order_id
            ]);
        } else {
            throw new Exception(mysqli_error($conn));
        }
    } catch (Exception $e) {
        // Rollback transaction on error
        mysqli_rollback($conn);
        
        echo json_encode([
            'success' => false,
            'message' => 'Error placing order: ' . $e->getMessage()
        ]);
    }
    
    mysqli_close($conn);
    exit();
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
    exit();
}
?>