<?php
   include("../PlanCalculatFunc.php");
   CreateConnection();
   include("../Display/StartPage.php");

   include("../Display/DisplayFunc.php");

   DisplayPageTitle("","���� ����� ����������");
   if (isset($_GET['sh'])) $sh = $_GET['sh'];
   $Departs = FetchArrays("select Reduction, CodeOfDepart from department order by Reduction");
?>
<FORM name="eee" action="DoNewDiscip.php" method="post">
<table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center">
<tr><td cellpadding="0" cellspacing="0">
<TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
<TR>
<TD align='center'><strong>��������</strong></TD>
<TD align='center'><strong>�������� ����������</strong></TD>
<TD align='center'><strong>����������</strong></TD>
<TD align='center'><strong>������� ������������ �� ���������</strong></TD>
</TR>
<TR>
<TD align='center'><INPUT TYPE=TEXT NAME="standart" SIZE=7 maxlength=7></INPUT></TD>
<TD align='center'><INPUT TYPE=TEXT NAME="DiscipN" SIZE=45 MAXLENGTH=150></INPUT></TD>
<TD align='center'><INPUT TYPE=TEXT NAME="Reduction" SIZE=10 MAXLENGTH=30></INPUT></TD>
<TD align='center'><SELECT name="DepartN">
<?php
foreach ($Departs as $kc => $vc) {
    echo "<OPTION VALUE=$vc[CodeOfDepart]>$vc[Reduction]\n";
} 
?>
</SELECT></TD>
</TR>
</TABLE></td>
</tr>
</table>
<BR><CENTER>
<TABLE>
<TR>
<?php
    if (!empty($back) && $REQUEST_METHOD=='GET' && $_GET['back'])
        echo "<INPUT TYPE='HIDDEN' NAME='back' value=\"$back\"></INPUT>\n";
    else {
        echo "<TD><INPUT TYPE='RADIO' NAME='shift' VALUE='0' ";
        if (empty($sh) || $sh=="0")
            echo "CHECKED";
        echo ">�������� � ����������� ���������</TD>\n";
        echo "<TD><INPUT TYPE='RADIO' NAME='shift' VALUE='1' ";
        if (!empty($sh) && $sh)
            echo "CHECKED";
        echo ">������ ��������� ����� ���������</TD>\n";
    }
?>
</TR>
</TABLE>
<BR><BR>
<CENTER>
<INPUT TYPE='SUBMIT' NAME='OK' VALUE='�������� ���������� � ������ ���������'>
</CENTER>
</FORM>
<?php
   include("../Display/FinishPage.php");
?>