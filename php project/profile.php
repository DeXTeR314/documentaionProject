<?php
session_start();
include 'connect.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('location:log in.php');
    exit;
}

// Fetch the user's data from the database
$username = $_SESSION['username'];
$sql = "SELECT * FROM registration WHERE username = '$username'";
$result = mysqli_query($con, $sql);
$user = mysqli_fetch_assoc($result);

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_email = mysqli_real_escape_string($con, $_POST['email']);
    $new_phone = mysqli_real_escape_string($con, $_POST['phone']);

    // Validate phone number length (must be exactly 10 digits)
    if (strlen($new_phone) !== 10 || !ctype_digit($new_phone)) {
        $error_message = "Phone number must be exactly 10 digits.";
    } else {
        // Check if the new email already exists in the database
        $check_email_sql = "SELECT * FROM registration WHERE email = '$new_email' AND username != '$username'";
        $check_email_result = mysqli_query($con, $check_email_sql);

        if (mysqli_num_rows($check_email_result) > 0) {
            $error_message = "The email address is already in use.";
        } else {
            // Update user data in the database
            $update_sql = "UPDATE registration SET email = '$new_email', phone = '$new_phone' WHERE username = '$username'";
            if (mysqli_query($con, $update_sql)) {
                header('Location: profile.php'); // Refresh page after successful update
                exit;
            } else {
                $error_message = "Error updating profile.";
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
    <title>User Profile</title>
    <link rel="stylesheet" href="profile.css">
</head>
<body>
    <!-- Profile Content -->
    <div class="container">
        <h1>Welcome to Your Profile, <?= htmlspecialchars($user['username']); ?>!</h1>
        
        <!-- Error message -->
        <?php if (isset($error_message)) : ?>
            <p class="error"><?= htmlspecialchars($error_message); ?></p>
        <?php endif; ?>
        
        <!-- Profile Form -->
        <div class="profile-details">
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username"><strong>Username:</strong></label>
                    <input type="text" id="username" value="<?= htmlspecialchars($user['username']); ?>" disabled>
                </div>
                <div class="form-group">
                    <label for="email"><strong>Email:</strong></label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="phone"><strong>Phone:</strong></label>
                    <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($user['phone']); ?>" required maxlength="10" minlength="10" pattern="\d{10}" title="Phone number must be exactly 10 digits.">
                </div>

                <div class="form-group">
                    <label for="gender"><strong>Gender:</strong></label>
                    <input type="text" id="gender" value="<?= ucfirst(htmlspecialchars($user['gender'])); ?>" disabled>
                </div>
                <div class="form-group">
                    <label for="role"><strong>Role:</strong></label>
                    <input type="text" id="role" value="<?= ucfirst(htmlspecialchars($user['role'])); ?>" disabled>
                </div>

                <button type="submit" class="button">Update Profile</button>
            </form>
        </div>

        <div class="button">
    <?php if ($user['role'] === 'admin'): ?>
        <a href="admin_home.php" class="button">Back to home page</a>
    <?php else: ?>
        <a href="user_home.php" class="button">Back to home page</a>
    <?php endif; ?>
</div>




        <div class="home-button">
    <a href="log in.php" class="btn logout-btn">Log Out</a>
</div>


    </div>
</body>
</html>

