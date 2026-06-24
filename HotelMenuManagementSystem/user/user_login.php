<?php
include '../config.php';
session_start();



if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $email=mysqli_real_escape_string($conn,$_POST['email']);
    $password=mysqli_real_escape_string($conn,md5($_POST['password']) );

    $select_users=mysqli_query($conn,"SELECT * FROM `users` WHERE email='$email' AND password='$password'") or die('query failed');

    if(mysqli_num_rows($select_users) > 0){
        $row=mysqli_fetch_assoc($select_users);

        
        $_SESSION['user_name']=$row['fullname'];
        $_SESSION['user_email']=$row['email'];
        $_SESSION['user_id']=$row['id'];
        header('location:user_dashboard.php');
        
    }else{
        $message[]='Incorrect email or password';
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login - HMMS</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
    </div>
    
    <div class="login-container">
        <div class="login-header">
            <a href="../index.php" class="back-home">
                <i class="fas fa-arrow-left"></i> Back to Home
            </a>
            <div class="logo">
                <i class="fas fa-concierge-bell"></i>
                <span>HMMS User</span>
            </div>
        </div>
        
        <div class="login-card">
            <div class="login-icon">
                <i class="fas fa-user-circle"></i>
            </div>
            
            <h2>User Login</h2>
            <?php
            if(isset($message)){
                foreach($message as $msg){
                    echo '<div class="message">'.$msg.'</div>';
                }
            }
            ?>
            <p class="login-subtitle">Access your account to browse menus and place orders</p>
            
            <form id="userLoginForm" class="login-form" action="" method="POST">
                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-user"></i> Username or Email
                    </label>
                    <input type="text" id="email" name="email" required 
                           placeholder="Enter your username or email">
                </div>
                
                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i> Password
                    </label>
                    <input type="password" id="password" name="password" required 
                           placeholder="Enter your password">
                    <button type="button" class="toggle-password" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                
                <div class="form-options">
                    <label class="checkbox">
                        <input type="checkbox" name="remember">
                        <span>Remember me</span>
                    </label>
                    <a href="#" class="forgot-password">Forgot Password?</a>
                </div>
                
                <button type="submit" class="submit-btn">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
                
                <div class="login-footer">
                    <p>Don't have an account? <a href="user_register.php" class="register-link">Register here</a></p>
                </div>
            </form>
        </div>
        
        <div class="login-features">
            <h3>User Benefits</h3>
            <div class="features-list">
                <div class="feature-item">
                    <i class="fas fa-search"></i>
                    <span>Browse complete menus</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-star"></i>
                    <span>View recommendations</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Place orders easily</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-history"></i>
                    <span>Track order history</span>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/user.js"></script>
</body>
</html>