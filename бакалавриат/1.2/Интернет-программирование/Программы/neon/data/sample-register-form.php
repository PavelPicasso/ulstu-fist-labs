<?php
require_once("../connection.php");
$resp = array();

$username = $_POST["username"];
$password = $_POST["password"];
$password_again = $_POST["password_again"];
$resp['submitted_data'] = $_POST;
$result = mysqli_query($con,"SELECT * FROM `users` WHERE `username` = '".$username."'");

if (mysqli_fetch_row($result) > 0) {
    $resp['success'] = 0;
} else {
    mysqli_query($con, "INSERT INTO users (username, password) VALUES('{$username}', md5('{$password}'))");
    $resp['success'] = 1;
}
echo json_encode($resp);