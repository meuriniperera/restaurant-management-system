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
    <title>Gallery Cafe - Home Page</title>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="main">
        <section class="home-section">
            <div class="slider">
            <div class="slider__slider slide1">
                <div class="over-lay"></div>
                <div class="slide-detail">
                    <h1>Welcome to The Gallery Cafe</h1>
                    <p>Welcome to The Gallery Cafe, where every meal is a masterpiece.</p>
                    <a href="view_menu.php" class="btn">Order Now</a>
                </div> 
                <div class="hero-dec-top"></div>
                <div class="hero-dec-bottom"></div>  
            </div>
            <!-- slide end -->
            <div class="slider__slider slide2">
                <div class="over-lay"></div>
                <div class="slide-detail">
                    <h1>Welcome to Our Restuarant</h1>
                    <p>Welcome to The Gallery Café, where every meal is a masterpiece.</p>
                    <a href="view_menu.php" class="btn">Order Now</a>
                </div> 
                <div class="hero-dec-top"></div>
                <div class="hero-dec-bottom"></div>  
            </div>
            <!-- slide end -->
            <div class="slider__slider slide3">
                <div class="over-lay"></div>
                <div class="slide-detail">
                    <h1>Welcome to Our Restuarant</h1>
                    <p>Welcome to The Gallery Café, where every meal is a masterpiece.</p>
                    <a href="view_menu.php" class="btn">Order Now</a>
                </div> 
                <div class="hero-dec-top"></div>
                <div class="hero-dec-bottom"></div>  
            </div>
            <!-- slide end -->
            <div class="slider__slider slide4">
                <div class="over-lay"></div>
                <div class="slide-detail">
                    <h1>Welcome to Our Restuarant</h1>
                    <p>Welcome to The Gallery Café, where every meal is a masterpiece.</p>
                    <a href="view_menu.php" class="btn">Order Now</a>
                </div> 
                <div class="hero-dec-top"></div>
                <div class="hero-dec-bottom"></div>  
            </div>
            <!-- slide end -->
            <div class="slider__slider slide5">
                <div class="over-lay"></div>
                <div class="slide-detail">
                    <h1>Welcome to Our Restuarant</h1>
                    <p>Welcome to The Gallery Café, where every meal is a masterpiece.</p>
                    <a href="view_menu.php" class="btn">Order Now</a>
                </div> 
                <div class="hero-dec-top"></div>
                <div class="hero-dec-bottom"></div>  
            </div>
            </div>
            <!-- slide end --> 
            <div class="left-arrow"><i class="bx bxs-left-arrow"></i></div>
            <div class="right-arrow"><i class="bx bxs-right-arrow"></i></div>
        <!-- home slider end -->
        </section>
        <section class ="thumb">
            <div class="box-container">
                <div class="box">
                    <img src="img/thumb1.png">
                    <h3>Sri Lankan Cuisine</h3>
                    <p>Discover the true essence of Sri Lankan cuisine, crafted to perfection.</p>
                    <i class="bx bx-chevron-right"></i>
                </div>
                <div class="box">
                    <img src="img/thumb2.png">
                    <h3>Thai Cuisine</h3>
                    <p>Experience the vibrant flavors of Thai cuisine, crafted to perfection.</p>
                    <i class="bx bx-chevron-right"></i>
                </div>
                <div class="box">
                    <img src="img/thumb3.png">
                    <h3>Chinese Cuisine</h3>
                    <p>Savor the authentic tastes of Chinese cuisine, crafted to perfection.</p>
                    <i class="bx bx-chevron-right"></i>
                </div>
                <div class="box">
                    <img src="img/thumb4.png">
                    <h3>Italian Cuisine</h3>
                    <p>Indulge in the rich flavors of Italian cuisine, crafted to perfection.</p>
                    <i class="bx bx-chevron-right"></i>
                </div>
            </div>
        </section>
        <section class="container">
            <div class="box-container">
                <div class="box">
                    <img src="img/about-us.png">
                </div>
                <div class="box">
                    <img src="img/download.jpg">
                    <span>Healthy Food</span>
                    <h1>Save up to 50% off</h1>
                </div>
            </div>
        </section>
        <section class="shop">
            <div class="title">
                <img src="img/download.jpg">
                <h1>Trending Products</h1>
            </div>
            <div class="row">
                <div class="row-detail">
                    <img src="img/banner.jpg">
                    <div class="top-footer">
                        <h1>Save up to 30% off</h1>
                    </div>
                </div>
            </div>
            <div class="box-container">
                <div class="box">
                    <img src="img/card.jpg">
                    <a href="view_menu.php" class="btn">Order Now</a>
                </div>
                <div class="box">
                    <img src="img/card1.jpg">
                    <a href="view_menu.php" class="btn">Order Now</a>
                </div>
                <div class="box">
                    <img src="img/card2.jpg">
                    <a href="view_menu.php" class="btn">Order Now</a>
                </div>
                <div class="box">
                    <img src="img/card3.webp">
                    <a href="view_menu.php" class="btn">Order Now</a>
                </div>
                <div class="box">
                    <img src="img/card4.jpg">
                    <a href="view_menu.php" class="btn">Order Now</a>
                </div>
                <div class="box">
                    <img src="img/card5.jpg">
                    <a href="view_menu.php" class="btn">Order Now</a>
                </div>
            </div>
        </section>
        <section class="shop-category">
            <div class="box-container">
                <div class="box">
                    <img src="img/promo1.jpg">
                    <div class="detail">
                        <span>Exclusive Event</span>
                        <h1>Wine Tasting Evening</h1>
                    </div>
                </div>
                <div class="box">
                    <img src="img/promo2.jpg">
                    <div class="detail">
                        <span>Live Performance</span>
                        <h1>Live Music Brunch</h1>
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
