<?php
   session_start();
   if (!(($_SESSION["status"] == 1)||($_SESSION["status"] == 2))){
      Header ("Location: ../Unreg.html");
      exit;
   }
?>
<HTML>
<HEAD>
<TITLE>Справочник кафедр</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<link rel="stylesheet" href="../../../CSS/PlansEditor.css" type="text/css">
</HEAD>
<BODY topmargin="1" leftmargin="1" marginheight="1" marginwidth="1">
<form name=fed method=post action="">
<em class='h1'><center>Справочник кафедр</center></em><table border='0' width='100%' cellpadding='0' cellspacing='2'><tr><td height='5' bgcolor="#92a2d9"><img src="../img/line.gif" width=15 height=15 hspace=0 vspace=0 border='0' align='left'></td></tr></table>
<br>
<table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center" WIDTH='95%'>
<tr><td cellpadding="0" cellspacing="0">
<TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
<TR ALIGN='CENTER' VALIGN='MIDDLE'>
<TH width = 5%>&nbsp;</TD>
<TH width = 40%><strong>Название кафедры</strong></TH>
<TH width = 8%><strong>Сокр ащение</strong></TH>
<TH width = 7%><strong>Факуль тет</strong></TH>
<TH width = 20%><strong>Зав. кафедрой</strong></TH>
<TH width = 14%><strong>Для подписи</strong></TH>
<TH width = 6%><strong>URL</strong></TH>
</TR>
<?php
   include("../../cfg.php");
   $Connection = mysql_connect($data_source , $dbi_user , $dbi_password)
      or die("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
   mysql_select_db("plans") 
      or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");

   $result = mysql_query("select department.CodeOfDepart, department.DepName, 
      department.Reduction, department.Addres, 
      department.ZavDepart, department.ZavSignature,
      department.URL, faculty.Reduction
      from department, faculty where faculty.CodeOfFaculty=department.CodeOfFaculty order by DepName", $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   $i = 1;
   while ($row = mysql_fetch_row($result)){
      $DepartCode = $row[0];
      $Depart = $row[1];
      $Reduction = $row[2];
      $Adress = $row[3];
      $ZavDep = $row[4];
      $ZavPdp = $row[5];
      $URL = $row[6];
      $FcCode = $row[7];
      echo "<TR>\n";
      echo "<TD align='center'>$i</TD>\n";
      echo "<TD align='left'><b>$Depart</b></TD>\n";
      echo "<TD align='center'><b>$Reduction</b></TD>\n";
      echo "<td align='center'><b>$FcCode<b></TD>\n";
      echo "<TD align='left'>$ZavDep</TD>\n";
      echo "<TD align='left'>$ZavPdp</TD>\n";
      echo "<TD align='center'><b>$URL</b></TD>\n";
      echo "</TR>\n";
      $i++;
   }
   mysql_free_result($result);
   mysql_close($Connection);
?>
</TABLE>
</td></tr></table><BR>
<hr />
</body>
</html>