<?
session_start();
if ($_SESSION["statusName"] != "uc") {
	Header("Location: ./index.php");
	exit;
}

$codeOfReportType = $_GET["id"];

include("../header.php");

if (!empty($_POST["editType"])) {
	if ($_POST["TypeName"] != "") {
		$q = "update reporttypes set TypeName = {?}, RatingNorm = {?} where CodeOfReportType = {?}";
		$db->query($q, array($_POST["TypeName"], $_POST["RatingNorm"], $codeOfReportType));
		// удалить все соответствия оценок и видов отчетности
		$q = "delete from reportmarktotype where CodeOfReportType = {?}";
		$db->query($q, array($codeOfReportType));
		if (!empty ($_POST["CodeOfReportMark"])) {
			foreach ($_POST["CodeOfReportMark"] as $reportMark) {
				$q = "INSERT INTO reportmarktotype (CodeOfReportType, CodeOfReportMark) values ({?}, {?})";
				$db->query($q, array($codeOfReportType, $reportMark));
			}
		}
		$successMsg = 'Вид отчетности успешно изменен. <a href="./index.php" class="alert-link">Вернуться к списку норм.</a>';
	} else {
		$errorMsg = "Не введено название вида отчетности";
	}
}

$q = "select TypeName, RatingNorm from reporttypes where CodeOfReportType = {?}";
$reportType = $db->selectRow($q, array($codeOfReportType));

if (empty($reportType)) {
	$errorMsg = 'Вид отчетности не найден. <a href="./index.php" class="alert-link">Вернуться к списку норм.</a>';
} else {
	// получение списка оценок, связанных с видом отчетности
	$reportTypeMarks = array();
	$q = "select CodeOfReportMark from reportmarktotype where CodeOfReportType = {?}";
	$marks = $db->select($q, array($codeOfReportType));
	foreach ($marks as $mark) {
		$reportTypeMarks[] = $mark["CodeOfReportMark"];
	}
}
?>

	<div id="page-wrapper" style="min-height: 600px">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Редактировать вид отчетности</h1>
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
				<?if (!empty($reportType)):?>
					<div class="form-group">
						<label>Название</label>
						<input class="form-control" type="text" name="TypeName" value="<?=$reportType["TypeName"]?>">
					</div>
					<div class="form-group">
						<label>Оценки</label>
						<?
						$q = "select CodeOfReportMark, MarkName from reportmarks";
						$reportMarks = $db->select($q);
						?>
						<select multiple="" class="form-control" name="CodeOfReportMark[]">
							<?foreach ($reportMarks as $mark):
								$selected = "";
								if (in_array($mark["CodeOfReportMark"], $reportTypeMarks)) {
									$selected = " selected";
								}
								?>
								<option value="<?=$mark["CodeOfReportMark"]?>"<?=$selected?>><?=$mark["MarkName"]?></option>
							<?endforeach;?>
						</select>
					</div>
					<div class="form-group">
						<label>Норма</label>
						<input class="form-control" type="text" name="RatingNorm" value="<?=$reportType["RatingNorm"]?>">
					</div>
					<button type="submit" class="btn btn-default" name="editType" value="edit">Сохранить</button>
					<a href="./index.php" class="btn btn-default">Отменить</a>
				<?endif;?>
			</div>
		</form>
	</div>

<? include("../footer.php"); ?>