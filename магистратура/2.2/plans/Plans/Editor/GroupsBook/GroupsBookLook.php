<?php
   session_start();
   if (!($_SESSION["status"] == 2)){
      Header ("Location: ../Unreg.html");
   }
?>
<HTML>
<HEAD>
<TITLE>Справочник потоков</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<link rel="stylesheet" href="../../../CSS/PlansEditor.css" type="text/css">
</HEAD>
<BODY topmargin="1" leftmargin="1" marginheight="1" marginwidth="1">
<form name=fed method=post action="">
<em class='h1'><center>Справочник потоков</center></em>
<table border='0' width='100%' cellpadding='0' cellspacing='2'><tr>
<td height='5' bgcolor="#92a2d9"><img src="../img/line.gif" width=15 height=15 hspace=0 vspace=0 border='0' align='left'></td></tr></table>
<br>
<table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center" WIDTH='80%'>
<tr><td cellpadding="0" cellspacing="0">
<TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
<TR ALIGN='CENTER' VALIGN='MIDDLE'>
<TH WIDTH='5%'>&nbsp;</TH>
<TH><strong>Название потока</strong></TH>
<TH WIDTH='5%'><strong>Курс</strong></TH>
<TH WIDTH='10%'><strong>Кол-во групп</strong></TH>
<TH WIDTH='5%'><strong>Кол-во студентов</strong></TH>
<TH><strong>Учебный план</strong></TH>
</TR>
<?php
   include("../PlanCalculatFunc.php");
   CreateConnection();

   $q[] = "select CodeOfStream, StreamName, Kurs, StdCount, GroupCount, PlnName, YearCount, MinistryCode from streams, directions, plans where streams.CodeOfPlan=plans.CodeOfPlan and plans.CodeOfDirect=directions.CodeOfDirect and plans.CodeOfSpecialization is NULL and plans.CodeOfSpecial is NULL order by MinistryCode, StreamName, Kurs";
   $q[] = "select CodeOfStream, StreamName, Kurs, StdCount, GroupCount, PlnName, YearCount, MinistryCode from streams, specials, plans where streams.CodeOfPlan=plans.CodeOfPlan and plans.CodeOfSpecial=specials.CodeOfSpecial and plans.CodeOfSpecialization is NULL order by MinistryCode, StreamName, Kurs";
   $q[] = "select CodeOfStream, StreamName, Kurs, StdCount, GroupCount, PlnName, YearCount, MinistryCode from streams, specializations, plans where streams.CodeOfPlan=plans.CodeOfPlan and plans.CodeOfSpecialization=specializations.CodeOfSpecialization order by MinistryCode, StreamName, Kurs";

   $i = 1;
   while ($qw = each ($q)){
      $result = mysql_query($qw[1], $Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
      while ($row = mysql_fetch_object($result)){
         $StreamCode = $row->CodeOfStream;
         $Group = $row->StreamName;
         $Kurs = $row->Kurs;
         $GrpCount = $row->GroupCount;
         $StdCount = $row->StdCount;
         $PlanName = $row->PlnName;
         $KursNumb=$row->YearCount;
         $MinistryCode = $row->MinistryCode;
         echo "<TR>\n";
         echo "<TD align='center'>".$i."</TD>\n";
         echo "<TD align='left'><b>&nbsp;".trim($Group)."</b></TD>\n";
         echo "<TD align='center'>$Kurs</TD>\n";
         echo "<TD align='center'>$GrpCount</TD>\n";
         echo "<TD align='center'>$StdCount</TD>\n";
         echo "<TD align='left'><b>&nbsp;$MinistryCode&nbsp;&nbsp;&nbsp;$PlanName</b></TD>\n";
         echo "</TR>\n";
         $i++;
      }
   }
   mysql_free_result($result);
   mysql_close($Connection);
?>
</TABLE>
</td></tr></table><BR>
</center>
<hr>
</body>
</html>