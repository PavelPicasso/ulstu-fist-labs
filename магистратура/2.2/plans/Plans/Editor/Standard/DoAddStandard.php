<?php
	if (!($REQUEST_METHOD=='POST')) {
	  Header ("Location: Standard.php");
	  exit;
	}
	$alert="../alert.html";

	include("../Display/DisplayFunc.php");
	include("../PlanCalculatFunc.php");
	CreateConnection();

	$StData = $year."-".$month."-".$day;

	$q = "SELECT * from specialslimit where";
	if (empty($Dir)){$q .= " (CodeOfDirect is NULL  or CodeOfDirect='0')";}
	else{$q .= " CodeOfDirect='$Dir'";}
	if (empty($Spec)){$q .= " and (CodeOfSpecial is NULL or CodeOfSpecial='0')";}
	else{$q .= " and CodeOfSpecial='$Spec'";}
	if (empty($Spz)){$q .= " and (CodeOfSpecialization is NULL or CodeOfDirect='0') and StDate='$StData'";}
	else{$q .= " and CodeOfSpecialization='$Spz' and StDate='$StData'";}
	$result = mysql_query($q, $Connection)
		or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
	if (!$res=mysql_fetch_row($result)){
		$i = FetchResult("select MAX(CodeOfStandard) from specialslimit", $Connection)
			or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
		if ($i)
		  $i+=1;
		else
		  $i=1; 
		FetchQuery("INSERT INTO specialslimit (CodeOfStandard, CodeOfDirect, CodeOfSpecial, CodeOfSpecialization, TotalHours, TotalVolume, TheorlTeach, Exams, Attestation, Vacation, LimitFile, DiplomProjection, TeachPractice, WorkPractice, DiplomPractice, HoursInW, MeanHoursInW, KursWP, MeanKursWP, StDate, CodeOfPlan) VALUES ('$i', '$Dir', '$Spec', '$Spz', '$TotalHour', '$TotalVolume', '$TheorlTeach', '$Exams', '$Attestation', '$Vacation', '$LimitFile', '$DiplomProjection', '$TeachPractice', '$WorkPractice', '$DiplomPractice', '$HoursInW', '$MeanHoursInW', '$KursWP', '$MeanKursWP', '$StData', '$CodeOfPlan') ");

		$PlanFilesList = preg_split ("/[\s,]+/", $PlanFilesList);
		/*Дополняем базу данных именами файлов*/
		while ($PlanFile = each($PlanFilesList)){
			FetchQuery("INSERT INTO standardplans (CodeOfStandard, PlanFile) VALUES ($i, '$PlanFile[1]')");
		}
		/*  */
		mysql_free_result($result);
		/*  */
		mysql_close($Connection);
		if ($shift==0){Header ("Location: Standard.php");}  /* Редирект браузера на сайт PHP */
		if ($shift==1){Header ("Location: ChoiseDirect.php?shift=1");}
  }
  else{
	FuncAlert("База данных уже содержит данную запись.");
  }
?>
