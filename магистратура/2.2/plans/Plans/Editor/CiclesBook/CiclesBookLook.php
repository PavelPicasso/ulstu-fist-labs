<?php
   session_start();
   if (!($_SESSION["status"] == 1)){
      Header ("Location: ../Unreg.html");
   }
?>
<HTML>
<HEAD>
<TITLE>Справочник циклов</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<link rel="stylesheet" href="../../../CSS/PlansEditor.css" type="text/css">
</HEAD>
<BODY topmargin="1" leftmargin="1" marginheight="1" marginwidth="1">
<form name=fed method=post action="">
<em class='h1'><center>Справочник циклов</center></em>
<table border='0' width='100%' cellpadding='0' cellspacing='2'><tr><td height='5' bgcolor="#92a2d9"><img src="../img/line.gif" width=15 height=15 hspace=0 vspace=0 border='0' align='left'></td></tr></table>
<br>
<table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center" WIDTH='70%'>
<tr><td cellpadding="0" cellspacing="0">
<TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
<TR ALIGN='CENTER' VALIGN='MIDDLE'>
<TH WIDTH='8%'>&nbsp;</TD>
<TH WIDTH='70%'><strong>Название цикла дисциплин</strong></TH>
<TH WIDTH='22%'><strong>Сокращение</strong></TH>
</TR>

<?php
   include("../../cfg.php");
   $Connection = mysql_connect($data_source , $dbi_user , $dbi_password)
      or die("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
   mysql_select_db("plans") 
      or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");
   $result = mysql_query("select * from cicles order by CicName", $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   $i = 1;
   while ($row = mysql_fetch_object($result)) {
      $CicCode = $row->CodeOfCicle;
      $CicName = $row->CicName;
      $CicRed = $row->CicReduction;
      echo "<TR ALIGN='LEFT' VALIGN='MIDDLE'>";
      echo "<TD align='center'>$i</TD>\n";
      echo "<TD align='left'><b>&nbsp;".trim($CicName)."</b></TD>\n";
      echo "<TD align='center'><b>&nbsp;".trim($CicRed)."</b></TD>\n";
      echo "</TR>";
      $i++;
   }

   /*  */
   mysql_free_result($result);
   /*  */
   mysql_close($Connection);
//<tr><td align="center"><a href="../UnderCiclesBook/UnderCiclesBook.php" target="Body" onClick="parent.LeftDown.location='down4.html'"><img src="img/UnderCicles.gif" width=120 height=30 border=0 hspace="0" vspace="0" alt=""></a></td></tr>
?>

</TABLE>


</td></tr></table>
<BR>
</center>
<hr /></body></html>