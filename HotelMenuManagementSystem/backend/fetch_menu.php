<?php
require_once '../config.php';

header('Content-Type: application/json');

// Get hotel_id from request
$hotel_id = isset($_GET['hotel_id']) ? mysqli_real_escape_string($conn, $_GET['hotel_id']) : '';

$sql = "SELECT mi.*, c.name as category_name 
        FROM menu_items mi
        LEFT JOIN categories c ON mi.category_id = c.id
        WHERE mi.is_available = 1";
        
if ($hotel_id) {
    $sql .= " AND mi.hotel_id = '$hotel_id'";
}

$sql .= " ORDER BY mi.category_id, mi.name";

$result = mysqli_query($conn, $sql);

$menu_items = array();
while($row = mysqli_fetch_assoc($result)) {
    $menu_items[] = $row;
}

echo json_encode($menu_items);
mysqli_close($conn);
?>