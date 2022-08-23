<?
session_start();
$GLOBALS["SITE_DIR"] = "/Plans/rating/";
if ($_SESSION['isLoginRating'] != 1) {
	Header ("Location: ".$GLOBALS["SITE_DIR"]."login.php");
	exit;
}
include ("SQLFunc.php");
$db = DataBase::getDB();
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Рейтинг студентов</title>

    <!-- Bootstrap Core CSS -->
    <link href="<?=$GLOBALS["SITE_DIR"]?>bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="<?=$GLOBALS["SITE_DIR"]?>bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Timeline CSS -->
    <link href="<?=$GLOBALS["SITE_DIR"]?>dist/css/timeline.css" rel="stylesheet">

	<!-- DataTables CSS -->
	<link href="<?=$GLOBALS["SITE_DIR"]?>bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?=$GLOBALS["SITE_DIR"]?>dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="<?=$GLOBALS["SITE_DIR"]?>bower_components/morrisjs/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?=$GLOBALS["SITE_DIR"]?>bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/Plans/rating/">Сайт оценки активности студентов УлГТУ</a>
            </div>
            <!-- /.navbar-header -->

			<?
			include("menu.php");
			if ($_SESSION["userRole"] == "st") {
				$menu = studentMenu();
			} elseif ($_SESSION["userRole"] == "tch") {
				$menu = teacherMenu();
			}
			//var_dump($_SERVER);
			?>

            <ul class="nav navbar-top-links navbar-right">
				<?=$menu["top"]?>
            </ul>
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
						<?
						$leftMenu = $menu["left"];
						if (preg_match("/profile/", $_SERVER["REQUEST_URI"]) == 1) {
							$leftMenu = $menu["leftProfile"];
						}
						foreach ($leftMenu as $menuItem):?>
							<li>
								<a href="<?=$GLOBALS["SITE_DIR"].$menuItem["url"]?>"><i class="fa fa-fw"></i><?=$menuItem["name"]?></a>
							</li>
						<?endforeach;?>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>