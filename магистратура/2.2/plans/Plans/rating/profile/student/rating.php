<?
session_start();
if ($_SESSION["userRole"] != "st") {
	Header("Location: ../../index.php");
	exit;
} else {
	$CodeOfStudent = $_SESSION["userRoleId"];
}
?>

<?include("../../header.php");?>
	<div id="page-wrapper">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Рейтинг</h1>
			</div>
			<!-- /.col-lg-12 -->
		</div>
		<div class="panel-body">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover">
							<thead>
							<tr>
								<th>Название рейтинга</th>
								<th>Место</th>
								<th>Ссылка</th>
							</tr>
							</thead>
							<tbody>
							<tr>
								<td>Лучшая успеваемость группы ИВТПОд-11</td>
								<td>1</td>
								<td><a href="#">Подробнее</a></td>
							</tr>
							<tr>
								<td>Лучшая успеваемость кафедры ВТ</td>
								<td>5</td>
								<td><a href="#">Подробнее</a></td>
							</tr>
							<tr>
								<td>Лучшая успеваемость ФИСТ</td>
								<td>17</td>
								<td><a href="#">Подробнее</a></td>
							</tr>
							</tbody>
						</table>
					</div>
					<!-- /.table-responsive -->
				</div>
				<!-- /.panel-body -->
			</div>
		</div>
	</div>
<?include("../../footer.php");?>