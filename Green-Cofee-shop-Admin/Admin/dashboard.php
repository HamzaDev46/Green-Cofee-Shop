<?php
    include '../components/connection.php';
    session_start();
    $admin_id = $_SESSION['admin_id'];
    if (!isset($admin_id)) {
        header('location: login.php');
        exit;
    }

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="admin_style.css?v=<? echo time(); ?>">
    <title>Green Coffee - Admin panel - Dashboard</title>
</head>

<body>
    <?php include '../components/admin_header.php'; ?>

    <div class="main">
        <div class="banner">
            <h1>Dashboard</h1>
        </div>
        <div class="title2">
            <a href="dashboard.php">home</a><span> / Dashboard</span>
        </div>
        <section class="dashboard">
            <h1 class="heading">dashboard</h1>
            <div class="box-container">
                <div class="box">
                    <h3>welcome!</h3>
                    <p> <?= $fetch_profile['name']; ?></p>
                    <a href="" class="btn">profile</a>
                </div>
                <div class="box">
                    <?php
                    $select_product = $conn->prepare("SELECT * FROM `products`");
                    $select_product->execute();
                    $total_products = $select_product->rowCount();
                    ?>
                    <h3>Total Products</h3>
                    <p><?= $total_products; ?></p>
                    <a href="add_products.php" class="btn">add new products</a>
                </div>
                <div class="box">
                    <?php
                        $select_active_product = $conn->prepare("SELECT * FROM `products` WHERE status=?");
                        $select_active_product->execute(['active']);
                        $total_active_products = $select_active_product->rowCount();
                    ?>
                    <h3>Total active Products</h3>
                    <p><?= $total_active_products; ?></p>
                    <a href="view_product.php" class="btn">view active products</a>
                </div>
                <div class="box">
                    <?php
                        $select_inactive_product = $conn->prepare("SELECT * FROM `products` WHERE status=?");
                        $select_inactive_product->execute(['inactive']);
                        $total_inactive_products = $select_inactive_product->rowCount();
                    ?>
                    <h3>Total inactive Products</h3>
                    <p><?= $total_inactive_products; ?></p>
                    <a href="view_product.php" class="btn">view inactive products</a>
                </div>
                <div class="box">
                    <?php
                    $select_users = $conn->prepare("SELECT * FROM `users`");
                    $select_users->execute();
                    $total_users = $select_users->rowCount();
                    ?>
                    <h3>Total Users</h3>
                    <p><?= $total_users; ?></p>
                    <a href="view_users.php" class="btn">view users</a>
                </div>
                <div class="box">
                    <?php
                    $select_admin = $conn->prepare("SELECT * FROM `admin`");
                    $select_admin->execute();
                    $total_admin = $select_admin->rowCount();
                    ?>
                    <h3>Total Admins</h3>
                    <p><?= $total_admin; ?></p>
                    <a href="view_admin.php" class="btn">view admins</a>
                </div>
                <div class="box">
                    <?php
                    $select_message = $conn->prepare("SELECT * FROM `admin_message`");
                    $select_message->execute();
                    $total_message = $select_message->rowCount();
                    ?>
                    <h3>Total unread Messages</h3>
                    <p><?= $total_message; ?></p>
                    <a href="view_message.php" class="btn">view messages</a>
                </div>
                <div class="box">
                    <?php
                    $select_admin_orders = $conn->prepare("SELECT * FROM `orders`");
                    $select_admin_orders->execute();
                    $total_admin_orders = $select_admin_orders->rowCount();
                    ?>
                    <h3>Total  orders</h3>
                    <p><?= $total_admin_orders; ?></p>
                    <a href="view_orders.php" class="btn">view  orders</a>
                </div>
                <div class="box">
                    <?php
                    $select_canceled_orders = $conn->prepare("SELECT * FROM `orders` WHERE status=?");
                    $select_canceled_orders->execute(['canceled']);
                    $total_canceled_orders = $select_canceled_orders->rowCount();
                    ?>
                    <h3>Total Canceled orders</h3>
                    <p><?= $total_canceled_orders; ?></p>
                    <a href="view__orders.php" class="btn">view cancelled orders</a>
                </div>
                <div class="box">
                    <?php
                    $select_confirm_orders = $conn->prepare("SELECT * FROM `orders` WHERE status=?");
                    $select_confirm_orders->execute(['confirmed']);
                    $total_confirm_orders = $select_confirm_orders->rowCount();
                    ?>
                    <h3>Total Confirmed orders</h3>
                    <p><?= $total_confirm_orders; ?></p>
                    <a href="view__orders.php" class="btn">view confirm orders</a>
                </div>
            </div>
        </section>
    </div>


    <!-- Scripts at the bottom for better performance -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script type="text/javascript" src="script.js"></script>
    <?php include '../components/alert.php'; ?>
</body>

</html>