<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
include('config.php');

// Handle add to cart
// Handle coupon
// Coupon logic: persist coupon in session
$discount = 0;
$discount_percent = 0;
// Coupon logic: persist coupon in session and show error for invalid
$discount = 0;
$discount_percent = 0;
$coupon_error = '';
if (isset($_POST['apply_coupon'])) {
    $coupon = strtoupper(trim($_POST['coupon']));
    $stmt = $conn->prepare("SELECT discount_percent, valid FROM coupons WHERE coupon_code = ?");
    $stmt->bind_param("s", $coupon);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        if ($row['valid'] === 'yes') {
            $_SESSION['coupon'] = $coupon;
            $_SESSION['discount_percent'] = $row['discount_percent'];
            $_SESSION['coupon_error'] = ''; // No error
        } else {
            // Coupon is not valid
            $_SESSION['coupon'] = $coupon;
            $_SESSION['discount_percent'] = 0;
            $_SESSION['coupon_error'] = 'Coupon is not valid.';
        }
    } else {
        // Invalid coupon
        $_SESSION['coupon'] = $coupon;
        $_SESSION['discount_percent'] = 0;
        $_SESSION['coupon_error'] = 'Invalid coupon code.';
    }

    $stmt->close();
    header('Location: cart.php');
    exit();
}

if (isset($_SESSION['discount_percent'])) {
    $discount_percent = intval($_SESSION['discount_percent']);
}
if (isset($_SESSION['coupon_error'])) {
    $coupon_error = $_SESSION['coupon_error'];
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = intval($_POST['product_id']);
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    $size = isset($_POST['size']) ? $_POST['size'] : null;

    // Check if product already in cart
    $stmt = $conn->prepare("SELECT cart_id, quantity FROM cart_details WHERE user_id=? AND product_id=?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // Update quantity
        $row = $result->fetch_assoc();
        $new_qty = $row['quantity'] + $quantity;
        $update = $conn->prepare("UPDATE cart_details SET quantity=? WHERE cart_id=?");
        $update->bind_param("ii", $new_qty, $row['cart_id']);
        $update->execute();
        $update->close();
    } else {
        // Insert new cart item
        $insert = $conn->prepare("INSERT INTO cart_details (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $insert->bind_param("iii", $user_id, $product_id, $quantity);
        $insert->execute();
        $insert->close();
    }
    $stmt->close();
    // Redirect to avoid resubmission
    header('Location: cart.php');
    exit();
}
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
                <li><a href="contact.php">Contact</a></li>

                <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                    <li><a href="admin.php">Dashboard</a></li>
                <?php endif; ?>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="logout.php" class="active">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>

                <li id="lg-bag"><a href="cart.php" class="active"><i class="fas fa-cart-shopping"></i></a></li>
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

    <section id="cart" class="section-p1">
        <table width="100%">
            <thead>
                <tr>
                    <th>Remove</th>
                    <th>Image</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Sub-Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $user_id = $_SESSION['user_id'];
                $cart_items = [];
                $total = 0;
                $stmt = $conn->prepare("SELECT c.cart_id, c.product_id, c.quantity, p.name, p.price, p.image_url FROM cart_details c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    $subtotal = $row['price'] * $row['quantity'];
                    $total += $subtotal;
                    echo '<tr>';
                    echo '<td class="remove"><a href="cart.php?remove=' . $row['cart_id'] . '" onclick="return confirm(\'Remove this item?\');"><i class="fas fa-times"></i></a></td>';
                    echo '<td><img src="' . htmlspecialchars($row['image_url']) . '" alt="" style="width:60px; border-radius:8px;"></td>';
                    echo '<td class="product">' . htmlspecialchars($row['name']) . '</td>';
                    echo '<td class="price">$' . number_format($row['price'], 2) . '</td>';
                    // Editable quantity
                    // Quantity input and update button
                    echo '<td class="quantity">'
                        . '<input type="number" class="cart-qty" data-cartid="' . $row['cart_id'] . '" data-price="' . $row['price'] . '" value="' . $row['quantity'] . '" min="1" style="width:50px; text-align:center;">'
                        . '<button class="update-qty-btn" data-cartid="' . $row['cart_id'] . '" style="margin-left:5px;"></button>'
                        . '</td>';
                    // Subtotal cell
                    echo '<td class="sub-total">$' . number_format($row['price'] * $row['quantity'], 2) . '</td>';
                    echo '</tr>';
                }
                $stmt->close();
                // Always use session discount_percent for calculation
                $discount_percent = isset($_SESSION['discount_percent']) ? intval($_SESSION['discount_percent']) : 0;
                if ($discount_percent > 0) {
                    $discount = $total * ($discount_percent / 100);
                } else {
                    $discount = 0;
                }
                ?>
            </tbody>
        </table>
    </section>

    <section id="cart-total" class="section-p1">
        <div class="apply">
            <h2>Apply Coupon</h2>
            <p>Enter your coupon code if you have one.</p>
            <form id="coupon-form" autocomplete="off">
                <input type="text" name="coupon" id="coupon" placeholder="Enter your coupon code" value="<?php echo isset($_SESSION['coupon']) ? htmlspecialchars($_SESSION['coupon']) : ''; ?>">
                <button type="submit" id="apply-coupon" class="normal">Apply</button>
            </form>
            <p class="coupon-msg" style="font-weight:600;"></p>
        </div>

        <div class="cart-details">
            <h2>Cart Summary</h2>
            <table>
                <?php
                // Ensure all values are numbers
                $total = isset($total) ? floatval($total) : 0;
                $discount = isset($discount) ? floatval($discount) : 0;
                $shipping = ($total * 0.05);
                $grand_total = $total - $discount + $shipping;
                if ($grand_total < 0) {
                    $grand_total = 0;
                    $shipping = 0;
                }
                ?>
                <tr>
                    <td>Subtotal</td>
                    <td class="sub-total">$<?php echo number_format($total, 2); ?></td>
                </tr>
                <tr>
                    <td>Discount<?php echo $discount_percent > 0 ? " ({$discount_percent}%)" : ""; ?></td>
                    <td class="discount">$<?php echo number_format($discount, 2); ?></td>
                </tr>
                <tr>
                    <td>Shipping</td>
                    <td class="shipping">$<?php echo number_format($shipping, 2); ?></td>
                </tr>
                <tr class="total">
                    <td>Total</td>
                    <td class="grand-total">$<?php echo number_format($grand_total, 2); ?></td>
                </tr>
            </table>
            <form method="post" action="cart.php" style="margin-top:10px;">
                <input type="hidden" name="checkout" value="1">
                <button type="submit" class="normal">Proceed to Checkout</button>
            </form>
        </div>
    </section>

    <div style="height: 3px; width: 100%; 
  background: linear-gradient(to right, #ff7eb3, #ff758c, #ff7eb3); border-radius: 2px;"></div>


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
    <?php
    // Handle remove from cart
    if (isset($_GET['remove'])) {
        $cart_id = intval($_GET['remove']);
        $stmt = $conn->prepare("DELETE FROM cart_details WHERE cart_id=? AND user_id=?");
        $stmt->bind_param("ii", $cart_id, $_SESSION['user_id']);
        $stmt->execute();
        $stmt->close();
        echo "<script>window.location='cart.php';</script>";
        exit();
    }

    // Handle quantity update
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_qty'])) {
        $cart_id = intval($_POST['cart_id']);
        $quantity = max(1, intval($_POST['quantity']));
        $stmt = $conn->prepare("UPDATE cart_details SET quantity=? WHERE cart_id=? AND user_id=?");
        $stmt->bind_param("iii", $quantity, $cart_id, $_SESSION['user_id']);
        $stmt->execute();
        $stmt->close();
        header('Location: cart.php');
        exit();
    }

    // Handle checkout
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
        $user_id = $_SESSION['user_id'];
        // Get cart items
        $cart_stmt = $conn->prepare("SELECT product_id, quantity FROM cart_details WHERE user_id=?");
        $cart_stmt->bind_param("i", $user_id);
        $cart_stmt->execute();
        $cart_result = $cart_stmt->get_result();
        $cart_items = [];
        while ($row = $cart_result->fetch_assoc()) {
            $cart_items[] = $row;
        }
        $cart_stmt->close();

        if (count($cart_items) > 0) {
            // Create order
            $order_stmt = $conn->prepare("INSERT INTO orders (user_id, order_date) VALUES (?, NOW())");
            $order_stmt->bind_param("i", $user_id);
            $order_stmt->execute();
            $order_id = $order_stmt->insert_id;
            $order_stmt->close();

            // Insert order items
            $item_stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");
            foreach ($cart_items as $item) {
                $item_stmt->bind_param("iii", $order_id, $item['product_id'], $item['quantity']);
                $item_stmt->execute();
            }
            $item_stmt->close();
        }

        // Clear cart
        $stmt = $conn->prepare("DELETE FROM cart_details WHERE user_id=?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();
        unset($_SESSION['coupon']);
        unset($_SESSION['coupon_error']);
    // Redirect with a query parameter to show notification after reload
    echo "<script>window.location='cart.php?checkout=success';</script>";
        exit();
    }
    ?>

    <?php
    // Show notification if redirected after checkout
    if (isset($_GET['checkout']) && $_GET['checkout'] === 'success') {
        echo "<script>window.onload=function(){showNotification('Checkout successful!');};</script>";
    }
?>
</body>

</html>