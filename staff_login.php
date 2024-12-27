<?php 
include 'connection.php';
session_start();

if (isset($_SESSION['staff_id'])) {
    $staff_id = $_SESSION['staff_id'];
} else {
    $staff_id = '';
}

$message = [];

// Login staff
if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $pass = $_POST['pass'];
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);
    
    $select_staff = $conn->prepare("SELECT * FROM `staff` WHERE email = ?");
    $select_staff->execute([$email]);
    $row = $select_staff->fetch(PDO::FETCH_ASSOC);

    if ($select_staff->rowCount() > 0 && password_verify($pass, $row['password'])) {
        $_SESSION['staff_id'] = $row['id'];
        $_SESSION['staff_name'] = $row['name'];
        $_SESSION['staff_email'] = $row['email'];
        header("Location: staff_dashboard.php");
        exit;
    } else {
        $message[] = 'Incorrect Email or Password';
        echo 'Incorrect Email or Password';
    } 
}
?>
<style type="text/css">
    <?php include 'style.css'; ?>
</style>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Gallery Cafe - Staff Login</title>
</head>
<body>
    <div class="form-container">
        <section class="form-container">
            <div class="title">
                <img src="img/download.jpg">
                <h1>Staff Login</h1>
                <p>Login now to access the staff dashboard!</p>
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
                    <p>Your Email*</p>
                    <input type="email" name="email" required placeholder="Enter your email" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
                </div>
                <div class="input-field">
                    <p>Your Password*</p>
                    <input type="password" name="pass" required placeholder="Enter your password" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
                </div>
                <input type="submit" name="submit" value="Login" class="btn">
                <p>Don't have an account? <a href="staff_register.php">Register Now</a></p>
            </form>
            <p class="admin-staff-message">Not a staff member? If you are an admin, please <a href="admin_login.php">Login Here</a>. If you are a customer, please <a href="login.php">Login Here</a>.</p>
        </section>
    </div>
</body>
</html>
