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
//adding products in whishlist

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
    <title>Green Coffee - Shop Page</title>
    <style>
        .img {
    width: 225px;
    height: 225px;
    object-fit: cover; /* ensures the image fills the area without distortion */
    border-radius: 10px; /* optional styling */
}

    </style>
</head>
<body>
    <?php include 'components/header.php'; ?>
    <div class="main">
         <div class="banner">
            <h1>Shop</h1>
        </div>
        <div class="title2">
         <a href="home.php">home</a><span>our shop</span>
        </div>
       <section class='products'>
            <div class="box-container">
                <?php
                    $select_products = $conn->prepare("SELECT * FROM `products`");
                    $select_products->execute();
                    if($select_products->rowCount() > 0){
                        while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
                      
                ?>
                <form action="" method="post" class="box">
                  
                        <img src="img/<?= $fetch_product['image']; ?>" alt="" class="img">
                       <div class="button">
                        <button type='submit' name='add_to_cart'><i class='bx bx-cart'></i></button>
                        <button type='submit' name='add_to_wishlist'><i class='bx bx-heart'></i></button>
                         <a href="view_page.php?pid=<?php echo $fetch_product['id']; ?>" class='bx bxs-show'></a>
                       </div>
                    <h3 class='name'><?=$fetch_product['name']; ?></h3>
                    <input type="hidden" name="product_id" value="<?=$fetch_product['id']; ?>">
                    <div class="flex">
                        <p class="price">price $<?=$fetch_product['price']; ?>/-</p>
                        <input type="number" name='qty' required min="1" max="99" maxlength="2" class="qty" >
                    </div>
                    <a href="checkout.php?get_id=<?=$fetch_product['id'];?>" class='btn'>buy now</a>
                </form>
                <?php
                      }
                    }else{
                        echo '<p class="empty">no products added yet!</p>';
                    }
                ?>
                
            </div>
       </section>
   
    <!-- Scripts at the bottom for better performance -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="script.js" defer></script>
    <?php include 'components/alert.php'; ?>
</body>
</html>