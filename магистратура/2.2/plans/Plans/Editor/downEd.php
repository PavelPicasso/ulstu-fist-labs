<HTML>
<HEAD>
<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=windows-1251\">
<!--  <link rel="stylesheet" href="../../CSS/PlansEditor.css" type="text/css">  -->
</HEAD>
<BODY topmargin=\"0\" leftmargin=\"1\" marginheight=\"0\" marginwidth=\"1\" bgcolor="#eeeeee">
<table align="center" cellpadding="0" cellspacing="2">
<tr><td align="center"><img src="img/Comands.gif" width=120 height=15 border=0 hspace="0" vspace="0" alt=""></td></tr>
<!--<tr><td align="center"><a href="PlansBook/ChoiseP.php" target="Body"><img src="img/Edit1.gif" width=120 height=15 border=0 hspace="0" vspace="0" alt=""></a></td></tr>-->
<!--<tr><td align="center"><a href="PlansBook/ChoiseDirect.php" target="Body"><img src="img/new.gif" width=120 height=15 border=0 hspace="0" vspace="0" alt=""></a></td></tr>
<tr><td align="center"><a href="PlansBook/Import.php" target="Body"><img src="img/import1.gif" width=120 height=15 border=0 hspace="0" vspace="0" alt=""></a></td></tr>-->
<tr><td align="center"><a href="PlansBook/ImportGUP.php" target="Body"><img src="img/ImportGUP.gif" width=120 height=15 border=0 hspace="0" vspace="0" alt=""></a></td></tr>
<?php
    if (!empty($_GET['plan'])) $plan=$_GET['plan'];
    if (!empty($_POST['plan'])) $plan=$_POST['plan'];	
    echo "<tr><td align=\"center\"><a href=\"../planFull.php?plan=$plan\" target=\"Body\"><img src=\"img/ShowPlan.gif\" width=120 height=15 border=0 hspace=\"0\" vspace=\"0\" alt=\"\"></a></td></tr>\n"
?>
<!--<tr><td align="center"><a href="PlansBook/ChoiseP.php" target="Body"><img src="img/NewDepart.gif" width=120 height=15 border=0 hspace="0" vspace="0" alt=""></a></td></tr>-->
</table>

</body>
</html>