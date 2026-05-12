<?php
session_start();
include 'connect.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('location:login.php');
    exit;
}

// Fetch product details if an ID is passed
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $product_query = "SELECT * FROM products WHERE id = $id";
    $product_result = mysqli_query($con, $product_query);
    $product = mysqli_fetch_assoc($product_result);

    // If product not found, redirect
    if (!$product) {
        header('location:admin_home.php');
        exit;
    }
}

// Handle product update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    // Update product
    $update_query = "UPDATE products SET name='$name', description='$description', price='$price', stock='$stock' WHERE id = $id";
    if (mysqli_query($con, $update_query)) {
        header('location:admin_home.php');
    } else {
        echo 'Error updating product: ' . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="container">
        <h1>Edit Product</h1>
        <form action="edit_product.php?id=<?= $product['id']; ?>" method="post">
            <label for="name">Name</label>
            <input type="text" name="name" value="<?= $product['name']; ?>" required>

            <label for="description">Description</label>
            <textarea name="description" required><?= $product['description']; ?></textarea>

            <label for="price">Price</label>
            <input type="number" name="price" value="<?= $product['price']; ?>" required>

            <label for="stock">Stock</label>
            <input type="number" name="stock" value="<?= $product['stock']; ?>" required>

            <button type="submit">Update Product</button>
        </form>
        <a href="admin_home.php" class="btn">Back to Admin Dashboard</a>
    </div>
</body>
</html>
