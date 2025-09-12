<?php
include '../components/connection.php';
session_start();
$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('location: login.php');
    exit;
}

//delete product
if (isset($_POST['delete'])) {
    $product_id = $_POST['product_id'];
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
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="admin_style.css?v=<?php echo time(); ?>">
    <title>Green Coffee - Admin panel - All Products</title>

    
</head>

<body>
    <?php include '../components/admin_header.php'; ?>

    <div class="main">
        <div class="banner">
            <h1>All Products</h1>
        </div>
        <div class="title2">
            <a href="dashboard.php">Dashboard</a><span> / All Products</span>
        </div>
        <section class="show-post">
            <h1 class="heading">all products</h1>
            <div class="box-container">
                <?php
                $select_products = $conn->prepare("SELECT * FROM `products`");
                $select_products->execute();

                if ($select_products->rowCount() > 0) {
                    while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                        <form action="" method="post" class="box">
                            <input type="hidden" name="product_id" value="<?= $fetch_products['id']; ?>">
                            <?php if (!empty($fetch_products['image'])) { ?>
                                <img src="../image/<?= $fetch_products['image']; ?>" class="img" alt="">
                            <?php } ?>
                            <div class="status"
                                style="color: <?= $fetch_products['status'] == 'active' ? 'green' : 'red'; ?>; background-color: <?= $fetch_products['status'] == 'active' ? '#e8f5e9' : '#ffebee'; ?>">
                                <?= $fetch_products['status']; ?>
                            </div>
                            <div class="price">$<?= $fetch_products['price']; ?>/-</div>
                            <div class="title"><?= $fetch_products['name']; ?></div>
                            <div class="flex-btn">
                                <a href="edit_product.php?id=<?= $fetch_products['id']; ?>" class="btn-edit"><i class='bx bx-edit'></i>edit</a>
                                <button type="submit" name="delete" class="btn-delete" onclick="return confirm('delete this product');"><i class='bx bx-trash'></i>delete</button>
                                <a href="read_product.php?post_id=<?= $fetch_products['id']; ?>" class="btn-view"><i class='bx bx-show'></i>view</a>
                            </div>
                        </form>
                    <?php
                    }
                } else {
                    echo '
                        <div class="empty">
                            <p>No product added yet! <br>
                             <a href="add_products.php" style="margin-top:1.5rem">Add product</a></p>
                        </div>
                        ';
                }
                ?>
            </div>
        </section>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script type="text/javascript" src="script.js"></script>
    <?php include '../components/alert.php'; ?>
</body>

</html>
