<?php
    set_time_limit(60*3);   
    include("cfg.php");
    include("Editor/PlanCalculatFunc.php");
    CreateConnection();
    $name_trans = "Аудит";
?>
<HTML>
<HEAD>
<TITLE>Аудиторная нагрузка кафедр</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<link rel="stylesheet" href="../CSS/Plans.css" type="text/css"></HEAD>

<BODY topmargin="2" leftmargin="2" marginheight="2" marginwidth="2" >
<br>
<table border=0 width="90%" align="center">
<tr>
<td align="center" width="10%">&nbsp;</td>
<td align="center" width="80%"><em class="h1">Аудиторная нагрузка кафедр</em></td>
<td align="center" width="10%"><a  href="help/departs.html" target="help" onClick='window.open("","help","menubar=no,scrollbars=yes,height=350,width=450")'><img src="img/help.gif" width=28 height=28 hspace=0 vspace=0 border=0 align="right" alt="Помощь"></a></td>
</tr>
</table>
<br>
<table  class='ramka' border="0" cellpadding="0" cellspacing="0" width="90%" align="center">
<tr><td cellpadding="0" cellspacing="0">
<TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
<TR VALIGN=CENTER>
<TD VALIGN=CENTER ALIGN=CENTER WIDTH="40%">
<strong> Кафедра</strong>
</TD>
<TD  VALIGN=CENTER ALIGN=CENTER WIDTH="20%">
<strong> Количество читаемых дисциплин</strong>
</TD>
<TD  VALIGN=CENTER ALIGN=CENTER WIDTH="20%">
<strong> Общий объем аудиторных часов</strong>
</TD>
<TD  VALIGN=CENTER ALIGN=CENTER WIDTH="20%">
<strong> Средний объем часов на одну дисциплину</strong>
</TD>
</TR>

<?php
    $date_trans = date("Y-m-d h:i:s");
    $time_b = getmicrotime();
    $Departs = FetchArrays("select distinct department.CodeOfDepart, DepName, 
        count(distinct CodeOfDiscipline) as DiscipCount from department 
        left join schedplan on department.CodeOfDepart=schedplan.CodeOfDepart 
        left join plans on plans.CodeOfPlan=schedplan.CodeOfPlan 
        left join schplanitems on schedplan.CodeOfSchPlan=schplanitems.CodeOfSchPlan 
        left join streams on plans.CodeOfPlan=streams.CodeOfPlan 
        where (schplanitems.NumbOfSemestr=streams.Kurs*2-1 or schplanitems.NumbOfSemestr=streams.Kurs*2)
        and (plans.FixDate is NULL or plans.FixDate='0000-00-00') group by DepName, department.CodeOfDepart order by DepName ");
        //and (plans.DateArchive is NULL or plans.DateArchive='0000-00-00') group by DepName, department.CodeOfDepart order by DepName ");
    $TeachWeeks = array();
    foreach ($Departs as $k=>$v) {
        echo "<tr>\n";
        echo "<td  align='left'>&nbsp;$v[DepName]</td>\n";
        echo "<td  align='right'>$v[DiscipCount]&nbsp;</td>\n";
        $Discips = FetchArrays("select SUM(LectInW) as SummLect, SUM(LabInW) as SummLab, 
        SUM(PractInW) as SummPract, plans.CodeOfPlan, NumbOfSemestr, 
        GroupCount, CodeOfStream from plans left join schedplan on plans.CodeOfPlan=schedplan.CodeOfPlan 
        left join schplanitems on schedplan.CodeOfSchPlan=schplanitems.CodeOfSchPlan 
        left join streams on plans.CodeOfPlan=streams.CodeOfPlan 
        where schedplan.CodeOfDepart= '$v[CodeOfDepart]' 
        and (schplanitems.NumbOfSemestr=streams.Kurs*2-1 or schplanitems.NumbOfSemestr=streams.Kurs*2)
        and (plans.FixDate is NULL or plans.FixDate='0000-00-00') group by schedplan.CodeOfPlan, NumbOfSemestr, CodeOfStream");
        //and (plans.DateArchive is NULL or plans.DateArchive='0000-00-00') group by schedplan.CodeOfPlan, NumbOfSemestr, CodeOfStream");
        $SummAud = 0;
        foreach ($Discips as $kd=>$vd) {
             if (empty ($TeachWeeks[$vd["CodeOfPlan"]][$vd["NumbOfSemestr"]]))
                 $TeachWeeks[$vd["CodeOfPlan"]][$vd["NumbOfSemestr"]] = TeachWeek($vd["CodeOfPlan"], $vd["NumbOfSemestr"]);
             $SummAud +=  $TeachWeeks[$vd["CodeOfPlan"]][$vd["NumbOfSemestr"]] * ($vd["SummLect"] + $vd["SummLab"]*2 + $vd["SummPract"])*$vd["GroupCount"];
        }
        echo "<td  align='right'>$SummAud&nbsp;</td>\n";

        $average = ceil($SummAud*100 / $v["DiscipCount"])/100;
        echo "<td  align='right'>$average&nbsp;</td>\n";
        echo "</tr>\n";

    }
    $time_e = getmicrotime();
    $time_all = $time_e-$time_b;
?>

</TABLE></td>
</tr>
</table>
</BODY>
</HTML>
<?php
     $id_sess = session_id();
      mysql_query("Insert into logs (name_trans, id_sess, date_trans, time_trans) values ('$name_trans', '$id_sess', '$date_trans', '$time_all')")
               or die("Unable to execute query:".mysql_errno().": ".mysql_error());
    mysql_close($Connection);
?>