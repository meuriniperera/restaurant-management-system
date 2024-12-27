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

if (isset($_POST['update_status'])) {
    $reservation_id = filter_var($_POST['reservation_id'], FILTER_SANITIZE_NUMBER_INT);
    $status = filter_var($_POST['status'], FILTER_SANITIZE_STRING);

    try {
        $update_status = $conn->prepare("UPDATE reservations SET status = ? WHERE id = ?");
        $update_status->execute([$status, $reservation_id]);
        $success_msg = 'Status updated successfully!';
    } catch (PDOException $e) {
        $error_msg = 'Error: ' . $e->getMessage();
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
    <title>Gallery Cafe - View Reservations</title>
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
        <section class="reservation-info">
            <h2>View Reservations</h2>
            <p>Here you can manage and view all reservations made by customers.</p>
        </section>   

        <section class="reservations">
            <h2>Current Reservations</h2>
            <div class="reservation-list">
                <?php 
                    if (isset($success_msg)) {
                        echo '<p class="success">' . $success_msg . '</p>';
                    }
                    if (isset($error_msg)) {
                        echo '<p class="error">' . $error_msg . '</p>';
                    }

                    try {
                        $select_reservations = $conn->prepare("SELECT * FROM reservations");
                        $select_reservations->execute();
                        
                        if ($select_reservations->rowCount() > 0) {
                            while ($fetch_reservation = $select_reservations->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <div class="reservation-item">
                    <h3><?php echo $fetch_reservation['name']; ?></h3>
                    <p>Email: <?php echo $fetch_reservation['email']; ?></p>
                    <p>Phone: <?php echo $fetch_reservation['phone']; ?></p>
                    <p>Table Size: <?php echo ucfirst($fetch_reservation['table_size']); ?></p>
                    <p>Date: <?php echo $fetch_reservation['reservation_date']; ?></p>
                    <p>Time: <?php echo $fetch_reservation['reservation_time']; ?></p>
                    <p>Status: <?php echo ucfirst($fetch_reservation['status']); ?></p>
                    <form action="" method="POST">
                        <input type="hidden" name="reservation_id" value="<?php echo $fetch_reservation['id']; ?>">
                        <select name="status" required>
                            <option value="Pending" <?php if ($fetch_reservation['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                            <option value="Processing" <?php if ($fetch_reservation['status'] == 'Processing') echo 'selected'; ?>>Processing</option>
                            <option value="Completed" <?php if ($fetch_reservation['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
                            <option value="Cancelled" <?php if ($fetch_reservation['status'] == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
                        </select>
                        <button type="submit" name="update_status" class="btn">Update Status</button>
                    </form>
                    <a href="delete_reservation.php?id=<?php echo $fetch_reservation['id']; ?>" class="btn">Delete</a>
                </div>
                <?php 
                            }
                        } else {
                            echo '<p class="empty">No Reservations Found!</p>';
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
