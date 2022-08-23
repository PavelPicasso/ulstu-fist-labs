<?php
   include("../PlanCalculatFunc.php");
   CreateConnection();
    if (!empty($_GET['plan'])) $plan=$_GET['plan'];
    if (!empty($_POST['plan'])) $plan=$_POST['plan'];
   if (empty($plan)) {
       $message = "Plan is empty";
       include("../alert.php");
       exit;
   }
   include("../Display/StartPage.php");
   include("../Display/DisplayFunc.php");

   include ("../Display/ValidationField.php");

   DisplayPageTitle("../downEd.php?plan=$plan","Редактирование учебного плана","Добавление новой записи");


   $Departs = FetchArrays("select Reduction, CodeOfDepart from department order by Reduction");
   $Disciplines = FetchArrays("select CodeOfDiscipline,DisName from disciplins order by DisName");
   $UndCicles = FetchArrays("select  CodeOfUnderCicle, UndCicReduction from undercicles order by CodeOfUnderCicle");
   $Cicles = FetchArrays("select CodeOfCicle, CicReduction from cicles order by CodeOfCicle");
   $CurrentCicles = FetchArrays("select distinct cicles.CodeOfCicle, cicles.CicName, cicles.CicReduction from cicles, schedplan where cicles.CodeOfCicle=schedplan.CodeOfCicle and schedplan.CodeOfPlan=$plan order by cicles.CodeOfCicle");
   $CurrentCicles[] = array("CicName"=>"Без раздела", "CodeOfCicle"=>"0");

   $CurrentDiscip = "";
   if (!empty($discip)) 
       $CurrentDiscip = FetchFirstRow("select schedplan.* from schedplan where schedplan.CodeOfSchPlan=$discip");

   $result = mysql_query("select MIN(KursNumb) as StartKurs, MAX(KursNumb) as EndKurs from schedules where CodeOfPlan=$plan", $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   $Period = mysql_fetch_assoc($result);
?>
<H3>Заполните форму</H3>
<FORM name='add' METHOD='POST' ACTION='DoAddRecInP.php'>
<table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center">
<tr><td cellpadding="0" cellspacing="0">
<TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 width=100%>
<TR ALIGN='CENTER' VALIGN='MIDDLE'>
<TD align="center"><B>Название дисциплины</B></TD>
<TD width=90 align="center"><B>Код цикла</B></TD>
<TD width=90 align="center"><B>Код компонента</B></TD>
<TD width=90 align="center"><B>Номер в компоненте</B></TD>
</TR>
<TR>
<?php
   echo "<TD align=\"center\"><SELECT NAME=\"CodeOfDiscipline\">";
   foreach ($Disciplines as $kc => $vc) {
       echo "<OPTION VALUE=$vc[CodeOfDiscipline]";
       if ($CurrentDiscip != "" && $CurrentDiscip["CodeOfDiscipline"] == $vc["CodeOfDiscipline"])
           echo " SELECTED";
       echo ">$vc[DisName]";
   }
   echo "</SELECT></TD>";
   echo "<TD align=\"center\"><SELECT NAME=\"CodeOfCicle\">";
   foreach ($Cicles as $kc => $vc) {
       echo "<OPTION VALUE=$vc[CodeOfCicle]";
       if ($CurrentDiscip != "" && $CurrentDiscip["CodeOfCicle"] == $vc["CodeOfCicle"])
           echo " SELECTED";
       echo ">$vc[CicReduction]";
   } 
   echo "<OPTION VALUE=0>Без цикла";
   echo "</SELECT></TD>";
   echo "<TD align=\"center\"><SELECT NAME=\"CodeOfUndCicle\">";
   foreach ($UndCicles as $kc => $vc) {
       echo "<OPTION VALUE=$vc[CodeOfUnderCicle]";
       if ($CurrentDiscip != "" && $CurrentDiscip["CodeOfUndCicle"] == $vc["CodeOfUnderCicle"])
           echo " SELECTED";
       echo ">$vc[UndCicReduction]";
   } 
   echo "</SELECT></TD>";
   echo "<TD align=\"center\"><INPUT TYPE=TEXT NAME=\"UndCicCode\"  SIZE=3 MAXLENGTH=10 VALUE=\"";
   if ($CurrentDiscip != "")
     echo $CurrentDiscip["UndCicCode"];
   echo "\"></TD>";
?>
</TR>
</TABLE></td>
</tr>
</table>
<BR>
<BR>
<table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center">
<tr><td cellpadding="0" cellspacing="0"><TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1  width=100%>
<TR    VALIGN='MIDDLE'>
<TD width=80 align="center"><B>Номер семестра</B></TD>
<TD width=80 align="center"><B>Экзамен</B></TD>
<TD width=80 align="center"><B>Зачет</B></TD>
<TD width=80 align="center"><B>Курсовой проект</B></TD>
<TD width=80 align="center"><B>Курсовая работа</B></TD>
<TD width=100 align="center"><B>РГР</B></TD>
<TD width=100 align="center"><B>Рефераты</B></TD>
</TR>
<TR>
<?php
   echo "<TD align=\"center\"><SELECT NAME=\"NumbOfSemestr\">";
   for ($i=$Period["StartKurs"]*2-1; $i<=$Period["EndKurs"]*2; $i++)
        echo "<OPTION VALUE=$i>$i";
   echo "</SELECT></TD>";
?>
<TD align="center"><INPUT TYPE='checkbox' NAME='Exam'  VALUE='1'> </TD>
<TD align="center"><INPUT TYPE='checkbox' NAME='Test'  VALUE='1'> </TD>
<TD align="center"><INPUT TYPE='checkbox' NAME='KursPrj' VALUE='1'> </TD>
<TD align="center"><INPUT TYPE='checkbox' NAME='KursWork' VALUE='1'></TD>
<TD align="center"><INPUT TYPE=TEXT NAME='RGR' SIZE=1 MAXLENGTH=2 onChange="Validator(this)"></INPUT></TD>
<TD align="center"><INPUT TYPE=TEXT NAME='Synopsis' SIZE=1 MAXLENGTH=2 onChange="Validator(this)"></INPUT></TD>
</TR>
</TABLE></td>
</tr>
</table>
<BR>
<BR>
<table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center">
<tr><td cellpadding="0" cellspacing="0"><TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1  width=100%>
<TR    VALIGN='MIDDLE'>
<TD width=100 align="center"><B>Лекций в неделю</B></TD>
<TD width=100 align="center"><B>Лабораторных в неделю</B></TD>
<TD width=100 align="center"><B>Практик в неделю</B></TD>
<TD width=100 align="center"><B>Общий объем часов</B></TD>
<TD width=140 align="center"><B>Кафедра, ведущая дисциплину</B></TD>
<TD width=80 align="center"><B>Учитывать при подсчете</B></TD>
</TR>
<TR>
<TD align="center"><INPUT TYPE=TEXT NAME='LectInW' SIZE=1 MAXLENGTH=2 onChange="Validator(this)"></INPUT></TD>
<TD align="center"><INPUT TYPE=TEXT NAME='LabInW' SIZE=1 MAXLENGTH=2 onChange="Validator(this)"> </INPUT></TD>
<TD align="center"><INPUT TYPE=TEXT NAME='PractInW' SIZE=1 MAXLENGTH=2 onChange="Validator(this)"></INPUT></TD>

<?php
   echo "<TD align=\"center\"><INPUT TYPE=TEXT NAME='AllH' SIZE=2 MAXLENGTH=5 onChange=\"Validator(this)\" VALUE=\"";
   if (!empty($CurrentDiscip))
     echo $CurrentDiscip["AllH"];
   echo "\"></TD>";
   echo "<TD align=\"center\"><SELECT NAME=\"CodeOfDepart\">";
   foreach ($Departs as $kc => $vc)
       echo "<OPTION VALUE=$vc[CodeOfDepart]>$vc[Reduction]";
   echo "</SELECT></TD>";
   echo "<TD align=\"center\"><INPUT TYPE='checkbox' NAME='ToCount' VALUE='1'";
   if ($CurrentDiscip != "") {
      if ($CurrentDiscip["ToCount"])
         echo " CHECKED";
   } else
      echo " CHECKED";
   echo "></TD>";
?>

</TR>
</TABLE></td>
</tr>
</table>
<br>
<TABLE  align='center'>
<TR>
<?php
   echo "<TD><INPUT TYPE='RADIO' NAME='shift' VALUE='0'";
   if (empty($shift))
      echo " CHECKED";
   echo ">Вернутся к редактированию плана<TD>";
   echo "<TD><INPUT TYPE='RADIO' NAME='shift' VALUE='1'";
   if (!empty($shift) && $shift==1)
      echo " CHECKED";
   echo ">Ввести несколько новых дисциплин<TD></TR>\n";
   echo "<input type='hidden' name='plan' value='$plan'>\n";
   echo "<input type='hidden' name='discip' value='$discip'>\n";
   echo "<input type='hidden' name='cicle' value='$cicle'>\n";
   echo "<input type='hidden' name='ControlWork' value=''>\n";
?>
</TABLE>
<BR>
<CENTER><INPUT TYPE='SUBMIT' NAME='OK' VALUE='Внести дисциплину в учебный план' ></INPUT></CENTER>
</FORM>
<?php
   
   include("../Display/FinishPage.php");
   mysql_close($Connection);
?>