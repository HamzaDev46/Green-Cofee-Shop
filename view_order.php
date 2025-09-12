<?php
include 'components/connection.php';
session_start();

$user_id = $_SESSION['user_id'] ?? '';
if (!$user_id) {
    header('location:login.php');
    exit;
}

if (!isset($_GET['order_id'])) {
    header('location:order.php');
    exit;
}

$get_id = $_GET['order_id'];

// Cancel order
if (isset($_POST['cancel_order'])) {
    $cancel_order = $conn->prepare("UPDATE `orders` SET status = ? WHERE id = ?");
    $cancel_order->execute(['canceled', $get_id]);
    header('location:view_order.php?order_id='.$get_id.'&cancel=success');
    exit;
}

// Fetch order details
$select_orders = $conn->prepare("SELECT * FROM `orders` WHERE id = ? AND user_id = ? LIMIT 1");
$select_orders->execute([$get_id, $user_id]);
$fetch_order = $select_orders->fetch(PDO::FETCH_ASSOC);

if (!$fetch_order) {
    die("Order not found.");
}

// Fetch product info
$select_product = $conn->prepare("SELECT * FROM `products` WHERE id = ? LIMIT 1");
$select_product->execute([$fetch_order['product_id']]);
$fetch_product = $select_product->fetch(PDO::FETCH_ASSOC);

$grand_total = $fetch_order['price'] * $fetch_order['qty'];

// Status display: matches admin
$status = $fetch_order['status'];
switch($status){
    case 'pending': $color='orange'; break;
    case 'complete': $color='blue'; break;
    case 'delivered': $color='green'; break;
    case 'canceled': $color='red'; break;
    default: $color='black'; break;
}
?>

<style><?php include 'Style.css'; ?></style>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Order Detail</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
</head>
<body>
<?php include 'components/header.php'; ?>

<div class="main">
    <div class="banner"><h1>Order Detail</h1></div>
    <div class="title2"><a href="home.php">Home</a><span>/ Order Detail</span></div>

    <section class='order-detail'>
        <div class="title">
            <img src="img/download.png" alt="" class="logo">
            <h1>Order Detail</h1>
            <p>Details of your selected order.</p>
        </div>

        <div class="box-container">
            <div class="box">
                <div class="col">
                    <p class="title"><i class='bx bx-calendar'></i> Order Date: <?= $fetch_order['date']; ?></p>
                    <img src="img/<?= $fetch_product['image']; ?>" class="image" alt="Product Image">
                    <p class="price"><?= $fetch_product['price']; ?> x <?= $fetch_order['qty']; ?></p>
                    <h3 class="name"><?= $fetch_product['name']; ?></h3>
                    <p class="grand-total">Total: <span>$<?= $grand_total; ?></span></p>
                </div>

                <div class="col">
                    <p class="title">Billing Address</p>
                    <p class="user"><i class="bi bi-person-bounding-box icon"></i><?= $fetch_order['name']; ?></p>
                    <p class="email"><i class="bi bi-envelope-fill icon"></i><?= $fetch_order['email']; ?></p>
                    <p class="phone"><i class="bi bi-telephone-fill icon"></i><?= $fetch_order['number']; ?></p>
                    <p><i class="bi bi-geo-alt-fill icon"></i><?= $fetch_order['address']; ?></p>

                    <p class="title">Status</p>
                    <p class="status" style="color:<?= $color; ?>"><?= ucfirst($status); ?></p>

                    <!-- Cancel button always visible -->
                    <form method="post" class="cancel-form">
                        <input type="hidden" name="cancel_order" value="1">
                        <?php if($status != 'canceled'): ?>
                            <button type="submit" class="btn cancel-btn">Cancel Order</button>
                        <?php else: ?>
                            <button type="button" class="btn" disabled>Order Already Canceled</button>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Total Canceled Orders Section -->
    <section class="canceled-orders">
        <h2>Total Canceled Orders</h2>
        <?php
        $select_canceled = $conn->prepare("SELECT * FROM `orders` WHERE user_id=? AND status='canceled' ORDER BY date DESC");
        $select_canceled->execute([$user_id]);

        if($select_canceled->rowCount() > 0){
            while($order = $select_canceled->fetch(PDO::FETCH_ASSOC)){
                $select_prod = $conn->prepare("SELECT * FROM `products` WHERE id=? LIMIT 1");
                $select_prod->execute([$order['product_id']]);
                $prod = $select_prod->fetch(PDO::FETCH_ASSOC);

                echo "<div class='box'>";
                echo "<p>Order ID: {$order['id']}</p>";
                echo "<p>Product: {$prod['name']}</p>";
                echo "<p>Date: {$order['date']}</p>";
                echo "<p>Total: $".$order['price'] * $order['qty']."</p>";
                echo "</div>";
            }
        } else {
            echo "<p>No canceled orders yet.</p>";
        }
        ?>
    </section>

    <?php include 'components/footer.php'; ?>
</div>

<script>
document.querySelector('.cancel-btn')?.addEventListener('click', function(e){
    e.preventDefault();
    swal({
        title: "Are you sure?",
        text: "Do you really want to cancel your order?",
        icon: "warning",
        buttons: true,
        dangerMode: true
    })
    .then((willCancel)=>{
        if(willCancel){
            e.target.closest('form').submit();
        }
    });
});
</script>
</body>
</html>
