<?include("header.php");

// получение списка рейтингов
$q = "select CodeOfRating, RatingName, DateFrom, DateTo, PublishDate from ratings";
$ratingsDB = $db->select($q);

$dinamicRating = array();
$staticRatings = array();

$tempDate = date("Y-m-d");
foreach ($ratingsDB as $rating) {
	if ($rating["DateTo"] < $tempDate) {
		$staticRatings[] = $rating;
	} else {
		$dinamicRating[] = $rating;
	}
}

$dinamicCount = (!empty($dinamicRating)) ? count($dinamicRating) : 0;
$staticCount = (!empty($staticRatings)) ? count($staticRatings) : 0;
?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Рейтинги</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
	<?
	//$db = DataBase::getDB();
	//$query = "SELECT * FROM teachers";
	//$table = $db->select($query);
	//foreach ($table as $row) {
		//var_dump($row);
	//}
	//$query = "INSERT INTO students (StudentName) VALUES ({?})";
	//$res = $db->query($query, array("Пушкарева"));
	//var_dump($res);
	?>
    <div class="row">
        <div class="col-lg-4 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-tasks fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?=($dinamicCount + $staticCount)?></div>
                            <div>Общее количество рейтингов</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="panel panel-green">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-tasks fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?=$dinamicCount?></div>
                            <div>Количество динамичных рейтинга</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="panel panel-yellow">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-tasks fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?=$staticCount?></div>
                            <div>Количество статичных рейтингов</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->

	<div class="panel panel-default">
		<div class="panel-heading">
			Динамичные рейтинги
		</div>
		<!-- /.panel-heading -->
		<div class="panel-body">
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover">
					<thead>
					<tr>
						<th>Название</th>
						<th>Начало фиксирования</th>
						<th>Конец фиксирования</th>
						<th>Ссылка</th>
					</tr>
					</thead>
					<tbody>
						<?foreach ($dinamicRating as $rating):?>
							<tr>
								<td><?=$rating["RatingName"]?></td>
								<td><?=date("d.m.Y", strtotime($rating["DateFrom"]))?></td>
								<td><?=date("d.m.Y", strtotime($rating["DateTo"]))?></td>
								<td><a href="./rating/detail.php?id=<?=$rating["CodeOfRating"]?>">Подробнее</a></td>
							</tr>
						<?endforeach;?>
					</tbody>
				</table>
			</div>
			<!-- /.table-responsive -->
		</div>
		<!-- /.panel-body -->
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			Статичные рейтинги
		</div>
		<!-- /.panel-heading -->
		<div class="panel-body">
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover">
					<thead>
					<tr>
						<th>Название</th>
						<th>Начало фиксирования</th>
						<th>Конец фиксирования</th>
						<th>Ссылка</th>
					</tr>
					</thead>
					<tbody>
						<?foreach ($staticRatings as $rating):?>
							<tr>
								<td><?=$rating["RatingName"]?></td>
								<td><?=date("d.m.Y", strtotime($rating["DateFrom"]))?></td>
								<td><?=date("d.m.Y", strtotime($rating["DateTo"]))?></td>
								<td><a href="./rating/detail.php?id=<?=$rating["CodeOfRating"]?>">Подробнее</a></td>
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
<!-- /#page-wrapper -->
<?include("footer.php");?>
