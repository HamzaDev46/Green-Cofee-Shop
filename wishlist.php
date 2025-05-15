<?php
include 'components/connection.php';
session_start();

$user_id = $_SESSION['user_id'] ?? '';

// ✅ Always redirect early if not logged in
if (!$user_id) {
    header('Location: login.php');
    exit;
}

// =====================
// Logout Handler
// =====================
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
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

// =====================
// Delete from Wishlist
// =====================
if (isset($_POST['delete_item'])) {
    $id = $_POST['wishlist_id'];
    $delete_item = $conn->prepare("DELETE FROM `wishlist` WHERE id = ?");
    $delete_item->execute([$id]);
    $success_msg[] = 'Item removed from wishlist successfully!';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green Coffee - Wishlist Page</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <style>
    <?php include 'Style.css'; ?>



</style>

</head>
<body>
    <?php include 'components/header.php'; ?>

    <div class="main">
        <div class="banner">
            <h1>My Wishlist</h1>
        </div>
        <div class="title2">
            <a href="home.php">Home</a><span> / Wishlist</span>
        </div>

        <section class='products'>
            <h1 class='title'>Products added in wishlist</h1>
            <div class="box-container">
                <?php
                $grand_total = 0;
                $select_wishlist = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
                $select_wishlist->execute([$user_id]);

                if ($select_wishlist->rowCount() > 0) {
                    while ($fetch_list = $select_wishlist->fetch(PDO::FETCH_ASSOC)) {
                        $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
                        $select_products->execute([$fetch_list['product_id']]);

                        if ($select_products->rowCount() > 0) {
                            $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);
                            ?>
                            <form method="post" class='box'>
                                <input type="hidden" name='wishlist_id' value="<?= $fetch_list['id']; ?>">
                                <img src="img/<?= $fetch_products['image']; ?>" class="img" alt="">
                               <div class="product-actions">
                                        <button type='submit' name='add_to_cart'><i class='bx bx-cart'></i></button>
                                        <a href="view_page.php?pid=<?= $fetch_products['id']; ?>"><i class='bx bxs-show'></i></a>
                                        <button type='submit' name='delete_item' onclick="return confirm('Are you sure you want to remove this item from your wishlist?')"><i class='bx bx-x'></i></button>
                              </div>
                                <h3 class="name"><?= $fetch_products['name']; ?></h3>
                                <input type="hidden" name='product_id' value="<?= $fetch_products['id']; ?>">
                                <div class="flex">
                                    <p class="price"><?= $fetch_products['price']; ?></p>
                                </div>
                                <a href="checkout.php?get_id=<?= $fetch_products['id']; ?>">Buy Now</a>
                            </form>
                            <?php
                            $grand_total += $fetch_products['price'];
                        }
                    }
                }
                ?>
            </div>
        </section>

        <?php include 'components/footer.php'; ?>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="script.js" defer></script>
    <?php include 'components/alert.php'; ?>
</body>
</html>
