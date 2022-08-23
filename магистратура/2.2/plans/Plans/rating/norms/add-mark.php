<?
session_start();
if ($_SESSION["statusName"] != "uc") {
	Header("Location: ./index.php");
	exit;
}

include("../header.php");

if (!empty($_POST["addMark"])) {
	if ($_POST["MarkName"] != "") {
		$q = "INSERT INTO reportmarks (MarkName, RatingNorm) values ({?}, {?})";
		$newMarkId = $db->query($q, array($_POST["MarkName"], $_POST["RatingNorm"]));
		if (!empty ($_POST["CodeOfReportType"])) {
			foreach ($_POST["CodeOfReportType"] as $reportType) {
				$q = "INSERT INTO reportmarktotype (CodeOfReportType, CodeOfReportMark) values ({?}, {?})";
				$db->query($q, array($reportType, $newMarkId));
			}
		}
		$successMsg = 'Оценка успешно добавлена. <a href="./index.php" class="alert-link">Вернуться к списку норм.</a>';
	} else {
		$errorMsg = "Не введено название оценки";
	}
}
?>

	<div id="page-wrapper" style="min-height: 600px">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Новая оценка</h1>
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
					<input class="form-control" type="text" name="MarkName">
				</div>
				<div class="form-group">
					<label>Виды отчетности</label>
					<?
					$q = "select CodeOfReportType, TypeName from reporttypes";
					$reportTypes = $db->select($q);
					?>
					<select multiple="" class="form-control" name="CodeOfReportType[]">
						<?foreach ($reportTypes as $type):?>
							<option value="<?=$type["CodeOfReportType"]?>"><?=$type["TypeName"]?></option>
						<?endforeach;?>
					</select>
				</div>
				<div class="form-group">
					<label>Норма</label>
					<input class="form-control" type="text" name="RatingNorm">
				</div>
				<button type="submit" class="btn btn-default" name="addMark" value="add">Добавить оценку</button>
				<a href="./index.php" class="btn btn-default">Отменить</a>
			</div>
		</form>
	</div>

<? include("../footer.php"); ?>