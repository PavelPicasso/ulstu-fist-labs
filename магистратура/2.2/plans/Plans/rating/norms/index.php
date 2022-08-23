<?include("../header.php");?>
<?
if (!empty($_POST["saveNorms"])) {
	// сохраниение норм
	if (!empty($_POST["norm"])) {
		foreach ($_POST["norm"] as $normType => $norm) {
			$table = "";
			$codeFieldName = "";

			// определение таблицы и название поля с кодом
			switch ($normType) {
				case "Mark":
					// оценки
					$table = "reportmarks";
					$codeFieldName = "CodeOfReportMark";
					break;
				case "Report":
					// типы отчетности
					$table = "reporttypes";
					$codeFieldName = "CodeOfReportType";
					break;
				case "Degree":
					// степень участия
					$table = "eventpartdegree";
					$codeFieldName = "CodeOfEventPartDegree";
					break;
				case "Event":
					// виды мероприятий
					$table = "eventtypes";
					$codeFieldName = "CodeOfEventType";
					break;

				default:
					break;
			}
			$q = "update ".$table." set RatingNorm = {?} where ".$codeFieldName." = {?}";
			foreach ($norm as $key => $value) {
				$db->query($q, array($value * 1, $key));
			}
		}
	}
}
$disabled = "";
// редатировать нормы могут только пользователи с типом uc
if ($_SESSION["statusName"] != "uc") {
	$disabled = " id='disabledInput' disabled";
}
?>
	<div id="page-wrapper">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Нормы</h1>
			</div>
			<!-- /.col-lg-12 -->
		</div>
		<form role="form" action="" method="post">

			<div class="panel-body">
				<div class="panel panel-default">
					<div class="panel-heading">
						Нормы учебной активности
					</div>
					<div class="panel-body">
						<div class="table-responsive">
							<h4>
								Оценки
								<?if ($_SESSION["statusName"] == "uc"):?>
									<a href="add-mark.php" class="btn btn-default">Добавить оценку</a>
								<?endif;?>
							</h4>
							<table class="table table-striped table-bordered table-hover">
								<thead>
								<tr>
									<th>Оценка</th>
									<th>Норма</th>
									<th>Виды отчетности</th>
									<th>Итоговый коэффициент</th>
									<?if ($_SESSION["statusName"] == "uc"):?>
										<th class="col-lg-1"></th>
										<th class="col-lg-1"></th>
									<?endif;?>
								</tr>
								</thead>
								<tbody>
									<?
									// получить список оценок
									$q = "select CodeOfReportMark, MarkName, RatingNorm ".
										"from reportmarks ".
										"order by RatingNorm asc";
									$reportMarks = $db->select($q);
									?>
									<?foreach ($reportMarks as $mark):
										// получить связанные с оценкой типы отчетности
										$q = "select reporttypes.TypeName, reporttypes.RatingNorm ".
											"from reportmarktotype, reportmarks, reporttypes ".
											"where reportmarks.CodeOfReportMark = reportmarktotype.CodeOfReportMark ".
											"and reportmarktotype.CodeOfReportType = reporttypes.CodeOfReportType ".
											"and reportmarks.CodeOfReportMark = {?} ".
											"order by reporttypes.RatingNorm asc";
										$typeNorms = $db->select($q, array($mark["CodeOfReportMark"]));
										?>
										<tr>
											<td><?=$mark["MarkName"]?></td>
											<td><input type="text" class="form-control"<?=$disabled?> name="norm[Mark][<?=$mark["CodeOfReportMark"]?>]" value="<?=$mark["RatingNorm"]?>"></td>
											<?if (!empty($typeNorms)):
												$typesArr = array();
												$sumArr = array();
												// если норма не установлена, то записываем 1, чтобы при умножении произведение не изменилось
												$markNorm = ($mark["RatingNorm"] > 0) ? $mark["RatingNorm"] : 1;
												foreach ($typeNorms as $type) {
													$typesArr[] = $type["TypeName"];
													// если норма не установлена, то записываем 1, чтобы при умножении произведение не изменилось
													$typeNorm = ($type["RatingNorm"] > 0) ? $type["RatingNorm"] : 1;
													if ($mark["RatingNorm"] > 0 || $type["RatingNorm"] > 0) {
														$sumArr[] = $markNorm * $typeNorm;
													} else {
														// если обе нормы не установлены, то эта строка должна быть пуста
														$sumArr[] = "";
													}
												}
												?>
												<td><?=implode("<br>", $typesArr)?></td>
												<td><?=implode("<br>", $sumArr)?></td>
											<?else:?>
												<td></td>
												<td></td>
											<?endif;?>
											<?if ($_SESSION["statusName"] == "uc"):?>
												<td><a href="edit-mark.php?id=<?=$mark["CodeOfReportMark"]?>" class="btn btn-default">Редактировать</a></td>
												<td><a href="del_norm.php?id=<?=$mark["CodeOfReportMark"]?>&type=Mark&code=<?=md5($mark["CodeOfReportMark"].'-CodeOfReportMark-reportmarks')?>" onclick="return confirmDelete();" class="btn btn-default">Удалить</a></td>
											<?endif;?>
										</tr>
									<?endforeach;?>
								</tbody>
							</table>
						</div>
						<!-- /.table-responsive -->
						<div class="table-responsive">
							<h4>
								Виды отчетности
								<?if ($_SESSION["statusName"] == "uc"):?>
									<a href="add-report-type.php" class="btn btn-default">Добавить вид отчетности</a>
								<?endif;?>
							</h4>
							<table class="table table-striped table-bordered table-hover">
								<thead>
								<tr>
									<th>Вид отчетности</th>
									<th>Норма</th>
									<th>Оценка</th>
									<th>Итоговый коэффициент</th>
									<?if ($_SESSION["statusName"] == "uc"):?>
										<th class="col-lg-1"></th>
										<th class="col-lg-1"></th>
									<?endif;?>
								</tr>
								</thead>
								<tbody>
									<?
									// получение видов отчетности
									$q = "select CodeOfReportType, TypeName, RatingNorm ".
										"from reporttypes ".
										"order by RatingNorm asc";
									$reportTypes = $db->select($q);
									?>
									<?foreach ($reportTypes as $type):
										// получение списка оценок, связанных с видом отчетности
										$q = "select reportmarks.MarkName, reportmarks.RatingNorm ".
											"from reportmarktotype, reportmarks, reporttypes ".
											"where reportmarks.CodeOfReportMark = reportmarktotype.CodeOfReportMark ".
											"and reportmarktotype.CodeOfReportType = reporttypes.CodeOfReportType ".
											"and reporttypes.CodeOfReportType = {?} ".
											"order by reportmarks.RatingNorm asc";
										$markNorms = $db->select($q, array($type["CodeOfReportType"]));
										?>
										<tr>
											<td><?=$type["TypeName"]?></td>
											<td><input type="text" class="form-control"<?=$disabled?> name="norm[Report][<?=$type["CodeOfReportType"]?>]" value="<?=$type["RatingNorm"]?>"></td>
											<?if (!empty($markNorms)):
												$marksArr = array();
												$sumArr = array();
												// если норма не установлена, то записываем 1, чтобы при умножении произведение не изменилось
												$typeNorm = ($type["RatingNorm"] > 0) ? $type["RatingNorm"] : 1;
												foreach ($markNorms as $mark) {
													$marksArr[] = $mark["MarkName"];
													// если норма не установлена, то записываем 1, чтобы при умножении произведение не изменилось
													$markNorm = ($mark["RatingNorm"] > 0) ? $mark["RatingNorm"] : 1;
													if ($mark["RatingNorm"] > 0 || $type["RatingNorm"] > 0) {
														$sumArr[] = $markNorm * $typeNorm;
													} else {
														// если обе нормы не установлены, то эта строка должна быть пуста
														$sumArr[] = "";
													}
												}
												?>
												<td><?=implode("<br>", $marksArr)?></td>
												<td><?=implode("<br>", $sumArr)?></td>
											<?else:?>
												<td></td>
												<td></td>
											<?endif;?>
											<?if ($_SESSION["statusName"] == "uc"):?>
												<td><a href="edit-report-type.php?id=<?=$type["CodeOfReportType"]?>" class="btn btn-default">Редактировать</a></td>
												<td><a href="del_norm.php?id=<?=$type["CodeOfReportType"]?>&type=Report&code=<?=md5($type["CodeOfReportType"].'-CodeOfReportType-reporttypes')?>" onclick="return confirmDelete();" class="btn btn-default">Удалить</a></td>
											<?endif;?>
										</tr>
									<?endforeach;?>
								</tbody>
							</table>
						</div>
						<!-- /.table-responsive -->
					</div>
					<!-- /.panel-body -->
				</div>
			</div>
			<div class="panel-body">
				<div class="panel panel-default">
					<div class="panel-heading">
						Нормы внеучебной активности
					</div>
					<div class="panel-body">
						<div class="table-responsive">
							<h4>
								Степень участия
								<?if ($_SESSION["statusName"] == "uc"):?>
									<a href="add-part-degree.php" class="btn btn-default">Добавить степень участия</a>
								<?endif;?>
							</h4>
							<table class="table table-striped table-bordered table-hover">
								<thead>
								<tr>
									<th>Степень участия</th>
									<th>Норма</th>
									<?if ($_SESSION["statusName"] == "uc"):?>
										<th class="col-lg-1"></th>
										<th class="col-lg-1"></th>
									<?endif;?>
								</tr>
								</thead>
								<tbody>
									<?
									// получить степени участия
									$q = "select CodeOfEventPartDegree, DegreeName, RatingNorm ".
										"from eventpartdegree ".
										"order by RatingNorm asc, DegreeName asc";
									$eventPartDegrees = $db->select($q);
									?>
									<?foreach ($eventPartDegrees as $degree):?>
										<tr>
											<td><?=$degree["DegreeName"]?></td>
											<td><input type="text" class="form-control"<?=$disabled?> name="norm[Degree][<?=$degree["CodeOfEventPartDegree"]?>]" value="<?=$degree["RatingNorm"]?>"></td>
											<?if ($_SESSION["statusName"] == "uc"):?>
												<td><a href="edit-part-degree.php?id=<?=$degree["CodeOfEventPartDegree"]?>" class="btn btn-default">Редактировать</a></td>
												<td><a href="del_norm.php?id=<?=$degree["CodeOfEventPartDegree"]?>&type=Degree&code=<?=md5($degree["CodeOfEventPartDegree"].'-CodeOfEventPartDegree-eventpartdegree')?>" onclick="return confirmDelete();" class="btn btn-default">Удалить</a></td>
											<?endif;?>
										</tr>
									<?endforeach;?>
								</tbody>
							</table>
						</div>
						<!-- /.table-responsive -->
						<div class="table-responsive">
							<h4>
								Виды мероприятия
								<?if ($_SESSION["statusName"] == "uc"):?>
									<a href="add-event-type.php" class="btn btn-default">Добавить вид мероприятия</a>
								<?endif;?>
							</h4>
							<table class="table table-striped table-bordered table-hover">
								<thead>
								<tr>
									<th>Тип мероприятия</th>
									<th>Вид мероприятия</th>
									<th>Норма</th>
									<?if ($_SESSION["statusName"] == "uc"):?>
										<th class="col-lg-1"></th>
										<th class="col-lg-1"></th>
									<?endif;?>
								</tr>
								</thead>
								<tbody>
									<?
									// получить виды мероприятий и их типы
									$q = "select eventtypes.CodeOfEventType, eventtypes.TypeName, eventtypes.CodeOfEventSort, ".
										"eventtypes.RatingNorm, eventsorts.SortName ".
										"from eventtypes, eventsorts ".
										"where eventtypes.CodeOfEventSort = eventsorts.CodeOfEventSort ".
										"order by eventsorts.CodeOfEventSort asc, eventtypes.TypeName asc";
									$eventTypes = $db->select($q);
									?>
									<?foreach ($eventTypes as $type):?>
										<tr>
											<td><?=$type["SortName"]?></td>
											<td><?=$type["TypeName"]?></td>
											<td><input type="text" class="form-control"<?=$disabled?> name="norm[Event][<?=$type["CodeOfEventType"]?>]" value="<?=$type["RatingNorm"]?>"></td>
											<?if ($_SESSION["statusName"] == "uc"):?>
												<td><a href="edit-event-type.php?id=<?=$type["CodeOfEventType"]?>" class="btn btn-default">Редактировать</a></td>
												<td><a href="del_norm.php?id=<?=$type["CodeOfEventType"]?>&type=Event&code=<?=md5($type["CodeOfEventType"].'-CodeOfEventType-eventtypes')?>" onclick="return confirmDelete();" class="btn btn-default">Удалить</a></td>
											<?endif;?>
										</tr>
									<?endforeach;?>
								</tbody>
							</table>
						</div>
						<!-- /.table-responsive -->
					</div>
					<!-- /.panel-body -->
				</div>
			</div>
			<div class="panel-body">
				<button type="submit" name="saveNorms" class="btn btn-default" value="save">Сохранить нормы</button>
			</div>
		</form>

		<div class="panel-body">
			<form role="form" action="norm-control.php" method="post">
				<div class="input-group">
					<span class="input-group-addon">Трудоемкость работ, дающих приращение рейтинга:</span>
					<input class="form-control" name="pointsCount" value="100">
					<span class="input-group-btn"><button type="submit" name="getNorms" class="btn btn-default">Посмотреть</button></span>
				</div>
			</form>
		</div>
	</div>
<script>
	function confirmDelete() {
		if (confirm("Вы подтверждаете удаление?")) {
			return true;
		} else {
			return false;
		}
	}
</script>
<?include("../footer.php");?>