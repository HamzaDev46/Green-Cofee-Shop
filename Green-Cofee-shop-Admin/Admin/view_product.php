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

    <style>
        :root {
            --primary: #3a6e3e;
            --primary-light: #4c8c50;
            --light-green: #e8f5e9;
            --secondary: #8f6b3d;
            --light: #f5f5f5;
            --dark: #333;
            --gray: #777;
            --danger: #e74c3c;
            --success: #2ecc71;
            --warning: #f39c12;
            --border: #ddd;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .heading {
            font-size: 24px;
            color: var(--dark);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary);
        }

        .show-post .box-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            justify-content: center;
        }

        .show-post .box-container .box {
            background: white;
            border-radius: 0.5rem;
            padding: 1.5rem;
            box-shadow: var(--box-shadow);
            position: relative;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .show-post .box-container .box:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .show-post .box-container .box .img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            border: 1px solid var(--border);
        }

        .show-post .box-container .box .status {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            z-index: 2;
        }

        .show-post .box-container .box .price {
            font-size: 1.5rem;
            color: var(--primary);
            margin: 0.5rem 0;
            font-weight: 700;
        }

        .show-post .box-container .box .title {
            font-size: 1.2rem;
            color: var(--dark);
            margin-bottom: 1.5rem;
            text-transform: capitalize;
        }

        .show-post .box-container .box .flex-btn {
            display: flex;
            flex-direction: row;
            gap: 0.8rem;
            margin-top: auto;
            justify-content: space-between;
            flex-wrap: nowrap;
        }

        /* Buttons */
        .show-post .box-container .box .btn-edit {
            padding: 0.7rem 0.5rem;
            border: none;
            border-radius: 0.5rem;
            text-align: center;
            text-decoration: none;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.3rem;
            font-size: 0.9rem;
            min-width: 80px;
            width: 100%;
            box-sizing: border-box;
            background-color: var(--primary);
            color: white;
        }

        .show-post .box-container .box .btn-edit:hover {
            background-color: var(--primary-light);
        }

        .show-post .box-container .box .btn-view {
            padding: 0.7rem 0.5rem;
            border: none;
            border-radius: 0.5rem;
            text-align: center;
            text-decoration: none;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.3rem;
            font-size: 0.9rem;
            min-width: 80px;
            width: 100%;
            box-sizing: border-box;
            background-color: var(--secondary);
            color: white;
        }

        .show-post .box-container .box .btn-view:hover {
            background-color: #a17a4a;
        }

        .show-post .box-container .box .btn-delete {
            padding: 0.7rem 0.5rem;
            border: none;
            border-radius: 0.5rem;
            text-align: center;
            text-decoration: none;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.3rem;
            font-size: 0.9rem;
            min-width: 80px;
            width: 100%;
            box-sizing: border-box;
            background-color: var(--danger);
            color: white;
        }

        .show-post .box-container .box .btn-delete:hover {
            background-color: #c0392b;
        }

        .show-post .empty {
            text-align: center;
            padding: 40px 20px;
            background: white;
            border-radius: 8px;
            box-shadow: var(--box-shadow);
            grid-column: 1 / -1;
        }

        .show-post .empty p {
            font-size: 18px;
            color: var(--gray);
            margin-bottom: 20px;
        }

        .show-post .empty a {
            display: inline-block;
            padding: 12px 25px;
            background-color: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }

        .show-post .empty a:hover {
            background-color: var(--primary-light);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .show-post .box-container {
                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            }

            .show-post .box-container .box .img {
                height: 160px;
            }

            .show-post .box-container .box .flex-btn {
                flex-direction: row;
                flex-wrap: wrap;
            }
        }

        @media (max-width: 576px) {
            .show-post .box-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
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
