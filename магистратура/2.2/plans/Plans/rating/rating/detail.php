<?include("../header.php");

$codeOfRating = $_GET["id"];
// получить информацию о рейтинге
$q = "select r.RatingName, r.DateFrom, r.DateTo, r.PublishDate, r.CodeOfFaculty, r.CodeOfDepart, r.Kurs, ".
	"g.CodeOfStudentGroup, d.CodeOfDiscipline, e.CodeOfEventSort, e.CodeOfEventType, e.CodeOfEvent, ".
	"f.FacName, dep.DepName, dis.DisName, es.SortName, et.TypeName, ee.EventName, sg.GroupName ".
	"from ratings r left join ratinggroups g on g.CodeOfRating = r.CodeOfRating ".
	"left join ratingdiscips d on d.CodeOfRating = r.CodeOfRating ".
	"left join ratingevents e on e.CodeOfRating = r.CodeOfRating ".
	"left join faculty f on f.CodeOfFaculty = r.CodeOfFaculty ".
	"left join department dep on dep.CodeOfDepart = r.CodeOfDepart ".
	"left join disciplins dis on dis.CodeOfDiscipline = d.CodeOfDiscipline ".
	"left join eventsorts es on es.CodeOfEventSort = e.CodeOfEventSort ".
	"left join eventtypes et on et.CodeOfEventType = e.CodeOfEventType ".
	"left join events ee on ee.CodeOfEvent = e.CodeOfEvent ".
	"left join studentgroups sg on sg.CodeOfStudentGroup = g.CodeOfStudentGroup ".
	"where r.CodeOfRating = {?} ";
$ratingInfo = $db->select($q, array($codeOfRating));
if (empty($ratingInfo)) {
	$errorMsg = 'Рейтинг не найден. <a href="./index.php" class="alert-link">Вернуться к списку рейтингов.</a>';
}

$ratDiscips = array();
$ratGroups = array();

$haveDis = false;
$haveGroups = false;

foreach ($ratingInfo as $rating) {
	if (!empty($rating["CodeOfDiscipline"])) {
		$haveDis = true;
	}
	$ratDiscips[$rating["CodeOfDiscipline"]] = $rating["DisName"];
	if (!empty($rating["CodeOfStudentGroup"])) {
		$haveGroups = true;
	}
	$ratGroups[$rating["CodeOfStudentGroup"]] = $rating["GroupName"];
}
$ratingInfo = array_shift($ratingInfo);
?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?=$ratingInfo["RatingName"]?></h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
	<?if (!empty($errorMsg)):?>
		<div class="alert alert-danger"><?=$errorMsg?></div>
	<?endif;?>
	<div class="panel-body">
		<dl class="dl-horizontal">
			<h4><dt style="text-align: left; width:25%">Тип рейтинга: </dt><dd><?=($ratingInfo["DateTo"] < date("Y-m-d")) ? "статичный" : "динамичный"?></dd></h4>
			<h4><dt style="text-align: left; width:25%">Начало фиксирования: </dt><dd><?=date("d.m.Y", strtotime($ratingInfo["DateFrom"]))?></dd></h4>
			<h4><dt style="text-align: left; width:25%">Конец фиксирования: </dt><dd><?=date("d.m.Y", strtotime($ratingInfo["DateTo"]))?></dd></h4>
			<?if (!empty($ratingInfo["FacName"])):?><h4><dt style="text-align: left; width:25%">Факультет: </dt><dd><?=$ratingInfo["FacName"]?></dd></h4><?endif;?>
			<?if (!empty($ratingInfo["DepName"])):?><h4><dt style="text-align: left; width:25%">Кафедра: </dt><dd><?=$ratingInfo["DepName"]?></dd></h4><?endif;?>
			<?if (!empty($ratingInfo["Kurs"])):?><h4><dt style="text-align: left; width:25%">Курс: </dt><dd><?=$ratingInfo["Kurs"]?></dd></h4><?endif;?>
			<?if ($haveGroups):?><h4><dt style="text-align: left; width:25%">Группы: </dt><dd><?=implode('</dd></dt></h4><h4><dt style="text-align: left; width:25%">&nbsp;</dt><dd>', $ratGroups)?></dd></h4><?endif;?>
			<?if (!is_null($ratingInfo["CodeOfDiscipline"])):?><h4><dt style="text-align: left; width:25%">Тип активности: </dt><dd>учебная</dd></h4><?endif;?>
			<?if (!is_null($ratingInfo["CodeOfEventSort"])):?><h4><dt style="text-align: left; width:25%">Тип активности: </dt><dd>внеучебная</dd></h4><?endif;?>
			<?if ($haveDis):?><h4><dt style="text-align: left; width:25%">Дисциплины: </dt><dd><?=implode('</dd></dt></h4><h4><dt style="text-align: left; width:25%">&nbsp;</dt><dd>', $ratDiscips)?></dd></h4><?endif;?>
			<?if (!empty($ratingInfo["SortName"])):?><h4><dt style="text-align: left; width:25%">Тип мероприятия: </dt><dd><?=$ratingInfo["SortName"]?></dd></h4><?endif;?>
			<?if (!empty($ratingInfo["TypeName"])):?><h4><dt style="text-align: left; width:25%">Вид мероприятия: </dt><dd><?=$ratingInfo["TypeName"]?></dd></h4><?endif;?>
			<?if (!empty($ratingInfo["EventName"])):?><h4><dt style="text-align: left; width:25%">Мероприятие: </dt><dd><?=$ratingInfo["EventName"]?></dd></h4><?endif;?>
		</dl>
	</div>

	<div class="panel-body">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
				<thead>
				<tr>
					<th>Место</th>
					<th>ФИО студента</th>
					<th>Группа</th>
					<th>Балл</th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td>1</td>
					<td>Петров</td>
					<td>ИВТАСбд-21</td>
					<td>100</td>
				</tr>
				<tr>
					<td>2</td>
					<td>Иванов</td>
					<td>ИТСбд-32</td>
					<td>78</td>
				</tr>
				<tr>
					<td>3</td>
					<td>Авдеев</td>
					<td>ИВТПОмд-11</td>
					<td>70</td>
				</tr>
				</tbody>
			</table>
		</div>
		<!-- /.table-responsive -->
	</div>
	<!-- /.panel-body -->
</div>
<!-- /#page-wrapper -->
<?include("../footer.php");?>
