<?php
    if (!empty($_GET['plan'])) $plan=$_GET['plan'];
    if (!empty($_POST['plan'])) $plan=$_POST['plan'];
	if (empty($plan)) {
		$message = "Plan is empty";
		include("../alert.php");
		exit;
	}

	function AddError($name, $svalue, $pvalue) {
		global $StError;
		if (!$StError) {
?>
<table  class='ramka' border="0" cellpadding="0" cellspacing="0" width="90%" align="center">
<tr><td cellpadding="0" cellspacing="0">
<TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
<TR ALIGN='CENTER' VALIGN='MIDDLE'>
<TD width=60%><strong>Ошибка</strong></TD>
<TD><strong>Данные плана</strong></TD>
<TD><strong>Требования стандарта</strong></TD>
</TR>
<?php
		}
		$StError = true;
		echo "<TR>\n";
		echo "<TD class='ErrMessage'>&nbsp;$name</TD>\n";
		echo "<TD align='center'><strong>$pvalue</strong></TD>\n";
		echo "<TD align='center'>$svalue</TD>\n";
		echo "</TR>\n";
	}


	include("../PlanCalculatFunc.php");
	CreateConnection();

	include("../Display/StartPage.php");
	include("../Display/DisplayFunc.php");

	include ("../Display/ValidationField.php");

	$PlanData = GetPlanInfo($plan);

	DisplayPageTitle("","Проверка плана на соотвествие стандарту","$PlanData[PlanSpcCode] $PlanData[PlanSpcName]");

//	$Plan = FetchFirstRow("select p.* from plans p  where p.CodeOfPlan='$plan'");
	$Standard = FetchFirstRow("select s.* from specialslimit s, plans p  where s.CodeOfDirect=p.CodeOfDirect and s.CodeOfSpecial=p.CodeOfSpecial and  s.CodeOfSpecialization=p.CodeOfSpecialization and p.CodeOfPlan='$plan'");
	if (empty($Standard)) {
		echo "<H3>Не найдено стандартов соответствующих выбранному учебному плану</H3>";
		include("../Display/FinishPage.php");
		exit;
	}

	list ($FirstKurs, $LastKurs, $FirstTerm, $LastTerm) = GetPeriod($plan, "Y");
	$TotalHours = TotalHours($plan);
	$TWKinds = TWeekKinds($plan);
	$TSem = CalculateTSem($plan,$FirstTerm,$LastTerm);
	$PlanDiscips = PlanDiscips($plan,$TSem);
	$standard = $Standard["CodeOfStandard"];
	
	$StError = false;
//	$AudinW = ceil($TotalHours/$TWKinds["to"]/2);
	$MeanAudInW = ceil($TotalHours/$TWKinds["to"]/2);
	foreach ($PlanDiscips["Cicles"] as $k => $v) {
			$StAllH = FetchResult("SELECT CicleHours FROM `LimitCicles` WHERE CodeOfCicle='$v[CodeOfCicle]' and CodeOfStandard='$Standard[CodeOfStandard]'");
		if (!empty($StAllH) && $StAllH!=$v["AllH"] )
			AddError("Общее количество часов обучения за цикл $v[CicName]",$StAllH,$v["AllH"]);
	}

	$Groups = FetchArrays("SELECT * FROM LimitGroups WHERE CodeOfStandard='$standard'");
	if (!empty($Groups)) {
		foreach ($Groups as $kg => $vg ) {
			$GrDisciplins =FetchArrays("Select d.CodeOfDiscipline, d.DisName from disciplins d, LimitDis l where l.CodeOfGroup='$vg[CodeOfGroup]' and d.CodeOfDiscipline=l.CodeOfDiscipline");
			if (!empty($GrDisciplins)) {
				$DisCodes = "";
				$DisNames = "";
				foreach ($GrDisciplins as $kd => $vd) {
					if (!empty($DisCodes)) {
						$DisCodes .= ",";
						$DisNames .= "<BR>";
					}
					$DisCodes .= $vd["CodeOfDiscipline"];
					$DisNames .= $vd["DisName"];
				}
				$GrSum = FetchResult("SELECT SUM(AllH) from schedplan where CodeOfDiscipline IN ($DisCodes) and CodeOfPlan='$plan'");
				if ($GrSum != $vg["GroupHours"])
					AddError("Общее количество часов обучения для дисциплин<BR> $DisNames",$vg["GroupHours"],$GrSum);
				
			}
		}

	}
	$StDis = FetchArrays("SELECT l.*, d.DisName FROM LimitDis l,  disciplins d WHERE CodeOfStandard='$standard' and d.CodeOfDiscipline=l.CodeOfDiscipline");
	if (is_array($StDis)) {
		foreach ($StDis as $ks=>$vs) {
			if (! empty($vs["DisHours"])) {
				$DisHours = FetchResult("SELECT AllH FROM schedplan WHERE CodeOfDiscipline='$vs[CodeOfDiscipline]' and CodeOfPlan='$plan'");
				if ($DisHours != $vs["DisHours"])
					AddError("Общее количество часов обучения для дисциплины<BR> $vs[DisName]",$vs["DisHours"],$DisHours);
			}
		}
	}
	
	if ($Standard["TotalHours"] != $TotalHours) 
		AddError("Общее количество часов обучения",$Standard["TotalHours"],$TotalHours);

	if ($Standard["TotalVolume"] != $TWKinds["all"]) 
		AddError("Общий объем занятий ",$Standard["TotalVolume"],$TWKinds["all"]);

	if ($Standard["TheorlTeach"] != $TWKinds["to"]) 
		AddError("Теоретическое обучение",$Standard["TheorlTeach"],$TWKinds["to"]);

	if ($Standard["Exams"] != $TWKinds["ex"]) 
		AddError("Экзаменационная сессия ",$Standard["Exams"],$TWKinds["ex"]);

	if ($Standard["Attestation"] != $TWKinds["ia"]) 
		AddError("Итоговая аттестация ",$Standard["Attestation"],$TWKinds["ia"]);

	if ($Standard["Vacation"] != $TWKinds["hl"]) 
		AddError("Каникулы ",$Standard["Vacation"],$TWKinds["hl"]);

	if ($Standard["DiplomProjection"] != $TWKinds["dp"]) 
		AddError("Дипломное проектирование",$Standard["DiplomProjection"],$TWKinds["dp"]);

	if ($Standard["TeachPractice"] != $TWKinds["up"]) 
		AddError("Учебная практика",$Standard["TeachPractice"],$TWKinds["up"]);

	if ($Standard["WorkPractice"] != $TWKinds["ptp"]) 
		AddError("Производственно-технологическая практика",$Standard["WorkPractice"],$TWKinds["ptp"]);

	if ($Standard["DiplomPractice"] != $TWKinds["pp"]) 
		AddError("Преддипломная практика",$Standard["DiplomPractice"],$TWKinds["pp"]);

/*	if ($Standard["HoursInW"] != $AudinW) 
		AddError("Объем аудиторных занеятий в неделю",$Standard["HoursInW"],$AudinW);*/

	if ($Standard["MeanHoursInW"] != $MeanAudInW) 
		AddError("Средненедельное число аудиторных работ",$Standard["MeanHoursInW"],$MeanAudInW);

	//Контроль за каникулами
	for ($i = $FirstTerm; $i<=$LastTerm; $i++) {
		$THl = TeachWeek($plan, $i,1);
		$STHl =2;
		if ($i%2 == 0)
			$STHl =10;
		if ($STHl > $THl)
			AddError("Число каникул в семестре $i",$STHl,$THl);
			
	}


	if (!$StError)
		echo "<H3>Учебный план полностью соответствует стандарту</H3>\n";
	else 
		echo "</TABLE></td></tr></table><BR>\n";

	echo "<HR>\n";
	include("../Display/FinishPage.php");
?>