<?
session_start();
if ($_SESSION["statusName"] != "uc") {
	Header("Location: ./index.php");
	exit;
}

include("../header.php");

// типы мероприятий
$eventSorts = array();
// виды мероприятий
$eventTypes = array();
// мероприятия
$events = array();

// получение типов мероприятий
$q = "select CodeOfEventSort, SortName from eventsorts";
$eventSortsDB = $db->select($q);
foreach ($eventSortsDB as $sort) {
	// получение видов мероприятий по типу
	$q = "select CodeOfEventType, TypeName from eventtypes where CodeOfEventSort = {?} order by TypeName asc";
	$eventTypesDB = $db->select($q, array($sort["CodeOfEventSort"]));

	foreach ($eventTypesDB as $type) {
		// получение мероприятий по виду
		$q = "select CodeOfEvent, EventName from events where CodeOfEventType = {?} order by EventName asc";
		$eventsDB = $db->select($q, array($type["CodeOfEventType"]));

		foreach ($eventsDB as $event) {
			$events[$type["CodeOfEventType"]][$event["CodeOfEvent"]] = $event["EventName"];
		}
		$eventTypes[$sort["CodeOfEventSort"]][$type["CodeOfEventType"]] = $type["TypeName"];
	}
	$eventSorts[$sort["CodeOfEventSort"]] = $sort["SortName"];
}

// дисциплины
$disciplines = array();

$q = "select CodeOfDiscipline, DisName from disciplins order by DisName asc";
$discipsDB = $db->select($q);
foreach ($discipsDB as $discip) {
	if (!empty($discip["DisName"])) {
		if (!empty($disciplines[$discip["DisName"]])) {
			$disciplines[$discip["DisName"]] .= "-".$discip["CodeOfDiscipline"];
		} else {
			$disciplines[$discip["DisName"]] = $discip["CodeOfDiscipline"];
		}
	}
}

// факультуты
$faculties = array();
// кафедры
$departments = array();
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
	$q = "select faculty.CodeOfFaculty, faculty.FacName, department.CodeOfDepart, department.DepName ".
		"from department, faculty ".
		"where department.CodeOfDepart = {?} ".
		"and department.CodeOfFaculty = faculty.CodeOfFaculty";
	$facultyDB = $db->selectRow($q, array($group["CodeOfDepart"]));
	$departments[$facultyDB["CodeOfFaculty"]][$facultyDB["CodeOfDepart"]] = $facultyDB["DepName"];
	$faculties[$facultyDB["CodeOfFaculty"]] = $facultyDB["FacName"];

	$kurses[$kurs] = $kurs;
	$groups[$group["CodeOfStudentGroup"]] = array(
		"CodeOfFaculty" => $facultyDB["CodeOfFaculty"],
		"CodeOfDepart" => $group["CodeOfDepart"],
		"Kurs" => $kurs,
		"GroupName" => $group["GroupName"]
	);
}
asort($faculties);
asort($kurses);


// создание нового рейтинга
if (!empty($_POST["addRating"])) {
	$errors = array();
	if (empty($_POST["RatingName"])) {
		$errors[] = "Не введено название рейтинга";
	}
	if (empty($_POST["DateFrom"])) {
		$errors[] = "Не выбрана дата начала фиксирования";
	}
	if (empty($_POST["DateTo"])) {
		$errors[] = "Не выбрана дата окончания фиксирования";
	}

	if (!empty($errors)) {
		$errorMsg = implode("<br>", $errors);
	} else {
		$dateFrom = $_POST["DateFrom"];
		$dateTo = $_POST["DateTo"];

		$publishDate = date("Y-m-d");

		if ($_POST["RatingStatic"] == "1") {
			if ($dateTo > $publishDate) {
				$dateTo = $publishDate;
			}
		}

		if ($dateTo < $dateFrom) {
			$dateFrom = $dateTo;
		}

		$fields = array("RatingName", "DateFrom", "DateTo", "PublishDate");
		$fieldValues = array("{?}", "{?}", "{?}", "{?}");
		$values = array($_POST["RatingName"], $dateFrom, $dateTo, $publishDate);

		if (!empty($_POST["CodeOfFaculty"])) {
			$fields[] = "CodeOfFaculty";
			$fieldValues[] = "{?}";
			$values[] = $_POST["CodeOfFaculty"];
		}
		if (!empty($_POST["CodeOfDepart"])) {
			$fields[] = "CodeOfDepart";
			$fieldValues[] = "{?}";
			$values[] = $_POST["CodeOfDepart"];
		}
		if (!empty($_POST["Kurs"])) {
			$fields[] = "Kurs";
			$fieldValues[] = "{?}";
			$values[] = $_POST["Kurs"];
		}

		$q = "INSERT INTO ratings (".implode(", ", $fields).") values (".implode(", ", $fieldValues).")";
		$ratingId = $db->query($q, $values);

		if (!empty($_POST["CodeOfStudentGroup"])) {
			foreach ($_POST["CodeOfStudentGroup"] as $group) {
				$q = "INSERT INTO ratinggroups (CodeOfRating, CodeOfStudentGroup) values ({?}, {?})";
				$db->query($q, array($ratingId, $group));
			}
		}

		if ($_POST["ActivityType"] == "report") {
			if (!empty($_POST["CodeOfDiscipline"])) {
				$ratingDis = explode("-", $_POST["CodeOfDiscipline"]);

				foreach ($ratingDis as $disId) {
					$q = "INSERT INTO ratingdiscips (CodeOfRating, CodeOfDiscipline) values ({?}, {?})";
					$db->query($q, array($ratingId, $disId));
				}
			} else {
				$q = "INSERT INTO ratingdiscips (CodeOfRating) values ({?})";
				$db->query($q, array($ratingId));
			}
		} elseif ($_POST["ActivityType"] == "event") {
			$fields = array("CodeOfRating");
			$fieldValues = array("{?}");
			$values = array($ratingId);

			if (!empty($_POST["CodeOfEventSort"])) {
				$fields[] = "CodeOfEventSort";
				$fieldValues[] = "{?}";
				$values[] = $_POST["CodeOfEventSort"];
			}
			if (!empty($_POST["CodeOfEventType"])) {
				$fields[] = "CodeOfEventType";
				$fieldValues[] = "{?}";
				$values[] = $_POST["CodeOfEventType"];
			}
			if (!empty($_POST["CodeOfEvent"])) {
				$fields[] = "CodeOfEvent";
				$fieldValues[] = "{?}";
				$values[] = $_POST["CodeOfEvent"];
			}
			$q = "INSERT INTO ratingevents (".implode(", ", $fields).") values (".implode(", ", $fieldValues).")";
			$db->query($q, $values);
		}

		$successMsg = 'Рейтинг успешно добавлен. <a href="./index.php" class="alert-link">Вернуться к списку рейтингов.</a>';
	}
}
?>

<div id="page-wrapper" style="height: 1000px">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Новый рейтинг</h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->

	<form method="post" action="" role="form">

		<div class="col-lg-12">
			<?if (!empty($successMsg)):?>
				<div class="alert alert-success"><?=$successMsg?></div>
			<?endif;?>
			<?if (!empty($errorMsg)):?>
				<div class="alert alert-danger"><?=$errorMsg?></div>
			<?endif;?>
			<div class="panel panel-default">
				<div class="panel-heading">
					Общая информация о рейтинге
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<div class="form-group">
						<label>Название рейтинга</label>
						<input class="form-control" type="text" name="RatingName">
					</div>
					<div class="form-group">
						<label>Начало фиксирования</label>
						<input class="form-control" type="date" name="DateFrom">
					</div>
					<div class="form-group">
						<label>Конец фиксирования</label>
						<input class="form-control" type="date" name="DateTo">
					</div>
					<div class="form-group">
						<label class="checkbox-inline" title="Конец фиксирования статичного рейтинга не может быть больше текущей даты">
							<input type="checkbox" name="RatingStatic" value="1">Рейтинг статичный
						</label>
					</div>
					<p class="text-warning">Конец фиксирования статического рейтинга не может быть больше текущей даты.<br>
						Если указана более поздняя дата, она будет приведена к текущей.</p>
				</div>
				<!-- /.panel-body -->
			</div>
		</div>

		<div class="col-lg-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					Контингент
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<div class="form-group">
						<label>Факультет</label>
						<select class="form-control js-faculty" name="CodeOfFaculty">
							<option value="0">Все</option>
							<?foreach ($faculties as $code => $fac):?>
								<option value="<?=$code?>"><?=$fac?></option>
							<?endforeach;?>
						</select>
					</div>
					<div class="form-group">
						<label>Кафедра</label>
						<select class="form-control js-department" name="CodeOfDepart">
							<option value="0">Все</option>
						</select>
					</div>
					<div class="form-group">
						<label>Курс</label>
						<select class="form-control js-kurses" name="Kurs">
							<option value="0">Все</option>
							<?foreach ($kurses as $kurs):?>
								<option value="<?=$kurs?>"><?=$kurs?></option>
							<?endforeach;?>
						</select>
					</div>
					<div class="form-group">
						<label>Группы</label>
						<select multiple="" class="form-control js-groups" name="CodeOfStudentGroup[]">
							<?foreach ($groups as $code => $group):?>
								<option value="<?=$code?>"
										data-fac="<?=$group["CodeOfFaculty"]?>"
										data-dep="<?=$group["CodeOfDepart"]?>"
										data-kurs="<?=$group["Kurs"]?>"><?=$group["GroupName"]?></option>
							<?endforeach;?>
						</select>
					</div>

				</div>
				<!-- /.panel-body -->
			</div>
		</div>
		<div class="col-lg-6">
			<div class="panel panel-default">
			<div class="panel-heading">
				Источник данных
			</div>
			<!-- /.panel-heading -->
			<div class="panel-body">
				<div class="form-group">
					<label>Тип активности</label>
					<select class="form-control js-group-type" name="ActivityType">
						<option value="0">Все</option>
						<option value="report">Учебная</option>
						<option value="event">Внеучебная</option>
					</select>
				</div>
				<?/*Учебная активность*/?>
				<div class="form-group js-report-group" style="display: none;">
					<label>Дисциплина</label>
					<select class="form-control" name="CodeOfDiscipline">
						<option value="0">Все</option>
						<?foreach ($disciplines as $discip => $code):?>
							<option value="<?=$code?>"><?=$discip?></option>
						<?endforeach;?>
					</select>
				</div>

				<?/*Внеучебная активность*/?>
				<div class="form-group js-event-group" style="display: none;">
					<label>Тип мероприятия</label>
					<select class="form-control js-event-sort" name="CodeOfEventSort">
						<option value="0">Все</option>
						<?foreach ($eventSorts as $code => $sort):?>
							<option value="<?=$code?>"><?=$sort?></option>
						<?endforeach;?>
					</select>
				</div>
				<div class="form-group js-event-group" style="display: none;">
					<label>Вид мероприятия</label>
					<select class="form-control js-event-type" name="CodeOfEventType">
						<option value="0">Все</option>
					</select>
				</div>
				<div class="form-group js-event-group" style="display: none;">
					<label>Мероприятие</label>
					<select class="form-control js-events" name="CodeOfEvent">
						<option value="0">Все</option>
					</select>
				</div>
			</div>
			<!-- /.panel-body -->
		</div>
		</div>

		<div class="col-lg-12">
			<div class="panel-body">
				<button type="submit" class="btn btn-default" name="addRating" value="add">Добавить рейтинг</button>
				<a href="./index.php" class="btn btn-default">Отменить</a>
			</div>
		</div>
	</form>
</div>
<!-- /#page-wrapper -->
<?include("../footer.php");?>
<script type="text/javascript">
	var eventTypes = <?php echo json_encode($eventTypes)?>;
	var events = <?php echo json_encode($events)?>;
	var eventDates = <?php echo json_encode($eventDates)?>;

	var departments = <?php echo json_encode($departments)?>;

	function compareObjects (a, b) {
		if (a.name > b.name) return 1;
		if (a.name < b.name) return -1;
		return 0;
	};

	$('.js-group-type').on('change', function() {
		$('.js-report-group').hide();
		$('.js-event-group').hide();
		if (this.value == "report") {
			$('.js-report-group').show();
		}
		if (this.value == "event") {
			$('.js-event-group').show();
		}
	});

	// изменение списка видов мероприятий при изменении типа мероприятия
	$('.js-event-sort').on('change', function() {
		var types = eventTypes[this.value];
		$('.js-event-type').find('option').remove().end().append($("<option/>", {value: 0, text: "Все"}));
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
	});

	// изменение списка мероприятий при изменении вида мероприятия
	$('.js-event-type').on('change', function() {
		var eventItems = events[this.value];
		$('.js-events').find('option').remove().end().append($("<option/>", {value: 0, text: "Все"}));
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
	});

	function changeGroups(faculty, department, kurs) {
		$.each($('.js-groups option'), function() {
			this.removeAttribute('disabled');
			this.style.display = "block";
			$(this).prop('selected', false);
		});
		if (department != null && department != "0") {
			$.each($('.js-groups option'), function() {
				if (this.getAttribute("data-dep") != department) {
					this.style.display = "none";
					this.setAttribute('disabled', true);
				}
			});
		} else {
			if (faculty != null && faculty != "0") {
				$.each($('.js-groups option'), function() {
					if (this.getAttribute("data-fac") != faculty) {
						this.style.display = "none";
						this.setAttribute('disabled', true);
					}
				});
			}
		}
		if (kurs != null && kurs != "0") {
			$.each($('.js-groups option'), function() {
				if (this.getAttribute("data-kurs") != kurs) {
					this.style.display = "none";
					this.setAttribute('disabled', true);
				}
			});
		}
	}

	// изменение списка кафедр при изменении факультета
	$('.js-faculty').on('change', function() {
		var deps = departments[this.value];
		$('.js-department').find('option').remove().end().append($("<option/>", {value: 0, text: "Все"}));
		if (deps != undefined) {
			var depsNew = [];
			for (var i in deps) {
				depsNew.push({ id: i, name: deps[i]});
			}
			depsNew.sort(compareObjects);

			$.each(depsNew, function(key, value) {
				$('.js-department').append($("<option/>", {value: value.id, text: value.name}));
			});
		}
		$('.js-department').change();
		changeGroups(this.value, null, $('.js-kurses').val());
	});

	// изменение списка групп при изменении кафедры
	$('.js-department').on('change', function() {
		changeGroups(null, this.value, $('.js-kurses').val());
	});

	// изменение списка групп при изменении курса
	$('.js-kurses').on('change', function() {
		changeGroups($('.js-faculty').val(), $('.js-department').val(), this.value);
	});
</script>