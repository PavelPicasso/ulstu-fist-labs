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
if (isset($_POST['EditTime'])) {
	$q = "update reports set DeadLine={?} where CodeOfReport={?}";
	$db->query($q, array($_POST["Deadline"], $_POST["CodeOfReport"]));
}

if (isset($_POST['EditMarks'])) {
	$CodeOfReport = $_POST['CodeOfReport'];
	foreach ($_POST['Marks'] as $k => $mark) {
		$mark = $mark + 0;
		if (empty($mark)) {
			continue;
		}
		if (!is_int($mark)) {
			continue;
		}
		if ($mark < 3 || $mark > 5) {
			continue;
		}
		$PassDate = $_POST['PassDate'][$k];
		if (empty($PassDate)) {
			$PassDate = date("Y-m-d");
		}
		$q = "select CodeOfStudentMark from studentmarks where CodeOfReport={?} and CodeOfStudent={?}";
		$CodeOfStudentMark = $db->selectCell($q, array($CodeOfReport, $k));
		if (empty($CodeOfStudentMark)) {
			$q = "insert into studentmarks (CodeOfReport, CodeOfStudent, PassDate, Mark) values ({?}, {?}, {?}, {?})";
			$db->query($q, array($CodeOfReport, $k, $PassDate, $mark));
		} else {
			$q = "update studentmarks set PassDate={?}, Mark={?} where CodeOfStudentMark={?}";
			$db->query($q, array($PassDate, $mark, $CodeOfStudentMark));
		}
	}
}
?>
	<div id="page-wrapper">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Учебный процесс</h1>
			</div>
			<!-- /.col-lg-12 -->
		</div>
		<div class="panel-body">
			<?php
			$reportsArray = array();
			$q = "SELECT teachersDiscips.CodeOfTeacherDiscip, disciplins.DisName, disciplins.CodeOfDiscipline, " .
				"schedplan.CodeOfSchPlan, schplanitemshours.CodeOfSchPlanItem, schplanitemshours.NumbOfSemestr " .
				"FROM teachersDiscips, disciplins, schedplan, schplanitemshours, plans " .
				"WHERE teachersDiscips.CodeOfTeacher = {?} " .
				"AND schplanitemshours.CodeOfSchPlanItem = teachersDiscips.CodeOfSchPlanItem " .
				"AND schedplan.CodeOfSchPlan = schplanitemshours.CodeOfSchPlan " .
				"AND disciplins.CodeOfDiscipline = schedplan.CodeOfDiscipline " .
				"AND schedplan.CodeOfPlan = plans.CodeOfPlan " .
				"AND (plans.Date IS NULL OR plans.Date = '0000-00-00') " .
				"ORDER BY disciplins.DisName, schplanitemshours.NumbOfSemestr ASC";
			$disciplines = $db->select($q, array($CodeOfTeacher));
			foreach ($disciplines as $dis) {
				$GlobalSem = $dis['NumbOfSemestr'];
				if ($GlobalSem % 2 == 0) {
					$Semestr = " (2 семестр)";
				} else {
					$Semestr = " (1 семестр)";
				}

				$q = "select distinct reports.CodeOfStudentGroup, studentgroups.GroupName " .
					"from reports, studentgroups " .
					"where studentgroups.CodeOfStudentGroup = reports.CodeOfStudentGroup " .
					"and reports.CodeOfSchPlanItem = {?} " .
					"and reports.CodeOfTeacher = {?} " .
					"order by studentgroups.GroupName";

				$groups = $db->select($q, array($dis['CodeOfSchPlanItem'], $CodeOfTeacher));

				if (empty($groups)) {
					continue;
				}
				$groupsArray = array();
				foreach ($groups as $group) {
					$q = "select distinct reports.CodeOfReport, reports.ReportName, reports.Deadline " .
						"from reports " .
						"where reports.CodeOfStudentGroup = {?} " .
						"and reports.CodeOfSchPlanItem = {?} " .
						"and reports.CodeOfTeacher = {?} " .
						"order by reports.ReportName";

					$reports = $db->select($q, array($group['CodeOfStudentGroup'], $dis['CodeOfSchPlanItem'], $CodeOfTeacher));

					if (empty($reports)) {
						continue;
					}
					$repNamesArray = array();
					foreach ($reports as $rep) {
						$q = "select CodeOfStudent, StudentName from students where CodeOfStudentGroup = {?} order by StudentName asc";

						$students = $db->select($q, array($group['CodeOfStudentGroup']));

						$studentMarks = array();
						foreach ($students as $stud) {
							$q = "select distinct CodeOfStudentMark, PassDate, Mark " .
								"from studentmarks " .
								"where CodeOfStudent = {?} " .
								"and CodeOfReport = {?}";
							$marks = $db->selectRow($q, array($stud['CodeOfStudent'], $rep['CodeOfReport']));
							$CodeOfStudentMark = "";
							$PassDate = "";
							$Mark = "";
							if (!empty($marks)) {
								$CodeOfStudentMark = $marks['CodeOfStudentMark'];
								$PassDate = $marks['PassDate'];
								$Mark = $marks['Mark'];
							}
							$studentMarks[] = array("CodeOfStudent" => $stud['CodeOfStudent'],
								"StudentName" => $stud['StudentName'], "CodeOfStudentMark" => $CodeOfStudentMark,
								"PassDate" => $PassDate, "Mark" => $Mark);
						}
						$repNamesArray[] = array("ReportCode" => $rep['CodeOfReport'],
							"ReportName" => $rep['ReportName'], "Deadline" => $rep['Deadline'],
							"StudentMarks" => $studentMarks);
					}
					$groupsArray[] = array("GroupCode" => $group['CodeOfStudentGroup'],
						"GroupName" => $group['GroupName'], "Reports" => $repNamesArray);
				}
				$reportsArray[] = array("CodeOfSchPlanItem" => $dis['CodeOfSchPlanItem'],
					"Dis" => $dis['DisName'] . $Semestr, "Groups" => $groupsArray);
			}
			?>
			<form role="form" action="" name="fed" method="post">
				<div class="row">
					<div class="form-group">
						<div class="col-lg-4">
							<label>Дисциплина</label>
							<select class="form-control" name="CodeOfSchPlanItem" onChange="getGroupsByDis(this.value)">
								<? foreach ($reportsArray as $dis): ?>
									<option value="<?= $dis['CodeOfSchPlanItem'] ?>"><?= $dis['Dis'] ?></option>
								<? endforeach; ?>
							</select>
						</div>
						<div class="col-lg-4">
							<label>Группа</label>
							<select class="form-control" name="CodeOfStudentGroup"
									onChange="getReportsByGroup(CodeOfSchPlanItem.value, this.value)">
								<option value="N/A">N/A</option>
							</select>
						</div>
						<div class="col-lg-4">
							<label>Вид отчетности</label>
							<select class="form-control" name="CodeOfReport"
									onChange="getMarksByReport(CodeOfSchPlanItem.value, CodeOfStudentGroup.value, this.value)">
								<option value="N/A">N/A</option>
							</select>
						</div>
					</div>
				</div>
				<div class="panel-body">
					<div class="panel panel-default">
						<div class="panel-heading">
							<div class="form-group input-group">
								<span class="input-group-addon">Срок сдачи</span>
								<input class="form-control" type="date" id="deadline" name="Deadline">

								<div class="input-group-btn">
									<button type="submit" class="btn btn-default" name='EditTime' value='Изменить'>
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
										<th>Дата сдачи</th>
										<th>Оценка</th>
									</tr>
									</thead>
									<tbody>
										<tr>
											<td>Иванова Дарья</td>
											<td><input class="form-control" type="date" name="PassDate[idStud]" value=""></td>
											<td>
												<select class="form-control">
													<option value=""></option>
													<option value="5">5</option>
													<option value="4">4</option>
													<option value="3">3</option>
												</select>
											</td>
										</tr>
										<tr>
											<td>Соловьев Владислав</td>
											<td><input class="form-control" type="date" name="PassDate[idStud]" value=""></td>
											<td>
												<select class="form-control">
													<option value=""></option>
													<option value="5">5</option>
													<option value="4">4</option>
													<option value="3">3</option>
												</select>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							<!-- /.table-responsive -->
							<button type="submit" class="btn btn-default" name="EditMarks" value="Внести изменения">
								Внести изменения
							</button>
							<a href="new-report.php" type="submit" class="btn btn-default" name="NewReport"
							   value="Новая отчетность">Новая отчетность</a>
						</div>
						<!-- /.panel-body -->
					</div>
				</div>
			</form>
		</div>
	</div>
	<script type="text/javascript">
		var reports = <?php echo json_encode($reportsArray)?>;

		function getDisId(value) {
			for (var i = 0; i < reports.length; i++) {
				if (reports[i].CodeOfSchPlanItem == value) {
					return i;
				}
			}
			;
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
			getReportsByGroup(document.forms["fed"].elements["CodeOfSchPlanItem"].value,
				document.forms["fed"].elements["CodeOfStudentGroup"].value);
		}

		function getGroupId(groupsArr, value) {
			for (var i = 0; i < groupsArr.length; i++) {
				if (groupsArr[i].GroupCode == value) {
					return i;
				}
			}
		}

		function getReportsByGroup(discip, group) {
			//alert(discip);
			//alert(group);
			var disId = getDisId(discip);
			var groups = reports[disId].Groups;
			var groupId = getGroupId(groups, group);
			var reportNames = groups[groupId].Reports;
			//alert(groupNames[1]);
			var reportCount = reportNames.length;
			var reportList = document.forms["fed"].elements["CodeOfReport"];
			var reportListCount = reportList.options.length;
			reportList.length = 0; // удаляем все элементы из списка домов
			for (i = 0; i < reportCount; i++) {
				// далее мы добавляем необходимые дома в список
				if (document.createElement) {
					var newReportList = document.createElement("OPTION");
					newReportList.text = reportNames[i].ReportName;
					newReportList.value = reportNames[i].ReportCode;
					// тут мы используем для добавления элемента либо метод IE, либо DOM, которые, alas, не совпадают по параметрам…
					(reportList.options.add) ? reportList.options.add(newReportList) : reportList.add(newReportList, null);
					//(oHouseList.options.add) ? oHouseList.options.add(newHouseListOption) : oHouseList.add(newHouseListOption, null);
				} else {
					// для NN3.x-4.x
					reportList.options[i] = new Option(reportNames[i], reportNames[i], false, false);
				}
			}
			getMarksByReport(document.forms["fed"].elements["CodeOfSchPlanItem"].value,
				document.forms["fed"].elements["CodeOfStudentGroup"].value,
				document.forms["fed"].elements["CodeOfReport"].value);
		}

		function getReportId(reportsArr, value) {
			for (var i = 0; i < reportsArr.length; i++) {
				if (reportsArr[i].ReportCode == value) {
					return i;
				}
			}
		}

		/*function getMarksByReport(discip, group, report) {
			var disId = getDisId(discip);
			var groups = reports[disId].Groups;
			var groupId = getGroupId(groups, group);
			var reportItems = groups[groupId].Reports;
			var reportId = getReportId(reportItems, report);
			var studentMarks = reportItems[reportId].StudentMarks;
			var studentMarksCount = studentMarks.length;
			var deadline = document.getElementById("deadline");
			deadline.value = reportItems[reportId].Deadline;
			var studentTable = document.getElementById("marks").getElementsByTagName('TBODY')[0];
			while (studentTable.rows[0]) {
				studentTable.deleteRow(0);
			}
			for (var i = 0; i < studentMarksCount; i++) {
				var row = document.createElement("TR");
				studentTable.appendChild(row);

				// Создаем ячейки в вышесозданной строке
				// и добавляем тх
				var td1 = document.createElement("TD");
				var td2 = document.createElement("TD");
				var td3 = document.createElement("TD");

				row.appendChild(td1);
				row.appendChild(td2);
				row.appendChild(td3);

				// Наполняем ячейки
				td1.innerHTML = studentMarks[i].StudentName;
				td2.innerHTML = '<input class="form-control" type="date" name="PassDate[' + studentMarks[i].CodeOfStudent + ']" value="' + studentMarks[i].PassDate + '">';
				td3.innerHTML = '<input class="form-control" type="text" name="Marks[' + studentMarks[i].CodeOfStudent + ']" value="' + studentMarks[i].Mark + '">';
			}
		}*/

		getGroupsByDis(document.forms["fed"].elements["CodeOfSchPlanItem"].value);
		getReportsByGroup(document.forms["fed"].elements["CodeOfSchPlanItem"].value,
			document.forms["fed"].elements["CodeOfStudentGroup"].value);
		getMarksByReport(document.forms["fed"].elements["CodeOfSchPlanItem"].value,
			document.forms["fed"].elements["CodeOfStudentGroup"].value,
			document.forms["fed"].elements["CodeOfReport"].value);
		//-->
	</script>
<? include("../../footer.php"); ?>