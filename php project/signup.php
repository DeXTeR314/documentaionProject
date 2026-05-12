<?php
$user = 0;
$email_exists = 0;
$password_error = 0;
$input_error = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'connect.php'; // Ensure this file is correctly set up with your database connection
    $username = $_POST['username'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];

    // Basic input validation
    if (strlen($password) <= 8) {
        $password_error = "Password must be greater than 8 characters.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $input_error = "Invalid email format.";
    } elseif (!preg_match('/^\d{10}$/', $phone)) {
        $input_error = 1;
    } else {
        // Check if username or email already exists
        $sql_user_check = "SELECT * FROM registration WHERE username = '$username'";
        $sql_email_check = "SELECT * FROM registration WHERE email = '$email'";

        $user_result = mysqli_query($con, $sql_user_check);
        $email_result = mysqli_query($con, $sql_email_check);

        if ($user_result && mysqli_num_rows($user_result) > 0) {
            $user = 1; // Username already exists
        } elseif ($email_result && mysqli_num_rows($email_result) > 0) {
            $email_exists = 1; // Email already exists
        } else {
            // Insert new user data into the database
            $sql = "INSERT INTO registration (username, password, phone, email, gender) 
                    VALUES ('$username', '$password', '$phone', '$email', '$gender')";
            $result = mysqli_query($con, $sql);
            if ($result) {
                session_start();
                $_SESSION['username'] = $username;
                header('location:user_home.php');
            } else {
                die(mysqli_error($con));
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="signup-container">
        <?php
        if ($user) {
            echo '<div class="alert alert-danger">
                The username already exists. Please choose another.
            </div>';
        }
        if ($email_exists) {
            echo '<div class="alert alert-danger">
                The email address is already registered. Please use another email.
            </div>';
        }
        if ($password_error) {
            echo '<div class="alert alert-danger"> Password must be greater than 8 characters. </div>';
        }
        if ($input_error) {
            echo '<div class="alert alert-danger">Phone number must be 10 digits.</div>';
        }
        ?>
        <h1>Create Account</h1>
        <form action="signup.php" method="post">
            <label for="username">Username</label>
            <input type="text" name="username" placeholder="Enter your username" required>

            <label for="password">Password</label>
            <input type="password" name="password" placeholder="Enter your password" required>

            <label for="phone">Phone Number</label>
            <input type="text" name="phone" placeholder="Enter your phone number" required>

            <label for="email">Email</label>
            <input type="email" name="email" placeholder="Enter your email" required>

            <label for="gender">Gender</label>
            <select name="gender" required>
                <option value="">Select your gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>

            <button type="submit">Sign Up</button>
        </form>
        <p>Already have an account? <a href="log in.php">Login here</a></p>
    </div>
</body>
</html>
