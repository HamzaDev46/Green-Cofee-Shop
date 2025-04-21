<?php
$db_name='mysql:host=localhost;dbname=shop_db';
$db_user='root';
$db_password='';
$connection = new PDO($db_name, $db_user, $db_password);

// if ($connection) {
//     echo "Connected to the database successfully!";
// }

function unique_id() {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[mt_rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
?>