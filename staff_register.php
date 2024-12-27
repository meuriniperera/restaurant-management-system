<?php 
include 'connection.php'; // Include your database connection

$message = [];

// Register staff
if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Sanitize input
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $password = filter_var($password, FILTER_SANITIZE_STRING);
    $confirm_password = filter_var($confirm_password, FILTER_SANITIZE_STRING);
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message[] = 'Invalid email format';
    }
    
    // Check if passwords match
    if ($password !== $confirm_password) {
        $message[] = 'Passwords do not match';
    }
    
    // Check if email already exists
    $select_staff = $conn->prepare("SELECT * FROM `staff` WHERE email = ?");
    $select_staff->execute([$email]);
    
    if ($select_staff->rowCount() > 0) {
        $message[] = 'Email already registered';
    }
    
    // If no errors, insert into database
    if (empty($message)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $insert_staff = $conn->prepare("INSERT INTO `staff` (name, email, password) VALUES (?, ?, ?)");
        $insert_staff->execute([$name, $email, $hashed_password]);
        
        if ($insert_staff) {
            $message[] = 'Staff registered successfully';
            header("Location: staff_dashboard.php"); // Redirect to staff dashboard or login page
            exit;
        } else {
            $message[] = 'Failed to register staff';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Gallery Cafe - Staff Register</title>
    <link rel="stylesheet" href="style.css"> <!-- Link your stylesheet -->
</head>
<body>
    <div class="form-container">
        <section class="form-container">
            <div class="title">
                <img src="img/download.jpg" alt="Gallery Cafe">
                <h1>Staff Register</h1>
                <p>Register now to access the staff dashboard!</p>
            </div>
            <?php if (!empty($message)): ?>
                <div class="message-container">
                    <?php foreach ($message as $msg): ?>
                        <p class="error-message"><?php echo htmlspecialchars($msg); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <form action="" method="post">
                <div class="input-field">
                    <p>Your Name*</p>
                    <input type="text" name="name" required placeholder="Enter your name" maxlength="50">
                </div>
                <div class="input-field">
                    <p>Your Email*</p>
                    <input type="email" name="email" required placeholder="Enter your email" maxlength="50">
                </div>
                <div class="input-field">
                    <p>Your Password*</p>
                    <input type="password" name="password" required placeholder="Enter your password" maxlength="50">
                </div>
                <div class="input-field">
                    <p>Confirm Password*</p>
                    <input type="password" name="confirm_password" required placeholder="Confirm your password" maxlength="50">
                </div>
                <input type="submit" name="register" value="Register" class="btn">
                <p>Already have an account? <a href="staff_login.php">Login Here</a></p>
            </form>
        </section>
    </div>
</body>
</html>
