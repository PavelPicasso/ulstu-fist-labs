<?php                                   
    include("../Display/DisplayFunc.php");

    include("../PlanCalculatFunc.php");
    CreateConnection();
    include("../Display/StartPage.php");
?>      <br>
	<table width=80% border=0 cellpadding=2 cellspacing=2>
	<tr>
	<form action="" method="post" enctype="multipart/form-data">
	
		<td colspan=2><b><u>Загрузка xml-файла</u></b></td>
	</tr>
	<tr>
		<td></td>
		<td>
	 		<b>Укажите путь к файлу</b> (нажмите кнопку "Обзор" и выберите файл с расширением *.xml)
 			<input type="file" name="filepath" size="40">
		</td>	
	</tr>
	<tr><td></td>
	    <td>
		<b>Введите</b> служебную информацию
	 	<table width=90% cellpadding=0 cellspacing=0 border=0 align="center">
		<tr>
			<td>
				Введите&nbsp;название&nbsp;плана:&nbsp; 
			</td>
			<td width=100%>
				<input type="text" name="plnName" value="План подготовки инженеров 2009" size="50">
			</td>
		</tr>
		<tr>
			<td>
				Укажите&nbsp;программу&nbsp;обучения:&nbsp; 
			</td>
			<td>
				<select name="usk">
						<option value="poln">полная
						<option value="usk">ускоренная
				</select>
			</td>
		</tr>
		<tr>
			<td>
				Укажите&nbsp;форму&nbsp;обучения:&nbsp; 
			</td>
			<td>
				<select name="edu">
				<option value="classroom">очная
					<option value="night">очно-заочная
					<option value="correspondence">заочная
					<option value="external">экстернат
				</select>
		        </td>
		</tr>
		<tr>
			<td>
				Укажите&nbsp;название&nbsp;потока:&nbsp; 
			</td>
			<td>
				<input type="text" name="stream" size="10" value="неуказано">&nbsp;(Например ЭВМд, БРд, ММд и т.д.)
		        </td>
		</tr>

		</table>
	    </td>
	</tr>
	<tr><td></td>
	    <td>
		<input type="submit" value="Импорт плана из GosInsp">
		<input type="hidden" name="import" value="2">
	</form>
			
	</td></tr>

<?php
    if (isset($_POST['import']) && $_POST['import']==2){
	$cafedras = FileToArray('depnames.txt');
	$fac_id = $_SESSION["statusCode"];
	//$xml =  simplexml_load_file($_FILES['filepath']['tmp_name']);
	$xml = new DOMDocument('1.0', 'windows-1251');
	$xml->load($_FILES['filepath']['tmp_name']);
	$xpath = new DOMXpath($xml);
	$root = $xpath->query(iconv("cp1251", "utf-8", "/Документ"))->item(0);
	$data = array();
	$weeks = array();
	$rootChilds = $root->childNodes;
	/*{
		echo iconv("utf-8", "cp1251", $root->nodeName);
		return;
	}*/
	//foreach($xml as $key0 => $value0){
	foreach($rootChilds as $key0){
		//Элемент "План"
		//Из атрибутов выделяем ФормуОбучения
		foreach($key0->attributes as $attributeskey0){
			$attributeskey0Name = iconv("UTF-8", "cp1251", $attributeskey0->nodeName);
            $attributesvalue0 = iconv("UTF-8", "cp1251", $attributeskey0->nodeValue);
			
			//############################
			//echo "<b>".iconv("UTF-8", "cp1251", $key0->nodeName).": </b><i>".$attributeskey0Name."</i> - ".$attributesvalue0."<br>";
			//############################
			
			if ($attributeskey0Name=="ФормаОбучения")
				$data['edform_ab'] = $attributesvalue0;
		}
                $res = FetchResult("Select max(CodeOfPlan) as id from plans");
		$plnCode = $res+1;

		//Проверяем вложенные элементы "Титул", "СтрокиПлана"
		
		$key0Childs = $key0->childNodes;
		foreach($key0Childs as $key1){
			$key1Name = iconv("UTF-8", "cp1251", $key1->nodeName); 

			//############################
			//echo "<b>".$key1Name."</b><br>";
			//############################
			
			
			if ($_POST['usk']=="usk") $usk = 1;
			else $usk = 0;

			if ($key1Name=="Титул"){
				foreach($key1->attributes as $attributeskey1){
					$attributeskey1Name = iconv("UTF-8", "cp1251", $attributeskey1->nodeName);
                	$attributesvalue1 = iconv("UTF-8", "cp1251", $attributeskey1->nodeValue);
							
					//############################
					//echo "<b>".iconv("UTF-8", "cp1251", $key1->nodeName).": </b><i>".$attributeskey1Name."</i> - ".$attributesvalue1."<br>";
					//############################
					
					if ($attributeskey1Name=="ПоследнийШифр")
						$data['code'] = $attributesvalue1;
					if ($attributeskey1Name=="КодКафедры")
						$data['cafedra'] = $attributesvalue1;
					if ($attributeskey1Name=="Факультет"){
						$data['faculty'] = $attributesvalue1;
					if ($data['faculty']=='ИСТ') 
						$data['faculty'] = 'ФИСТ';
					if ($data['faculty']=='ПП и БФО' || $data['faculty']=='ФПП и БФО' || $data['faculty']=='ППиБФО') $data['faculty'] = 'ФППиБФО';
						$resFac = FetchResult("Select CodeOfFaculty as id from faculty where Reduction='".$data['faculty']."'");
					if (!$resFac) 
						$data['faculty'] = 0;
					else 
						$data['faculty'] = $resFac;
					}
					
					if ($attributeskey1Name=="ДатаПриложения")
						$data['filedate'] = $attributesvalue1;
				}
				
				$key1Childs = $key1->childNodes;
				foreach($key1Childs as $key2){
					$key2Name = iconv("UTF-8", "cp1251", $key2->nodeName); 
							
					//############################
					//echo "<b>".$key2Name."</b><br>";
					//############################
					
					if ($key2Name=="Утверждение"){
						foreach($key2->attributes as $attributeskey2){
                            $attributeskey2Name = iconv("UTF-8", "cp1251", $attributeskey2->nodeName);
							$attributesvalue2 = iconv("UTF-8", "cp1251", $attributeskey2->nodeValue);
											
							//############################
							//echo "<b>".iconv("UTF-8", "cp1251", $key2->nodeName).": </b><i>".$attributeskey2Name."</i> - ".$attributesvalue2."<br>";
							//############################
							
							if ($attributeskey2Name=="Дата")
								$data['date'] = $attributesvalue2;
						}
					}			
					if ($key2Name=="Квалификации"){
						
						$key2Childs = $key2->childNodes;
						foreach($key2Childs as $key3){
							foreach($key3->attributes as $attributeskey3){
                                $attributeskey3Name = iconv("UTF-8", "cp1251", $attributeskey3->nodeName);
		                		$attributesvalue3 = iconv("UTF-8", "cp1251", $attributeskey3->nodeValue);
													
								//############################
								//echo "<b>".iconv("UTF-8", "cp1251", $key2->nodeName).": </b><i>".$attributeskey2Name."</i> - ".$attributesvalue2."<br>";
								//############################
								
								if ($attributeskey3Name=="Название"){
									$data['kval'] = $attributesvalue3;
									$data['kval_code'] = '65';
									$kval = 'enginer';
									$data['degree'] = 2;
									if ($data['kval']=='магистр'){
										$data['kval_code']='68';
										$kval = 'magistr';
									    $data['degree'] = 4;
									}
									elseif($data['kval']=='бакалавр'){ 
										$data['kval_code']='62';
										$kval = 'bakalavr';
										$data['degree'] = 1;
									}
									elseif($data['kval']=='специалист' || $data['kval']=='экономист')
									        $data['degree'] = 3;
									$data['kval'] = $kval;
								}
								if ($attributeskey3Name=="СрокОбучения")
									$data['years'] = $attributesvalue3;
							}
						}
					}
					if ($key2Name=="ГрафикУчПроцесса"){
						$sched = array();
						
						$key2Childs = $key2->childNodes;
						foreach($key2Childs as $key3){
							$kurs = 0;
							foreach($key3->attributes as $attributeskey3){
                                $attributeskey3Name = iconv("UTF-8", "cp1251", $attributeskey3->nodeName);
		                		$attributesvalue3 = iconv("UTF-8", "cp1251", $attributeskey3->nodeValue);
													
								//############################
								//echo "<b>".iconv("UTF-8", "cp1251", $key3->nodeName).": </b><i>".$attributeskey3Name."</i> - ".$attributesvalue3."<br>";
								//############################
																
								if ($attributeskey3Name=="Ном")
									$kurs = $attributesvalue3;
	                            if ($attributeskey3Name=="Студентов")
									$sched[$kurs]['stud'] = $attributesvalue3;
	                            if ($attributeskey3Name=="Групп")
									$sched[$kurs]['group'] = $attributesvalue3;
	                            if ($attributeskey3Name=="График")
									$sched[$kurs]['chart'] = $attributesvalue3;
							}
						}
						parseSchedule($sched, $data, $weeks);
					}
				}
				
				$resSp = FetchResult("Select CodeOfSpecial as id from specials where MinistryCode='".$data['code'].$data['kval_code']."' and CodeOfFaculty=".$data['faculty']);
				if ($resSp) $spcCode = $resSp;
				else{
					$res = FetchResult("Select max(CodeOfSpecial) as id from specials");
					$spcCode = $res+1;
					$q = "INSERT INTO specials (CodeOfSpecial, MinistryCode, SpcName, CodeOfDepart, CodeOfFaculty, Type, CodeOfDegree, CodeOfDirect)".
					" VALUES ($spcCode, '".$data['code'].$data['kval_code']."', '', ".$cafedras[$data['cafedra']-1].", ".$data['faculty'].", '".$data['kval']."', ".
					$data['degree'].", 0)";
                    FetchQuery($q);
					
					//IIIIIIIIIIIIIIIIIIIIIIIIIIII
					echo "INSERT INTO specials (CodeOfSpecial, MinistryCode, SpcName, CodeOfDepart, CodeOfFaculty, Type, CodeOfDegree, CodeOfDirect)".
					" VALUES ($spcCode, '".$data['code'].$data['kval_code']."', '', ".$cafedras[$data['cafedra']-1].", ".$data['faculty'].", '".$data['kval']."', ".
					$data['degree'].", 0)<br><br>";
					//IIIIIIIIIIIIIIIIIIIIIIIIIIII
				}

                  		$d = date("Y-m-d H:i:s");
	                        //$q = "INSERT INTO plans (CodeOfPlan, CodeOfDirect, CodeOfSpecial, CodeOfSpecialization, ".
				//"PlnName, Fast, Date, FixDate, YearCount, TeachForm) VALUES ($plnCode, 0, $spcCode, ".
				//"0, '".$_POST['plnName']."', $usk, '".$data['date']." 00:00:00', '$d', ".$data['years'].", '".$_POST['edu']."')"; 
				$q = "INSERT INTO plans (CodeOfPlan, CodeOfDirect, CodeOfSpecial, CodeOfSpecialization, ".
				"PlnName, Date, FixDate, YearCount, TeachForm) VALUES ($plnCode, 0, $spcCode, ".
				"0, '".$_POST['plnName']."', '".$data['date']." 00:00:00', '$d', ".$data['years'].", '".$_POST['edu']."')"; 
				FetchQuery($q);
				
					//IIIIIIIIIIIIIIIIIIIIIIIIIIII
					echo "INSERT INTO plans (CodeOfPlan, CodeOfDirect, CodeOfSpecial, CodeOfSpecialization, ".
					"PlnName, Date, FixDate, YearCount, TeachForm) VALUES ($plnCode, 0, $spcCode, ".
					"0, '".$_POST['plnName']."', '".$data['date']." 00:00:00', '$d', ".$data['years'].", '".$_POST['edu']."')<br><br>"; 
					//IIIIIIIIIIIIIIIIIIIIIIIIIIII
					
				foreach($data['schedule'] as $kurs=>$schedule){
					$q = "INSERT INTO schedules (CodeOfPlan, KursNumb, Period1, Period2, Period3, Period4, ".
						"Period5, Period6, Period7, Period8, Period9, Period10, Period11, Period12, Period13, ".
						"Period14, Period15, Period16, LengthOfPeriod1, LengthOfPeriod2, LengthOfPeriod3, LengthOfPeriod4, ".
						"LengthOfPeriod5, LengthOfPeriod6, LengthOfPeriod7, LengthOfPeriod8, LengthOfPeriod9, ".
						"LengthOfPeriod10, LengthOfPeriod11, LengthOfPeriod12, LengthOfPeriod13, LengthOfPeriod14, ".
						"LengthOfPeriod15, LengthOfPeriod16) VALUES ($plnCode, $kurs, '".$schedule[0]['type']."', '".$schedule[1]['type']."', ".
						"'".$schedule[2]['type']."', '".$schedule[3]['type']."', '".$schedule[4]['type']."', '".$schedule[5]['type']."', ".
						"'".$schedule[6]['type']."', '".$schedule[7]['type']."', '".$schedule[8]['type']."', '".$schedule[9]['type']."', ".
						"'".$schedule[10]['type']."', '".$schedule[11]['type']."', '".$schedule[12]['type']."', '".$schedule[13]['type']."', ".
						"'".$schedule[14]['type']."', '".$schedule[15]['type']."', ".$schedule[0]['len'].", ".$schedule[1]['len'].", ".
						$schedule[2]['len'].", ".$schedule[3]['len'].", ".$schedule[4]['len'].", ".$schedule[5]['len'].", ".
						$schedule[6]['len'].", ".$schedule[7]['len'].", ".$schedule[8]['len'].", ".$schedule[9]['len'].", ".
						$schedule[10]['len'].", ".$schedule[11]['len'].", ".$schedule[12]['len'].", ".$schedule[13]['len'].", ".
						$schedule[14]['len'].", ".$schedule[15]['len'].")";
						
						//IIIIIIIIIIIIIIIIIIIIIIIIIIII
						echo "INSERT INTO schedules (CodeOfPlan, KursNumb, Period1, Period2, Period3, Period4, ".
						"Period5, Period6, Period7, Period8, Period9, Period10, Period11, Period12, Period13, ".
						"Period14, Period15, Period16, LengthOfPeriod1, LengthOfPeriod2, LengthOfPeriod3, LengthOfPeriod4, ".
						"LengthOfPeriod5, LengthOfPeriod6, LengthOfPeriod7, LengthOfPeriod8, LengthOfPeriod9, ".
						"LengthOfPeriod10, LengthOfPeriod11, LengthOfPeriod12, LengthOfPeriod13, LengthOfPeriod14, ".
						"LengthOfPeriod15, LengthOfPeriod16) VALUES ($plnCode, $kurs, '".$schedule[0]['type']."', '".$schedule[1]['type']."', ".
						"'".$schedule[2]['type']."', '".$schedule[3]['type']."', '".$schedule[4]['type']."', '".$schedule[5]['type']."', ".
						"'".$schedule[6]['type']."', '".$schedule[7]['type']."', '".$schedule[8]['type']."', '".$schedule[9]['type']."', ".
						"'".$schedule[10]['type']."', '".$schedule[11]['type']."', '".$schedule[12]['type']."', '".$schedule[13]['type']."', ".
						"'".$schedule[14]['type']."', '".$schedule[15]['type']."', ".$schedule[0]['len'].", ".$schedule[1]['len'].", ".
						$schedule[2]['len'].", ".$schedule[3]['len'].", ".$schedule[4]['len'].", ".$schedule[5]['len'].", ".
						$schedule[6]['len'].", ".$schedule[7]['len'].", ".$schedule[8]['len'].", ".$schedule[9]['len'].", ".
						$schedule[10]['len'].", ".$schedule[11]['len'].", ".$schedule[12]['len'].", ".$schedule[13]['len'].", ".
						$schedule[14]['len'].", ".$schedule[15]['len'].")<br><br>";
						//IIIIIIIIIIIIIIIIIIIIIIIIIIII
						
					FetchQuery($q);
					$q = "INSERT INTO streams (StreamName, Kurs, GroupCount, StdCount, CodeOfPlan) VALUES ('".$_POST['stream'].
						"', $kurs, ".$schedule['group'].", ".$schedule['stud'].", $plnCode)";
						
						//IIIIIIIIIIIIIIIIIIIIIIIIIIII
						echo "INSERT INTO streams (StreamName, Kurs, GroupCount, StdCount, CodeOfPlan) VALUES ('".$_POST['stream'].
						"', $kurs, ".$schedule['group'].", ".$schedule['stud'].", $plnCode)<br><br>"; 
						//IIIIIIIIIIIIIIIIIIIIIIIIIIII
					FetchQuery($q);
				}				


			}
			if ($key1Name=="СтрокиПлана"){
				$planDis = array();
				$i = 0;
				$res = FetchResult("Select max(CodeOfSchPlan) as id from schedplan");
				$schCode = $res+1;

				$key1Childs = $key1->childNodes;
				foreach($key1Childs as $key2){
					foreach($key2->attributes as $attributeskey2){
                        $attributeskey2Name = iconv("UTF-8", "cp1251", $attributeskey2->nodeName);
		                $attributesvalue2 = iconv("UTF-8", "cp1251", $attributeskey2->nodeValue);
			
						//############################
						//echo "<b>".iconv("UTF-8", "cp1251", $key2->nodeName).": </b><i>".$attributeskey2Name."</i> - ".$attributesvalue2."<br>";
						//############################
						
						if ($attributeskey2Name=='Дис')
							$planDis[$i]['discip'] = $attributesvalue2;
						if ($attributeskey2Name=='ПодлежитИзучению')
							$planDis[$i]['allH'] = $attributesvalue2;
						if ($attributeskey2Name=='СР')
							$planDis[$i]['SR'] = $attributesvalue2;	
						if ($attributeskey2Name=='Кафедра')
							$planDis[$i]['depart'] = $cafedras[$attributesvalue2-1];	
						if ($attributeskey2Name=='ИдетификаторДисциплины')
							$planDis[$i]['cicle'] = $attributesvalue2;
						if ($attributeskey2Name=='СемЭкз')
							examForm($planDis,'exam', $i, $attributesvalue2);
						if ($attributeskey2Name=='СемЗач')
							examForm($planDis,'test', $i, $attributesvalue2);
						if ($attributeskey2Name=='СемКР')
							examForm($planDis,'kr', $i, $attributesvalue2);
	                    if ($attributeskey2Name=='СемКП')
							examForm($planDis,'kp', $i, $attributesvalue2);
					}
					if ($planDis[$i]['discip'] && $planDis[$i]['depart'] && $planDis[$i]['cicle']){
						$disCode = DiscipCode($planDis[$i]['discip'],$planDis[$i]['depart']);
						$cicle = CiclesCode($planDis[$i]['cicle']);
						$q = "INSERT INTO schedplan (CodeOfSchPlan, CodeOfPlan, CodeOfDiscipline, CodeOfCicle, CodeOfUndCicle, ".
						"CodeOfDepart, UndCicCode, AllH, ToCount) VALUES ($schCode,$plnCode,$disCode,$cicle[0],$cicle[1],".
						$planDis[$i]['depart'].",'$cicle[2]',".$planDis[$i]['allH'].",1)";
						
						//IIIIIIIIIIIIIIIIIIIIII
						echo "INSERT INTO schedplan (CodeOfSchPlan, CodeOfPlan, CodeOfDiscipline, CodeOfCicle, CodeOfUndCicle, ".
						"CodeOfDepart, UndCicCode, AllH, ToCount) VALUES ($schCode,$plnCode,$disCode,$cicle[0],$cicle[1],".
						$planDis[$i]['depart'].",'$cicle[2]',".$planDis[$i]['allH'].",1)<br><br>";
						//IIIIIIIIIIIIIIIIIIIIII
						
						FetchQuery($q);
						$res = FetchResult("Select max(CodeOfSchPlanItem) as id from schplanitems");
						$schiCode = $res+1;

						$key2Childs = $key2->childNodes;
						foreach($key2Childs as $key3){
							$key3Name = iconv("UTF-8", "cp1251", $key3->nodeName);
											
							//############################
							//echo "<b>".$key3Name."</b><br>";
							//############################
							
							if ($key3Name == "Сем"){
								$lect = $pract = $lab = 0;
								foreach($key3->attributes as $attributeskey3){
                                    $attributeskey3Name = iconv("UTF-8", "cp1251", $attributeskey3->nodeName);
					                $attributesvalue3 = iconv("UTF-8", "cp1251", $attributeskey3->nodeValue);
															
									//############################
									//echo "<b>".iconv("UTF-8", "cp1251", $key3->nodeName).": </b><i>".$attributeskey3Name."</i> - ".$attributesvalue3."<br>";
									//############################
																		
									if ($attributeskey3Name=='Ном') 
										$sem = $attributesvalue3;
									if ($attributeskey3Name=='Лек') 
										$lect = $attributesvalue3;
									if ($attributeskey3Name=='Пр') 
										$pract = $attributesvalue3;
                                    if ($attributeskey3Name=='Лаб') 
										$lab = $attributesvalue3;
								}
								$auditH = $lect+$pract+$lab;							
								$lectinw = ($weeks[$sem] && $weeks[$sem]!=0) ? (($lect!=0 && round($lect/$weeks[$sem])<1) ? 1: round($lect/$weeks[$sem])): 0;
								$practinw = ($weeks[$sem] && $weeks[$sem]!=0) ? (($pract!=0 && round($pract/$weeks[$sem])<1) ? 1: round($pract/$weeks[$sem])): 0;
								$labinw = ($weeks[$sem] && $weeks[$sem]!=0) ? (($lab!=0 && round($lab/$weeks[$sem])<1) ? 1: round($lab/$weeks[$sem])): 0;
								$exam = ($planDis[$i]['items'][$sem]['exam']) ? 1: 0;
								$test = ($planDis[$i]['items'][$sem]['test']) ? 1: 0; 
								$kr = ($planDis[$i]['items'][$sem]['kr']) ? 1: 0; 
								$kp = ($planDis[$i]['items'][$sem]['kp']) ? 1: 0; 
								//$q = "INSERT INTO schplanitems (CodeOfSchPlanItem, CodeOfSchPlan, CodeOfDepart, ".
								//"NumbOfSemestr, AuditH, LectSem, PractSem, LabSem, LectInW, LabInW, PractInW, ".
								//"KursWork, KursPrj, Test, Exam, ControlWork, RGR, CalcW, TestW, Synopsis) VALUES ".
								//"($schiCode, $schCode, ".$planDis[$i]['depart'].", $sem, $auditH, $lect, $pract, ".
								//"$lab, $lectinw, $labinw, $practinw, $kr, $kp, $test, $exam, 0, 0, 0 ,0 ,0)";
								$q = "INSERT INTO schplanitems (CodeOfSchPlanItem, CodeOfSchPlan, CodeOfDepart, ".
								"NumbOfSemestr, AuditH, LectInW, LabInW, PractInW, ".
								"KursWork, KursPrj, Test, Exam, ControlWork, RGR, CalcW, TestW, Synopsis) VALUES ".
								"($schiCode, $schCode, ".$planDis[$i]['depart'].", $sem, $auditH, ".
								"$lectinw, $labinw, $practinw, $kr, $kp, $test, $exam, 0, 0, 0 ,0 ,0)";
								
								//IIIIIIIIIIIIIIIIIIIIIIIIIIIIIIII
								echo "INSERT INTO schplanitems (CodeOfSchPlanItem, CodeOfSchPlan, CodeOfDepart, ".
								"NumbOfSemestr, AuditH, LectInW, LabInW, PractInW, ".
								"KursWork, KursPrj, Test, Exam, ControlWork, RGR, CalcW, TestW, Synopsis) VALUES ".
								"($schiCode, $schCode, ".$planDis[$i]['depart'].", $sem, $auditH, ".
								"$lectinw, $labinw, $practinw, $kr, $kp, $test, $exam, 0, 0, 0 ,0 ,0)<br><br>";
								//IIIIIIIIIIIIIIIIIIIIIIIIIIIIIIII
								
								FetchQuery($q);
							}
							$schiCode++;
							
						}
					}
					$i++;
					$schCode++;
				}
				
			}

		}
	}

//    }
?>
<script type="text/javascript">
	alert("Импорт успешно завершен!");
</script>
<?php
	
    }	
?>
	</table>
<?php
    include("../Display/FinishPage.php");
?>