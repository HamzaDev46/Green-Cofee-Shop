 <?php
include '../components/connection.php';
session_start();

$admin_id = $_SESSION['admin_id'] ?? null;
if (!$admin_id) {
    header('location: login.php');
    exit;
}

// delete canceled order
if (isset($_POST['delete_order'])) {
    $order_id = $_POST['order_id'];
    $order_id = filter_var($order_id, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

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
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" type="text/css" href="admin_style.css?v=<?php echo time(); ?>">
<title>Canceled Orders</title>
</head>
<body>
<?php include '../components/admin_header.php'; ?>

<div class="main">
    <h1>Canceled Orders</h1>
    <div class="box-container">
        <?php
        $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE status = ?");
        $select_orders->execute(['canceled']);

        if ($select_orders->rowCount() > 0) {
            while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
        ?>
        <div class="box">
            <div class="status" style="color:red;"><?= $fetch_orders['status']; ?></div>
            <div class="detail">
                <p>User Name: <span><?= $fetch_orders['name']; ?></span></p>
                <p>Order ID: <span><?= $fetch_orders['id']; ?></span></p>
                <p>Placed On: <span><?= $fetch_orders['date']; ?></span></p>
                <p>Email: <span><?= $fetch_orders['email']; ?></span></p>
                <p>Total Price: <span><?= $fetch_orders['price']; ?></span></p>
            </div>
            <form action="" method="post">
                <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
                <button type="submit" name="delete_order" class="btn">Delete Order</button>
            </form>
        </div>
        <?php
            }
        } else {
            echo '<p>No canceled orders yet.</p>';
        }
        ?>
    </div>
</div>
</body>
</html>
