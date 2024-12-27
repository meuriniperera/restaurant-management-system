<?php 
include 'connection.php'; 
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("location: admin_login.php");
    exit();
}
if (isset($_POST['logout'])){
    session_destroy();
    header("location: admin_login.php");
    exit();
}

// Fetch total number of orders
$total_orders = 0;
$total_reservations = 0;

try {
    $order_query = $conn->prepare("SELECT COUNT(*) AS total FROM orders");
    $order_query->execute();
    $total_orders = $order_query->fetch(PDO::FETCH_ASSOC)['total'];

    $reservation_query = $conn->prepare("SELECT COUNT(*) AS total FROM reservations");
    $reservation_query->execute();
    $total_reservations = $reservation_query->fetch(PDO::FETCH_ASSOC)['total'];
} catch (PDOException $e) {
    $error_msg = 'Error: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin_style.css"> <!-- Link to external CSS -->
    <title>Gallery Cafe - Admin Dashboard</title>
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
        <section class="admin-home">
            <h1>Admin Dashboard</h1>
            <p>Manage the Gallery Cafe's operations here.</p>
            
            <!-- Dashboard Widgets -->
            <div class="dashboard-widgets">
                <div class="widget">
                    <h2>Total Orders</h2>
                    <p><?php echo $total_orders; ?></p>
                </div>
                <div class="widget">
                    <h2>New Reservations</h2>
                    <p><?php echo $total_reservations; ?></p>
                </div>
            </div>
        </section>
    </div>
</body>
</html>
