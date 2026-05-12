<?php
session_start();
include 'connect.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('location:log in.php');
    exit;
}

// Handle product deletion
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $delete_query = "DELETE FROM products WHERE id = $id";
    mysqli_query($con, $delete_query);
}

// Fetch all products
$products_query = "SELECT * FROM products";
$products_result = mysqli_query($con, $products_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Home</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="container">
        <!-- Profile Button -->
        <div class="profile-link">
            <a href="profile.php" class="btn">Go to Profile</a>
            <a href="logout.php" class="btn">Log Out</a>
        </div>

        <h1>Admin Dashboard</h1>
        <a href="add_product.php" class="btn">Add New Product</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($products_result)) : ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><?= $row['name']; ?></td>
                        <td><?= $row['description']; ?></td>
                        <td><?= $row['price']; ?></td>
                        <td><?= $row['stock']; ?></td>
                        <td>
                            <?php if ($row['image']): ?>
                                <img src="<?= $row['image']; ?>" alt="<?= $row['name']; ?>" class="product-image" width="100">
                            <?php else: ?>
                                <p>No image</p>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="edit_product.php?id=<?= $row['id']; ?>" class="btn">Edit</a>
                            <a href="?delete=<?= $row['id']; ?>" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
