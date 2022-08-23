<?php
include("../PlanCalculatFunc.php");
CreateConnection();

QueryExec("update teachers set Mail='{$_POST["mail"]}' where CodeOfTeacher='{$_POST["CodeOfTeacher"]}'");

FuncRedirect("main.php");
mysql_close($Connection);
?>