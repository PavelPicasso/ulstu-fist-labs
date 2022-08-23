<?php
	include("../PlanCalculatFunc.php");
	CreateConnection();

	include("../Display/StartPage.php");
	include("../Display/ValidationForm.php");
	include("../Display/DisplayFunc.php");

	if (empty($stCode)) {
	        if (!empty($Spz)) 
			$Standard = FetchFirstRow("select spz.SpzName as MinName, spz.MinistryCode as MinCode FROM specializations spz WHERE spz.CodeOfSpecialization='$Spz'");
		elseif (!empty($Spec))
			$Standard = FetchFirstRow("select spc.SpcName as MinName, spc.MinistryCode as MinCode FROM specials spc WHERE spc.CodeOfSpecial='$Spec'");
		elseif (!empty($Dir)) 
			$Standard = FetchFirstRow("select dr.DirName as MinName, dr.MinistryCode as MinCode FROM directions dr WHERE dr.CodeOfDirect='$Dir'");
		
		$Standard = array_merge($Standard, array("TotalHours"=>8262, "TotalVolume"=>0, "TheorlTeach"=>153, "Exams"=>19, "Attestation"=>0, "Vacation"=>44, "LimitFile"=>'', "DiplomProjection"=>12, "TeachPractice"=>4, "WorkPractice"=>4, "DiplomPractice"=>6, "HoursInW"=>27, "MeanHoursInW"=>23, "KursWP"=>10, "MeanKursWP"=>2, "StDate"=>0, "CodeOfDirect"=>$Dir, "CodeOfSpecial"=>$Spec, "CodeOfSpecialization"=>$Spz, "CodeOfPlan"=>0));
		$day = date("d");
		$month = date("m");
		$year = date("Y");
		DisplayPageTitle("","Добавление нового стандарта", "$Standard[MinName] $Standard[MinCode]");
	}
	else {
		$StCode = $stCode;

		$Standard = FetchFirstRow("select IFNULL(IFNULL(spz.SpzName,spc.SpcName),dr.DirName) as MinName, IFNULL(IFNULL(spz.MinistryCode,spc.MinistryCode),dr.MinistryCode) as MinCode, s.* from specialslimit s LEFT JOIN directions dr on dr.CodeOfDirect = s.CodeOfDirect LEFT JOIN specials spc on spc.CodeOfSpecial=s.CodeOfSpecial LEFT JOIN specializations spz on spz.CodeOfSpecialization=s.CodeOfSpecialization where s.CodeOfStandard=$StCode");

		$day = substr($Standard["StDate"],8,2);
		$month = substr($Standard["StDate"],5,2);
		$year = substr($Standard["StDate"],0,4);
		DisplayPageTitle("","Редактирование данных стандарта", "$Standard[MinName] $Standard[MinCode]");
	}
?>
<FORM METHOD='post' NAME='UpdLimits' ACTION=''>
<br>
<table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center" WIDTH='100%'>
<tr><td cellpadding="0" cellspacing="0">
<TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
<TR>
<TH><strong>Всего часов обучения</strong></TH>
<TH><strong>Общий объем занятий (недели)</strong></TH>
<TH><strong>Теоретическое обучение (недели)</strong></TH>
<TH><strong>Экзаменационная сессия (недели)</strong></TH>
<TH><strong>Итоговая аттестация (недели)</strong></TH>
<TH><strong>Каникулы (недели)</strong></TH>
</TR>
<TR>
<?php
	echo " <TD align='center'><INPUT TYPE=TEXT NAME=\"TotalHour\" VALUE=\"$Standard[TotalHours]\"  SIZE='4' MAXLENGTH=4 onChange=\"Validator(this)\"></TD>\n";
	echo " <TD align='center'><INPUT TYPE=TEXT NAME=\"TotalVolume\" VALUE=\"$Standard[TotalVolume]\"  SIZE='3' MAXLENGTH=3 onChange=\"Validator(this)\"></TD>\n";
	echo " <TD align='center'><INPUT TYPE=TEXT NAME=\"TheorlTeach\" VALUE=\"$Standard[TheorlTeach]\"  SIZE='3' MAXLENGTH=3 onChange=\"Validator(this)\"></TD>\n";
	echo " <TD align='center'><INPUT TYPE=TEXT NAME=\"Exams\" VALUE=\"$Standard[Exams]\"  SIZE='3' MAXLENGTH=3 onChange=\"Validator(this)\"></TD>\n";
	echo " <TD align='center'><INPUT TYPE=TEXT NAME=\"Attestation\" VALUE=\"$Standard[Attestation]\"  SIZE='3' MAXLENGTH=3 onChange=\"Validator(this)\"></TD>\n";
	echo " <TD align='center'><INPUT TYPE=TEXT NAME=\"Vacation\" VALUE=\"$Standard[Vacation]\"  SIZE='3' MAXLENGTH=3 onChange=\"Validator(this)\"></TD>\n";
	echo " </TR></TABLE></TD></TR></TABLE><BR>\n";
?>
<table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center" WIDTH='100%'>
<tr><td cellpadding="0" cellspacing="0">
<TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
<TR>
<TH><strong>Дипломное проектирование</strong></TH>
<TH><strong>Учебная практика</strong></TH>
<TH><strong>Производственно-технологическая практика</strong></TH>
<TH><strong>Преддипломная практика</strong></TH>
<TH><strong>Объем аудиторных занеятий в неделю</strong></TH>
<TH><strong>Общее число курсовых проектов и работ</strong></TH>
<TH><strong>Средненедельное число аудиторных работ</strong></TH>
<TH><strong>Количество курсовых работ и проектов в семестре</strong></TH>
</TR>
<TR>
<?php
	echo " <TD align='center'><INPUT TYPE=TEXT NAME=\"DiplomProjection\" VALUE=\"$Standard[DiplomProjection]\"  SIZE='3' MAXLENGTH=3 onChange=\"Validator(this)\"></TD>\n";
	echo " <TD align='center'><INPUT TYPE=TEXT NAME=\"TeachPractice\" VALUE=\"$Standard[TeachPractice]\"  SIZE='4' MAXLENGTH=4 onChange=\"Validator(this)\"></TD>\n";
	echo " <TD align='center'><INPUT TYPE=TEXT NAME=\"WorkPractice\" VALUE=\"$Standard[WorkPractice]\"  SIZE='3' MAXLENGTH=3 onChange=\"Validator(this)\"></TD>\n";
	echo " <TD align='center'><INPUT TYPE=TEXT NAME=\"DiplomPractice\" VALUE=\"$Standard[DiplomPractice]\"  SIZE='3' MAXLENGTH=3 onChange=\"Validator(this)\"></TD>\n";
	echo " <TD align='center'><INPUT TYPE=TEXT NAME=\"HoursInW\" VALUE=\"$Standard[HoursInW]\"  SIZE='3' MAXLENGTH=3 onChange=\"Validator(this)\"></TD>\n";
	echo " <TD align='center'><INPUT TYPE=TEXT NAME=\"KursWP\" VALUE=\"$Standard[KursWP]\"  SIZE='3' MAXLENGTH=3 onChange=\"Validator(this)\"></TD>\n";
	echo " <TD align='center'><INPUT TYPE=TEXT NAME=\"MeanHoursInW\" VALUE=\"$Standard[MeanHoursInW]\"  SIZE='3' MAXLENGTH=3 onChange=\"Validator(this)\"></TD>\n";
	echo " <TD align='center'><INPUT TYPE=TEXT NAME=\"MeanKursWP\" VALUE=\"$Standard[MeanKursWP]\"  SIZE='3' MAXLENGTH=3 onChange=\"Validator(this)\"></TD>\n";
	echo " </TR></TABLE></TD></TR></TABLE><BR>\n";
?>
<?php
	echo " <table  class='ramka' border=\"0\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\" WIDTH='100%'>\n";
	echo " <tr><td cellpadding=\"0\" cellspacing=\"0\">\n";
	echo " <TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>\n";
	echo " <TR>\n";
	echo " <TH><strong>План стандарта</strong></TH>\n";
	echo " <TH><strong>Дата утверждения</strong></TH>\n";
	echo " <TH><strong>Файл стандарта</strong></TH>\n";
	echo " <TH><strong>Файлы типовых планов</strong></TH>\n";
	echo " </TR>\n";
	echo " <TR>\n";

	echo " <TD align='center'>\n";
	echo " <SELECT NAME=\"CodeOfPlan\" style=\"width:250px;\">\n";
	echo " <OPTION VALUE='0'>Без плана";
	$Plans = FetchArrays("SELECT * FROM plans WHERE CodeOfSpecial='$Standard[CodeOfSpecial]' and CodeOfSpecialization='$Standard[CodeOfSpecialization]' and CodeOfDirect='$Standard[CodeOfDirect]'");

	foreach ($Plans as $k=>$Plan) {
		echo " <OPTION VALUE='$Plan[CodeOfPlan]'";
		if ($Standard["CodeOfPlan"] == $Plan["CodeOfPlan"])
			echo " SELECTED";
		echo ">$Plan[PlnName]";
	}
	echo " </SELECT>\n";
	echo " </TD>\n";
	echo " <TD align='center'>\n";
	echo " <INPUT TYPE=TEXT NAME=\"day\" VALUE=\"$day\"  SIZE='2' MAXLENGTH=2>\n";
	echo " <INPUT TYPE=TEXT NAME=\"month\" VALUE=\"$month\"  SIZE='2' MAXLENGTH=2>\n";
	echo " <INPUT TYPE=TEXT NAME=\"year\" VALUE=\"$year\"  SIZE='4' MAXLENGTH=4>\n";
	echo " </TD>\n";
	echo " <TD align='center'><INPUT TYPE=TEXT NAME=\"LimitFile\" VALUE=\"$Standard[LimitFile]\"  SIZE='20' MAXLENGTH=20></TD>\n";
	echo " <TD ALIGN='CENTER'><TEXTAREA ROWS=3 COLS=20 NAME=PlanFilesList>";
	if (!empty($StCode)) {
		$resTP = mysql_query("select * from standardplans where CodeOfStandard=".$StCode, $Connection)
			or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
		while ($rowTP = mysql_fetch_object($resTP)){
			echo "$rowTP->PlanFile\n";
		}
	}
	echo "</TEXTAREA></TD>\n";
	echo " </TR></TABLE></TD></TR></TABLE><BR>\n";
	if (!empty($StCode)) {

		echo " <CENTER><TABLE  align='center'><TR>\n";
		echo " <TD><CENTER>\n";
		echo " <INPUT TYPE='SUBMIT' NAME='OK' VALUE='Внести изменения' ONCLICK = \"UpdLimits.action='../Standard/DoEditStandard.php'\">\n";
		echo " </TD>\n";
		echo " <TD><CENTER>\n";
		echo " <INPUT TYPE='SUBMIT' NAME='Esk' VALUE='Вернуться к списку' ONCLICK = \"UpdLimits.action='../Standard/Standard.php'\">\n";
		echo " </TD>\n";
		echo " </TR><TR>";
		echo " <TD colspan=\"2\"><CENTER><INPUT TYPE='SUBMIT' NAME='EditSpc' VALUE='Редактировать направление, специальность, специализацию' onClick=\"UpdLimits.action='../Standard/ChoiseDirect.php'\">";
		echo " </CENTER></TD>";
		echo " </TR><TR>";
		echo " <TD colspan=\"2\"><CENTER><INPUT TYPE='BUTTON' NAME='EditDetails' VALUE='Детальное редактирование' onClick=\"javascript: self.location='../Standard/Detail.php?standard=$Standard[CodeOfStandard]'\">";
		echo " </CENTER></TD>";
		echo " <input type='hidden' name='shift' value='editSpc'>\n";
		echo " <INPUT TYPE=HIDDEN NAME=\"CodeOfStandard\" VALUE=\"$StCode\"  SIZE='4' MAXLENGTH=4>\n";
		echo "</TR></TABLE>";
	}
	else {
?>
<BR>
<TABLE  align='center'>
<TR>
<?php
		if (isset($shift)) 
			$sh = $shift;
		else
			$sh=0;

		echo "<TD><INPUT TYPE='RADIO' NAME='shift' VALUE='0' ";

		if($sh==0)
			echo "CHECKED ";
		echo ">Вернутся к справочнику стандартов";
		echo "</TD>";
		echo "<TD><INPUT TYPE='RADIO' NAME='shift' VALUE='1'";
		if($sh==1)
			echo "CHECKED ";
		echo ">Ввести несколько новых стандартов</TD>";
		echo "<input type='hidden' name='Dir' value='$Dir'>";
		echo "<input type='hidden' name='Spec' value='$Spec'>";
		echo "<input type='hidden' name='Spz' value='$Spz'>";
?>
</TR>
</TABLE>
<BR>
<TABLE  align='center'><TR>
<TD>&nbsp;&nbsp;&nbsp;&nbsp;
<INPUT TYPE='SUBMIT' NAME='OK' VALUE='Добавить стандарт' ONCLICK = "javascript: UpdLimits.action='DoAddStandard.php'">
&nbsp;&nbsp;&nbsp;&nbsp;</TD>
<TD>&nbsp;&nbsp;&nbsp;&nbsp;
<INPUT TYPE='BUTTON' NAME='Esk' VALUE='Вернуться к списку' ONCLICK = "javascript: self.location='Standard.php'">
&nbsp;&nbsp;&nbsp;&nbsp;</TD>
</TR>
</TABLE>
<?php
	}
	mysql_close($Connection);
?>
</CENTER>
</FORM>
<HR>
<?php
	include("../Display/FinishPage.php");
?>