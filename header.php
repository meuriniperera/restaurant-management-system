<header class="header">
    <div class="container flex">
        <a href="home.php" class="logo"><img src="img/logo.png.png" alt="Logo"></a>
        <nav class="navbar">
            <a href="home.php">Home</a>
            <a href="view_menu.php">Menu</a>
            <a href="search.php">Search</a>
            <a href="orders.php">Orders</a>
            <a href="about.php">About Us</a>
            <a href="contact.php">Contact Us</a>
            <a href="reservations.php">Reservations</a>
        </nav>
        <div class="icons">
            <i class="bx bxs-user" id="user-btn"></i>
            <?php
                if ($user_id) {
                    // Corrected query with backticks for table names
                    $count_wishlist_items = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
                    $count_wishlist_items->execute([$user_id]);
                    $total_wishlist_items = $count_wishlist_items->rowCount();
                } else {
                    $total_wishlist_items = 0;
                }
            ?>
            <a href="wishlist.php" class="cart-btn"><i class="bx bx-heart"></i><sup><?=$total_wishlist_items ?></sup></a>
            <?php
                if ($user_id) {
                    // Corrected query with backticks for table names
                    $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
                    $count_cart_items->execute([$user_id]);
                    $total_cart_items = $count_cart_items->rowCount();
                } else {
                    $total_cart_items = 0;
                }
            ?>
            <a href="cart.php" class="cart-btn"><i class="bx bx-cart-download"></i><sup><?=$total_cart_items ?></sup></a> 
            <i class="bx bx-list-plus" id="menu-btn" style="font-size: 2rem;"></i>
        </div>
        <div class="user-box">
            <p>username : <span><?php echo $_SESSION['user_name']; ?></span></p>
            <p>Email : <span><?php echo $_SESSION['user_email']; ?></span></p> 
            <a href="login.php" class="btn">Login</a>
            <a href="register.php" class="btn">Register</a> 
            <form method="post">
                <button type="submit" name="logout" class="logout-btn">Log out</button>
            </form> 
        </div>   
    </div> 
</header>