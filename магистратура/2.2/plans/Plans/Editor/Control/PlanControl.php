<?php  
   session_start();
   if (!(($_SESSION["status"] == 0)||($_SESSION["status"] == 1)||($_SESSION["status"] == 2))){
      Header ("Location: ../Unreg.html");
      exit;
   }

   include("../Display/StartPage.php");
   include("../Display/DisplayFunc.php");

   include("../PlanCalculatFunc.php");
   CreateConnection();

   DisplayPageTitle("../downcontr.html","Данные по планам","");
?>
<table  class='ramka' border="0" cellpadding="0" cellspacing="0" width="90%" align="center">
<tr><td cellpadding="0" cellspacing="0">
<TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
<TR ALIGN='CENTER' VALIGN='MIDDLE'>
<TD width=60%><strong>Название плана</strong></TD>
<TD><strong>Всего записей</strong></TD>
<TD><strong>Добавлено за день</strong></TD>
<TD><strong>Добавлено за неделю</strong></TD>
<TD><strong>Процент покрытия объема занятий</strong></TD>
</TR>
<?php  
   $send="/cgi/planFull.pl?plan=";

   $result = mysql_query("select DepName from department where CodeOfDepart=".$Depart,$Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   if ($row = mysql_fetch_object($result)) {
      $DName = $row->DepName;
   }
   echo "<TR ALIGN='LEFT' VALIGN='MIDDLE'>";
   echo "<TD width=60%><strong>&nbsp;&nbsp;&nbsp;Кафедра&nbsp;&nbsp;\"$DName\"</strong></TD><TD></TD><TD></TD><TD></TD><TD></TD></TR>";

   $q = array(
      "select plans.CodeOfPlan, plans.PlnName, directions.MinistryCode from  plans, directions where (plans.CodeOfDirect=directions.CodeOfDirect and plans.CodeOfSpecial is NULL and plans.CodeOfSpecialization is NULL) and directions.CodeOfDepart=".$Depart." and plans.Date is NULL",   
      "select plans.CodeOfPlan, plans.PlnName, specials.MinistryCode from  plans, specials where (plans.CodeOfSpecial=specials.CodeOfSpecial and plans.CodeOfSpecialization is NULL) and specials.CodeOfDepart=".$Depart." and plans.Date is NULL",   
      "select plans.CodeOfPlan, plans.PlnName, specializations.MinistryCode from  plans, specializations where plans.CodeOfSpecialization=specializations.CodeOfSpecialization and specializations.CodeOfDepart=".$Depart." and plans.Date is NULL"   
   );

while ($qw = each ($q)){
   $result = mysql_query($qw[1],$Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   //проходим по всем планам
   while($row = mysql_fetch_object($result)) {
      $plCode = $row->CodeOfPlan;
      echo "<TR ALIGN='LEFT' VALIGN='MIDDLE'>";
      echo "<TD width=60% height=40>&nbsp;&nbsp;&nbsp;"."<b>$row->MinistryCode</b>&nbsp;&nbsp;&nbsp;";
      echo "<a href=\"$send".$plCode."\" >$row->PlnName</a><br></TD>";
      $resSum = mysql_query("select SUM(CPositions) from control where CodeOfPlan=".$plCode, $Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
      if($rowSum=mysql_fetch_row($resSum)){
         $PosCount=$rowSum[0];
      }
      echo "<TD ALIGN='CENTER'>$PosCount</TD>";
      $Dt = date("Y/m/d");
      $resDt = mysql_query("select CPositions from control where CodeOfPlan=".$plCode." and CDate='".$Dt."'", $Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
      if($rowDt=mysql_fetch_object($resDt)){
         $PosCount = $rowDt->CPositions;
      }
      echo "<TD ALIGN='CENTER'>$PosCount</TD>";
      for ($i=1; $i<7; $i++){
         $Dt=date("Y/m/d",strtotime ("-$i day"));
         $resDt = mysql_query("select CPositions from control where CodeOfPlan=".$plCode." and CDate='".$Dt."'", $Connection)
            or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
         if($rowDt=mysql_fetch_object($resDt)){
            $PosCount += $rowDt->CPositions;
         }
      }
      echo "<TD ALIGN='CENTER'>$PosCount</TD>";
      $ControlTotalHours = FetchResult("select specialslimit.TotalHours from specialslimit, plans  where plans.CodeOfPlan = '$plCode' and (specialslimit.CodeOfDirect=plans.CodeOfDirect or (specialslimit.CodeOfDirect is NULL and plans.CodeOfDirect is NULL)) and (specialslimit.CodeOfSpecial=plans.CodeOfSpecial or (specialslimit.CodeOfSpecial is NULL and plans.CodeOfSpecial is NULL)) and (specialslimit.CodeOfSpecialization=plans.CodeOfSpecialization or (plans.CodeOfSpecialization is NULL and specialslimit.CodeOfSpecialization is NULL))");
//      echo "select specialslimit.TotalHours from specialslimit, plans  where plans.CodeOfPlan = '$plCode' and specialslimit.CodeOfDirect=plans.CodeOfDirect and specialslimit.CodeOfSpecial=plans.CodeOfSpecial and specialslimit.CodeOfSpecialization=plans.CodeOfSpecialization ";
      if(! empty($ControlTotalHours)){
          $Summ = TotalHours($plCode);
          $s = (($Summ*100)/$ControlTotalHours);
          $s = substr("$s",0,5);
          echo "<TD ALIGN='CENTER'>".$s."</TD>";
      }
      else {echo "<TD ALIGN='CENTER'>--</TD>";}
      echo "</TR>";
      mysql_free_result($resDt);
   }
}
?>
</TABLE>
</td></tr></table><BR>
<CENTER>
</FORM>
<?php
   include("../Display/FinishPage.php");
   mysql_close($Connection);
?>