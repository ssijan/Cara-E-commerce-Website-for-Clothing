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
                <li><a href="blogs.php">Blogs</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php" class="active">Contact</a></li>

                <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                    <li><a href="admin.php">Dashboard</a></li>
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

    <section id="page-header" class="about-header">
        <h2>#Let's Talk</h2>
        <p>LEAVE A MESSAGE, WE LOVE TO HEAR FROM YOU!</p>

    </section>

    <section id="contact-details" class="section-p1">
        <div class="details">
            <span>Get In Touch</span>
            <h2>Visit one of our agency locations or contact us today!</h2>
            <h3>Head Office</h3>
            <div class="info">
                <i class="fas fa-map-marker-alt"></i>
                <p>Daffodil International University, Ashuliya, Dhaka, Bangladesh</p>
            </div>
            <div class="info">
                <i class="fas fa-envelope"></i>
                <p>contact@example.com</p>
            </div>
            <div class="info">
                <i class="fas fa-phone-alt"></i>
                <p>+8801708366765 / +8801315483288</p>
            </div>
            <div class="info">
                <i class="fas fa-clock"></i>
                <p>Mon - Sun: 10:00 - 18:00</p>
            </div>
        </div>
        <div class="map">
            <iframe <
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d29186.869679514402!2d90.30264973955076!3d23.8768956!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755b8ada2664e21%3A0x3c872fd17bc11ddb!2sDaffodil%20International%20University!5e0!3m2!1sen!2sbd!4v1753890359827!5m2!1sen!2sbd"
                width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </section>

    <section id="contact-form" class="section-p1">
        <form action="">
            <span>Leave a Message</span>
            <h2>We love to hear from you!</h2>
            <div class="form">
                <div class="input-box">
                    <label for="name">Your Name</label>
                    <input type="text" id="name" placeholder="Enter your name" required>
                </div>
                <div class="input-box">
                    <label for="email">Your Email</label>
                    <input type="email" id="email" placeholder="Enter your email" required>
                </div>
                <div class="input-box">
                    <label for="subject">Subject</label>
                    <input type="text" id="subject" placeholder="Enter subject" required>
                </div>
                <div class="input-box message-box">
                    <label for="message">Your Message</label>
                    <textarea id="message" placeholder="Write your message here..." required></textarea>
                </div>
                <button type="submit">Send Message</button>
            </div>
        </form>
        <div class="people">
            <div>
                <img src="img/people/jinia.jpeg" alt="">
                <p><span>Jarin</span><br>Manager <br>Phone: +8805454524 <br>Email: jarin@gmail.com </p>
            </div>
            <div>
                <img src="img/people/sijan.jpg" alt="">
                <p><span>Sijan</span><br>Sales Executive<br>Phone: +8805454524 <br>Email: sijan@gmail.com </p>
            </div>
            <div>
                <img src="img/people/3.png" alt="">
                <p><span>Emma</span><br>Customer Support Executive<br>Phone: +8805454524 <br>Email: emma@gmail.com </p>
            </div>
        </div>

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