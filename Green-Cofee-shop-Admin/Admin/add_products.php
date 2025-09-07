<?php
include '../components/connection.php';
session_start();
$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('location: login.php');
    exit;
}

// add product in database
if (isset($_POST['submit'])) {
    $id = unique_id();

    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
    $price = htmlspecialchars($_POST['price'], ENT_QUOTES, 'UTF-8');
    $content = htmlspecialchars($_POST['content'], ENT_QUOTES, 'UTF-8');

    $status = 'active'; // publish ke case mein hamesha active

    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $image = filter_var($image, FILTER_UNSAFE_RAW);
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = '../image/' . $image;

        $select_image = $conn->prepare("SELECT * FROM `products` WHERE image = ?");
        $select_image->execute([$image]);

        if ($select_image->rowCount() > 0) {
            $warning_msg[] = 'image name repeated';
        } elseif ($image_size > 2000000) {
            $warning_msg[] = 'image size is too large';
        } else {
            move_uploaded_file($image_tmp_name, $image_folder);
        }
    } else {
        $image = "";
    }

    if (!isset($warning_msg)) {
        $insert_product = $conn->prepare("INSERT INTO `products` (id, name, price, image, product_detail, status) VALUES(?,?,?,?,?,?)");
        $insert_product->execute([$id, $name, $price, $image, $content, $status]);
        $success_msg[] = 'Product published successfully!';
    }
}
//save as draft
if (isset($_POST['draft'])) {
    $id = unique_id();

    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
    $price = htmlspecialchars($_POST['price'], ENT_QUOTES, 'UTF-8');
    $content = htmlspecialchars($_POST['content'], ENT_QUOTES, 'UTF-8');

    $status = 'draft'; // draft ke case mein hamesha draft

    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $image = filter_var($image, FILTER_UNSAFE_RAW);
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = '../image/' . $image;

        $select_image = $conn->prepare("SELECT * FROM `products` WHERE image = ?");
        $select_image->execute([$image]);

        if ($select_image->rowCount() > 0) {
            $warning_msg[] = 'image name repeated';
        } elseif ($image_size > 2000000) {
            $warning_msg[] = 'image size is too large';
        } else {
            move_uploaded_file($image_tmp_name, $image_folder);
        }
    } else {
        $image = "";
    }

    if (!isset($warning_msg)) {
        $insert_product = $conn->prepare("INSERT INTO `products` (id, name, price, image, product_detail, status) VALUES(?,?,?,?,?,?)");
        $insert_product->execute([$id, $name, $price, $image, $content, $status]);
        $success_msg[] = 'Product saved as draft!';
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
    <title>Green Coffee - Admin panel - Add Products</title>
</head>

<body>
    <?php include '../components/admin_header.php'; ?>

    <div class="main">
        <div class="banner">
            <h1>add products</h1>
        </div>
        <div class="title2">
            <a href="dashboard.php">dashboard</a><span> / Add Products</span>
        </div>
        <section class="form-container">
            <h1 class="heading">add products</h1>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="input-field">
                    <label>product name <sup>*</sup></label>
                    <input type="text" name="name" maxlength="100" required placeholder="add product name">
                </div>
                <div class="input-field">
                    <label>product price <sup>*</sup></label>
                    <input type="number" name="price" maxlength="100" required placeholder="add product name">
                </div>
                <div class="input-field">
                    <label>product detail <sup>*</sup></label>
                    <textarea name="content" required maxlength="10000" required
                        placeholder="write product description"></textarea>
                </div>
                <div class="input-field">
                    <label>product image <sup>*</sup></label>
                    <input type="file" name="image" required accept="image/*" required>
                </div>
                <div class="flex-btn">
                    <button type="submit" name="submit" class="btn">publish product</button>
                    <button type="submit" name="draft" class="btn">save as draft</button>
                </div>
            </form>
        </section>
    </div>


    <!-- Scripts at the bottom for better performance -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script type="text/javascript" src="script.js"></script>
    <?php include '../components/alert.php'; ?>
</body>

</html>