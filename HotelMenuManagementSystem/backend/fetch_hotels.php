<?php
require_once '../config.php';

header('Content-Type: application/json');

$sql = "SELECT * FROM hotels ORDER BY name";
$result = mysqli_query($conn, $sql);

$hotels = array();
while($row = mysqli_fetch_assoc($result)) {
    $hotels[] = $row;
}

echo json_encode($hotels);
mysqli_close($conn);
?>