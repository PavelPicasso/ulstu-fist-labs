<?php
   include("../Display/StartPage.php");

   include("../Display/DisplayFunc.php");
   DisplayPageTitle("","���� ������ ����� ���������");
?>
<FORM METHOD='post' NAME='depform' ACTION='DoNewCicle.php'>
<table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center">
<tr>
<td cellpadding="0" cellspacing="0"><TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
<TR>
<TH><strong>�������� ����� ���������</strong></TH>
<TH><strong>����������</strong></TH>
</TR>
<TR>
<TD align='center'><INPUT TYPE=TEXT NAME="Cicle"   SIZE='50' MAXLENGTH=150></INPUT></TD>
<TD align='center'><INPUT TYPE=TEXT NAME="CicRed"   SIZE='7' MAXLENGTH=7></INPUT></TD>
</TR>
</TABLE></TD>
</TR>
</TABLE><BR>
<TABLE  align='center'>
<TR>
<?php
    echo "<TD><INPUT TYPE='RADIO' NAME='shift' VALUE='0' ";
    if (empty($sh) || $sh=="0")
        echo "CHECKED";
    echo ">�������� � ����������� ������</TD>\n";
    echo "<TD><INPUT TYPE='RADIO' NAME='shift' VALUE='1' ";
    if (!empty($sh) && $sh)
        echo "CHECKED";
    echo ">������ ��������� ����� ������</TD>\n";
?>
</TR>
</TABLE>
<BR><BR>
<CENTER>
<INPUT TYPE='SUBMIT' NAME='OK' VALUE='�������� ���� ��������� � ����������'>
</CENTER>
</FORM>
<?php
   include("../Display/FinishPage.php");
?>