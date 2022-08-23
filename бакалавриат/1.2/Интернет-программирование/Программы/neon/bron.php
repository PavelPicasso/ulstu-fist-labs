<?php
session_start();
?>
<!DOCTYPE html>
<html class="nojs html css_verticalspacer" lang="ru-RU">
<head>

    <meta http-equiv="Content-type" content="text/html;charset=UTF-8"/>
    <meta name="generator" content="2015.2.0.352"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <script type="text/javascript">
        // Update the 'nojs'/'js' class on the html node
        document.documentElement.className = document.documentElement.className.replace(/\bnojs\b/g, 'js');

        // Check that all required assets are uploaded and up-to-date
        if (typeof Muse == "undefined") window.Muse = {};
        window.Muse.assets = {
            "required": ["jquery-1.8.3.min.js", "museutils.js", "museconfig.js", "jquery.watch.js", "require.js", "bron.css"],
            "outOfDate": []
        };
    </script>

    <title>bron</title>
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="css/site_global.css?crc=3916556066"/>
    <link rel="stylesheet" type="text/css" href="css/bron.css?crc=4085880880" id="pagesheet"/>
</head>
<body>
<?php
require_once("connection.php");

// Проверяем если существуют данные в сессий.
if (isset($_SESSION['session_username'])) {

    // Вставляем данные из сессий в обычные переменные
    $username = $_SESSION['session_username'];

    // Делаем запрос к БД для выбора данных.
    $query = "SELECT * FROM users WHERE username = '" . $username . "'";
    $result = mysqli_query($con, $query) or die ("Error : " . mysqli_error());
    $row = mysqli_fetch_assoc($result);
    echo $row["username"]; ?><br><?
    if ($row["role"] == "0") {
        echo "User";?><br><?
    } else if ($row["role"] == "1") {
        echo "Moderator";
        $query2 = "SELECT * FROM users";
        $result2 = mysqli_query($con, $query2) or die ("Error : " . mysqli_error());
        while ($row2 = mysqli_fetch_array($result2)) {
            if ($row2["role"] == "0") {
                echo "<li>";
                echo $row2["username"];
                echo "<a href=\"#openModal2-".$row2["id"]."\">Изменить</a>";
                echo "</li>";
                echo "
            <div id=\"openModal2-".$row2["id"]."\" class=\"modalDialog\">
                <div>
                    <a href=\"#close\" title=\"Закрыть\" class=\"close\">X</a>
                    <h2>Изменение пользователя</h2>
                    <form action=\"edit.php\" method=\"post\">
                        <input type=\"hidden\" value=\"".$row2["id"]."\" name=\"id\">
                        <label>Имя</label> <br>
                        <input type=\"text\" value=\"".$row2["username"]."\" name=\"username\"><br>
                        <label>Пароль</label><br>
                        <input type=\"password\" value=\"".$row2["password"]."\" name=\"password\"><br>
                        
                        <select name=\"option\">
                            <option value=\"0\" ";
                            if ($row2["role"] == "0") { echo "selected"; }
                            echo ">User</option>
                            <option value=\"1\" ";
                            if ($row2["role"] == "1") { echo "selected"; }
                            echo ">Moderator</option>
                        </select>
                        <br>
                        
                        <input type=\"submit\" name=\"button\" value=\"Изменить\">
                    </form>
                </div>
            </div>";
                continue;
            }

        }
    } else if ($row["role"] == "2") {
        $query2 = "SELECT * FROM users";
        $result2 = mysqli_query($con, $query2) or die ("Error : " . mysqli_error());
        while ($row2 = mysqli_fetch_array($result2)) {
            if ($row2["id"] == $row["id"]) {
                echo "<li>";
                echo $row2["username"];
                echo "<a href=\"#openModal2-".$row2["id"]."\">Изменить</a>";
                echo "</li>";
                echo "
                <div id=\"openModal2-".$row2["id"]."\" class=\"modalDialog\">
                    <div>
                        <a href=\"#close\" title=\"Закрыть\" class=\"close\">X</a>
                        <h2>Изменение пользователя</h2>
                        <form action=\"edit.php\" method=\"post\">
                            <input type=\"hidden\" value=\"".$row2["id"]."\" name=\"id\">
                            <label>Имя</label> <br>
                            <input type=\"text\" value=\"".$row2["username"]."\" name=\"username\"><br>
                            <label>Пароль</label><br>
                            <input type=\"password\" value=\"".$row2["password"]."\" name=\"password\"><br>
                            
                            <input type=\"submit\" name=\"button\" value=\"Изменить\">
                        </form>
                    </div>
                 </div>";
                continue;
            }

            echo "<li>";
            echo $row2["username"];
            echo "<a href=\"#openModal2-".$row2["id"]."\">Изменить </a>";
            echo "<a href=\"#openModal-".$row2["id"]."\">Удалить</a>";
            echo "
            <div id=\"openModal-".$row2["id"]."\" class=\"modalDialog\">
                <div>
                <h2>Вы точно хотите удалить этого пользователя?</h2>
                <span class=\"left\"><a href=\"delete.php?id=".$row2["id"]."\">Да</a></span>
                <span class=\"right\"><a href=\"#close\" title=\"Закрыть\">Нет</a></span>
                </div >
            </div >";
            echo "
            <div id=\"openModal2-".$row2["id"]."\" class=\"modalDialog\">
                <div>
                    <a href=\"#close\" title=\"Закрыть\" class=\"close\">X</a>
                    <h2>Изменение пользователя</h2>
                    <form action=\"edit.php\" method=\"post\">
                        <input type=\"hidden\" value=\"".$row2["id"]."\" name=\"id\">
                        <label>Имя</label> <br>
                        <input type=\"text\" value=\"".$row2["username"]."\" name=\"username\"><br>
                        <label>Пароль</label><br>
                        <input type=\"password\" value=\"".$row2["password"]."\" name=\"password\"><br>
                        
                        <select name=\"option\">
                            <option value=\"0\" ";
                            if ($row2["role"] == "0") { echo "selected"; }
                            echo ">User</option>
                            <option value=\"1\" ";
                            if ($row2["role"] == "1") { echo "selected"; }
                            echo ">Moderator</option>
                            <option value=\"2\" ";
                            if ($row2["role"] == "2") { echo "selected"; }
                           echo ">Admin</option>
                        </select>
                        <br>
                        
                        <input type=\"submit\" name=\"button\" value=\"Изменить\">
                    </form>
                </div>
            </div>";
            echo "</li>";
        }
    }
    echo "<a href=\"logout.php\">Выйти</a>"; ?><br><?
} else {
    echo "Вход доступен только авторизированным пользователям! Перейти на <a href='index.php'>главную страницу</a>";
}
?>




<style>
    .left {
        float: left;
    }
    .right {
        float: right;
    }
    .modalDialog {
        position: fixed;
        font-family: Arial, Helvetica, sans-serif;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        background: rgba(0,0,0,0.8);
        z-index: 99999;
        -webkit-transition: opacity 400ms ease-in;
        -moz-transition: opacity 400ms ease-in;
        transition: opacity 400ms ease-in;
        display: none;
        pointer-events: none;
    }

    .modalDialog:target {
        display: block;
        pointer-events: auto;
    }

    .modalDialog > div {
        width: 300px;
        position: relative;
        margin: 10% auto;
        padding: 7px 23px 23px 22px;
        border-radius: 10px;
        background: #fff;
        background: -moz-linear-gradient(#fff, #999);
        background: -webkit-linear-gradient(#fff, #999);
        background: -o-linear-gradient(#fff, #999);
    }

    .close {
        background: #606061;
        color: #FFFFFF;
        line-height: 25px;
        position: absolute;
        right: -12px;
        text-align: center;
        top: -10px;
        width: 24px;
        text-decoration: none;
        font-weight: bold;
        -webkit-border-radius: 12px;
        -moz-border-radius: 12px;
        border-radius: 12px;
        -moz-box-shadow: 1px 1px 3px #000;
        -webkit-box-shadow: 1px 1px 3px #000;
        box-shadow: 1px 1px 3px #000;
    }
    .close:hover { background: #00d9ff; }
</style>

</body>
</html>
