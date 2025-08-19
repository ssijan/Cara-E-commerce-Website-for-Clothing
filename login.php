<?php
session_start();
require_once 'config.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST['role'];
    $email_or_name = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = mysqli_prepare($conn, "SELECT id, password FROM users WHERE role = ? AND (email = ? OR name = ?)");
    mysqli_stmt_bind_param($stmt, "sss", $role, $email_or_name, $email_or_name);

    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        mysqli_stmt_bind_result($stmt, $id, $hashed_password);
        mysqli_stmt_fetch($stmt);

        if (password_verify($password, $hashed_password)) {
            $_SESSION['role'] = $role;
            $_SESSION['user_id'] = $id;
            $_SESSION['email'] = $email_or_name; 
            $message = "✅ Login successful!";
            header("Location: home.php?message=" . urlencode($message));
            exit();
        } else {
            $message = "❌ Incorrect password!";
        }
    } else {
        $message = "❌ Email or Username not registered!";
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Cara</title>
</head>

<body>
    <section id="header">
        <a href="home.php"><img src="img/logo.png" class="logo" alt="Cara Logo"></a>
        <div>
            <ul id="navbar">
                <i id="close" class="fas fa-times"></i>
                <li><a href="home.php">Home</a></li>
                <li><a href="shop.php">Shop</a></li>
                <li><a href="blogs.php">Blogs</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="logout.php" class="active">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php" class="active">Login</a></li>
                <?php endif; ?>

                <li id="lg-bag"><a href="cart.php"><i class="fas fa-cart-shopping"></i></a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="profile-menu">
                        <a href="#" class="profile-toggle">
                            <img src="https://randomuser.me/api/portraits/men/75.jpg" alt="User Avatar" class="user-avatar" />
                            <span class="username">John Doe</span>
                        </a>
                        <ul class="dropdown">
                            <li><a href="#">My Profile</a></li>
                            <li><a href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
        <div id="mobile">
            <a href="cart.php"><i class="fas fa-cart-shopping"></i></a>
            <i id="bar" class="fas fa-bars"></i>
        </div>
    </section>



    <section id="page-header" class="about-header">
        <h2>#Login</h2>
        <p>Log in to your account!</p>
    </section>

    <section id="form-details">
        <form action="" method="POST">
            <span>LOGIN TO YOUR ACCOUNT</span>
            <h2>Welcome Back!</h2>
            <fieldset id="product-stock-fieldset">
                <legend>Role</legend>
                <label for="role">Select your role:</label>
                <select name="role" required>
                    <option value="user" selected>User</option>
                    <option value="admin">Admin</option>
                </select>
            </fieldset>
            <input type="text" name="email" placeholder=" Username or Email" required>
            <input type="password" name="password" placeholder="Password" required minlength="4">
            <button class="normal">Login</button>
            <p>Don't have an account? <a href="registration.php">Register here</a></p>
        </form>
    </section>

    <div style="height: 3px; width: 100%; 
  background: linear-gradient(to right, #ff7eb3, #ff758c, #ff7eb3); border-radius: 2px;"></div>

    <footer class="section-p1">
        <div class="col">
            <img class="logo" src="img/logo.png" alt="Cara Logo">
            <h4>Contact</h4>
            <p><strong>Address:</strong> Daffodil International University, Ashuliya.</p>
            <p><strong>Phone:</strong> +8801708366765 / +8801315483288</p>
            <p><strong>Hours:</strong>10:00 - 18, Mon - Sun</p>
            <div class="follow">
                <h4>Follow Us</h4>
                <div class="icon">
                    <a href="https://www.facebook.com" target="blank"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://x.com" target="blank"><i class="fab fa-twitter"></i></a>
                    <a href="https://www.instagram.com" target="blank"><i class="fab fa-instagram"></i></a>
                    <a href="https://discord.com" target="blank"><i class="fab fa-discord"></i></a>
                    <a href="https://www.youtube.com" target="blank"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>
        <div class="col">
            <h4>About</h4>
            <a href="#">About us</a>
            <a href="#">Delivery Informations</a>
            <a href="#">Privacy & Policy</a>
            <a href="#">Terms & Conditions</a>
            <a href="#">Contact Us</a>
        </div>
        <div class="col">
            <h4>My Account</h4>
            <a href="#">Sign In</a>
            <a href="#">View Cart</a>
            <a href="#">My Wishlisht</a>
            <a href="#">Track My Order</a>
            <a href="#">Help</a>
        </div>
        <div class="col install">
            <h4>Install App</h4>
            <p>Form App Store or Google Play</p>
            <div class="row">
                <a href="#"><img src="img/pay/app.jpg" alt="App Store"></a>
                <a href="#"><img src="img/pay/play.jpg" alt="Google Play"></a>
            </div>
            <p>Secured Payment Gateways</p>
            <img src="img/pay/pay.png" alt="Payment Gateways">
        </div>
        <div class="copyright">
            <p>© 2025, FemBro</p>
        </div>
    </footer>

    <?php if (!empty($message)): ?>
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                showNotification("<?php echo $message; ?>");
            });
        </script>
    <?php endif; ?>

    <script src="script.js"></script>
</body>

</html>