<?php
//Выводит заголовок страницы
function DisplayPageTitle($SubMenuURL='', $PageTitle='', $PageSubTitle="") {
    global $PlansDir;
    if (empty($SubMenuURL))
        $SubMenuURL = "/$PlansDir/Plans/Editor/down4.html";
?>
<script  language="JavaScript">
<!--
<?php
    echo "top.LeftDown.location='$SubMenuURL';\n";   
?>
//-->
</script>

<?php
    echo "<em class='h1'><center>$PageTitle</center></em>";
?>

<table  class='line' border='0' width='100%' cellpadding='0' cellspacing='2'>
<tr>
<?php
    echo "<td><img src='/$PlansDir/img/line.gif' width='1' height='15' hspace=0 vspace=0 border='0'></td>\n";
?>
</tr>
</table>                           
<br>

<?php
    if ($PageSubTitle != "")
    echo "<H2>$PageSubTitle</H2>";
}

//Выводит сообщение об ошибке и переходит на предыдущую страницу
function FuncAlert ($message,$page) {
?>
<HTML>
<HEAD>
<SCRIPT LANGUAGE="JAVASCRIPT">
<?php
    echo "alert(\"$message\");\n";
    if (!empty($page))
        echo "location='$page'";
    else
        echo "history.go(-1)\n";

?>
//Stop hiding the code-->
</SCRIPT>
</HEAD>
</HTML>
<?php
   exit;
}
?>