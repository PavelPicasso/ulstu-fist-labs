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
   $CodeOfPlan = intval($plan);

   //Формы обучения
   $keys = array('очная','заочная','вечерняя','дистанционная','экстернат');
   $forms = array('classroom','correspondence','night','distance','external');

   include("../Display/StartPage.php");
   include("../Display/DisplayFunc.php");

   DisplayPageTitle("../down0.html","Редактирование заголовка плана");
   $PlanData = GetPlanInfo($CodeOfPlan);
?>
<form name=fed method=post action="">
<br>
<table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center">
<tr><td cellpadding="0" cellspacing="0">
<TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
<TR ALIGN='CENTER' VALIGN='MIDDLE'>
<TH><strong>Специализация</strong></TH>
<TH><strong>Название плана</strong></TH>
<TH><strong>Фрма обучения</strong></TH>
</TR>

<TR>
<?php
    echo "<TD align='center'><B>$PlanData[PlanSpcCode]</B></TD>\n";
    echo "<TD align='center'>";
    echo "<INPUT TYPE=TEXT NAME='PlnName' SIZE='31' MAXLENGTH='35' VALUE='$PlanData[PlnName]'>";
    echo "</TD>\n";
    echo "<TD align='center'>";
    echo   "<SELECT NAME='TchForm'>\n";
    while (($f = each($forms)) && ($k = each($keys))){  
        echo "<OPTION VALUE='".$f[1]."'";
        if ($PlanData["TeachForm"] == $f[1])
            echo "SELECTED";
        echo ">".$k[1]."\n"; 
    }
    echo "</SELECT></TD>\n";
?>
</TR>

</TABLE>


</td></tr></table><BR>
<TABLE BORDER=0 ALIGN=CENTER>
<TR>
<TD><CENTER><INPUT TYPE='SUBMIT' NAME='Edit' VALUE='Внести изменения' onClick="fed.action='DoPlanHEd.php'"></INPUT></CENTER></TD>
<TD><CENTER><INPUT TYPE='RESET' NAME='reset' VALUE='Отменить изменения'></INPUT></CENTER></TD>
</TR>

<TR>
<TD colspan="2"><CENTER><INPUT TYPE='SUBMIT' NAME='EditSpc' VALUE='Редактировать специальность' onClick="fed.action='ChoiseDirect.php'"></INPUT></CENTER></TD>
</TR>

</TABLE>



</center>
<?php
    echo "<input type='hidden' name='plan' value='$CodeOfPlan'>\n";
?>
<input type='hidden' name='shift' value='editSpc'>
</form>
<HR>
<?php
   include("../Display/FinishPage.php");
   mysql_close($Connection);
?>
