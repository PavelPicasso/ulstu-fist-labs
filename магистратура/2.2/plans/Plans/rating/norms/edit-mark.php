<?
session_start();
if ($_SESSION["statusName"] != "uc") {
	Header("Location: ./index.php");
	exit;
}

$codeOfReportMark = $_GET["id"];

include("../header.php");

if (!empty($_POST["editMark"])) {
	if ($_POST["MarkName"] != "") {
		$q = "update reportmarks set MarkName = {?}, RatingNorm = {?} where CodeOfReportMark = {?}";
		$db->query($q, array($_POST["MarkName"], $_POST["RatingNorm"], $codeOfReportMark));
		// удалить все соответствия оценок и виды отчетности
		$q = "delete from reportmarktotype where CodeOfReportMark = {?}";
		$db->query($q, array($codeOfReportMark));
		if (!empty ($_POST["CodeOfReportType"])) {
			foreach ($_POST["CodeOfReportType"] as $reportType) {
				$q = "INSERT INTO reportmarktotype (CodeOfReportType, CodeOfReportMark) values ({?}, {?})";
				$db->query($q, array($reportType, $codeOfReportMark));
			}
		}
		$successMsg = 'Оценка успешно добавлена. <a href="./index.php" class="alert-link">Вернуться к списку норм.</a>';
	} else {
		$errorMsg = "Не введено название оценки";
	}
}

// получить информацию об изменяемой оценке
$q = "select MarkName, RatingNorm from reportmarks where CodeOfReportMark = {?}";
$reportMark = $db->selectRow($q, array($codeOfReportMark));

if (empty($reportMark)) {
	$errorMsg = 'Оценка не найдена. <a href="./index.php" class="alert-link">Вернуться к списку норм.</a>';
} else {
	// получить связанные с оценкой виды отчетности
	$reportMarkTypes = array();
	$q = "select CodeOfReportType from reportmarktotype where CodeOfReportMark = {?}";
	$types = $db->select($q, array($codeOfReportMark));
	foreach ($types as $type) {
		$reportMarkTypes[] = $type["CodeOfReportType"];
	}
}
?>

	<div id="page-wrapper" style="min-height: 600px">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Редактировать оценку</h1>
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
				<?if (!empty($reportMark)):?>
					<div class="form-group">
						<label>Название</label>
						<input class="form-control" type="text" name="MarkName" value="<?=$reportMark["MarkName"]?>">
					</div>
					<div class="form-group">
						<label>Виды отчетности</label>
						<?
						$q = "select CodeOfReportType, TypeName from reporttypes";
						$reportTypes = $db->select($q);
						?>
						<select multiple="" class="form-control" name="CodeOfReportType[]">
							<?foreach ($reportTypes as $type):
								$selected = "";
								if (in_array($type["CodeOfReportType"], $reportMarkTypes)) {
									$selected = " selected";
								}?>
								<option value="<?=$type["CodeOfReportType"]?>"<?=$selected?>><?=$type["TypeName"]?></option>
							<?endforeach;?>
						</select>
					</div>
					<div class="form-group">
						<label>Норма</label>
						<input class="form-control" type="text" name="RatingNorm" value="<?=$reportMark["RatingNorm"]?>">
					</div>
					<button type="submit" class="btn btn-default" name="editMark" value="edit">Сохранить</button>
					<a href="./index.php" class="btn btn-default">Отменить</a>
				<?endif;?>
			</div>
		</form>
	</div>

<? include("../footer.php"); ?>