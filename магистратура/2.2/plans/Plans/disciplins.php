<?php
    set_time_limit(60*4);
    include("cfg.php");
    include("Editor/PlanCalculatFunc.php");
    CreateConnection();
    $name_trans = "Дисциплины";
?>
<HTML>
<HEAD>
<TITLE>Общий список дисциплин</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<link rel="stylesheet" href="../CSS/Plans.css" type="text/css"></HEAD>

<BODY topmargin="2" leftmargin="2" marginheight="2" marginwidth="2" >
<br>
<h1>Список дисциплин</h1>
<table  class='ramka' border="0" cellpadding="0" cellspacing="0" width="90%" align='center'>
<tr><td cellpadding="0" cellspacing="0">
<TABLE  class='table' BORDER=0 CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
<TR VALIGN=CENTER>
<td VALIGN=CENTER ALIGN=CENTER><strong> Дисциплина</strong></td>
<td VALIGN=CENTER ALIGN=CENTER><strong> Ведущая кафедра </strong></td>
<td  VALIGN=CENTER ALIGN=CENTER><strong> Учебный план</strong></td>
</TR>
<?php
    $date_trans = date("Y-m-d h:i:s");  
    $time_b = getmicrotime();
    $Discips = FetchArrays("select distinct disciplins.DisName, disciplins.CodeOfDiscipline, 
        department.Reduction, department.CodeOfDepart 
        from plans left join schedplan on plans.CodeOfPlan=schedplan.CodeOfPlan 
        left join disciplins on schedplan.CodeOfDiscipline=disciplins.CodeOfDiscipline 
        left join department on schedplan.CodeOfDepart=department.CodeOfDepart 
		where (plans.FixDate is NULL) order by DisName, Reduction"); 
        //where (plans.DateArchive is NULL) order by DisName, Reduction"); 

    foreach ($Discips as $k =>$v) {
        echo "<tr><td  valign='top' align='left' rowspan='1'><em>&nbsp;$v[DisName]<em></td>";
        echo "<td  valign='top' align='left' rowspan='1'>&nbsp;$v[Reduction]</td>";
        echo "<td  align='left'><table  border='0' width='100%'>";

        $result = mysql_query("select distinct plans.CodeOfPlan  from schedplan, plans where schedplan.CodeOfDepart='$v[CodeOfDepart]' and schedplan.CodeOfDiscipline='$v[CodeOfDiscipline]' and plans.CodeOfPlan=schedplan.CodeOfPlan and (plans.FixDate is NULL) order by CodeOfDirect, CodeOfSpecial, CodeOfSpecialization",$Connection)
        //$result = mysql_query("select distinct plans.CodeOfPlan  from schedplan, plans where schedplan.CodeOfDepart='$v[CodeOfDepart]' and schedplan.CodeOfDiscipline='$v[CodeOfDiscipline]' and plans.CodeOfPlan=schedplan.CodeOfPlan and (plans.DateArchive is NULL) order by CodeOfDirect, CodeOfSpecial, CodeOfSpecialization",$Connection)
            or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
        while ($row = mysql_fetch_assoc($result)) {
             $PlanData = GetPlanInfo($row["CodeOfPlan"]);
             echo "<tr><td valign='top' width='17%'>&nbsp;<a href='planFull.php?if=0&plan=".$row["CodeOfPlan"]."'>$PlanData[PlanSpcCode]<a></td><td valign='top' width='83%'>&nbsp;$PlanData[PlnName]</td></tr>";
        }

        echo "</table></td></tr>";
    }
    $time_e = getmicrotime();   
?>
</TABLE></td>
</tr>
</table>
</BODY>
</HTML>
<?php
    $time_all = $time_e-$time_b;
    $id_sess = session_id();    
    mysql_query("Insert into logs (name_trans, id_sess, date_trans, time_trans) values ('$name_trans', '$id_sess', '$date_trans', '$time_all')")
               or die("Unable to execute query:".mysql_errno().": ".mysql_error());

    mysql_close($Connection);
?>
