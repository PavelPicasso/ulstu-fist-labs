<?php 
   include("../../cfg.php");
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
<em class='h1'><center>Получение отчетов о дисциплинах закрепленных за кафедрами</center></em>
<table border='0' width='100%' cellpadding='0' cellspacing='2'>
<tr><td height='5' bgcolor="#92a2d9">
<img src="../img/line.gif" width=15 height=15 hspace=0 vspace=0 border='0' align='left'>
</td></tr>
</table>
<H2>Выберите специальность и семестр:</H2>
<form name=Info method=post action="">
<TABLE border=0 align='center' width=70%>
<?php
/*<TR>
<TD></TD>
<TD align='center' width=15%><strong>Код</strong></TD>
<TD align='left' width=50%><strong>Название</strong></TD>
</TR>*/
   $Connection = mysql_connect($data_source , $dbi_user , $dbi_password)
      or die("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
   mysql_select_db("plans") 
      or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");
   //Все специальности, участвующие в учебных планах и потоках
   $q[] = array("direction", "select distinct directions.CodeOfDirect, directions.MinistryCode, directions.CodeOfDepart, directions.DirName ". 
         "from directions left join plans on directions.CodeOfDirect=plans.CodeOfDirect left join streams on streams.CodeOfPlan=plans.CodeOfPlan ".
         "where (plans.CodeOfSpecial is NULL or plans.CodeOfSpecial=0) ".
         "and (plans.CodeOfSpecialization is NULL or plans.CodeOfSpecialization=0) and (plans.DateArchive is NULL or plans.DateArchive='0000-00-00') ".
         "order by directions.MinistryCode ");

   //Все специальности, участвующие в учебных планах и потоках
   $q[] = array("special", "select distinct specials.CodeOfSpecial, specials.MinistryCode, specials.CodeOfDepart, specials.SpcName ".
         "from specials left join plans on specials.CodeOfSpecial=plans.CodeOfSpecial left join streams on streams.CodeOfPlan=plans.CodeOfPlan ".
         "where (plans.CodeOfSpecialization is NULL or plans.CodeOfSpecialization=0) and (plans.DateArchive is NULL or plans.DateArchive='0000-00-00') ".
         "order by specials.MinistryCode ");

   $q[] = array("specialization", "select distinct specializations.CodeOfSpecialization, specializations.MinistryCode, specializations.CodeOfDepart, specializations.SpzName ".
         "from specializations left join plans on specializations.CodeOfSpecialization=plans.CodeOfSpecialization left join streams on streams.CodeOfPlan=plans.CodeOfPlan ".
         "where specializations.CodeOfSpecialization=plans.CodeOfSpecialization ".
         "and streams.CodeOfPlan=plans.CodeOfPlan and (plans.DateArchive is NULL or plans.DateArchive='0000-00-00') ".
         "order by specializations.MinistryCode"); 
   //Все специализации, участвующие в учебных планах и потоках
   while ($qw = each($q)){
      $result=mysql_query($qw[1][1],$Connection) 
         or die("Query failed:".mysql_errno().": ".mysql_error()."<BR>");
      while($row=mysql_fetch_row($result)){
         echo  "<TR>";
         echo  "<TD align='center'  valign='top'><INPUT TYPE='RADIO' NAME='spc' VALUE='".$qw[1][0].",".$row[0]."' CHECKED></INPUT></TD>";
         echo  "<TD align='center' valign='top' width=20%><b>".$row[1]."</b></TD>";
         echo  "<TD  valign='top' width=80%>".$row[3]."</TD>";
         echo  "</TR>";
      }
   }
   mysql_free_result($result);
   mysql_close($Connection);
?>
</TABLE>
<P>
<TABLE BORDER=0 ALIGN=CENTER cellspacing="2">
<TR>
<TD WIDTH=300 align='center'><INPUT TYPE='RADIO' NAME='semestr' VALUE='1' CHECKED></INPUT>1-ый семестр </TD>
<TD WIDTH=300 align='center'><INPUT TYPE='RADIO' NAME='semestr' VALUE='2'></INPUT>2-ой семестр </TD>
</TR>
<TR><TD WIDTH=600  colspan="2" align='center'></TD></TR>
</TABLE>
<P>
<CENTER>
<input type=submit value='Создать документ' onClick="Info.action='InfoCard.php'">
</FORM>
<HR>
</BODY>
</HTML>
