<?php
    include("../PlanCalculatFunc.php");
    CreateConnection();

    if (empty($plitem)) {
        $message = "Ни одной дисциплины не выбрано";
        include("../alert.php");
        exit;
    }
    include("../Display/StartPage.php");
    include("../Display/DisplayFunc.php");

    if (empty($cicle)) $cicle=0;
    if (empty($discip)) $discip=0;

    $discipline = FetchFirstRow("select DisName, schplanitems.CodeOfDepart,  schplanitems.NumbOfSemestr, schedplan.CodeOfDiscipline, PlnName, SpzName, SpcName, DirName, specializations.MinistryCode as SpzCode, specials.MinistryCode as SpcCode, directions.MinistryCode as DirCode, plans.CodeOfPlan  from plans, disciplins, schplanitems, schedplan LEFT JOIN specials ON specials.CodeOfSpecial=plans.CodeOfSpecial LEFT JOIN specializations ON specializations.CodeOfSpecialization=plans.CodeOfSpecialization  LEFT JOIN directions ON directions.CodeOfDirect=plans.CodeOfDirect where plans.CodeOfPlan=schedplan.CodeOfPlan and schedplan.CodeOfSchPlan=schplanitems.CodeOfSchPlan and schplanitems.CodeOfSchPlanItem='$plitem' and disciplins.CodeOfDiscipline=schedplan.CodeOfDiscipline");

    $PlanData = GetPlanInfo($discipline["CodeOfPlan"]);
        $PlTitle = "$PlanData[PlanSpcCode]&nbsp;&nbsp;&nbsp;$PlanData[PlanSpcName]&nbsp;&nbsp;&nbsp;$PlanData[PlnName]";

    DisplayPageTitle("/$PlansDir/Plans/Editor/downEd.php?plan=$discipline[CodeOfPlan]","Слияние потоков и разбиение на подгруппы дисциплины '$discipline[DisName]'",$PlTitle);
?>
<form name=fed method=post action="BlenStreams.php">
<H1>Слияние потоков</H1>
<?php
    $Kurs = ceil ($discipline["NumbOfSemestr"]/2);
    $AvailPlans = FetchArrays("select CodeOfPlan from schedplan, schplanitems where schplanitems.NumbOfsemestr='$discipline[NumbOfSemestr]' and schedplan.CodeOfDiscipline='$discipline[CodeOfDiscipline]' and schplanitems.CodeOfDepart='$discipline[CodeOfDepart]' and schplanitems.CodeOfSchPlan = schedplan.CodeOfSchPlan");
    
    $codes = '\'0\'';
    foreach ($AvailPlans as $k=>$v)
        $codes .= ",$v[CodeOfPlan]";
    $Streams = FetchArrays("select * from streams where Kurs='$Kurs' and CodeOfPlan in ($codes)");
    
?>
<table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center">
<tr><td>
<TABLE  class='table' BORDER=1 ALIGN=CENTER CELLSPACING=1 CELLPADDING=3 WIDTH='100%'>
<TR ALIGN='CENTER' VALIGN='MIDDLE'>
<TH width=250><FONT COLOR='#1F4D1C'>Название Потока</FONT></TH>
<TH width=60 ><FONT COLOR='#1F4D1C'>лаб</FONT></TH>
<TH width=60 ><FONT COLOR='#1F4D1C'>прак</FONT></TH>
<TH width=60 ><FONT COLOR='#1F4D1C'>лек</FONT></TH>
</TR>
<?php
foreach ($Streams as $k => $v) {
    echo "<TR>\n";
    echo "<TD align='center'>$v[StreamName]</TD>\n";

    foreach ($discip_types as $kd => $vd) {

        $IsBlend = FetchFirstRow("select blending.CodeOfBlend from blending, streamblending where blending.CodeOfBlend=streamblending.CodeOfBlend and streamblending.CodeOfStream = '$v[CodeOfStream]' and BlendStyle='$vd' and blending.CodeOfDiscipline = '$discipline[CodeOfDiscipline]' and blending.NumbOfSemestr='$discipline[NumbOfSemestr]' and blending.CodeOfDepart = '$discipline[CodeOfDepart]'");
        $checked = $IsBlend? " checked":"";
        echo "<TD align='center'><input type='checkbox' name='".$vd."[$v[CodeOfStream]]'$checked></TD>\n";
    }
    echo "</TR>\n";
}
?>

</TABLE>
</td>
</tr>
</table>
<BR>
<TABLE BORDER=0 ALIGN=CENTER>
<TR>
<TD align="center"><INPUT TYPE='SUBMIT' NAME='Edit' VALUE='Внести изменения'></TD>
<TD align="center"><INPUT TYPE='RESET' NAME='reset' VALUE='Отменить изменения'></TD>
</TR>

</TABLE>
<?php
    echo "<input type='hidden' name='CodeOfDiscipline' value='$discipline[CodeOfDiscipline]'>\n";
    echo "<input type='hidden' name='CodeOfDepart' value='$discipline[CodeOfDepart]'>\n";
    echo "<input type='hidden' name='NumbOfSemestr' value='$discipline[NumbOfSemestr]'>\n";
    echo "<input type='hidden' name='plitem' value='$plitem'>\n";
?>
</form>

<form name=div method=post action="BlenStreams.php">
<H1>Разбиение на подгруппы</H1>
<table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center">
<tr><td>
<TABLE  class='table' BORDER=1 ALIGN=CENTER CELLSPACING=1 CELLPADDING=3 WIDTH='100%'>
<TR ALIGN='CENTER' VALIGN='MIDDLE'>
<TH width=60 align="center" ><FONT COLOR='#1F4D1C'>Удалить</FONT></TH>
<TH width=250 align="center"><FONT COLOR='#1F4D1C'>Название Потока</FONT></TH>
<TH width=60 align="center" ><FONT COLOR='#1F4D1C'>лаб/прак/лек</FONT></TH>
<TH width=60 align="center" ><FONT COLOR='#1F4D1C'>Количество подгрупп</FONT></TH>
</TR>

<?php

$DivGroups = FetchArrays("select * from divdiscip, streams where divdiscip.CodeOfSchPlanItem='$plitem' and divdiscip.CodeOfStream=streams.CodeOfStream");
$DivStreams = FetchArrays("select * from streams where Kurs='$Kurs' and CodeOfPlan='$discipline[CodeOfPlan]'");
if (!empty($DivStreams)) {
foreach ($DivGroups as $k => $v) {
    

    echo "<TR>\n";
    echo "<TD align='center'><INPUT type='checkbox' value='$v[CodeOfDiv]' name='divgoups[Delete][$v[CodeOfDiv]]'></TD>\n";
    echo "<TD align='center'>$v[StreamName]</TD>\n";
    echo "<TD align='center'>".GetRusName($v["BlendStyle"])."</TD>\n";
    echo "<TD align='center'><INPUT type='text' value='$v[DivRate]' name='divgoups[DivRate][$v[CodeOfDiv]]' size='2'></TD>\n";
    echo "</TR>\n";
    
}
    echo "<TR><TH colspan='4'><BR><FONT COLOR='#1F4D1C'>Добавить слияние</FONT></TH></TR>\n";
    echo "<TR>\n";
    echo "<TD align='center'>&nbsp;</TD>\n";
    echo "<TD align='center'>\n";
        echo "<SELECT name='CodeOfStream'>\n";

        foreach ($DivStreams as $k => $v) {
            echo "<OPTION value='$v[CodeOfStream]'>$v[StreamName]</OPTION>\n";
        }

        echo "</SELECT>\n";

    echo "</TD>\n";
    echo "<TD align='center'>\n";
        echo "<SELECT name='BlendStyle'>\n";

        foreach ($discip_types as $k => $v) {
            echo "<OPTION value='$v'>".GetRusName($v)."</OPTION>\n";
        }

        echo "</SELECT>\n";
    echo "</TD>\n";
    echo "<TD align='center'><INPUT 'type=text' name='DivRate' value='' size='2'></TD>\n";
    echo "</TR>\n";
}
else 
echo "<TR><TH colspan='4' align='center' ><FONT COLOR='#1F4D1C'>Не найдено потоков соответствующих дисциплине плана</FONT></TH></TR>";
?>

</TABLE>
</td>
</tr>
</table>
<BR>
<TABLE BORDER=0 ALIGN=CENTER>
<TR>
<TD align="center"><INPUT TYPE='SUBMIT' NAME='Edit' VALUE='Внести изменения'></TD>
<TD align="center"><INPUT TYPE='RESET' NAME='reset' VALUE='Отменить изменения'></TD>
</TR>

</TABLE>
<?php
    echo "<input type='hidden' name='CodeOfDiscipline' value='$discipline[CodeOfDiscipline]'>\n";
    echo "<input type='hidden' name='CodeOfDepart' value='$discipline[CodeOfDepart]'>\n";
    echo "<input type='hidden' name='updategroups' value='Y'>\n";
    echo "<input type='hidden' name='NumbOfSemestr' value='$discipline[NumbOfSemestr]'>\n";
    echo "<input type='hidden' name='plitem' value='$plitem'>\n";
?>
</form>
<?php
    include("../Display/FinishPage.php");
?>