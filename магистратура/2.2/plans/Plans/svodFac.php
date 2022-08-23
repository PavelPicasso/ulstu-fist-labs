<?php
    set_time_limit(60*3);   
    include("cfg.php");
    include("Editor/PlanCalculatFunc.php");
    CreateConnection();
    $name_trans = "Свод_факультет";
    $facname = "";	
?>
<HTML>
<HEAD>
<TITLE>Сводная таблица дисциплин, которые проводятся на кафедрах факультет</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<link rel="stylesheet" href="../CSS/Plans.css" type="text/css"></HEAD>

<BODY topmargin="2" leftmargin="2" marginheight="2" marginwidth="2" >
<br>
<form action="svodFac.php" method="get">
<table border=0 width="90%" align="center">
<tr>
<td colspan=2><strong>Выберите факультет</strong>&nbsp;&nbsp;<select name="fac">
<?php
    $time_b = getmicrotime();
    $Facs = FetchArrays("select Reduction, CodeOfFaculty from faculty order by CodeOfFaculty");
    $time_e = getmicrotime();	
    foreach ($Facs as $k => $v){
	if ($_GET['fac']==$v[CodeOfFaculty]){
		echo "<OPTION value='$v[CodeOfFaculty]' selected>$v[Reduction]</OPTION>\n";
		$facname = $v[Reduction];
	}
	else
        	echo "<OPTION value='$v[CodeOfFaculty]'>$v[Reduction]</OPTION>\n";
    }
?>
</SELECT>
<strong>Семестр</strong>&nbsp;&nbsp;<select name="sem">
<?php
	if ($_GET['sem']==1){
		echo "<OPTION value='1' selected>осенний</OPTION>\n";
		echo "<OPTION value='2'>весенний</OPTION>\n";		
	}
	else{
        	echo "<OPTION value='1'>осенний</OPTION>\n";
		echo "<OPTION value='2' selected>весенний</OPTION>\n";
	}
   
?>
</SELECT>
&nbsp;&nbsp;<input type="submit" value="Сформировать отчет">
</form>
</td>
<td><a  href="help/departs.html" target="help" onClick='window.open("","help","menubar=no,scrollbars=yes,height=350,width=450")'><img src="img/help.gif" width=28 height=28 hspace=0 vspace=0 border=0 align="right" alt="Помощь"></a></td>
</tr>
<tr>
	<td colspan=3>&nbsp;</td>
</tr>
<?php
	if (!empty($_GET['fac'])){
?>
<tr>
<td align="center" width="10%">&nbsp;</td>
<td align="center" width="80%"><em class="h1">Сводная таблица дисциплин, которые проводятся на кафедрах факультета <?=$facname?></em></td>
<td align="center" width="10%">&nbsp;</td>
</tr>
<?php
	
	} 
?>
</table>
<br>
<?php
	if (!empty($_GET['fac'])){
?>
<table width="100%" border=0 cellpadding=0 cellspacing=0>
<tr>
<td>
<table  class='ramka' border="0" cellpadding="0" cellspacing="0" width="60%" align="left">
<tr><td cellpadding="0" cellspacing="0">
<TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
<?php
    	$date_trans = date("Y-m-d h:i:s");
    	$time_b = getmicrotime();
	if ($_GET['sem']==1	)
	    	$q = "select distinct MinistryCode, SpcName from specials as s 
			left join plans as pl on pl.CodeOfSpecial=s.CodeOfSpecial 
			left join schedplan as sch on sch.CodeOfPlan=pl.CodeOfPlan
			left join schplanitems as si on sch.CodeOfSchPlan=si.CodeOfSchPlan
			left join department as d on sch.CodeOfDepart=d.CodeOfDepart
				where d.CodeOfFaculty='$_GET[fac]' and (pl.FixDate is NULL or pl.FixDate='0000-00-00') 
			and NumbOfSemestr in (1,3,5,7,9,11)
			order by MinistryCode";
	else
	    	$q = "select distinct MinistryCode, SpcName from specials as s 
			left join plans as pl on pl.CodeOfSpecial=s.CodeOfSpecial 
			left join schedplan as sch on sch.CodeOfPlan=pl.CodeOfPlan
			left join schplanitems as si on sch.CodeOfSchPlan=si.CodeOfSchPlan
			left join department as d on sch.CodeOfDepart=d.CodeOfDepart
				where d.CodeOfFaculty='$_GET[fac]' and (pl.FixDate is NULL or pl.FixDate='0000-00-00') 
			and NumbOfSemestr in (2,4,6,8,10,12)
			order by MinistryCode";

    	$Specials = FetchArrays($q); 
    	foreach ($Specials as $k=>$v) {
        	echo "<tr>\n";
        	echo "<td  align='left'>&nbsp;$v[MinistryCode]</td>\n";
        	echo "<td  align='left'>$v[SpcName]&nbsp;</td>\n";
        	echo "</tr>\n"; 
    	}
    	$time_e = getmicrotime();
    	$time_all = $time_e-$time_b;
?>
</TABLE></td>
</tr>
</table>

</td>
</tr>
<tr>
<td>
<br>
<table class='ramka' border=0 cellpadding=0 cellspacing=0 width=100% align=left>
<tr><td>
<table class='table' border=0 align=center cellpadding=1 cellspacing=1 width=100%>
<tr>
	<td align=center width=10% rowspan=2>Спец-ть</td>
	<td align=center width=5% rowspan=2>Семестр</td>
        <td align=center width=5% rowspan=2>Кол-во групп</td>
	<td align=center width=5% rowspan=2>Кафедра</td>
	<td align=center width=45% rowspan=2>Дисциплина</td>
	<td align=center width=20% colspan=3>Виды занятий и количество часов, планируемых на них</td>
	<td align=center width=10% colspan=2>Отчетность</td>
</tr>
<tr>
	<td align=center>Лек.</td>
	<td align=center>Пр.</td>
	<td align=center>Лаб.</td>
	<td align=center>Экз.</td>
	<td align=center>Зач.</td>
</tr>
<?php
    	$date_trans = date("Y-m-d h:i:s");
    	$time_b = getmicrotime();
	if ($_GET['sem']==1)
    		$q = "SELECT pl.CodeOfPlan, DisName, NumbOfSemestr, LectSem, PractSem, LabSem, test, exam, sp.MinistryCode, dep.Reduction 
			FROM `schedplan` as sch 
			left join plans as pl on sch.CodeOfPlan=pl.CodeOfPlan 
			left join disciplins as d on sch.CodeOfDiscipline=d.CodeOfDiscipline
			left join schplanitems as si on sch.CodeOfSchPlan=si.CodeOfSchPlan 
			left join specials as sp on pl.CodeOfSpecial=sp.CodeOfSpecial 
			left join department as dep on sch.CodeOfDepart=dep.CodeOfDepart
			WHERE dep.CodeOfFaculty='$_GET[fac]' and (pl.FixDate is NULL or pl.FixDate='0000-00-00') and NumbOfSemestr in (1,3,5,7,9,11)
			order by sp.MinistryCode, dep.Reduction, NumbOfSemestr, DisName";
	else
    		$q = "SELECT pl.CodeOfPlan, DisName, NumbOfSemestr, LectSem, PractSem, LabSem, test, exam, sp.MinistryCode, dep.Reduction 
			FROM `schedplan` as sch 
			left join plans as pl on sch.CodeOfPlan=pl.CodeOfPlan 
			left join disciplins as d on sch.CodeOfDiscipline=d.CodeOfDiscipline
			left join schplanitems as si on sch.CodeOfSchPlan=si.CodeOfSchPlan 
			left join specials as sp on pl.CodeOfSpecial=sp.CodeOfSpecial 
			left join department as dep on sch.CodeOfDepart=dep.CodeOfDepart
			WHERE dep.CodeOfFaculty='$_GET[fac]' and (pl.FixDate is NULL or pl.FixDate='0000-00-00') and NumbOfSemestr in (2,4,6,8,10,12)
			order by sp.MinistryCode, dep.Reduction, NumbOfSemestr, DisName";

    	$Specials = FetchArrays($q); 
    	foreach ($Specials as $k=>$v) {
		$q = "Select GroupCount from streams as st left join plans as pl on st.CodeOfPlan=pl.CodeOfPlan 
			where st.CodeOfPlan='$v[CodeOfPlan]' and round($v[NumbOfSemestr]/2)=Kurs";
		$Groups = FetchArrays($q);
		if ($Groups){
        	echo "<tr>\n";
        	echo "<td  align='center'>&nbsp;$v[MinistryCode]</td>\n";
        	echo "<td  align='center'>&nbsp;$v[NumbOfSemestr]</td>\n";
        	echo "<td  align='center'>".$Groups[0]['GroupCount']."</td>\n";
        	echo "<td  align='center'>$v[Reduction]</td>\n";
        	echo "<td  align='left'>&nbsp;$v[DisName]</td>\n";
        	echo "<td  align='center'>&nbsp;$v[LectSem]</td>\n";
        	echo "<td  align='center'>&nbsp;$v[PractSem]</td>\n";
        	echo "<td  align='center'>&nbsp;$v[LabSem]</td>\n";
		if ($v['exam']==1)
        		echo "<td  align='center'>&nbsp;есть</td>\n";
		else
			echo "<td  align='center'>&nbsp;</td>\n";
		if ($v['test']==1)
        		echo "<td  align='center'>&nbsp;есть</td>\n";
		else
			echo "<td  align='center'>&nbsp;</td>\n";

        	echo "</tr>\n";
		} 
    	}
    	$time_e = getmicrotime();
    	$time_all = $time_e-$time_b;

?>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
<?php
	}
?>

</BODY>
</HTML>
<?php
     $id_sess = session_id();
      mysql_query("Insert into logs (name_trans, id_sess, date_trans, time_trans) values ('$name_trans', '$id_sess', '$date_trans', '$time_all')")
               or die("Unable to execute query:".mysql_errno().": ".mysql_error());
    mysql_close($Connection);
?>