<?php 
include 'connection.php'; 
session_start();
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}
if (isset($_POST['logout'])){
    session_destroy();
    header("location: login.php");
}
if (isset($_POST['reserve'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
    $table_size = filter_var($_POST['table_size'], FILTER_SANITIZE_STRING);
    $reservation_date = filter_var($_POST['reservation_date'], FILTER_SANITIZE_STRING);
    $reservation_time = filter_var($_POST['reservation_time'], FILTER_SANITIZE_STRING);

    $reservation_made = false;
    $conn->beginTransaction();

    try {
        // Insert reservation into the database
        $insert_reservation = $conn->prepare("INSERT INTO reservations (user_id, name, email, phone, table_size, reservation_date, reservation_time) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $insert_reservation->execute([$user_id, $name, $email, $phone, $table_size, $reservation_date, $reservation_time]);

        $reservation_made = true;

        if ($reservation_made) {
            $conn->commit();
            $success_msg[] = 'Reservation placed successfully!';
        } else {
            $conn->rollBack();
            $error_msg[] = 'Failed to place reservation.';
        }
    } catch (Exception $e) {
        $conn->rollBack();
        $success_msg[] = 'Error: ' . $e->getMessage();
    }
}
// Fetch reservations for the logged-in user
$fetch_reservations = $conn->prepare("SELECT * FROM reservations WHERE user_id = ?");
$fetch_reservations->execute([$user_id]);
$reservations = $fetch_reservations->fetchAll(PDO::FETCH_ASSOC);
?>
<style type="text/css">
    <?php include 'style.css'; ?>
</style>
    
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <title>Gallery Cafe - Reservations Page</title>
</head>
<body>
<?php include 'header.php'; ?>
<div class="main">    
    <section class="reservation-info">
            <h2>Reservations</h2>
            <p>Making a reservation ensures that we can provide you with the best possible service. Please provide accurate details to help us accommodate your needs effectively. We look forward to welcoming you!</p>
    </section>   
    <section class="table-capacities">
            <h2>Available Table Capacities</h2>
            <div class="table-list">
                <div class="table-item">
                    <h3>Small Table (2-4 people)</h3>
                    <p>Ideal for intimate gatherings and small parties.</p>
                </div>
                <div class="table-item">
                    <h3>Medium Table (4-6 people)</h3>
                    <p>Perfect for family dinners and small groups.</p>
                </div>
                <div class="table-item">
                    <h3>Large Table (6-10 people)</h3>
                    <p>Suitable for larger groups and celebrations.</p>
                </div>
            </div>
        </section>

        <section class="parking-availability">
            <h2>Parking Availability</h2>
            <p>We offer ample parking space for our guests. There are designated parking areas near the entrance. Valet parking is also available upon request.</p>
        </section>

        <section class="reservation-form">
            <h2>Make a Reservation</h2>
            <form action="" method="POST">
                <div class="input-field">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="input-field">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="input-field">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" required>
                </div>
                <div class="input-field">
                    <label for="table">Table Size</label>
                    <select id="table" name="table_size" required>
                        <option value="small">Small Table (2-4 people)</option>
                        <option value="medium">Medium Table (4-6 people)</option>
                        <option value="large">Large Table (6-10 people)</option>
                    </select>
                </div>
                <div class="input-field">
                    <label for="date">Reservation Date</label>
                    <input type="date" id="date" name="reservation_date" required>
                </div>
                <div class="input-field">
                    <label for="time">Reservation Time</label>
                    <input type="time" id="time" name="reservation_time" required>
                </div>
                <button type="submit" name="reserve" class="btn">Reserve Now</button>
            </form>
        </section>
        <section class="reservation-list">
        <?php 
        if (!empty($reservations)) {
            foreach ($reservations as $reservation) {
        ?>
        <div class="reservation-card">
            <h3>Reservation ID: <?=$reservation['id']; ?></h3>
            <p>Status: <?=$reservation['status']; ?></p>
            <p>Name: <?=$reservation['name']; ?></p>
            <p>Email: <?=$reservation['email']; ?></p>
            <p>Phone: <?=$reservation['phone']; ?></p>
            <p>Table Size: <?=$reservation['table_size']; ?></p>
            <p>Date: <?=$reservation['reservation_date']; ?></p>
            <p>Time: <?=$reservation['reservation_time']; ?></p>
            <!-- Add code to update the status if needed -->
        </div>
        <?php 
            }
        } else {
            echo '<p>No reservations found!</p>';
        }
        ?>
    </section>
    <?php include 'footer.php'; ?>                    
    </div>    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="script.js"></script>
    <?php include 'alert.php'; ?>
</body>
</html>
