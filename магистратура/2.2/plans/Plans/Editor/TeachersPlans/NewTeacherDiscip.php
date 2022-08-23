<?php
session_start();
if (!($_SESSION["status"] == 0)){
    Header ("Location: ../Unreg.html");
    exit;
}
?>
<HTML>
<HEAD>
    <TITLE>Выбор новых дисциплин преподавателя</TITLE>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
    <link rel="stylesheet" href="../../../CSS/PlansEditor.css" type="text/css">
</HEAD>

<BODY topmargin="1" leftmargin="5" marginheight="1" marginwidth="5">
<FORM METHOD='post' NAME='depform' ACTION='DoNewTchDis.php'>
    <em class='h1'><center>Выбор новых дисциплин преподавателя</center></em>
    <?php
    include("../Display/DisplayFunc.php");
    $CodeOfTeacher = $_POST['teacher'];
    if (empty($CodeOfTeacher)) {
        FuncAlert("Не выбран преподаватель","../TeachersBook/TeachersBook.php");
    }
    include("../PlanCalculatFunc.php");
    CreateConnection();
    $q="select TeacherName, CodeOfDepart from teachers where CodeOfTeacher={$CodeOfTeacher}";
    $result = FetchFirstRow($q);
    $fio = $result['TeacherName'];
    $CodeOfDepart = $result['CodeOfDepart'];
    echo "<br><em class='h2'><center>".$fio."</center></em><br>";
    ?>
    <table border='0' width='100%' cellpadding='0' cellspacing='2'><tr><td height='5' bgcolor="#92a2d9"><img src="../img/line.gif" width=15 height=15 hspace=0 vspace=0 border='0' align='left'></td></tr></table><H2>Выберите дисциплины</H2><br><table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center">
        <tr><td cellpadding="0" cellspacing="0">
                <TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
                    <TR>
                        <TH>&nbsp;</TH>
                        <TH><strong>Дисциплина</strong></TH>
                        <TH><strong>План</strong></TH>
                        <TH><strong>Семестр</strong></TH>
                        <TH><strong>№ группы</strong></TH>
                    </TR>
                    <?php
                    $q = "select distinct schplanitemshoursrest.CodeOfSchPlanItem, schplanitemshoursrest.NumbOfSemestr, plans.PlnName, plans.CodeOfPlan, ".
                        "disciplins.DisName, disciplins.CodeOfDiscipline ".
                        "from schplanitemshoursrest, disciplins, schedplan, plans ".
                        "where (schplanitemshoursrest.LectInSem != 0 or schplanitemshoursrest.LabInSem != 0 or ".
                        "schplanitemshoursrest.PractInSem != 0 or schplanitemshoursrest.KursWork != 0 or ".
                        "schplanitemshoursrest.KursPrj != 0 or schplanitemshoursrest.Test != 0 or ".
                        "schplanitemshoursrest.Exam != 0 or schplanitemshoursrest.ControlWork != 0 or ".
                        "schplanitemshoursrest.RGR != 0 or schplanitemshoursrest.CalcW != 0 or ".
                        "schplanitemshoursrest.TestW != 0 or schplanitemshoursrest.Synopsis)".
                        " and schplanitemshoursrest.CodeOfDepart = ".$CodeOfDepart.
                        " and schedplan.CodeOfSchPlan = schplanitemshoursrest.CodeOfSchPlan ".
                        "and disciplins.CodeOfDiscipline = schedplan.CodeOfDiscipline ".
                        "and schedplan.CodeOfPlan = plans.CodeOfPlan ".
                        "and (plans.Date IS NULL OR plans.Date = '0000-00-00') ".
                        "order by disciplins.DisName";
                    $res = FetchArrays($q);

                    /*$q = "select CodeOfSchPlanItem from teachersDiscips";
                    $res = FetchArrays($q);
                    $usedDicsips = array();
                    foreach ($res as $item=>$ds) {
                        $usedDicsips[] = $ds['CodeOfSchPlanItem'];
                    }
                    $q = "select CodeOfDiscipline, DisName from disciplins where CodeOfDepart = {$CodeOfDepart} order by DisName";
                    $result = FetchArrays($q);
                    $disciplines = array();
                    foreach ($result as $item=>$discip) {
                        $q = "select distinct schplanitemshours.CodeOfSchPlanItem, schplanitemshours.NumbOfSemestr, plans.PlnName, plans.CodeOfPlan ".
                        "from schplanitemshours, schedplan, plans ".
                        "where schedplan.CodeOfDiscipline = ".$discip['CodeOfDiscipline'].
                        " and schedplan.CodeOfSchPlan = schplanitemshours.CodeOfSchPlan ".
                        "and schedplan.CodeOfPlan = plans.CodeOfPlan ".
                        "and (plans.Date IS NULL OR plans.Date = '0000-00-00')";
                        $res = FetchArrays($q);
                        foreach ($res as $temp=>$schItem) {
                            if (!in_array($schItem['CodeOfSchPlanItem'], $usedDicsips, true)) {
                                $disciplines[] = array(
                                    'CodeOfDiscip' => $discip['CodeOfDiscipline'],
                                    'DisName' => $discip['DisName'],
                                    'CodeOfSchPlanItem' => $schItem['CodeOfSchPlanItem'],
                                    'NumbOfSemestr' => $schItem['NumbOfSemestr'],
                                    'CodeOfPlan' => $schItem['CodeOfPlan'],
                                    'PlnName' => $schItem['PlnName']
                                );
                            }
                        }
                    }*/

                    //foreach ($disciplines as $dis){
                    foreach ($res as $dis) {
                        $CodeOfSchPlanItem = $dis['CodeOfSchPlanItem'];
                        $DisName = $dis['DisName'];
                        $CodeOfPlan = $dis['CodeOfPlan'];
                        $PlnName = $dis['PlnName'];
                        $GlobalSem = $dis['NumbOfSemestr'];
                        if ($GlobalSem % 2 == 0) {
                            $Semestr = 2;
                        } else {
                            $Semestr = 1;
                        }

                        /*$qstream = "select distinct streams.StreamName ".
                            "from streams, plans ".
                            "where plans.CodeOfPlan=streams.CodeOfPlan ".
                            "and plans.CodeOfPlan=$CodeOfPlan";*/
                        $qstream = "select distinct streams.StreamName ".
                            "from streams ".
                            "where streams.CodeOfPlan=$CodeOfPlan";
                        $Streams = FetchArrays($qstream);

                        echo "<TR>\n";
                        echo "<TD align='center'><INPUT TYPE='CHECKBOX' NAME='insert[]' VALUE='".$CodeOfSchPlanItem."'></TD>\n";
                        echo "<TD align='left' style='padding-left:3px'><em class='h2'>".$DisName."</em></TD>\n";
                        echo "<TD align='left' style='padding-left:3px'><em class='h2'>".$PlnName."</em></TD>\n";
                        echo "<TD align='center'><em class='h2'>".$Semestr."</em></TD>\n";
                        $GroopsAll = "";
                        foreach ($Streams as $ks =>$vs) {
                            $StrName = $vs["StreamName"];		//Поток
                            $kurs = $GlobalSem / 2;
                            if ($Semestr == 1) {
                                $kurs += 0.5;
                            }
                            $Groop = $StrName."-".$kurs;
                            if (strcmp($GroopsAll,"")!=0) {
                                $GroopsAll .= "<br>";
                            }
                            $GroopsAll .= $Groop;
                        }
                        if (strcmp($GroopsAll,"")==0) {
                            $GroopsAll = "не указано";
                        }
                        echo "<TD align='left' style='padding-left:3px'><em class='h2'>".$GroopsAll."</em></TD>\n";
                        echo "</TR>\n";
                    }
                    mysql_close($Connection);
                    ?>
                </TABLE>
            </TD></TR></TABLE>
    <BR><br>
        <CENTER>
            <INPUT TYPE='SUBMIT' NAME='OK' VALUE='Добавить выбранные дисциплины'></INPUT>
            <input type='hidden' name='teacher' value='<?=$CodeOfTeacher?>'>
        </CENTER>
</FORM>
<HR>
</BODY>
</HTML>
