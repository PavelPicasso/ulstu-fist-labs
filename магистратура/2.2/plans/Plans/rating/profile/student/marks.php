<?
session_start();
if ($_SESSION["userRole"] != "st") {
	Header("Location: ../../index.php");
	exit;
} else {
	$CodeOfStudent = $_SESSION["userRoleId"];
}
?>

<? include("../../header.php"); ?>

<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Оценки и баллы</h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<?
	/*$q = "select distinct studentmarks.CodeOfStudentMark, studentmarks.PassDate, studentmarks.Mark, reports.CodeOfReport, reports.ReportName, reporttypes.TypeName, reporttypes.GlobalType " .
		"from studentmarks, reports, reporttypes " .
		"where studentmarks.CodeOfStudent = {?} " .
		"and studentmarks.CodeOfReport = reports.CodeOfReport ".
		"and reports.CodeOfReportType = reporttypes.CodeOfReportType ".
		"order by reporttypes.GlobalType asc, studentmarks.PassDate asc";
	$reports = $db->select($q, array($CodeOfStudent));
	$marks = array();
	foreach ($reports as $report) {
		$marks[$report["GlobalType"]][] = $report;
	}*/
	?>
	<div class="panel panel-default">
		<div class="panel-heading">
			Учебная деятельность
		</div>
		<!-- /.panel-heading -->
		<div class="panel-body">
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover">
					<thead>
					<tr>
						<th>Предмет</th>
						<th>Вид отчетности</th>
						<th>Дата сдачи</th>
						<th>Оценка</th>
					</tr>
					</thead>
					<tbody>
						<tr>
							<td>Проектирование программных систем</td>
							<td>Лабораторная работа №1</td>
							<td>12.10.2015</td>
							<td>4</td>
						</tr>
						<tr>
							<td>Философия</td>
							<td>Реферат</td>
							<td>09.11.2015</td>
							<td>5</td>
						</tr>
						<tr>
							<td>Проектирование программных систем</td>
							<td>Лабораторная работа №2</td>
							<td>25.11.2015</td>
							<td>5</td>
						</tr>
					<?/*if (!empty($marks[1])):?>
						<?foreach ($marks[1] as $mark):?>
							<?
							$q = "select disciplins.DisName ".
								"from disciplins,  schedplan, schplanitems, reports ".
								"where disciplins.CodeOfDiscipline = schedplan.CodeOfDiscipline ".
								"and schedplan.CodeOfSchPlan = schplanitems.CodeOfSchPlan ".
								"and schplanitems.CodeOfSchPlanItem = reports.CodeOfSchPlanItem ".
								"and reports.CodeOfReport = {?}";
							$disName = $db->selectCell($q, array($mark["CodeOfReport"]));
							?>
							<tr>
								<td><?=$disName?></td>
								<td><?=$mark["ReportName"]?></td>
								<?
								$date = new DateTime($mark["PassDate"]);
								?>
								<td><?=$date->format('d.m.Y')?></td>
								<td><?=$mark["Mark"]?></td>
							</tr>
						<?endforeach;?>
					<?endif;*/?>
					</tbody>
				</table>
			</div>
			<!-- /.table-responsive -->
		</div>
		<!-- /.panel-body -->
	</div>
	<?/*<div class="panel panel-default">
		<div class="panel-heading">
			Научная деятельность
		</div>
		<!-- /.panel-heading -->
		<div class="panel-body">
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover">
					<thead>
					<tr>
						<th class="col-lg-6">Тип отчетности</th>
						<th class="col-lg-6">Вид отчетности</th>
					</tr>
					</thead>
					<tbody>
					<?if (!empty($marks[2])):?>
						<?foreach ($marks[2] as $mark):?>
							<tr>
								<td><?=$mark["TypeName"]?></td>
								<td><?=$mark["ReportName"]?></td>
							</tr>
						<?endforeach;?>
					<?endif;?>
					</tbody>
				</table>
			</div>
			<!-- /.table-responsive -->
		</div>
		<!-- /.panel-body -->
	</div>*/?>
	<div class="panel panel-default">
		<div class="panel-heading">
			Внеучебная деятельность
		</div>
		<!-- /.panel-heading -->
		<div class="panel-body">
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover">
					<thead>
					<tr>
						<th>Тип мероприятия</th>
						<th>Вид мероприятия</th>
						<th>Название мероприятия</th>
						<th>Дата участия</th>
						<th>Степень участия</th>
					</tr>
					</thead>
					<tbody>
						<tr>
							<td>Научное</td>
							<td>НИРС</td>
							<td>НИРС 2015</td>
							<td>12.2015</td>
							<td>Написание статьи</td>
						</tr>
						<tr>
							<td>Культурное</td>
							<td>Фестиваль</td>
							<td>Студенческая осень 2015</td>
							<td>15.10.2015</td>
							<td>Участник</td>
						</tr>
					<?/*if (!empty($marks[3])):?>
						<?foreach ($marks[3] as $mark):?>
							<tr>
								<td><?=$mark["TypeName"]?></td>
								<td><?=$mark["ReportName"]?></td>
							</tr>
						<?endforeach;?>
					<?endif;*/?>
					</tbody>
				</table>
			</div>
			<!-- /.table-responsive -->
		</div>
		<!-- /.panel-body -->
	</div>
</div>

<? include("../../footer.php"); ?>