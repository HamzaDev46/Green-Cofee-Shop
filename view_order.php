<?php 
include 'components/connection.php'; 
session_start();  

$user_id = $_SESSION['user_id'] ?? '';

// Logout handler
if (isset($_POST['logout'])) {
    session_destroy();
    header('location:login.php');
    exit;
}

// Redirect if order_id is missing
if (!isset($_GET['order_id'])) {
    header('location:order.php');
    exit;
}

$get_id = $_GET['order_id'];

// Cancel order handler
if (isset($_POST['cancel_order'])) {
    $cancel_order = $conn->prepare("UPDATE `orders` SET status = ? WHERE id = ?");
    $cancel_order->execute(['canceled', $get_id]);
    header('location:order.php?cancel=success');
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
    <title>Green Coffee - Order Detail</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
</head>
<body>
<?php include 'components/header.php'; ?>

<div class="main">
    <div class="banner">
        <h1>Order Detail</h1>
    </div>
    <div class="title2">
        <a href="home.php">Home</a><span>/ Orders Detail</span>
    </div>

    <section class='order-detail'>
        <div class="title">
            <img src="img/download.png" alt="" class="logo">
            <h1>Order Detail</h1>
            <p>Details of your selected order.</p>
        </div>

        <div class="box-container">
        <?php
        $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE id = ? LIMIT 1");
        $select_orders->execute([$get_id]);

        if ($select_orders->rowCount() > 0) {
            $fetch_order = $select_orders->fetch(PDO::FETCH_ASSOC);
            $select_product = $conn->prepare("SELECT * FROM `products` WHERE id = ? LIMIT 1");
            $select_product->execute([$fetch_order['product_id']]);
            if ($select_product->rowCount() > 0) {
                $fetch_product = $select_product->fetch(PDO::FETCH_ASSOC);
                $grand_total = $fetch_order['price'] * $fetch_order['qty'];
                ?>
                <div class="box">
                    <div class="col">
                        <p class="title"><i class='bx bx-calendar'></i> Order Date: <?= $fetch_order['date']; ?></p>
                        <img src="img/<?= $fetch_product['image']; ?>" class="image" alt="Product Image">
                        <p class="price"><?= $fetch_product['price']; ?> x <?= $fetch_order['qty']; ?></p>
                        <h3 class="name"><?= $fetch_product['name']; ?></h3>
                        <p class="grand-total">Total-amount-payable : <span>$<?= $grand_total; ?></span></p>
                    </div>
                    <div class="col">
                        <p class="title">Billing Address</p>
                        <p class="user"><i class="bi bi-person-bounding-box icon"></i><?= $fetch_order['name']; ?></p><br>
                        <p class="email"><i class="bi bi-envelope-fill icon"></i><?= $fetch_order['email']; ?></p><br>
                        <p class="phone"><i class="bi bi-telephone-fill icon"></i><?= $fetch_order['number']; ?></p><br>
                        <p><i class="bi bi-geo-alt-fill icon"></i><?= $fetch_order['address']; ?></p><br>

                        <p class="title">Status</p>
                        <p class="status" style="color:<?php
                            if($fetch_order['status']=='delivered') {
                                echo 'green';
                            } elseif($fetch_order['status']=='canceled') {
                                echo 'red';
                            } else {
                                echo 'orange';
                            } ?>">
                            <?= $fetch_order['status']; ?>
                        </p>

                        <?php if ($fetch_order['status'] == 'canceled') { ?>
                            <a href="checkout.php?get_id=<?= $fetch_product['id']; ?>" class="btn">Reorder</a>
                        <?php } else { ?>
                            <button type="button" class="btn cancel-btn">Cancel Order</button>
                        <?php } ?>
                    </div>
                </div>
                <?php
            } else {
                echo "<p class='empty'>Product not found!</p>";
            }
        } else {
            echo "<p class='empty'>No order details found!</p>";
        }
        ?>
        </div>
    </section>

    <?php include 'components/footer.php'; ?>
</div>

<script src="script.js" defer></script>
<?php include 'components/alert.php'; ?>

<!-- SweetAlert confirmation for cancel -->
<script>
document.querySelector('.cancel-btn')?.addEventListener('click', function () {
    swal({
        title: "Are you sure?",
        text: "Do you really want to cancel your order?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
    .then((willCancel) => {
        if (willCancel) {
            const form = document.createElement('form');
            form.method = 'post';
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'cancel_order';
            input.value = '1';
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }
    });
});
</script>

</body>
</html>
