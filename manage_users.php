<?php
include 'connection.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("location: admin_login.php");
    exit();
}

// Add New User
if (isset($_POST['add_user'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $add_user = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $add_user->execute([$name, $email, $password]);
        $success_msg[] = 'User added successfully!';
    } catch (Exception $e) {
        $error_msg[] = 'Error: ' . $e->getMessage();
    }
}

// Edit User
if (isset($_POST['update_user'])) {
    $user_id = $_POST['user_id'];
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    try {
        $update_user = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        $update_user->execute([$name, $email, $user_id]);
        $success_msg[] = 'User updated successfully!';
    } catch (Exception $e) {
        $error_msg[] = 'Error: ' . $e->getMessage();
    }
}

// Delete User
if (isset($_GET['delete_id'])) {
    $user_id = $_GET['delete_id'];

    try {
        $delete_user = $conn->prepare("DELETE FROM users WHERE id = ?");
        $delete_user->execute([$user_id]);
        $success_msg[] = 'User deleted successfully!';
    } catch (Exception $e) {
        $error_msg[] = 'Error: ' . $e->getMessage();
    }
}

// Fetch Users
$select_users = $conn->prepare("SELECT * FROM users");
$select_users->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin_style.css"> <!-- Link to external CSS -->
    <title>Manage Users - Gallery Cafe</title>
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
    
    <main>
        <section class="manage-users">
            <h2>Manage Users</h2>
            
            <!-- Add User Form -->
            <div class="form-container">
                <h3>Add New User</h3>
                <form method="POST">
                    <div class="input-field">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="input-field">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="input-field">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <button type="submit" name="add_user" class="btn">Add User</button>
                </form>
            </div>

            <!-- Update User Form (if needed) -->
            <?php if (isset($_GET['edit_id'])): ?>
                <?php
                $user_id = $_GET['edit_id'];
                $select_user = $conn->prepare("SELECT * FROM users WHERE id = ?");
                $select_user->execute([$user_id]);
                $user = $select_user->fetch(PDO::FETCH_ASSOC);
                ?>
                <div class="form-container">
                    <h3>Edit User</h3>
                    <form method="POST">
                        <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
                        <div class="input-field">
                            <label for="name">Name</label>
                            <input type="text" id="name" name="name" value="<?= $user['name']; ?>" required>
                        </div>
                        <div class="input-field">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="<?= $user['email']; ?>" required>
                        </div>
                        <button type="submit" name="update_user" class="btn">Update User</button>
                    </form>
                </div>
            <?php endif; ?>

            <!-- User List -->
            <div class="user-list">
                <h3>Current Users</h3>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($user = $select_users->fetch(PDO::FETCH_ASSOC)): ?>
                            <tr>
                                <td><?= $user['id']; ?></td>
                                <td><?= $user['name']; ?></td>
                                <td><?= $user['email']; ?></td>
                                <td>
                                    <a href="manage_users.php?edit_id=<?= $user['id']; ?>" class="btn-edit">Edit</a>
                                    <a href="manage_users.php?delete_id=<?= $user['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <script src="script.js"></script>
</body>
</html>
