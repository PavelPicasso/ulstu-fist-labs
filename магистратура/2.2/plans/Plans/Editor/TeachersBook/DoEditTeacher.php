<?php
include("../PlanCalculatFunc.php");
CreateConnection();
$update = array_unique(explode(',', $_POST['NumOfChangeRec']));

foreach ($update as $k)
    QueryExec("update teachers set TeacherName='{$_POST["TeacherName"][$k]}', TeacherSignature='{$_POST["Signature"][$k]}', CodeOfDepart='{$_POST["CodeOfDepart"][$k]}', Mail='{$_POST["Mail"][$k]}' where CodeOfTeacher=$k");

FuncRedirect("TeachersBook.php");
mysql_close($Connection);
?>