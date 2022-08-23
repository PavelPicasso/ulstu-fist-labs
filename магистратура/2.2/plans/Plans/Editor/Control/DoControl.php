<?php  
   include("../Display/StartPage.php");
   include("../Display/DisplayFunc.php");

   include("../PlanCalculatFunc.php");
   CreateConnection();

   $result = mysql_query("SELECT CodeOfPlan from plans where Date is NULL",$Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   //проходим по всем планам
   while($row = mysql_fetch_object($result)) {
      $plCode = $row->CodeOfPlan;

      $PosCount  = FetchResult("select COUNT(schplanitems.CodeOfSchPlanItem) from schplanitems, schedplan where schedplan.CodeOfSchPlan=schplanitems.CodeOfSchPlanItem and CodeOfPlan=".$plCode);
      //вычитаем позиции, которые были введены в др. дни
      $resSum = mysql_query("select SUM(CPositions) from control where CodeOfPlan=".$plCode, $Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
      if($rowSum=mysql_fetch_row($resSum)){
         $PosCount=$PosCount-$rowSum[0];
      }
      $Dt = date("Y/m/d");
      //проверяем проводился ли учет сегодня
      $resDt = mysql_query("select CPositions from control where CodeOfPlan=".$plCode." and CDate='".$Dt."'", $Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
      //если учет уже проводился, то обновляем запись
      if($rowDt=mysql_fetch_object($resDt)){
         $PosCount = $PosCount + $rowDt->CPositions;
         mysql_query("update control set CPositions=$PosCount ".
         " where CodeOfPlan=$plCode and CDate='$Dt'",$Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
      //если нет, тодобавляем
      }else{
         mysql_query("INSERT INTO control (CodeOfPlan, CDate, CPositions)".
         " VALUES ($plCode, '$Dt', $PosCount)",$Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
      }
   }

   DisplayPageTitle("../downcontr.html","Статистика ввода учебных планов","Данные по статистике ввода планов обнавлены");
   echo "<P><HR WIDTH='100%'></P>";
   include("../Display/FinishPage.php");
   mysql_close($Connection);
?>