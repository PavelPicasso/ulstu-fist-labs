<?
session_start();
if ($_SESSION["userRole"] == "st") {
	Header ("Location: student/index.php");
	exit;
} elseif ($_SESSION["userRole"] == "tch") {
	Header ("Location: teacher/index.php");
	exit;
} else {
	Header("Location: ../index.php");
	exit;
}
?>