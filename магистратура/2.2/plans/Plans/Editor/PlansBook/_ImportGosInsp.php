<?php                                   
    include("../Display/DisplayFunc.php");

    include("../PlanCalculatFunc.php");
    CreateConnection();
    include("../Display/StartPage.php");
?>      <br>
	<table width=80% border=0 cellpadding=2 cellspacing=2>
	<tr>
	<form action="" method="post" enctype="multipart/form-data">
	
		<td colspan=2><b><u>�������� xml-�����</u></b></td>
	</tr>
	<tr>
		<td></td>
		<td>
	 		<b>������� ���� � �����</b> (������� ������ "�����" � �������� ���� � ����������� *.xml)
 			<input type="file" name="filepath" size="40">
		</td>	
	</tr>
	<tr><td></td>
	    <td>
		<b>�������</b> ��������� ����������
	 	<table width=90% cellpadding=0 cellspacing=0 border=0 align="center">
		<tr>
			<td>
				�������&nbsp;��������&nbsp;�����:&nbsp; 
			</td>
			<td width=100%>
				<input type="text" name="plnName" value="���� ���������� ��������� 2009" size="50">
			</td>
		</tr>
		<tr>
			<td>
				�������&nbsp;���������&nbsp;��������:&nbsp; 
			</td>
			<td>
				<select name="usk">
						<option value="poln">������
						<option value="usk">����������
				</select>
			</td>
		</tr>
		<tr>
			<td>
				�������&nbsp;�����&nbsp;��������:&nbsp; 
			</td>
			<td>
				<select name="edu">
				<option value="classroom">�����
					<option value="night">����-�������
					<option value="correspondence">�������
					<option value="external">���������
				</select>
		        </td>
		</tr>
		<tr>
			<td>
				�������&nbsp;��������&nbsp;������:&nbsp; 
			</td>
			<td>
				<input type="text" name="stream" size="10" value="���������">&nbsp;(�������� ����, ���, ��� � �.�.)
		        </td>
		</tr>

		</table>
	    </td>
	</tr>
	<tr><td></td>
	    <td>
		<input type="submit" value="������ ����� �� GosInsp">
		<input type="hidden" name="import" value="2">
	</form>
			
	</td></tr>

<?php
    if (isset($_POST['import']) && $_POST['import']==2){
	$cafedras = FileToArray('depnames.txt');
	$fac_id = $_SESSION["statusCode"];
	$xml =  simplexml_load_file($_FILES['filepath']['tmp_name']);
	$data = array();
	$weeks = array();
	foreach($xml as $key0 => $value0){
		//������� "����"
		//�� ��������� �������� �������������
		foreach($value0->attributes() as $attributeskey0 => $attributesvalue0){
			$attributeskey0 = iconv("UTF-8", "cp1251", $attributeskey0);
                        $attributesvalue0 = iconv("UTF-8", "cp1251", $attributesvalue0);
			if ($attributeskey0=="�������������")
				$data['edform_ab'] = $attributesvalue0;
		}
                $res = FetchResult("Select max(CodeOfPlan) as id from plans");
		$plnCode = $res+1;

		//��������� ��������� �������� "�����", "�����������"
		foreach($value0 as $key1=>$value1){
			$key1 = iconv("UTF-8", "cp1251", $key1); 

			if ($_POST['usk']=="usk") $usk = 1;
			else $usk = 0;

			if ($key1=="�����"){
				foreach($value1->attributes() as $attributeskey1 => $attributesvalue1){
					$attributeskey1 = iconv("UTF-8", "cp1251", $attributeskey1);
                		        $attributesvalue1 = iconv("UTF-8", "cp1251", $attributesvalue1);
					if ($attributeskey1=="�������������")
						$data['code'] = $attributesvalue1;
					if ($attributeskey1=="����������")
						$data['cafedra'] = $attributesvalue1;
					if ($attributeskey1=="���������"){
						$data['faculty'] = $attributesvalue1;
						if ($data['faculty']=='���') $data['faculty'] = '����';
						if ($data['faculty']=='�� � ���' || $data['faculty']=='��� � ���' || $data['faculty']=='������') $data['faculty'] = '�������';
						$resFac = FetchResult("Select CodeOfFaculty as id from faculty where Reduction='".$data['faculty']."'");
					        if (!$resFac) $data['faculty'] = 0;
						else $data['faculty'] = $resFac;
					}
					
					if ($attributeskey1=="��������������")
						$data['filedate'] = $attributesvalue1;
				}
				foreach($value1 as $key2=>$value2){
					$key2 = iconv("UTF-8", "cp1251", $key2); 
					if ($key2=="�����������"){
						foreach($value2->attributes() as $attributeskey2 => $attributesvalue2){
                                                	$attributeskey2 = iconv("UTF-8", "cp1251", $attributeskey2);
		                		        $attributesvalue2 = iconv("UTF-8", "cp1251", $attributesvalue2);
							if ($attributeskey2=="����")
								$data['date'] = $attributesvalue2;
						}
					}			
					if ($key2=="������������"){
						foreach($value2 as $key3=>$value3){
							foreach($value3->attributes() as $attributeskey3 => $attributesvalue3){
                                                		$attributeskey3 = iconv("UTF-8", "cp1251", $attributeskey3);
		                		        	$attributesvalue3 = iconv("UTF-8", "cp1251", $attributesvalue3);
								if ($attributeskey3=="��������"){
									$data['kval'] = $attributesvalue3;

	                                                        	$data['kval_code'] = '65';
									$kval = 'enginer';
									$data['degree'] = 2;
									if ($data['kval']=='�������'){
										$data['kval_code']='68';
										$kval = 'magistr';
									        $data['degree'] = 4;
									}
									elseif($data['kval']=='��������'){ 
										$data['kval_code']='62';
										$kval = 'bakalavr';
										$data['degree'] = 1;
									}
									elseif($data['kval']=='����������' || $data['kval']=='���������')
									        $data['degree'] = 3;
									$data['kval'] = $kval;
								}
								if ($attributeskey3=="������������")
									$data['years'] = $attributesvalue3;
							}
						}
					}
					if ($key2=="����������������"){
						$sched = array();
						foreach($value2 as $key3=>$value3){
							$kurs = 0;
							foreach($value3->attributes() as $attributeskey3 => $attributesvalue3){
                                                		$attributeskey3 = iconv("UTF-8", "cp1251", $attributeskey3);
		                		        	$attributesvalue3 = iconv("UTF-8", "cp1251", $attributesvalue3);
	                                                        if ($attributeskey3=="���")
									$kurs = $attributesvalue3;
	                                                        if ($attributeskey3=="���������")
									$sched[$kurs]['stud'] = $attributesvalue3;
	                                                        if ($attributeskey3=="�����")
									$sched[$kurs]['group'] = $attributesvalue3;
	                                                        if ($attributeskey3=="������")
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
				}

                  		$d = date("Y-m-d H:i:s");
	                        $q = "INSERT INTO plans (CodeOfPlan, CodeOfDirect, CodeOfSpecial, CodeOfSpecialization, ".
				"PlnName, Fast, Date, FixDate, YearCount, TeachForm) VALUES ($plnCode, 0, $spcCode, ".
				"0, '".$_POST['plnName']."', $usk, '".$data['date']." 00:00:00', '$d', ".$data['years'].", '".$_POST['edu']."')"; 
				FetchQuery($q);
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
					FetchQuery($q);
					$q = "INSERT INTO streams (StreamName, Kurs, GroupCount, StdCount, CodeOfPlan) VALUES ('".$_POST['stream'].
						"', $kurs, ".$schedule['group'].", ".$schedule['stud'].", $plnCode)";
					FetchQuery($q);
				}				


			}
			if ($key1=="�����������"){
				$planDis = array();
				$i = 0;
				$res = FetchResult("Select max(CodeOfSchPlan) as id from schedplan");
				$schCode = $res+1;

				foreach($value1 as $key2=>$value2){
					foreach($value2->attributes() as $attributeskey2 => $attributesvalue2){
                                                $attributeskey2 = iconv("UTF-8", "cp1251", $attributeskey2);
		                		$attributesvalue2 = iconv("UTF-8", "cp1251", $attributesvalue2);

						if ($attributeskey2=='���')
							$planDis[$i]['discip'] = $attributesvalue2;
						if ($attributeskey2=='����������������')
							$planDis[$i]['allH'] = $attributesvalue2;
						if ($attributeskey2=='��')
							$planDis[$i]['SR'] = $attributesvalue2;	
						if ($attributeskey2=='�������')
							$planDis[$i]['depart'] = $cafedras[$attributesvalue2-1];	
						if ($attributeskey2=='����������������������')
							$planDis[$i]['cicle'] = $attributesvalue2;
						if ($attributeskey2=='������')
							examForm($planDis,'exam', $i, $attributesvalue2);
						if ($attributeskey2=='������')
							examForm($planDis,'test', $i, $attributesvalue2);
						if ($attributeskey2=='�����')
							examForm($planDis,'kr', $i, $attributesvalue2);
	                                        if ($attributeskey2=='�����')
							examForm($planDis,'kp', $i, $attributesvalue2);
					}
					if ($planDis[$i]['discip'] && $planDis[$i]['depart'] && $planDis[$i]['cicle']){
						$disCode = DiscipCode($planDis[$i]['discip'],$planDis[$i]['depart']);
						$cicle = CiclesCode($planDis[$i]['cicle']);
						$q = "INSERT INTO schedplan (CodeOfSchPlan, CodeOfPlan, CodeOfDiscipline, CodeOfCicle, CodeOfUndCicle, ".
						"CodeOfDepart, UndCicCode, AllH, ToCount) VALUES ($schCode,$plnCode,$disCode,$cicle[0],$cicle[1],".
						$planDis[$i]['depart'].",'$cicle[2]',".$planDis[$i]['allH'].",1)";
						FetchQuery($q);
						$res = FetchResult("Select max(CodeOfSchPlanItem) as id from schplanitems");
						$schiCode = $res+1;

						foreach($value2 as $key3=>$value3){
							$key3 = iconv("UTF-8", "cp1251", $key3);
							if ($key3 == "���"){
								$lect = $pract = $lab = 0;
								foreach($value3->attributes() as $attributeskey3 => $attributesvalue3){
                                                			$attributeskey3 = iconv("UTF-8", "cp1251", $attributeskey3);
					                		$attributesvalue3 = iconv("UTF-8", "cp1251", $attributesvalue3);
									if ($attributeskey3=='���') $sem = $attributesvalue3;
									if ($attributeskey3=='���') $lect = $attributesvalue3;
									if ($attributeskey3=='��') $pract = $attributesvalue3;
                                                                        if ($attributeskey3=='���') $lab = $attributesvalue3;
								}
								$auditH = $lect+$pract+$lab;							
								$lectinw = ($weeks[$sem] && $weeks[$sem]!=0) ? (($lect!=0 && round($lect/$weeks[$sem])<1) ? 1: round($lect/$weeks[$sem])): 0;
								$practinw = ($weeks[$sem] && $weeks[$sem]!=0) ? (($pract!=0 && round($pract/$weeks[$sem])<1) ? 1: round($pract/$weeks[$sem])): 0;
								$labinw = ($weeks[$sem] && $weeks[$sem]!=0) ? (($lab!=0 && round($lab/$weeks[$sem])<1) ? 1: round($lab/$weeks[$sem])): 0;
								$exam = ($planDis[$i]['items'][$sem]['exam']) ? 1: 0;
								$test = ($planDis[$i]['items'][$sem]['test']) ? 1: 0; 
								$kr = ($planDis[$i]['items'][$sem]['kr']) ? 1: 0; 
								$kp = ($planDis[$i]['items'][$sem]['kp']) ? 1: 0; 
								$q = "INSERT INTO schplanitems (CodeOfSchPlanItem, CodeOfSchPlan, CodeOfDepart, ".
								"NumbOfSemestr, AuditH, LectSem, PractSem, LabSem, LectInW, LabInW, PractInW, ".
								"KursWork, KursPrj, Test, Exam, ControlWork, RGR, CalcW, TestW, Synopsis) VALUES ".
								"($schiCode, $schCode, ".$planDis[$i]['depart'].", $sem, $auditH, $lect, $pract, ".
								"$lab, $lectinw, $labinw, $practinw, $kr, $kp, $test, $exam, 0, 0, 0 ,0 ,0)";
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
	alert("������ ������� ��������!");
</script>
<?php
	
    }	
?>
	</table>
<?php
    include("../Display/FinishPage.php");
?>