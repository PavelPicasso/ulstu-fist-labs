<?
session_start();
if ($_SESSION["userRole"] != "tch") {
	Header("Location: ../../index.php");
	exit;
} else {
	$CodeOfTeacher = $_SESSION["userRoleId"];
}
?>

<? include("../../header.php"); ?>

<?
if (isset($_POST["ReportName"])) {
	if ($_POST["ReportName"] != "") {
		$q = "select CodeOfReport from reports where ReportName={?} and CodeOfReportType={?} and CodeOfTeacher={?} and CodeOfSchPlanItem={?} and CodeOfStudentGroup={?}";
		$report = $db->selectCell($q, array($_POST['ReportName'], $_POST['CodeOfReportType'], $CodeOfTeacher, $_POST['CodeOfSchPlanItem'], $_POST['CodeOfStudentGroup']));
		if (!empty($report)) {
			$errorMsg = "Такая отчетность уже существует";
		} else {
			$q = "INSERT INTO reports (ReportName, CodeOfReportType, CodeOfTeacher, CodeOfSchPlanItem, CodeOfStudentGroup, Deadline) " .
				"values ({?}, {?}, {?}, {?}, {?}, {?})";
			$db->query($q, array($_POST["ReportName"], $_POST["CodeOfReportType"], $CodeOfTeacher, $_POST["CodeOfSchPlanItem"], $_POST["CodeOfStudentGroup"], $_POST["Deadline"]));
			$successMsg = 'Отчетность успешно добавлена. <a href="marks.php" class="alert-link">Вернуться в Учебный процесс.</a>';
		}
	} else {
		$errorMsg = "Не введено название отчетности";
	}
}
?>

	<div id="page-wrapper" style="min-height: 600px">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Новая отчетность</h1>
			</div>
			<!-- /.col-lg-12 -->
		</div>
		<?php
		$reportsArray = array();
		$q="SELECT teachersDiscips.CodeOfTeacherDiscip, disciplins.DisName, disciplins.CodeOfDiscipline, ".
			"schedplan.CodeOfSchPlan, schplanitemshours.CodeOfSchPlanItem, schplanitemshours.NumbOfSemestr, plans.CodeOfPlan ".
			"FROM teachersDiscips, disciplins, schedplan, schplanitemshours, plans ".
			"WHERE teachersDiscips.CodeOfTeacher = {?} ".
			"AND schplanitemshours.CodeOfSchPlanItem = teachersDiscips.CodeOfSchPlanItem ".
			"AND schedplan.CodeOfSchPlan = schplanitemshours.CodeOfSchPlan ".
			"AND disciplins.CodeOfDiscipline = schedplan.CodeOfDiscipline ".
			"AND schedplan.CodeOfPlan = plans.CodeOfPlan ".
			"AND (plans.Date IS NULL OR plans.Date = '0000-00-00') ".
			"ORDER BY disciplins.DisName, schplanitemshours.NumbOfSemestr ASC";

		$disciplines = $db->select($q, array($CodeOfTeacher));
		foreach ($disciplines as $dis) {
			$CodeOfTeachersDiscip = $dis['CodeOfTeacherDiscip'];
			$CodeOfSchPlan = $dis['CodeOfSchPlan'];
			$CodeOfPlan = $dis['CodeOfPlan'];
			$DisName = $dis['DisName'];

			$GlobalSem = $dis['NumbOfSemestr'];
			if ($GlobalSem % 2 == 0) {
				$Semestr = " (2 семестр)";
			} else {
				$Semestr = " (1 семестр)";
			}
			//$qstream = "select distinct streams.StreamName from streams where streams.CodeOfPlan = ".$CodeOfPlan." order by streams.StreamName";
			$q = "select distinct studentgroups.CodeOfStudentGroup, studentgroups.GroupName, studentgroups.CodeOfStream ".
				"from studentgroups, streams ".
				"where studentgroups.CodeOfStream = streams.CodeOfStream ".
				"and streams.CodeOfPlan = {?} ".
				"order by studentgroups.GroupName";
			$groups = $db->select($q, array($dis['CodeOfPlan']));
			if (empty($groups)) {
				continue;
			}
			$groupsArray = array();
			foreach ($groups as $group) {
				$groupsArray[] = array("GroupCode" => $group['CodeOfStudentGroup'],
					"GroupName" => $group['GroupName']);
			}
			$reportsArray[] = array("CodeOfSchPlanItem" => $dis['CodeOfSchPlanItem'],
				"Dis" => $dis['DisName'].$Semestr, "Groups" => $groupsArray);
		}

		$q = "select CodeOfReportType, TypeName from reporttypes where GlobalType = 1";
		$reportTypes = $db->select($q);
		?>
		<form role="form" name="fed" action="" method="post">
			<div class="col-lg-6">
				<?if (!empty($successMsg)):?>
					<div class="alert alert-success"><?=$successMsg?></div>
				<?endif;?>
				<?if (!empty($errorMsg)):?>
					<div class="alert alert-danger"><?=$errorMsg?></div>
				<?endif;?>
				<div class="form-group">
					<label>Дисциплина</label>
					<select class="form-control" name="CodeOfSchPlanItem" onChange="getGroupsByDis(this.value)">
						<?foreach ($reportsArray as $dis):?>
							<option value="<?=$dis['CodeOfSchPlanItem']?>"><?=$dis['Dis']?></option>
						<?endforeach;?>
					</select>
				</div>
				<div class="form-group">
					<label>Группа</label>
					<select class="form-control" name="CodeOfStudentGroup">
						<option value="N/A">N/A</option>
					</select>
				</div>
				<div class="form-group">
					<label>Вид отчетности</label>
					<select class="form-control" name="CodeOfReportType">
						<option value="1">Лабораторная работа</option>
						<option value="2">Экзамен</option>
						<?foreach ($reportTypes as $type):?>
							<option value="<?=$type['CodeOfReportType']?>"><?=$type['TypeName']?></option>
						<?endforeach;?>
					</select>
				</div>
				<div class="form-group">
					<label>Название</label>
					<input class="form-control" type="text" name="ReportName">
				</div>
				<div class="form-group">
					<label>Срок сдачи</label>
					<input class="form-control" type="date" name="Deadline">
				</div>
				<button type="submit" class="btn btn-default" name="NewReport">Добавить отчетность</button>
				<a href="marks.php" class="btn btn-default">Отменить</a>
			</div>
		</form>
	</div>
	<script type="text/javascript">
		var reports = <?php echo json_encode($reportsArray)?>;

		function getDisId(value){
			for (var i = 0; i < reports.length; i++) {
				if (reports[i].CodeOfSchPlanItem == value) {
					return i;
				}
			};
		}

		function getGroupsByDis(value) {
			//alert(value);
			//alert(reports[0].Dis);
			var disId = getDisId(value);
			//alert(value);
			var groups = reports[disId].Groups;
			var groupCount = groups.length;
			var groupList = document.forms["fed"].elements["CodeOfStudentGroup"];
			var groupListCount = groupList.options.length;
			groupList.length = 0; // удаляем все элементы из списка домов
			for (i = 0; i < groupCount; i++) {
				// далее мы добавляем необходимые дома в список
				if (document.createElement) {
					var newGroupList = document.createElement("OPTION");
					newGroupList.text = groups[i].GroupName;
					newGroupList.value = groups[i].GroupCode;
					// тут мы используем для добавления элемента либо метод IE, либо DOM, которые, alas, не совпадают по параметрам…
					(groupList.options.add) ? groupList.options.add(newGroupList) : groupList.add(newGroupList, null);
					//(oHouseList.options.add) ? oHouseList.options.add(newHouseListOption) : oHouseList.add(newHouseListOption, null);
				} else {
					// для NN3.x-4.x
					groupList.options[i] = new Option(groupNames[i], groupNames[i], false, false);
				}
			}
		}

		getGroupsByDis(document.forms["fed"].elements["CodeOfSchPlanItem"].value);
	</script>

<? include("../../footer.php"); ?>