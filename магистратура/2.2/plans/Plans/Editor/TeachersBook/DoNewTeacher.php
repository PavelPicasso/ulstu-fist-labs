<?php
include("../PlanCalculatFunc.php");
CreateConnection();
include("../Display/DisplayFunc.php");

$id = FetchResult("SELECT MAX(CodeOfTeacher) from teachers");
if (!empty($id))
    $id++;
else
    $id=1;
if (isset($_POST["TeacherName"]) && ($_POST["TeacherName"] != "")) {
    QueryExec("INSERT INTO teachers values ('{$id}', '{$_POST["TeacherName"]}', '{$_POST["Signature"]}', '{$_POST["Department"]}', '{$_POST["Mail"]}')");
} else {
    FuncRedirect("NewTeacher.php?sh=1&depart={$_POST["Department"]}&error=1");
}

mysql_close($Connection);

if ($_POST['shift'])
    FuncRedirect("NewTeacher.php?sh=1&depart={$_POST["Department"]}");
else
    FuncRedirect("TeachersBook.php");
?>

