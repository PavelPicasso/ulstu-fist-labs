<?php
	include("../PlanCalculatFunc.php");
	CreateConnection();
	$OldAdr="";
	if ($REQUEST_METHOD=='POST' && !empty($Adress)) {
		FetchQuery("update metalimits SET LimitsAdr='$Adress'");
		mysql_close($Connection);
		Header ("Location: Standard.php");
		exit;
	}

	$OldAdr=FetchResult("SELECT LimitsAdr from metalimits");
	mysql_close($Connection);

	include("../Display/StartPage.php");
	include("../Display/DisplayFunc.php");
	DisplayPageTitle("","Редактирование данных по стандартам");
?>
<FORM METHOD='post' NAME='UpdLimits' ACTION=''>
<br><table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center">
<tr><td cellpadding="0" cellspacing="0">
<TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
<TR><TH><strong>Путь к файлам стандартов</strong></TH></TR>
<TR><TD align='center'>
<?php
	echo "<INPUT TYPE=TEXT NAME=\"Adress\" VALUE=\"$OldAdr\"  SIZE='100' MAXLENGTH=100></INPUT>";
?>
</TD>
</TR>
</TABLE>
</TD></TR>
</TABLE><BR>
<CENTER>
<TABLE  align='center'>
<TR><TD>
<INPUT TYPE='SUBMIT' NAME='Esk' VALUE='Отмена' ONCLICK = "UpdLimits.action='Standard.php'"></INPUT>
&nbsp;&nbsp;&nbsp;&nbsp;
</TD><TD>
&nbsp;&nbsp;&nbsp;&nbsp;<INPUT TYPE='SUBMIT' NAME='OK' VALUE='Внести изменения' ONCLICK = "UpdLimits.action='EditMeta.php'"></INPUT>
</TABLE>

</CENTER>
</FORM>
<HR>
</BODY></HTML>
