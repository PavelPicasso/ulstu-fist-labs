<?
session_start();
if ($_SESSION["statusName"] != "uc") {
	Header("Location: ./index.php");
	exit;
}

include("../header.php");

if (!empty($_POST["addEventType"])) {
	if ($_POST["TypeName"] != "") {
		$q = "INSERT INTO eventtypes (TypeName, CodeOfEventSort, RatingNorm) values ({?}, {?}, {?})";
		$db->query($q, array($_POST["TypeName"], $_POST["CodeOfEventSort"], $_POST["RatingNorm"]));
		$successMsg = 'Вид мероприятия успешно добавлен. <a href="./index.php" class="alert-link">Вернуться к списку норм.</a>';
	} else {
		$errorMsg = "Не введено название вида";
	}
}
?>

	<div id="page-wrapper" style="min-height: 600px">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Новый вид мероприятия</h1>
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
					<label>Тип мероприятия</label>
					<?
					$q = "select CodeOfEventSort, SortName from eventsorts";
					$eventSorts = $db->select($q);
					?>
					<select class="form-control" name="CodeOfEventSort">
						<?foreach ($eventSorts as $sort):?>
							<option value="<?=$sort["CodeOfEventSort"]?>"><?=$sort["SortName"]?></option>
						<?endforeach;?>
					</select>
				</div>
				<div class="form-group">
					<label>Название</label>
					<input class="form-control" type="text" name="TypeName">
				</div>
				<div class="form-group">
					<label>Норма</label>
					<input class="form-control" type="text" name="RatingNorm">
				</div>
				<button type="submit" class="btn btn-default" name="addEventType" value="add">Добавить вид мероприятия</button>
				<a href="./index.php" class="btn btn-default">Отменить</a>
			</div>
		</form>
	</div>

<? include("../footer.php"); ?>