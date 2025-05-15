<?php
// Start session safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'connection.php';
 // ensure this is present in pages using header

// Set user session variables
$user_name = $_SESSION['user_name'] ?? 'Guest';
$user_email = $_SESSION['user_email'] ?? 'Not logged in';
$user_id = $_SESSION['user_id'] ?? '';

// Default counts
$total_wishlist_items = 0;
$total_cart_items = 0;

if ($user_id) {
    // Wishlist count
    $count_wishlist_items = $conn->prepare("SELECT COUNT(*) FROM `wishlist` WHERE user_id = ?");
    $count_wishlist_items->execute([$user_id]);
    $total_wishlist_items = $count_wishlist_items->fetchColumn();

    // Cart quantity total
    $count_cart_items = $conn->prepare("SELECT SUM(qty) AS total_qty FROM `cart` WHERE user_id = ?");
    $count_cart_items->execute([$user_id]);
    $fetch_cart_items = $count_cart_items->fetch(PDO::FETCH_ASSOC);
    $total_cart_items = $fetch_cart_items['total_qty'] ?? 0;
}
?>

<header class="header">
    <div class="flex">
        <a href="home.php" class='logo'><img src="img/logo.jpg" alt="logo"></a>

        <nav class="navbar">
            <a href="home.php">home</a>
            <a href="view_products.php">products</a>
            <a href="order.php">orders</a>
            <a href="about.php">about</a>
            <a href="contact.php">contact</a>
        </nav>

        <div class="icons">
            <i class='bx bxs-user' id='user-btn'></i>

            <!-- Wishlist -->
            <a href="<?= $user_id ? 'wishlist.php' : 'login.php' ?>" class='cart-btn'>
                <i class='bx bx-heart'></i>
                <?php if ($user_id && $total_wishlist_items > 0): ?>
                    <sup><?= $total_wishlist_items ?></sup>
                <?php endif; ?>
            </a>

            <!-- Cart -->
            <a href="<?= $user_id ? 'cart.php' : 'login.php' ?>" class='cart-btn'>
                <i class='bx bx-cart-download'></i>
                <?php if ($user_id && $total_cart_items > 0): ?>
                    <sup><?= $total_cart_items ?></sup>
                <?php endif; ?>
            </a>

            <i class='bx bx-list-plus' id='menu-btn' style='font-size:2rem;'></i>
        </div>

        <div class="user-box">
            <p>username: <span><?= htmlspecialchars($user_name) ?></span></p>
            <p>Email: <span><?= htmlspecialchars($user_email) ?></span></p>

            <?php if ($user_id): ?>
                <form action="" method="post">
                    <button type="submit" name="logout" class='btn'>logout</button>
                </form>
            <?php else: ?>
                <a href="login.php" class='btn'>login</a>
                <a href="register.php" class='btn'>register</a>
            <?php endif; ?>
        </div>
    </div>
</header>
