<?php
include("../PlanCalculatFunc.php");
CreateConnection();
include("../Display/DisplayFunc.php");
//$sql = "select distinct(CodeOfPlan) from shedplan sh left join plans p on p.CodeOfPlan=sh.CodeOfPlan where sh.CodeOfDepart="
$flag=array();
if (empty($_GET['depart'])) 
	FuncAlert("Невыбрана кафедра или потоки","../DepartBook/ChooseDepart.php");
$sql = "select distinct(p.CodeOfPlan) as cp from schedplan as sh left join plans as p on p.CodeOfPlan=sh.CodeOfPlan where sh.CodeOfDepart=".$_GET['depart']." and (DateArchive is NULL or DateArchive='0000-00-00')";
$res=FetchArrays($sql);
$AddQ = "";
while ( $PlnCode = array_shift($res)){
	if (strcmp($AddQ,"")==0){$AddQ = "streams.CodeOfPlan in ('".$PlnCode['cp']."' ";}
	else {$AddQ .= ",'".$PlnCode['cp']."'" ;}
}
if (strcmp($AddQ,"")!=0){ $AddQ .= ") ";}
$sql = "select distinct(CodeOfStream) from streams where ".$AddQ;
$flag = FetchArrays($sql);
include("../Display/StartPage.php");
DisplayPageTitle("../down1.html","Объемы учебной нагрузки кафедры","Файл отчета");

//Подключение библиотеки функций
$lib_file = "../../../lib/reportlib.php";
$local_variables = "../../../lib/locallib.php";
$global_variables = "../../../lib/globallib.php";
include($lib_file);


$DepFlag = $flag;

include ("ExpVlmFunc.php");
$start = "VlmRTFParts/StartPage.rtf";
$colonEnd = "VlmRTFParts/ColonTitulEnd.rtf";
$startTbl = "VlmRTFParts/StartTbl.rtf";
$div = "VlmRTFParts/DivTable.rtf";
$AddQ = "";
while ( $StreamCode = array_shift($flag)){
	if (strcmp($AddQ,"")==0){$AddQ = "streams.CodeOfStream in ('".$StreamCode['CodeOfStream']."' ";}
	else {$AddQ .= ",'".$StreamCode['CodeOfStream']."'" ;}

}
if (strcmp($AddQ,"")!=0){ $AddQ .= ") ";}
$DepCode=$_GET['depart'];
$result = mysql_query("select department.DepName, department.Reduction, faculty.DeanSignature, faculty.Reduction, department.ZavSignature  from department, faculty  where department.CodeOfDepart=$DepCode and department.CodeOfFaculty=faculty.CodeOfFaculty",$Connection)
	or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
$res=mysql_fetch_row($result);
$DepName=$res[0];
$DepRed=$res[1];
$FDepSign=$res[2];
$FDepRed=$res[3];
$DepSign=$res[4];
echo "<P ALIGN='CENTER'><h2>$DepRed: ";
//Подбор имени файла---------------------------
$filename="../../../Exported/Vlm";
$i=0;
while ( file_exists ($filename."_".$i.".rtf")){ $i++;}
$PrintName = "Vlm_".$i.".rtf";
$filename .= "_".$i.".rtf";
//---------------------------------------------
$fl = fopen ($filename,"w")
	or die("Невозможно создать файл  $filename.<BR>");
FileCopy($fl,$start);
fwrite ($fl," Объемы учебной нагрузки $DepRed ");
FileCopy($fl,$colonEnd);
fwrite ($fl,"{\\b\\f51\\fs28\\ul $DepName}");
fwrite ($fl,"{\\b\\f51\\fs28  за }");
$NowDate = getdate();
//	$month = $NowDate["mon"];
fwrite ($fl,"{\\b\\fs28\\ul ".$NowDate["year"]."-".($NowDate["year"]+1)."}");
fwrite ($fl,"{\\b\\f51\\fs28  учебный год\\par }");
fwrite ($fl,"\\pard\\plain \\s1\\ql \\li0\\ri0\\keepn\\widctlpar\\aspalpha\\aspnum\\faauto\\outlinelevel0\\adjustright\\rin0\\lin0\\itap0 \\b\\fs24\\lang1049\\langfe1049\\cgrid\\langnp1049\\langfenp1049 ");
fwrite ($fl,"{\\lang1033\\langfe1049\\langnp1033}");

//Потоки которые учавствуют в расчете
$whereC = " plans.CodeOfPlan=streams.CodeOfPlan and ";

$order_byC=" streams.Kurs, COFSz, COFSp, COFDp, SpzCode, SpcCode, DirCode, streams.StreamName";
$fromC = " streams, plans ";
$selectC = "plans.CodeOfPlan, ". 
	" streams.StreamName, streams.StdCount, streams.Kurs, " . 
	" streams.GroupCount, sz.MinistryCode as SpzCode, s.MinistryCode as SpcCode, d.MinistryCode as DirCode, streams.CodeOfStream, " .
	" sz.CodeOfFaculty as COFSz, s.CodeOfFaculty as COFSp, d.CodeOfFaculty as COFDp, " .
	" plans.TeachForm ";

for ($semestr = 1; $semestr<=2; $semestr++) {

//Подключение файла с расчетом всех переменных за семестр
include($global_variables);


	if ($semestr == 1){
	  fwrite ($fl,"{\\b\\f51\\fs28 Осенний семестр}");
	}else{
	  fwrite ($fl,"{\\page\\b\\f51\\fs28 Весенний семестр}");
	}

	fwrite ($fl,"{\\ul \\par }");
	FileCopy($fl,$startTbl);
	  //Обрабатываем запрос
	$i = 1;		  //Счетчик дисциплин
	$j = 1;		  //Счетчик дисциплин
	$wc = 0;		 //Число недель обучения
	$resSumm = 0;	//Итоговая сумма
	$resSumCons = 0;
	$resSumLab = 0;
	$resSumLec = 0;
	$resSumPract = 0;
	$resSumExams = 0;
	$resSumTest = 0;
	$resSumTestW = 0; 
	$resSumRGR = 0;
	$resSumTR = 0;
	$resSumKW = 0;
	$resSumKP = 0;
	$resSumm = 0;

	$qstream = "select ". $selectC .  "from ". $fromC . 
	  " LEFT JOIN specials AS s ON s.CodeOfSpecial=plans.CodeOfSpecial  LEFT JOIN specializations AS sz ON sz.CodeOfSpecialization=plans.CodeOfSpecialization LEFT JOIN directions AS d ON d.CodeOfDirect=plans.CodeOfDirect " .
	  "where ". $whereC .
	  $AddQ.
	  "order by ".$order_byC;
    $Streams = FetchArrays($qstream);
    foreach ($Streams as $ks =>$vs) {
		$CodeOfFaculty = !empty($vs["COFSz"])? $vs["COFSz"] : (!empty($vs["COFSp"]) ? $vs["COFSp"]:$vs["COFDp"]) ;		//Минист. код специальности
		$Faculty = FetchResult("SELECT * FROM faculty WHERE CodeOfFaculty='$CodeOfFaculty'");	  //код факультета
		$FCode = $CodeOfFaculty;
		$PCode = $vs["CodeOfPlan"];		 //код плана
		$FName = $Faculty["FacName"];			//название факультета
		$FSign = $Faculty["DeanSignature"];	  //название факультета
		$FRed = $Faculty["Reduction"];			//название факультета

		$StrName = $vs["StreamName"];		//Поток
		$students = $vs["StdCount"];		//Количество студентов
		$groups = $vs["GroupCount"];		//Количество групп
		$SpRed = !empty($vs["SpzCode"])? $vs["SpzCode"] : (!empty($vs["SpcCode"]) ? $vs["SpcCode"]:$vs["DirCode"]) ;		//Минист. код специальности
		$TForm = $vs["TeachForm"];		  //Форма обучения
		$kurs = $vs["Kurs"];				//номер курса
		$stream = $vs["CodeOfStream"];

		if ($semestr == 1)
			$Term = ($kurs*2-1);	 //номер семестра
		else
			$Term = ($kurs*2);	 //номер семестра

		$wc = TeachWeek($PCode,$Term);

		$dq = "select \n" .
		" cicles.CodeOfCicle, \n" .
		" cicles.CicReduction, \n" . 
		" disciplins.DisName, \n" .
		" disciplins.CodeOfDiscipline, \n" . 
		" schedplan.CodeOfSchPlan, \n" . 
		" undercicles.CodeOfUnderCicle, schedplan.UndCicCode \n" .
		" from cicles, disciplins, schedplan, undercicles \n" .
		" where \n" .
		" schedplan.CodeOfPlan='$PCode' and \n" .
		" disciplins.CodeOfDiscipline=schedplan.CodeOfDiscipline and \n" .
		" cicles.CodeOfCicle=schedplan.CodeOfCicle and \n " .
		" undercicles.CodeOfUnderCicle = schedplan.CodeOfUndCicle \n" .
		" order by cicles.CodeOfCicle, disciplins.DisName";
		$Discips = FetchArrays($dq);
        $DisCodes = array();   //Массив для уже встречавшихся кодов дисциплин повыбору

		foreach ($Discips as $kd => $vd) {

			$schitem = FetchFirstRow("select * \n" .
			" from schplanitems \n" .
			" where \n" .
			" schplanitems.CodeOfSchPlan='$vd[CodeOfSchPlan]' and \n" .
			" schplanitems.CodeOfDepart='$DepCode' and \n" .
			" schplanitems.NumbOfSemestr='$Term' \n");
			if (!empty($schitem)){
				$CodeOfUnderCicle = $vd["CodeOfUnderCicle"];
				$UndCicCode = $vd["UndCicCode"];
				$lec = $schitem["LectInW"];              //лекций
				$lecs = $schitem["LectSem"];              //лекций
				$lab = $schitem["LabInW"];               //лаб. раб. 
				$labs = $schitem["LabSem"];               //лаб. раб. 
				$pract = $schitem["PractInW"];	   //практик
				$practs = $schitem["PractSem"];	   //практик
				$kp = $schitem["KursPrj"];		   //курс. проект
				$kw = $schitem["KursWork"];		  //курс. работа
				$ex = $schitem["Exam"];		  //Экзамен
				$test = $schitem["Test"];		//Зачет
				$ref = $schitem["Synopsis"];		 //Реферат
				$rgr = $schitem["RGR"];		  //РГР
				$CCode = $vd["CodeOfCicle"];	//код цикла
				$CRed = $vd["CicReduction"];	//сокращенное название цикла дисциплин
				$DisName = $vd["DisName"];	  //Название дисциплины
				$DisCode = $vd["CodeOfDiscipline"]; //Название дисциплины
				$FPrnCode = $FCode;
				$CodeOfSchPlanItem = $schitem["CodeOfSchPlanItem"];

				$ToCount = 1;

				if((strlen($UndCicCode)>2) && ($CodeOfUnderCicle==4)){

					$NewDC=substr($UndCicCode,0,2);
						while ($DCode = each($DisCodes)){
							if ((strcmp($NewDC, $DCode[1][0]) == 0)&&($CodeOfCicle == $DCode[1][1]))
								$ToCount = 0;
						}
				}

				//Если таких дисциплин еще не встречалось то 
				//сохраняем данные о ней и суммируем
				if ($ToCount)
					OutputDiscipline($fl);
			}
		}//Вывод дисциплин 

	} //Вывод потоков

	$result = mysql_query("select * from expansion where CodeOfDepart = $DepCode and Semester='$semestr'",$Connection)
	  or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
	//Вывод переопределенных переменных
/*	if(!empty($global_output) && is_array($global_output))
		foreach($global_output as $k=>$v) {
		  $resSumm += $$k;
		  OutputExpansion($fl,$v,$$k);
		}
*/
	while ($row = mysql_fetch_object($result)){
	  $resSumm += $row->Hours;
	  OutputExpansion($fl,$row->ExpansionName,$row->Hours);
	}
	//Закрываем таблицу
	OutputEnd($fl);
	fwrite($fl,"\\pard \\ql \\li0\\ri0\\widctlpar\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\itap0{\\par }");
}
//Закрываем файл
fwrite($fl,"\\itap0}{\\par}}");
fclose($fl);
echo "<A HREF=\"$filename\">$PrintName</A></h2></P>";
mysql_close($Connection);

echo "<HR>";
include("../Display/FinishPage.php");
?>