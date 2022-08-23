<?
if (!isset($_POST["getNorms"]) || empty($_POST["pointsCount"]) || ($_POST["pointsCount"] * 1 <= 0)) {
	Header ("Location: index.php");
	exit;
}
$pointsCount = $_POST["pointsCount"];

include("../header.php");

// получение видов отчетности
$q = "select CodeOfReportType, TypeName, RatingNorm from reporttypes";
$reportTypes = $db->select($q);

$reports = array();
$counts = array();
foreach ($reportTypes as $type) {
	// получение списка оценок, связанных с видом отчетности
	$q = "select reportmarks.MarkName, reportmarks.RatingNorm ".
		"from reportmarktotype, reportmarks, reporttypes ".
		"where reportmarks.CodeOfReportMark = reportmarktotype.CodeOfReportMark ".
		"and reportmarktotype.CodeOfReportType = reporttypes.CodeOfReportType ".
		"and reporttypes.CodeOfReportType = {?}";
	$markNorms = $db->select($q, array($type["CodeOfReportType"]));

	// если норма не установлена, то записываем 1, чтобы при умножении произведение не изменилось
	$typeNorm = ($type["RatingNorm"] > 0) ? $type["RatingNorm"] : 1;

	foreach ($markNorms as $mark) {
		// если норма не установлена, то записываем 1, чтобы при умножении произведение не изменилось
		$markNorm = ($mark["RatingNorm"] > 0) ? $mark["RatingNorm"] : 1;
		if ($mark["RatingNorm"] > 0 || $type["RatingNorm"] > 0) {
			// получение количества единиц отчености для получения $pointsCount количества баллов
			$count = $pointsCount / ($markNorm * $typeNorm);
			$reports[] = array($type["TypeName"], $mark["MarkName"], $count);
			$counts[] = $count;
		}
	}
}

// получение видов мероприятий
$q = "select TypeName, RatingNorm from eventtypes";
$events = $db->select($q);

// получение степеней участия
$q = "select DegreeName, RatingNorm from eventpartdegree";
$degrees = $db->select($q);

foreach ($events as $event) {
	// если норма не установлена, то записываем 1, чтобы при умножении произведение не изменилось
	$eventNorm = ($event["RatingNorm"] > 0) ? $event["RatingNorm"] : 1;

	foreach ($degrees as $degree) {
		// если норма не установлена, то записываем 1, чтобы при умножении произведение не изменилось
		$degreeNorm = ($degree["RatingNorm"] > 0) ? $degree["RatingNorm"] : 1;
		if ($event["RatingNorm"] > 0 || $degree["RatingNorm"] > 0) {
			// получение количества единиц отчености для получения $pointsCount количества баллов
			$count = $pointsCount / ($eventNorm * $degreeNorm);
			$reports[] = array($event["TypeName"], $degree["DegreeName"], $count);
			$counts[] = $count;
		}
	}
}

// сортировать по количеству единиц отчетности
array_multisort($counts, SORT_ASC, $reports);
?>
	<div id="page-wrapper">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Трудоемкость работ, дающих приращение рейтинга <?=$pointsCount?> баллов</h1>
			</div>
			<!-- /.col-lg-12 -->
		</div>
		<div class="panel-body">
			<form role="form" action="" method="post">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover">
								<thead>
								<tr>
									<th>Вид работы</th>
									<th>Единицы</th>
									<th>Количество единиц</th>
								</tr>
								</thead>
								<tbody>
									<?foreach ($reports as $row):?>
										<tr>
											<?foreach ($row as $cell):?>
												<td><?=$cell?></td>
											<?endforeach;?>
										</tr>
									<?endforeach;?>
								</tbody>
							</table>
						</div>
						<!-- /.table-responsive -->
					</div>
					<!-- /.panel-body -->
				</div>
			</form>
		</div>
	</div>
<?include("../footer.php");?>