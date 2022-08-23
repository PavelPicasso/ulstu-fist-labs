<META NAME=Author CONTENT="Карпова Анна">
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<link rel="stylesheet" href="../../../CSS/PlansEditor.css" type="text/css">
</HEAD>
<BODY topmargin="1" leftmargin="5" marginheight="1" marginwidth="5">
<em class='h1'><center>Ввод нового цикла дисциплин</center></em>
		<table border='0' width='100%' cellpadding='0' cellspacing='2'>
		<tr><td height='5' bgcolor="#92a2d9"><img src="../img/line.gif" width=15 height=15 hspace=0 vspace=0 border='0' align='left'></td></tr>
		</table>
<H2>Заполните форму</H2>

<FORM METHOD='post' NAME='depform' ACTION='DoAddUndCic.php'>

<br><table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center">
<tr><td cellpadding="0" cellspacing="0">
 
<TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
<TR>
<TH><strong>Название цикла дисциплин</strong></TH>
<TH><strong>Сокращение</strong></TH>
</TR>
<TR>

<TD align='center'><INPUT TYPE=TEXT NAME="UndCicle"   SIZE='50' MAXLENGTH=100></INPUT></TD>
<TD align='center'><INPUT TYPE=TEXT NAME="UndCicRed"   SIZE='7' MAXLENGTH=7></INPUT></TD>
</TR>
</TABLE>
</TD>
</TR>
</TABLE>
<BR>
			
<TABLE  align='center'>
<TR>
<?php
if (!isset($shift)){$sh=0;}
else {$sh = 1;}

echo "<TD><INPUT TYPE='RADIO' NAME='shift' VALUE='0' ";
if($sh==0){echo "CHECKED ";}
echo ">Вернутся к справочнику компонентов";
echo "</TD>";
echo "<TD><INPUT TYPE='RADIO' NAME='shift' VALUE='1'";
if($sh==1){echo "CHECKED ";}
echo ">Ввести несколько новых компонентов</TD>";
?>
</TR>
</TABLE><BR>

<BR>
<INPUT TYPE='HIDDEN' NAME='hist'></INPUT>
<INPUT TYPE='HIDDEN' NAME='plan'></INPUT>
<CENTER><INPUT TYPE='SUBMIT' NAME='OK' VALUE='Добавить цикл дисциплин в справочник'></INPUT></CENTER>

</FORM>
</BODY>
</HTML>