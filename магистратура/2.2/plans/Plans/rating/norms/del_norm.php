<?php
session_start();
if ($_SESSION["statusName"] != "uc") {
	Header("Location: ./index.php");
	exit;
}

$normId = $_GET["id"];
$normType = $_GET["type"];
$code = $_GET["code"];

if (!empty($normId) && !empty($normType) && !empty($code)) {
	$table = "";
	$codeFieldName = "";
	$subQ = "";

	// определение таблицы и название поля с кодом
	switch ($normType) {
		case "Mark":
			// оценки
			$table = "reportmarks";
			$codeFieldName = "CodeOfReportMark";
			$subQ = "delete from reportmarktotype where CodeOfReportMark = {?}";
			break;
		case "Report":
			// типы отчетности
			$table = "reporttypes";
			$codeFieldName = "CodeOfReportType";
			$subQ = "delete from reportmarktotype where CodeOfReportType = {?}";
			break;
		case "Degree":
			// степень участия
			$table = "eventpartdegree";
			$codeFieldName = "CodeOfEventPartDegree";
			break;
		case "Event":
			// виды мероприятий
			$table = "eventtypes";
			$codeFieldName = "CodeOfEventType";
			break;

		default:
			break;
	}

	if ($code == md5($normId."-".$codeFieldName."-".$table) && !empty($table) && !empty($codeFieldName)) {
		include ("../SQLFunc.php");
		$db = DataBase::getDB();

		$q = "delete from ".$table." where ".$codeFieldName." = {?}";
		$res = $db->query($q, array($normId));

		if (!empty($subQ)) {
			$db->query($subQ, array($normId));
		}
	}
}

Header("Location: ./index.php");
?>