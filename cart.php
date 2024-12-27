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

// Delete item from cart
if (isset($_POST['delete_item'])) {
    $cart_id = $_POST['cart_id'];
    $cart_id = filter_var($cart_id, FILTER_SANITIZE_STRING); // Assuming the ID is an integer

    // Prepare the SELECT statement
    $verify_delete_items = $conn->prepare("SELECT * FROM cart WHERE id = ?");
    $verify_delete_items->execute([$cart_id]);

    // Check if the item exists
    if ($verify_delete_items->rowCount() > 0) {
        // Prepare the DELETE statement
        $delete_cart_id = $conn->prepare("DELETE FROM cart WHERE id = ?");
        $delete_cart_id->execute([$cart_id]);
        $success_msg[] = "Cart item deleted successfully.";
    } else {
        $warning_msg[] = "Cart item already deleted.";
    }
}
// Empty cart
if (isset($_POST['empty_cart'])) {
    $varify_empty_item = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
    $varify_empty_item->execute([$user_id]);

    // Check if the item exists
    if ($varify_empty_item->rowCount() > 0) {
        // Prepare the DELETE statement
        $delete_cart_id = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
        $delete_cart_id->execute([$user_id]);
        $success_msg[] = "Emptying Cart successful.";
    } else {
        $warning_msg[] = "Cart item already deleted.";
    }
}
// Update Product in Cart
if (isset($_POST['update_cart'])) {
    $cart_id = $_POST['cart_id'];
    $cart_id = filter_var($cart_id, FILTER_SANITIZE_STRING);
    $quantity = $_POST['quantity'];
    $quantity = filter_var($quantity, FILTER_SANITIZE_STRING);

    $update_quantity = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
    $update_quantity->execute([$quantity, $cart_id]);

    $success_msg[] = 'Cart Quantity updated Successfully!';
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
    <title>Gallery Cafe - Cart Page</title>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="main">
    <section class="products">
    <div class="box-container">
    <?php 
        $grand_total = 0;
        // Corrected the table name quoting
        $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
        $select_cart->execute([$user_id]);
        if ($select_cart->rowCount() > 0) {
            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
                // Corrected the table name quoting
                $select_products = $conn->prepare("SELECT * FROM `menu_items` WHERE id = ?");
                $select_products->execute([$fetch_cart['product_id']]);
                if ($select_products->rowCount() > 0) {
                    $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);
    ?>
    <form method="post" action="" class="box">
        <input type="hidden" name="cart_id" value="<?=$fetch_cart['id']; ?>">
        <img src="img/<?=$fetch_products['image_url']; ?>"class="img">
        <h3 class="name"><?=$fetch_products['name']; ?></h3>
        <div class="flex">
            <p class="price">Price $<?=$fetch_products['price']; ?>/-</p>
            <input type="number" name="quantity" required min ="1" value="<?=$fetch_cart['quantity']; ?>" max="99" maxlength="2" class="quantity">
            <button type="submit" name="update_cart" class="bx bxs-edit fa-edit"></button>
        </div>
        <p class="sub-total">Sub Total : <span>$<?=$sub_total = ($fetch_cart['quantity']* $fetch_cart['price']) ?></span></p>

        <button type="submit" name="delete_item" class="btn" onclick="return confirm('Delete this item')">Delete</button>
        
    </form>
    <?php 
                    $grand_total += $sub_total;            
                }else{
                    echo '<p class="empty">Product was not found</p>';
                }
            }
        } else {
            echo '<p class="empty">No products added yet!</p>';
        }
    ?>
</div>
<?php 
    if ($grand_total !=0) {

    
?>
<div class="cart-total">
    <p>Total Amount Payable : <span>$ <?= $grand_total; ?>/-</span></p>
    <div class="button">
        <form method="post">
            <button type="submit" name="empty_cart" class="btn" onclick="return confirm('Are you sure you want to empty your cart?')">Empty Cart</button>
            <a href="checkout.php" class="btn">Proceed to checkout</a>
        </form>
    </div>
</div>
<?php } ?>
    </section>
    
</section>
    <?php include 'footer.php'; ?>                    
    </div>    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="script.js"></script>
    <?php include 'alert.php'; ?>
</body>
</html>