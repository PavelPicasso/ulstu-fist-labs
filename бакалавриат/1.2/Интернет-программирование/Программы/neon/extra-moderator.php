<?php
session_start();
$username = $_SESSION['session_username'];
require_once("connection.php");
$query2 = "SELECT * FROM users WHERE username='".$username."'";
$result2 = mysqli_query($con, $query2) or die ("Error : " . mysqli_error());
$row2 = mysqli_fetch_assoc($result2);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="description" content="Neon Admin Panel"/>
    <meta name="author" content=""/>

    <link rel="icon" href="assets/images/favicon.ico">

    <title>Admin | Dashboard</title>

    <link rel="stylesheet" href="assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css">
    <link rel="stylesheet" href="assets/css/font-icons/entypo/css/entypo.css">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic">
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/neon-core.css">
    <link rel="stylesheet" href="assets/css/neon-theme.css">
    <link rel="stylesheet" href="assets/css/neon-forms.css">
    <link rel="stylesheet" href="assets/css/custom.css">
    <script src="assets/js/jquery-1.11.3.min.js"></script>

    <!--[if lt IE 9]>
    <script src="assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


</head>
<body>

<div class="page-container">
    <!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->
    <div class="sidebar-menu">
        <div class="sidebar-menu-inner">
            <header class="logo-env">
                <!-- logo -->
                <div class="logo">
                    <a href="">
                        <img src="assets/images/logo@2x.png" width="120" alt=""/>
                    </a>
                </div>
                <!-- logo collapse icon -->
                <div class="sidebar-collapse">
                    <a href="#" class="sidebar-collapse-icon">
                        <!-- add class "with-animation" if you want sidebar to have animation during expanding/collapsing transition -->
                        <i class="entypo-menu"></i>
                    </a>
                </div>
                <!-- open/close menu icon (do not remove if you want to enable menu on mobile devices) -->
                <div class="sidebar-mobile-menu visible-xs">
                    <a href="#" class="with-animation"><!-- add class "with-animation" to support animation -->
                        <i class="entypo-menu"></i>
                    </a>
                </div>
            </header>

            <ul id="main-menu" class="main-menu">
                <!-- add class "multiple-expanded" to allow multiple submenus to open -->
                <!-- class "auto-inherit-active-class" will automatically add "active" class for parent elements who are marked already with class "active" -->
                <li class="active opened active has-sub">
                    <a href="">
                        <i class="entypo-gauge"></i>
                        <span class="title">Dashboard</span>
                    </a>
                    <ul class="visible">
                        <li class="active">
                            <a href="extra-moderator.php">
                                <span class="title">Profile</span>
                            </a>
                        </li>
                        <li class="has-sub">
                            <a href="skin-black.html">
                                <span class="title">Skins</span>
                            </a>
                            <ul>
                                <li>
                                    <a href="skin-black.html">
                                        <span class="title">Black Skin</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="skin-white.html">
                                        <span class="title">White Skin</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="skin-purple.html">
                                        <span class="title">Purple Skin</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="skin-cafe.html">
                                        <span class="title">Cafe Skin</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="skin-red.html">
                                        <span class="title">Red Skin</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="skin-green.html">
                                        <span class="title">Green Skin</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="skin-yellow.html">
                                        <span class="title">Yellow Skin</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="skin-blue.html">
                                        <span class="title">Blue Skin</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="skin-facebook.html">
                                        <span class="title">Facebook Skin</span>
                                        <span class="badge badge-secondary badge-roundless">New</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="tables-datatable-moder.php">
                        <i class="entypo-monitor"></i>
                        <span class="title">Users</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="main-content">
        <div class="row">
            <!-- Profile Info and Notifications -->
            <div class="col-md-6 col-sm-8 clearfix">
                <ul class="user-info pull-left pull-none-xsm">
                    <!-- Profile Info -->
                    <li class="profile-info dropdown">
                        <!-- add class "pull-right" if you want to place this from right -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="assets/images/moderator.jpg" alt="" class="img-circle" width="44"/>

                            <?php
                            if ($row2["role"] == "1") {
                                echo $row2["username"];
                            }
                            ?>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- Raw Links -->
            <div class="col-md-6 col-sm-4 clearfix hidden-xs">
                <ul class="list-inline links-list pull-right">
                    <li class="sep"></li>
                    <li>
                        <a href="extra-login.html">
                            Log Out <i class="entypo-logout right"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <hr>
        <div class="profile-env">
            <header class="row">
                <div class="col-sm-2">
                    <a href="#" class="profile-picture">
                        <img src="assets/images/moderator.jpg" width="128" height="128" class="img-responsive img-circle"/>
                    </a>
                </div>
                <div class="col-sm-7">
                    <ul class="profile-info-sections">
                        <li>
                            <div class="profile-name">
                                <strong>
                                    <a href="#">
                                        <?php
                                        if ($row2["role"] == "1") {
                                            echo $row2["username"];
                                        }
                                        ?>
                                    </a>
                                    <a href="#" class="user-status is-online tooltip-primary" data-toggle="tooltip"
                                       data-placement="top" data-original-title="Online"></a>
                                    <!-- User statuses available classes "is-online", "is-offline", "is-idle", "is-busy" -->
                                </strong>
                                <span><a href="#">
                                         <?php
                                         if ($row2["role"] == "2") {
                                             echo "Admin";
                                         }
                                         if ($row2["role"] == "1") {
                                             echo "Moderator";
                                         }
                                         ?>
                                    </a></span>
                            </div>
                        </li>
                        <li>
                            <div class="profile-stat">
                                <h3>643</h3>
                                <span><a href="#">followers</a></span>
                            </div>
                        </li>
                        <li>
                            <div class="profile-stat">
                                <h3>108</h3>
                                <span><a href="#">following</a></span>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col-sm-3">
                    <div class="profile-buttons">
                        <a href="#" class="btn btn-default">
                            <i class="entypo-user-add"></i>
                            Follow
                        </a>
                        <a href="#" class="btn btn-default">
                            <i class="entypo-mail"></i>
                        </a>
                    </div>
                </div>
            </header>
            <section class="profile-info-tabs">
                <div class="row">
                    <div class="col-sm-offset-2 col-sm-10">
                        <ul class="user-details">
                            <li>
                                <a href="#">
                                    <i class="entypo-location"></i>
                                    Ulyanovsk
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="entypo-suitcase"></i>
                                    Student <span>UlSTU</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="entypo-calendar"></i>
                                    7 May
                                </a>
                            </li>
                        </ul>
                        <!-- tabs for the profile links -->
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#profile-info">Profile</a></li>
                            <li><a href="#biography">Bio</a></li>
                        </ul>
                    </div>
                </div>
            </section>
        </div>

        <!-- Bottom scripts (common) -->
        <script src="assets/js/gsap/TweenMax.min.js"></script>
        <script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
        <script src="assets/js/bootstrap.js"></script>
        <script src="assets/js/joinable.js"></script>
        <script src="assets/js/resizeable.js"></script>
        <script src="assets/js/neon-api.js"></script>
        <script src="assets/js/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>

        <!-- Imported scripts on this page -->
        <script src="assets/js/jvectormap/jquery-jvectormap-europe-merc-en.js"></script>
        <script src="assets/js/jquery.sparkline.min.js"></script>
        <script src="assets/js/rickshaw/vendor/d3.v3.js"></script>
        <script src="assets/js/rickshaw/rickshaw.min.js"></script>
        <script src="assets/js/raphael-min.js"></script>
        <script src="assets/js/morris.min.js"></script>
        <script src="assets/js/toastr.js"></script>

        <!-- JavaScripts initializations and stuff -->
        <script src="assets/js/neon-custom.js"></script>

</body>
</html>