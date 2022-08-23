<?php
    include("Editor/Display/DisplayFunc.php");
    if (! empty ($_POST['depart']))
        $depart=intval($_POST['depart']);
    else 
        FuncAlert ("Ни одной кафедры не выбрано");

    include("cfg.php");
    include("Editor/PlanCalculatFunc.php");
    CreateConnection();
    $name_trans = "Дисцип_каф";
    $date_trans = date("Y-m-d h:i:s");
    $time_b = getmicrotime();
    $Depart = FetchFirstRow ("select * from department where CodeOfDepart='$depart'");
    $time_e = getmicrotime();
    $time_all = $time_e-$time_b;
?>
<HTML>
<HEAD>
<?php
    echo "<TITLE>$Depart[DepName]</TITLE>";
?>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<link rel="stylesheet" href="../CSS/Plans.css" type="text/css"></HEAD>

<BODY topmargin="2" leftmargin="2" marginheight="2" marginwidth="2" >
<br>
<?php
    echo "<h1>$Depart[DepName]</h1>";
?>
<table  class='ramka' border="0" cellpadding="0" cellspacing="0" width="90%" align='center'>
<tr><td cellpadding="0" cellspacing="0">
<TABLE  class='table' BORDER=0 CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
<TR VALIGN=CENTER>
<td VALIGN=CENTER ALIGN=CENTER>
<strong> Дисциплина</strong>
</td>
<td  VALIGN=CENTER ALIGN=CENTER>
<strong> Учебный план</strong>
</td>
</TR>
<?php
    $time_b = getmicrotime();
    $Discips = FetchArrays("select distinct disciplins.DisName, disciplins.CodeOfDiscipline  
	from disciplins, schedplan, plans where schedplan.CodeOfDepart=$depart 
	and disciplins.CodeOfDiscipline=schedplan.CodeOfDiscipline 
	and schedplan.CodeOfPlan=plans.CodeOfPlan and (plans.FixDate is NULL)
	//and schedplan.CodeOfPlan=plans.CodeOfPlan and (plans.DateArchive is NULL) 
	order by DisName"); 

    foreach ($Discips as $k =>$v) {
        echo "<tr><td  valign='top' align='left' rowspan='1'><em>&nbsp;$v[DisName]<em></td>";
        echo "<td  align='left'><table  border='0' width='100%'>";

        $result = mysql_query("select distinct plans.CodeOfPlan  
		from disciplins, schedplan, plans 
		where schedplan.CodeOfDepart=$depart 
		and disciplins.CodeOfDiscipline=schedplan.CodeOfDiscipline 
		and disciplins.CodeOfDiscipline='$v[CodeOfDiscipline]' 
		and plans.CodeOfPlan=schedplan.CodeOfPlan 
		and (plans.FixDate is NULL) order by DisName, CodeOfDirect, CodeOfSpecial, CodeOfSpecialization",$Connection)
		//and (plans.DateArchive is NULL) order by DisName, CodeOfDirect, CodeOfSpecial, CodeOfSpecialization",$Connection)
            or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
        while ($row = mysql_fetch_assoc($result)) {
             $PlanData = GetPlanInfo($row["CodeOfPlan"]);
             echo "<tr><td valign='top' width='17%'>&nbsp;<a href='planFull.php?if=0&plan=".$row["CodeOfPlan"]."'>$PlanData[PlanSpcCode]<a></td><td valign='top' width='83%'>&nbsp;$PlanData[PlnName]</td></tr>";
        }

        echo "</table></td></tr>";
    }
    $time_e = getmicrotime();
    $time_all += $time_e-$time_b;	
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
