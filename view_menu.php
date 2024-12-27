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
    <title>Gallery Cafe - Menu Page</title>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="main">
    <section class="products">
    <div class="box-container">
        <?php 
            try {
                // Correct SQL query with backticks or no quotes
                $select_products = $conn->prepare("SELECT * FROM menu_items");
                // Alternatively: $select_products = $conn->prepare("SELECT * FROM menu_items");
                $select_products->execute();
                
                if ($select_products->rowCount() > 0) {
                    while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
        ?>
        <form action="" method="post" class="box"> 
            <img src="img/<?=$fetch_products['image_url']; ?>" class="img">
            <div class="button">
                <button type="submit" name="add_to_cart"><i class="bx bx-cart"></i></button>
                <button type="submit" name="add_to_wishlist"><i class="bx bx-heart"></i></button>
                <a href = "view_page.php?pid=<?php echo $fetch_products['id']; ?>" class="bx bxs-show"></a>
            </div>
            <h3 class="name"><?=$fetch_products['name']; ?></h3>
            <input type="hidden" name="product_id" value="<?=$fetch_products['id']; ?>">
            <div class="flex">
                <p class="price">Price $<?=$fetch_products['price']; ?>/-</p>
                <input type="number" name="quantity" required min="1" value="1" max="99" maxlength="2" class="quantity">
            </div>
            <a href="checkout.php?get_id=<?=$fetch_products['id']; ?>" class="btn">Buy Now</a>
        </form>
        <?php 
                    }
                }else{
                    echo '<p class="empty">No Products Added yet!</p>';
                }    
            } catch (PDOException $e) {
                echo 'Error: ' . $e->getMessage();
            }
        ?>
    </div>
</section>
    <?php include 'footer.php'; ?>                    
    </div>    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="script.js"></script>
    <?php include 'alert.php'; ?>
</body>
</html>