<?
session_start();
if ($_SESSION["statusName"] != "uc") {
	Header("Location: ./index.php");
	exit;
}

include("../header.php");

if (!empty($_POST["addType"])) {
	if ($_POST["TypeName"] != "") {
		$q = "INSERT INTO reporttypes (TypeName, RatingNorm) values ({?}, {?})";
		$newTypeId = $db->query($q, array($_POST["TypeName"], $_POST["RatingNorm"]));
		if (!empty ($_POST["CodeOfReportMark"])) {
			foreach ($_POST["CodeOfReportMark"] as $reportMark) {
				$q = "INSERT INTO reportmarktotype (CodeOfReportType, CodeOfReportMark) values ({?}, {?})";
				$db->query($q, array($newTypeId, $reportMark));
			}
		}
		$successMsg = 'Вид отчетности успешно добавлен. <a href="./index.php" class="alert-link">Вернуться к списку норм.</a>';
	} else {
		$errorMsg = "Не введено название вида отчетности";
	}
}
?>

	<div id="page-wrapper" style="min-height: 600px">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Новый вид отчетности</h1>
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
					<input class="form-control" type="text" name="TypeName">
				</div>
				<div class="form-group">
					<label>Оценки</label>
					<?
					$q = "select CodeOfReportMark, MarkName from reportmarks";
					$reportMarks = $db->select($q);
					?>
					<select multiple="" class="form-control" name="CodeOfReportMark[]">
						<?foreach ($reportMarks as $mark):?>
							<option value="<?=$mark["CodeOfReportMark"]?>"><?=$mark["MarkName"]?></option>
						<?endforeach;?>
					</select>
				</div>
				<div class="form-group">
					<label>Норма</label>
					<input class="form-control" type="text" name="RatingNorm">
				</div>
				<button type="submit" class="btn btn-default" name="addType" value="add">Добавить вид отчетности</button>
				<a href="./index.php" class="btn btn-default">Отменить</a>
			</div>
		</form>
	</div>

<? include("../footer.php"); ?>