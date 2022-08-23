<?
session_start();
if ($_SESSION["userRole"] != "st") {
	Header("Location: ../../index.php");
	exit;
} else {
	$CodeOfStudent = $_SESSION["userRoleId"];
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
						$q = "update students set Mail = {?}, PersonalDataPermission = {?} where CodeOfStudent = {?}";
						$permission = 0;
						if ($_POST["permission"] == 1) {
							$permission = 1;
						}
						$db->query($q, array($_POST["email"], $permission, $_SESSION["userRoleId"]));
					}

					$q = "select students.StudentName, students.Mail, students.PersonalDataPermission, studentgroups.GroupName from students, studentgroups where students.CodeOfStudent = {?} ".
						"and studentgroups.CodeOfStudentGroup = students.CodeOfStudentGroup";
					$info = $db->selectRow($q, array($_SESSION["userRoleId"]));
					?>

					<h4><dt style="text-align: left; width:15%">ФИО</dt>
						<dd><?=$info["StudentName"]?></dd></h4>

					<h4><dt style="text-align: left; width:15%">Группа</dt>
						<dd><?=$info["GroupName"]?></dd></h4>

					<h4><dt style="text-align: left; width:15%">E-mail</dt>
						<dd><input type="text" name="email" value="<?=$info["Mail"]?>"></dd></h4>

					<h4><dt style="text-align: left; width:15%">Публиковать<br>в рейтинге</dt>
						<dd>
							<div class="checkbox">
								<label>
									<input type="checkbox" name="permission" <?if ($info["PersonalDataPermission"] == 1):?>checked <?endif;?>value="1">
								</label>
							</div>
						</dd></h4>

					<dt><button type="submit" name="saveEmail" class="btn btn-default">Сохранить</button></dt>
					<dd><button type="reset" class="btn btn-default">Отменить</button></dd>
				</dl>
			</form>
		</div>
	</div>
<?include("../../footer.php");?>