<?php
if (!($REQUEST_METHOD=='POST' && $_POST['flag'])) {
	Header ("Location: Standard.php");
	exit;
}

include("../PlanCalculatFunc.php");
CreateConnection();

if (!empty($flag))
	while ( $StCode = each($flag)){
		FetchQuery("Delete from specialslimit where CodeOfStandard=".$StCode[1]);
		FetchQuery("Delete from standardplans where CodeOfStandard=".$StCode[1]);
	}

mysql_close($Connection);
Header ("Location: Standard.php");
?>
