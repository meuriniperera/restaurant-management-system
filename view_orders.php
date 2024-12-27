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

// Handle status update
if (isset($_POST['update_status'])) {
    $order_id = filter_var($_POST['order_id'], FILTER_SANITIZE_NUMBER_INT);
    $new_status = filter_var($_POST['status'], FILTER_SANITIZE_STRING);

    try {
        $update_status = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $update_status->execute([$new_status, $order_id]);

        if ($update_status->rowCount() > 0) {
            $success_msg[] = 'Order status updated successfully!';
        } else {
            $error_msg[] = 'Failed to update order status.';
        }
    } catch (PDOException $e) {
        $error_msg[] = 'Error: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin_style.css"> <!-- Link to external CSS -->
    <title>Gallery Cafe - View Orders</title>
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
        <section class="order-info">
            <h2>View Orders</h2>
            <p>Here you can manage and view all orders made by customers.</p>
        </section>   

        <section class="orders">
            <h2>Current Orders</h2>
            <div class="order-list">
                <?php 
                if (isset($success_msg)) {
                    foreach ($success_msg as $msg) {
                        echo "<p class='success'>$msg</p>";
                    }
                }
                if (isset($error_msg)) {
                    foreach ($error_msg as $msg) {
                        echo "<p class='error'>$msg</p>";
                    }
                }

                try {
                    $select_orders = $conn->prepare("SELECT * FROM orders");
                    $select_orders->execute();
                    
                    if ($select_orders->rowCount() > 0) {
                        while ($fetch_order = $select_orders->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <div class="order-item">
                    <h3>Order ID: <?php echo $fetch_order['id']; ?></h3>
                    <p>Customer Name: <?php echo $fetch_order['name']; ?></p>
                    <p>Email: <?php echo $fetch_order['email']; ?></p>
                    <p>Phone: <?php echo $fetch_order['number']; ?></p>
                    <p>Address: <?php echo $fetch_order['address']; ?></p>
                    <p>Address Type: <?php echo ucfirst($fetch_order['address_type']); ?></p>
                    <p>Payment Method: <?php echo $fetch_order['method']; ?></p>
                    <p>Product ID: <?php echo $fetch_order['product_id']; ?></p>
                    <p>Price: $<?php echo $fetch_order['price']; ?></p>
                    <p>Quantity: <?php echo $fetch_order['quantity']; ?></p>
                    <p>Status: <?php echo ucfirst($fetch_order['status']); ?></p>

                    <!-- Status Update Form -->
                    <form action="" method="POST">
                        <input type="hidden" name="order_id" value="<?php echo $fetch_order['id']; ?>">
                        <select name="status" required>
                            <option value="Pending" <?php if ($fetch_order['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                            <option value="Processing" <?php if ($fetch_order['status'] == 'Processing') echo 'selected'; ?>>Processing</option>
                            <option value="Completed" <?php if ($fetch_order['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
                            <option value="Cancelled" <?php if ($fetch_order['status'] == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
                        </select>
                        <button type="submit" name="update_status" class="btn">Update Status</button>
                    </form>
                    <a href="delete_order.php?id=<?php echo $fetch_order['id']; ?>" class="btn">Delete</a>
                </div>
                <?php 
                        }
                    } else {
                        echo '<p class="empty">No Orders Found!</p>';
                    }
                } catch (PDOException $e) {
                    echo 'Error: ' . $e->getMessage();
                }
                ?>
            </div>
        </section>
    </div>
</body>
</html>
