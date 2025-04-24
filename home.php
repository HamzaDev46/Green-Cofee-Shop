<?php
include 'components/connection.php';
?>
<style type="text/css">
     <?php include 'Style.css'; ?>
    
</style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Green Coffee - Home Page</title>
</head>
<body>
    <?php include 'components/header.php'; ?>
    <div class="main">
       
        <section class="home-section">
            <div class="slider">
                <div class="slider__slider slider1">
                    <div class="overlay"></div>
                    <div class="slide-detail">
                        <h1>Lorem, ipsum dolor sit</h1>
                        <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Tempore est culpa quo, dolore nam dignissimos architecto.</p>
                        <a href="view_products.php" class='btn'>shop now</a>
                    </div>
                    <div class="hero-dec-top"></div>
                    <div class="hero-dec-bottom"></div>
                </div>
                <!-- Slide End -->
                <div class="slider__slider slider2">
                    <div class="overlay"></div>
                    <div class="slide-detail">
                        <h1>Welcome to my shop</h1>
                        <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Tempore est culpa quo, dolore nam dignissimos architecto.</p>
                        <a href="view_products.php" class='btn'>shop now</a>
                    </div>
                    <div class="hero-dec-top"></div>
                    <div class="hero-dec-bottom"></div>
                </div>
                <!-- Slide End -->
                <div class="slider__slider slider3">
                    <div class="overlay"></div>
                    <div class="slide-detail">
                        <h1>Lorem, ipsum dolor sit</h1>
                        <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Tempore est culpa quo, dolore nam dignissimos architecto.</p>
                        <a href="view_products.php" class='btn'>shop now</a>
                    </div>
                    <div class="hero-dec-top"></div>
                    <div class="hero-dec-bottom"></div>
                </div>
                <!-- Slide End -->
                <div class="slider__slider slider4">
                    <div class="overlay"></div>
                    <div class="slide-detail">
                        <h1>Lorem, ipsum dolor sit</h1>
                        <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Tempore est culpa quo, dolore nam dignissimos architecto.</p>
                        <a href="view_products.php" class='btn'>shop now</a>
                    </div>
                    <div class="hero-dec-top"></div>
                    <div class="hero-dec-bottom"></div>
                </div>
                <!-- Slide End -->
                <div class="slider__slider slider5">
                    <div class="overlay"></div>
                    <div class="slide-detail">
                        <h1>Lorem, ipsum dolor sit</h1>
                        <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Tempore est culpa quo, dolore nam dignissimos architecto.</p>
                        <a href="view_products.php" class='btn'>shop now</a>
                    </div>
                    <div class="hero-dec-top"></div>
                    <div class="hero-dec-bottom"></div>
                </div>
                <!-- Slide End -->
                <div class="left-arrow"><i class="bx bxs-left-arrow"></i></div>
                <div class="right-arrow"><i class="bx bxs-right-arrow"></i></div>
            </div>
        </section> 
         <!-- home slider End -->
         <?php include 'components/footer.php'; ?>
    </div>
   
    <!-- Scripts at the bottom for better performance -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="script.js" defer></script>
    <?php include 'components/alert.php'; ?>
</body>
</html>