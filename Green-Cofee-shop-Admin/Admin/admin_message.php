<?php
include '../components/connection.php';
session_start();

$admin_id = $_SESSION['admin_id'] ?? null;
if (!$admin_id) {
    header('location: login.php');
    exit;
}

// Delete message
if (isset($_POST['delete'])){
    $delete_id = filter_var($_POST['delete_id'], FILTER_SANITIZE_NUMBER_INT);

    $verify_delete = $conn->prepare("SELECT * FROM message WHERE id = ?");
    $verify_delete->execute([$delete_id]);

    if($verify_delete->rowCount() > 0){
        $delete_message = $conn->prepare("DELETE FROM message WHERE id = ?");
        $delete_message->execute([$delete_id]);
        $success_msg[] = 'Message deleted successfully';
    } else {
        $warning_msg[] = 'Message already deleted';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" type="text/css" href="admin_style.css?v=<?php echo time(); ?>">
<title>Green Coffee - Admin - Messages</title>
</head>
<body>
<?php include '../components/admin_header.php'; ?>

<div class="main">
    <div class="banner">
        <h1>Unread Messages</h1>
    </div>
    <div class="title2">
        <a href="dashboard.php">dashboard</a><span> / unread messages</span>
    </div>

    <section class="accounts">
        <h1 class="heading">Unread Messages</h1>
        <div class="box-container">
            <?php
            $select_messages = $conn->prepare("SELECT * FROM message ORDER BY id DESC");
            $select_messages->execute();

            if ($select_messages->rowCount() > 0) {
                while ($fetch_message = $select_messages->fetch(PDO::FETCH_ASSOC)) {
            ?>
                <div class="box">
                    <h3 class="name"><?= htmlspecialchars($fetch_message['name']); ?></h3>
                    <h4><?= htmlspecialchars($fetch_message['email']); ?></h4>
                    <p><?= nl2br(htmlspecialchars($fetch_message['message'])); ?></p>
                    <form action="" method="post" class="flex-btn">
                        <input type="hidden" name="delete_id" value="<?= $fetch_message['id']; ?>">
                        <button type="submit" name="delete" class="btn" onclick="return confirm('Delete this message?');">Delete Message</button>
                    </form>
                </div>
            <?php
                }
            } else {
                echo '<p class="empty">No unread messages found!</p>';
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
