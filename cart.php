<?php
include 'components/connection.php';
session_start();

$user_id = $_SESSION['user_id'] ?? '';

if (!$user_id) {
    header('Location: login.php');
    exit;
}

if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}
// dlete from cart
if (isset($_POST['delete_item'])) {
    $cart_id = $_POST['delete_item'];
    $delete_item = $conn->prepare("DELETE FROM `cart` WHERE id = ? AND user_id = ?");
    $delete_item->execute([$cart_id, $user_id]);
    $success_msg[] = 'Item removed from cart successfully!';
}
// empty cart
if (isset($_POST['empty_cart'])) {
    $delete_item = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
    $delete_item->execute([$user_id]);
    $success_msg[] = 'Cart emptied successfully!';
}
//update cart
if (isset($_POST['update_cart'])) {
    $cart_id = $_POST['cart_id'];
    $qty_raw = $_POST['qty'] ?? '1';

    $qty_sanitized = filter_var($qty_raw, FILTER_SANITIZE_NUMBER_INT);
    $qty = filter_var($qty_sanitized, FILTER_VALIDATE_INT);

    if ($qty === false || $qty < 1) {
        $warning_msg[] = 'Please enter a valid quantity.';
    } else {
        $update_cart = $conn->prepare("UPDATE `cart` SET qty = ? WHERE id = ? AND user_id = ?");
        $update_cart->execute([$qty, $cart_id, $user_id]);
        $success_msg[] = 'Cart updated successfully!';
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green Coffee - Cart</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style><?php include 'Style.css'; ?></style>
</head>
<body>

<?php include 'components/header.php'; ?>

<div class="main">
    <div class="banner"><h1>My Cart</h1></div>
    <div class="title2"><a href="home.php">Home</a><span>/ Cart</span></div>

    <section class="products">
        <h1 class="title">Products added in cart</h1>
        <div class="box-container">
            <?php
            $grand_total = 0;
            $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $select_cart->execute([$user_id]);

            if ($select_cart->rowCount() > 0):
                while ($cart = $select_cart->fetch(PDO::FETCH_ASSOC)):
                    $product_stmt = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
                    $product_stmt->execute([$cart['product_id']]);
                    $product = $product_stmt->fetch(PDO::FETCH_ASSOC);

                    if ($product):
                        $sub_total = $product['price'] * $cart['qty'];
                        $grand_total += $sub_total;
            ?>
            <form method="post" class="box">
                    <input type="hidden" name="cart_id" value="<?= $cart['id']; ?>">
                    <img src="img/<?= $product['image']; ?>" class="img" alt="">
                    <h3 class="name"><?= $product['name']; ?></h3>

                    <div class="flex">
                        <p class="price">PKR <?= $product['price']; ?></p>
                        <input type="number" name="qty" value="<?= $cart['qty']; ?>" min="1" max="99" class="qty" required>
                        <button type="submit" name="update_cart" class="icon-btn" title="Update Quantity">
                            <i class='bx bx-refresh'></i>
                        </button>
                    </div>

                    <p class="sub-total">Sub Total: <span>PKR <?= $sub_total; ?></span></p>

                    <button type="submit" name="delete_item" value="<?= $cart['id']; ?>" class="btn"
                        onclick="return confirm('Are you sure to remove this item?')">Remove</button>
            </form>

            <?php
                    endif;
                endwhile;
            else:
                echo '<p class="empty">Your cart is empty!</p>';
            endif;
            ?>
        </div>
       <?php if ($grand_total != 0): ?>
                    <div class="cart-total">
                        <p>Total Amount Payable: <span>PKR <?= $grand_total; ?>/-</span></p>
                        <div class="button">
                            <form method="post">
                                <button type="submit" name="empty_cart" class="btn" 
                                    onclick="return confirm('Are you sure you want to empty your cart?')">
                                    Empty Cart
                                </button>
                            </form>
                            <a href="checkout.php" class="btn">Proceed to Checkout</a>
                        </div>
                    </div>
        <?php endif; ?>

    </section>

    <?php include 'components/footer.php'; ?>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="script.js" defer></script>
<?php include 'components/alert.php'; ?>
</body>
</html>
