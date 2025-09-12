<?php
    include '../components/connection.php';
    session_start();
    if (isset($_POST['login'])) 
    {

        $email = $_POST['email'];

        $email = filter_var($email, FILTER_UNSAFE_RAW);

        $pass = sha1($_POST['password']);

        $pass = filter_var($pass, FILTER_UNSAFE_RAW);

        $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE email = ? AND password = ? LIMIT 1");
        $select_admin->execute([$email, $pass]);

        if ($select_admin->rowCount() > 0) {
            $fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC);
            $_SESSION['admin_id'] = $fetch_admin['id']; // store correct admin ID

            header('location: dashboard.php');
            exit;
        } else {
            $warning_msg[] = 'Invalid email or password!';
        }
    }

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="admin_style.css?v=<? echo time(); ?>">
    <title>Green Coffee - Admin panel - Register Page</title>
</head>

<body>


    <div class="main">
        <section>
            <div class="form-container" id="admin_login">
                <form action="" method="post" enctype="multipart/form-data">
                    <h3>Login Now</h3>

                    
                    <!-- Add more input fields as needed -->
                    <div class="input-field">
                        <label for="email">Email <sup>*</sup></label>
                        <input type="email" id="email" name="email" maxlength="50" required
                            placeholder="Enter your email" oninput="this.value = this.value.replace(/\s/g, '')">
                    </div>
                    <div class="input-field">
                        <label for="password">Password <sup>*</sup></label>
                        <input type="password" id="password" name="password" maxlength="20" required
                            placeholder="Enter your password" oninput="this.value = this.value.replace(/\s/g, '')">
                    </div>

                    <button type="submit" name="login" class="btn">Login</button>
                    <p>don't have an account? <a href="register.php">Register here</a></p>
                </form>
            </div>
        </section>
    </div>


    <!-- Scripts at the bottom for better performance -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script type="text/javascript" src="script.js"></script>
    <?php include '../components/alert.php'; ?>
</body>

</html>