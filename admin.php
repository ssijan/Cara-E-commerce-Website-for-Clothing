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
    <title>Cara</title>
    <link rel="stylesheet" href="adminstyle.css">
</head>

<body>
    <section id="header">
        <a href="home.php"><img src="img/logo.png" class="logo" alt="Cara Logo"></a>
        <div class="container">
            <ul id="navbar">
                <i id="close" class="fas fa-times"></i>
                <li><a href="home.php">Home</a></li>
                <li><a href="shop.php">Shop</a></li>
                <li><a href="blogs.php">Blogs</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>

                <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                    <li><a href="admin.php" class="active">Dashboard</a></li>
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
        <div class="cont">
            <h1>Product Management</h1>

            <!-- Operation Select -->
            <section class="form-container">
                <div class="operation">
                    <h2>Select Operation</h2>
                    <select id="operation-select" onchange="showForm()">
                        <option value="none">-- Select Operation --</option>
                        <option value="add">Add New Product</option>
                        <option value="update">Update Product</option>
                        <option value="delete">Delete Product</option>
                    </select>
                </div>
            </section>

            <!-- Add New Product Form -->
            <section class="form-container" id="add-form" style="display: none;">
                <h2>Add New Product</h2>
                <form id="add-product-form" enctype="multipart/form-data">
                    <input type="text" id="product-name" placeholder="Product Name" required>
                    <input type="text" id="product-description" placeholder="Description" required>
                    <input type="number" id="product-price" placeholder="Price" required>

                    <!-- Stock Quantity as Yes or No -->
                    <fieldset id="product-stock-fieldset">
                        <legend>Product Stock Availability</legend>
                        <label for="product-stock">In Stock:</label>
                        <input type="radio" id="stock-yes" name="stock" value="yes" required> Yes
                        <input type="radio" id="stock-no" name="stock" value="no"> No
                    </fieldset>

                    <input type="file" id="product-image" required>
                    <button type="submit">Add Product</button>
                </form>
            </section>

            <!-- Update Product Form -->
            <section class="form-container" id="update-form" style="display: none;">
                <h2>Update Product</h2>
                <form id="update-product-form">
                    <input type="number" id="product-id" placeholder="Product ID" required>
                    <input type="text" id="product-name" placeholder="New Product Name" required>
                    <input type="text" id="product-description" placeholder="Description" required>
                    <input type="number" id="product-price" placeholder="Price" required>

                    <!-- Stock Quantity as Yes or No -->
                    <fieldset id="product-stock-fieldset">
                        <legend>Product Stock Availability</legend>
                        <label for="product-stock">In Stock:</label>
                        <input type="radio" id="stock-yes" name="stock" value="yes" required> Yes
                        <input type="radio" id="stock-no" name="stock" value="no"> No
                    </fieldset>

                    <input type="file" id="product-image" required>
                    <button type="submit">Update Product</button>
                </form>
            </section>

            <!-- Delete Product Form -->
            <section class="form-container" id="delete-form" style="display: none;">
                <h2>Delete Product</h2>
                <form id="delete-product-form">
                    <input type="number" id="delete-product-id" placeholder="Product ID to Delete" required>
                    <button type="submit">Delete Product</button>
                </form>
            </section>
        </div>
    </section>


    <script src="script.js"></script>

    

</body>

</html>