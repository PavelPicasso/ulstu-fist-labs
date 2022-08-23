<?php
   //if (!($REQUEST_METHOD=='GET' && $_GET['depart'])) {
     //Header ("Location: ../../../cgi/Editor/DepartBook/DepartBook.pl");
     //exit;
   //}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<HTML>
<HEAD>
<TITLE>Учебные планы</TITLE>
<META NAME=Author CONTENT="Карпова Анна">
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<link rel="stylesheet" href="../../../CSS/PlansEditor.css" type="text/css">
</HEAD>
<BODY topmargin=1 leftmargin=5 marginheight="1" marginwidth="5">
<form name=streams method=post action="">
<em class='h1'><center>Отчет о дисциплинах закрепленных за кафедрой</center></em><table border='0' width='100%' cellpadding='0' cellspacing='2'><tr><td height='5' bgcolor="#92a2d9"><img src="../img/line.gif" width=15 height=15 hspace=0 vspace=0 border='0' align='left'></td></tr></table>
<h2>Выберите учитываемые потоки</h2>
<br><table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center" WIDTH='40%'>
<tr><td cellpadding="0" cellspacing="0">
<TABLE class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
<TR>
<TD align='center' width=10%><strong>&nbsp;</strong></TD>
<TD align='center' width=60%><strong>Название потока</strong></TD>
<TD align='center' width=20%><strong>Курс</strong></TD>
</TR>
<?php
    include("../PlanCalculatFunc.php");
    CreateConnection();
   /*  */

   $DepCode=$_GET['depart'];
   $result = mysql_query("select * from department  where CodeOfDepart=$DepCode",$Connection)
       or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   $res=mysql_fetch_object($result);
   $DepRed=$res->Reduction;
   $DepName=$res->DepName;
   $DepSign=$res->ZavSignature;
   $result = mysql_query("
      select distinct  streams.StreamName, streams.CodeOfStream, streams.Kurs  
      from streams,  schplanitems, schedplan 
      where 
      streams.CodeOfPlan=schedplan.CodeOfPlan and 
      schedplan.CodeOfSchPlan = schplanitems.CodeOfSchPlan  and 
      (schplanitems.NumbOfSemestr=streams.Kurs*2 or schplanitems.NumbOfSemestr=streams.Kurs*2-1) and
      schplanitems.CodeOfDepart=$DepCode
      order by streams.StreamName, streams.Kurs
      ",$Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");

   while ($row = mysql_fetch_object($result)){
      $StreamCode = $row->CodeOfStream;         //код плана
      $StrName = $row->StreamName;            //название факультета
      $StrKurs = $row->Kurs;            //название факультета
      echo "<TR>";
      echo "<TD width=11% align='center' valign='top'><INPUT TYPE='CHECKBOX' NAME='flag[]' VALUE=\"$StreamCode\"CHECKED ></TD>";
      echo "<TD width=50% align='center' valign='top'><b>$StrName</b></TD>";
      echo "<TD width=50% align='center' valign='top'><b>$StrKurs</b></TD>";
      echo "</TR>";
   }
   echo "<input type='hidden' name='depart' value=$DepCode>";
   mysql_free_result($result);
   mysql_close($Connection);

?>
</TABLE>
</td></tr></table><BR>
<TABLE BORDER=0 ALIGN=CENTER>
<TR>
<TD COLSPAN=2><CENTER><INPUT TYPE='SUBMIT' NAME='Export' VALUE='Перечень дисциплин, закрепленных на отмеченных потоках' 
            onClick="streams.action='../DepartBook/DiscipDepartExp.php'"></INPUT></CENTER></TD>
</TR>
<TR>
<TD COLSPAN=2><CENTER><INPUT TYPE='SUBMIT' NAME='Vlm' VALUE='Объемы учебной нагрузки кафедры' 
            onClick="streams.action='../Staff/DiscipVolume.php'"></INPUT></CENTER></TD>
</TR>

</TABLE>
<HR>
</FORM>
</BODY>
</HTML>
