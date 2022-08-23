<!DOCTYPE html>
<?php
define('_DEFOUT', 'AUD.USD.EUR.RUB', true);
if (isset($_GET['out']))
    if (ctype_alnum(str_replace('.', '', $_GET['out'])))
        define('_GETOUT', $_GET['out'], true);
define('_VALOUT', (defined('_GETOUT') ? _GETOUT : _DEFOUT), true);
// Получаем курсы на текущую дату
$CBRFDoc = simplexml_load_file('http://www.cbr.ru/scripts/XML_daily.asp');
$CBRFDat = array();
// Пихаем всё в ассоциативный массив
foreach ($CBRFDoc->children() as $CBRFItem) {
    $_chc = strval($CBRFItem->CharCode);
    $_res = array(
        'name' => strval($CBRFItem->Name),
        'value' => implode(".", explode(",", strval($CBRFItem->Value)))

    );
    $CBRFDat[$_chc] = $_res;
}
$CBRFDat[RUB] = array(
    'name' => "Российский рубль",
    'value' => 1
);
// Выводим таблицу 

$currency = 1;

if (isset($_POST['currency']))
    $currency = $_POST['currency'];
$init_cur = 1;
$out_cur = 1;
if (isset($_POST['calc_rates1']))
    $init_cur = $CBRFDat[$_POST['calc_rates1']]['value'];
if (isset($_POST['calc_rates2']))
    $out_cur = $CBRFDat[$_POST['calc_rates2']]['value'];
?>
<html>
<head>
    <title>Конвертер</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/reset.css" type="text/css" media="all">
    <link rel="stylesheet" href="css/layout.css" type="text/css" media="all">
    <link rel="stylesheet" href="css/style.css" type="text/css" media="all">
    <script type="text/javascript" src="js/jquery-1.4.2.js"></script>
    <script type="text/javascript" src="js/cufon-yui.js"></script>
    <script type="text/javascript" src="js/cufon-replace.js"></script>
    <!--[if lt IE 9]>
    <script type="text/javascript" src="http://info.template-help.com/files/ie6_warning/ie6_script_other.js"></script>
    <script type="text/javascript" src="js/html5.js"></script>
    <![endif]-->
</head>
<body id="page2">
<div class="main">
    <!-- header -->
    <header>
            <a href="index.html">
                <img id="logo" src="img/logo2.png" width="245" height="85" alt="JPG">
            </a>
		<nav>
			<ul id="menu">
				<li class="alpha"><a href="index.html"><span><span>Главная</span></span></a></li>
				<li id="menu_active"><a href="About.php"><span><span>Конвертер</span></span></a></li>
				<li><a href="Projects.html"><span><span>Проект</span></span></a></li>
				<li><a href="Contacts.html"><span><span>Контакты</span></span></a></li>
				<li class="omega"><a href="Sitemap.html"><span><span>Координаты</span></span></a></li>
			</ul>
		</nav>
		  </header>
    <section id="content">
        <div class="wrapper">
            <div class="box pad_bot1">
                <div class="pad marg_top">
                    <article class="col1">
                        <div class="wrapper">
                            <article class="col1">
                                <p><b>Курс валют</b></p>
                                <?php $_TOOUT = explode('.', _VALOUT);
                                echo '<table cellpadding="0" cellspacing="0" border="0" width="500px">' . "\n";
                                foreach ($_TOOUT as $_KEY => $_VAL) {
                                    if (isset($CBRFDat[$_VAL])) {
                                        echo " <tr>\n";
                                        echo " <td>" . $CBRFDat[$_VAL]['name'] . "</td>\n";
                                        echo " <td>" . $CBRFDat[$_VAL]['value'] . "</td>\n";
                                        echo " </tr>\n";
                                    }
                                }
                                echo "</table>\n";
                                ?>

                                <br><br>
                            </article>
                        </div>
                        <form id="ContactForm" action="" method="POST">
                            <div>
                                <div class="wrapper">
                                    <label for="calc_rates">
                                        <article class="col1">
                                            <b>Конвертер валют</b>
                                            из
                                    </label>
                                    <select name="calc_rates1" id="ratesoption">
                                        <option value="">Из</option>
                                        <?
                                        foreach ($CBRFDat as $key => $value)
                                            echo "<option value=\"" . $key . "\">" . $CBRFDat[$key]['name'] . "</option>";
                                        ?>

                                    </select>

                                    <label for="calc_rates"> в </label>
                                    <select name="calc_rates2" id="ratesoption">
                                        <option value="">В</option>
                                        <?
                                        foreach ($CBRFDat as $key => $value)
                                            echo "<option value=\"" . $key . "\">" . $CBRFDat[$key]['name'] . "</option>";
                                        ?>

                                    </select>
                    </article>
                </div>
                <br>

                <div class="wrapper">
                    <div class="bg3"><input type="text" name="currency" size="10"
                                            placeholder="<?php echo $currency; ?>"/></div>
                    <input class="button2" type="submit" value=" = " name="button"/>
                    <?php
                    echo (float)$currency * $init_cur / $out_cur;
                    ?>
                </div>

            </div>
            </form>
            </article>

        </div>
</div>


</div>
</section>
<!-- / content -->
</div>
<script type="text/javascript"> Cufon.now(); </script>
</body>
</html>