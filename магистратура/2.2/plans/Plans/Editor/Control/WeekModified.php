<?php
   session_start();
   if (!(($_SESSION["status"] == 0)||($_SESSION["status"] == 1)||($_SESSION["status"] == 2))){
      Header ("Location: ../Unreg.html");
      exit;
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
<em class='h1'><center>Планы измененные за неделю</center></em>
<table border='0' width='100%' cellpadding='0' cellspacing='2'>
<tr><td height='5' bgcolor="#92a2d9">
<img src="../img/line.gif" width=15 height=15 hspace=0 vspace=0 border='0' align='left'></td></tr>
</table><br>
<table  class='ramka' border="0" cellpadding="0" cellspacing="0" width="90%" align="center">
<tr><td cellpadding="0" cellspacing="0">
<TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
<TR ALIGN='CENTER' VALIGN='MIDDLE'>
<TH width=60%><strong>Название плана</strong></TH>
<TH><strong>Кафедра</strong></TH>
<TH><strong>Всего записей</strong></TH>
<TH><strong>Добавлено за неделю</strong></TH>
<TH><strong>Всего часов теор. обуч.</strong></TH>
<TH><strong>Стандарт часов теор. обуч.</strong></TH>
</TR>
<?php  
   $send="/cgi/planFull.pl?plan=";
   include("../../cfg.php");
   include("../PlanCalculatFunc.php");
   $Connection = mysql_connect($data_source , $dbi_user , $dbi_password)
      or die("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
   mysql_select_db("plans") 
      or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");

   //Массив с кодами направлений 
   $result = mysql_query("select MinistryCode, CodeOfDirect, Reduction from directions, department where directions.CodeOfDepart=department.CodeOfDepart", $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   $DirectArray = array();
   while ($row = mysql_fetch_object($result)){
      $DirectArray[$row->CodeOfDirect] = array( $row->MinistryCode, $row->Reduction);
   }

   //Массив с кодами специальностей
   $result = mysql_query("select MinistryCode, CodeOfSpecial, Reduction from specials, department where specials.CodeOfDepart=department.CodeOfDepart", $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   $SpecArray = array();
   while ($row = mysql_fetch_object($result)){
      $SpecArray[$row->CodeOfSpecial] = array( $row->MinistryCode, $row->Reduction);
   }

   //Массив с кодами специализаций
   $result = mysql_query("select MinistryCode, CodeOfSpecialization, Reduction from specializations, department where specializations.CodeOfDepart=department.CodeOfDepart", $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   $SpzArray = array();
   while ($row = mysql_fetch_object($result)){
      $SpzArray[$row->CodeOfSpecialization] = array( $row->MinistryCode, $row->Reduction);
   }

   //выбираем все планы измененные за день
   $Dt = date("Y/m/d");
   $sq = "select DISTINCT plans.CodeOfPlan, plans.PlnName, plans.CodeOfDirect, plans.CodeOfSpecial, plans.CodeOfSpecialization from plans, control  where control.CodeOfPlan=plans.CodeOfPlan and control.CPositions!=0 and plans.Date is NULL and control.CDate<='".$Dt."'";
   $Dt1=date("Y/m/d",strtotime ("-7 day"));
   $sq.=" and control.CDate>='".$Dt1."'";
   $result = mysql_query($sq, $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   while ($row = mysql_fetch_object($result)) {
      $plCode = $row->CodeOfPlan;
      echo "<TR ALIGN='LEFT' VALIGN='MIDDLE'>";
      if (isset($row->CodeOfSpecialization)){
         $MinCode = $SpzArray[$row->CodeOfSpecialization][0];
         $Depart = $SpzArray[$row->CodeOfSpecialization][1];
      }else{
         if (isset($row->CodeOfSpecial)){
            $MinCode = $SpecArray[$row->CodeOfSpecial][0];
            $Depart = $SpecArray[$row->CodeOfSpecial][1];
         }else{
            if (isset($row->CodeOfDirect)){
               $MinCode = $DirectArray[$row->CodeOfDirect][0];
               $Depart = $DirectArray[$row->CodeOfDirect][1];
            }else{
               $MinCode = "------------";
               $Depart = "~~~~~~~~~~~~";
            }
         }
      }
      echo "<TH width=60% height=40>&nbsp;&nbsp;&nbsp;"."<b>".$MinCode."</b>&nbsp;&nbsp;&nbsp;";
      echo "<a href=\"$send".$plCode."\" >".$row->PlnName."</a><br></TH>";
      echo "<TH ALIGN='CENTER'>".$Depart."</TH>";
      $resSum = mysql_query("select SUM(CPositions) from control where CodeOfPlan=".$plCode, $Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
      if($rowSum=mysql_fetch_row($resSum)){
         $PosCount=$rowSum[0];
         echo "<TH ALIGN='CENTER'>$rowSum[0]</TH>";
      }
      $resSum = mysql_query("select SUM(CPositions) from control where CDate<='".$Dt."' and CDate>='".$Dt1."' and CodeOfPlan=". $plCode, $Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
      if($rowSum=mysql_fetch_row($resSum)){
         $PosCount=$rowSum[0];
         echo "<TH ALIGN='CENTER'>$rowSum[0]</TH>";
      }
      echo "<TH ALIGN='CENTER'>".TotalHours($plCode, $Connection)."</TH>";
      $resDt = mysql_query("select specialslimit.TotalHours from specialslimit, plans
               where plans.CodeOfPlan = $plCode and 
               specialslimit.CodeOfDirect=plans.CodeOfDirect and 
               specialslimit.CodeOfSpecial=plans.CodeOfSpecial and 
               specialslimit.CodeOfSpecialization=plans.CodeOfSpecialization 
               ", $Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
      if($rowDt=mysql_fetch_object($resDt)){
          echo "<TH ALIGN='CENTER'>".$rowDt->TotalHours."</TH>";
      }
      else {echo "<TH ALIGN='CENTER'>--</TH>";}
      echo "</TR>";
      mysql_free_result($resDt);
   }
   /*  */
   mysql_free_result($result);
   /*  */
   mysql_close($Connection);
?>
</TABLE>
</td></tr></table><BR>
<CENTER>
</FORM>
<HR>
</BODY></HTML>
