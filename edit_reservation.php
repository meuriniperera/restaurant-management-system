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

if (isset($_GET['id'])) {
    $reservation_id = $_GET['id'];
    try {
        $select_reservation = $conn->prepare("SELECT * FROM reservations WHERE id = ?");
        $select_reservation->execute([$reservation_id]);
        $reservation = $select_reservation->fetch(PDO::FETCH_ASSOC);

        if (!$reservation) {
            $error_msg[] = 'Reservation not found!';
        }
    } catch (Exception $e) {
        $error_msg[] = 'Error: ' . $e->getMessage();
    }
}

if (isset($_POST['update'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
    $table_size = filter_var($_POST['table_size'], FILTER_SANITIZE_STRING);
    $reservation_date = filter_var($_POST['reservation_date'], FILTER_SANITIZE_STRING);
    $reservation_time = filter_var($_POST['reservation_time'], FILTER_SANITIZE_STRING);

    try {
        $update_reservation = $conn->prepare("UPDATE reservations SET name = ?, email = ?, phone = ?, table_size = ?, reservation_date = ?, reservation_time = ? WHERE id = ?");
        $update_reservation->execute([$name, $email, $phone, $table_size, $reservation_date, $reservation_time, $reservation_id]);

        $success_msg[] = 'Reservation updated successfully!';
    } catch (Exception $e) {
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
    <title>Gallery Cafe - Edit Reservation</title>
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
        <section class="reservation-form">
            <h2>Edit Reservation</h2>
            <?php if (isset($error_msg)) { foreach ($error_msg as $msg) { echo '<p class="error">'.$msg.'</p>'; } } ?>
            <?php if (isset($success_msg)) { foreach ($success_msg as $msg) { echo '<p class="success">'.$msg.'</p>'; } } ?>
            <form action="" method="POST">
                <div class="input-field">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" value="<?php echo $reservation['name']; ?>" required>
                </div>
                <div class="input-field">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo $reservation['email']; ?>" required>
                </div>
                <div class="input-field">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" value="<?php echo $reservation['phone']; ?>" required>
                </div>
                <div class="input-field">
                    <label for="table">Table Size</label>
                    <select id="table" name="table_size" required>
                        <option value="small" <?php if ($reservation['table_size'] == 'small') echo 'selected'; ?>>Small Table (2-4 people)</option>
                        <option value="medium" <?php if ($reservation['table_size'] == 'medium') echo 'selected'; ?>>Medium Table (4-6 people)</option>
                        <option value="large" <?php if ($reservation['table_size'] == 'large') echo 'selected'; ?>>Large Table (6-10 people)</option>
                    </select>
                </div>
                <div class="input-field">
                    <label for="date">Reservation Date</label>
                    <input type="date" id="date" name="reservation_date" value="<?php echo $reservation['reservation_date']; ?>" required>
                </div>
                <div class="input-field">
                    <label for="time">Reservation Time</label>
                    <input type="time" id="time" name="reservation_time" value="<?php echo $reservation['reservation_time']; ?>" required>
                </div>
                <button type="submit" name="update" class="btn">Update Reservation</button>
            </form>
        </section>
    </div>
    
</body>
</html>
