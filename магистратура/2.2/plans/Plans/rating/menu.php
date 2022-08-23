<?
function teacherMenu() {
	$menu["top"] = '<li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#">
							<i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
						</a>
						<ul class="dropdown-menu dropdown-user">
							<li><a href="'.$GLOBALS["SITE_DIR"].'profile/teacher/index.php"><i class="fa fa-user fa-fw"></i>Личный кабинет</a>
							</li>
							<li class="divider"></li>
							<li><a href="'.$GLOBALS["SITE_DIR"].'login.php?logout=1"><i class="fa fa-sign-out fa-fw"></i>Выйти</a>
							</li>
						</ul>
						<!-- /.dropdown-user -->
					</li>
					<!-- /.dropdown -->';
	$menu["left"] = array(
		array(
			"name" => "Главная",
			"url" => "index.php"
		),
		array(
			"name" => "Рейтинги",
			"url" => "rating/index.php"
		),
		array(
			"name" => "Нормы",
			"url" => "norms/index.php"
		),
		array(
			"name" => "Внеучебная активность",
			"url" => "other-activity/index.php"
		)
	);
	$menu["leftProfile"] = array(
		array(
			"name" => "Персональные данные",
			"url" => "profile/teacher/index.php"
		),
		array(
			"name" => "Учебный процесс",
			"url" => "profile/teacher/marks.php"
		),
	);
	return($menu);
}

function studentMenu() {
	$menu["top"] = '<li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#">
							<i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
						</a>
						<ul class="dropdown-menu dropdown-user">
							<li><a href="'.$GLOBALS["SITE_DIR"].'profile/student/index.php"><i class="fa fa-user fa-fw"></i>Личный кабинет</a>
							</li>
							<li class="divider"></li>
							<li><a href="'.$GLOBALS["SITE_DIR"].'login.php?logout=1"><i class="fa fa-sign-out fa-fw"></i>Выйти</a>
							</li>
						</ul>
						<!-- /.dropdown-user -->
					</li>
					<!-- /.dropdown -->';
	$menu["left"] = array(
						array(
							"name" => "Главная",
							"url" => "index.php"
						),
						array(
							"name" => "Нормы",
							"url" => "norms/index.php"
						)
					);
	$menu["leftProfile"] = array(
								array(
									"name" => "Персональные данные",
									"url" => "profile/student/index.php"
								),
								array(
									"name" => "Оценки и баллы",
									"url" => "profile/student/marks.php"
								),
								array(
									"name" => "Рейтинг",
									"url" => "profile/student/rating.php"
								),
							);
	return($menu);
}
?>