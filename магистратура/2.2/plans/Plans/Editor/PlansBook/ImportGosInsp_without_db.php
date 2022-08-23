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
    <tr>
        <td></td>
        <td>
            <input type="submit" name="confirmInfo" value="Проанализировать план">
        </td>
    </tr>
	</form>
    </tr>

<?php

//var_dump($_POST);

if (isset($_POST['import']) && isset($_POST['importInfo'])) {
    //unset($_POST['confirmInfo']);
    $temp2 = unserialize(stripslashes($_POST['importInfo']));
    if (!is_array($temp2)) {
        // something went wrong, initialize to empty array
    }
    //var_dump($temp2);
}

if (isset($_POST['confirmInfo'])){
    //if (isset($_POST['import2']) && $_POST['import2']==2){
					$cafedras = FileToArray('depnames.txt');
					$fac_id = $_SESSION["statusCode"];
					$xml = new DOMDocument('1.0', 'windows-1251');
					if ($_FILES['filepath']['tmp_name'] == null) {
						echo "Не выбран файл";
						return;
					} 
					$xml->load($_FILES['filepath']['tmp_name']);
					$xpath = new DOMXpath($xml);
					$root = $xpath->query(iconv("cp1251", "utf-8", "/Документ"))->item(0);
					$data = array();
					$weeks = array();
					$rootChilds = $root->childNodes;
					foreach($rootChilds as $key0){
						//Элемент "План"
						//Из атрибутов выделяем ФормуОбучения
						foreach($key0->attributes as $attributeskey0){
							$attributeskey0Name = iconv("UTF-8", "cp1251", $attributeskey0->nodeName);
							$attributesvalue0 = iconv("UTF-8", "cp1251", $attributeskey0->nodeValue);
							if ($attributeskey0Name=="ФормаОбучения")
								$data['edform_ab'] = $attributesvalue0;
						}
								$res = FetchResult("Select max(CodeOfPlan) as id from plans");
						$plnCode = $res+1;

						//Проверяем вложенные элементы "Титул", "СтрокиПлана"
						
						$key0Childs = $key0->childNodes;
						foreach($key0Childs as $key1){
							$key1Name = iconv("UTF-8", "cp1251", $key1->nodeName); 
							if ($_POST['usk']=="usk") $usk = 1;
							else $usk = 0;

							if ($key1Name=="Титул"){
								foreach($key1->attributes as $attributeskey1){
									$attributeskey1Name = iconv("UTF-8", "cp1251", $attributeskey1->nodeName);
									$attributesvalue1 = iconv("UTF-8", "cp1251", $attributeskey1->nodeValue);
									if ($attributeskey1Name == "ГодНачалаПодготовки")
										$data['begin_year'] = $attributesvalue1;
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
									if ($key2Name=="Утверждение"){
										foreach($key2->attributes as $attributeskey2){
											$attributeskey2Name = iconv("UTF-8", "cp1251", $attributeskey2->nodeName);
											$attributesvalue2 = iconv("UTF-8", "cp1251", $attributeskey2->nodeValue);
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
									
									if ($key2Name == "Специальности") {
										$key2Childs = $key2->childNodes;
										$data['spesialnost'] = "";
										foreach ($key2Childs as $key3) {
											foreach($key3->attributes as $attributeskey3){
												$attributeskey3Name = iconv("UTF-8", "cp1251", $attributeskey3->nodeName);
												$attributesvalue3 = iconv("UTF-8", "cp1251", $attributeskey3->nodeValue);
												if ($attributeskey3Name == "Название") {
													$data['spesialnost'] .= $attributesvalue3;
												}
											}
											$data['spesialnost'] .= "<br>";
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
                                    else {
                                        $res = FetchResult("Select max(CodeOfSpecial) as id from specials");
                                        $spcCode = $res+1;
                                    }
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
										$res = FetchResult("Select max(CodeOfSchPlanItem) as id from schplanitems");
										$schiCode = $res+1;

										$key2Childs = $key2->childNodes;
										foreach($key2Childs as $key3){
											$key3Name = iconv("UTF-8", "cp1251", $key3->nodeName);
											if ($key3Name == "Сем"){
												$lect = $pract = $lab = 0;
												foreach($key3->attributes as $attributeskey3){
													$attributeskey3Name = iconv("UTF-8", "cp1251", $attributeskey3->nodeName);
													$attributesvalue3 = iconv("UTF-8", "cp1251", $attributeskey3->nodeValue);
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
											}
											$schiCode++;
											
										}
									}
									$i++;
									$schCode++;
								}
								
							}

						}
                        ?>
                        <form action="" method="post" enctype="multipart/form-data">
                            <tr>
                                <td></td>
                                <td>
                                    <b>Сведения</b> из исходного xml-файла:
                                    <table width=90% cellpadding=0 cellspacing=0 border=0 align="center">
                                        <tr>
                                            <td>
                                                <?php echo $data['spesialnost'];?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Год начала подготовки - <?php echo $data['begin_year'];?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Срок обучания (лет) - <?php echo $data['years'];?>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr><td></td>
                                <td>
                                    <b>Введите</b> дополнительные данные для плана в формате системы "Учебные планы"
                                    <table width=90% cellpadding=0 cellspacing=0 border=0 align="center">
                                        <tr>
                                            <td>
                                                Введите&nbsp;название&nbsp;плана:&nbsp;
                                            </td>
                                            <td width=100%>
                                                <?php
                                                $plan_name = 'План подготовки ';
                                                switch ($data['kval']) {
                                                    case 'bakalavr':
                                                        $plan_name .= 'бакалавров';
                                                        break;
                                                    case 'magistr':
                                                        $plan_name .= 'магистров';
                                                        break;
                                                    case 'enginer':
                                                        $plan_name .= 'инженеров';
                                                        break;
                                                    default:
                                                        break;
                                                }
                                                $plan_name .= ' '.$data['begin_year'];
                                                ?>
                                                <input type="text" name="plnName" value="<?php echo $plan_name;?>" size="50">
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
                                        <tr>
                                            <td>
                                                <input type="submit" name="import" value="Импорт плана">
                                                <?php
                                                    $temp = serialize ($data);
                                                    //echo $temp."<br>";
                                                    //$temp2 = unserialize($temp);
                                                    //var_dump($temp2);
                                                ?>
                                                <input type="hidden" name="importInfo" value='<?= $temp ?>'>
                                            </td>
                                        </tr>


                                    </table>
                                </td>
                            </tr>
                        </form>
                        </table>

                        <?php
					}
				//}
				?>
				
				
<!--script type="text/javascript">
	alert("Импорт успешно завершен!");
</script-->
<?php
	
    //}
?>

<?php
    include("../Display/FinishPage.php");
?>