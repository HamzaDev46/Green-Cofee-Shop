<?php 
include 'components/connection.php'; 
session_start();  

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

if (isset($_POST['logout'])) {
    session_destroy();
    header('location:login.php');
    exit;
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
    <title>Green Coffee - Order Page</title>
    <style>
        body {
            background: #e8f5e9;
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .main {
            max-width: 1100px;
            margin: 40px auto 0 auto;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(34, 139, 34, 0.08);
            padding: 32px 36px 36px 36px;
        }
       
        .banner h1 {
            margin: 0;
            font-size: 2.5rem;
            font-weight: 700;
            letter-spacing: 1px;
        }
        .title2 {
            margin: 18px 0 32px 0;
            font-size: 1.1rem;
            color: #388e3c;
        }
        .title2 a {
            color: #388e3c;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }
        .title2 a:hover {
            color: #1b5e20;
            text-decoration: underline;
        }
        .orders .title {
            display: flex;
            align-items: center;
            gap: 18px;
            margin-bottom: 18px;
        }
        .orders .title img.logo {
            width: 54px;
            height: 54px;
            border-radius: 50%;
            border: 2px solid #388e3c;
            background: #fff;
            object-fit: cover;
        }
        .orders .title h1 {
            font-size: 2rem;
            color: #388e3c;
            margin: 0;
            font-weight: 600;
        }
        .orders .title p {
            color: #757575;
            margin: 0 0 0 18px;
            font-size: 1rem;
        }
        .box-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(270px, 1fr));
            gap: 28px;
            margin-top: 18px;
        }
        .box {
            background: #f1f8e9;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(56, 142, 60, 0.07);
            padding: 18px 16px 16px 16px;
            transition: box-shadow 0.2s, transform 0.2s;
            border: 2px solid transparent;
        }
        .box:hover {
            box-shadow: 0 6px 24px rgba(56, 142, 60, 0.13);
            transform: translateY(-4px) scale(1.03);
        }
        .box a {
            text-decoration: none;
            color: inherit;
            display: block;
        }
        .box .date {
            font-size: 0.98rem;
            color: #757575;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .box .date i {
            color: #388e3c;
            font-size: 1.1rem;
        }
        .image {
            width: 225px;
            height: 225px;
            object-fit: cover;
            border-radius: 10px;
            display: block;
            margin: 0 auto 12px auto;
            box-shadow: 0 2px 8px rgba(56, 142, 60, 0.09);
            background: #fff;
        }
        .box .row {
            padding: 0 8px;
        }
        .box .name {
            font-size: 1.18rem;
            font-weight: 600;
            color: #388e3c;
            margin: 0 0 8px 0;
        }
        .box .price {
            font-size: 1rem;
            color: #333;
            margin: 0 0 8px 0;
        }
        .box .status {
            font-size: 1rem;
            font-weight: 500;
            margin: 0;
            padding: 4px 10px;
            border-radius: 8px;
            background: #e0f2f1;
            display: inline-block;
        }
        /* .box .status[style*="green"] {
            background: #e8f5e9;
            color: #388e3c !important;
        }
        .box .status[style*="red"] {
            background: #ffebee;
            color: #d32f2f !important;
        }
        .box .status[style*="orange"] {
            background: #fff3e0;
            color: #f57c00 !important;
        } */
        .empty {
            text-align: center;
            color: #757575;
            font-size: 1.2rem;
            margin: 48px 0;
        }
        @media (max-width: 700px) {
            .main {
            padding: 12px 4vw;
            }
            .banner {
            padding: 18px 0 12px 12px;
            }
            .orders .title {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
            }
            .image {
            width: 100%;
            height: 180px;
            }
        }
       
    </style>
</head>
<body>
<?php include 'components/header.php'; ?>

<div class="main">
    <div class="banner">
        <h1>my order</h1>
    </div>
    <div class="title2">
        <a href="home.php">home</a><span>/order</span>
    </div>

    <section class='orders'>
        
          <div class="title">
                <img src="img/download.png" alt="" class="logo">
                <h1>my orders</h1>
                <p>Here you can find all your past orders.</p>
            </div>
            <div class="box-container">
                <?php
                $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ? ORDER BY date DESC");
                $select_orders->execute([$user_id]);
                if ($select_orders->rowCount() > 0) {
                    while ($fetch_order = $select_orders->fetch(PDO::FETCH_ASSOC)) {
                        $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
                        $select_products->execute([$fetch_order['product_id']]);
                        if($select_products->rowcount()>0){
                while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
                ?>
                    <div class="box" <?php if($fetch_order['status']=='cancel'){echo 'style="border:2px solid red"';} ?>>
                        <a href="view_order.php?order_id=<?= $fetch_order['id']; ?>">
                            <p class="date"><i class="bi bi-calender-fill"></i><span><?= $fetch_order['date']; ?></span></p>
                            <img src="img/<?= $fetch_product['image']; ?>" class="image">
                            <div class="row">
                                <h3 class="name"><?= $fetch_product['name']; ?></h3>
                                <p class="price">Price : Rs<?= $fetch_order['price']; ?> x <?= $fetch_order['qty']; ?></p>
                                <p class="status" style="color:<?php if($fetch_order['status']=='delivered'){ echo 'green';}elseif($fetch_order['status']=='canceled'){echo 'red';}else{ echo 'orange';} ?>"><?= $fetch_order['status']; ?></p>
                            </div>
                        </a>
                    </div>
                <?php
                            }
                        }
                    }
                } else {
                    echo "<p class='empty'>No orders placed yet!</p>";
                }
                
                ?>
            </div>
       
        <?php include 'components/footer.php'; ?>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="script.js" defer></script>
    <?php include 'components/alert.php'; ?>
</div>
</body>
</html>
