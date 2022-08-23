<?php
	if (empty($standard)) {
	     Header ("Location: Standard.php");
	     exit;
	}
	include("../PlanCalculatFunc.php");
	CreateConnection();
	$Standard = FetchFirstRow("select IFNULL(IFNULL(spz.SpzName,spc.SpcName),dr.DirName) as MinName, IFNULL(IFNULL(spz.MinistryCode,spc.MinistryCode),dr.MinistryCode) as MinCode, s.* from specialslimit s LEFT JOIN directions dr on dr.CodeOfDirect = s.CodeOfDirect LEFT JOIN specials spc on spc.CodeOfSpecial=s.CodeOfSpecial LEFT JOIN specializations spz on spz.CodeOfSpecialization=s.CodeOfSpecialization where s.CodeOfStandard='$standard'");

	if (!empty($UpdatePlans) && $UpdatePlans == "Y" && is_array($CicleHours)) {
		$CiclesStr="0";
				
		foreach ($CicleHours as $k=>$v) {
			$CiclesStr .= ", $k";
			$CodeOfLimitCicle = FetchResult("SELECT CodeOfLimitCicle FROM LimitCicles WHERE CodeOfStandard='$standard' and CodeOfCicle='$k'");
			if (!empty($CodeOfLimitCicle))
				FetchQuery("UPDATE LimitCicles SET CicleHours='$v' WHERE CodeOfLimitCicle='$CodeOfLimitCicle'");
			else
				FetchQuery("INSERT INTO LimitCicles (CodeOfStandard, CodeOfCicle, CicleHours) VALUES ('$standard', '$k', '$v')");
		}
		//Удаление несуществующих в плане циклов
		FetchQuery("DELETE FROM LimitCicles WHERE CodeOfStandard = '$standard' AND CodeOfCicle NOT IN ($CiclesStr)");
		FetchQuery("DELETE FROM LimitDis WHERE CodeOfStandard = '$standard' AND CodeOfCicle NOT IN ($CiclesStr)");
		
		if (isset($DisHours) && is_array($DisHours) && !empty($cicle)) {
			$DisStr = "0";
			foreach($DisHours as $k=>$v) {
				$DisStr .= ", $k";
				$CodeOfLimitDis = FetchResult("SELECT CodeOfLimitDis FROM LimitDis WHERE CodeOfStandard='$standard' and CodeOfCicle='$cicle' and CodeOfDiscipline='$k'");
				if (!empty($CodeOfLimitDis))
					FetchQuery("UPDATE LimitDis SET DisHours='$v' WHERE CodeOfLimitDis='$CodeOfLimitDis'");
				else
					FetchQuery("INSERT INTO LimitDis (CodeOfDiscipline, CodeOfStandard, CodeOfCicle, DisHours) VALUES ('$k', '$standard', '$cicle', '$v')");
			}
			FetchQuery("DELETE FROM LimitDis WHERE CodeOfStandard = '$standard' AND CodeOfCicle = '$cicle' AND CodeOfDiscipline NOT IN ($DisStr)");
			
			if (! empty($Group) && !empty($GroupName) && !empty($GroupHours)){
				FetchQuery("INSERT INTO LimitGroups (CodeOfStandard, GroupName, GroupHours) VALUES ('$standard', '$GroupName', '$GroupHours')");
				$CodeOfGroup = FetchResult("SELECT CodeOfGroup FROM LimitGroups WHERE CodeOfStandard='$standard' and GroupName='$GroupName'");
				$GroupStr = implode(", ",$Group);
				FetchQuery("UPDATE LimitDis SET CodeOfGroup='$CodeOfGroup' WHERE CodeOfStandard='$standard' and CodeOfCicle='$cicle' and CodeOfDiscipline IN ($GroupStr)");
				
			}
			
			Header ("Location: Detail.php?standard=$standard&cicle=$cicle");
			exit;
		}

		Header ("Location: Detail.php?standard=$standard");
		exit;
	}
	if (!empty($UpdateGroups) and $UpdateGroups=="Y") {
		if ($DeleteGroups=="Y" and is_array($del))
			FetchQuery("DELETE FROM LimitGroups WHERE CodeOfGroup IN (".implode(", ",$del).")");
		
		elseif (is_array($Groups)) 
			foreach ($Groups as $k=>$v)
				FetchQuery("UPDATE LimitGroups SET GroupName='$v[GroupName]', GroupHours='$v[GroupHours]' WHERE CodeOfGroup='$k'");

			
		Header ("Location: Detail.php?standard=$standard");
		exit;
	}

	$Cicles =FetchArrays("Select distinct c.* from cicles c, schedplan sh where sh.CodeOfCicle = c.CodeOfCicle and sh.CodeOfPlan='$Standard[CodeOfPlan]'");

	$Cicles[] = array("CicName"=>"Без раздела", "CodeOfCicle"=>"0");
	include("../Display/StartPage.php");
	include("../Display/ValidationForm.php");
	include("../Display/DisplayFunc.php");

	DisplayPageTitle("","Редактировать детальные данные стандарта", "$Standard[MinName] $Standard[MinCode]");
	
?>
<FORM METHOD='post' NAME='EditStandard' ACTION='Detail.php'>
<table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center" width="70%">
<tr><td>
<TABLE  class='table' BORDER=1 ALIGN=CENTER CELLSPACING=1 CELLPADDING=3 WIDTH='100%'>
<TR ALIGN='CENTER' VALIGN='MIDDLE'>
<TH>&nbsp;</TH>
<TH width="85%"><strong>Название цикла</strong></TH>
<TH width="10%"><strong>Сумарный объем часов теоретического обучения</strong></TH>
</TR>
<?php
	foreach ($Cicles as $k=>$v) {
		$CicleHours = FetchResult("SELECT CicleHours FROM LimitCicles WHERE CodeOfStandard='$standard' AND CodeOfCicle='$v[CodeOfCicle]'");
		if (empty ($CicleHours))
			$CicleHours = 0;
		echo "<TR>\n";
		echo "<TD>&nbsp;</TD>\n";
		echo "<TD class='CicleTitle'><A href='Detail.php?standard=$standard&cicle=$v[CodeOfCicle]'>$v[CicName]</A></TD>\n";
		echo "<TD align='center'><INPUT TYPE=TEXT NAME=\"CicleHours[$v[CodeOfCicle]]\" VALUE=\"$CicleHours\"  SIZE='4' MAXLENGTH=4></TD>\n";
		echo "</TR>\n";
		if (!empty($cicle) && $v["CodeOfCicle"] == $cicle) {
			$Disciplins =FetchArrays("Select d.* from disciplins d, schedplan sh where d.CodeOfDiscipline = sh.CodeOfDiscipline and sh.CodeOfPlan='$Standard[CodeOfPlan]' and sh.CodeOfCicle='$cicle'");
			foreach ($Disciplins as $kd=>$vd) {
				$DisHours = FetchResult("SELECT DisHours FROM LimitDis WHERE CodeOfStandard='$standard' AND CodeOfCicle='$v[CodeOfCicle]' AND CodeOfDiscipline='$vd[CodeOfDiscipline]'");
				if (empty ($DisHours))
					$DisHours = 0;
				echo "<TR>\n";
				echo "<TD align='center'><INPUT TYPE='CHECKBOX' NAME='Group[$vd[CodeOfDiscipline]]' VALUE=\"$vd[CodeOfDiscipline]\"></TD>\n";
				echo "<TD>$vd[DisName]</TD>\n";
				echo "<TD align='center'><INPUT TYPE=TEXT NAME=\"DisHours[$vd[CodeOfDiscipline]]\" VALUE=\"$DisHours\"  SIZE='4' MAXLENGTH=4></TD>\n";
				echo "</TR>\n";
			}
		}
	}
	echo "<TR>\n";
	echo "<TD>&nbsp;</TD>\n";
	echo "<TD><INPUT TYPE=\"TEXT\" NAME=\"GroupName\"  SIZE=\"50\" MAXLENGTH=\"250\" VALUE=\"\"></TD>\n";
	echo "<TD align='center'><INPUT TYPE=TEXT NAME=\"GroupHours\" VALUE=\"\"  SIZE='4' MAXLENGTH=4></TD>\n";
	echo "</TR>\n";
?>
</TABLE>
</td>
</tr>
</table>
<BR>
<?php
	echo " <CENTER><TABLE  align='center'><TR>\n";
	echo " <TD><CENTER>\n";
	echo " <INPUT TYPE='SUBMIT' NAME='OK' VALUE='Внести изменения'>\n";
	echo " </TD>\n";
	echo " <TD><CENTER>\n";
	echo " <INPUT TYPE='SUBMIT' NAME='Esk' VALUE='Общие данные' ONCLICK = \"self.location='../Standard/EditStandard.php?stCode=$standard'\">\n";
	echo " </TD>\n";
	echo " <input type='hidden' name='shift' value='editSpc'>\n";
	echo " <INPUT TYPE=\"HIDDEN\" NAME=\"standard\" VALUE=\"$standard\">\n";
	echo " <INPUT TYPE=\"HIDDEN\" NAME=\"UpdatePlans\" VALUE=\"Y\">";
	if (!empty($cicle))
		echo " <INPUT TYPE=\"HIDDEN\" NAME=\"cicle\" VALUE=\"$cicle\">";
	echo "</TR></TABLE>";
?>
</FORM>
<HR>
<?php
	$Groups = FetchArrays("SELECT * FROM LimitGroups WHERE CodeOfStandard='$standard'");
	if (!empty($Groups)) {
?>
<H1>Группы дисциплин</H1>
<FORM METHOD='post' NAME='EditGroup' ACTION='Detail.php'>
<table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center" width="70%">
<tr><td>
<TABLE  class='table' BORDER=1 ALIGN=CENTER CELLSPACING=1 CELLPADDING=3 WIDTH='100%'>
<TR ALIGN='CENTER' VALIGN='MIDDLE'>
<TH>&nbsp;</TH>
<TH width="75%"><strong>Название группы</strong></TH>
<TH width="10%"><strong>Сумарный объем часов теоретического обучения</strong></TH>
<TH width="10%"><strong>Список дисциплин</strong></TH>
</TR>
<?php
	foreach ($Groups as $k=>$v) {
		echo "<TR>\n";
		echo "<TD align='center'><INPUT TYPE='CHECKBOX' NAME='del[$v[CodeOfGroup]]' VALUE=\"$v[CodeOfGroup]\"></TD>\n";
		echo "<TD><INPUT TYPE=TEXT NAME=\"Groups[$v[CodeOfGroup]][GroupName]\" VALUE=\"$v[GroupName]\"  SIZE='50' MAXLENGTH='250'></TD>\n";
		echo "<TD align='center'><INPUT TYPE=TEXT NAME=\"Groups[$v[CodeOfGroup]][GroupHours]\" VALUE=\"$v[GroupHours]\"  SIZE='4' MAXLENGTH=4></TD>\n";
		echo "<TD align='center'><A href=\"Detail.php?group=$v[CodeOfGroup]&standard=$standard\">Дисциплины</A></TD>\n";
		echo "</TR>\n";
		if (! empty($group) && $v["CodeOfGroup"] == $group) {
			$Disciplins =FetchArrays("Select d.* from disciplins d, LimitDis l where l.CodeOfGroup='$group' and d.CodeOfDiscipline=l.CodeOfDiscipline");
			foreach ($Disciplins as $kd=>$vd) {
				echo "<TR>\n";
				echo "<TD>&nbsp;</TD>\n";
				echo "<TD>$vd[DisName]</TD>\n";
				echo "<TD>&nbsp;</TD>\n";
				echo "<TD>&nbsp;</TD>\n";
				echo "</TR>\n";
			}
			
		}
	}

?>
</TABLE>
</td>
</tr>
</table>
<BR>
<TABLE  align='center'><TR>
<TD align="center"><INPUT TYPE='SUBMIT' NAME='OK' VALUE='Внести изменения'></TD>
<TD align="center"><INPUT TYPE='SUBMIT' NAME='OK' VALUE='Удалить отмеченные записи' ONCLICK="javascript: DeleteGroups.value='Y'"></TD>
</TR></TABLE>
<?php
	echo " <INPUT TYPE=\"HIDDEN\" NAME=\"standard\" VALUE=\"$standard\">\n";
?>
<INPUT TYPE="HIDDEN" NAME="UpdateGroups" VALUE="Y">
<INPUT TYPE="HIDDEN" NAME="DeleteGroups" VALUE="">
</FORM>
<HR>
<?php
	}
	mysql_close($Connection);
	include("../Display/FinishPage.php");
?>