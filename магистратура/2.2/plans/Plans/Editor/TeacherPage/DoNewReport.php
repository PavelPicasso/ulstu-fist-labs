<?php
include("../PlanCalculatFunc.php");
CreateConnection();

//var_dump($_POST);

$id = FetchResult("SELECT MAX(CodeOfReport) from reports");
if (!empty($id))
    $id++;
else
    $id=1;
if (isset($_POST["ReportName"]) && ($_POST["ReportName"] != "")) {
    $report = FetchResult("select CodeOfReport from reports where ReportName='{$_POST['ReportName']}' and CodeOfReportType={$_POST['CodeOfReportType']} and CodeOfTeacher={$_POST['teacher']} and CodeOfSchPlanItem={$_POST['CodeOfSchPlanItem']} and CodeOfStudentGroup={$_POST['CodeOfStudentGroup']}");
    if (!empty($report)) {
        FuncRedirect("NewReport.php?error=2");
    } else {
        QueryExec("INSERT INTO reports values ('{$id}', '{$_POST["ReportName"]}', '{$_POST["CodeOfReportType"]}', '{$_POST["teacher"]}', '{$_POST["CodeOfSchPlanItem"]}', '{$_POST["CodeOfStudentGroup"]}', '{$_POST["Deadline"]}')");
    }
} else {
    FuncRedirect("NewReport.php?error=1");
}

mysql_close($Connection);
FuncRedirect("StudentMarks.php");
?>