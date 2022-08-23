<?php
   session_start();
   if (!($_SESSION["status"] == 1)){
      Header ("Location: ../Unreg.html");
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<HTML>
<HEAD>
<TITLE>Учебные планы</TITLE>
<META NAME=Author CONTENT="Карпова Анна">
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<link rel="stylesheet" href="http://plans.nilaos.ustu/CSS/PlansEditor.css" type="text/css">
</HEAD>
<BODY topmargin=1 leftmargin=5 marginheight="1" marginwidth="5">
<form name=ExpFrm method=post action="">
<em class='h1'><center>Расширение учебной нагрузки</center></em>
<table border='0' width='100%' cellpadding='0' cellspacing='2'>
<tr><td height='5' bgcolor="#92a2d9">
<img src="../img/line.gif" width=15 height=15 hspace=0 vspace=0 border='0' align='left'></td></tr>
</table><br>
<table  class='ramka' border="0" cellpadding="0" cellspacing="0"  align="center">
<tr><td cellpadding="0" cellspacing="0">
<TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
<TR ALIGN='CENTER' VALIGN='MIDDLE'>
<TH>&nbsp;</TD>
<TH><strong>Кафедра</strong></TH>
<TH><strong>Вид учебной работы</strong></TH>
<TH><strong>Семестр</strong></TH>
<TH><strong>Количество часов</strong></TH>
</TR>
<?php
   include("../PlanCalculatFunc.php");
   CreateConnection();

   //Массив с сокр. названиями кафедрами
   $RedDepartArray=array();
   $CodeDepartArray=array();
   $result = mysql_query("select Reduction, CodeOfDepart from department order by Reduction", $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   while ($row = mysql_fetch_object($result)){
      $RedDepartArray[] = $row->Reduction;
      $CodeDepartArray[] = $row->CodeOfDepart;
   }
   $result = mysql_query("select * from expansion order by CodeOfDepart", $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   while ($row = mysql_fetch_object($result)) {
      $ExpCode = $row->CodeOfExpansion;
      $DepCode = $row->CodeOfDepart;
      $ExpName = $row->ExpansionName;
      $Semestr = $row->Semester;
      $Hours   = $row->Hours;
      echo "<TR ALIGN='LEFT' VALIGN='MIDDLE'>";
      echo "<TD align='center'>$ExpCode</TD>\n";
      //Выбор кафедры
      echo "<TD align='center'>";
      reset($RedDepartArray);
      reset($CodeDepartArray);
      while (($Dep = each($RedDepartArray)) && ($CodeDep = each($CodeDepartArray))){
         if ($CodeDep[1] == $DepCode){ echo $Dep[1];}
      }
      echo "</TD>\n";
      echo "<TD align='center'>".trim($ExpName)."</INPUT></TD>\n";
      echo "<TD align='center'>$Semestr</TD>\n";
      echo "<TD align='center'>".trim($Hours)."</INPUT></TD>\n";
      echo "</TR>";
   }

   /*  */
   mysql_free_result($result);
   /*  */
   mysql_close($Connection);
?>
</TABLE>
</td></tr></table><BR>
<CENTER>
<HR>
</FORM>
</BODY>
</HTML>
