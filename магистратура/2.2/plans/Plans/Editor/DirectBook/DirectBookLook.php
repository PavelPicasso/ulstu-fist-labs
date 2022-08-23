<HTML>
<HEAD>
<TITLE>Справочник направлений</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<link rel="stylesheet" href="../../../CSS/PlansEditor.css" type="text/css"></HEAD>
<BODY topmargin="1" leftmargin="1" marginheight="1" marginwidth="1">
<form name=fed method=post action="">
<em class='h1'><center>Справочник направлений</center></em><table border='0' width='100%' cellpadding='0' cellspacing='2'><tr><td height='5' bgcolor="#92a2d9"><img src="../img/line.gif" width=15 height=15 hspace=0 vspace=0 border='0' align='left'></td></tr></table>
<br>
<table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center" width='85%'>
<tr><td cellpadding="0" cellspacing="0">
<TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
<TR ALIGN='CENTER' VALIGN='MIDDLE'>
<TH WIDTH=5%>&nbsp;</TD>
<TH WIDTH=10%><strong>Мин. код</strong></TH>
<TH><strong>Название направления</strong></TH>
<TH WIDTH=10%><strong>Факультет</strong></TH>
<TH WIDTH=10%><strong>Кафедра</strong></TH>
<TH WIDTH=20%><strong>Тип</strong></TH>
</TR>
<?php
   include("../../cfg.php");
   $Connection = mysql_connect($data_source , $dbi_user , $dbi_password)
      or die("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
   mysql_select_db("plans") 
      or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");
   $result = mysql_query("
         select directions.MinistryCode, directions.DirName, 
         department.Reduction, faculty.Reduction, degrees.DegreeName 
         from directions, faculty, department, degrees 
         where 
         directions.CodeOfFaculty=faculty.CodeOfFaculty and 
         department.CodeOfDepart=directions.CodeOfDepart 
         and directions.CodeOfDegree= degrees.CodeOfDegree 
         order by directions.CodeOfFaculty, 
         directions.CodeOfDepart, 
         directions.DirName", $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   $i = 1;
   while ($row = mysql_fetch_row($result)){
      $MinCode=$row[0];
      $Dir=$row[1];
      $Depart=$row[2];
      $Faculty=$row[3];
      $Type=$row[4];
      echo "<TR>\n";
      echo "<TD align='center'>$i</TD>\n";
      echo "<TD align='center'><b>$MinCode</b></TD>\n";
      echo "<TD align='left'><b>$Dir</b></TD>\n";
      echo "<TD align='center'>$Faculty</TD>\n";
      echo "<TD align='center'>$Depart</TD>\n";
      echo "<TD align='center'>$Type</TD>\n";
      echo "</TR>\n";
      $i++;
   }
   mysql_free_result($result);
   mysql_close($Connection);
?>
</TABLE>
</td></tr></table><BR>
</body>
</html>