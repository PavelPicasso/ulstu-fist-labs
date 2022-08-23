<?php
//Функции используемые при составлении отчетов по расчету штатов

//------------------------------------------------------------
//Копирует в уже открытый для чтения файл $fl
//все строки из файла с именем $FName
Function FileCopy($fTo,$fName){
	$fFrom = fopen ($fName,"r")
	  or die("Невозможно открыть файл  $fName<BR>");
	$contents = fread ($fFrom, filesize ($fName));
	fwrite($fTo,$contents);
	fclose($fFrom);
}
//------------------------------------------------------------

//------------------------------------------------------------
//Вывод строки дисциплины в файл $fl
Function OutputDiscipline($fl){
	//!!!!!!!!!!!!!!!!!!!!!
	//Константы (из таблиц)
	$Kind = 1;
	$labMax = 15;
	$PrMax = 0;
	//!!!!!!!!!!!!!!!!!!!!!

	$Summ = 0;
	global $CodeOfSchPlanItem;
	global $DisCodes;	//Массив для уже встречавшихся кодов дисциплин повыбору
	global $DepCode;
	global $UndCicCode;
	global $CodeOfUnderCicle;
	global $DepCode;
	global $div;
	global $StrName;
	global $SpRed;
	global $DisName;
	global $kurs;
	global $students;
	$Streams = 1; //Потоки
	global $groups;
	global $lec;
	global $lecs;
	global $lab;
	global $labs;
	global $pract;
	global $practs;
	global $wc;
	global $TForm;
	global $ex;
	global $test;
	global $kp;
	global $kw;
	global $rgr;
	global $ref;
	global $DisName;
	global $stream;
	global $DisCode;
	global $Term;
	$TR = 0; //Типовые расчеты

	//Лек, Лаб, Практ
	$NumJ = FetchResult("select UnionRate from blending, streamblending where BlendStyle='lc' and NumbOfSemestr='$Term' and CodeOfDepart='$DepCode' and CodeOfDiscipline='$DisCode' and streamblending.CodeOfBlend=blending.CodeOfBlend and streamblending.CodeOfStream='$stream'");

	if (! $NumJ)
	$NumJ = 1;
	$SumLec = $lecs;
//	$SumLec = $wc*$lec*$Kind/$NumJ;
	$Summ += $SumLec;


//	$PLGroups = ceil($students/$groups/$labMax);

	$PLGroups = FetchResult("select DivRate from divdiscip where BlendStyle='lb' and CodeOfStream='$stream' and CodeOfSchPlanItem='$CodeOfSchPlanItem'");

	if (! $PLGroups)
	$PLGroups = 1;

	$SumLab = $labs;
//	$SumLab = $wc*$lab*$groups*$PLGroups*$Kind;
	$Summ += $SumLab;

//	$PPGroups = 1;//ceil($students/$groups/$PrMax);

	$PPGroups = FetchResult("select DivRate from divdiscip where BlendStyle='pr' and CodeOfStream='$stream' and CodeOfSchPlanItem='$CodeOfSchPlanItem'");

	if (! $PPGroups)
	$PPGroups = 1;

	$SumPract = $practs;
//	$SumPract = $wc*$pract*$groups*$PPGroups*$Kind;
	$Summ += $SumPract;

	//Консультации
	$CNArr = array ("classroom"=>0.05,"correspondence"=>0.15,"night"=>0,"distance"=>0,"external"=>0.15);
	$CN = $CNArr[$TForm];
	if (!isset($CN)){$CN = 0;}
	$SumCons = 0;//?????????
	//Тесты/экзамены
	if (strcmp($DisName,"Дипломный проект")==0){
		$SumExams = $students*(35 - 3 - 2 - 2);
	}else{ 
		if (strcmp($DisName,"Подготовка магистерской диссертации")==0){ 
			$SumExams = $students*(40 - 5);
		}else{ 
			if (strcmp ($DisName,"Госэкзамен по специальности")==0){
				$SumExams = $students*6*0.5;
			}
			else {
				$SumExams = $ex * 0.4 * $students;
				$SumCons = $SumLec*$groups*$CN*$NumJ + $ex*2*$groups;//?????????
			}
		}
	}
	$Summ += $SumCons;
	$Summ += $SumExams;
	$SumTest = $test * 0.3 * $students;
	//Контрольные работы, РГР/рефераты/типовые расчеты
	$Summ += $SumTest;
	$SumTestW = 0;
	$Summ += $SumTestW;
	$SumRGR = 0;
	$Summ += $SumRGR;
	if ($rgr == 1){$SumRGR = $students*0.5;}
	if ($rgr > 1){$SumRGR = $students;}
	if ($ref > 0){$SumRGR += $students*1.5;}

	$SumTR = 0;
	$Summ += $SumTR;
	if ($TR == 1){$SumTR = $Stdents*0.5;}
	if ($TR > 1){$SumTR = $Stdents;}
	//Курсовые работы, курсовые проекты
	$SumKW = $kw*2*$students;
	$Summ += $SumKW;
	$SumKP = $kp*4*$students;
	$Summ += $SumKP;
	
	global $i;
	global $j;
	global $PCode;
	global $res;
	global $CRed;
	global $DisCode;
	global $Term;
	global $local_variables;
	global $local_output;
	$diplom_proect = 0;
	$GEK = 0;
	$teach_practice = 0;
	$work_practice = 0;
	$diplom_practice = 0;
	$test_work_review = 0;
	$test_visit = 0;
	$facultative_lessons = 0;
	$science_practice = 0;
	$science_work_guide = 0;

	//Подключение файла с расчетом всех переменных за семестр
	include($local_variables);

	$Summ += $diplom_proect + $GEK + $teach_practice + $work_practice + $diplom_practice + $test_work_review + $test_visit + $facultative_lessons + $science_practice + $science_work_guide;

	$i++; //переводим счетчик дисциплин
	/*\\i\\ul - КУРСИВ И ПОДЧЕРКИВАНИЕ*/
	fwrite($fl,"{\\f51\\fs22 $StrName-$SpRed}\n");
	fwrite($fl,"{\\fs22\\dbch\\af28 \\cell }\n");
	fwrite($fl,"\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\n");
	fwrite($fl,"{\\f51\\fs22 $DisName}\n");
	fwrite($fl,"{\\fs22\\dbch\\af28 \\cell }\n");
	fwrite($fl,"\\pard \\qc \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 \n");
	fwrite($fl,"{\\fs22\\dbch\\af28 \\hich\\af0\\dbch\\af28\\loch\\f0 $kurs\\cell \n");
	fwrite($fl,"\\hich\\af0\\dbch\\af28\\loch\\f0 $students\\cell \n");
	fwrite($fl,"\\hich\\af0\\dbch\\af28\\loch\\f0 $Streams\\cell \n");
	fwrite($fl,"\\hich\\af0\\dbch\\af28\\loch\\f0 $groups\\cell \n");
	fwrite($fl,"\\hich\\af0\\dbch\\af28\\loch\\f0 $lec\\cell }\n");
	fwrite($fl,"\\pard \\qr \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 \n");
	fwrite($fl,"{\\fs22\\dbch\\af28 \\hich\\af0\\dbch\\af28\\loch\\f0 $SumLec\\cell }\n");
	fwrite($fl,"\\pard \\qc \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 \n");
	fwrite($fl,"{\\fs22\\dbch\\af28 \\hich\\af0\\dbch\\af28\\loch\\f0 $pract\\cell }\n");
	fwrite($fl,"\\pard \\qr \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 \n");
	fwrite($fl,"{\\fs22\\dbch\\af28 \\hich\\af0\\dbch\\af28\\loch\\f0 $SumPract\\cell }\n");
	fwrite($fl,"\\pard \\qc \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 \n");
	fwrite($fl,"{\\fs22\\dbch\\af28 \\hich\\af0\\dbch\\af28\\loch\\f0 $lab\\cell }\n");
	fwrite($fl,"\\pard \\qr \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 \n");
	fwrite($fl,"{\\fs22\\dbch\\af28 \\hich\\af0\\dbch\\af28\\loch\\f0 $SumLab\n");
	fwrite($fl,"\\cell \\hich\\af0\\dbch\\af28\\loch\\f0 $SumCons\\cell \n");
	fwrite($fl,"\\hich\\af0\\dbch\\af28\\loch\\f0 $SumExams\\cell \n");
	fwrite($fl,"\\hich\\af0\\dbch\\af28\\loch\\f0 $SumTest \\cell\n");
	fwrite($fl,"\\hich\\af0\\dbch\\af28\\loch\\f0 $SumTestW \\cell\n");
	fwrite($fl,"\\hich\\af0\\dbch\\af28\\loch\\f0 $SumRGR \\cell\n");
	fwrite($fl,"\\hich\\af0\\dbch\\af28\\loch\\f0 $SumTR \\cell\n");
	fwrite($fl,"\\hich\\af0\\dbch\\af28\\loch\\f0 $SumKW \\cell\n");
	fwrite($fl,"\\hich\\af0\\dbch\\af28\\loch\\f0 $SumKP \\cell\n");
	fwrite($fl,"\\hich\\af0\\dbch\\af28\\loch\\f0 $diplom_proect \\cell\n");
	fwrite($fl,"\\hich\\af0\\dbch\\af28\\loch\\f0 $GEK \\cell\n");
	fwrite($fl,"\\hich\\af0\\dbch\\af28\\loch\\f0 $teach_practice \\cell\n");
	fwrite($fl,"\\hich\\af0\\dbch\\af28\\loch\\f0 $work_practice \\cell\n");
	fwrite($fl,"\\hich\\af0\\dbch\\af28\\loch\\f0 $diplom_practice \\cell\n");
	fwrite($fl,"\\hich\\af0\\dbch\\af28\\loch\\f0 $test_work_review \\cell\n");
	fwrite($fl,"\\hich\\af0\\dbch\\af28\\loch\\f0 $test_visit \\cell\n");
	fwrite($fl,"\\hich\\af0\\dbch\\af28\\loch\\f0 $facultative_lessons \\cell\n");
	fwrite($fl,"\\hich\\af0\\dbch\\af28\\loch\\f0 $science_practice \\cell\n");
	fwrite($fl,"\\hich\\af0\\dbch\\af28\\loch\\f0 $science_work_guide \\cell }\n");
//	fwrite($fl," \\cell \\cell \\cell \\cell \\cell \\cell \\cell \\cell }\n");
//	fwrite($fl,"{\\fs22 \\~}{\\fs22\\dbch\\af28 \\cell }{\\fs22 \\~}{\\fs22\\dbch\\af28 \\cell }\n");
	fwrite($fl,"{\\fs22 $Summ}\n");
//	fwrite($fl,"\n");
//	fwrite($fl,"\n");

	//Суммирование глобальных сумм
	global $resSumCons;
	global $resSumLab;
	global $resSumLec;
	global $resSumPract;
	global $resSumExams;
	global $resSumTest;
	global $resSumTestW; 
	global $resSumRGR;
	global $resSumTR;
	global $resSumKW;
	global $resSumKP;
	global $resSumm;
	$resSumCons += $SumCons;
	$resSumLab += $SumLab;
	$resSumLec += $SumLec;
	$resSumPract += $SumPract;
	$resSumExams += $SumExams;
	$resSumTest += $SumTest;
	$resSumTestW += $SumTestW; 
	$resSumRGR += $SumRGR;
	$resSumTR += $SumTR;
	$resSumKW += $SumKW;
	$resSumKP += $SumKP;
	$resSumm += $Summ;

	FileCopy($fl,$div);

	if(!empty($local_output) && is_array($local_output))
		foreach($local_output as $k=>$v) {
		  $resSumm += $$k;
		  OutputExpansion($fl,"$DisName ($v)",$$k,"$StrName-$SpRed");
		}

	//сбрасываем значения выводимых переменных в несуществующие
	$DisCode = -1; //Код выодимой дисциплины
//	$Term = "";		//выводимые данные о семестрах
	$lec = 0;		//часы лекций
	$lab = 0;		//часы лекций
	$pract = 0;	 //часы лекций
	$ex = 0;
	$test = 0;
	$kp = 0;
	$kw = 0;
	$rgr = 0;
	$ref = 0;
}
//------------------------------------------------------------

//------------------------------------------------------------
//Вывод строки расширения учебной нагруки в файл $fl
Function OutputExpansion($fl,$expName,$Hours,$Group=""){
	global $div;
	global $i;
	$i++; //переводим счетчик дисциплин
	/*\\i\\ul - КУРСИВ И ПОДЧЕРКИВАНИЕ*/
	fwrite($fl,"{\\f51\\fs22 $Group}\n");
	fwrite($fl,"{\\fs22\\dbch\\af28 \\cell }\n");
	fwrite($fl,"\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\n");
	fwrite($fl,"{\\f51\\fs22 $expName}\n");
	fwrite($fl,"{\\fs22\\dbch\\af28 \\cell }\n");
	fwrite($fl,"\\pard \\qc \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 \n");
	fwrite($fl,"{\\fs22\\dbch\\af28 \\hich\\af0\\dbch\\af28\\loch\\f0 \\cell \n");
	fwrite($fl,"\\hich\\af0\\dbch\\af28\\loch\\f0 \\cell \n");
	fwrite($fl,"\\hich\\af0\\dbch\\af28\\loch\\f0 \\cell \n");
	fwrite($fl,"\\hich\\af0\\dbch\\af28\\loch\\f0 \\cell \n");
	fwrite($fl,"\\hich\\af0\\dbch\\af28\\loch\\f0 \\cell }\n");
	fwrite($fl,"\\pard \\qr \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 \n");
	fwrite($fl,"{\\fs22\\dbch\\af28 \\hich\\af0\\dbch\\af28\\loch\\f0 \\cell }\n");
	fwrite($fl,"\\pard \\qc \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 \n");
	fwrite($fl,"{\\fs22\\dbch\\af28 \\hich\\af0\\dbch\\af28\\loch\\f0 \\cell }\n");
	fwrite($fl,"\\pard \\qr \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 \n");
	fwrite($fl,"{\\fs22\\dbch\\af28 \\hich\\af0\\dbch\\af28\\loch\\f0 \\cell }\n");
	fwrite($fl,"\\pard \\qc \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 \n");
	fwrite($fl,"{\\fs22\\dbch\\af28 \\hich\\af0\\dbch\\af28\\loch\\f0 \\cell }\n");
	fwrite($fl,"\\pard \\qr \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 \n");
	fwrite($fl,"{\\fs22\\dbch\\af28 \\hich\\af0\\dbch\\af28\\loch\\f0 \n");
	fwrite($fl,"\\cell \\hich\\af0\\dbch\\af28\\loch\\f0 \\cell \n");
	fwrite($fl,"\\hich\\af0\\dbch\\af28\\loch\\f0 \\cell \n");
	fwrite($fl,"\\hich\\af0\\dbch\\af28\\loch\\f0 \\cell\n");
	fwrite($fl,"\\hich\\af0\\dbch\\af28\\loch\\f0 \\cell\n");
	fwrite($fl,"\\hich\\af0\\dbch\\af28\\loch\\f0 \\cell\n");
	fwrite($fl,"\\hich\\af0\\dbch\\af28\\loch\\f0 \\cell\n");
	fwrite($fl,"\\hich\\af0\\dbch\\af28\\loch\\f0 \\cell\n");
	fwrite($fl,"\\hich\\af0\\dbch\\af28\\loch\\f0 \\cell\n");
	fwrite($fl," \\cell \\cell \\cell \\cell \\cell \\cell \\cell \\cell }\n");
	fwrite($fl,"{\\fs22 \\~}{\\fs22\\dbch\\af28 \\cell }{\\fs22 \\~}{\\fs22\\dbch\\af28 \\cell }{\\fs22 $Hours}\n");
	fwrite($fl,"\n");
	fwrite($fl,"\n");

	FileCopy($fl,$div);
}
//------------------------------------------------------------

//------------------------------------------------------------
//Вывод строки дисциплины в файл $fl
Function OutputEnd($fl){
	global $div;
	global $kurs;
	global $resSumCons;
	global $resSumLab;
	global $resSumLec;
	global $resSumPract;
	global $resSumm;
	global $resSumExams;
	global $resSumTest;
	global $resSumTestW; 
	global $resSumRGR;
	global $resSumTR;
	global $resSumKW;
	global $resSumKP;
	global $Term;
	/*\\i\\ul - КУРСИВ И ПОДЧЕРКИВАНИЕ*/
	fwrite($fl,"{\\f51\\fs22 }\n");
	fwrite($fl,"{\\fs22\\dbch\\af28 \\cell }\n");
	fwrite($fl,"\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\n");
	if ($Term == 1){
		fwrite($fl,"{\\f51\\fs22 Итого за осенний семестр}\n");
	}else {fwrite($fl,"{\\f51\\fs22 Итого за весенний семестр}\n");}
	fwrite($fl,"{\\fs22\\dbch\\af28 \\cell }\n");
	fwrite($fl,"\\pard \\qc \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 \n");
	fwrite($fl,"{\\fs22\\dbch\\af28 \\hich\\af0\\dbch\\af28\\loch\\f0 $kurs\\cell \n");
	fwrite($fl,"\\hich\\af0\\dbch\\af28\\loch\\f0 \\cell \n");
	fwrite($fl,"\\hich\\af0\\dbch\\af28\\loch\\f0 \\cell \n");
	fwrite($fl,"\\hich\\af0\\dbch\\af28\\loch\\f0 \\cell \n");
	fwrite($fl,"\\hich\\af0\\dbch\\af28\\loch\\f0 \\cell }\n");
	fwrite($fl,"\\pard \\qr \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 \n");
	fwrite($fl,"{\\fs22\\dbch\\af28 \\hich\\af0\\dbch\\af28\\loch\\f0 $resSumLec\\cell }\n");
	fwrite($fl,"\\pard \\qc \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 \n");
	fwrite($fl,"{\\fs22\\dbch\\af28 \\hich\\af0\\dbch\\af28\\loch\\f0 \\cell }\n");
	fwrite($fl,"\\pard \\qr \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 \n");
	fwrite($fl,"{\\fs22\\dbch\\af28 \\hich\\af0\\dbch\\af28\\loch\\f0 $resSumPract\\cell }\n");
	fwrite($fl,"\\pard \\qc \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 \n");
	fwrite($fl,"{\\fs22\\dbch\\af28 \\hich\\af0\\dbch\\af28\\loch\\f0 \\cell }\n");
	fwrite($fl,"\\pard \\qr \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 \n");
	fwrite($fl,"{\\fs22\\dbch\\af28 \\hich\\af0\\dbch\\af28\\loch\\f0 $resSumLab\n");
	fwrite($fl,"\\cell \\hich\\af0\\dbch\\af28\\loch\\f0 $resSumCons\\cell \n");
	fwrite($fl,"\\hich\\af0\\dbch\\af28\\loch\\f0 $resSumExams\\cell \n");
	fwrite($fl,"\\hich\\af0\\dbch\\af28\\loch\\f0 $resSumTest \\cell\n");
	fwrite($fl,"\\hich\\af0\\dbch\\af28\\loch\\f0 $resSumTestW \\cell\n");
	fwrite($fl,"\\hich\\af0\\dbch\\af28\\loch\\f0 $resSumRGR \\cell\n");
	fwrite($fl,"\\hich\\af0\\dbch\\af28\\loch\\f0 $resSumTR \\cell\n");
	fwrite($fl,"\\hich\\af0\\dbch\\af28\\loch\\f0 $resSumKW \\cell\n");
	fwrite($fl,"\\hich\\af0\\dbch\\af28\\loch\\f0 $resSumKP \\cell\n");
	fwrite($fl," \\cell \\cell \\cell \\cell \\cell \\cell \\cell \\cell }\n");
	fwrite($fl,"{\\fs22 \\~}{\\fs22\\dbch\\af28 \\cell }{\\fs22 \\~}{\\fs22\\dbch\\af28 \\cell }{\\fs22 $resSumm}\n");
	fwrite($fl,"\n");
	fwrite($fl,"\n");

	FileCopy($fl,$div);
}
//------------------------------------------------------------

?>