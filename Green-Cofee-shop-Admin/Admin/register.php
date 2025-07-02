<?php
    include '../components/connection.php';

    if (isset($_POST['register'])) 
    {

        $id = unique_id();

        $name = $_POST['username'];

        $name = filter_var($name, FILTER_UNSAFE_RAW);

        $email = $_POST['email'];

        $email = filter_var($email, FILTER_UNSAFE_RAW);

        $pass = sha1($_POST['password']);

        $pass = filter_var($pass, FILTER_UNSAFE_RAW);

        $cpass = sha1($_POST['confirm_password']);

        $cpass = filter_var($cpass, FILTER_UNSAFE_RAW);

        $image = $_FILES['image']['name'];

        $image = filter_var($image, FILTER_UNSAFE_RAW);

        $image_tmp_name = $_FILES['image']['tmp_name'];

        $image_folder = '../image/'.$image;

        $select_admin=$conn->prepare("SELECT * FROM `admin` WHERE email = ? LIMIT 1");
        $select_admin->execute([$email]);
        if($select_admin->rowCount() > 0)
        {
            $warning_msg[] = 'Email already exists!';
        }
        else
        {
            if($pass != $cpass)
            {
                $warning_msg[] = 'Passwords do not match!';
            }
            else
            {
                $insert_admin = $conn->prepare("INSERT INTO `admin`(id, name, email, password, image) VALUES(?,?,?,?,?)");
                $insert_admin->execute([$id, $name, $email, $cpass, $image]);

                if($insert_admin)
                {
                    move_uploaded_file($image_tmp_name, $image_folder);
                    $success_msg[] = ' New Admin Registered successfully!';
                }
                else
                {
                    $error_msg[] = 'Registration failed!';
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
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="admin_style.css?v=<? echo time(); ?>">
    <title>Green Coffee - Admin panel - Register Page</title>
</head>
<body>
    
   
    <div class="main">
        <section>
            <div class="form-container" id="admin_login">
            <form action="" method="post" enctype="multipart/form-data">
                <h3>Register Now</h3>

                <div class="input-field">
                    <label for="username">User Name <sup>*</sup></label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        maxlength="20" 
                        required 
                        placeholder="Enter your username"
                        oninput="this.value = this.value.replace(/\s/g, '')"
                    >
                </div>

                <!-- Add more input fields as needed -->
                <div class="input-field">
                    <label for="email">Email <sup>*</sup></label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        maxlength="50"
                        required 
                        placeholder="Enter your email"
                        oninput="this.value = this.value.replace(/\s/g, '')"
                    >
                </div>
                <div class="input-field">
                    <label for="password">Password <sup>*</sup></label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        maxlength="20"
                        required 
                        placeholder="Enter your password"
                        oninput="this.value = this.value.replace(/\s/g, '')"
                    >
                </div>
                <div class="input-field">
                    <label for="confirm_password">Confirm Password <sup>*</sup></label>
                    <input 
                        type="password" 
                        id="confirm_password" 
                        name="confirm_password" 
                        maxlength="20"
                        required 
                        placeholder="Confirm your password"
                        oninput="this.value = this.value.replace(/\s/g, '')"
                    >
                </div>
                <div class="input-field">
                    <label for="image">Profile Image <sup>*</sup></label>
                    <input 
                        type="file" 
                        id="image" 
                        name="image" 
                        accept="image/png, image/jpg, image/jpeg"
                        required
                    >
                <button type="submit" name="register" class="btn">Register</button>
                <p>already have an account? <a href="login.php">Login here</a></p>
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