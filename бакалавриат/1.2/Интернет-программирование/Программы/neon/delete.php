<?php

session_start();
require_once("connection.php");

$username = $_SESSION['session_username'];
$query2 = "SELECT * FROM users WHERE username='".$username."'";
$result2 = mysqli_query($con, $query2) or die ("Error : " . mysqli_error());
$row2 = mysqli_fetch_assoc($result2);


if (isset($_SESSION['session_username'])) {
    $username = $_SESSION['session_username'];
    if (isset($_GET["id"])) {
        $id = $_GET["id"];
        $query = "DELETE FROM users WHERE id = '".$id."'";
        mysqli_query($con, $query) or die ("Error : " . mysqli_error());
        echo "Пользователь удален"; ?><br><?
    } else {
        echo "Нельзя удалить пользователя"; ?><br><?
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