<?php
include("../Display/DisplayFunc.php");
include("../Display/StartPage.php");
DisplayPageTitle("../down1.html","Получение отчетов о дисциплинах закрепленных за кафедрами","Выберите кафедру:");
include("../PlanCalculatFunc.php");
CreateConnection();

	$result=mysql_query("select * from department order by DepName",$Connection) 
       or die("Query failed:".mysql_errno().": ".mysql_error()."<BR>");
    while($row=mysql_fetch_object($result)){
       echo  "<strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
       echo  "<a href=\"ChooseStreams.php?depart=$row->CodeOfDepart\">"; 
       echo  "$row->DepName</strong></a><P>";
    }
   mysql_close($Connection);
?>

<HR>

<?php 
	include("../Display/FinishPage.php");
?>
