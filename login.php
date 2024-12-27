<?php 
include 'connection.php';
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}
$message = [];
// Login user
if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $pass = $_POST['pass'];
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);
    
    $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
    $select_user->execute([$email]);
    $row = $select_user->fetch(PDO::FETCH_ASSOC);

    if ($select_user->rowCount() > 0 && password_verify($pass, $row['password'])) {
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user_name'] = $row['name'];
        $_SESSION['user_email'] = $row['email'];
        header("Location: home.php");
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
    <title>The Gallery Cafe - Login Now</title>
</head>
<body>
    <div class="form-container">
        <section class="form-container">
            <div class="title">
                <img src = "img/download.jpg">
                <h1>Login Now</h1>
                <p>Login now to enjoy exclusive offers and updates!</p>
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
                    <input type="password" name="pass" required placeholder="Enter your password" maxlength="50"oninput="this.value = this.value.replace(/\s/g, '')">
                </div>
                <input type="submit" name="submit" value="Login" class="btn">
                <p>Don't have an account? <a href="register.php">Register Now</a></p>
            </form>
            <p class="admin-staff-message">Not a customer? If you are an Admin, please <a href="admin_login.php">Login Here</a>. If you are Staff, please <a href="staff_login.php">Login Here</a>.</p>
        </section>
    </div>
</body>
</html>