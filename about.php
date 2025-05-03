<?php
include 'components/connection.php';
session_start();
if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}
else{
    $user_id = '';
}
if(isset($_POST['logout'])){
    session_destroy();
    header('location:login.php');
}
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
    <title>Green Coffee - about us Page</title>
</head>
<body>
    <?php include 'components/header.php'; ?>
    <div class="main">
        <div class="banner">
            <h1>about us</h1>
        </div>
        <div class="title2">
         <a href="home.php">home</a><span>about</span>
        </div>
       <div class="about-category">
            <div class="box">
                <img src="img/3.webp" alt="">
                <div class="detail">
                    <span>cofee</span>
                    <h1>lemon green</h1>
                    <a href="view_products.php" class='btn'>shop now</a>
                </div>
            </div>
            <div class="box">
                <img src="img/2.webp" alt="">
                <div class="detail">
                    <span>cofee</span>
                    <h1>lemon green</h1>
                    <a href="view_products.php" class='btn'>shop now</a>
                </div>
            </div>
            <div class="box">
                <img src="img/about.png" alt="">
                <div class="detail">
                    <span>cofee</span>
                    <h1>lemon Teaname</h1>
                    <a href="view_products.php" class='btn'>shop now</a>
                </div>
            </div>
            <div class="box">
                <img src="img/1.webp" alt="">
                <div class="detail">
                    <span>cofee</span>
                    <h1>lemon green</h1>
                    <a href="view_products.php" class='btn'>shop now</a>
                </div>
            </div>
       </div>
       <section class='services'>
        <div class="title">
            <img src="img/download.png" alt="" class="logo">
            <h1>why choose us</h1>
            <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Dolorum assumenda vitae quae maxime molestiae magnam?</p>
        </div>
            <div class="box-container">
                <div class="box">
                    <img src="img/icon2.png" alt="">
                    <div class="detail">
                        <h3>great savings</h3>
                        <p>save big every order</p>
                    </div>
                </div>
                <div class="box">
                    <img src="img/icon1.png" alt="">
                    <div class="detail">
                        <h3>24*7 support</h3>
                        <p>one-on-one sup</p>
                    </div>
                </div>
                <div class="box">
                    <img src="img/icon0.png" alt="">
                    <div class="detail">
                        <h3>gift vouchers</h3>
                        <p>vouchers on every festivals</p>
                    </div>
                </div>
                <div class="box">
                    <img src="img/icon.png" alt="">
                    <div class="detail">
                        <h3>worldwide delivery </h3>
                        <p>dropship delivery</p>
                    </div>
                </div>
            </div>
          </section>
          <div class="about">
            <div class="row">
                <div class="img-box">
                    <img src="img/3.png" alt="">
                </div>
                <div class="detail">
                    <h1>visit our beautiful showroom!</h1>
                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. 
                        Quasi numquam non iusto fuga minus commodi. 
                        Quaerat temporibus possimus exercitationem?
                    </p>
                    <a href="view_products.php"class='btn'>shop now</a>
                </div>
            </div>
          </div>
        <div class="testimonial-container">
            <div class="title">
                <img src="img/download.png" alt="" class="logo">
                <h1>what our customers say</h1>
                <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Dolorum assumenda vitae quae maxime molestiae magnam?</p>
            </div>  
                <div class="container">
                    <div class="testimonial-item active">
                        <img src="img/01.jpg" alt="">
                        <h1>sara smith</h1>
                        <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. 
                            Quas qui velit earum dolorum enim consectetur sit maiores delectus fugiat voluptatum!
                        </p>
                    </div>
                    <div class="testimonial-item ">
                        <img src="img/02.jpg" alt="">
                        <h1>john smith</h1>
                        <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. 
                            Quas qui velit earum dolorum enim consectetur sit maiores delectus fugiat voluptatum!
                        </p>
                    </div>
                    <div class="testimonial-item ">
                        <img src="img/03.jpg" alt="">
                        <h1>salena ansari</h1>
                        <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. 
                            Quas qui velit earum dolorum enim consectetur sit maiores delectus fugiat voluptatum!
                        </p>
                    </div>
                    <div class="testimonial-item ">
                        <img src="img/04.png" alt="" height="140px">
                        <h1>Marina ansari</h1>
                        <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. 
                            Quas qui velit earum dolorum enim consectetur sit maiores delectus fugiat voluptatum!
                        </p>
                    </div>
                    <div class="left-arrow" onclick="nextSlide()"><i class='bx bxs-left-arrow-alt'></i></div>
                    <div class="right-arrow" onclick="prevSlide()"><i class='bx bxs-right-arrow-alt'></i></div>
                </div>    
       </div>
         <?php include 'components/footer.php'; ?>
    </div>
   
    <!-- Scripts at the bottom for better performance -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="script.js" defer></script>
    <?php include 'components/alert.php'; ?>
    <script>
        let slides = document.querySelectorAll('.testimonial-item');
        let index = 0;

        function nextSlide() {
        slides[index].classList.remove('active');
        index = (index + 1) % slides.length;
        slides[index].classList.add('active');
        }

        function prevSlide() {
        slides[index].classList.remove('active');
        index = (index - 1 + slides.length) % slides.length;
        slides[index].classList.add('active');
        }
    </script>
</body>
</html>