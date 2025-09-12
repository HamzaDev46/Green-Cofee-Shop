<?php
include 'components/connection.php';
session_start();

// Get user ID if logged in, else NULL
$user_id = $_SESSION['user_id'] ?? null;

// Logout
if(isset($_POST['logout'])){
    session_destroy();
    header('location:login.php');
    exit;
}

// Handle form submission
if(isset($_POST['submit-btn'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];

    // Insert into message table
    $insert_message = $conn->prepare(
        "INSERT INTO message (user_id, name, email, phone, message, status) VALUES (?, ?, ?, ?, ?, ?)"
    );
    $insert_message->execute([$user_id, $name, $email, $phone, $message, 'unread']);

    $success_msg = "Message sent successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<title>Green Coffee - Contact</title>
<style>
<?php include 'Style.css'; ?>
</style>
</head>
<body>
<?php include 'components/header.php'; ?>

<div class="main">
    <div class="banner">
        <h1>Contact us</h1>
    </div>

    <div class="title2">
        <a href="home.php">home</a><span>Contact</span>
    </div>

    <!-- Services Section -->
    <section class='services'>
        <div class="box-container">
            <div class="box">
                <img src="img/icon2.png" alt="">
                <div class="detail">
                    <h3>great savings</h3>
                    <p>save big every order</p>
                </div>
            </div>
            <div class="box">
                <img src="img/icon1.png" alt="">
                <div class="detail">
                    <h3>24*7 support</h3>
                    <p>one-on-one sup</p>
                </div>
            </div>
            <div class="box">
                <img src="img/icon0.png" alt="">
                <div class="detail">
                    <h3>gift vouchers</h3>
                    <p>vouchers on every festivals</p>
                </div>
            </div>
            <div class="box">
                <img src="img/icon.png" alt="">
                <div class="detail">
                    <h3>worldwide delivery</h3>
                    <p>dropship delivery</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form -->
    <div class="form-container">
        <form action="" method="post">
            <div class="title">
                <img src="img/download.png" alt="" class="logo">
                <h1>Leave a message</h1>
            </div>

            <div class="input-field">
                <p>Your name<sup>*</sup></p>
                <input type="text" name="name" placeholder="Enter your name" required>
            </div>

            <div class="input-field">
                <p>Your email<sup>*</sup></p>
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="input-field">
                <p>Your phone<sup>*</sup></p>
                <input type="text" name="phone" placeholder="Enter your phone number" required>
            </div>

            <div class="input-field">
                <p>Your message<sup>*</sup></p>
                <textarea name="message" placeholder="Enter your message" required></textarea>
            </div>

            <button type='submit' name='submit-btn' class='btn'>Send Message</button>
        </form>
    </div>

    <!-- Contact Details -->
    <div class="address">
        <div class="title">
            <img src="img/download.png" alt="" class="logo">
            <h1>Contact detail</h1>
            <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Laborum, necessitatibus?</p>
        </div>

        <div class="box-container">
            <div class="box-1">
                <i class='bx bxs-map-pin'></i>
                <div>
                    <h4>Address</h4>
                    <p>123, Green Coffee, New York</p>
                </div>
            </div>
            <div class="box-1">
                <i class='bx bxs-phone-call'></i>
                <div>
                    <h4>Phone number:</h4>
                    <p>+92-3135516510</p>
                </div>
            </div>
            <div class="box-1">
                <i class='bx bxs-envelope'></i>
                <div>
                    <h4>Email:</h4>
                    <p>hamza1234@gmail.com</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'components/footer.php'; ?>

<!-- SweetAlert -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<?php if(isset($success_msg)): ?>
<script>
    swal("Success!", "<?php echo $success_msg; ?>", "success");
</script>
<?php endif; ?>

</body>
</html>
