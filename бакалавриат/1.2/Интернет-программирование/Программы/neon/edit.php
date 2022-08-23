<?php

session_start();
require_once("connection.php");


$username = $_SESSION['session_username'];
$query2 = "SELECT * FROM users WHERE username='".$username."'";
$result2 = mysqli_query($con, $query2) or die ("Error : " . mysqli_error());
$row2 = mysqli_fetch_assoc($result2);


if (isset($_SESSION['session_username'])) {
    $username = $_SESSION['session_username'];
    if (isset($_POST["button"])) {
        $role = $_POST["option"];
        $id = $_POST["id"];
        $username = $_POST["username"];
        $password = $_POST["password"];
        $query = "UPDATE users SET username = '".$username."',password = md5('".$password."'),role = '".$role."' WHERE id = '".$id."'";
        mysqli_query($con, $query) or die ("Error : " . mysqli_error());
        echo "Пользователь изменен"; ?><br><?
    } else {
        echo "Нельзя изменить пользователя"; ?><br><?
    }
    if ($row2["role"] == "1") {
        echo "<a href=\"tables-datatable-moder.php\">Вернуться</a>"; ?><br><?
    }
    if ($row2["role"] == "2") {
        echo "<a href=\"tables-datatable.php\">Вернуться</a>"; ?><br><?
    }
} else {
    echo "Вы не авторезированны"; ?><br><?
}



?>