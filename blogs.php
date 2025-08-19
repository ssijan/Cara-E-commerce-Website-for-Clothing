<?php
session_start();

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
                <li>
                    <form action="shop.php" method="get" id="search-form">
                        <input type="text" name="search" placeholder="Search products...">
                        <button type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </li>
                <i id="close" class="fas fa-times"></i>
                <li><a href="home.php">Home</a></li>
                <li><a href="shop.php">Shop</a></li>
                <li><a href="blogs.php" class="active">Blogs</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>

                <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                    <li><a href="admin.php" >Dashboard</a></li>
                <?php endif; ?>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="logout.php" class="active">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>

                <li id="lg-bag"><a href="cart.php"><i class="fas fa-cart-shopping"></i></a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="profile-menu">
                        <a href="#" class="profile-toggle">
                            <img src="https://randomuser.me/api/portraits/men/75.jpg" alt="User Avatar" class="user-avatar" />
                            <span class="username">John Doe</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
        <div id="mobile">
            <a href="cart.php"><i class="fas fa-cart-shopping"></i></a>
            <i id="bar" class="fas fa-bars"></i>
        </div>
    </section>

    <section id="page-header" class="blog-header">
        <h2>#readmore</h2>
        <p>Read Our Latest Blogs</p>
    </section>

    <section id="blog">
        <div class="blog-box">
            <div class="blog-img">
                <img src="img/blog/b1.jpg" alt="">
            </div>
            <div class="blog-details">
                <h4>The Cotton-Jersey Zip-up Hoodie</h4>
                <p>Kickstarer man braid godaard coloring book. Raceltte waistcoat selfies
                    yr wolf chartreuse hexagon iron, grodard...
                </p>
                <a href="#">CONTINUE READING</a>
            </div>
            <h1>13/01</h1>
        </div>
        <div class="blog-box">
            <div class="blog-img">
                <img src="img/blog/b2.jpg" alt="">
            </div>
            <div class="blog-details">
                <h4>How to Style a Quiff</h4>
                <p>Kickstarer man braid godaard coloring book. Raceltte waistcoat selfies
                    yr wolf chartreuse hexagon iron, grodard...
                </p>
                <a href="#">CONTINUE READING</a>
            </div>
            <h1>13/01</h1>
        </div>
        <div class="blog-box">
            <div class="blog-img">
                <img src="img/blog/b3.jpg" alt="">
            </div>
            <div class="blog-details">
                <h4>Must-Have Skater Girl Items</h4>
                <p>Kickstarer man braid godaard coloring book. Raceltte waistcoat selfies
                    yr wolf chartreuse hexagon iron, grodard...
                </p>
                <a href="#">CONTINUE READING</a>
            </div>
            <h1>13/01</h1>
        </div>
        <div class="blog-box">
            <div class="blog-img">
                <img src="img/blog/b4.jpg" alt="">
            </div>
            <div class="blog-details">
                <h4>The Cotton-Jersey Zip-up Hoodie</h4>
                <p>Kickstarer man braid godaard coloring book. Raceltte waistcoat selfies
                    yr wolf chartreuse hexagon iron, grodard...
                </p>
                <a href="#">CONTINUE READING</a>
            </div>
            <h1>13/01</h1>
        </div>
        <div class="blog-box">
            <div class="blog-img">
                <img src="img/blog/b5.jpg" alt="">
            </div>
            <div class="blog-details">
                <h4>Runway-Inspired Trends</h4>
                <p>Kickstarer man braid godaard coloring book. Raceltte waistcoat selfies
                    yr wolf chartreuse hexagon iron, grodard...
                </p>
                <a href="#">CONTINUE READING</a>
            </div>
            <h1>13/01</h1>
        </div>
        <div class="blog-box">
            <div class="blog-img">
                <img src="img/blog/b6.jpg" alt="">
            </div>
            <div class="blog-details">
                <h4>AW20 Menswear Trends</h4>
                <p>Kickstarer man braid godaard coloring book. Raceltte waistcoat selfies
                    yr wolf chartreuse hexagon iron, grodard...
                </p>
                <a href="#">CONTINUE READING</a>
            </div>
            <h1>13/01</h1>
        </div>
        <div class="blog-box">
            <div class="blog-img">
                <img src="img/blog/b7.jpg" alt="">
            </div>
            <div class="blog-details">
                <h4>How to Style a Quiff</h4>
                <p>Kickstarer man braid godaard coloring book. Raceltte waistcoat selfies
                    yr wolf chartreuse hexagon iron, grodard...
                </p>
                <a href="#">CONTINUE READING</a>
            </div>
            <h1>13/01</h1>
        </div>
    </section>

    <section id="pagination" class="section-p1">
        <a href="#"><i class="fas fa-long-arrow-alt-left"></i></a>
        <a href="#">1</a>
        <a href="#">2</a>
        <a href="#"><i class="fas fa-long-arrow-alt-right"></i></a>
    </section>

    <section id="newsletter" class="section-p1 section-m1">
        <div class="newstext">
            <h4>Sign Up For Newsletters</h4>
            <p>Get E-main updates about out latest shop and <span>special offers.</span></p>
        </div>
        <div class="form">
            <input type="text" placeholder="Your email address">
            <button class="normal">Sign Up</button>
        </div>
    </section>



    <footer class="section-p1">
        <div class="col">
            <img class="logo" src="img/logo.png" alt="">
            <h4>Contact</h4>
            <p><strong>Address:</strong> Daffodil International University, Ashuliya.</p>
            <p><strong>Phone:</strong> +8801708366765 / +8801315483288</p>
            <p><strong>Hours:</strong> 10:00 - 18, Mon - Sun</p>
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
                <a href="#"><img src="img/pay/app.jpg" alt=""></a>
                <a href="#"><img src="img/pay/play.jpg" alt=""></a>
            </div>
            <p>Secured Payment Gateways</p>
            <img src="img/pay/pay.png" alt="">
        </div>
        <div class="copyright">
            <p>© 2025, FemBro</p>
        </div>
    </footer>

    <script src="script.js"></script>
</body>

</html>