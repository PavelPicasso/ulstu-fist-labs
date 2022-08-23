<?
session_start();
if ($_SESSION["userRole"] != "tch") {
	Header("Location: ../../index.php");
	exit;
} else {
	$CodeOfTeacher = $_SESSION["userRoleId"];
}
?>

<?include("../../header.php");?>
<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Персональные данные</h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<div class="panel-body">
		<form role="form" action="" method="post">
			<dl class="dl-horizontal">
				<?
				if (isset($_POST["saveEmail"])) {
					$q = "update teachers set Mail = {?} where CodeOfTeacher = {?}";
					$db->query($q, array($_POST["email"], $_SESSION["userRoleId"]));
				}

				$q = "select teachers.TeacherName, teachers.Mail, department.DepName from teachers, department where teachers.CodeOfTeacher = {?} ".
					"and department.CodeOfDepart = teachers.CodeOfDepart";
				$info = $db->selectRow($q, array($_SESSION["userRoleId"]));
				?>
				<h4><dt style="text-align: left; width:15%">ФИО</dt><dd><?=$info["TeacherName"]?></dd></h4>
				<h4><dt style="text-align: left; width:15%">Кафедра</dt><dd><?=$info["DepName"]?></dd></h4>
				<h4><dt style="text-align: left; width:15%">E-mail</dt><dd><input type="text" name="email" value="<?=$info["Mail"]?>"></dd></h4>
				<dt><button type="submit" name="saveEmail" class="btn btn-default">Сохранить</button></dt>
				<dd><button type="reset" class="btn btn-default">Отменить</button></dd>
			</dl>
		</form>
	</div>
</div>
<?include("../../footer.php");?>