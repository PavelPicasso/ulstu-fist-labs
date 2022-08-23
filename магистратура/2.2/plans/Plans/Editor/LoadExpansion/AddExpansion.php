<META NAME=Author CONTENT="Карпова Анна">
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<link rel="stylesheet" href="http://plans.nilaos.ustu/CSS/PlansEditor.css" type="text/css">
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
</SCRIPT>
</HEAD>
<BODY topmargin="1" leftmargin="5" marginheight="1" marginwidth="5">
<em class='h1'><center>Добавление расширения учебной нагрузки</center></em>
        <table border='0' width='100%' cellpadding='0' cellspacing='2'>
        <tr><td height='5' bgcolor="#92a2d9"><img src="../img/line.gif" width=15 height=15 hspace=0 vspace=0 border='0' align='left'></td></tr>
        </table>
<H2>Заполните форму</H2>

<FORM METHOD='post' NAME='depform' ACTION='DoAddExpansion.php'>

<br><table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center">
<tr><td cellpadding="0" cellspacing="0">
 
<TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
<TR>
<TH><strong>Кафедра</strong></TH>
<TH><strong>Вид учебной работы</strong></TH>
<TH><strong>Семестр</strong></TH>
<TH><strong>Количество часов</strong></TH>
</TR>
<TR>
<?php
   include("../PlanCalculatFunc.php");
   CreateConnection();

   //Массив с сокр. названиями кафедрами
   $RedDepartArray=array();
   $CodeDepartArray=array();
   $result = mysql_query("select Reduction, CodeOfDepart from department order by Reduction", $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   while ($row = mysql_fetch_object($result)){
      $RedDepartArray[] = $row->Reduction;
      $CodeDepartArray[] = $row->CodeOfDepart;
   }
      //Выбор кафедры
      echo "<TD><SELECT NAME=\"Depart\" onChange=\"FillArrChenge(this)\">\n";
      reset($RedDepartArray);
      reset($CodeDepartArray);
      while (($Dep = each($RedDepartArray)) && ($CodeDep = each($CodeDepartArray))){
         echo "<OPTION VALUE=".$CodeDep[1].">".$Dep[1];
      }
      echo "</SELECT></TD>\n";
?>
<TD align='center'><INPUT TYPE=TEXT NAME="ExpName"   SIZE='20' MAXLENGTH=220 VALUE=''>
</INPUT></TD>
<TD align='center'><SELECT NAME="Semestr" >
<OPTION VALUE=1>1
<OPTION VALUE=2>2
</SELECT></TD>
<TD align='center'><INPUT TYPE=TEXT NAME="Hours"   SIZE='7' MAXLENGTH=7 VALUE=''  
onChange="Validator(this)"></INPUT></TD>
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
echo ">Вернутся к справочнику расширений";
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
<CENTER><INPUT TYPE='SUBMIT' NAME='OK' VALUE='Добавить расширение учебной нагрузки в справочник'></INPUT></CENTER>

</FORM>
</BODY>
</HTML>