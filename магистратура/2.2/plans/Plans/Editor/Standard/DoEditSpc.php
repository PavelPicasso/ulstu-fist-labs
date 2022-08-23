<?php
	session_start();
	if (!(($_SESSION["status"] == 0)||($_SESSION["status"] == 2))){
		Header ("Location: ../Unreg.html");
	}

	include("../PlanCalculatFunc.php");
	CreateConnection();

	FetchQuery("Update specialslimit set CodeOfDirect='$Dir', CodeOfSpecial='$Spec', CodeOfSpecialization='$Spz' where CodeOfStandard='$CodeOfStandard'");
	mysql_close($Connection);
	Header ("Location: EditStandard.php?stCode=$CodeOfStandard");
?>
