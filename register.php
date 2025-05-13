<?php
include 'components/connection.php';
session_start();

if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

// Register user
if(isset($_POST['submit'])){
    $id = unique_id();
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_UNSAFE_RAW);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_UNSAFE_RAW);
    $pass = $_POST['pass'];
    $pass = filter_var($pass, FILTER_UNSAFE_RAW);
    $cpass = $_POST['cpass'];
    $cpass = filter_var($cpass, FILTER_UNSAFE_RAW);

    $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
    $select_user->execute([$email]);
    $row = $select_user->fetch(PDO::FETCH_ASSOC);

    if($select_user->rowCount() > 0) {
        $error_msg[] = 'User already exists!';
    } else {
        if($pass != $cpass) {
            $error_msg[] = 'Confirm password not matched!';
        } else {
            $insert_user = $conn->prepare("INSERT INTO `users`(id, name, email, password) VALUES(?,?,?,?)");
            $insert_user->execute([$id, $name, $email, $pass]);

            $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
            $select_user->execute([$email, $pass]);
            $row = $select_user->fetch(PDO::FETCH_ASSOC);

            if($select_user->rowCount() > 0) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_name'] = $row['name'];
                $_SESSION['user_email'] = $row['email'];
                $success_msg[] = 'Registration successful!';
                header("refresh:1;url=home.php");
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
    <title>Green Tea - Register Now</title>
    <link rel="stylesheet" href="Style.css">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body>

<section class="form-container">
    <div class="title">
        <img src="img/download.png" alt="" class="logo">
        <h1>Register Now</h1>
        <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Laborum, necessitatibus?</p>
    </div>
    <form action="" method="post">
        <div class="input-field">
            <p>Your name</p>
            <input type="text" name="name" placeholder="Enter your name" required maxlength="50">
        </div>
        <div class="input-field">
            <p>Your email</p>
            <input type="email" name="email" placeholder="Enter your email" required maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
        </div>
        <div class="input-field">
            <p>Your password</p>
            <input type="password" name="pass" placeholder="Enter your password" required maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
        </div>
        <div class="input-field">
            <p>Confirm password</p>
            <input type="password" name="cpass" placeholder="Enter your password" required maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
        </div>
        <button type="submit" name="submit" class="btn">Register</button>
        <p>Already have an account? <a href="login.php">Login now</a></p>
    </form>
</section>

<!-- Include alert handler -->
<?php include 'components/alert.php'; ?>

</body>
</html>
