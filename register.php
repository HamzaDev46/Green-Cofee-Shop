<?php
include 'components/connection.php';
session_start();
if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}
else{
    $user_id = '';
}
//register user
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

    $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? ");
    $select_user->execute([$email]);
    $row= $select_user->fetch(PDO::FETCH_ASSOC);
    if($select_user->rowCount() > 0)
    {
        $message[] = 'user already exist!';
        
    }else
    {
        if($pass!= $cpass)
        {
            $message[] = 'confirm password not matched!';
         }
         else{
            $insert_user=$conn->prepare("INSERT INTO `users`(id, name, email, password) VALUES(?,?,?,?)");
            $insert_user->execute([$id, $name, $email, $pass]);
            $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ? ");
            $select_user->execute([$email, $pass]);
            $row= $select_user->fetch(PDO::FETCH_ASSOC);
            if($select_user->rowCount() > 0)
            {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_name'] = $row['name'];
                $_SESSION['user_email'] = $row['email'];
                header('location:home.php');
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
    <title>Green tea - register now</title>
</head>
<body>

        <section class='form-container'>
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
                    <button type='submit' name='submit'  value='register now' class='btn'>Register</button>
                    <p>already have an account?<a href="login.php">login now</a></p>
                </form>
        </section>

</body>
</html>