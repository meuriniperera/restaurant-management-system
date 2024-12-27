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
//adding items in wishlist
if (isset($_POST['add_to_wishlist'])) {
    $id = unique_id();
    $product_id = $_POST['product_id'];

    // Ensure $user_id is defined and set before this point
    $user_id = $_SESSION['user_id']; // Assuming user_id is stored in session

    // Corrected queries with backticks around table names
    $varify_wishlist = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ? AND product_id = ?");
    $varify_wishlist->execute([$user_id, $product_id]);

    $cart_num = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ? AND product_id = ?");
    $cart_num->execute([$user_id, $product_id]);

    if ($varify_wishlist->rowCount() > 0) {
        $warning_msg[] = 'Product already exists in your Wishlist';
    } else if ($cart_num->rowCount() > 0) {
        $warning_msg[] = 'Product already exists in your Cart';
    } else {
        $select_price = $conn->prepare("SELECT * FROM `menu_items` WHERE id = ? LIMIT 1");
        $select_price->execute([$product_id]);
        $fetch_price = $select_price->fetch(PDO::FETCH_ASSOC);

        $insert_wishlist = $conn->prepare("INSERT INTO `wishlist` (id, user_id, product_id, price) VALUES (?, ?, ?, ?)");
        $insert_wishlist->execute([$id, $user_id, $product_id, $fetch_price['price']]);
        $success_msg[] = 'Product added to Wishlist Successfully';
    }
}
//adding items in cart
if (isset($_POST['add_to_cart'])) {
    $id = unique_id();
    $product_id = $_POST['product_id'];

    // Ensure $user_id is defined and set before this point
    $user_id = $_SESSION['user_id']; // Assuming user_id is stored in session

    $quantity = $_POST['quantity'];
    $quantity = filter_var($quantity, FILTER_SANITIZE_STRING);

    // Check if the product is already in the cart
    $varify_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ? AND product_id = ?");
    $varify_cart->execute([$user_id, $product_id]);

    // Correctly prepare the query to count the number of items in the cart
    $max_cart_items = $conn->prepare("SELECT COUNT(*) FROM `cart` WHERE user_id = ?");
    $max_cart_items->execute([$user_id]);

    // Fetch the count from the executed query
    $cart_item_count = $max_cart_items->fetchColumn();

    if ($varify_cart->rowCount() > 0) {
        $warning_msg[] = 'Product already exists in your Cart';
    } else if ($cart_item_count > 20) {
        $warning_msg[] = 'Cart is full';
    } else {
        // Fetch the price of the product
        $select_price = $conn->prepare("SELECT * FROM `menu_items` WHERE id = ? LIMIT 1");
        $select_price->execute([$product_id]);
        $fetch_price = $select_price->fetch(PDO::FETCH_ASSOC);

        // Insert the product into the cart
        $insert_cart = $conn->prepare("INSERT INTO `cart` (id, user_id, product_id, quantity, price) VALUES (?, ?, ?, ?, ?)");
        $insert_cart->execute([$id, $user_id, $product_id, $quantity, $fetch_price['price']]);
        $success_msg[] = 'Product added to Cart Successfully';
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
    <title>Gallery Cafe - Item Detail Page</title>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="main">
        <section class="view_page">
        <?php 
                if (isset($_GET['pid'])) {
                    $product_id = $_GET['pid'];
                    $select_products = $conn->prepare("SELECT * FROM `menu_items` WHERE id = ?");
                    $select_products->execute([$product_id]);
                    if ($select_products->rowCount() > 0) {
                        while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <form method="post">
                <img src="img/<?php echo $fetch_products['image_url']; ?>" alt="Product Image">
                <div class="detail">
                    <div class="price">Price $<?php echo $fetch_products['price']; ?></div>
                    <div class="name"><?php echo $fetch_products['name']; ?></div>
                    <div class="description"><?php echo $fetch_products['description']; ?></div>
                </div>
                <div class="flex">
                    <input type="number" name="quantity" required min="1" value="1" max="99" maxlength="2" class="quantity">
                    <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
                    <button type="submit" name="add_to_cart" class="btn">Add to Cart</button>
                    <button type="submit" name="add_to_wishlist" class="btn">Add to Wishlist</button>
                </div>
            </form>
            <?php 
                        }
                    } else {
                        echo '<p class="empty">No Products Found!</p>';
                    }
                } else {
                    echo '<p class="empty">Product ID is missing!</p>';
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