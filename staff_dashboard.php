<?php 
include 'connection.php'; 
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("location: staff_login.php");
    exit();
}
if (isset($_POST['logout'])){
    session_destroy();
    header("location: staff_login.php");
    exit();
}

$message = ''; // Initialize message variable

// Fetch reservations and orders
$reservation_details = [];
$order_details = [];

try {
    // Fetch reservations
    $reservation_query = $conn->prepare("SELECT * FROM reservations");
    $reservation_query->execute();
    $reservation_details = $reservation_query->fetchAll(PDO::FETCH_ASSOC);

    // Fetch orders
    $order_query = $conn->prepare("SELECT * FROM orders");
    $order_query->execute();
    $order_details = $order_query->fetchAll(PDO::FETCH_ASSOC);
    
    // Handle reservation and order updates
    if (isset($_POST['update_reservation'])) {
        $reservation_id = $_POST['reservation_id'];
        $status = $_POST['reservation_status'];
        $update_reservation = $conn->prepare("UPDATE reservations SET status = ? WHERE id = ?");
        $update_reservation->execute([$status, $reservation_id]);
        $message = 'Reservation status updated successfully!';
        header("Refresh:0"); // Refresh to reflect changes
    }

    if (isset($_POST['update_order'])) {
        $order_id = $_POST['order_id'];
        $status = $_POST['order_status'];
        $update_order = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $update_order->execute([$status, $order_id]);
        $message = 'Order status updated successfully!';
        header("Refresh:0"); // Refresh to reflect changes
    }

    if (isset($_POST['delete_order'])) {
        $order_id = $_POST['order_id'];
        $delete_order = $conn->prepare("DELETE FROM orders WHERE id = ?");
        $delete_order->execute([$order_id]);
        $message = 'Order deleted successfully!';
        header("Refresh:0"); // Refresh to reflect changes
    }
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
    <link rel="stylesheet" href="staff_style.css"> <!-- Link to external CSS -->
    <title>Gallery Cafe - Staff Dashboard</title>
</head>
<body>
    <header>
        <h1>Gallery Cafe - Staff Dashboard</h1>
        <form method="post">
            <button type="submit" name="logout" class="btn">Logout</button>
        </form>
    </header>
    <div class="staff-main">
        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <section class="reservations-section">
            <h2>Manage Reservations</h2>
            <div class="reservations-list">
                <?php foreach ($reservation_details as $reservation): ?>
                <div class="reservation-item">
                    <h3><?php echo htmlspecialchars($reservation['name']); ?></h3>
                    <p>Email: <?php echo htmlspecialchars($reservation['email']); ?></p>
                    <p>Phone: <?php echo htmlspecialchars($reservation['phone']); ?></p>
                    <p>Table Size: <?php echo htmlspecialchars(ucfirst($reservation['table_size'])); ?></p>
                    <p>Date: <?php echo htmlspecialchars($reservation['reservation_date']); ?></p>
                    <p>Time: <?php echo htmlspecialchars($reservation['reservation_time']); ?></p>
                    <p>Status: <?php echo htmlspecialchars($reservation['status']); ?></p>
                    <form action="" method="post">
                        <input type="hidden" name="reservation_id" value="<?php echo htmlspecialchars($reservation['id']); ?>">
                        <select name="reservation_status" required>
                            <option value="Pending" <?php if ($reservation['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                            <option value="Confirmed" <?php if ($reservation['status'] == 'Confirmed') echo 'selected'; ?>>Confirmed</option>
                            <option value="Cancelled" <?php if ($reservation['status'] == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
                        </select>
                        <button type="submit" name="update_reservation" class="btn">Update Status</button>
                    </form>
                </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="orders-section">
            <h2>Manage Orders</h2>
            <div class="orders-list">
                <?php foreach ($order_details as $order): ?>
                <div class="order-card">
                    <h3>Order ID: <?php echo htmlspecialchars($order['id']); ?></h3>
                    <p>Status: <?php echo htmlspecialchars($order['status']); ?></p>
                    <p>Name: <?php echo htmlspecialchars($order['name']); ?></p>
                    <p>Number: <?php echo htmlspecialchars($order['number']); ?></p>
                    <p>Email: <?php echo htmlspecialchars($order['email']); ?></p>
                    <p>Address: <?php echo htmlspecialchars($order['address']); ?></p>
                    <p>Payment Method: <?php echo htmlspecialchars($order['method']); ?></p>
                    <p>Product ID: <?php echo htmlspecialchars($order['product_id']); ?></p>
                    <p>Price: $<?php echo htmlspecialchars($order['price']); ?></p>
                    <p>Quantity: <?php echo htmlspecialchars($order['quantity']); ?></p>

                    <!-- Update Status Form -->
                    <form action="" method="post">
                        <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['id']); ?>">
                        <select name="order_status" required>
                            <option value="Pending" <?php if ($order['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                            <option value="Processing" <?php if ($order['status'] == 'Processing') echo 'selected'; ?>>Processing</option>
                            <option value="Completed" <?php if ($order['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
                            <option value="Cancelled" <?php if ($order['status'] == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
                        </select>
                        <button type="submit" name="update_order" class="btn">Update Status</button>
                    </form>

                    <!-- Delete Order Form -->
                    <form action="" method="post" onsubmit="return confirm('Are you sure you want to delete this order?');">
                        <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['id']); ?>">
                        <button type="submit" name="delete_order" class="btn btn-danger">Delete Order</button>
                    </form>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
    </div>
</body>
</html>
