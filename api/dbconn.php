<?php
/**
 * Created by PhpStorm.
 * User: luka
 * Date: 23-Dec-17
 * Time: 14:41
 */
// establish database connection

$dbServername = "localhost";
$dbUsername = "root";
$dbPassword = "root";
$dbName = "ep_db";

$conn = mysqli_connect($dbServername, $dbUsername, $dbPassword, $dbName);
mysqli_set_charset($conn, 'utf8');

// Check connection
if (mysqli_connect_errno()) {
    //header("Location: ../client/error404.php");
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}
function cleanData($data){
    $data=trim($data);
    $data=stripcslashes($data);
    $data=htmlspecialchars($data);
    $data=strip_tags($data);
    return $data;
}