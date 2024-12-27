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

// Handle add, edit, and delete operations
if (isset($_POST['add_item'])) {
    $id = unique_id();
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category = $_POST['category_id']; // Updated to category_id
    $image_url = $_POST['image_url'];
    $description = $_POST['description'];

    $insert_product = $conn->prepare("INSERT INTO `menu_items` (id, name, price, category_id, image_url, description) VALUES (?, ?, ?, ?, ?, ?)");
    $insert_product->execute([$id, $name, $price, $category, $image_url, $description]);
    $success_msg[] = 'Product added to Menu Successfully';
}

if (isset($_POST['edit_item'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category = $_POST['category_id']; // Updated to category_id
    $image_url = $_POST['image_url'];
    $description = $_POST['description'];

    $update_product = $conn->prepare("UPDATE `menu_items` SET name = ?, price = ?, category_id = ?, image_url = ?, description = ? WHERE id = ?");
    $update_product->execute([$name, $price, $category, $image_url, $description, $id]);
    $success_msg[] = 'Product updated Successfully';
}

if (isset($_POST['delete_item'])) {
    $id = $_POST['id'];

    $delete_product = $conn->prepare("DELETE FROM `menu_items` WHERE id = ?");
    $delete_product->execute([$id]);
    $success_msg[] = 'Product deleted Successfully';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin_style.css"> <!-- Link to external CSS -->
    <title>Gallery Cafe - Manage Menu</title>
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
    <div class="main">
        <h1>Manage Menu</h1>
        <!-- Add New Item Form -->
        <div class="form-container">
            <form action="" method="post">
                <h2>Add New Item</h2>
                <input type="text" name="name" placeholder="Product Name" required>
                <input type="text" name="price" placeholder="Price" required>
                <select name="category_id" required>
                    <option value="" disabled selected>Select Category</option>
                    <?php
                    try {
                        $select_categories = $conn->prepare("SELECT * FROM categories");
                        $select_categories->execute();
                        while ($fetch_category = $select_categories->fetch(PDO::FETCH_ASSOC)) {
                            echo '<option value="'.$fetch_category['id'].'">'.$fetch_category['name'].'</option>';
                        }
                    } catch (PDOException $e) {
                        echo 'Error: ' . $e->getMessage();
                    }
                    ?>
                </select>
                <input type="text" name="image_url" placeholder="Image URL" required>
                <textarea name="description" placeholder="Description" required></textarea>
                <button type="submit" name="add_item" class="btn">Add Item</button>
            </form>
        </div>

        <!-- Edit/Delete Items -->
        <div class="item-list">
            <h2>Menu Items</h2>
            <?php 
            try {
                $select_products = $conn->prepare("SELECT * FROM menu_items");
                $select_products->execute();
                
                if ($select_products->rowCount() > 0) {
                    while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
                        $product_id = $fetch_products['id'];
                        $select_category = $conn->prepare("SELECT name FROM categories WHERE id = ?");
                        $select_category->execute([$fetch_products['category_id']]);
                        $category_name = $select_category->fetchColumn();
            ?>
            <div class="item-card">
                <img src="img/<?=$fetch_products['image_url']; ?>" alt="<?=$fetch_products['name']; ?>">
                <div class="item-info">
                    <h3><?=$fetch_products['name']; ?></h3>
                    <p>Price: $<?=$fetch_products['price']; ?>/-</p>
                    <p>Category: <?=$category_name; ?></p>
                    <p>Description: <?=$fetch_products['description']; ?></p>
                    <form action="" method="post" class="edit-form">
                        <input type="hidden" name="id" value="<?=$fetch_products['id']; ?>">
                        <input type="text" name="name" value="<?=$fetch_products['name']; ?>" required>
                        <input type="text" name="price" value="<?=$fetch_products['price']; ?>" required>
                        <select name="category_id" required>
                            <?php
                            try {
                                $select_categories = $conn->prepare("SELECT * FROM categories");
                                $select_categories->execute();
                                while ($fetch_category = $select_categories->fetch(PDO::FETCH_ASSOC)) {
                                    $selected = $fetch_category['id'] == $fetch_products['category_id'] ? 'selected' : '';
                                    echo '<option value="'.$fetch_category['id'].'" '.$selected.'>'.$fetch_category['name'].'</option>';
                                }
                            } catch (PDOException $e) {
                                echo 'Error: ' . $e->getMessage();
                            }
                            ?>
                        </select>
                        <input type="text" name="image_url" value="<?=$fetch_products['image_url']; ?>" required>
                        <textarea name="description" required><?=$fetch_products['description']; ?></textarea>
                        <button type="submit" name="edit_item" class="btn">Edit Item</button>
                        <button type="submit" name="delete_item" class="btn btn-danger">Delete Item</button>
                    </form>
                </div>
            </div>
            <?php 
                    }
                } else {
                    echo '<p class="empty">No Items in Menu!</p>';
                }    
            } catch (PDOException $e) {
                echo 'Error: ' . $e->getMessage();
            }
            ?>
        </div>
    </div>                 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="script.js"></script>
    <?php include 'alert.php'; ?>
</body>
</html>
