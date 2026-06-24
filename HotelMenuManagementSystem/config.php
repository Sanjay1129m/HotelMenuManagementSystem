<?php
$server   = "localhost";
$user     = "root";
$password = "";
$dbname   = "hotel_menu_db";

$conn = new mysqli($server, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

