<?php
include("../PlanCalculatFunc.php");
$del = $_POST['del'];
if (!empty($del)) {
    CreateConnection();
    foreach ($del as $k) {
        QueryExec("delete from teachersDiscips where CodeOfTeacherDiscip='$k'");
    }
    mysql_close($Connection);
}
$CodeOfTeacher = $_POST['teacher'];
FuncRedirect("TeacherDiscip.php?teacher=".$CodeOfTeacher);
?>