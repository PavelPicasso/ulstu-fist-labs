<?php
    set_time_limit(60*3);   
    
    if (empty($_GET['plan'])) {
        echo "Plan is empty";
        exit;
    }
    else
        $CodeOfPlan = intval($_GET['plan']);

    include("cfg.php");
    include("Editor/PlanCalculatFunc.php");

    CreateConnection();

    $name_trans = "Объем занятий";
    $date_trans = date("Y-m-d h:i:s");
    $time_b = getmicrotime();
    $PlanData = GetPlanInfo($CodeOfPlan);
    $time_e = getmicrotime();
    $time_all = $time_e-$time_b;
    echo "<HTML>\n";
    echo "<HEAD>\n";
    echo "<TITLE>Объем занятий ".$PlanData["PlanSpcCode"]."&nbsp;&nbsp;".$PlanData["PlnName"]."</TITLE>\n";
?>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<link rel="stylesheet" href="../CSS/Plans.css" type="text/css"></HEAD>
<BODY topmargin="1" leftmargin="1" marginheight="1" marginwidth="1">
<br>
<h1>Объем занятий</h1>
<?php
    echo "<h2>".$PlanData["PlanSpcCode"]."&nbsp;&nbsp;".$PlanData["PlanSpcName"]."<BR>".$PlanData["PlnName"]."</h2>\n";
?>
<table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center">
<tr><td cellpadding="0" cellspacing="0">
<TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=3 WIDTH='100%'>
<TR>
<th class="h2"><strong>&nbsp;</strong></td>
<th class="h2"><strong>Дисциплина</strong></td>
<th class="h2"><strong>Аудиторных<BR>часов</strong></td>
<th class="h2"><strong>Самостоятельных<BR>часов</strong></td>
<th class="h2"><strong>Всего</strong></td>
</TR>
<?php

    $time_b = getmicrotime();
    list ($FirstKurs, $LastKurs, $FirstTerm, $LastTerm) = GetPeriod($CodeOfPlan, "Y");
    $TSem = array();
    for($i=$FirstTerm; $i<=$LastTerm; $i++) 
        $TSem[$i] = TeachWeek($CodeOfPlan, $i);

    $PlanDiscips = PlanDiscips($CodeOfPlan,$TSem);  
    $time_e = getmicrotime();
    $time_all +=$time_e-$time_b; 

    foreach ($PlanDiscips["Cicles"] as $k => $v) {
        //Вывод всех циклов
        $DisStr = "<TR><TD  bgcolor='white'><B>".$v["CicReduction"]."</B></FONT></TD>\n";
        $DisStr .= "<TD bgcolor='white'><B>".$v["CicName"]."</B></FONT></TD>\n";
        $DisStr .= "<TD bgcolor='white' align='center'><B>".$v["AudH"]."</B></FONT></TD>\n";
        $DisStr .= "<TD bgcolor='white' align='center'><B>".$v["SelfH"]."</B></FONT></TD>\n";
        $DisStr .= "<TD bgcolor='white' align='center'><B>".$v["AllH"]."</B></FONT></TD>\n";
        $DisStr .= "</TR>\n";
 
        echo $DisStr;
 
        foreach ($v["UnderCicles"] as $ku => $vu) {
            if ($vu["UndCicReduction"] != "") {
 
                // Вывод строки подцикла
                $DisStr = "<TR><TD  bgcolor='white'><B>$v[CicReduction].$vu[UndCicReduction]</B></FONT></TD>\n";
                $DisStr .= "<TD bgcolor='white'><B>".$vu["UndCicName"]."</B></FONT></TD>\n";
                $DisStr .= "<TD bgcolor='white' align='center'><B>".$vu["AudH"]."</B></FONT></TD>\n";
                $DisStr .= "<TD bgcolor='white' align='center'><B>".$vu["SelfH"]."</B></FONT></TD>\n";
                $DisStr .= "<TD bgcolor='white' align='center'><B>".$vu["AllH"]."</B></FONT></TD>\n";
                $DisStr .= "</TR>\n";
                
                echo $DisStr;
            }
            foreach ($vu["Discips"] as $kd => $vd) {
 
                //формируем строку для вывода
                $DisStr = "<TR><TD  bgcolor='white'>$v[CicReduction]";
 
                if ($vu["UndCicReduction"] != "")
                    $DisStr .= ".$vu[UndCicReduction]";
                if ($vd["UndCicCode"] != "")
                    $DisStr .= ".$vd[UndCicCode]";
 
                $DisStr .= "</FONT></TD>\n";
 
                $DisStr .= "<TD bgcolor='white'>".$vd["DisName"]."</FONT></TD>\n";
                $DisStr .= "<TD bgcolor='white' align='center'>".$vd["AudH"]."</FONT></TD>\n";
                $DisStr .= "<TD bgcolor='white' align='center'>".$vd["SelfH"]."</FONT></TD>\n";
                $DisStr .= "<TD bgcolor='white' align='center'>".$vd["AllH"]."</FONT></TD>\n";
                $DisStr .= "</TR>\n";
 
                echo $DisStr;
            } //Вывод дисциплин*
        } //Вывод подциклов
    } //Вывод циклов
 
    //Вывод итоговых данных
    $DisStr = "<TR><TD>&nbsp;</TD>";
    $DisStr .= "<TD bgcolor='white'><B>ВСЕГО</B></FONT></TD>\n";
    $DisStr .= "<TD bgcolor='white' align='center'><B>".$PlanDiscips["AudH"]."</B></FONT></TD>\n";
    $DisStr .= "<TD bgcolor='white' align='center'><B>".$PlanDiscips["SelfH"]."</B></FONT></TD>\n";
    $DisStr .= "<TD bgcolor='white' align='center'><B>".$PlanDiscips["AllH"]."</B></FONT></TD>\n";
    $DisStr .= "</TR>\n";
    echo $DisStr;
    $id_sess = session_id();
    mysql_query("Insert into logs (name_trans, id_sess, date_trans, time_trans) values ('$name_trans', '$id_sess', '$date_trans', '$time_all')")
               or die("Unable to execute query:".mysql_errno().": ".mysql_error());
    mysql_close($Connection);
?>
</TABLE></TD>
</TR>
</TABLE>
</BODY>
</HTML>