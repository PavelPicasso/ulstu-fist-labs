<?php
session_start();
//var_dump($_SESSION);

if ($_SESSION['isLoginRating'] == 1) {
    if ($_GET['logout'] == 1) {
        unset($_SESSION['isLoginRating']);
        unset($_SESSION['userCode']);
		unset($_SESSION["userRole"]);
		unset($_SESSION["userRoleId"]);
		unset($_SESSION["statusName"]);
		Header ("Location: login.php");
		exit;
    } else {
		Header ("Location: index.php");
		exit;
	}
}

if (isset($_POST['login'])) {
	$user = $_POST['user'];
    $pass = $_POST['pass'];
    if (empty($user)) {
        $errorMsg = "Не введен логин!";
    } elseif (empty($pass)) {
        $errorMsg = "Не введен пароль!";
    }
    if (strlen($errorMsg) == 0) {
		include ("SQLFunc.php");
		$db = DataBase::getDB();
        $q = "select CodeOfUser, pass, role, status, statusCode from metausers where login={?}";
		$userInfo = $db->selectRow($q, array($user));

        if (empty($userInfo)) {
            $errorMsg = "Неверен логин или пароль";
        } else {
            $userPass = $userInfo['pass'];
            if ($userPass != crypt($pass, "22")) {
                $errorMsg = "Неверен логин или пароль";
            } else {
				$userRoleId = null;

				if ($userInfo["role"] == "tch") {
					$q = "select CodeOfTeacher from teachers where CodeOfUser={?}";
					$res = $db->selectRow($q, array($userInfo['CodeOfUser']));
					$userRoleId = $res["CodeOfTeacher"];
				} elseif ($userInfo["role"] == "st") {
					$q = "select CodeOfStudent from students where CodeOfUser={?}";
					$res = $db->selectRow($q, array($userInfo['CodeOfUser']));
					$userRoleId = $res["CodeOfStudent"];
				}

				if (is_null($userRoleId)) {
					$errorMsg = "Информации о пользователе нет в системе";
				} else {

					$_SESSION['isLoginRating'] = 1;
					/*$statusName = $userInfo['status'];
					$_SESSION['statusCode'] = $userInfo['statusCode'];
					//    $statusCode = 0;
					if (strcmp($statusName, "uc") == 0) {
						$_SESSION['status'] = 0;
						$_SESSION["uCode"] = $codeOfUser;
						if (!isset($_SESSION['statusCode'])) {
							$_SESSION['statusCode'] = 0;
						}
					}
					if (strcmp($statusName, "dec") == 0) {
						$_SESSION['status'] = 1;
					}
					if (strcmp($statusName, "kaf") == 0) {
						$_SESSION['status'] = 2;
					}*/

					$_SESSION["userCode"] = $userInfo['CodeOfUser'];
					$_SESSION["userRole"] = $userInfo['role'];
					$_SESSION["userRoleId"] = $userRoleId;
					$_SESSION["statusName"] = $userInfo['status'];

					Header ("Location: index.php");
					exit;
				}
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Рейтинг студентов</title>

    <!-- Bootstrap Core CSS -->
    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-panel panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Вход в систему</h3>
                </div>
                <div class="panel-body">
                    <form role="form" method="post">
                        <fieldset>
							<?if (!empty($errorMsg)):?>
								<p class="text-danger"><?=$errorMsg?></p>
							<?endif;?>
                            <div class="form-group">
                                <input class="form-control" placeholder="Логин" name="user" type="text" autofocus>
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="Пароль" name="pass" type="password" value="">
                            </div>
                            <!-- Change this to a button or input when using this as a form -->
                            <input type="submit" name="login" href="login.php" class="btn btn-lg btn-success btn-block" value="Войти">
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="bower_components/metisMenu/dist/metisMenu.min.js"></script>

<!-- Custom Theme JavaScript -->
<script src="dist/js/sb-admin-2.js"></script>

</body>

</html>