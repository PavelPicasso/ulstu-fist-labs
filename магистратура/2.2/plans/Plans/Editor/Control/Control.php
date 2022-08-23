<?php
   session_start();
   if (!(($_SESSION["status"] == 0)||($_SESSION["status"] == 1)||($_SESSION["status"] == 2))){
      Header ("Location: ../Unreg.html");
      exit;
   }
   if ($_SESSION["status"] == 2){
      Header ("Location: PlanControl.php?Depart=".$_SESSION["statusCode"]."");
      exit;
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<HTML>
<HEAD>
<TITLE>Учебные планы</TITLE>
<META NAME=Author CONTENT="Карпова Анна">
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<LINK rel=stylesheet href="../../../CSS/Plans.css" type=text/css>
</HEAD>
<BODY topmargin=1 leftmargin=5 marginheight="1" marginwidth="5">
<FORM NAME=CHOISE METHOD=POST ACTION="PlanControl.php">
<CENTER>
<em class='h1'>
<center>Формирование отчета изменений планов кафедры</center></em>
<table border='0' width='100%' cellpadding='0' cellspacing='2'>
<tr><td height='5' bgcolor="#92a2d9">
<img src="../img/line.gif" width=15 height=15 hspace=0 vspace=0 border='0' align='left'>
</td></tr></table>
<h2>Выберите кафедру планы которой вы хотите посмотреть</h2>
<TABLE border=0 align='center' width=97%>
<TR>
<TD align='center' width=15%><strong></strong></TD>
<TD width=25%><strong>Сокращение</strong></TD>
<TD width=60%><strong>Название Кафедры</strong></TD>
</TR>
<?php  
   include("../../cfg.php");
   $Connection = mysql_connect($data_source , $dbi_user , $dbi_password)
      or die("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
   mysql_select_db("plans") 
      or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");
   $q = "select department.CodeOfDepart, department.Reduction, department.DepName from department";
   if ($_SESSION["status"] == 1){ $q .= " where department.CodeOfFaculty =".$_SESSION["statusCode"];}
   $q  .= " order by department.DepName";
   $result = mysql_query($q,$Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   if($row = mysql_fetch_object($result)) {
         echo "<TR>";
         echo "<TD align='center'  valign='top' width=15%>";
         echo "<INPUT TYPE='RADIO' NAME='Depart' VALUE='$row->CodeOfDepart' CHECKED></INPUT></TD>";
         echo "<TD  valign='top' width=25%><b>$row->Reduction</b></TD>";
         echo "<TD  valign='top' width=60%>$row->DepName</TD>";
         echo "</TR>";
   }
   while($row = mysql_fetch_object($result)) {
         echo "<TR>";
         echo "<TD align='center'  valign='top' width=15%>";
         echo "<INPUT TYPE='RADIO' NAME='Depart' VALUE='$row->CodeOfDepart'></INPUT></TD>";
         echo "<TD  valign='top' width=25%><b>$row->Reduction</b></TD>";
         echo "<TD  valign='top' width=60%>$row->DepName</TD>";
         echo "</TR>";
   }
   /*  */
   mysql_free_result($result);
   /*  */
   mysql_close($Connection);
?>
</TABLE><BR>
<center><TABLE BORDER=0 ALIGN=CENTER cellspacing="2">
<TR><TD WIDTH=300  colspan="2" align='center'>
<input type=submit value='Посмотреть статистику кафедры'">
</TD></TR>
</TABLE>
</FORM>
<HR>
</BODY>
</HTML>