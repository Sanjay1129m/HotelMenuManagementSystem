<?php
include '../config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['adminUsername']);
    $password = $_POST['adminPassword'];
    
    // Check if admin exists
    $sql = "SELECT * FROM admins WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        $admin = mysqli_fetch_assoc($result);
        
        // Verify password (in real app, use password_verify with hashed passwords)
        if ($password == $admin['password']) { // Replace with password_verify in production
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['fullname'];
            $_SESSION['admin_role'] = 'admin';
            
            header('Location: ../admin/admin_dashboard.php');
            exit();
        } else {
            echo "<script>alert('Invalid password!'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Admin not found!'); window.history.back();</script>";
    }
    
    mysqli_close($conn);
}
?>