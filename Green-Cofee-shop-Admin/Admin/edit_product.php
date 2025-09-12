<?php
include '../components/connection.php';
session_start();

// check admin login
if (!isset($_SESSION['admin_id'])) {
    header('location: login.php');
    exit;
}

// Get product ID from URL
if (!isset($_GET['id'])) {
    header('location: view_product.php');
    exit;
}

$post_id = $_GET['id'];

// Fetch product
$select_product = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
$select_product->execute([$post_id]);
$fetch_product = $select_product->fetch(PDO::FETCH_ASSOC);

// If product not found
if (!$fetch_product) {
    echo "<script>alert('Product not found!'); window.location.href='view_product.php';</script>";
    exit;
}

// Update product
if (isset($_POST['update'])) {
    $name    = htmlspecialchars($_POST['name'], ENT_QUOTES);
    $price   = htmlspecialchars($_POST['price'], ENT_QUOTES);
    $content = htmlspecialchars($_POST['content'], ENT_QUOTES);
    $status  = htmlspecialchars($_POST['status'], ENT_QUOTES);

    $update_product = $conn->prepare("UPDATE `products` SET name = ?, price = ?, product_detail = ?, status = ? WHERE id = ?");
    $update_product->execute([$name, $price, $content, $status, $post_id]);

    // Handle image upload
    $old_image = $_POST['old_image'];
    $image = htmlspecialchars($_FILES['image']['name'], ENT_QUOTES);
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = '../image/' . $image;

    if (!empty($image)) {
        // check for duplicate image name
        $select_image = $conn->prepare("SELECT * FROM `products` WHERE image = ?");
        $select_image->execute([$image]);
        if ($select_image->rowCount() > 0) {
            $warning_msg[] = 'Image name already exists!';
        } elseif ($image_size > 2000000) {
            $warning_msg[] = 'Image size is too large!';
        } else {
            $update_image = $conn->prepare("UPDATE `products` SET image = ? WHERE id = ?");
            $update_image->execute([$image, $post_id]);
            move_uploaded_file($image_tmp_name, $image_folder);
            if ($old_image != '' && $old_image != $image) {
                unlink('../image/' . $old_image);
            }
            $success_msg[] = 'Image updated successfully!';
        }
    }

    $success_msg[] = 'Product updated successfully!';
}

//delete product
if (isset($_POST['delete'])) {
    $product_id = $_POST['product_id'] ?? ''; // <-- fixed undefined key warning
    $product_id = filter_var($product_id, FILTER_UNSAFE_RAW);

    $select_image = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
    $select_image->execute([$product_id]);
    $fetch_image = $select_image->fetch(PDO::FETCH_ASSOC);
    if ($fetch_image && !empty($fetch_image['image'])) {
        unlink('../image/' . $fetch_image['image']);
    }

    $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ?");
    $delete_product->execute([$product_id]);
    $success_msg[] = 'Product deleted successfully!';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Admin Panel</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="admin_style.css?v=<?php echo time(); ?>">
    <style>
    .edit-post .flex-btn .btn {
        width: 33%;
        margin: .5rem;
        text-align: center;
    }

    .btn-update {
        background-color: #2ecc71;
        color: white;
    }

    .btn-back {
        background-color: #3498db;
        color: white;
    }

    .btn-delete {
        background-color: #e74c3c;
        color: white;
    }
    </style>
</head>

<body>

    <?php include '../components/admin_header.php'; ?>

    <div class="main">
        <div class="banner">
            <h1>Edit Products</h1>
        </div>
        <div class="title2">
            <a href="dashboard.php">Dashboard</a><span> / Edit Product</span>
        </div>

        <section class="edit-post">
            <h1 class="heading">Edit Product</h1>

            <div class="form-container">
                <form action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="old_image" value="<?= htmlspecialchars($fetch_product['image']); ?>">
                    <input type="hidden" name="product_id" value="<?= $fetch_product['id']; ?>"> <!-- fixed -->

                    <div class="input-field">
                        <label>Update Status</label>
                        <select name="status" required>
                            <option value="active" <?= $fetch_product['status'] === 'active' ? 'selected' : '' ?>>Active
                            </option>
                            <option value="inactive" <?= $fetch_product['status'] === 'inactive' ? 'selected' : '' ?>>
                                Inactive</option>
                        </select>
                    </div>

                    <div class="input-field">
                        <label>Product Name</label>
                        <input type="text" name="name" value="<?= htmlspecialchars($fetch_product['name']); ?>"
                            required>
                    </div>

                    <div class="input-field">
                        <label>Product Price</label>
                        <input type="number" name="price" value="<?= htmlspecialchars($fetch_product['price']); ?>"
                            required>
                    </div>

                    <div class="input-field">
                        <label>Product Detail</label>
                        <textarea name="content"
                            required><?= htmlspecialchars($fetch_product['product_detail']); ?></textarea>
                    </div>

                    <div class="input-field">
                        <label>Product Image</label>
                        <input type="file" name="image" accept="image/*">
                        <?php if(!empty($fetch_product['image'])): ?>
                        <img src="../image/<?= htmlspecialchars($fetch_product['image']); ?>" alt=""
                            style="max-width:150px;margin-top:10px;">
                        <?php endif; ?>
                    </div>

                    <div class="flex-btn">
                        <button type="submit" name="update" class="btn btn-update">Update Product</button>
                        <a href="view_product.php" class="btn btn-back">Go Back</a>
                        <button type="submit" name="delete" class="btn btn-delete"
                            onclick="return confirm('Are you sure to delete this product?');">Delete Product</button>
                    </div>
                </form>
            </div>
        </section>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script type="text/javascript" src="script.js"></script>
    <?php include '../components/alert.php'; ?>
</body>

</html>
