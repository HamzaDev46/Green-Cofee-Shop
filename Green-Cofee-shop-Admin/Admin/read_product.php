<?php
include '../components/connection.php';
session_start();
$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('location: login.php');
    exit;
}

$get_id = $_GET['post_id'];
//delete product
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
    <title>Green Coffee - Admin panel - Read Products</title>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Product Details</title>
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
      <style>
        .read-post {
            width: 100%;
            max-width: 1000px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-left: 10%;
        }

        .read-post:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.12);
        }

        .heading {
            text-align: center;
            padding: 30px 20px;
            background: var(--primary);
            color: white;
            font-size: 32px;
            font-weight: 700;
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
        }

        .heading::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #f72585 0%, #7209b7 100%);
        }

        .empty {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }

        .empty p {
            font-size: 18px;
            margin-bottom: 20px;
        }

        .empty a {
            display: inline-block;
            margin-top: 1.5rem;
            padding: 14px 30px;
            background: linear-gradient(90deg, #4361ee 0%, #3a0ca3 100%);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            transition: all 0.3s ease;
            font-weight: 500;
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }

        .empty a:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(67, 97, 238, 0.4);
        }

        form {
            display: flex;
            flex-wrap: wrap;
            padding: 40px;
        }

        .image-container {
            flex: 1;
            min-width: 300px;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f8f9fa;
            border-radius: 16px;
            margin-right: 30px;
        }

        .image {
            max-width: 100%;
            max-height: 400px;
            object-fit: contain;
            border-radius: 12px;
            transition: transform 0.5s ease;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        .image:hover {
            transform: scale(1.03);
        }

        .product-details {
            flex: 1;
            min-width: 300px;
            padding: 20px 0;
        }

        .status {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 600;
            margin-bottom: 25px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            background: #f8f9fa;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .price {
            font-size: 36px;
            font-weight: 700;
            color: #4361ee;
            margin: 20px 0;
            display: flex;
            align-items: center;
        }

        .price::before {
            content: 'Price:';
            font-size: 20px;
            font-weight: 500;
            color: #6c757d;
            margin-right: 12px;
        }

        .title {
            font-size: 32px;
            font-weight: 700;
            color: #212529;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e9ecef;
            line-height: 1.3;
        }

        .content {
            color: #495057;
            line-height: 1.8;
            margin-bottom: 30px;
            font-size: 17px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 12px;
            box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.03);
        }

        .flex-btn {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 40px;
        }

        .btn-edit,
        .btn-delete,
        .btn-view {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 14px 25px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 16px;
            flex: 1;
            min-width: 140px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-edit {
            background: linear-gradient(90deg, #2ecc71 0%, #27ae60 100%);
            color: white;
        }

        .btn-delete {
            background: linear-gradient(90deg, #e74c3c 0%, #c0392b 100%);
            color: white;
        }

        .btn-view {
            background: linear-gradient(90deg, #3498db 0%, #2980b9 100%);
            color: white;
        }

        .btn-edit:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(46, 204, 113, 0.35);
        }

        .btn-delete:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(231, 76, 60, 0.35);
        }

        .btn-view:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(52, 152, 219, 0.35);
        }

        i {
            margin-right: 10px;
            font-size: 20px;
        }

        @media (max-width: 900px) {
            form {
                flex-direction: column;
                padding: 30px;
            }

            .image-container {
                width: 100%;
                margin-right: 0;
                margin-bottom: 30px;
            }

            .product-details {
                width: 100%;
            }

            .flex-btn {
                justify-content: center;
            }

            .btn-edit,
            .btn-delete,
            .btn-view {
                flex: 1;
                min-width: 120px;
            }
        }

        @media (max-width: 576px) {
            .heading {
                font-size: 26px;
                padding: 25px 15px;
            }

            form {
                padding: 20px;
            }

            .title {
                font-size: 26px;
            }

            .price {
                font-size: 30px;
            }

            .flex-btn {
                flex-direction: column;
            }

            .btn-edit,
            .btn-delete,
            .btn-view {
                width: 100%;
            }
        }
    </style>
 </head>

<body>
    <?php include '../components/admin_header.php'; ?>

    <div class="main">
        <div class="banner">
            <h1>Read Products</h1>
        </div>
        <div class="title2">
            <a href="dashboard.php">All Products</a><span> / Read Products</span>
        </div>
        <section class="read-post">
            <h1 class="heading">Read Products</h1>

            <?php
        $select_product = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
        $select_product->execute([$get_id]);

        if($select_product->rowCount() > 0){
            while($fetch_product = $select_product->fetch(PDO::FETCH_ASSOC)){
    ?>
            <form action="" method="post">
                <input type="hidden" name="product_id" value="<?= $fetch_product['id']; ?>">

                <div class="status" style="color: <?= ($fetch_product['status']=='active') ? 'green' : 'red'; ?>">
                    <?= $fetch_product['status']; ?>
                </div>

                <?php if($fetch_product['image'] != '') { ?>
                <div class="image-container">
                    <img src="../image/<?= $fetch_product['image']; ?>" class="image">
                </div>
                <?php } ?>

                <div class="product-details">
                    <div class="price">$<?= $fetch_product['price']; ?>/-</div>
                    <div class="title"><?= $fetch_product['name']; ?></div>
                    <div class="content"><?= $fetch_product['description'] ?? "No description available."; ?></div>

                    <div class="flex-btn">
                        <a href="edit_product.php?id=<?= $fetch_product['id']; ?>" class="btn-edit">
                            <i class='bx bx-edit'></i>Edit
                        </a>

                          <button type="submit" name="delete" class="btn-delete" onclick="return confirm('delete this product');"><i class='bx bx-trash'></i>delete</button>
                        
                        <a href="view_product.php?post_id=<?= $get_id; ?>" class="btn-view">
                            <i class='bx bx-arrow-to-left'></i>
                        </a>
                    </div>
                </div>
            </form>
            <?php
            }
        } else {
            echo '
            <div class="empty">
                <p>No product added yet! <br>
                <a href="add_products.php">Add product</a></p>
            </div>
            ';
        }
    ?>
        </section>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script type="text/javascript" src="script.js"></script>
    <?php include '../components/alert.php'; ?>
</body>

</html>