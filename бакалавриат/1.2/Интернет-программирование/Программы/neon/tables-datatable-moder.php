<?php
session_start();
require_once("connection.php");
$username = $_SESSION['session_username'];
$query2 = "SELECT * FROM users";
$result2 = mysqli_query($con, $query2) or die ("Error : " . mysqli_error());
$result4 = mysqli_query($con, "SELECT * FROM users WHERE username='".$username."'") or die ("Error : " . mysqli_error());
$row4 = mysqli_fetch_array($result4);
$result3 = mysqli_query($con, $query2) or die ("Error : " . mysqli_error());
$row3 = mysqli_fetch_assoc($result3);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="icon" href="assets/images/favicon.ico">
    <title>Neon | Data Tables</title>

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
                <li class="has-sub">
                    <a href="">
                        <i class="entypo-gauge"></i>
                        <span class="title">Dashboard</span>
                    </a>
                    <ul>
                        <li>
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
                                if ($row4["role"] == "1") {
                                    echo $row4["username"];
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

        <hr/>

        <ol class="breadcrumb bc-3">
            <li>
                <a href=""><i class="active"></i>Users</a>
            </li>
            <li class="active">
                <strong>Edit users</strong>
            </li>
        </ol>

        <br/>


        <h3>Table without DataTable Header</h3>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                var $table = jQuery("#table-2");

                // Initialize DataTable
                $table.DataTable({
                    "aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                    "bStateSave": true
                });

                // Initalize Select Dropdown after DataTables is created
                $table.closest('.dataTables_wrapper').find('select').select2({
                    minimumResultsForSearch: -1
                });
            });
        </script>

        <script type="text/javascript">
            jQuery(window).load(function () {
                var $table2 = jQuery("#table-2");

                // Highlighted rows
                $table2.find("tbody input[type=checkbox]").each(function (i, el) {
                    var $this = $(el),
                        $p = $this.closest('tr');

                    $(el).on('change', function () {
                        var is_checked = $this.is(':checked');

                        $p[is_checked ? 'addClass' : 'removeClass']('highlight');
                    });
                });

                // Replace Checboxes
                $table2.find(".pagination a").click(function (ev) {
                    replaceCheckboxes();
                });
            });

            // Sample Function to add new row
            var giCount = 1;

            function fnClickAddRow() {
                jQuery('#table-2').dataTable().fnAddData(['<div class="checkbox checkbox-replace"><input type="checkbox" /></div>', giCount + ".1", giCount + ".2", giCount + ".3", giCount + ".4"]);
                replaceCheckboxes(); // because there is checkbox, replace it
                giCount++;
            }
        </script>

        <table class="table table-bordered table-striped datatable" id="table-2">
            <thead>
            <tr>
                <th>
                    <div class="checkbox checkbox-replace">
                        <input type="checkbox" id="chk-1">
                    </div>
                </th>
                <th>Login</th>
                <th>Password</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
            </thead>

            <tbody>

            <?php
            while ($row2 = mysqli_fetch_array($result2)) {
            if ($row2["role"] !== "2") {
                ?>

                <tr>
                    <td>
                        <div class="checkbox checkbox-replace">
                            <input type="checkbox" id="chk-1">
                        </div>
                    </td>

                    <td><?= $row2["username"] ?></td>
                    <td><?= $row2["password"] ?></td>
                    <td><?php
                        if ($row2["role"] == "0")
                            echo "User";
                        if ($row2["role"] == "1")
                            echo "Moderator";
                        ?>

                    </td>
                    <td>
                        <?php
                        if ($row2["role"] !== "1") {
                            echo "<a href=\"#modal-1" . $row2["id"] . "\" class=\"btn btn-default btn-sm btn-icon icon-left\"><i class=\"entypo-pencil\"></i>Edit </a>";
                        }
                        echo "
                        <div id=\"modal-1" . $row2["id"] . "\" class=\"modalDialog\" >
                            <div class=\"modal-dialog\">
                                <div class=\"modal-content\">
                                      <div class=\"modal-header\">
                                            <a href=\"#close\" title=\"Закрыть\" class=\"close\">&times;</a>
                                            <h4 class=\"modal-title\">Edit form</h4>
                                      </div>
                                     <div class=\"modal-body\">  
                                        <form action=\"edit.php\" method=\"post\">
                                            <input type=\"hidden\" value=\"" . $row2["id"] . "\" name=\"id\">
                                            <label>Имя</label> <br>
                                            <input type=\"text\" value=\"" . $row2["username"] . "\" name=\"username\" class=\"form-control input-lg\"><br>
                                            <label>Пароль</label><br>
                                            <input type=\"password\" value=\"" . $row2["password"] . "\" name=\"password\" class=\"form-control input-lg\"><br>
                                            
                                            <select name=\"option\" class=\"form-control\" style='z-index: 9999'>
                                                <option value=\"0\" ";
                        if ($row2["role"] == "0") {
                            echo "selected";
                        }
                        echo ">User</option>
                                                                        <option value=\"1\" ";
                        if ($row2["role"] == "1") {
                            echo "selected";
                        }
                        echo ">Moderator</option>
                                            </select>
                                            <br>
                                            
                                           <div class=\"modal-footer\">
                                                 <a href=\"#close\" title=\"Закрыть\" type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</a>
                                                <input type=\"submit\" class=\"btn btn-info\" name=\"button\">
				                           </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>";
                        if ($row2["role"] !== "1") {
                            echo "<a href=\"#openModal" . $row2["id"] . "\" class=\"btn btn-danger btn-sm btn-icon icon-left\"><i class=\"entypo-pencil\"></i>Delete</a>";
                        }
                        echo "
                        <div id=\"openModal" . $row2["id"] . "\" class=\"modalDialog\" >
                            <div class=\"modal-dialog\">
                                <div class=\"modal-content\">
                                        <div class=\"modal-header\">
                                            <a href=\"#close\" title=\"Закрыть\" class=\"close\">&times;</a>
                                            <h4 class=\"modal-title\">Delete form</h4>
                                        </div>
                                        <div class=\"modal-body\">
                                            <h3>Вы точно хотите удалить этого пользователя?</h3>
                                            <div class=\"modal-footer\">
                                                 <a href=\"delete.php?id=" . $row2["id"] . "\" title=\"Закрыть\" type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Yes</button>
                                                <a href=\"#close\" title=\"Закрыть\" type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">No</a>
                                            </div>
                                        </div >
                                </div>
                            </div>
                        </div >";
                        ?>

                        <a href="#" class="btn btn-info btn-sm btn-icon icon-left">
                            <i class="entypo-info"></i>
                            Profile
                        </a>
                    </td>
                </tr>
                <?php
            }
            }
            ?>
            </tbody>
        </table>

        <br/>


        <a href="#modal-add" class="btn btn-primary">
            <i class="entypo-plus"></i>
            Add Row
        </a>

        <div id="modal-add" class="modalDialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <a href="#close" title="Закрыть" class="close">&times;</a>
                        <h4 class="modal-title">New user</h4>
                    </div>
                    <div class="modal-body">
                        <form action="create.php" method="post">
                            <input type="hidden" name="id">
                            <label>Имя</label> <br>
                            <input type="text" name="username" class="form-control input-lg"><br>
                            <label>Пароль</label><br>
                            <input type="password" name="password" class="form-control input-lg"><br>
                            <br>
                            <select name="option" class="form-control" style='z-index: 9999'>
                                <option value="0"> User</option>
                                <option value="1">Moderator</option>
                            </select>
                            <br>
                            <div class="modal-footer">
                                <a href="#close" title="Закрыть" type="button" class="btn btn-default"
                                   data-dismiss="modal">Close</a>
                                <input type="submit" class="btn btn-info" name="button">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Imported styles on this page -->
<link rel="stylesheet" href="assets/js/datatables/datatables.css">
<link rel="stylesheet" href="assets/js/select2/select2-bootstrap.css">
<link rel="stylesheet" href="assets/js/select2/select2.css">

<!-- Bottom scripts (common) -->
<script src="assets/js/gsap/TweenMax.min.js"></script>
<script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
<script src="assets/js/bootstrap.js"></script>
<script src="assets/js/joinable.js"></script>
<script src="assets/js/resizeable.js"></script>
<script src="assets/js/neon-api.js"></script>


<!-- Imported scripts on this page -->
<script src="assets/js/datatables/datatables.js"></script>
<script src="assets/js/select2/select2.min.js"></script>
<script src="assets/js/neon-chat.js"></script>


<!-- JavaScripts initializations and stuff -->
<script src="assets/js/neon-custom.js"></script>


<!-- Demo Settings -->
<script src="assets/js/neon-demo.js"></script>

<style>.modalDialog {  position: fixed;  font-family: Arial, Helvetica, sans-serif;  top: 0;  right: 0;  bottom: 0;  left: 0;  background: rgba(0, 0, 0, 0.8);  z-index: 999;  -webkit-transition: opacity 400ms ease-in;  -moz-transition: opacity 400ms ease-in;  transition: opacity 400ms ease-in;  display: none;  pointer-events: none;  }  .modalDialog:target {  display: block;  pointer-events: auto;  }</style>
</body>
</html>