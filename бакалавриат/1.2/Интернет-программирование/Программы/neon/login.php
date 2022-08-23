<?php
session_start();
?>

<?php require_once("connection.php"); ?>
<?php

$resp = array();

$username = htmlspecialchars($_POST['username']);
$password = htmlspecialchars($_POST['password']);
$resp['submitted_data'] = $_POST;

$login_status = 'invalid';

if (!empty($_POST['username']) && !empty($_POST['password'])) {
    if ($query = mysqli_query($con, "SELECT * FROM users WHERE username='" . $username . "' AND password=md5('" . $password . "')")) {
        while ($row = mysqli_fetch_assoc($query)) {
            $dbusername = $row['username'];
            $dbrole = $row['role'];
            $dbpassword = $row['password'];
        }
        if (($username == $dbusername) && ($dbpassword === md5($password))) {
            $_SESSION['session_username'] = $username;
            $login_status = 'success';
        }
    }
}

$resp['login_status'] = $login_status;

if($login_status == 'success')
{

    if ($dbrole == "0") {
        $resp['redirect_url'] = 'extra-users.php';
    }
    if ($dbrole == "1") {
        $resp['redirect_url'] = 'extra-moderator.php';
    }
    if ($dbrole == "2") {
        $resp['redirect_url'] = 'extra-profile.php';
    }
}

echo json_encode($resp);
?>