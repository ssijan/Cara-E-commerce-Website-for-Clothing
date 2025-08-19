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
                <li><a href="shop.php" class="active">Shop</a></li>
                <li><a href="blogs.php">Blogs</a></li>
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


    <?php
    include('config.php');
    $product = null;
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();
        }
        $stmt->close();
    }
    ?>
    <section id="prodetails" class="section-p1">
        <?php if ($product): ?>
        <div class="single-pro-image" style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" width="80%" style="max-width:350px; border-radius:12px; box-shadow:0 4px 24px rgba(0,0,0,0.08); margin-bottom:20px;" class="main-image" id="mainimage" alt="<?php echo htmlspecialchars($product['name']); ?>">
        </div>
        <div class="single-pro-details" style="background:#fff; border-radius:12px; box-shadow:0 4px 24px rgba(0,0,0,0.08); padding:32px 32px 24px 32px; margin-left:24px;">
            <h5 style="color:#088178; font-size:16px; font-weight:700; margin-bottom:8px;">Product ID: <?php echo $product['id']; ?></h5>
            <h2 style="font-size:32px; font-weight:800; margin-bottom:8px; color:#222;"> <?php echo htmlspecialchars($product['name']); ?> </h2>
            <h3 style="font-size:28px; color:#088178; font-weight:700; margin-bottom:16px;">$<?php echo number_format($product['price'], 2); ?></h3>
            <div style="margin-bottom:16px;">
                <span style="font-size:15px; font-weight:600; color:#555;">Stock: </span>
                <?php if(strtolower($product['stock']) === 'yes'): ?>
                    <span style="color:#28a745; font-weight:700;">In Stock</span>
                <?php else: ?>
                    <span style="color:#dc3545; font-weight:700;">Out of Stock</span>
                <?php endif; ?>
            </div>
            <form method="post" action="cart.php" style="margin-bottom:18px;">
                <div class="grp" style="display:flex; align-items:center; gap:10px; margin-bottom:12px;">
                    <label for="size" style="font-weight:600;">Size:</label>
                    <select name="size" id="size" style="padding:6px 12px; border-radius:6px; border:1px solid #ccc;">
                        <option value="S">S</option>
                        <option value="M">M</option>
                        <option value="L">L</option>
                        <option value="XL">XL</option>
                        <option value="XXL">XXL</option>
                    </select>
                    <label for="product-qty" style="font-weight:600;">Qty:</label>
                    <input type="number" name="quantity" value="1" min="1" id="product-qty" style="width:60px; padding:6px; border-radius:6px; border:1px solid #ccc;">
                </div>
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <button type="submit" class="normal" style="background:#088178; color:#fff; font-size:16px; font-weight:700; border-radius:6px; padding:12px 32px;">Add To Cart</button>
            </form>
            <h4 style="font-size:20px; font-weight:700; margin-bottom:8px; color:#222;">Product Details</h4>
            <span style="font-size:16px; color:#555; line-height:1.7;"> <?php echo htmlspecialchars($product['description']); ?> </span>
        </div>
        <?php else: ?>
        <div class="single-pro-details">
            <h4>Product not found.</h4>
        </div>
        <?php endif; ?>
    </section>

    <section id="product1" class="section-p1">
        <h2>Feature Products</h2>
        <p>Sumer Collection New Morden Design</p>
        <div class="pro-container">



            <div class="pro">
                <img src="img/products/n5.jpg" alt="">
                <div class="des">
                    <span>adidas</span>
                    <h5>Cartoon Astronaut T-Shirts</h5>
                    <div class="star">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <h4>$100</h4>
                </div>
                <a href="#"><i class="fas fa-shopping-cart cart"></i></a>
            </div>
            <div class="pro">
                <img src="img/products/n6.jpg" alt="">
                <div class="des">
                    <span>adidas</span>
                    <h5>Cartoon Astronaut T-Shirts</h5>
                    <div class="star">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <h4>$100</h4>
                </div>
                <a href="#"><i class="fas fa-shopping-cart cart"></i></a>
            </div>
            <div class="pro">
                <img src="img/products/n7.jpg" alt="">
                <div class="des">
                    <span>adidas</span>
                    <h5>Cartoon Astronaut T-Shirts</h5>
                    <div class="star">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <h4>$100</h4>
                </div>
                <a href="#"><i class="fas fa-shopping-cart cart"></i></a>
            </div>
            <div class="pro">
                <img src="img/products/n8.jpg" alt="">
                <div class="des">
                    <span>adidas</span>
                    <h5>Cartoon Astronaut T-Shirts</h5>
                    <div class="star">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <h4>$100</h4>
                </div>
                <a href="#"><i class="fas fa-shopping-cart cart"></i></a>
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

    <script>
        var mainimage = document.getElementById("mainimage");
        var smallImage = document.getElementsByClassName('small-img');
        for (let i = 0; i < smallImage.length; i++) {
            smallImage[i].onclick = function() {
                mainimage.src = smallImage[i].src;
            }
        }
    </script>

    <script src="script.js"></script>
</body>

</html>