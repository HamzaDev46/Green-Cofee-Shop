<?php
$db_name='mysql:host=localhost;dbname=shop_db1';
$db_user='root';
$db_password='';
$conn = new PDO($db_name, $db_user, $db_password);


// if ($connection) {
//     echo "Connected to the database successfully!";
// }

function unique_id($length = 20) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[mt_rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

?>