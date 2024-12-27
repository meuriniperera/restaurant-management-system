<?php 
include 'connection.php'; 
session_start();

// Initialize user_id
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

if (isset($_POST['logout'])){
    session_destroy();
    header("location: login.php");
    exit();
}

if (isset($_POST['place_order'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $address = filter_var($_POST['flat'] . ', ' . $_POST['street'] . ', ' . $_POST['city'] . ', ' . $_POST['country'] . ', ' . $_POST['pincode'], FILTER_SANITIZE_STRING);
    $address_type = filter_var($_POST['address_type'], FILTER_SANITIZE_STRING);
    $method = filter_var($_POST['method'], FILTER_SANITIZE_STRING);

    $order_placed = false;
    $conn->beginTransaction();

    try {
        // Check if a specific product is being ordered
        if (isset($_GET['get_id'])) {
            $get_product = $conn->prepare("SELECT * FROM menu_items WHERE id = ? LIMIT 1");
            $get_product->execute([$_GET['get_id']]);
            
            if ($get_product->rowCount() > 0) {
                $fetch_p = $get_product->fetch(PDO::FETCH_ASSOC);
                $insert_order = $conn->prepare("INSERT INTO orders (user_id, name, number, email, address, address_type, method, product_id, price, quantity) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $insert_order->execute([$user_id, $name, $number, $email, $address, $address_type, $method, $fetch_p['id'], $fetch_p['price'], 1]);
                $order_placed = true;
            } else {
                $warning_msg[] = 'Product not found';
            }
        } else {
            // Process cart items
            $verify_cart = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
            $verify_cart->execute([$user_id]);

            if ($verify_cart->rowCount() > 0) {
                while ($f_cart = $verify_cart->fetch(PDO::FETCH_ASSOC)) {
                    $select_products = $conn->prepare("SELECT * FROM menu_items WHERE id = ?");
                    $select_products->execute([$f_cart['product_id']]);
                    $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);

                    $insert_order = $conn->prepare("INSERT INTO orders (user_id, name, number, email, address, address_type, method, product_id, price, quantity) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $insert_order->execute([$user_id, $name, $number, $email, $address, $address_type, $method, $fetch_products['id'], $fetch_products['price'], $f_cart['quantity']]);
                }

                $delete_cart_id = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
                $delete_cart_id->execute([$user_id]);
                $order_placed = true;
            } else {
                $warning_msg[] = 'Your cart is empty';
            }
        }

        if ($order_placed) {
            $conn->commit();
            $success_msg[] = 'Order placed Successfully!';
        } else {
            $conn->rollBack();
            $success_msg[] = 'Failed to place order';
        }
    } catch (Exception $e) {
        $conn->rollBack();
        $success_msg[] = 'Error: ' . $e->getMessage();
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
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <title>Gallery Cafe - Checkout Page</title>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="main">
        <section class="checkout">
            <div class="title">
                <img src="img/download.jpg" class="logo">
                <h1>Checkout Summary</h1>
                <p>Review your order details below before proceeding to payment.</p>
            </div>    
            <div class="row">
                <form method="post">
                    <h3>Billing Details</h3>
                    <div class="flex">
                        <div class="box">
                            <div class="input-field">
                                <p>Your Name <span>*</span></p>
                                <input type="text" name="name" required maxlength="50" placeholder="Enter your name" class="input">
                            </div>
                            <div class="input-field">
                                <p>Your Number <span>*</span></p>
                                <input type="number" name="number" required maxlength="10" placeholder="Enter your Number" class="input">
                            </div>
                            <div class="input-field">
                                <p>Your Email <span>*</span></p>
                                <input type="email" name="email" required maxlength="50" placeholder="Enter your email" class="input">
                            </div>
                            <div class="input-field">
                                <p>Payment Method <span>*</span></p>
                                <select name="method" class="input">
                                    <option value="Cash on Delivery">Cash on Delivery</option>
                                    <option value="Credit or Debit Card">Credit or Debit Card</option>
                                </select>
                            </div>
                            <div class="input-field">
                                <p>Address Type <span>*</span></p>
                                <select name="address_type" class="input">
                                    <option value="home">Home</option>
                                    <option value="office">Office</option>
                                </select>
                            </div>
                        </div>
                        <div class="box">
                            <div class="input-field">
                                <p>Address line 01 <span>*</span></p>
                                <input type="text" name="flat" required maxlength="50" placeholder="e.g flat & building number" class="input">
                            </div>
                            <div class="input-field">
                                <p>Address line 02 <span>*</span></p>
                                <input type="text" name="street" required maxlength="50" placeholder="e.g street name" class="input">
                            </div>
                            <div class="input-field">
                                <p>City name <span>*</span></p>
                                <input type="text" name="city" required maxlength="50" placeholder="Enter your city name" class="input">
                            </div>
                            <div class="input-field">
                                <p>Country Name <span>*</span></p>
                                <input type="text" name="country" required maxlength="50" placeholder="Enter your country name" class="input">
                            </div>
                            <div class="input-field">
                                <p>Pincode <span>*</span></p>
                                <input type="text" name="pincode" required maxlength="6" placeholder="112233" min="0" max="999999" class="input">
                            </div>
                        </div>
                    </div>
                    <button type="submit" name="place_order" class="btn">Place Order</button>
                </form>
                <div class="summary">
                    <h3>My Bag</h3>
                    <div class="box-container">
                        <?php 
                        $grand_total = 0;
                        if (isset($_GET['get_id'])) {
                            $select_get = $conn->prepare("SELECT * FROM menu_items WHERE id = ?");
                            $select_get->execute([$_GET['get_id']]);
                            while ($fetch_get = $select_get->fetch(PDO::FETCH_ASSOC)) {
                                $sub_total = $fetch_get['price'];
                                $grand_total += $sub_total;
                        ?>
                        <div class="flex">
                            <img src="img/<?= $fetch_get['image_url']; ?>" class="img">
                            <div>
                                <h3 class="name"><?= $fetch_get['name']; ?></h3>
                                <p class="price"><?= $fetch_get['price']; ?>/-</p>
                            </div>
                        </div>
                        <?php 
                            } 
                        } else {
                            $select_cart = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
                            $select_cart->execute([$user_id]);
                            if ($select_cart->rowCount() > 0) {
                                while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                                    $select_products = $conn->prepare("SELECT * FROM menu_items WHERE id = ?");
                                    $select_products->execute([$fetch_cart['product_id']]);
                                    $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);
                                    $sub_total = ($fetch_cart['quantity'] * $fetch_products['price']);
                                    $grand_total += $sub_total;
                        ?>
                        <div class="flex">
                            <img src="img/<?= $fetch_products['image_url']; ?>" class="img">
                            <div>
                                <h3 class="name"><?= $fetch_products['name']; ?></h3>
                                <p class="price"><?= $fetch_products['price']; ?> X <?= $fetch_cart['quantity'] ?></p>
                            </div>
                        </div>
                        <?php 
                                } 
                            } else {
                                echo '<p class="empty">Your cart is empty!</p>';
                            }
                        }
                        ?>
                    </div>
                    <div class="grand-total"><span>Total Amount Payable : </span>$<?= $grand_total ?>/-</div>
                </div>
            </div>
        </section>
        <?php include 'footer.php'; ?>                    
    </div>    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="script.js"></script>
    <?php include 'alert.php'; ?>
</body>
</html>
