<?php
    set_time_limit(60*5);   
    
    if (empty($_GET['plan'])) {
        echo "Plan is empty";
        exit;
    }
    else
        $CodeOfPlan = intval($_GET['plan']);
    $IF = !empty($_GET['if']);
    if ($IF==0) $tmp='(типовая форма)';
    else $tmp='(детальная форма)';

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
<link rel="stylesheet" href="../CSS/Plans.css" type="text/css"></HEAD><BODY topmargin="1" leftmargin="1" marginheight="1" marginwidth="1">
<br><TABLE border=0 width="90%" align="center"><TR><TD align="center" width="10%">&nbsp;</TD><TD align="center" width="80%"><em class="h1">УЧЕБНЫЙ ПЛАН <?=$tmp?></em></TD>
<TD align="center" width="10%"><a  href="help/planFull.html" target="help" onClick='window.open("","help","menubar=no,scrollbars=yes,height=350,width=450")'><img src="img/help.gif" width=28 height=28 hspace=0 vspace=0 border=0 align="right" alt="Справка"></a></TD></TR></TABLE><TABLE border=0 width="100%">
<TR>
<?php
    echo "<TD width='80%'>&nbsp;&nbsp;&nbsp;<strong>".$PlanData["PlanSpcCode"]."&nbsp;&nbsp;";
    if (strcmp($PlanData["DirName"],"")!=0) echo $PlanData["DirName"];
    else echo $PlanData["SpcName"];
    if (strcmp($PlanData["SpzName"],"")!=0) echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>".$PlanData["SpzName"];
    echo "</i><br><br>&nbsp;&nbsp;&nbsp;".$PlanData["PlnName"]."</strong></TD>\n";
    echo "<TD><em>Утвержден:&nbsp;&nbsp;<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></strong></TD>\n";
?>
</TR>
<TR>
<?php     
    $st = "лет";
    if ($PlanData["YearCount"] < 4){$st = "года";}
    if ($PlanData["YearCount"] == 1){$st = "год";}
    echo "<TD>&nbsp;&nbsp;&nbsp;<em>Квалификация:&nbsp;&nbsp;&nbsp;".$PlanData["DegreeName"]."</em></TD>\n";
    echo "<TD><em>Срок обучения:&nbsp;&nbsp;&nbsp;".$PlanData["YearCount"]."&nbsp;".$st."</em></TD>\n";
?>        
</TR>
</TABLE><TABLE  class="ramka" border="0" cellpadding="0" cellspacing="0">
<TR><TD cellpadding="0" cellspacing="0"  bgcolor="0040A0"><TABLE class="table" BORDER="0" cellpadding="1" cellspacing="1">
<TR>
<TH class="noname" ROWSPAN="5"  bgcolor="white">#</TH>
<TH class="noname" ROWSPAN="5"  bgcolor="white" align="center" width="100%"><FONT SIZE=-1>Название дисциплины</FONT></TH>
<TH class="noname" COLSPAN="5"  bgcolor="white" align="center" ROWSPAN="2"><FONT SIZE=-1>Распределение по семестрам</FONT></TH>
<TH class="noname" COLSPAN="4"  bgcolor="white" align="center" ROWSPAN="2"><FONT SIZE=-1>Аудиторные занятия</FONT></TH>
<TD ROWSPAN="5" WIDTH="1" bgcolor="white"></TD>
<?php
    $time_b = getmicrotime();
    list ($FirstKurs, $LastKurs, $FirstTerm, $LastTerm) = GetPeriod($CodeOfPlan, "Y");
    $time_e = getmicrotime();
    $time_all += $time_e-$time_b;
    $YearCount = $LastKurs - $FirstKurs + 1;
    echo "<TH class='noname' COLSPAN='".($YearCount*2)."' bgcolor='white' align='center' ><FONT SIZE=-1>Распределение по курсам и семестрам</FONT></TH>\n";
?>
<TD ROWSPAN="5" WIDTH="1" bgcolor="white"></TD>
<TH class="noname" ROWSPAN="5"  bgcolor="white" align="center" ><FONT SIZE=-1>Самост. занятия</FONT></TH>
<TH class="noname" ROWSPAN="5"  bgcolor="white" align="center" ><FONT SIZE=-1>Всего</FONT></TH>
<TH class="noname" ROWSPAN="5"  bgcolor="white" align="center" ><FONT SIZE=-1>% Ауд. зан.</FONT></TH>
<TH class="noname" ROWSPAN="5"  bgcolor="white" align="center" ><FONT SIZE=-1>Кафедра</FONT></TH>
</TR>
<TR>
<?php
    for($i=$FirstKurs; $i<=$LastKurs; $i++)  
        echo "<TH class='noname' COLSPAN='2' bgcolor='white' ><FONT SIZE=-1>$i Курс</FONT></TH>\n";
?>
</TR>
<TR>
<TH class="noname" ROWSPAN="3" bgcolor="white" ><FONT SIZE=-2>Экзам</FONT></TH>
<TH class="noname" ROWSPAN="3"  bgcolor="white"  bgcolor="white"><FONT SIZE=-2>Зачет</FONT></TH>
<TH class="noname" ROWSPAN="3"  bgcolor="white"><FONT SIZE=-2>Курс про.</FONT></TH>
<TH class="noname" ROWSPAN="3"  bgcolor="white"><FONT SIZE=-2>Курс раб.</FONT></TH>
<TH class="noname" ROWSPAN="3"  bgcolor="white" align="center" ><FONT SIZE=-1>РГР</FONT></TH>
<TH class="noname" ROWSPAN="3"  bgcolor="white"><FONT SIZE=-2>Всего</FONT></TH>
<TH class="noname" ROWSPAN="3"  bgcolor="white"><FONT SIZE=-2>Лекц.</FONT></TH>
<TH class="noname" ROWSPAN="3"  bgcolor="white"><FONT SIZE=-2>Лаб раб.</FONT></TH>
<TH class="noname" ROWSPAN="3"  bgcolor="white"><FONT SIZE=-2>Прак зан.</FONT></TH>
<?php
    for($i=$FirstTerm; $i<=$LastTerm; $i++)  
        echo "<TH class='noname' bgcolor='white'><FONT SIZE=-2>$i сем.</FONT></TH>\n";
    echo "</TR>\n";
    echo "<TR>\n";
    echo "<TH class='noname' bgcolor='white'bgcolor='white'COLSPAN='".($YearCount*2)."'><FONT SIZE=-1>Количество недель</FONT></TH>\n";
    echo "</TR>\n";
    echo "<TR>\n";
    $TSem = array();
    for($i=$FirstTerm; $i<=$LastTerm; $i++)  {
        $time_b = microtime();
        $TSem[$i] = TeachWeek($CodeOfPlan, $i);
        $time_e = microtime();
        $time_all += $time_e-$time_b;
        echo "<TH class='noname' bgcolor='white'><FONT SIZE=-2>".$TSem[$i]."</FONT></TH>\n";
    }
    echo "</TR>\n";

    echo "<TR><TD  colspan='".(17+$YearCount*2)."' bgcolor='white'><IMG src='img/Oboi2.jpg' height='1' width='1' border='0'></TD></TR>";
 
    $time_b = getmicrotime();
    $PlanDiscips = PlanDiscips($CodeOfPlan,$TSem,1);
    $time_e = getmicrotime();
    $time_all += $time_e-$time_b;
    $id_sess = session_id();
    mysql_query("Insert into logs (name_trans, id_sess, date_trans, time_trans) values ('$name_trans', '$id_sess', '$date_trans', '$time_all')")
           or die("Unable to execute query:".mysql_errno().": ".mysql_error());

 
    foreach ($PlanDiscips["Cicles"] as $k => $v) {
        //Вывод всех циклов
        $DisStr = "<TR><TD  bgcolor='white' align='center'><FONT SIZE='-1'><B>".$v["CicReduction"]."</B></FONT></TD>\n";
        $DisStr .= "<TD colspan='6' bgcolor='white'><FONT SIZE='-1'><B>".$v["CicName"]."</B></FONT></TD>\n";
 
        $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'><B>".$v["AudH"]. "</B></FONT></TD>\n";
        $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'><B>".$v["Lect"]. "</B></FONT></TD>\n";
        $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'><B>".$v["Lab"].  "</B></FONT></TD>\n";
        $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'><B>".$v["Pract"]."</B></FONT></TD>\n";
        $DisStr .= "<TD bgcolor='white' align='center'><IMG src='img/Oboi2.jpg' height='1' width='1' border='0'></TD>\n";
 
        for ($sn=$FirstTerm; $sn<=$LastTerm; $sn++)
            if ( !empty($v["HoursInW"][$sn]["LectInW"]) || !empty($v["HoursInW"][$sn]["LabInW"]) || !empty($v["HoursInW"][$sn]["PractInW"])){
                if ($IF)
                    $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'><B>".(empty($v["HoursInW"][$sn]["LectInW"])?0:$v["HoursInW"][$sn]["LectInW"])."/".(empty($v["HoursInW"][$sn]["LabInW"])?0:$v["HoursInW"][$sn]["LabInW"])."/".(empty($v["HoursInW"][$sn]["PractInW"])?0:$v["HoursInW"][$sn]["PractInW"])."</B></FONT></TD>\n";
                else
                    $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'><B>".array_sum($v["HoursInW"][$sn])."</B></FONT></TD>\n";
            } else
                    $DisStr .= "<TD bgcolor='white' align='center'>&nbsp;</TD>\n";
 
   //     echo $DisStr;
        $DisStr .= "<TD bgcolor='white' align='center'><IMG src='img/Oboi2.jpg' height='1' width='1' border='0'></TD>\n";
        $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'><B>".$v["SelfH"]."</B></FONT></TD>\n";
        $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'><B>".$v["AllH"]."</B></FONT></TD>\n";
        if ($v["AllH"] > 0) {
            $s = ($v["SelfH"]*100)/$v["AllH"];
            $s = substr("$s",0,5);
        } else
            $s = "&nbsp;";
        $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'><B>".$s."</B></FONT></TD>\n";
        $DisStr .= "<TD bgcolor='white' align='center'><IMG src='img/Oboi2.jpg' height='1' width='1' border='0'></TD>\n";
        $DisStr .= "</TR>\n";
 
        echo $DisStr;
 
        foreach ($v["UnderCicles"] as $ku => $vu) {
            if ($vu["UndCicReduction"] != "") {
 
                // Вывод строки подцикла
                $DisStr = "<TR><TD  bgcolor='white' align='center'><FONT SIZE='-2'><B>$v[CicReduction].$vu[UndCicReduction]</B></FONT></TD>\n";
                $DisStr .= "<TD colspan='6' bgcolor='white'><FONT SIZE='-2'><B>".$vu["UndCicName"]."</B></FONT></TD>\n";
 
                $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'><B>".$vu["AudH"]. "</B></FONT></TD>\n";
                $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'><B>".$vu["Lect"]. "</B></FONT></TD>\n";
                $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'><B>".$vu["Lab"].  "</B></FONT></TD>\n";
                $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'><B>".$vu["Pract"]."</B></FONT></TD>\n";
                $DisStr .= "<TD bgcolor='white' align='center'><IMG src='img/Oboi2.jpg' height='1' width='1' border='0'></TD>\n";
                
                for ($sn=$FirstTerm; $sn<=$LastTerm; $sn++)
                    if ( !empty($vu["HoursInW"][$sn]["LectInW"]) || !empty($vu["HoursInW"][$sn]["LabInW"]) || !empty($vu["HoursInW"][$sn]["PractInW"])){
                        if ($IF)
                            $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'><B>".(empty($vu["HoursInW"][$sn]["LectInW"])?0:$vu["HoursInW"][$sn]["LectInW"])."/".(empty($vu["HoursInW"][$sn]["LabInW"])?0:$vu["HoursInW"][$sn]["LabInW"])."/".(empty($vu["HoursInW"][$sn]["PractInW"])?0:$vu["HoursInW"][$sn]["PractInW"])."</B></FONT></TD>\n";
                        else
                            $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'><B>".array_sum($vu["HoursInW"][$sn])."</B></FONT></TD>\n";
                    } else
                            $DisStr .= "<TD bgcolor='white' align='center'>&nbsp;</TD>\n";
            //    echo $DisStr;
                $DisStr .= "<TD bgcolor='white' align='center'><IMG src='img/Oboi2.jpg' height='1' width='1' border='0'></TD>\n";
                $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'><B>".$vu["SelfH"]."</B></FONT></TD>\n";
                $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'><B>".$vu["AllH"]."</B></FONT></TD>\n";
                if ($vu["AllH"] > 0) {
                    $s = ($vu["SelfH"]*100)/$vu["AllH"];
                    $s = substr("$s",0,5);
                } else
                    $s = "&nbsp;";
                $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'><B>".$s."</B></FONT></TD>\n";
                $DisStr .= "<TD bgcolor='white' align='center'><IMG src='img/Oboi2.jpg' height='1' width='1' border='0'></TD>\n";
                $DisStr .= "</TR>\n";
                
                echo $DisStr;
            }
            foreach ($vu["Discips"] as $kd => $vd) {
                //формируем строку для вывода
                $DisStr = "<TR><TD  bgcolor='white' align='center'><FONT SIZE='-2'>$v[CicReduction]";
 
                if ($vu["UndCicReduction"] != "")
                    $DisStr .= ".$vu[UndCicReduction]";
                if ($vd["UndCicCode"] != "")
                    $DisStr .= ".$vd[UndCicCode]";
 
                $DisStr .= "</FONT></TD>\n";
 
                $DisStr .= "<TD bgcolor='white'><FONT SIZE='-2'>".$vd["DisName"]."</FONT></TD>\n";
                $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'>".$vd["ExamPrn"]."</FONT></TD>\n";
                $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'>".$vd["TestPrn"]."</FONT></TD>\n";
                $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'>".$vd["KursPrjPrn"]."</FONT></TD>\n";
                $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'>".$vd["KursWorkPrn"]."</FONT></TD>\n";
                if (!empty ($vd["RGRPrn"]))
                    $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'>".$vd["RGRPrn"]."</FONT></TD>\n";
                else
                    $DisStr .= "<TD bgcolor='white' align='center'>&nbsp;</TD>\n";
                $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'>".$vd["AudH"]. "</FONT></TD>\n";
                $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'>".$vd["Lect"]."</FONT></TD>\n";
                $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'>".$vd["Lab"]."</FONT></TD>\n";
                $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'>".$vd["Pract"]."</FONT></TD>\n";
                $DisStr .= "<TD bgcolor='white' align='center'><IMG src='img/Oboi2.jpg' height='1' width='1' border='0'>&nbsp;</TD>\n";
                for ($sn=$FirstTerm; $sn<=$LastTerm; $sn++)
                    if ( !empty($vd["HoursInW"][$sn]["LectInW"]) || !empty($vd["HoursInW"][$sn]["LabInW"]) || !empty($vd["HoursInW"][$sn]["PractInW"])){
                        if ($IF)
                            $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'>&nbsp;".
                                       ( empty($vd["HoursInW"][$sn]["LectInW"]) ? 0 : $vd["HoursInW"][$sn]["LectInW"] )."/".
                                       ( empty($vd["HoursInW"][$sn]["LabInW"]) ? 0 : $vd["HoursInW"][$sn]["LabInW"] )."/".
                                       ( empty($vd["HoursInW"][$sn]["PractInW"]) ? 0 : $vd["HoursInW"][$sn]["PractInW"] ) ."</FONT></TD>\n";
                        else
                            $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'>&nbsp;".array_sum($vd["HoursInW"][$sn])."</FONT></TD>\n";
                    } else
                        $DisStr .= "<TD bgcolor='white' align='center'>&nbsp;</TD>\n";

                $DisStr .= "<TD bgcolor='white' align='center'><IMG src='img/Oboi2.jpg' height='1' width='1' border='0'>&nbsp;</TD>\n";
                $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'>&nbsp;".$vd["SelfH"]."</FONT></TD>\n";
                $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'>&nbsp;".$vd["AllH"]."</FONT></TD>\n";
                if ($vd["AllH"] > 0) {
                    $s = ($vd["SelfH"]*100)/$vd["AllH"];
                    $s = substr("$s",0,5);
                } else
                    $s = "&nbsp;";
                $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'>&nbsp;".$s."</FONT></TD>\n";
                $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'>&nbsp;".$vd["Reduction"]."</FONT></TD>\n";
                $DisStr .= "</TR>\n";
 
                echo $DisStr; 
            } //Вывод дисциплин*
        } //Вывод подциклов
    } //Вывод циклов
 
 
    //Вывод итоговых данных
    $ExamStr = "<TR><TD bgcolor='white'>&nbsp;</TD>";
    $ExamStr .= "<TD  colspan='10' bgcolor='white'><FONT SIZE='-1'><B>ЧИСЛО ЭКЗАМЕНОВ ПО СЕМЕСТРАМ</B></FONT></TD>\n";
    $ExamStr .= "<TD bgcolor='white' align='center'><IMG src='img/Oboi2.jpg' height='1' width='1' border='0'></TD>\n";
    $ExamSr=0;
    for ($sn=$FirstTerm; $sn<=$LastTerm; $sn++) {
       $pl = (empty($PlanDiscips["HoursTest"][$sn]["Exam"])?0:$PlanDiscips["HoursTest"][$sn]["Exam"]);
       $ExamSr += $pl;
       $ExamStr .= "<TD  bgcolor='white' align='center'><FONT SIZE='-1'><B>$pl</B></FONT></TD>\n";
    }
    $ExamStr .= "<TD bgcolor='white' align='center'><IMG src='img/Oboi2.jpg' height='1' width='1' border='0'></TD>\n";
    $ExamStr .= "<TD bgcolor='white' align='center' colspan='4'><IMG src='img/Oboi2.jpg' height='1' width='1' border='0'></TD>\n";
    $ExamStr .= "</TR>\n";
 
    $TestStr = "<TR><TD bgcolor='white'>&nbsp;</TD>";
    $TestStr .= "<TD  colspan='10' bgcolor='white'><FONT SIZE='-1'><B>ЧИСЛО ЗАЧЕТОВ ПО СЕМЕСТРАМ</B></FONT></TD>\n";
    $TestStr .= "<TD bgcolor='white' align='center'><IMG src='img/Oboi2.jpg' height='1' width='1' border='0'></TD>\n";
    $TestSr=0;
    for ($sn=$FirstTerm; $sn<=$LastTerm; $sn++) {
       $pl = (empty($PlanDiscips["HoursTest"][$sn]["Test"])?0:$PlanDiscips["HoursTest"][$sn]["Test"]);
       $TestSr += $pl;
       $TestStr .= "<TD  bgcolor='white' align='center'><FONT SIZE='-1'><B>$pl</B></FONT></TD>\n";
    }
 
    $TestStr .= "<TD bgcolor='white' align='center'><IMG src='img/Oboi2.jpg' height='1' width='1' border='0'></TD>\n";
    $TestStr .= "<TD bgcolor='white' align='center' colspan='4'><IMG src='img/Oboi2.jpg' height='1' width='1' border='0'></TD>\n";
    $TestStr .= "</TR>\n";
 
    $KursPrjStr = "<TR><TD bgcolor='white'>&nbsp;</TD>";
    $KursPrjStr .= "<TD  colspan='10' bgcolor='white'><FONT SIZE='-1'><B>ЧИСЛО КУРСОВЫХ ПРОЕКТОВ ПО СЕМЕСТРАМ</B></FONT></TD>\n";
    $KursPrjStr .= "<TD bgcolor='white' align='center'><IMG src='img/Oboi2.jpg' height='1' width='1' border='0'></TD>\n";
    $KursPrjSr=0;
 
    for ($sn=$FirstTerm; $sn<=$LastTerm; $sn++) {
       $pl = (empty($PlanDiscips["HoursTest"][$sn]["KursPrj"])?0:$PlanDiscips["HoursTest"][$sn]["KursPrj"]);
       $KursPrjSr += $pl;
       $KursPrjStr .= "<TD  bgcolor='white' align='center'><FONT SIZE='-1'><B>$pl</B></FONT></TD>\n";
    }
 
    $KursPrjStr .= "<TD bgcolor='white' align='center'><IMG src='img/Oboi2.jpg' height='1' width='1' border='0'></TD>\n";
    $KursPrjStr .= "<TD bgcolor='white' align='center' colspan='4'><IMG src='img/Oboi2.jpg' height='1' width='1' border='0'></TD>\n";
    $KursPrjStr .= "</TR>\n";
 
    $KursWorkStr = "<TR><TD bgcolor='white'>&nbsp;</TD>";
    $KursWorkStr .= "<TD  colspan='10' bgcolor='white'><FONT SIZE='-1'><B>ЧИСЛО КУРСОВЫХ РАБОТ ПО СЕМЕСТРАМ</B></FONT></TD>\n";
    $KursWorkStr .= "<TD bgcolor='white' align='center'><IMG src='img/Oboi2.jpg' height='1' width='1' border='0'></TD>\n";
    $KursWorkSr=0;
    for ($sn=$FirstTerm; $sn<=$LastTerm; $sn++) {
       $pl = (empty($PlanDiscips["HoursTest"][$sn]["KursWork"])?0:$PlanDiscips["HoursTest"][$sn]["KursWork"]);
       $KursWorkSr += $pl;
       $KursWorkStr .= "<TD  bgcolor='white' align='center'><FONT SIZE='-1'><B>$pl</B></FONT></TD>\n";
    }
    $KursWorkStr .= "<TD bgcolor='white' align='center'><IMG src='img/Oboi2.jpg' height='1' width='1' border='0'></TD>\n";
    $KursWorkStr .= "<TD bgcolor='white' align='center' colspan='4'><IMG src='img/Oboi2.jpg' height='1' width='1' border='0'></TD>\n";
    $KursWorkStr .= "</TR>\n";
 
 
    $DisStr = "<TR><TD bgcolor='white'>&nbsp;</TD>";
    $DisStr .= "<TD bgcolor='white'><FONT SIZE='-1'><B>ВСЕГО</B></FONT></TD>\n";
    $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'><B>".$ExamSr."</B></FONT></TD>\n";
    $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'><B>".$TestSr."</B></FONT></TD>\n";
    $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'><B>".$KursPrjSr."</B></FONT></TD>\n";
    $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'><B>".$KursWorkSr."</B></FONT></TD>\n";
    if (!empty ($vd["RGRPrn"]))
        $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'><B>nbsp;</B></FONT></TD>\n";
    else
        $DisStr .= "<TD bgcolor='white' align='center'>&nbsp;</TD>\n";
 
    $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'><B>".$PlanDiscips["AudH"]."</B></FONT></TD>\n";
    $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'><B>".$PlanDiscips["Lect"]."</B></FONT></TD>\n";
    $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'><B>".$PlanDiscips["Lab"]."</B></FONT></TD>\n";
    $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'><B>".$PlanDiscips["Pract"]."</B></FONT></TD>\n";
    $DisStr .= "<TD bgcolor='white' align='center'><IMG src='img/Oboi2.jpg' height='1' width='1' border='0'></TD>\n";
    for ($sn=$FirstTerm; $sn<=$LastTerm; $sn++)
        if ( !empty($PlanDiscips["HoursInW"][$sn]["LectInW"]) || !empty($PlanDiscips["HoursInW"][$sn]["LabInW"]) || !empty($PlanDiscips["HoursInW"][$sn]["PractInW"])){
            if ($IF)
                $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'><B>&nbsp;".
                    (empty($PlanDiscips["HoursInW"][$sn]["LectInW"])?0:$PlanDiscips["HoursInW"][$sn]["LectInW"])."/".
                    (empty($PlanDiscips["HoursInW"][$sn]["LabInW"])?0:$PlanDiscips["HoursInW"][$sn]["LabInW"])."/".
                    (empty($PlanDiscips["HoursInW"][$sn]["PractInW"])?0:$PlanDiscips["HoursInW"][$sn]["PractInW"])."</B></FONT></TD>\n";
            else
                $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'><B>&nbsp;".array_sum($PlanDiscips["HoursInW"][$sn])."</B></FONT></TD>\n";
        } else
            $DisStr .= "<TD bgcolor='white' align='center'>&nbsp;</TD>\n";
 
    $DisStr .= "<TD bgcolor='white' align='center'><IMG src='img/Oboi2.jpg' height='1' width='1' border='0'></TD>\n";
    $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'><B>".$PlanDiscips["AudH"]."</B></FONT></TD>\n";
    $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'><B>".$PlanDiscips["AllH"]."</B></FONT></TD>\n";
    if ($PlanDiscips["AllH"] > 0) {
        $s = ($PlanDiscips["SelfH"]*100)/$PlanDiscips["AllH"];
        $s = substr("$s",0,5);
    } else
        $s = "&nbsp;";
    $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'><B>".$s."</B></FONT></TD>\n";
    $DisStr .= "<TD bgcolor='white' align='center'><FONT SIZE='-1'>&nbsp;</FONT></TD>\n";
    $DisStr .= "</TR>\n";
 
    echo "<TR><TD  colspan='".(17+$YearCount*2)."' bgcolor='white'><IMG src='img/Oboi2.jpg' height='1' width='1' border='0'></TD></TR>";
    echo $DisStr;
    echo "<TR><TD  colspan='".(17+$YearCount*2)."' bgcolor='white'><IMG src='img/Oboi2.jpg' height='1' width='1' border='0'></TD></TR>";
    echo $ExamStr;
    echo $TestStr;
    echo $KursPrjStr;
    echo $KursWorkStr;

//   $PlanSr = $PlanSr/($SemCount-$StartCount+1); 
    mysql_close($Connection);
?>
</TABLE></TD>
</TR>
</TABLE>
</BODY>
</HTML>