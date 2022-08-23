<?
session_start();
if ($_SESSION["statusName"] != "uc" || empty($_GET["id"])) {
	Header("Location: ./index.php");
	exit;
}

$codeOfEventType = $_GET["id"];

include("../header.php");

if (!empty($_POST["editEventType"])) {
	if ($_POST["TypeName"] != "") {
		$q = "update eventtypes set TypeName = {?}, CodeOfEventSort = {?}, RatingNorm = {?} where CodeOfEventType = {?}";
		$db->query($q, array($_POST["TypeName"], $_POST["CodeOfEventSort"], $_POST["RatingNorm"], $codeOfEventType));
		$successMsg = 'Отчетность успешно изменена. <a href="./index.php" class="alert-link">Вернуться к списку норм.</a>';
	} else {
		$errorMsg = "Не введено название вида";
	}
}

// получить информацию об изменяемом виде мероприятия
$q = "select TypeName, CodeOfEventSort, RatingNorm from eventtypes where CodeOfEventType = {?}";
$eventType = $db->selectRow($q, array($codeOfEventType));
if (empty($eventType)) {
	$errorMsg = 'Вид мероприятия не найден. <a href="./index.php" class="alert-link">Вернуться к списку норм.</a>';
}
?>

	<div id="page-wrapper" style="min-height: 600px">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Редактировать вид мероприятия</h1>
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
				<?if (!empty($eventType)):?>
					<div class="form-group">
						<label>Тип мероприятия</label>
						<?
						$q = "select CodeOfEventSort, SortName from eventsorts";
						$eventSorts = $db->select($q);
						?>
						<select class="form-control" name="CodeOfEventSort">
							<?foreach ($eventSorts as $sort):
								$selected = "";
								if ($sort["CodeOfEventSort"] == $eventType["CodeOfEventSort"]) {
									$selected = " selected";
								}
								?>
								<option value="<?=$sort["CodeOfEventSort"]?>"<?=$selected?>><?=$sort["SortName"]?></option>
							<?endforeach;?>
						</select>
					</div>
					<div class="form-group">
						<label>Название</label>
						<input class="form-control" type="text" name="TypeName" value="<?=$eventType["TypeName"]?>">
					</div>
					<div class="form-group">
						<label>Норма</label>
						<input class="form-control" type="text" name="RatingNorm" value="<?=$eventType["RatingNorm"]?>">
					</div>
					<button type="submit" class="btn btn-default" name="editEventType" value="edit">Сохранить</button>
					<a href="./index.php" class="btn btn-default">Отменить</a>
				<?endif;?>
			</div>
		</form>
	</div>

<? include("../footer.php"); ?>