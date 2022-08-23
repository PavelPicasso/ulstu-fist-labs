<?php
    session_start();
   //include("../cfg.php");
   //$user = $_POST['user'];
   //$pass = $_POST['pass'];

   if ($_SESSION['isLogin'] != 1) {
      Header ("Location: Login.php");
      exit;
   }
   /*$Connection = mysql_connect($data_source , $dbi_user , $dbi_password)
      or die("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
   mysql_select_db("plans") 
      or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");
   //$result = mysql_query("select status, statusCode, CodeOfUser from metausers where login='$PHP_AUTH_USER' and pass='".crypt($PHP_AUTH_PW,"22")."'",$Connection)
  $result = mysql_query("select status, statusCode, CodeOfUser from metausers where login='$user' and pass='".crypt($pass,"22")."'",$Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");         /*  */
   /*session_start();
   //session_register("status","statusCode");
   $_SESSION['status'] = -1; //тип пользователя неопределен
   $_SESSION['statusCode'] = 0; //код 
   if (!($row = mysql_fetch_object($result))){
      $result = mysql_query("select status, statusCode, CodeOfUser from metausers where login='$PHP_AUTH_USER'",$Connection)
//        $result = mysql_query("select status, statusCode, CodeOfUser from metausers where login='{$_SERVER['REMOTE_USER']}'",$Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");         /*  */
      /*if (!($row = mysql_fetch_object($result))){
         Header ("Location: Unreg.html");
         exit;
      }
      $row = mysql_fetch_object($result);
      $CodeOfUser = $row['CodeOfUser'];
      
   }
   $statusName = $row->status;
   $_SESSION['statusCode'] = $row->statusCode;
 //    $statusCode = 0;
   if (strcmp($statusName,"uc") == 0){ 
      $_SESSION['status']=0;
      $_SESSION["uCode"] = $row->CodeOfUser;
      if (!isset($_SESSION['statusCode'])){ $_SESSION['statusCode'] = 0;}
   }
   if (strcmp($statusName,"dec") == 0){ $_SESSION['status']=1;}
   if (strcmp($statusName,"kaf") == 0){ $_SESSION['status']=2;}
   //Закрытие соединения
   mysql_free_result($result);
   mysql_close($Connection);*/
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
<title>Редактор БД Учебных планов</title>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<link rel="stylesheet" href="../../../CSS/PlansEditor.css" type="text/css">
<!-- frames -->
<frameset  cols="126,*" border=0>
    <!--<frame name="LeftPanel" marginwidth="0" marginheight="0"  framespacing="0" scrolling="yes" frameborder=0>-->
    <!--frameset  rows="418,*" border=0-->
    <frameset  rows="50,418,*" border=0>
    
      <frame name="Menu" src="Menu.php" marginwidth="0" marginheight="2"   topmargin="2" leftmargin="0" scrolling="no" frameborder="0" framespacing="0">

<?php
    //Вывод верхнего меню
    if ($_SESSION['status']==0){echo "<frame name=\"LeftUp\" src=\"up.html\" marginwidth=\"0\" marginheight=\"2\"   topmargin=\"2\" leftmargin=\"0\" scrolling=\"no\" frameborder=0 framespacing=\"0\">";}
    if ($_SESSION['status']==1){echo "<frame name=\"LeftUp\" src=\"upDean.html\" marginwidth=\"0\" marginheight=\"2\"   topmargin=\"2\" leftmargin=\"0\" scrolling=\"no\" frameborder=0 framespacing=\"0\">";}
    if ($_SESSION['status']==2){echo "<frame name=\"LeftUp\" src=\"upDep.html\" marginwidth=\"0\" marginheight=\"2\"   topmargin=\"2\" leftmargin=\"0\" scrolling=\"no\" frameborder=0 framespacing=\"0\">";}
    //Вывод нижней части меню
    if (($_SESSION['status']==0)||($_SESSION['status']==2)){echo "<frame name=\"LeftDown\" src=\"down0.html\" marginwidth=\"0\" marginheight=\"2\"   topmargin=\"2\" leftmargin=\"0\" scrolling=\"no\" frameborder=0 framespacing=\"0\">";}
    if ($_SESSION['status']==1){echo "<frame name=\"LeftDown\" src=\"down4.html\" marginwidth=\"0\" marginheight=\"2\"   topmargin=\"2\" leftmargin=\"0\" scrolling=\"no\" frameborder=0 framespacing=\"0\">";}
?>


    </frameset>                                         
    <!--</frame>-->
<?php
    //Вывод стартовой страницы
    if (isset($_SESSION["teacher"])) {
        echo "<frame name=\"Body\" src=\"TeacherPage/main.php\" marginwidth=\"0\" marginheight=\"2\"  framespacing=\"0\" scrolling=\"yes\" frameborder=0>";
    }
    if (($_SESSION['status']==2)){echo "<frame name=\"Body\" src=\"PlansBook/ChoiseP.php\" marginwidth=\"0\" marginheight=\"2\"  framespacing=\"0\" scrolling=\"yes\" frameborder=0>";}
    if ($_SESSION['status']==1){echo "<frame name=\"Body\" src=\"PlansBook/ChoisePLook.php\" marginwidth=\"0\" marginheight=\"2\"  framespacing=\"0\" scrolling=\"yes\" frameborder=0>";}

    //Вывод панели выбора контекста
    if (($_SESSION['status']==0)){
       echo "   <!--<frame name=\"RightPanel\" marginwidth=\"10\" marginheight=\"10\"  framespacing=\"0\" scrolling=\"yes\" frameborder=0>-->\n";
       echo "   <frameset  rows=\"20,*\" border=2>\n";
       echo "\n";
       echo "\n";
       echo "\n";
       echo "       <frame name=\"ContextPanel\" src=\"Context/ShowContext.php\" marginwidth=\"0\" marginheight=\"2\"   topmargin=\"2\" leftmargin=\"0\" scrolling=\"no\" frameborder=0 framespacing=\"0\">\n";
       echo "       <frame name=\"Body\" src=\"PlansBook/ChoiseP.php\" marginwidth=\"0\" marginheight=\"2\"  framespacing=\"0\" scrolling=\"yes\" frameborder=0>";
       echo "   </frameset>\n";
       echo "   <!--</frame>-->\n";
    }

?>
</frameset>
</head>

<BODY topmargin="2" leftmargin="0" marginheight="2" marginwidth="0" bgcolor="#eeeeee">
</body>
</html>

<?
?>