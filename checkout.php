<?php 
include 'components/connection.php'; 
session_start();  

$user_id = $_SESSION['user_id'] ?? '';

if (isset($_POST['logout'])) {
    session_destroy();
    header('location:login.php');
    exit;
}

if (isset($_POST['place_order'])) {

    $name = filter_var($_POST['name'], FILTER_UNSAFE_RAW);
    $number = filter_var($_POST['phone'], FILTER_UNSAFE_RAW);
    $email = filter_var($_POST['email'], FILTER_UNSAFE_RAW);
    $address = filter_var($_POST['flat'] . ', ' . $_POST['street'] . ', ' . $_POST['city'] . ', ' . $_POST['state'] . ' - ' . $_POST['zip'], FILTER_UNSAFE_RAW);
    $address_type = filter_var($_POST['address_type'], FILTER_UNSAFE_RAW);
    $method = filter_var($_POST['payment_method'], FILTER_UNSAFE_RAW);

    $verify_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id=?");
    $verify_cart->execute([$user_id]);

    if (isset($_GET['get_id'])) {
        $get_product = $conn->prepare("SELECT * FROM `products` WHERE id=? LIMIT 1");
        $get_product->execute([$_GET['get_id']]);

        if ($get_product->rowCount() > 0) {
            while ($fetch_p = $get_product->fetch(PDO::FETCH_ASSOC)) {
                $insert_order = $conn->prepare("INSERT INTO `orders` 
                (user_id, name, number, email, address, address_type, method, product_id, price, qty) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                
                $insert_order->execute([
                    $user_id, $name, $number, $email, $address, $address_type, $method,
                    $fetch_p['id'], $fetch_p['price'], 1
                ]);
            }
            header('location:order.php');
            exit;
        } else {
            $warning_msg[] = 'Something went wrong!';
        }
    } elseif ($verify_cart->rowCount() > 0) {
        while ($f_cart = $verify_cart->fetch(PDO::FETCH_ASSOC)) {
            $insert_order = $conn->prepare("INSERT INTO `orders` 
            (user_id, name, number, email, address, address_type, method, product_id, price, qty) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            $insert_order->execute([
                $user_id, $name, $number, $email, $address, $address_type, $method,
                $f_cart['product_id'], $f_cart['price'], $f_cart['qty']
            ]);
        }

        $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id=?");
        $delete_cart->execute([$user_id]);

        header('location:order.php');
        exit;
    } else {
        $warning_msg[] = 'Your cart is empty!';
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
            <form method="post">
                <h3>Billing Details</h3>
                <div class="flex">
                    <div class="box">
                        <div class="input-feld">
                            <p>Your Name <span>*</span></p>
                            <input type="text" name="name" required maxlength='50' placeholder="Enter your name" class="input">
                        </div>
                        <div class="input-feld">
                            <p>Your Email <span>*</span></p>
                            <input type="email" name="email" required maxlength='50' placeholder="Enter your email" class="input">
                        </div>
                        <div class="input-feld">
                            <p>Your Phone <span>*</span></p>
                            <input type="tel" name="phone" required maxlength='11' placeholder="11-digit phone number" class="input">
                        </div>
                        <div class="input-feld">
                            <p>Payment Method <span>*</span></p>
                            <select name="payment_method" required class="input">
                                <option value="" disabled selected>Select payment method</option>
                                <option value="cash on delivery">Cash on Delivery</option>
                                <option value="credit card">Credit Card</option>
                                <option value="paypal">PayPal</option>
                            </select>
                        </div>
                        <div class="input-feld">
                            <p>Address Type <span>*</span></p>
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
                            <p>Address Line 01 <span>*</span></p>
                            <input type="text" name="flat" required maxlength='50' placeholder="Enter your flat & building no" class="input">
                        </div>
                        <div class="input-feld">
                            <p>Address Line 02 <span>*</span></p>
                            <input type="text" name="street" required maxlength='50' placeholder="Enter your street & area" class="input">
                        </div>
                        <div class="input-feld">
                            <p>City Name <span>*</span></p>
                            <input type="text" name="city" required maxlength='50' placeholder="Enter your city" class="input">
                        </div>
                        <div class="input-feld">
                            <p>State Name <span>*</span></p>
                            <input type="text" name="state" required maxlength='50' placeholder="Enter your state" class="input">
                        </div>
                        <div class="input-feld">
                            <p>ZIP Code <span>*</span></p>
                            <input type="text" name="zip" required maxlength='6' placeholder="Enter your zip code" class="input">
                        </div>
                    </div>
                </div>
                <div class="btn">
                    <button type="submit" class="btn" name="place_order">Place Order</button>
                </div>
            </form>

            <div class="summary">
                <h3>My Bag</h3>
                <div class="box-container">
                <?php
                $grand_total = 0;

                if (isset($_GET['get_id'])) {
                    $select_get = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
                    $select_get->execute([$_GET['get_id']]);

                    while ($fetch_get = $select_get->fetch(PDO::FETCH_ASSOC)) {
                        $sub_total = $fetch_get['price'];
                        $grand_total += $sub_total;
                ?>
                    <div class="flex">
                        <img src="img/<?= $fetch_get['image'] ?>" alt="" class="image">
                        <div>
                            <h3 class='name'><?= $fetch_get['name'] ?></h3>
                            <p class="price"><?= $fetch_get['price'] ?>/-</p>
                        </div>
                    </div>
                <?php
                    }
                } else {
                    $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
                    $select_cart->execute([$user_id]);

                    if ($select_cart->rowCount() > 0) {
                        while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                            $select_product = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
                            $select_product->execute([$fetch_cart['product_id']]);
                            $fetch_product = $select_product->fetch(PDO::FETCH_ASSOC);

                            if ($fetch_product) {
                                $sub_total = $fetch_product['price'] * $fetch_cart['qty'];
                                $grand_total += $sub_total;
                ?>
                    <div class="flex">
                        <img src="img/<?= $fetch_product['image'] ?>" alt="" class="image">
                        <div>
                            <h3 class='name'><?= $fetch_product['name'] ?></h3>
                            <p class="price"><?= $fetch_product['price'] ?> x <?= $fetch_cart['qty'] ?> = <?= $sub_total ?>/-</p>
                        </div>
                    </div>
                <?php
                            }
                        }
                    } else {
                        echo "<p>Your cart is empty.</p>";
                    }
                }
                ?>
                </div>
               <div class="cart-total">
                    <h3>Total amount to be paid:</h3>
                    <p>PKR/<?= $grand_total ?>/-</p>
                </div>
            </div>
        </div>
    </section>

    <?php include 'components/footer.php'; ?>
</div>

<script>
document.querySelector("form").addEventListener("submit", function(e) {
    const form = e.target;
    let isValid = true;
    let messages = [];

    const phonePattern = /^\d{11}$/; // 11 digits only
    const zipPattern = /^\d{4,6}$/;

    const name = form.name.value.trim();
    const email = form.email.value.trim();
    const phone = form.phone.value.trim();
    const payment = form.payment_method.value;
    const addressType = form.address_type.value;
    const flat = form.flat.value.trim();
    const street = form.street.value.trim();
    const city = form.city.value.trim();
    const state = form.state.value.trim();
    const zip = form.zip.value.trim();

    if (name.length < 2) {
        isValid = false;
        messages.push("Name must be at least 2 characters.");
    }

    if (!email.includes("@")) {
        isValid = false;
        messages.push("Enter a valid email.");
    }

    if (!phonePattern.test(phone)) {
        isValid = false;
        messages.push("Phone number must be exactly 11 digits.");
    }

    if (!payment) {
        isValid = false;
        messages.push("Please select a payment method.");
    }

    if (!addressType) {
        isValid = false;
        messages.push("Please select an address type.");
    }

    if (!flat || !street || !city || !state || !zip) {
        isValid = false;
        messages.push("All address fields are required.");
    }

    if (!zipPattern.test(zip)) {
        isValid = false;
        messages.push("ZIP code must be between 4 and 6 digits.");
    }

    if (!isValid) {
        e.preventDefault();
        alert(messages.join("\n"));
    }
});
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="script.js" defer></script>
<?php include 'components/alert.php'; ?>
</body> 
</html>
