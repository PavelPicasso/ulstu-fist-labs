<?php
   if (!($REQUEST_METHOD=='POST' && $_POST['action'] && (strcmp ($_POST['action'],"NewPlan")==0))){ 
      Header ("Location: ChoiseP.php");
   }

   //Добавление нового учебного плана
   $PName = $_POST['PlanName'];
   $DirectCode = $_POST['Dir'];
   $SpecCode = $_POST['Spec'];
   $SpzCode = $_POST['Spz'];
   $day = $_POST['day'];
   $mon = $_POST['mon'];
   $year = $_POST['year'];
   $NumOfChangeKurs = $_POST['NumOfChangeKurs'];
   $NumOfChangePer = $_POST['NumOfChangePer'];
   $yCnt = $_POST['yCnt'];
   $TchForm = $_POST['TchForm'];
   $FirstSm  = $_POST['FirstSm'];

   $dt=$day."/".$mon."/".$year;

   include("../../cfg.php");
   $Connection = mysql_connect($data_source , $dbi_user , $dbi_password)
      or die("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
   mysql_select_db("plans") 
      or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");

   $result = mysql_query("SELECT MAX(CodeOfPlan) from plans", $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   $row = mysql_fetch_row($result);
   $MaxCode = $row[0];

   $result = mysql_query("SELECT MAX(CodeOfPlan) from control", $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   $row = mysql_fetch_row($result);
   $MaxCCode = $row[0];
   if ($MaxCCode > $MaxCode){$MaxCode=$MaxCCode;}


   //***************************
   // Создаем  новую запись в таблице plans
   $MaxCode++;
   if ((strcmp($day,"")==0) || (strcmp($month,"")==0) || (strcmp($year,"")==0)){
      $q = "INSERT INTO plans (CodeOfPlan, CodeOfDirect, CodeOfSpecial, CodeOfSpecialization, PlnName, YearCount, TeachForm, Date) values ('$MaxCode', '$DirectCode', '$SpecCode', '$SpzCode', '$PName', '$yCnt', '$TchForm', null) ";
   }
   else{
      $q = "INSERT INTO plans (CodeOfPlan, CodeOfDirect, CodeOfSpecial, CodeOfSpecialization, PlnName, YearCount, TeachForm, Date) values ('$MaxCode', '$DirectCode', '$SpecCode', '$SpzCode', '$PName', '$yCnt', '$TchForm', '$dt') "; 
   }
   mysql_query($q, $Connection)
   or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   //********************************************************
   //			Создаем новый график  УП

   for($ii=$FirstSm ;$ii<=$yCnt+$FirstSm-1;$ii++){
      $querry="insert into schedules ( CodeOfPlan, KursNumb) values ($MaxCode,$ii) ";
      mysql_query($querry, $Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   }	 
   $numKurs=split(',', $NumOfChangeKurs);        
   $numPer=split(',', $NumOfChangePer);
   reset ($numKurs);
   reset ($numPer);
   while (($KursNum=each($numKurs)) && ($PerNum=each($numPer)) && is_numeric($KursNum[1])){
      $PerStr="Period".$PerNum[1]."_".$KursNum[1];
      $period = $_POST["$PerStr"];
      $LenPerStr="LengthOfPeriod".$PerNum[1]."_".$KursNum[1];
      $length = 0;
      if ($_POST["$LenPerStr"]){$length = $_POST["$LenPerStr"];}
      mysql_query("update schedules set Period".$PerNum[1]."='$period', LengthOfPeriod".$PerNum[1]."=$length where KursNumb=".$KursNum[1]." and CodeOfPlan=$MaxCode", $Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   }                      
   Header ("Location: ChoiseP.php");
?>