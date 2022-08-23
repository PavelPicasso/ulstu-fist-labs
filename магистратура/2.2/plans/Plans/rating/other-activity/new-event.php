<?
session_start();
if ($_SESSION["statusName"] != "uc") {
	Header("Location: ../index.php");
	exit;
}

include("../header.php");

if (!empty($_POST["addEvent"])) {
	if ($_POST["EventName"] != "") {
		$q = "INSERT INTO events (EventName, CodeOfEventType, Date) values ({?}, {?}, {?})";
		$db->query($q, array($_POST["EventName"], $_POST["CodeOfEventType"], $_POST["Date"]));
		$successMsg = 'Мероприятие успешно добавлено. <a href="./index.php" class="alert-link">Вернуться во Внеучебную активность.</a>';
	} else {
		$errorMsg = "Не введено название мероприятия";
	}
}

// типы мероприятий
$eventSorts = array();
// виды мероприятий
$eventTypes = array();

// получение типов мероприятий
$q = "select CodeOfEventSort, SortName from eventsorts";
$eventSortsDB = $db->select($q);
foreach ($eventSortsDB as $sort) {
	// поучение видов мероприятий по типу
	$q = "select CodeOfEventType, TypeName from eventtypes where CodeOfEventSort = {?} order by TypeName asc";
	$eventTypesDB = $db->select($q, array($sort["CodeOfEventSort"]));

	foreach ($eventTypesDB as $type) {
		$eventTypes[$sort["CodeOfEventSort"]][$type["CodeOfEventType"]] = $type["TypeName"];
	}
	if (!empty($eventTypes[$sort["CodeOfEventSort"]])) {
		$eventSorts[$sort["CodeOfEventSort"]] = $sort["SortName"];
	}
}
?>

	<div id="page-wrapper" style="min-height: 600px">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Новое мероприятие</h1>
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
					<select class="form-control js-event-sort" name="CodeOfEventSort">
						<?foreach ($eventSorts as $code => $sort):?>
							<option value="<?=$code?>"><?=$sort?></option>
						<?endforeach;?>
					</select>
				</div>
				<div class="form-group">
					<label>Вид мероприятия</label>
					<select class="form-control js-event-type" name="CodeOfEventType">
						<?
						reset($eventSorts);
						list($firstSortCode, $value) = each($eventSorts);
						foreach ($eventTypes[$firstSortCode] as $code => $type):?>
							<option value="<?=$code?>"><?=$type?></option>
						<?endforeach;?>
					</select>
				</div>
				<div class="form-group">
					<label>Название</label>
					<input class="form-control" type="text" name="EventName">
				</div>
				<div class="form-group">
					<label>Дата мероприятия</label>
					<input class="form-control" type="date" name="Date">
				</div>
				<button type="submit" class="btn btn-default" name="addEvent" value="add">Добавить мероприятие</button>
				<a href="index.php" class="btn btn-default">Отменить</a>
			</div>
		</form>
	</div>
<? include("../footer.php"); ?>

<script type="text/javascript">
	var eventTypes = <?php echo json_encode($eventTypes)?>;

	function compareObjects (a, b) {
		if (a.name > b.name) return 1;
		if (a.name < b.name) return -1;
		return 0;
	};

	// изменение списка видов мероприятий при изменении типа мероприятия
	$('.js-event-sort').on('change', function() {
		var types = eventTypes[this.value];
		$('.js-event-type').find('option').remove().end();
		if (types != undefined) {
			var typesNew = [];
			for (var i in types) {
				typesNew.push({ id: i, name: types[i]});
			}
			typesNew.sort(compareObjects);

			var i = 0;
			$.each(typesNew, function(key, value) {
				$('.js-event-type').append($("<option/>", {value: value.id, text: value.name}));
			});
		}
	});
</script>