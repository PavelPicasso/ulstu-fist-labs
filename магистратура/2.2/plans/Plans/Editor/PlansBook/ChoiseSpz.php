<?php
   session_start();
   if (!(($_SESSION["status"] == 0)||($_SESSION["status"] == 2))){
      Header ("Location: ../Unreg.html");
      exit;
   }
   if (!empty($_GET['plan'])) $plan = $_GET['plan'];
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

<?php
   if (isset ($shift) && (strcmp($shift,"editSpc")==0)){
     echo "<form name=fed method=post action=\"DoEditSpc.php\">";
   }else{
     echo "<form name=fed method=post action=\"NewPlan.php\">";
   }
?>
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

      if (! empty($plan)){
          $PlanData = GetPlanInfo($plan);
          echo "<h2>".$PlanData["PlanSpcCode"]."&nbsp;".$PlanData["PlanSpcName"]."&nbsp;".$PlanData["PlnName"]."</h2>";
      }
   }else{
      echo "<center>Создание нового учебного плана</center></em>";
      echo "<table border='0' width='100%' cellpadding='0' cellspacing='2'><tr><td height='5' bgcolor=\"#92a2d9\"><img src=\"../img/line.gif\" width=15 height=15 hspace=0 vspace=0 border='0' align='left'></td></tr></table>";
   }
?>
<h2>Выберите специализацию&nbsp;&nbsp;&nbsp;</h2>
<?php
   $Direct = $_POST['Dir'];
   $Spec = $_POST['Spec'];
   $q = "";

   if (empty($Direct)){
      $result = mysql_query("select MinistryCode, DirName from directions where CodeOfDirect=".$Direct, $Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
      if ($row = mysql_fetch_object($result)){
         echo "<strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Направление ".$row->MinistryCode." ".$row->DirName."</storng><P>\n";
      }
   }

   if (empty($Spec)){
      $result = mysql_query("select MinistryCode, SpcName from specials where CodeOfSpecial=".$Spec, $Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
      if ($row = mysql_fetch_object($result)){
         echo "<strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Специальность ".$row->MinistryCode." ".$row->SpcName."</storng><P>\n";
      }
   }

   echo "<CENTER>\n";
   echo "<table  border=0  align='center'>\n";


   echo "<TR><TD width=4%  valign='top' align='center'><INPUT TYPE='RADIO' NAME='Spz' VALUE='0' CHECKED></INPUT></TD>";
   echo "<TD width=5%   valign='top'>--------</TD>";
   echo "<TD width=40% valign='top'>Без специализации</TD>";
   echo "<td width=2%></td>";
   $i = 1;
   if (!empty($Direct)){
      $q= "select CodeOfSpecialization, MinistryCode, SpzName from specializations where CodeOfDirect=".$Direct;
   }else{
      $q= "select CodeOfSpecialization, MinistryCode, SpzName from specializations where CodeOfSpecial=".$Spec;
   }

   if($_SESSION["status"] == 2){//Если пользователь кафедра то отбираем специальности по кафедре
      $q .= " and CodeOfDepart = ".$_SESSION["statusCode"];
   }
   else{ //если пользователь уч. часть и выбран факультет, то отбираем специальности по факультету
      if($_SESSION["statusCode"]!=0){$q .= " and CodeOfFaculty = ".$_SESSION["statusCode"];}
   }

   //Получение данных о специальности
   $result = mysql_query($q, $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");

   while($row = mysql_fetch_object($result)) {
      if (($i % 2) == 0){ echo "<TR>\n";}
      $NameSpz = $row->SpzName;
      $CodeSpz = $row->CodeOfSpecialization;
      $MinistryCode = $row->MinistryCode;
      echo "<TD width=4%  valign='top' align='center'><INPUT TYPE='RADIO' NAME='Spz' VALUE='".$CodeSpz."'></INPUT></TD>";
      echo "<TD width=5%   valign='top'>".$MinistryCode."</TD>";
      echo "<TD width=40% valign='top'>".$NameSpz."</TD>";
      
      if (($i % 2) == 1){ echo "</TR>\n";}
      else {echo "<TD width=2%></TD>";}
      $i ++;
   }

   if (($i % 2) == 1){ echo "</TR>\n";}

   mysql_free_result($result);
   mysql_close($Connection);
   echo "<input type='hidden' name='Spec' value=$Spec>";
   echo "<input type='hidden' name='Dir' value=$Direct>";
   if (isset ($shift) && (strcmp($shift,"editSpc")==0)&& isset ($plan)){
      echo "<input type='hidden' name='shift' value='editSpc'>\n";
      echo "<input type='hidden' name='plan' value='$plan'>\n";
   }
?>
</TABLE>
</CENTER>
<BR>

<center>
<input type="submit" name="subm" value="Дальше" /></center></form><hr /></body></html>
