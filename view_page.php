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
    $id = unique_id();
    $product_id = $_POST['product_id'];

    $verify_wishlist = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ? AND product_id = ?");
    $verify_wishlist->execute([$user_id, $product_id]);

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

// =====================
// Add to Cart
// =====================
if (isset($_POST['add_to_cart'])) {
    $id = unique_id();
    $product_id = $_POST['product_id'];
    $qty_raw = $_POST['qty'] ?? '1';

    $qty_sanitized = filter_var($qty_raw, FILTER_SANITIZE_NUMBER_INT);
    $qty = filter_var($qty_sanitized, FILTER_VALIDATE_INT);

    if ($qty === false || $qty < 1) {
        $warning_msg[] = 'Please enter a valid quantity.';
    } else {
        $check_cart_item = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ? AND product_id = ?");
        $check_cart_item->execute([$user_id, $product_id]);

        $max_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
        $max_cart_items->execute([$user_id]);

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green Coffee - Product Detail Page</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        <?php include 'Style.css'; ?>
        .img {
            width: 225px;
            height: 225px;
            object-fit: cover;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <?php include 'components/header.php'; ?>

    <div class="main">
        <div class="banner">
            <h1>Product Detail</h1>
        </div>
        <div class="title2">
            <a href="home.php">Home</a><span> / Product Detail</span>
        </div>

        <section class='view_page'>
            <?php
            if (isset($_GET['pid'])) {
                $pid = $_GET['pid'];
                $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ? LIMIT 1");
                $select_products->execute([$pid]);
                if ($select_products->rowCount() > 0) {
                    while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <form method="post">
                <img src="img/<?php echo $fetch_product['image']; ?>" class="img" alt="">
                <div class="detail">
                    <div class="price">Price: PKR <?php  echo $fetch_product['price']; ?>/-</div>
                    <div class="name"><?php echo $fetch_product['name']; ?></div>
                    <div class="detail">
                        <p><?php echo $fetch_product['description'] ?? 'No description available.'; ?></p>
                    </div>
                    <input type="hidden" name="product_id" value="<?php echo $fetch_product['id']; ?>">
                    <div class="button">
                        <button type='submit' name='add_to_wishlist' class='btn'>Add to Wishlist <i class='bx bx-heart'></i></button>
                        <input type="hidden" name="qty" value="1" min="1" class='quantity'>
                        <button type='submit' name='add_to_cart' class='btn'>Add to Cart <i class='bx bx-cart'></i></button>
                    </div>
                </div>
            </form>
            <?php
                    }
                } else {
                    echo "<p class='empty'>Product not found!</p>";
                }
            }
            ?>
        </section>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="script.js" defer></script>
    <?php include 'components/alert.php'; ?>
</body>
</html>
