<?php

include("../PlanCalculatFunc.php");
include("../Display/DisplayFunc.php");
include("../ReportEditor/ReportFunctions.php");

$dir = "../../../Functions";
$lib_file = "../../../lib/reportlib.php";
$local_variables = "../../../lib/locallib.php";
$global_variables = "../../../lib/globallib.php";
$tipical_vars = array(
	""=>"Новая",
	"diplom_proect"=>"Дипломное проектирование",
	"GEK"=>"ГЭК",
	"teach_practice"=>"Учебная практика",
	"work_practice"=>"Производственная практика",
	"diplom_practice"=>"Преддипломная практика",
	"test_work_review"=>"Рецензирование контр. работ",
	"test_visit"=>"Контрольное посещение",
	"facultative_lessons"=>"Факультативные занятия",
	"science_practice"=>"Научно-педагогич.практика",
	"science_work_guide"=>"Руков. научной работой"

);

//Объявление изначально доступных перемнных
//при расчете штатов

$common_params = array();

$plan_params = array(
	"students"=>"Количество студентов",
	"groups"=>"Количество групп",
	"term"=>"Номер семестра",
	"kurs"=>"Номер курса",
	"lec"=>"Часы лекций",
	"lab"=>"Часы лабораторных работ",
	"pract"=>"Часы практик",
	"ex"=>"Экзамен (1-есть, 0-нет)",
	"test"=>"Зачет",
	"kp"=>"Курсовой проект",
	"kw"=>"Курсовая работа",
	"rgr"=>"расчетно-графические работы",
	"ref"=>"рефераты",
	"wc"=>"количество недель обучения"
);

$REQUEST_METHOD = $_SERVER["REQUEST_METHOD"];
$parameter = $_POST["parameter"];
$parameter_name = $_POST["parameter_name"];
$parameter_type = $_POST["parameter_type"];
$mode = $_GET["mode"];
$fname = $_GET["fname"];

if ($REQUEST_METHOD == "POST" && !empty($parameter) && !empty($parameter_name) && !empty($parameter_type)) {

	$letters = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","_"); 

	$ExistingVariables = array_keys(ExistingParamsList());

	include("../ReportEditor/ParseFunctions.php");
	
	$parameter_name = strtolower($parameter_name);

	if (!CheckVarName($parameter_name)) 
		FuncAlert("Название функции имеет недопустимый формат","AddFormula.php");

	if (!$mode=="edit" && in_array($parameter_name, $ExistingVariables)) 
		FuncAlert("Данная функция уже определена","AddFormula.php");
	
	
	$lexems = ScanFormula($parameter); //Разбор формулы на лексемы

	if ($lexems) {

		include("../ReportEditor/translator.php");
		$translator =  new translator($lexems);

		$error = $translator->parse();
		if (!$error) {
			if (!$translator->createxml($parameter_name)) 
				FuncAlert("Невозможно открыть файл для записи","AddFormula.php");

			Header ("Location: AddFormula.php");
			exit;

		}
		else
			FuncAlert($error,"AddFormula.php");
	}
	
	FuncAlert("Неврный формат формулы","AddFormula.php");

}
elseif ($REQUEST_METHOD == "GET" && !empty($mode) && $mode=="regenerate") {
	$allparams = ExistingParamsList();
	$positions = array();

	include("../ReportEditor/GenerateFunctions.php");
	SortParams($allparams);
	GenerateLib($allparams);
	AssignParamsLib($allparams);

	Header ("Location: AddFormula.php");
	exit;
}
elseif ($REQUEST_METHOD == "GET" && !empty($mode) && $mode=="delete" && !empty($fname)) {
	$allparams = ExistingParamsList();

	if (HasChild($allparams,$fname))
		FuncAlert("Этот параметр нельзя удалить т. к. он используется в определении других параметров","AddFormula.php");
	
	unlink ($dir."/".$fname.".xml");
	Header ("Location: AddFormula.php");
	exit;
}

include("../Display/StartPage.php");
DisplayPageTitle("../down1.html","Редактирование формул отчета 'Расчет штатов'","Ввод формулы");

$digits = array(0,1,2,3,4,5,6,7,8,9);
$operators = array (
	"+" => "+",
	"-" => "-",
	"*" => "*",
	"/" => "/",
	"(" => "(",
	")" => ")",
	"." => "."
);

$allparams = ExistingParamsList();

if ($REQUEST_METHOD == "GET" && !empty($mode) && $mode=="edit" && !empty($fname)) {

	if (!isset($allparams[$fname])) {
		Header ("Location: AddFormula.php");
		exit;
	}		

	$qurrent_param = $allparams[$fname];
	ExcludeParams($allparams,$fname);
}

?>
<script  language="JavaScript">
<!--

function AddParam(p) {
	var func = document.getElementById("parameter_val");
	if (func)
		func.value = func.value + p;
	return (true);
}

function ClearText() {
	var func = document.getElementById("parameter_val");
	if (func)
		func.value = "";
	return (true);
}

function SaveValue() {
	var func_val = document.getElementById("parameter_val");
	var func = document.getElementById("parameter");
	if (func && func_val)
		func.value = func_val.value;
	return (true);
}

function ChangeType() {
	var type = document.getElementById("parameter_type");
	if (!type) {
		return (false);
	}

	if (type.value == "common")
	{
		var func = document.getElementById("parameter_val");
		if (func)
			func.value="";

		var ext = document.getElementById("extended_vars");
		if (ext)
			ext.style.display="none";

		var ext = document.getElementById("tipical_vars");
		if (ext)
			ext.style.display="none";

	}
	else {
		var ext = document.getElementById("extended_vars");
		if (ext)
			ext.style.display="";
		var ext = document.getElementById("tipical_vars");
		if (ext)
			ext.style.display="";
	}

	return (true);
}

function SetParam(name) {

	var pname = document.getElementById("parameter_name");
	if (pname)
		pname.value=name;


	return (true);
}

-->
</script>

<FORM name="add_parameter" method="POST" action="AddFormula.php">
<?php

if (!empty($qurrent_param))
	echo "<INPUT type='hidden' name='mode' value='edit' />";
else
	echo "<INPUT type='hidden' name='mode' value='add' />";

?>
<TABLE width="100%">
<?php

if (!empty($qurrent_param)) {
	echo "<INPUT type='hidden' name='parameter_name' value='$qurrent_param[name]' />";

	if ($qurrent_param["isplan"]=="Y")
		echo "<INPUT type='hidden' name='parameter_type' value='discip' />";
	else
		echo "<INPUT type='hidden' name='parameter_type' value='common' />";

	echo "<TR>\n<TD nowrap>Тип величины</TD>\n	<TD width=\"100%\"><SELECT name=\"parameter_type\" onChange=\"javascript: ChangeType();\" id=\"parameter_type\" disabled>\n";
	echo "<OPTION value=\"common\">Общая</OPTION>\n";

	if ($qurrent_param["isplan"]=="Y")
		echo "<OPTION value=\"discip\" selected>Зависящая от дисциплины</OPTION>\n";
	else
		echo "<OPTION value=\"discip\">Зависящая от дисциплины</OPTION>\n";

	echo "</SELECT>\n</TD>\n</TR>\n";

	echo "<TR>\n<TD nowrap>Название величины</TD>\n<TD><INPUT type=\"text\" name=\parameter_name\" size=\"30\" maxlength=\"30\" value=\"$qurrent_param[name]\" disabled /></TD>\n</TR>\n";

	echo "<TR>\n<TD nowrap>Комментарий</TD>\n<TD><INPUT type=\"text\" name=\"rus_name\" size=\"30\" maxlength=\"30\" value=\"$qurrent_param[rusname]\" /></TD>\n</TR>\n";

	if ($qurrent_param["display"]=="Y")
		echo "<TR>\n<TD nowrap>Добавлять в отчет</TD>\n<TD><INPUT type=\"checkbox\" name=\"display\" checked /></TD>\n</TR>\n";
	else
		echo "<TR>\n<TD nowrap>Добавлять в отчет</TD>\n<TD><INPUT type=\"checkbox\" name=\"display\" /></TD>\n</TR>\n";
}else {
?>
<TR>
	<TD nowrap>Тип величины</TD>
	<TD width="100%">
	<TABLE cellpadding="0" cellspacing="0">
	<TR>
		<TD>
	<SELECT name="parameter_type" onChange="javascript: ChangeType();" id="parameter_type">
	<OPTION value="common">Общая</OPTION>
	<OPTION value="discip">Зависящая от дисциплины</OPTION>
	</SELECT>
		</TD>
		<TD>&nbsp;&nbsp;</TD>
<?php

	echo "<TD id=\"tipical_vars\" valign=\"top\" style=\"display: none\">\n";
	echo "<SELECT name='tipical_var'onchange=\"javascript: SetParam(this.value)\">\n";

	foreach ($tipical_vars as $k => $v) 
		echo "<OPTION value='$k'>$v</OPTION>\n";
	
	echo "</SELECT>\n";
	echo "</TD>\n"
?>
	</TR>
	</TABLE>

	</TD>
</TR>
<TR>
	<TD nowrap>Название величины</TD>
	<TD><INPUT type="text" name="parameter_name" id="parameter_name" size="30" maxlength="30" />	</TD>
</TR>
<TR>
	<TD nowrap>Комментарий</TD>
	<TD><INPUT type="text" name="rus_name" id="rus_name" size="30" maxlength="30" />	</TD>
</TR>
<TR>
	<TD nowrap>Добавлять в отчет</TD>
	<TD><INPUT type="checkbox" name="display" checked/></TD>
</TR>
<?php
}
?>

<TR>
	<TD>Формула</TD>
<?php

if (!empty($qurrent_param)) 
	echo "<TD><INPUT type=\"text\" name=\"parameter_val\" id=\"parameter_val\" size=\"100%\"  value=\"$qurrent_param[formula]\" disabled/></TD>\n";
else
	echo "<TD><INPUT type=\"text\" name=\"parameter_val\" id=\"parameter_val\" size=\"100%\"  value=\"\" disabled/></TD>\n";

?>
</TR>
<INPUT type="hidden" id="parameter" name="parameter" value=""/>
<TR>
	<TD>&nbsp;</TD>
	<TD><INPUT type="submit" name="add_parameter" size="100%"  value="Добавить формулу" onclick="javascript: SaveValue();">&nbsp;&nbsp;<INPUT type="button" name="add_parameter" size="100%"  value="Очистить" onclick="javascript: ClearText();"></TD>
</TR>
</TABLE>
<BR>
<TABLE>
<TR>
	<TD class="TableTitle">Операторы</TD>
	<TD class="TableTitle">Параметры</TD>
	<TD>&nbsp;</TD>
</TR>
<TR>
	<TD valign="top" width="200"><BR>
<TABLE>
<TR>
	<TD valign='top'>
<?php	
	OutputButtons($digits, 3, false);
	echo "</TD>\n<TD valign='top'>";
	OutputButtons($operators, 3, false);
?>
</TD>
</	TR>
</TABLE>
	</TD>
	<TD valign="top" width="250">
 <b><i>Общие параметры</i></b><BR>
<?php	
	OutputButtons($common_params, 1, true);
?>
</TD>
<?php

if (!empty($qurrent_param) && $qurrent_param["isplan"]=="Y") 
	echo "<TD id=\"extended_vars\" valign=\"top\">\n";
else
	echo "<TD id=\"extended_vars\" valign=\"top\" style=\"display: none\">\n";
?>
 <b><i>Параметры для дисциплин</i></b></BR>
<?php	
	OutputButtons($plan_params, 1, true);
?>

</TD>
</TR>
</TABLE>
</TR>
</FORM>

<H3>Существующие величины</H3>
<TABLE cellpadding="5">
<TR>
	<TD class="TableTitle">Название</TD>
	<TD class="TableTitle">Формула</TD>
	<TD colspan="2">&nbsp;</TD>
</TR>

<?php
foreach ($allparams as $k => $v) {
	echo "<TR>\n";
	echo "<TD><b>$k</b>($v[rusname])</TD>\n";
	echo "<TD>$v[formula]</TD>\n";
	echo "<TD><input type='button' value='Удалить' onclick='javascript: self.location=\"AddFormula.php?mode=delete&fname=$k\"' /></TD>\n";
	echo "<TD><input type='button' value='Редактировать' onclick='javascript: self.location=\"AddFormula.php?mode=edit&fname=$k\"' /></TD>\n";
	echo "</TR>\n";

}
?>
</TABLE>
<P>
<CENTER>
<input type="button" value="Перегенерировать библиотеку" onclick="javascript: self.location='AddFormula.php?mode=regenerate'" />
</CENTER>
<BR>
<BR>
<?php
include("../Display/FinishPage.php");
?>