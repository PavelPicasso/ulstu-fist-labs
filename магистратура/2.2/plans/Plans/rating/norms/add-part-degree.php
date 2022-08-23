<?
session_start();
if ($_SESSION["statusName"] != "uc") {
	Header("Location: ./index.php");
	exit;
}

include("../header.php");

if (!empty($_POST["addPartDegree"])) {
	if ($_POST["DegreeName"] != "") {
		$q = "INSERT INTO eventpartdegree (DegreeName, RatingNorm) values ({?}, {?})";
		$db->query($q, array($_POST["DegreeName"], $_POST["RatingNorm"]));
		$successMsg = 'Cтепень участия добавлена. <a href="./index.php" class="alert-link">Вернуться к списку норм.</a>';
	} else {
		$errorMsg = "Не введено название степени участия";
	}
}
?>

	<div id="page-wrapper" style="min-height: 600px">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Новая степень участия</h1>
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
				<div class="form-group">
					<label>Название</label>
					<input class="form-control" type="text" name="DegreeName">
				</div>
				<div class="form-group">
					<label>Норма</label>
					<input class="form-control" type="text" name="RatingNorm">
				</div>
				<button type="submit" class="btn btn-default" name="addPartDegree" value="add">Добавить степень участия</button>
				<a href="./index.php" class="btn btn-default">Отменить</a>
			</div>
		</form>
	</div>

<? include("../footer.php"); ?>