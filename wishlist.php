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

    $quantity = 1;
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
// Delete item from wishlist
if (isset($_POST['delete_item'])) {
    $wishlist_id = $_POST['wishlist_id'];
    $wishlist_id = filter_var($wishlist_id, FILTER_SANITIZE_STRING); // Assuming the ID is an integer

    // Prepare the SELECT statement
    $verify_delete_items = $conn->prepare("SELECT * FROM wishlist WHERE id = ?");
    $verify_delete_items->execute([$wishlist_id]);

    // Check if the item exists
    if ($verify_delete_items->rowCount() > 0) {
        // Prepare the DELETE statement
        $delete_wishlist_id = $conn->prepare("DELETE FROM wishlist WHERE id = ?");
        $delete_wishlist_id->execute([$wishlist_id]);
        $success_msg[] = "Wishlist item deleted successfully.";
    } else {
        $warning_msg[] = "Wishlist item already deleted.";
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
    <title>Gallery Cafe - Wishlist Page</title>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="main">
    <section class="products">
    <div class="box-container">
    <?php 
        $grand_total = 0;
        // Corrected the table name quoting
        $select_wishlist = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
        $select_wishlist->execute([$user_id]);
        if ($select_wishlist->rowCount() > 0) {
            while($fetch_wishlist = $select_wishlist->fetch(PDO::FETCH_ASSOC)){
                // Corrected the table name quoting
                $select_products = $conn->prepare("SELECT * FROM `menu_items` WHERE id = ?");
                $select_products->execute([$fetch_wishlist['product_id']]);
                if ($select_products->rowCount() > 0) {
                    $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);
    ?>
    <form method="post" action="" class="box">
        <input type="hidden" name="wishlist_id" value="<?=$fetch_wishlist['id']; ?>">
        <img src="img/<?=$fetch_products['image_url']; ?>"class="img">
        <div class="button">
            <button type="submit" name="add_to_cart"><i class="bx bx-cart"></i></button>
            <a href="view_page.php?pid=<?=$fetch_products['id']; ?>" class="bx bxs-show"></a>
            <button type="submit" name="delete_item" onclick="return confirm('Delete this item?');"><i class="bx bx-x"></i></button>
        </div>
        <h3 class="name"><?=$fetch_products['name']; ?></h3>
        <input type="hidden" name="product_id" value="<?=$fetch_products['id']; ?>">
        <div class="flex">
            <p class="price">Price $<?=$fetch_products['price']; ?>/-</p>
        </div>
        <a href="checkout.php?get_id=<?=$fetch_products['id']; ?>" class="btn">Buy Now</a>
    </form>
    <?php 
                    $grand_total += $fetch_wishlist['price'];            
                }
            }
        } else {
            echo '<p class="empty">No products added yet!</p>';
        }
    ?>
</div>

    </section>
    
</section>
    <?php include 'footer.php'; ?>                    
    </div>    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="script.js"></script>
    <?php include 'alert.php'; ?>
</body>
</html>