<?php
    set_time_limit(60*4);   
    include("cfg.php");
    include("Editor/PlanCalculatFunc.php");
    CreateConnection();
    $name_trans = "Нагрузка";
?>
<HTML>
<HEAD>
<TITLE>Раскладка аудиторной нагрузки кафедр</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<link rel="stylesheet" href="../CSS/Plans.css" type="text/css"></HEAD>

<BODY topmargin="2" leftmargin="2" marginheight="2" marginwidth="2" >
<br><table border=0 width="90%" align="center">
<tr>
<td align="center" width="10%">&nbsp;</td>
<td align="center" width="80%"><em class="h1">Раскладка аудиторной нагрузки кафедр</em></td>
<td align="center" width="10%">
<a  href="help/nagruzka.html" target="help" onClick='window.open("\"help\"menubar=no,scrollbars=yes,height=350,width=450")'>
<img src="img/help.gif" width=28 height=28 hspace=0 vspace=0 border=0 align="right" alt="Помощь">
</a>
</td>
</tr>
</table>
<br>
<table  class='ramka' border="0" cellpadding="0" cellspacing="0" width="90%" align="center">
<tr><td cellpadding="0" cellspacing="0">
<TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
<TR>
<td VALIGN=CENTER ALIGN=CENTER rowspan='2' width='40%'><strong> Кафедра</strong></td>
<td VALIGN=CENTER ALIGN=CENTER colspan='2'  width='20%'><strong> Лекций</strong></td>
<td VALIGN=CENTER ALIGN=CENTER colspan='2'  width='20%'><strong> Лабораторных занятий</strong></td>
<td VALIGN=CENTER ALIGN=CENTER colspan='2'  width='20%'><strong> Практических занятий</strong></td>
</TR>
<tr>
<td VALIGN=CENTER ALIGN=CENTER><strong class='light'> 1 сем.</strong></td>
<td VALIGN=CENTER ALIGN=CENTER><strong class='light'> 2 сем.</strong></td>
<td VALIGN=CENTER ALIGN=CENTER><strong class='light'> 1 сем.</strong></td>
<td VALIGN=CENTER ALIGN=CENTER><strong class='light'> 2 сем.</strong></td>
<td VALIGN=CENTER ALIGN=CENTER><strong class='light'> 1 сем.</strong></td>
<td VALIGN=CENTER ALIGN=CENTER><strong class='light'> 2 сем.</strong></td>
</tr>
<?php
$date_trans = date("Y-m-d h:i:s");
$time_b = getmicrotime();
$Departs = FetchArrays("select CodeOfDepart, DepName  from department order by DepName");
$TeachWeeks = array();
$AudGlobSumms = array();
foreach ($Departs as $k=>$v) {

    $AudSumms = array("Lect"=>array(0,0), "Lab"=>array(0,0), "Pract"=>array(0,0));
    echo "<tr>\n";
    echo "<td  align='left'>&nbsp;$v[DepName]</td>\n";
    // Подсчет за два семестра
    for ($i = 0; $i<=1;$i++) {
        $Discips = FetchArrays("select SUM(LectInW) as SummLect, SUM(LabInW) as SummLab, 
        SUM(PractInW) as SummPract, schedplan.CodeOfPlan, NumbOfSemestr, 
        GroupCount, CodeOfStream from plans left join schedplan on plans.CodeOfPlan=schedplan.CodeOfPlan
                left join schplanitems on schedplan.CodeOfSchPlan=schplanitems.CodeOfSchPlan 
        left join streams on plans.CodeOfPlan=streams.CodeOfPlan 
        where schedplan.CodeOfDepart= '$v[CodeOfDepart]' 
                and schplanitems.NumbOfSemestr=(streams.Kurs*2-1+$i) and (plans.FixDate is NULL or plans.FixDate='0000-00-00') 
                //and schplanitems.NumbOfSemestr=(streams.Kurs*2-1+$i) and (plans.DateArchive is NULL or plans.DateArchive='0000-00-00') 
                group by schedplan.CodeOfPlan, NumbOfSemestr, CodeOfStream"); 
        $SummAud = 0;

        foreach ($Discips as $kd=>$vd) {
             if (empty ($TeachWeeks[$vd["CodeOfPlan"]][$vd["NumbOfSemestr"]]))
                  $TeachWeeks[$vd["CodeOfPlan"]][$vd["NumbOfSemestr"]] = TeachWeek($vd["CodeOfPlan"], $vd["NumbOfSemestr"]);
             $AudSumms["Lect"][$i] += $TeachWeeks[$vd["CodeOfPlan"]][$vd["NumbOfSemestr"]] * $vd["SummLect"]  *$vd["GroupCount"]; 
             $AudSumms["Lab"][$i] += $TeachWeeks[$vd["CodeOfPlan"]][$vd["NumbOfSemestr"]] * $vd["SummLab"]*2 *$vd["GroupCount"];
             $AudSumms["Pract"][$i] += $TeachWeeks[$vd["CodeOfPlan"]][$vd["NumbOfSemestr"]] * $vd["SummPract"] *$vd["GroupCount"];
        }
    }
    echo "<td  align='right'>".$AudSumms["Lect"][0]."&nbsp;</td>\n";
    echo "<td  align='right'>".$AudSumms["Lect"][1]."&nbsp;</td>\n";
    echo "<td  align='right'>".$AudSumms["Lab"][0]."&nbsp;</td>\n";
    echo "<td  align='right'>".$AudSumms["Lab"][1]."&nbsp;</td>\n";
    echo "<td  align='right'>".$AudSumms["Pract"][0]."&nbsp;</td>\n";
    echo "<td  align='right'>".$AudSumms["Pract"][1]."&nbsp;</td>\n";
    echo "</tr>\n";

    $AudGlobSumms = SumHoursArrays($AudGlobSumms,$AudSumms);
}
$time_e = getmicrotime();
$time_all = $time_e-$time_b;
echo "<tr>\n";
echo "<td  align='left'>&nbsp;<B>Всего</B></td>\n";
echo "<td  align='right'><B>".$AudGlobSumms["Lect"][0]."&nbsp;</B></td>\n";
echo "<td  align='right'><B>".$AudGlobSumms["Lect"][1]."&nbsp;</B></td>\n";
echo "<td  align='right'><B>".$AudGlobSumms["Lab"][0]."&nbsp;</B></td>\n";
echo "<td  align='right'><B>".$AudGlobSumms["Lab"][1]."&nbsp;</B></td>\n";
echo "<td  align='right'><B>".$AudGlobSumms["Pract"][0]."&nbsp;</B></td>\n";
echo "<td  align='right'><B>".$AudGlobSumms["Pract"][1]."&nbsp;</B></td>\n";
echo "</tr>\n";
?>
</TABLE>
</td></tr></table>
</BODY>
</HTML>
<?php
     $id_sess = session_id();
      mysql_query("Insert into logs (name_trans, id_sess, date_trans, time_trans) values ('$name_trans', '$id_sess', '$date_trans', '$time_all')")
               or die("Unable to execute query:".mysql_errno().": ".mysql_error());
    mysql_close($Connection);
?>