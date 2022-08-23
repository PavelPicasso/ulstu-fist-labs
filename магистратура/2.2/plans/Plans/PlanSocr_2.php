<?php
    set_time_limit(60*5);   
    
    if (empty($plan)) {
        echo "Plan is empty";
        exit;
    }
    else
        $CodeOfPlan = intval($plan);

    include("cfg.php");
    include("Editor/PlanCalculatFunc.php");

    CreateConnection();
    $name_trans = "Учебный план";
    $date_trans = date("Y-m-d h:i:s");
    $time_b = getmicrotime(); 
    $PlanData = GetPlanInfo($CodeOfPlan);
    $time_e = getmicrotime();
    $time_all = $time_e-$time_b;
    echo "<HTML>\n";
    echo "<HEAD>\n";
    echo "<TITLE>".$PlanData["PlanSpcCode"]."&nbsp;&nbsp;".$PlanData["PlnName"]."</TITLE>\n";
?>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<link rel="stylesheet" href="/asuup/css/Plans.css" type="text/css"></HEAD><BODY topmargin="1" leftmargin="1" marginheight="1" marginwidth="1">
<br><TABLE border=0 width="90%" align="center"><TR><TD align="center" width="10%">&nbsp;</TD><TD align="center" width="80%"><em class="h1">УЧЕБНЫЙ ПЛАН (сокращенная форма)</em></TD>
<TD align="center" width="10%"><a  href="/Plans/help/planFull.html" target="help" onClick='window.open("","help","menubar=no,scrollbars=yes,height=350,width=450")'><img src="/asuup/Plans/img/help.gif" width=28 height=28 hspace=0 vspace=0 border=0 align="right" alt="Справка"></a></TD></TR></TABLE><TABLE border=0 width="100%">
<TR>
<?php
    echo "<TD width='80%'>&nbsp;&nbsp;&nbsp;<strong>".$PlanData["PlanSpcCode"]."&nbsp;&nbsp;".$PlanData["PlnName"]."</strong></TD>\n";
    echo "<TD><em>Утвержден:&nbsp;&nbsp;<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></strong></TD>\n";
?>
</TR>
<TR>
<?php     
    $st = "лет";
    if ($PlanData["YearCount"] < 4){$st = "года";}
    if ($PlanData["YearCount"] == 1){$st = "год";}
    echo "<TD>&nbsp;&nbsp;&nbsp;<em>Академическая степень:&nbsp;&nbsp;&nbsp;".$PlanData["DegreeName"]."</em></TD>\n";
    echo "<TD><em>Срок обучения:&nbsp;&nbsp;&nbsp;".$PlanData["YearCount"]."&nbsp;".$st."</em></TD>\n";
?>        
</TR>
</TABLE>
<TABLE  class="ramka" border="0" cellpadding="0" cellspacing="0" width="90%">
<TR><TD cellpadding="0" cellspacing="0"  bgcolor="0040A0">
    <TABLE class="table" BORDER="0" cellpadding="1" cellspacing="1" width="100%">
    <TR>
        <TH class="noname" ROWSPAN="2"  bgcolor="white"><FONT SIZE=-1>Курс</FONT></TH>
        <TH class="noname" ROWSPAN="2"  bgcolor="white" align="center" width="8%"><FONT SIZE=-1>Семестр</FONT></TH>
        <TH class="noname" ROWSPAN="2"  bgcolor="white" align="center"><FONT SIZE=-1>Наименование дисциплины</FONT></TH>
        <TH class="noname" COLSPAN="4"  bgcolor="white" align="center"><FONT SIZE=-1>Учебная нагрузка</FONT></TH>
        <TH class="noname" COLSPAN="3"  bgcolor="white" align="center"><FONT SIZE=-1>Форма отчетности</FONT></TH>
        <TH class="noname" ROWSPAN="2"  bgcolor="white" align="center" width="7%"><FONT SIZE=-1>Кафедра</FONT></TH>
    </TR>
    <TR>
        <TH class="noname" bgcolor="white" width="4%"><FONT SIZE=-2>Всего</FONT></TH>
        <TH class="noname" bgcolor="white" width="4%"><FONT SIZE=-2>Лекц.</FONT></TH>
        <TH class="noname" bgcolor="white" width="4%"><FONT SIZE=-2>Лаб раб.</FONT></TH>
        <TH class="noname" bgcolor="white" width="4%"><FONT SIZE=-2>Прак зан.</FONT></TH>
        <TH class="noname" bgcolor="white" width="4%"><FONT SIZE=-2>Курс пр.</FONT></TH>
        <TH class="noname" bgcolor="white" width="4%"><FONT SIZE=-2>Курс раб.</FONT></TH>
        <TH class="noname" bgcolor="white" width="6%"><FONT SIZE=-2>Зачет/Экзамен</FONT></TH>
    </TR>
<?php
    $time_b = getmicrotime();
    list ($FirstKurs, $LastKurs, $FirstTerm, $LastTerm) = GetPeriod($CodeOfPlan, "Y");
    $time_e = getmicrotime();
    $time_all += $time_e-$time_b;
    $YearCount = $LastKurs - $FirstKurs + 1;

    $TSem = array();
    for($i=$FirstTerm; $i<=$LastTerm; $i++)  {
        $time_b = microtime();
        $TSem[$i] = TeachWeek($CodeOfPlan, $i);
        $time_e = microtime();
        $time_all += $time_e-$time_b;
    }
?>
    <TR>
        <TD  colspan="11" bgcolor='white'><IMG src='img/Oboi2.jpg' height='1' width='1' border='0'></TD>
    </TR>    
<?php
    $FirstYear=1;
    if ($YearCount==2) {$FirstYear=5;}
    $LastYear = $FirstYear+$YearCount;
    for($i=$FirstYear; $i<$LastYear; $i++){
       $sem1 = 2*$i-1;
       $sem2 = 2*$i;
       $query="SELECT NumbOfSemestr, DisName, LectInW, LabInW, PractInW, KursWork, KursPrj, Test, Exam, Reduction FROM schedplan as sch"
                ." left join schplanitems as schi on sch.CodeOfSchPlan=schi.CodeOfSchPlan left join disciplins as dis on dis.CodeOfDiscipline=sch.CodeOfDiscipline"
                ." left join department as dep on dep.CodeOfDepart=sch.CodeOfDepart where CodeOfPlan=".$CodeOfPlan." and NumbOfSemestr in (".$sem1.",".$sem2.")"
                ." order by NumbOfSemestr, DisName";
       $result = FetchArrays($query);
       $n= count($result);
       $DisStrFirst = "<TR><TD rowspan='".$n."' bgcolor='white' align='center'><FONT SIZE='-1'>".$i."</FONT></TD>\n";
       echo $DisStrFirst;

       $DisStr = "";  
       foreach ($result as $k => $v){
            $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'>осенний</FONT></TD>\n";
            if ($v["NumbOfSemestr"]==$sem2) $DisStr = "<TD bgcolor='white' align='center'><FONT SIZE='-1'>весенний</FONT></TD>\n";
            $DisStr .= "<TD bgcolor='white' align='left'><FONT SIZE='-1'><b>".$v["DisName"]."</b></FONT></TD>\n";
            $lect = $v["LectInW"]*$TSem[$v["NumbOfSemestr"]];
            $lab = $v["LabInW"]*$TSem[$v["NumbOfSemestr"]];
            $pract = $v["PractInW"]*$TSem[$v["NumbOfSemestr"]];
            $all=$lect+$lab+$pract;
            $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'>".$all."</FONT></TD>\n";
            $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'>".$lect."</FONT></TD>\n";
            $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'>".$lab."</FONT></TD>\n";
            $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'>".$pract."</FONT></TD>\n";
            if ($v["KursPrj"]==0) $tmp = " ";
            else $tmp="+";
            $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'>".$tmp."</FONT></TD>\n"; 

            if ($v["KursWork"]==0) $tmp = " ";
            else $tmp="+";
            $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'>".$tmp."</FONT></TD>\n"; 

            if ($v["Test"]==0&&$v["Exam"]!=0) $tmp = "экзамен";
            if ($v["Test"]!=0&&$v["Exam"]==0) $tmp = "зачет";
            if ($v["Test"]!=0&&$v["Exam"]!=0) $tmp = "зачет/ экзамен";
            if ($v["Test"]==0&&$v["Exam"]==0) $tmp = " ";
            $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'>".$tmp."</FONT></TD>\n"; 
            $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'>".$v["Reduction"]."</FONT></TD></TR>\n"; 
            echo $DisStr;
            $DisStr="<TR>";
       }  

    }
?>
    </TR>
    </TABLE>
    </TD>
</TR>
</TABLE>
</BR>
</BODY>
</HTML>