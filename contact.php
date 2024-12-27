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
    <title>Gallery Cafe - Contact Page</title>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="main">
    <section class="contact">
            <div class="container">
                <div class="title">
                    <h2>Contact Us</h2>
                    <p>We'd love to hear from you!</p>
                </div>
                <div class="contact-content">
                    <div class="contact-info">
                        <h3>Our Address</h3>
                        <p>123 Gallery Street, Colombo, Sri Lanka.</p>
                        <h3>Phone</h3>
                        <p>(123) 456-7890</p>
                        <h3>Email</h3>
                        <p>info@gallerycafe.lk</p>
                        <h3>Business Hours</h3>
                        <p>Mon - Fri: 9am - 10pm</p>
                        <p>Sat - Sun: 10am - 11pm</p>
                    </div>
                    <div class="map">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31699.9801791888!2d79.83060962937235!3d6.921838613609203!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae25969b4b4e0b3%3A0xd8cf6a0665dd5a67!2sColombo%2C%20Sri%20Lanka!5e0!3m2!1sen!2s!4v1625580840387!5m2!1sen!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </section>

        <section class="special-offers">
            <div class="container">
                <h2>Special Offers</h2>
                <div class="offers">
                    <div class="offer-item">
                        <h3>Weekend Brunch Special</h3>
                        <p>Enjoy a complimentary drink with any brunch order on Saturdays <br> and Sundays from 10 AM to 2 PM.</p>
                    </div>
                    <div class="offer-item">
                        <h3>Happy Hour</h3>
                        <p>Get 20% off on all beverages from 5 PM to 7 PM, <br> Monday through Friday.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="photo-gallery">
            <div class="container">
                <h2>Photo Gallery</h2>
                <div class="gallery">
                    <img src="img/gallery1.jpg" alt="Gallery Image 1">
                    <img src="img/gallery2.jpg" alt="Gallery Image 2">
                    <img src="img/gallery3.webp" alt="Gallery Image 3">
                    <img src="img/gallery4.jpg" alt="Gallery Image 4">
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
