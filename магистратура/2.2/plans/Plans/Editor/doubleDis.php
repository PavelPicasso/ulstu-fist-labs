<?php
   session_start();
   if (!(($_SESSION["status"] == 0)||($_SESSION["status"] == 2))){
      Header ("Location: ../Unreg.html");
      exit;
   }
   
   include("../cfg.php");
   include("PlanCalculatFunc.php");

   CreateConnection();
   if ($_POST['code'] && $_POST['reduct'] && $_POST['reduct']!=''){
		$disRed = substr($_POST['reduct'], 0, 30);
		$sql = "Update disciplins set DisReduction='$disRed' where CodeOfDiscipline=".$_POST['code'];
		FetchQuery($sql);
	      	Header ("Location: doubleDis.php");
      		exit;
		
   }

?>
<HTML>
<head>
<link rel="stylesheet" href="../CSS/PlansEditor.css" type="text/css">
</head>
<body>
<h1>Дисциплины, встречающиеся в БД более 1 раза</h1>
<table cellpaddind=0 cellspacing=0 class="borderTbl">
	<tr>
		<th>№</th>
		<th>Дисциплина</th>
		<th>Код кафедры</th>
		<th>Количество</th>
		<th>Состояние</th>		
	</tr>
<?php
   $sql = "SELECT DisName, CodeOfDepart, col
	   FROM (SELECT DisName, CodeOfDepart, (
			SELECT count( * )
			FROM disciplins disc
			WHERE discip.DisName = disc.DisName AND discip.CodeOfDepart = disc.CodeOfDepart) AS col
		FROM disciplins discip) ds
	   WHERE ds.col >=2
	   GROUP BY DisName, CodeOfDepart, col";
   $result = mysql_query($sql, $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   $i = 1;
   while ($row = mysql_fetch_object($result)){
	$sql = "Select CodeOfDiscipline, DisReduction FROM disciplins where DisName='".$row->DisName."' and CodeOfDepart=".$row->CodeOfDepart." order by DisReduction desc";
        $res = FetchArrays($sql);
       	$first = -1;

?>
	<tr>
		<td><b><?php echo $i?></b></td>		
		<td><?php echo $row->DisName?></td>
		<td><?php echo $row->CodeOfDepart?></td>		
		<td><?php echo $row->col?></td>
		<td>исправлено</td>		
	</tr>
<?php
	foreach ($res as $val){
		if ($first==-1){ 
			$first = $val['CodeOfDiscipline'];
		}else{
			$sql = "Select CodeOfSchPlan FROM schedplan WHERE CodeOfDiscipline=".$val['CodeOfDiscipline'];
			$disList = FetchArrays($sql);
			foreach($disList as $sched_dis){
				$sql ="UPDATE schedplan set CodeOfDiscipline=$first where CodeOfSchPlan=".$sched_dis['CodeOfSchPlan']." and CodeOfDiscipline=".$val['CodeOfDiscipline'];	
                            	FetchQuery($sql);
			}			
			$sql = "Delete from disciplins where CodeOfDiscipline=".$val['CodeOfDiscipline'];
			FetchQuery($sql);      
		}
		
	}       

	$i++;
   }
?>
</table>
<h1>В результате исключения повторений дисциплин в БД было обнаружено, что нет сокращенного названия для следующих дисциплин:</h1>
<table cellpadding=0 cellspacing=0 class="borderTbl">
<tr>
	<th>№</th>
	<th>Код дисциплины</th>
	<th>Дисциплина</th>
</tr>
<?php 
	$DisRedList = array();
	$sql = "Select CodeOfDiscipline, DisName FROM disciplins WHERE (DisReduction='' or DisReduction is null)";
        $DisRedList = FetchArrays($sql);
	foreach($DisRedList as $key=>$red){
		echo "<tr><td>".($key+1)."</td><td>".$red['CodeOfDiscipline']."</td><td><a href='doubleDis.php?code=".$red['CodeOfDiscipline']."'>".$red['DisName']."</a></td></tr>";
		if ($_GET['code'] && $_GET['code']==$red['CodeOfDiscipline']){
		        $reduct = DiscipRediction($red['DisName']);
/*                        $words = preg_split("/[\s]+/", $red['DisName']);
                	$reduct = "";
                	if (count($words)>1){ 
                		foreach($words as $num=>$word){
                			$str = trim($word);
                			if (strlen($str)>4){
	                			$str = strtoupper($str);	
                				$word = substr($str,0,1);
					}
                			else
                				$word = $str;
                			$reduct .= $word;
                		}  
                	}
                	else $reduct = $red['DisName'];   */
			
?>              <tr>
		<td colspan=3>
			<form action="" method="post">
				<b>Сокращенное название дисциплины:</b>
				<input type="text" name="reduct" value="<? echo $reduct?>" size="100">
				<br>
				<input type="submit" value="Сохранить">
				<input type="hidden" name="code" value="<? echo $_GET['code']?>">
			</form>
		</td>
<?php
			
		}
	}
?>
</table>
</body>
</HTML>