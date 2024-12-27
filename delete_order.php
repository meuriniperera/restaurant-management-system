<?php
include 'connection.php';
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $order_id = $_GET['id'];

    try {
        $delete_order = $conn->prepare("DELETE FROM orders WHERE id = ?");
        $delete_order->execute([$order_id]);

        header("location: view_orders.php");
        exit();
    } catch (Exception $e) {
        $error_msg[] = 'Error: ' . $e->getMessage();
    }
} else {
    header("location: view_orders.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin_style.css"> <!-- Link to external CSS -->
    <title>Gallery Cafe - Delete Order</title>
</head>
<body>
    <header>
        <h1>Gallery Cafe - Admin Dashboard</h1>
        <nav>
            <ul>
                <li><a href="admin_dashboard.php">Home</a></li>
                <li><a href="manage_menu.php">Manage Menu</a></li>
                <li><a href="view_reservations.php">View Reservations</a></li>
                <li><a href="view_orders.php">View Orders</a></li>
                <li><a href="manage_users.php">Manage Users</a></li>
            </ul>
        </nav>
        <form method="post">
            <button type="submit" name="logout" class="btn">Logout</button>
        </form>
    </header>
    <div class="admin-main">
        <section class="delete-info">
            <h2>Delete Order</h2>
            <?php if (isset($error_msg)) { foreach ($error_msg as $msg) { echo '<p class="error">'.$msg.'</p>'; } } ?>
            <p>Are you sure you want to delete this order?</p>
            <form method="GET" action="">
                <input type="hidden" name="id" value="<?php echo $order_id; ?>">
                <button type="submit" name="confirm_delete" class="btn">Yes, Delete</button>
                <a href="view_orders.php" class="btn">Cancel</a>
            </form>
        </section>
    </div>
      
</body>
</html>
