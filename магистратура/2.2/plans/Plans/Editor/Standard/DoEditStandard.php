<?php
	if (!($REQUEST_METHOD=='POST')) {
		Header ("Location: Standard.php");
		exit;
	}
	include("../PlanCalculatFunc.php");
	CreateConnection();

	$StData = $year."-".$month."-".$day;
	FetchQuery("UPDATE specialslimit SET 
	TotalHours='$TotalHour', TotalVolume='$TotalVolume', 
	TheorlTeach='$TheorlTeach', Exams='$Exams', Attestation='$Attestation', 
	Vacation='$Vacation', LimitFile='$LimitFile', DiplomProjection='$DiplomProjection', 
	TeachPractice='$TeachPractice', WorkPractice='$WorkPractice', DiplomPractice='$DiplomPractice', 
	HoursInW='$HoursInW', MeanHoursInW='$MeanHoursInW', KursWP='$KursWP', MeanKursWP='$MeanKursWP', 
	StDate='$StData', CodeOfPlan='$CodeOfPlan'  
	WHERE CodeOfStandard=$CodeOfStandard");

	/*Редактирование списка типовых планов*/
	FetchQuery("delete from standardplans where CodeOfStandard=$CodeOfStandard");

	/*Получаем массив с именами файлов*/
	$PlanFilesList = preg_split ("/[\s,]+/", $PlanFilesList);
	/*Дополняем базу данных именами файлов*/
	while ($PlanFile = each($PlanFilesList))
		FetchQuery("INSERT INTO standardplans (CodeOfStandard, PlanFile) VALUES ($CodeOfStandard, '$PlanFile[1]')");
	/*  */
	mysql_close($Connection);
	Header ("Location: EditStandard.php?stCode=$CodeOfStandard");
?>
