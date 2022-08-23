<?php
   session_start();
   if (!(($_SESSION["status"] == 1)||($_SESSION["status"] == 2))){
      Header ("Location: ../Unreg.html");
   }
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
<form name=UndCFrm method=post action="">
<em class='h1'><center>Справочник наименований квалификации выпускника</center></em>
<table border='0' width='100%' cellpadding='0' cellspacing='2'>
<tr><td height='5' bgcolor="#92a2d9">
<img src="../img/line.gif" width=15 height=15 hspace=0 vspace=0 border='0' align='left'></td></tr>
</table><br>
<table  class='ramka' border="0" cellpadding="0" cellspacing="0"  align="center" WIDTH='50%'>
<tr><td cellpadding="0" cellspacing="0">
<TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
<TR ALIGN='CENTER' VALIGN='MIDDLE'>
<TH WIDTH ='10%'>&nbsp;</TD>
<TH WIDTH ='30%'><strong>Название степени</strong></TH>
<TH WIDTH ='18%'><strong>Срок обучения<BR>(по умолчанию)</strong></TH>
<TH WIDTH ='18%'><strong>Начальный курс<BR>(по умолчанию)</strong></TH>
<TH WIDTH ='14%'><strong>Порядок</strong></TH>
</TR>
<?php
   include("../../cfg.php");
   $Connection = mysql_connect($data_source , $dbi_user , $dbi_password)
      or die("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
   mysql_select_db("plans") 
      or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");
   $result = mysql_query("select * from degrees order by Sequence, DegreeName", $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   $i = 1;
   while ($row = mysql_fetch_object($result)) {
      $DegreeName = $row->DegreeName;
      $Apprenticeship = $row->Apprenticeship;
      $FirstYear = $row->FirstYear;
      $Sequence = $row->Sequence;
      echo "<TR ALIGN='LEFT' VALIGN='MIDDLE'>";
      echo "<TD align='center'>$i</TD>\n";
      echo "<TD align='left'><b>&nbsp;".trim($DegreeName)."</b></TD>\n";
      echo "<TD align='left'><b>&nbsp;$Apprenticeship</b></TD>\n";
      echo "<TD align='left'><b>&nbsp;$FirstYear</b></TD>\n";
      echo "<TD align='left'><b>&nbsp;$Sequence</b></TD>\n";
      echo "</TR>";
      $i++;
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
