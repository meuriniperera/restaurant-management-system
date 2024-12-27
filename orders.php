<?php 
include 'connection.php'; 
session_start();

// Initialize user_id
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

if (!isset($user_id)) {
    header("location: login.php");
    exit();
}

if (isset($_POST['logout'])){
    session_destroy();
    header("location: login.php");
    exit();
}

// Handle order deletion
if (isset($_POST['delete_order'])) {
    $order_id = $_POST['order_id'];

    $delete_order = $conn->prepare("DELETE FROM orders WHERE id = ?");
    $delete_order->execute([$order_id]);
    $success_msg[] = 'Order deleted successfully';
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
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <title>My Orders</title>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="main">
        <div class="order-list">
            <?php 
            if (isset($success_msg)) {
                foreach ($success_msg as $msg) {
                    echo "<p class='success'>$msg</p>";
                }
            }
            try {
                $select_orders = $conn->prepare("SELECT * FROM orders WHERE user_id = ?");
                $select_orders->execute([$user_id]);

                if ($select_orders->rowCount() > 0) {
                    while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <div class="order-card">
                <h3>Order ID: <?=$fetch_orders['id']; ?></h3>
                <p>Status: <?=$fetch_orders['status']; ?></p>
                <p>Name: <?=$fetch_orders['name']; ?></p>
                <p>Number: <?=$fetch_orders['number']; ?></p>
                <p>Email: <?=$fetch_orders['email']; ?></p>
                <p>Address: <?=$fetch_orders['address']; ?></p>
                <p>Payment Method: <?=$fetch_orders['method']; ?></p>
                <p>Product ID: <?=$fetch_orders['product_id']; ?></p>
                <p>Price: $<?=$fetch_orders['price']; ?></p>
                <p>Quantity: <?=$fetch_orders['quantity']; ?></p>

                <!-- Delete Order Form -->
                <form action="" method="post" onsubmit="return confirm('Are you sure you want to delete this order?');">
                    <input type="hidden" name="order_id" value="<?=$fetch_orders['id']; ?>">
                    <button type="submit" name="delete_order" class="btn btn-danger">Delete Order</button>
                </form>
            </div>
            <?php 
                    }
                } else {
                    echo '<p>No orders found!</p>';
                }
            } catch (PDOException $e) {
                echo 'Error: ' . $e->getMessage();
            }
            ?>
        </div>
     <?php include 'footer.php'; ?>    
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="script.js"></script>
    <?php include 'alert.php'; ?>
</body>
</html>
