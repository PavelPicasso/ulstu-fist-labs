<?php
    session_start();
    //var_dump($_SESSION);

	if ($_SESSION['isLogin'] == 1) {
        //if (isset($_POST['logoutBut'])) {
        if ($_GET['logout'] == 1) {
            unset($_SESSION['isLogin']);
            unset($_SESSION['statusCode']);
            unset($_SESSION['status']);
            unset($_SESSION['uCode']);
            //unset($_SESSION['isLogin']);
            //session_destroy();
        }
        Header ("Location: index.php");
        exit;
	}

    if (isset($_POST['login'])) {
   		include("../cfg.php");
	    $user = $_POST['user'];
	    $pass = $_POST['pass'];
        $errorMsg = "";
        if (empty($user)) {
            $errorMsg = "Не введен логин!";
        } elseif (empty($pass)) {
            $errorMsg = "Не введен пароль!";
        }
        if (strlen($errorMsg) == 0) {
            include("PlanCalculatFunc.php");
            CreateConnection();
            include("Display/DisplayFunc.php");
            $q = "select status, statusCode, CodeOfUser, pass from metausers where login='".$user."'";
            $userInfo = FetchArrays($q);
            $userInfo = $userInfo[0];

            $q = "select CodeOfTeacher from teachers where CodeOfUser='".$userInfo['CodeOfUser']."'";
            $teacher = FetchArrays($q);
            $teacher = $teacher[0];

            mysql_close($Connection);
            /*$Connection = mysql_connect($data_source , $dbi_user , $dbi_password)
                or die("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<br>");
            mysql_select_db("plans")
                or die("Could not select database:".mysql_errno().": ".mysql_error()."<br>");*/
            //$result = mysql_query("select status, statusCode, CodeOfUser from metausers where login='".$user."' and pass='".crypt($pass,"22")."'",$Connection)
            //$result = mysql_query("select status, statusCode, CodeOfUser, pass from metausers where login='".$user."'", $Connection)
                //or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<br>");         /*  */
            //session_start();
            //session_register("status","statusCode");
            //$_SESSION['status'] = -1; //тип пользователя неопределен
            //$_SESSION['statusCode'] = 0; //код
            //$row = mysql_fetch_object($result);
            //Закрытие соединения
            //mysql_free_result($result);
            //mysql_close($Connection);
            if (!isset($userInfo) || empty($userInfo)) {
                $errorMsg = "Логин не верен!";
            } else {
                $userPass = $userInfo['pass'];
                if ($userPass != crypt($pass, "22")) {
                    $errorMsg = "Пароль не верен!";
                } else {
                    $_SESSION['isLogin'] = 1;
                    //var_dump($_SESSION);
                    $codeOfUser = $userInfo['CodeOfUser'];
                    $statusName = $userInfo['status'];
                    $_SESSION['statusCode'] = $userInfo['statusCode'];
                    //    $statusCode = 0;
                    if (strcmp($statusName,"uc") == 0){
                        $_SESSION['status']=0;
                        $_SESSION["uCode"] = $codeOfUser;
                        if (!isset($_SESSION['statusCode'])){ $_SESSION['statusCode'] = 0;}
                    }
                    if (strcmp($statusName,"dec") == 0){ $_SESSION['status']=1;}
                    if (strcmp($statusName,"kaf") == 0){ $_SESSION['status']=2;}

                    if (!empty($teacher["CodeOfTeacher"])) {
                        $_SESSION["teacher"] = $teacher["CodeOfTeacher"];
                    }

                    FuncRedirect("index.php");
                    //Header ("Location: index.php");
                    //exit;
                }
            }
            //Header ("Location: index.php");
            //exit;
        }
	}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<HTML>
<HEAD>
<TITLE>Учебные планы</TITLE>
<META NAME=Author CONTENT="Карпова Анна">
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<LINK rel=stylesheet href="../../CSS/PlansEditor.css" type=text/css>
</HEAD>
<BODY topmargin=1 leftmargin=5 marginheight="1" marginwidth="5">
<CENTER>
<em class='h1'><P Align=left>
Вход</em>
<table border='0' width='100%' cellpadding='0' cellspacing='2'>
<tr><td height='5' bgcolor="#92a2d9">
<img src="img/line.gif" width=15 height=15 hspace=0 vspace=0 border='0' align='left'>
</td></tr></table>
<FORM method="post" name="log" action="">
	<TABLE  class='table' BORDER=0 CELLSPACING=0 CELLPADDING=10 align='left'>
        <TR ALIGN='CENTER' VALIGN='MIDDLE'>
            <TD><em class='h2'>Логин: </em></TD>
            <TD width="210px"><INPUT type="text" name="user"></TD>
        </TR>
        <TR ALIGN='CENTER' VALIGN='MIDDLE'>
            <TD><em class='h2'>Пароль: </em></TD>
            <TD><INPUT type="password" name="pass"></TD>
        </TR>
        <TR ALIGN='CENTER' VALIGN='MIDDLE'>
            <TD><INPUT type="submit" name="login" value="Войти"></TD>
            <td><em class='ErrMessage' align='left'><?=$errorMsg?></em></td>
        </TR>
    </TABLE>
</FORM>
</BODY>
</HTML>
