<?php
session_start();
require_once 'config.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST['role'];
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $message = "❌ Passwords do not match!";
    } else {
        // Check if the email already exists
        $checkemail = mysqli_prepare($conn, "SELECT id FROM users WHERE role = ? AND email = ?");
        if ($checkemail) {
            mysqli_stmt_bind_param($checkemail, "ss", $role, $email);
            mysqli_stmt_execute($checkemail);
            mysqli_stmt_store_result($checkemail);

            if (mysqli_stmt_num_rows($checkemail) > 0) {
                $message = "❌ Email already registered!";
            }
            mysqli_stmt_close($checkemail);
        }

        // Check if the username already exists
        if (empty($message)) {
            $checkname = mysqli_prepare($conn, "SELECT id FROM users WHERE role = ? AND name = ?");
            if ($checkname) {
                mysqli_stmt_bind_param($checkname, "ss", $role, $name);
                mysqli_stmt_execute($checkname);
                mysqli_stmt_store_result($checkname);

                if (mysqli_stmt_num_rows($checkname) > 0) {
                    $message = "❌ Username already registered!";
                }
                mysqli_stmt_close($checkname);
            }
        }

        // If email and username are unique, proceed to insert the user
        if (empty($message)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = mysqli_prepare($conn, "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $hashed_password, $role);

                if (mysqli_stmt_execute($stmt)) {
                    // Also insert into users_info or admins_info
                    if ($role === 'admin') {
                        $admin_stmt = mysqli_prepare($conn, "INSERT INTO admins_info (name, email, password, role) VALUES (?, ?, ?, 'admin')");
                        if ($admin_stmt) {
                            mysqli_stmt_bind_param($admin_stmt, "sss", $name, $email, $hashed_password);
                            mysqli_stmt_execute($admin_stmt);
                            mysqli_stmt_close($admin_stmt);
                        }
                    } else {
                        $user_stmt = mysqli_prepare($conn, "INSERT INTO users_info (name, email, password, role) VALUES (?, ?, ?, 'user')");
                        if ($user_stmt) {
                            mysqli_stmt_bind_param($user_stmt, "sss", $name, $email, $hashed_password);
                            mysqli_stmt_execute($user_stmt);
                            mysqli_stmt_close($user_stmt);
                        }
                    }
                    $message = "✅ Registration successful! Please log in.";
                    echo "<script>
                            setTimeout(() => window.location.href = 'login.php', 2000);
                          </script>";
                } else {
                    $message = "❌ Registration failed!";
                }
                mysqli_stmt_close($stmt);
            }
        }
    }
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
                <li><a href="registration.php" class="active">Register</a></li>
                <li id="lg-bag"><a href="cart.php"><i class="fas fa-cart-shopping"></i></a></li>
            </ul>
        </div>
        <div id="mobile">
            <a href="cart.php"><i class="fas fa-cart-shopping"></i></a>
            <i id="bar" class="fas fa-bars"></i>
        </div>
    </section>

    <section id="page-header" class="about-header">
        <h2>#Register</h2>
        <p>Create your new account!</p>
    </section>

    <section id="form-details">
        <form action="registration.php" method="POST">
            <span>CREATE AN ACCOUNT</span>
            <h2>Join Us!</h2>
            <fieldset id="product-stock-fieldset">
                <legend>Role</legend>
                <label for="role">Select your role:</label>
                <select name="role" required>
                    <option value="user" selected>User</option>
                    <option value="admin">Admin</option>
                </select>
            </fieldset>
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required minlength="4">
            <input type="password" name="confirm_password" placeholder="Confirm Password" required minlength="4">

            <button class="normal">Register</button>
            <p>Already have an account? <a href="login.php">Login here</a></p>
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
    <script src="script.js" defer></script>
</body>

</html>