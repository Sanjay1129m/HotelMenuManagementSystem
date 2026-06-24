<?php
include '../config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Validation
    if (empty($fullname) || empty($email) || empty($phone) || empty($password) || empty($confirmPassword)) {
        echo "<script>alert('Please fill in all fields'); window.history.back();</script>";
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Please enter a valid email address'); window.history.back();</script>";
        exit();
    }

    if ($password !== $confirmPassword) {
        echo "<script>alert('Passwords do not match'); window.history.back();</script>";
        exit();
    }

    if (strlen($password) < 6) {
        echo "<script>alert('Password must be at least 6 characters'); window.history.back();</script>";
        exit();
    }

    // Check if email or phone already exists
    $check_sql = "SELECT * FROM users WHERE email = '$email' OR phone = '$phone'";
    $check_result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        echo "<script>alert('Email or phone number already registered'); window.history.back();</script>";
        exit();
    }

    // Hash password
    $hashed_password = md5($password);

    // Insert user
    $insert_sql = "INSERT INTO users (fullname, email, phone, password) VALUES ('$fullname', '$email', '$phone', '$hashed_password')";

    if (mysqli_query($conn, $insert_sql)) {
        echo "<script>alert('Registration successful! Please login.'); window.location.href='user_login.php';</script>";
    } else {
        echo "<script>alert('Registration failed. Please try again.'); window.history.back();</script>";
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration - HMMS</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="bg-shapes">
        <div class="shape shape-3"></div>
        <div class="shape shape-4"></div>
    </div>
    
    <div class="login-container">
        <div class="login-header">
            <a href="user_login.php" class="back-home">
                <i class="fas fa-arrow-left"></i> Back to Login
            </a>
            <div class="logo">
                <i class="fas fa-concierge-bell"></i>
                <span>HMMS Registration</span>
            </div>
        </div>
        
        <div class="login-card">
            <div class="login-icon">
                <i class="fas fa-user-plus"></i>
            </div>
            
            <h2>User Registration</h2>
            <p class="login-subtitle">Create your account to start ordering</p>
            
            <form id="registerForm" class="login-form" action="" method="post">
                <div class="form-group">
                    <label for="fullname">
                        <i class="fas fa-user"></i> Full Name
                    </label>
                    <input type="text" id="fullname" name="fullname" required 
                           placeholder="Enter your full name">
                </div>
                
                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope"></i> Email Address
                    </label>
                    <input type="email" id="email" name="email" required 
                           placeholder="Enter your email">
                </div>
                
                <div class="form-group">
                    <label for="phone">
                        <i class="fas fa-phone"></i> Phone Number
                    </label>
                    <input type="tel" id="phone" name="phone" required 
                           placeholder="Enter your phone number">
                </div>
                
                <div class="form-group">
                    <label for="regPassword">
                        <i class="fas fa-lock"></i> Password
                    </label>
                    <input type="password" id="regPassword" name="password" required 
                           placeholder="Create a password">
                    <button type="button" class="toggle-password" id="toggleRegPassword">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                
                <div class="form-group">
                    <label for="confirmPassword">
                        <i class="fas fa-lock"></i> Confirm Password
                    </label>
                    <input type="password" id="confirmPassword" name="confirmPassword" required 
                           placeholder="Confirm your password">
                </div>
                
                <button type="submit" value="users" class="submit-btn" >
                    <i class="fas fa-user-plus"></i> Create Account
                </button>
                
                <div class="login-footer">
                    <p>Already have an account? <a href="user_login.php" class="register-link">Login here</a></p>
                </div>
            </form>
        </div>
    </div>

    <script src="../js/user.js"></script>
</body>
</html>