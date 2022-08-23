<?php
include("../PlanCalculatFunc.php");
CreateConnection();
include("../Display/DisplayFunc.php");

$id = FetchResult("SELECT MAX(CodeOfTeacherDiscip) from teachersDiscips");
if (!empty($id))
    $id++;
else
    $id=1;
$insertItems = $_POST['insert'];
$CodeOfTeacher = $_POST['teacher'];
if (!empty($CodeOfTeacher)) {
    foreach ($insertItems as $item) {
        $q = "select * from schplanitemshoursrest where schplanitemshoursrest.CodeOfSchPlanItem = {$item}";
    	$planItem = FetchArrays($q);
    	$planItem = $planItem[0];
        QueryExec("insert into teachersDiscips values ('{$id}', '{$CodeOfTeacher}', '{$item}', '{$planItem["LectInSem"]}', '{$planItem["LabInSem"]}', '{$planItem["PractInSem"]}', '{$planItem["KursWork"]}', '{$planItem["KursPrj"]}', '{$planItem["Test"]}', '{$planItem["Exam"]}', '{$planItem["ControlWork"]}', '{$planItem["RGR"]}', '{$planItem["CalcW"]}', '{$planItem["TestW"]}', '{$planItem["Synopsis"]}')");
        QueryExec("update schplanitemshoursrest set LectInSem = '0', LabInSem = '0', PractInSem = '0', KursWork = '0', KursPrj = '0', Test = '0', Exam = '0', ControlWork = '0', RGR = '0', CalcW = '0', TestW = '0', Synopsis = '0' where CodeOfSchPlanItem = {$item}");
        $id++;
    }
}

mysql_close($Connection);

FuncRedirect("TeacherDiscip.php?teacher={$CodeOfTeacher}");
?>

