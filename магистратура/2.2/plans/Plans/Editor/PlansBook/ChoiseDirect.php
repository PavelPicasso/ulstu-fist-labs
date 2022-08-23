<?php
   session_start();
   if (!(($_SESSION["status"] == 0)||($_SESSION["status"] == 2))){
      Header ("Location: ../Unreg.html");
      exit;
   }
   if (!empty($_GET['plan'])) $plan=$_GET['plan'];
   if (!empty($_POST['plan'])) $plan = $_POST['plan'];
   if (!empty($_GET['shift'])) $shift = $_GET['shift'];
   if (!empty($_POST['shift'])) $shift = $_POST['shift'];
?>
<HTML>
<HEAD>
<?php
if (isset ($shift) && (strcmp($shift,"editSpc")==0)){
  echo "<TITLE>Редактирование заголовка УП</TITLE>";
}else{
  echo "<TITLE>Создание нового учебного плана</TITLE>";
}
?>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<link rel="stylesheet" href="../../../CSS/PlansEditor.css" type="text/css">
</HEAD><BODY topmargin="1" leftmargin="5" marginheight="1" marginwidth="5">
<form name=fed method=post action="ChoiseSpec.php">
<script  language="JavaScript">
<!--
			top.LeftDown.location='../downNewP.html';	
//-->
			</script>
<em class='h1'>
<?php
   include("../PlanCalculatFunc.php");
   CreateConnection();

   if (isset ($shift) && (strcmp($shift,"editSpc")==0)){
      echo "<center>Редактирование заголовка УП</center></em>";
      echo "<table border='0' width='100%' cellpadding='0' cellspacing='2'><tr><td height='5' bgcolor=\"#92a2d9\"><img src=\"../img/line.gif\" width=15 height=15 hspace=0 vspace=0 border='0' align='left'></td></tr></table>";
   
      if (($_SESSION["status"] == 0) && ($_SESSION["statusCode"]==0)){$q[] = "select CodeOfPlan, MinistryCode, SpcName, PlnName, CodeOfDepart from specials, plans where (plans.CodeOfSpecial=specials.CodeOfSpecial and plans.CodeOfSpecialization='0') order by MinistryCode, PlnName";}
      else {$q[] = "select CodeOfPlan, MinistryCode, SpcName, PlnName, CodeOfDepart from specials, plans where (plans.CodeOfSpecial=specials.CodeOfSpecial and (plans.CodeOfSpecialization='0')) and specials.CodeOfFaculty=".$_SESSION["statusCode"]." order by MinistryCode, PlnName";}

      if (! empty($plan)){
          $PlanData = GetPlanInfo($plan);
          echo "<h2>".$PlanData["PlanSpcCode"]."&nbsp;".$PlanData["PlanSpcName"]."&nbsp;".$PlanData["PlnName"]."</h2>";
      }
   }else{
      echo "<center>Создание нового учебного плана</center></em>";
      echo "<table border='0' width='100%' cellpadding='0' cellspacing='2'><tr><td height='5' bgcolor=\"#92a2d9\"><img src=\"../img/line.gif\" width=15 height=15 hspace=0 vspace=0 border='0' align='left'></td></tr></table>";
   }
?>

<h2>Выберите направление&nbsp;&nbsp;&nbsp;</h2>
<CENTER>
<table  border=0  align='center'>
<TR>
<?php
   $q= "select CodeOfDirect, MinistryCode, DirName from directions";
   if($_SESSION["status"] == 2){//Если пользователь кафедра то отбираем направления по кафедре
      $q .= " where CodeOfDepart = ".$_SESSION["statusCode"];
   }
   else{ //если пользователь уч. часть и выбран факультет, то отбираем направления по факультету
      if($_SESSION["statusCode"]!=0){$q .= " where CodeOfFaculty = ".$_SESSION["statusCode"];}
   }
   echo "<TD width=4%  valign='top' align='center'><INPUT TYPE='RADIO' NAME='Dir' VALUE='0' CHECKED></INPUT></TD>";
   echo "<TD width=5%   valign='top'>--------</TD>";
   echo "<TD width=40% valign='top'>Без направления</TD>";
   echo "<td width=2%></td>";
   $i = 1;

   //Получение данных о специальности
   $result = mysql_query($q, $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");

   while($row = mysql_fetch_object($result)) {
      if (($i % 2) == 0){ echo "<TR>\n";}
      $NameDir = $row->DirName;
      $CodeDir = $row->CodeOfDirect;
      $MinistryCode = $row->MinistryCode;
      echo "<TD width=4%  valign='top' align='center'><INPUT TYPE='RADIO' NAME='Dir' VALUE='".$CodeDir."'></INPUT></TD>";
      echo "<TD width=5%   valign='top'>".$MinistryCode."</TD>";
      echo "<TD width=40% valign='top'>".$NameDir."</TD>";
      
      if (($i % 2) == 1){ echo "</TR>\n";}
      else {echo "<TD width=2%></TD>";}
      $i ++;
   }

   if (($i % 2) == 1){ echo "</TR>\n";}
   if (isset ($shift) && (strcmp($shift,"editSpc")==0)&& isset ($plan)){
      echo "<input type='hidden' name='shift' value='editSpc'>\n";
      echo "<input type='hidden' name='plan' value='$plan'>\n";
   }
   mysql_free_result($result);
   mysql_close($Connection);
?>
</TABLE>
</CENTER>
<BR>
<center><input type="submit" name="subm" value="Дальше" /></center></form><hr /></body></html>