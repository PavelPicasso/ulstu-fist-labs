<?php
include("../PlanCalculatFunc.php");
$del = $_POST['del'];
if (!empty($del)) {
    CreateConnection();
    foreach ($del as $k) {
        QueryExec("delete from teachersDiscips where CodeOfTeacher='$k'");
        QueryExec("delete from teachers where CodeOfTeacher='$k'");
    }
    mysql_close($Connection);
}
FuncRedirect("TeachersBook.php");
?>