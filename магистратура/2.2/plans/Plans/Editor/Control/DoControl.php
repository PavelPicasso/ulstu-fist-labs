<?php  
   include("../Display/StartPage.php");
   include("../Display/DisplayFunc.php");

   include("../PlanCalculatFunc.php");
   CreateConnection();

   $result = mysql_query("SELECT CodeOfPlan from plans where Date is NULL",$Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   //�������� �� ���� ������
   while($row = mysql_fetch_object($result)) {
      $plCode = $row->CodeOfPlan;

      $PosCount  = FetchResult("select COUNT(schplanitems.CodeOfSchPlanItem) from schplanitems, schedplan where schedplan.CodeOfSchPlan=schplanitems.CodeOfSchPlanItem and CodeOfPlan=".$plCode);
      //�������� �������, ������� ���� ������� � ��. ���
      $resSum = mysql_query("select SUM(CPositions) from control where CodeOfPlan=".$plCode, $Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
      if($rowSum=mysql_fetch_row($resSum)){
         $PosCount=$PosCount-$rowSum[0];
      }
      $Dt = date("Y/m/d");
      //��������� ���������� �� ���� �������
      $resDt = mysql_query("select CPositions from control where CodeOfPlan=".$plCode." and CDate='".$Dt."'", $Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
      //���� ���� ��� ����������, �� ��������� ������
      if($rowDt=mysql_fetch_object($resDt)){
         $PosCount = $PosCount + $rowDt->CPositions;
         mysql_query("update control set CPositions=$PosCount ".
         " where CodeOfPlan=$plCode and CDate='$Dt'",$Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
      //���� ���, �����������
      }else{
         mysql_query("INSERT INTO control (CodeOfPlan, CDate, CPositions)".
         " VALUES ($plCode, '$Dt', $PosCount)",$Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
      }
   }

   DisplayPageTitle("../downcontr.html","���������� ����� ������� ������","������ �� ���������� ����� ������ ���������");
   echo "<P><HR WIDTH='100%'></P>";
   include("../Display/FinishPage.php");
   mysql_close($Connection);
?>