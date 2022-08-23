<?php
$Connection = mysql_connect("localhost" , "root" , "")
or die("Can't connect to localhost:".mysql_errno().": ".mysql_error()."<BR>");
mysql_select_db("plans")
or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");

$q = "SELECT MAX(CodeOfSchPlanItem) ".
"FROM  schplanitems ".
"GROUP BY  CodeOfSchPlan ,  CodeOfDepart ,  NumbOfSemestr ,  AuditH ,  LectInW ,  LabInW ,  PractInW ,  KursWork ,  KursPrj ,  Test ,  Exam ,  ControlWork ,  RGR ,  CalcW ,  TestW ,  Synopsis ".
"HAVING COUNT( * ) >1";
$result = mysql_query($q,$Connection)
or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");


while ($row = mysql_fetch_assoc($result)) {
    $sql = "delete from schplanitems where CodeOfSchPlanItem = {$row['MAX(CodeOfSchPlanItem)']}";
    mysql_query($sql)
    or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
}

echo "Yea!";

mysql_close($Connection);
?>