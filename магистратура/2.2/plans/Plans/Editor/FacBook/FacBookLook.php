<?php
   session_start();
   if (!(($_SESSION["status"] == 1)||($_SESSION["status"] == 2))){
      Header ("Location: ../Unreg.html");
   }
?>
<HTML>
<HEAD>
<TITLE>Справочник факультетов</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<link rel="stylesheet" href="../../../CSS/PlansEditor.css" type="text/css">
</HEAD>
<BODY topmargin="1" leftmargin="1" marginheight="1" marginwidth="1">
<form name=fed method=post action="">
<em class='h1'><center>Справочник факультетов</center></em>
<table border='0' width='100%' cellpadding='0' cellspacing='2'><tr><td height='5' bgcolor="#92a2d9"><img src="../img/line.gif" width=15 height=15 hspace=0 vspace=0 border='0' align='left'></td></tr></table>
<br>
<table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center">
<tr><td cellpadding="0" cellspacing="0">
<TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
<TR ALIGN='CENTER' VALIGN='MIDDLE'>
<TH WIDTH='5%'>&nbsp;</TD>
<TH WIDTH='45%'><strong>Название факультета</strong></TH>
<TH WIDTH='5%'><strong>Сокр ащение</strong></TH>
<TH WIDTH='30%'><strong>Декан</strong></TH>
<TH WIDTH='15%'><strong>Для подписи</strong></TH>
</TR>
<?php
   include("../../cfg.php");
   $Connection = mysql_connect($data_source , $dbi_user , $dbi_password)
      or die("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
   mysql_select_db("plans") 
      or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");
   $result = mysql_query("select * from faculty order by FacName", $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   $i = 1;
   while ($row = mysql_fetch_object($result)){
      $FacCode=$row->CodeOfFaculty;
      $Reduction=$row->Reduction;
      $Faculty=$row->FacName;
      $Dean=$row->Dean;
      $DeanPdp=$row->DeanSignature;
      echo "<TR>\n";
      echo "<TD align='center'>$i</TD>\n";
      echo "<TD align='left'><b>$Faculty</b></TD>\n";
      echo "<TD align='center'><b>$Reduction</b></TD>\n";
      echo "<TD align='left'>$Dean</TD>\n";
      echo "<TD align='left'>$DeanPdp</TD>\n";
      echo "</TR>\n";
      $i++;
   }
   mysql_free_result($result);
   mysql_close($Connection);
?>

</TABLE>


</td></tr></table><BR>
</center>
<hr></body></html>