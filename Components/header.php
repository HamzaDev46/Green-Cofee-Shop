<?php
// Start session safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set user session variables if available
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';
$user_email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'Not logged in';
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
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
                    <?php
                    // Wishlist count
                    $count_wishlist_items = $conn->prepare("SELECT COUNT(*) FROM `wishlist` WHERE user_id = ?");
                    $count_wishlist_items->execute([$user_id]);
                    $total_wishlist_items = $count_wishlist_items->fetchColumn();
                    ?>
                    <a href="wishlist.php" class='cart-btn'><i class='bx bx-heart'></i><sup><?= $total_wishlist_items ?></sup></a>

                    <?php
                    // Cart total quantity count
                    $count_cart_items = $conn->prepare("SELECT SUM(qty) AS total_qty FROM `cart` WHERE user_id = ?");
                    $count_cart_items->execute([$user_id]);
                    $fetch_cart_items = $count_cart_items->fetch(PDO::FETCH_ASSOC);
                    $total_cart_items = $fetch_cart_items['total_qty'] ?? 0;
                    ?>
                    <a href="cart.php" class='cart-btn'><i class='bx bx-cart-download'></i><sup><?= $total_cart_items ?></sup></a>

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
