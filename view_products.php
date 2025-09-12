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

// =====================
// Add to Wishlist
// =====================
if (isset($_POST['add_to_wishlist'])) {
    if ($user_id == '') {
        $warning_msg[] = 'Please log in to use the wishlist!';
    } else {
        $id = unique_id();
        $product_id = $_POST['product_id'];

        // Check if already in wishlist
        $verify_wishlist = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ? AND product_id = ?");
        $verify_wishlist->execute([$user_id, $product_id]);

        // Check if already in cart
        $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ? AND product_id = ?");
        $check_cart->execute([$user_id, $product_id]);

        if ($verify_wishlist->rowCount() > 0) {
            $warning_msg[] = 'Already added to wishlist!';
        } elseif ($check_cart->rowCount() > 0) {
            $warning_msg[] = 'Already added to cart!';
        } else {
            $select_product = $conn->prepare("SELECT * FROM `products` WHERE id = ? LIMIT 1");
            $select_product->execute([$product_id]);
            $fetch_product = $select_product->fetch(PDO::FETCH_ASSOC);

            $insert_wishlist = $conn->prepare("INSERT INTO `wishlist`(id, user_id, product_id, price) VALUES (?, ?, ?, ?)");
            $insert_wishlist->execute([$id, $user_id, $product_id, $fetch_product['price']]);

            $success_msg[] = 'Product added to wishlist successfully!';
        }
    }
}

// =====================
// Add to Cart
// =====================
if (isset($_POST['add_to_cart'])) {
    if ($user_id == '') {
        $warning_msg[] = 'Please log in to add items to cart!';
    } else {
        $id = unique_id();
        $product_id = $_POST['product_id'];
        $qty_raw = $_POST['qty'] ?? '1';

        $qty_sanitized = filter_var($qty_raw, FILTER_SANITIZE_NUMBER_INT);
        $qty = filter_var($qty_sanitized, FILTER_VALIDATE_INT);

        if ($qty === false || $qty < 1) {
            $warning_msg[] = 'Please enter a valid quantity.';
        } else {
            $verify_wishlist = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ? AND product_id = ?");
            $verify_wishlist->execute([$user_id, $product_id]);

            $max_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $max_cart_items->execute([$user_id]);

            $check_cart_item = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ? AND product_id = ?");
            $check_cart_item->execute([$user_id, $product_id]);

            if ($check_cart_item->rowCount() > 0) {
                $warning_msg[] = 'Product already in cart!';
            } elseif ($max_cart_items->rowCount() > 20) {
                $warning_msg[] = 'Cart is full!';
            } else {
                $select_product = $conn->prepare("SELECT * FROM `products` WHERE id = ? LIMIT 1");
                $select_product->execute([$product_id]);
                $fetch_product = $select_product->fetch(PDO::FETCH_ASSOC);

                $insert_cart = $conn->prepare("INSERT INTO `cart`(id, user_id, product_id, price, qty) VALUES (?, ?, ?, ?, ?)");
                $insert_cart->execute([$id, $user_id, $product_id, $fetch_product['price'], $qty]);

                $success_msg[] = 'Product added to cart successfully!';
            }
        }
    }
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
    <title>Green Coffee - Shop Page</title>
    
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
        $select_products = $conn->prepare("SELECT * FROM `products` WHERE status = 'active'");
        $select_products->execute();
        if ($select_products->rowCount() > 0) {
            while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <form action="" method="post" class="box">
                    <img src="img/<?= $fetch_product['image']; ?>" alt="" class="img">
                    <div class="button">
                        <?php if ($user_id != ''): ?>
                            <button type='submit' name='add_to_cart'><i class='bx bx-cart'></i></button>
                            <button type='submit' name='add_to_wishlist'><i class='bx bx-heart'></i></button>
                        <?php else: ?>
                            <a href="login.php" class='bx bx-cart'></a>
                            <a href="login.php" class='bx bx-heart'></a>
                        <?php endif; ?>
                        <a href="view_page.php?pid=<?= $fetch_product['id']; ?>" class='bx bxs-show'></a>
                    </div>
                    <h3 class='name'><?= $fetch_product['name']; ?></h3>
                    <input type="hidden" name="product_id" value="<?= $fetch_product['id']; ?>">
                    <div class="flex">
                        <p class="price">price Rs<?= $fetch_product['price']; ?>/-</p>
                        <input type="number" name="qty" min="1" max="99" value="1" class="qty" maxlength="2" required>
                    </div>
                    <a href="checkout.php?get_id=<?= $fetch_product['id']; ?>" class='btn'>buy now</a>
                </form>
                <?php
            }
        } else {
            echo '<p class="empty">no products available yet!</p>';
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
