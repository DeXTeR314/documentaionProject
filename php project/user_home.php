<?php
session_start();
include 'connect.php';

// Check if user is logged in and is a regular user
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header('location:log in.php');
    exit;
}

// Fetch all products
$products_query = "SELECT * FROM products WHERE stock > 0";
$products_result = mysqli_query($con, $products_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Home</title>
    <link rel="stylesheet" href="user.css">
</head>
<body>
    <header>
        <div class="header-left">
            <img src="uploads/logo.jpg">
            <nav>
                <ul>
                
                    <li><a href="user_home.php">Local Shop</a></li>
                    <li><a href="user_home.php">Home</a></li>
                    <li><a href="profile.php"><?= isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?></a></li>
                    

                </ul>
            </nav>
        </div>

        <!-- Search Bar in the Center -->
        <div class="header-center">
            <form action="user_home.php" method="get" class="search-form">
                <input type="text" name="search" placeholder="Search for products..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button type="submit">Search</button>
            </form>
        </div>

        <!-- User Name on the Right -->
        <div class="header-right">
            <ul>
        <li><a href="logout.php">Log Out</a></li>
        </ul>
        </div>
    </header>

    <div class="container">
        <h2>Available Products</h2>
        <div class="products">
            <?php
            // Fetch the search query if it's set
            $search = isset($_GET['search']) ? mysqli_real_escape_string($con, $_GET['search']) : '';

            // Modify the product query to include the search condition
            if ($search) {
                $products_query = "SELECT * FROM products WHERE stock > 0 AND name LIKE '%$search%'";
            } else {
                $products_query = "SELECT * FROM products WHERE stock > 0";
            }

            $products_result = mysqli_query($con, $products_query);

            // Check if the query returned any products
            if (mysqli_num_rows($products_result) > 0) :
                while ($row = mysqli_fetch_assoc($products_result)) :
            ?>
                    <div class="product">
                        <!-- Display product image if exists -->
                        <?php if (!empty($row['image'])): ?>
                            <img src="<?= $row['image']; ?>" alt="<?= $row['name']; ?>" class="product-image">
                        <?php endif; ?>

                        <h3><?= $row['name']; ?></h3>
                        <p><?= $row['description']; ?></p>
                        <p>Price: $<?= $row['price']; ?></p>
                        <p><?= $row['stock']; ?> Left</p>
                        <a href="https://www.instagram.com/direct/t/115475483180483" class="btn" target="_blank">Order Now</a>
                    </div>
            <?php
                endwhile;
            else :
                echo "<p>No products found.</p>";
            endif;
            ?>
        </div>
    </div>
</body>
</html>

