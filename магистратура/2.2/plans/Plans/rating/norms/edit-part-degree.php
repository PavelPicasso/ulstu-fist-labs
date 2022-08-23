<?
session_start();
if ($_SESSION["statusName"] != "uc") {
	Header("Location: ./index.php");
	exit;
}

$codeOfPartDegree = $_GET["id"];

include("../header.php");

if (!empty($_POST["editPartDegree"])) {
	if ($_POST["DegreeName"] != "") {
		$q = "update eventpartdegree set DegreeName = {?}, RatingNorm = {?} where CodeOfEventPartDegree = {?}";
		$db->query($q, array($_POST["DegreeName"], $_POST["RatingNorm"], $codeOfPartDegree));
		$successMsg = 'Cтепень участия успешно изменена. <a href="./index.php" class="alert-link">Вернуться к списку норм.</a>';
	} else {
		$errorMsg = "Не введено название степени участия";
	}
}

// получить информацию об изменяемой степени участия
$q = "select DegreeName, RatingNorm from eventpartdegree where CodeOfEventPartDegree = {?}";
$partDegree = $db->selectRow($q, array($codeOfPartDegree));
if (empty($partDegree)) {
	$errorMsg = 'Cтепень участия не найдена. <a href="./index.php" class="alert-link">Вернуться к списку норм.</a>';
}
?>

	<div id="page-wrapper" style="min-height: 600px">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Редактировать степень участия</h1>
			</div>
			<!-- /.col-lg-12 -->
		</div>
		<form role="form" name="fed" action="" method="post">
			<div class="col-lg-6">
				<?if (!empty($successMsg)):?>
					<div class="alert alert-success"><?=$successMsg?></div>
				<?endif;?>
				<?if (!empty($errorMsg)):?>
					<div class="alert alert-danger"><?=$errorMsg?></div>
				<?endif;?>
				<?if (!empty($partDegree)):?>
					<div class="form-group">
						<label>Название</label>
						<input class="form-control" type="text" name="DegreeName" value="<?=$partDegree["DegreeName"]?>">
					</div>
					<div class="form-group">
						<label>Норма</label>
						<input class="form-control" type="text" name="RatingNorm" value="<?=$partDegree["RatingNorm"]?>">
					</div>
					<button type="submit" class="btn btn-default" name="editPartDegree" value="edit">Сохранить</button>
					<a href="./index.php" class="btn btn-default">Отменить</a>
				<?endif;?>
			</div>
		</form>
	</div>

<? include("../footer.php"); ?>