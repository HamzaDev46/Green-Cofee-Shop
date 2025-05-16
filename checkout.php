<?php 
include 'components/connection.php'; 
session_start();  

$user_id = $_SESSION['user_id'] ?? '';

if (isset($_POST['logout'])) {
    session_destroy();
    header('location:login.php');
    exit;
}

if (isset($_POST['add_to_cart'])) {
    if ($user_id === '') {
        $warning_msg[] = 'Please log in to add items to cart!';
    } else {
        $id = unique_id();
        $product_id = $_POST['product_id'];
        $qty = filter_var($_POST['qty'], FILTER_VALIDATE_INT);

        if ($qty === false || $qty < 1) {
            $warning_msg[] = 'Please enter a valid quantity.';
        } else {
            $check_cart_item = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ? AND product_id = ?");
            $check_cart_item->execute([$user_id, $product_id]);

            $max_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $max_cart_items->execute([$user_id]);

            if ($check_cart_item->rowCount() > 0) {
                $warning_msg[] = 'Product already in cart!';
            } elseif ($max_cart_items->rowCount() >= 20) {
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green Coffee - Checkout</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style><?php include 'Style.css'; ?></style>
</head>
<body>
<?php include 'components/header.php'; ?>

<div class="main">
    <div class="banner"><h1>Checkout Summary</h1></div>
    <div class="title2"><a href="home.php">home</a><span>/ Checkout Summary</span></div>

    <section class='checkout'>
        <div class="title">
            <img src="img/download.png" alt="" class="logo">
            <h1>Checkout Summary</h1>
            <p>Review your order details before proceeding to payment.</p>
       </div>
            <div class="row">
                <form  method="post">
                    <h3>billing details</h3>
                    <div class="flex">
                        <div class="box">
                            <div class="input-feld">
                                <p>your name <span>*</span></p>
                                <input type="text" name="name" required maxlength='50' placeholder="Enter your name" class="input">
                            </div>
                            <div class="input-feld">
                                <p>your email <span>*</span></p>
                                <input type="email" name="email" required maxlength='50' placeholder="Enter your email" class="input">
                            </div>
                            <div class="input-feld">
                                <p>your phone <span>*</span></p>
                                <input type="tel" name="phone" required maxlength='15' placeholder="Enter your phone number" class="input">
                            </div>
                            
                            <div class="input-feld">
                                <p>payment method <span>*</span></p>
                                <select name="payment_method" required class="input">
                                    <option value="" disabled selected>Select payment method</option>
                                    <option value="cash on delivery">Cash on Delivery</option>
                                    <option value="credit card">Credit Card</option>
                                    <option value="paypal">PayPal</option>
                                </select>
                            </div>
                            <div class="input-feld">
                                <p>address type <span>*</span></p>
                                <select name="address_type" required class="input">
                                    <option value="" disabled selected>Select address type</option>
                                    <option value="home">Home</option>
                                    <option value="office">Office</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                       </div>
                       <div class="box">
                           <div class="input-feld">
                                <p>address line 01 <span>*</span></p>
                                <input type="text" name="flat" required maxlength='50' placeholder="Enter your flat & building no" class="input">
                            </div>
                            <div class="input-feld">
                                <p>address line 02 <span>*</span></p>
                                <input type="text" name="street" required maxlength='50' placeholder="Enter your street & area" class="input">
                            </div>
                            <div class="input-feld">
                                <p>city name <span>*</span></p>
                                <input type="text" name="city" required maxlength='50' placeholder="Enter your city" class="input">
                            </div>
                            <div class="input-feld">
                                <p>state name <span>*</span></p>
                                <input type="text" name="state" required maxlength='50' placeholder="Enter your state" class="input">
                            </div>
                            <div class="input-feld">
                                <p>zip code <span>*</span></p>
                                <input type="text" name="zip" required maxlength='6' placeholder="Enter your zip code" min='0' max='999999' class="input">
                            </div>
                        </div>
                    </div>
                    <div class="btn">
                        <button type="submit" class="btn">Place Order</button>
                    </div>
                </form>
                <div class="summary">
                    
                </div>
            </div>
   </section>

    <?php include 'components/footer.php'; ?>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="script.js" defer></script>
<?php include 'components/alert.php'; ?>
</body>
</html>
