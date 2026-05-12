<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('location:log in.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_name = $_FILES['image']['name'];
        $image_path = 'uploads/' . $image_name;
        move_uploaded_file($image_tmp, $image_path);
    } 
    else {
        $image_path = ''; // If no image was uploaded
    }

    $sql = "INSERT INTO products (name, description, price, stock, image) 
    VALUES ('$name', '$description', '$price', '$stock', '$image_path')";

    if (mysqli_query($con, $sql)) {
    echo "Product added successfully!";
    } else {
    echo "Error: " . mysqli_error($con);
    }
    header('location:admin_home.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Add Product</h1>
        <form action="add_product.php" method="post" enctype="multipart/form-data">
            <label for="name">Product Name</label>
            <input type="text" name="name" required>

            <label for="description">Description</label>
            <textarea name="description" required></textarea>

            <label for="price">Price</label>
            <input type="number" name="price" value="<?= $product['price']; ?>" required>

            <label for="stock">Stock</label>
            <input type="number" name="stock" required>

            <label for="image">Product Image</label>
            <input type="file" name="image" accept="image/*" required>

            <button type="submit">Add Product</button>
        </form>

        <!-- Cancel Button -->
        <a href="admin_home.php" class="cancel-button">Cancel</a>
    </div>
</body>
</html>

