<?php
	session_start();
	if (!(($_SESSION["status"] == 0)||($_SESSION["status"] == 2))){
		Header ("Location: ../Unreg.html");
	}
	if (!($REQUEST_METHOD=='POST' && $_POST['plan'])) {
	  Header ("Location: Choise.php");
	  exit;
	}

	include("../PlanCalculatFunc.php");
	CreateConnection();

	FetchQuery("Update plans set CodeOfDirect='$Dir', CodeOfSpecial='$Spec', CodeOfSpecialization='$Spz' where CodeOfPlan='$plan'");

	mysql_close($Connection);
	Header ("Location: PlanHEd.php?plan=$plan");
?>
