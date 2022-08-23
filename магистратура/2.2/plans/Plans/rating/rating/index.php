<?include("../header.php");

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
?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Рейтинги</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->

	<div class="panel-body">
		<a href="new-rating.php" type="submit" class="btn btn-default">Новый рейтинг</a>
	</div>

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
								<td><a href="./detail.php?id=<?=$rating["CodeOfRating"]?>">Подробнее</a></td>
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
								<td><a href="./detail.php?id=<?=$rating["CodeOfRating"]?>">Подробнее</a></td>
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
<?include("../footer.php");?>
