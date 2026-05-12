<?php
$login = 0;
$invalid = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'connect.php';
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Use prepared statements for security
    $sql = "SELECT * FROM registration WHERE username = ? AND password = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $login = 1;
        session_start();
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on role
        if ($user['role'] === 'admin') {
            header('location:admin_home.php');
        } else {
            header('location:user_home.php');
        }
    } else {
        $invalid = 1;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="signup-container">
        <?php if ($invalid): ?>
            <div class="alert alert-danger">
                Wrong Username or Password.
            </div>
        <?php endif; ?>

        <?php if ($login): ?>
            <div class="alert alert-success">
                You successfully logged in.
            </div>
        <?php endif; ?>

        <h1>Log in</h1>
        <form action="log in.php" method="post">
            <label for="username">Username</label>
            <input type="text" name="username" placeholder="Enter your username" required>

            <label for="password">Password</label>
            <input type="password" name="password" placeholder="Enter your password" required>

            <button type="submit">Log in</button>
        </form>
        <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
    </div>
</body>
</html>
