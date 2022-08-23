<?
session_start();
if ($_SESSION["statusName"] != "uc") {
	Header("Location: ../index.php");
	exit;
}

include("../header.php");

// изменение даты мероприятия
if (!empty($_POST["editTime"]) && !empty($_POST["CodeOfEvent"])) {
	$q = "update events set Date = {?} where CodeOfEvent = {?}";
	$date = "0000-00-00";
	if (!empty($_POST["Date"])) {
		$date = date("Y-m-d", strtotime($_POST["Date"]));
	}
	$db->query($q, array($date, $_POST["CodeOfEvent"]));
}

// изменить степени участия студентов
if (!empty($_POST["editPartDegree"]) && !empty($_POST["StudentPartDegree"]) && !empty($_POST["CodeOfEvent"])) {
	foreach ($_POST["StudentPartDegree"] as $studentCode => $partDegree) {
		$q = "delete from studentevents where studentevents.CodeOfStudent = {?} and studentevents.CodeOfEvent = {?}";
		$db->query($q, array($studentCode, $_POST["CodeOfEvent"]));

		if (!empty($partDegree)) {
			$q = "insert into studentevents (CodeOfStudent, CodeOfEvent, CodeOfEventPartDegree) values ({?}, {?}, {?})";
			$db->query($q, array($studentCode, $_POST["CodeOfEvent"], $partDegree));
		}
	}
}

// типы мероприятий
$eventSorts = array();
// виды мероприятий
$eventTypes = array();
// мероприятия
$events = array();
// даты мероприятий
$eventDates = array();

// получение типов мероприятий
$q = "select CodeOfEventSort, SortName from eventsorts";
$eventSortsDB = $db->select($q);
foreach ($eventSortsDB as $sort) {
	// получение видов мероприятий по типу
	$q = "select CodeOfEventType, TypeName from eventtypes where CodeOfEventSort = {?} order by TypeName asc";
	$eventTypesDB = $db->select($q, array($sort["CodeOfEventSort"]));

	foreach ($eventTypesDB as $type) {
		// получение мероприятий по виду
		$q = "select CodeOfEvent, EventName, Date from events where CodeOfEventType = {?} order by EventName asc";
		$eventsDB = $db->select($q, array($type["CodeOfEventType"]));

		foreach ($eventsDB as $event) {
			$events[$type["CodeOfEventType"]][$event["CodeOfEvent"]] = $event["EventName"];
			if ($event["Date"] != "0000-00-00") {
				$eventDates[$event["CodeOfEvent"]] = date("Y-m-d", strtotime($event["Date"]));
			}
		}
		$eventTypes[$sort["CodeOfEventSort"]][$type["CodeOfEventType"]] = $type["TypeName"];
	}
	$eventSorts[$sort["CodeOfEventSort"]] = $sort["SortName"];
}

// факультуты
$faculties = array();
// курсы
$kurses = array();
// группы
$groups = array();

// список групп
$q = "select CodeOfStudentGroup, GroupName, CodeOfStream, CodeOfDepart from studentgroups order by GroupName asc";
$groupsDB = $db->select($q);
foreach ($groupsDB as $group) {
	// курс группы
	$q = "select Kurs from streams where CodeOfStream = {?}";
	$kurs = $db->selectCell($q, array($group["CodeOfStream"]));

	// факультет группы
	$q = "select faculty.CodeOfFaculty, faculty.FacName ".
		"from department, faculty ".
		"where department.CodeOfDepart = {?} ".
		"and department.CodeOfFaculty = faculty.CodeOfFaculty";
	$facultyDB = $db->selectRow($q, array($group["CodeOfDepart"]));
	$faculties[$facultyDB["CodeOfFaculty"]] = $facultyDB["FacName"];

	$kurses[$facultyDB["CodeOfFaculty"]][$kurs] = $kurs;
	$groups[$facultyDB["CodeOfFaculty"]][$kurs][$group["CodeOfStudentGroup"]] = $group["GroupName"];
}
?>

	<div id="page-wrapper">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Внеучебная активность</h1>
			</div>
			<!-- /.col-lg-12 -->
		</div>
		<div class="panel-body">
			<div class="panel-body">
				<a href="new-event.php" type="submit" class="btn btn-default">Новое мероприятие</a>
			</div>
			<form role="form" action="" name="fed" method="post">
				<div class="row panel-body">
					<div class="form-group">
						<div class="col-lg-4">
							<label>Тип мероприятия</label>
							<select class="form-control js-event-sort" name="CodeOfEventSort">
								<?foreach ($eventSorts as $code => $sort):?>
									<option value="<?=$code?>"><?=$sort?></option>
								<?endforeach;?>
							</select>
						</div>
						<div class="col-lg-4">
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
						<div class="col-lg-4">
							<label>Мероприятие</label>
							<select class="form-control js-events" name="CodeOfEvent">
								<?
								reset($eventTypes[$firstSortCode]);
								list($firstTypeCode, $value) = each($eventTypes[$firstSortCode]);
								foreach ($events[$firstTypeCode] as $code => $event):?>
									<option value="<?=$code?>"><?=$event?></option>
								<?endforeach;?>
							</select>
						</div>
					</div>
				</div>
				<div class="row panel-body">
					<div class="form-group">
						<div class="col-lg-4">
							<label>Факультет</label>
							<select class="form-control js-faculty" name="CodeOfFaculty">
								<option value="0">Не выбрано</option>
								<?foreach ($faculties as $code => $faculty):?>
									<option value="<?=$code?>"><?=$faculty?></option>
								<?endforeach?>
							</select>
						</div>
						<div class="col-lg-4">
							<label>Курс</label>
							<select class="form-control js-kurs" name="Kurs">
								<option value="0">Не выбрано</option>
							</select>
						</div>
						<div class="col-lg-4">
							<label>Группа</label>
							<select class="form-control js-group" name="CodeOfStudentGroup">
								<option value="0">Не выбрано</option>
							</select>
						</div>
					</div>
				</div>
				<div class="panel-body">
					<div class="panel panel-default">
						<div class="panel-heading">
							<div class="form-group input-group">
								<span class="input-group-addon">Дата мероприятия</span>
								<input class="form-control js-event-date" type="date" id="date" name="Date" value="">

								<div class="input-group-btn">
									<button type="submit" class="btn btn-default" name='editTime' value='Изменить'>
										Изменить
									</button>
								</div>
								<!-- /btn-group -->
							</div>
						</div>
						<!-- /.panel-heading -->
						<div class="panel-body">
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-hover" id="marks">
									<thead>
									<tr>
										<th>ФИО</th>
										<th>Степень участия</th>
									</tr>
									</thead>
									<tbody class="js-student-table">
										<?/*<tr>
											<td>Иванова Дарья</td>
											<td>
												<select class="form-control">
													<option value=""></option>
													<option value="5">Участие</option>
													<option value="4">4</option>
													<option value="3">3</option>
												</select>
											</td>
										</tr>
										<tr>
											<td>Соловьев Владислав</td>
											<td>
												<select class="form-control">
													<option value=""></option>
													<option value="5">Организация</option>
													<option value="4">4</option>
													<option value="3">3</option>
												</select>
											</td>
										</tr>*/?>
									</tbody>
								</table>
							</div>
							<!-- /.table-responsive -->
							<button type="submit" class="btn btn-default" name="editPartDegree" value="Внести изменения">
								Внести изменения
							</button>
						</div>
						<!-- /.panel-body -->
					</div>
				</div>
			</form>
		</div>
	</div>
<? include("../footer.php"); ?>

<script type="text/javascript">
	var eventTypes = <?php echo json_encode($eventTypes)?>;
	var events = <?php echo json_encode($events)?>;
	var eventDates = <?php echo json_encode($eventDates)?>;

	var kuses = <?php echo json_encode($kurses)?>;
	var groups = <?php echo json_encode($groups)?>;

	function compareObjects (a, b) {
		if (a.name > b.name) return 1;
		if (a.name < b.name) return -1;
		return 0;
	};

	// изменение списка видов мероприятий при изменении типа мероприятия
	$('.js-event-sort').on('change', function() {
		var types = eventTypes[this.value];
		$('.js-event-type').find('option').remove().end().append('<option></option>').find('option').remove().end();
		if (types != undefined) {
			var typesNew = [];
			for (var i in types) {
				typesNew.push({ id: i, name: types[i]});
			}
			typesNew.sort(compareObjects);

			$.each(typesNew, function(key, value) {
				$('.js-event-type').append($("<option/>", {value: value.id, text: value.name}));
			});
		}
		$('.js-event-type').change();

		$('.js-student-table tr').remove();
	});

	// изменение списка мероприятий при изменении вида мероприятия
	$('.js-event-type').on('change', function() {
		var eventItems = events[this.value];
		$('.js-events').find('option').remove().end().append('<option></option>').find('option').remove().end();
		$('.js-event-date').val("");
		if (eventItems != undefined) {
			var eventsNew = [];
			for (var i in eventItems) {
				eventsNew.push({ id: i, name: eventItems[i]});
			}
			eventsNew.sort(compareObjects);

			$.each(eventsNew, function(key, value) {
				$('.js-events').append($("<option/>", {value: value.id, text: value.name}));
			});
		}
		$('.js-events').change();

		$('.js-student-table tr').remove();
	});

	// запрос списка студентов после определения мероприятия
	$('.js-events').on('change', function() {
		$('.js-event-date').val(eventDates[this.value]);
		if (this.value != null && this.value != "") {
			getStudentsTable(this.value, $('.js-faculty').val(), $('.js-kurs').val(), $('.js-group').val());
		}
	});

	// изменение список курсов при изменении факультета
	$('.js-faculty').on('change', function() {
		var kursItems = kuses[this.value];
		$('.js-kurs').find('option').remove().end().append('<option value="0">Не выбрано</option>');
		$('.js-group').find('option').remove().end().append('<option value="0">Не выбрано</option>');
		if (kursItems != undefined) {
			var kursNew = [];
			for (var i in kursItems) {
				kursNew.push({ id: i, name: kursItems[i]});
			}
			kursNew.sort(compareObjects);

			$.each(kursNew, function(key, value) {
				$('.js-kurs').append($("<option/>", {value: value.id, text: value.name}));
			});
		}
		$('.js-student-table tr').remove();
		if ($('.js-events').val() != null && $('.js-events').val() != "") {
			getStudentsTable($('.js-events').val(), this.value, $('.js-kurs').val(), $('.js-group').val());
		}
	});

	// изменение список групп при изменении курса
	$('.js-kurs').on('change', function() {
		var groupItems = groups[$('.js-faculty').val()][this.value];
		$('.js-group').find('option').remove().end().append('<option value="0">Не выбрано</option>');
		if (groupItems != undefined) {
			var groupNew = [];
			for (var i in groupItems) {
				groupNew.push({ id: i, name: groupItems[i]});
			}
			groupNew.sort(compareObjects);

			$.each(groupNew, function(key, value) {
				$('.js-group').append($("<option/>", {value: value.id, text: value.name}));
			});
		}
		$('.js-student-table tr').remove();
		if ($('.js-events').val() != null && $('.js-events').val() != "") {
			getStudentsTable($('.js-events').val(), $('.js-faculty').val(), this.value, $('.js-group').val());
		}
	});

	// запрос списка студентов после выбора группы
	$('.js-group').on('change', function() {
		$('.js-student-table tr').remove();
		if ($('.js-events').val() != null && $('.js-events').val() != "") {
			getStudentsTable($('.js-events').val(), $('.js-faculty').val(), $('.js-kurs').val(), this.value);
		}
	});

	// получение списка студентов
	function getStudentsTable(eventCode, facultyCode, kurs, groupCode) {
		$.ajax({
			url: '../ajax/eventsStudents.php',
			type: 'POST',
			dataType: 'json',
			data: {
				CodeOfEvent: eventCode,
				CodeOfFaculty: facultyCode,
				Kurs: kurs,
				CodeOfStudentGroup: groupCode
			},
			success: function (data) {
				$('.js-student-table tr').remove();
				console.log(data);
				$('.js-student-table').html(data.html);
			}
		});
	}
</script>