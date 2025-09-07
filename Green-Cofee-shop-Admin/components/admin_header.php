<?php
// admin_header.php - Fixed Version
?>
<!DOCTYPE html>
<html lang="en">
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
 </head>
 <body>
        <header class="header">
            <div class="flex">
                <a href="dashboard.php" class="logo">
                    <img src="../img/logo.jpg" alt="Logo">
                </a>

                <nav class="navbar" id="navbar">
                    <a href="dashboard.php">Dashboard</a>
                    <a href="add_products.php">Add Product</a>
                    <a href="view_product.php">View Product</a>
                    <a href="accounts.php">Accounts</a>
                </nav>

                <div class="icons">
                    <i class="bx bxs-user" id="user-btn"></i>
                    <i class="bx bx-menu" id="menu-btn"></i>
                </div>
                
                <div class="profile-detail" id="profile-detail">
                    <?php
                        $select_profile = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
                        $select_profile->execute([$admin_id]);
                        if ($select_profile->rowCount() > 0) {
                            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
                    ?>
                    <div class="profile-info">
                        <img src="../image/<?php echo $fetch_profile['image']; ?>" alt="Profile Image" class="logo-img">
                        <p><?php echo $fetch_profile['name']; ?></p>
                    </div>
                    <div class="flex-btn">
                        <a href="profile.php" class="btn">Profile</a>
                        <a href="../components/admin_logout.php" onclick="return confirm('Are you sure you want to logout?');" class="btn">Logout</a>
                    </div>
                    <?php
                        }
                    ?>
                </div>
            </div>
        </header>
    
 </body>

</html>