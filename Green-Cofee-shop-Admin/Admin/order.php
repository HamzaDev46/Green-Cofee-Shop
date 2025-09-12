<?php
include '../components/connection.php';
session_start();

$admin_id = $_SESSION['admin_id'] ?? null;
if (!$admin_id) {
    header('location: login.php');
    exit;
}

// DELETE order
if (isset($_POST['delete_order'])) {
    $order_id = filter_var($_POST['order_id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $verify_delete = $conn->prepare("SELECT * FROM `orders` WHERE id = ?");
    $verify_delete->execute([$order_id]);

    if ($verify_delete->rowCount() > 0) {
        $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
        $delete_order->execute([$order_id]);
        $success_msg[] = 'Order deleted';
    } else {
        $warning_msg[] = 'Order already deleted';
    }
}

// UPDATE order status
if (isset($_POST['update_order'])) {
    $order_id = filter_var($_POST['order_id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $update_status = filter_var($_POST['update_payment'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $verify_update = $conn->prepare("SELECT * FROM `orders` WHERE id = ?");
    $verify_update->execute([$order_id]);

    if ($verify_update->rowCount() > 0) {
        $update_order = $conn->prepare("UPDATE `orders` SET status = ? WHERE id = ?");
        $update_order->execute([$update_status, $order_id]);
        $success_msg[] = 'Order status updated';
    } else {
        $warning_msg[] = 'Order not found';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Panel - Orders</title>
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="admin_style.css?v=<?php echo time(); ?>">
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
</head>
<body>
<?php include '../components/admin_header.php'; ?>

<div class="main">
    <div class="order-container">
        <h1>All Orders</h1>
    </div>
    <div class="title2">
        <a href="dashboard.php">Dashboard</a><span> / Orders</span>
    </div>

    <section class="accounts">
        <h1 class="heading">Total Orders</h1>
        <div class="box-container">
            <?php
            $select_orders = $conn->prepare("SELECT * FROM `orders` ORDER BY date DESC");
            $select_orders->execute();

            if ($select_orders->rowCount() > 0) {
                while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <div class="box">
                <div class="status" style="color: 
                    <?php 
                        switch($fetch_orders['status']){
                            case 'pending': echo 'orange'; break;
                            case 'complete': echo 'blue'; break;
                            case 'canceled': echo 'red'; break;
                            case 'delivered': echo 'green'; break;
                            default: echo 'black';
                        }
                    ?>">
                    <?= ucfirst($fetch_orders['status']); ?>
                </div>
                <div class="detail">
                    <p>User Name: <span><?= $fetch_orders['name']; ?></span></p>
                    <p>Order ID: <span><?= $fetch_orders['id']; ?></span></p>
                    <p>Placed On: <span><?= $fetch_orders['date']; ?></span></p>
                    <p>Number: <span><?= $fetch_orders['number']; ?></span></p>
                    <p>Email: <span><?= $fetch_orders['email']; ?></span></p>
                    <p>Total Price: <span><?= $fetch_orders['price']; ?></span></p>
                    <p>Payment Method: <span><?= $fetch_orders['method']; ?></span></p>
                    <p>Address: <span><?= $fetch_orders['address']; ?></span></p>
                </div>
                <form action="" method="post">
                    <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
                    <select name="update_payment" style="color: <?= ($fetch_orders['status']=='pending')?'orange':'blue'; ?>">
                        <option value="pending" <?= ($fetch_orders['status']=='pending')?'selected':''; ?> style="color:orange;">Pending</option>
                        <option value="complete" <?= ($fetch_orders['status']=='complete')?'selected':''; ?> style="color:blue;">Complete</option>
                    </select>
                    <div class="flex-btn">
                        <button type="submit" name="update_order" class="btn">Update Status</button>
                        <button type="submit" name="delete_order" class="btn">Delete Order</button>
                    </div>
                </form>
            </div>
            <?php
                }
            } else {
                echo '<p class="empty">No orders placed yet.</p>';
            }
            ?>
        </div>
    </section>
</div>

<script type="text/javascript" src="script.js"></script>
<?php include '../components/alert.php'; ?>
</body>
</html>
