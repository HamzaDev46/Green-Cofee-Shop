<?php
include '../components/connection.php';
session_start();

$admin_id = $_SESSION['admin_id'] ?? null;
if (!$admin_id) {
    header('location: login.php');
    exit;
}

// Fetch admin profile
$select_profile = $conn->prepare("SELECT * FROM admin WHERE id = ?");
$select_profile->execute([$admin_id]);
$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);

// Fetch counts
$select_product = $conn->prepare("SELECT * FROM products");
$select_product->execute();
$total_products = $select_product->rowCount();

$select_active_product = $conn->prepare("SELECT * FROM products WHERE status=?");
$select_active_product->execute(['active']);
$total_active_products = $select_active_product->rowCount();

$select_inactive_product = $conn->prepare("SELECT * FROM products WHERE status=?");
$select_inactive_product->execute(['inactive']);
$total_inactive_products = $select_inactive_product->rowCount();

$select_users = $conn->prepare("SELECT * FROM users");
$select_users->execute();
$total_users = $select_users->rowCount();

$select_admin = $conn->prepare("SELECT * FROM admin");
$select_admin->execute();
$total_admin = $select_admin->rowCount();

// Unread messages count
$select_message = $conn->prepare("SELECT * FROM message WHERE status=?");
$select_message->execute(['unread']);
$total_message = $select_message->rowCount();

$select_admin_orders = $conn->prepare("SELECT * FROM orders");
$select_admin_orders->execute();
$total_admin_orders = $select_admin_orders->rowCount();

$select_canceled_orders = $conn->prepare("SELECT * FROM orders WHERE status=?");
$select_canceled_orders->execute(['canceled']);
$total_canceled_orders = $select_canceled_orders->rowCount();

$select_confirm_orders = $conn->prepare("SELECT * FROM orders WHERE status=?");
$select_confirm_orders->execute(['complete']);
$total_confirm_orders = $select_confirm_orders->rowCount();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" type="text/css" href="admin_style.css?v=<?php echo time(); ?>">
<title>Green Coffee - Admin Dashboard</title>
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
        <h1 class="heading">Dashboard</h1>
        <div class="box-container">

            <!-- Welcome Box -->
            <div class="box">
                <h3>Welcome!</h3>
                <p><?= htmlspecialchars($fetch_profile['name']); ?></p>
                <a href="" class="btn">Profile</a>
            </div>

            <!-- Total Products -->
            <div class="box">
                <h3>Total Products</h3>
                <p><?= $total_products; ?></p>
                <a href="add_products.php" class="btn">Add New Products</a>
            </div>

            <!-- Active Products -->
            <div class="box">
                <h3>Total Active Products</h3>
                <p><?= $total_active_products; ?></p>
                <a href="view_product.php" class="btn">View Active Products</a>
            </div>

            <!-- Inactive Products -->
            <div class="box">
                <h3>Total Inactive Products</h3>
                <p><?= $total_inactive_products; ?></p>
                <a href="view_product.php" class="btn">View Inactive Products</a>
            </div>

            <!-- Users -->
            <div class="box">
                <h3>Total Users</h3>
                <p><?= $total_users; ?></p>
                <a href="view_users.php" class="btn">View Users</a>
            </div>

            <!-- Admins -->
            <div class="box">
                <h3>Total Admins</h3>
                <p><?= $total_admin; ?></p>
                <a href="view_admin.php" class="btn">View Admins</a>
            </div>

            <!-- Unread Messages -->
            <div class="box">
                <h3>Total Unread Messages</h3>
                <p><?= $total_message; ?></p>
                <a href="admin_message.php" class="btn">View Messages</a>
            </div>

            <!-- Total Orders -->
            <div class="box">
                <h3>Total Orders</h3>
                <p><?= $total_admin_orders; ?></p>
                <a href="order.php" class="btn">View Orders</a>
            </div>

            <!-- Canceled Orders -->
            <div class="box">
                <h3>Total Canceled Orders</h3>
                <p><?= $total_canceled_orders; ?></p>
                <a href="cancelled_order.php" class="btn">View Canceled Orders</a>
            </div>

            <!-- Confirmed Orders -->
            <div class="box">
                <h3>Total Confirmed Orders</h3>
                <p><?= $total_confirm_orders; ?></p>
                <a href="confirmed_order.php" class="btn">View Confirmed Orders</a>
            </div>

        </div>
    </section>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script type="text/javascript" src="script.js"></script>
<?php include '../components/alert.php'; ?>
</body>
</html>
