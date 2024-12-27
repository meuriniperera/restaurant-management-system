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
    <title>Gallery Cafe - About Page</title>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="main">
    <section class="about">
            <div class="container">
                <div class="title">
                    <h2>About The Gallery Cafe</h2>
                </div>
                <div class="about-content">
                    <div class="about-img">
                        <img src="img/about-image.jpg" alt="About Image">
                    </div>
                    <div class="about-text">
                        <h3>Our Story!</h3>
                        <p>The Gallery Cafe is a beloved restaurant in Colombo, known for its unique blend of culinary art and vibrant atmosphere. Our chefs use the freshest ingredients to create dishes that are not only delicious but also visually stunning.</p>
                        <div class="about-icons">
                            <div class="icon-box">
                                <i class='bx bx-dish'></i>
                                <span>Delicious Cuisine</span>
                            </div>
                            <div class="icon-box">
                                <i class='bx bx-restaurant'></i>
                                <span>Charming Ambiance</span>
                            </div>
                            <div class="icon-box">
                                <i class='bx bx-hard-hat'></i>
                                <span>Expert Chefs</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section> 
        <section class="testimonials">
            <div class="container">
                <div class="title">
                    <h2>What People Say About Us</h2>
                </div>
                <div class="testimonials-content">
                    <div class="testimonial-box">
                        <p>"The Gallery Cafe has an amazing atmosphere and the food is simply divine. Highly recommend!"</p>
                        <span>- John Doe</span>
                    </div>
                    <div class="testimonial-box">
                        <p>"A wonderful place to dine with friends and family. The service is excellent and the dishes are beautifully presented."</p>
                        <span>- Jane Smith</span>
                    </div>
                    <div class="testimonial-box">
                        <p>"My favorite restaurant in Colombo! The Gallery Cafe never disappoints with their delicious menu and charming ambiance."</p>
                        <span>- Michael Brown</span>
                    </div>
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
