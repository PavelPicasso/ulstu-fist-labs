<META NAME=Author CONTENT="Карпова Анна">
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<link rel="stylesheet" href="../../../CSS/PlansEditor.css" type="text/css">
<SCRIPT language="JavaScript">
        <!--
            function Validator(theForm) { return; }
        //-->
    </SCRIPT>
<SCRIPT language="JavaScript">
        <!--
function Validator(theForm) 
            { 
                var checkOK="0123456789";
                var checkStr = theForm.value;
                var allValid = true;
                for (i=0; i<checkStr.length; i++)
                {
                    ch = checkStr.charAt(i);
                    for(j=0;j<checkOK.length;j++)
                        if (ch == checkOK.charAt(j)) break;
                    if (j == checkOK.length)
                    {
                        allValid = false;
                        break;
                    }
                }
                    if (allValid == false)
                    {
                        alert("В данное поле разрешен ввод только цифр");
                        theForm.value=theForm.defaultValue;
                        theForm.focus();
                        return (false);
                    }
                return (true); 
                        }
        // -->
        </script>
</HEAD>
<BODY topmargin="1" leftmargin="5" marginheight="1" marginwidth="5">
<em class='h1'><center>Добавление наименования квалификации выпускника</center></em>
        <table border='0' width='100%' cellpadding='0' cellspacing='2'>
        <tr><td height='5' bgcolor="#92a2d9"><img src="../img/line.gif" width=15 height=15 hspace=0 vspace=0 border='0' align='left'></td></tr>
        </table>
<H2>Заполните форму</H2>

<FORM METHOD='post' NAME='depform' ACTION='DoAddDegree.php'>

<br><table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center">
<tr><td cellpadding="0" cellspacing="0">
 
<TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
<TR>
<TH WIDTH ='40%'><strong>Название степени</strong></TH>
<TH WIDTH ='20%'><strong>Срок обучения<BR>(по умолчанию)</strong></TH>
<TH WIDTH ='20%'><strong>Начальный курс<BR>(по умолчанию)</strong></TH>
<TH WIDTH ='20%'><strong>Порядок</strong></TH>
</TR>
<TR>

<TD align='center'><INPUT TYPE=TEXT NAME="DegreeName"   SIZE='30' MAXLENGTH=30></INPUT></TD>
<TD align='center'><INPUT TYPE=TEXT NAME="Apprenticeship"   SIZE='3' MAXLENGTH=3         onChange="Validator(this)"></INPUT></TD>
<TD align='center'><INPUT TYPE=TEXT NAME="FirstYear"   SIZE='3' MAXLENGTH=3          onChange="Validator(this)"></INPUT></TD>
<TD align='center'><INPUT TYPE=TEXT NAME="Sequence"   SIZE='3' MAXLENGTH=3          onChange="Validator(this)"></INPUT></TD>
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
echo ">Вернутся к справочнику направлений";
echo "</TD>";
echo "<TD><INPUT TYPE='RADIO' NAME='shift' VALUE='1'";
if($sh==1){echo "CHECKED ";}
echo ">Ввести несколько новых направлений</TD>";
?>
</TR>
</TABLE><BR>

<BR>
<CENTER><INPUT TYPE='SUBMIT' NAME='OK' VALUE='Добавить степень в справочник'></INPUT></CENTER>

</FORM>
</BODY>
</HTML>