<?php
    include("../PlanCalculatFunc.php");
    CreateConnection();
    $disciplins = array_unique(explode(",",$NumOfChangeDiscip));
    if (!empty($disciplins)) 
        foreach ($disciplins as $k=>$v) 
            if ($v) {
                $toCount = !empty($ToCount[$v]);
                mysql_query("update schedplan set CodeOfCicle='".$CodeOfCicle[$v]."', CodeOfUndCicle='".$CodeOfUndCicle[$v]."', UndCicCode='".$UndCicCode[$v]."', AllH='".$AllH[$v]."', ToCount='".$toCount."' where CodeOfSchPlan='$v'")
                    or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
            }
    $disciplinItems = array_unique(explode(",",$NumOfChangeRec));
    if (!empty($disciplinItems)) 
        foreach ($disciplinItems as $k=>$v) 
            if ($v) {
                $exam = !empty($Exam[$v]);
                $test = !empty($Test[$v]);
                $kursWork = !empty($KursWork[$v]);
                $kursPrj = !empty($KursPrj[$v]);
                mysql_query("Update schplanitems set CodeOfDepart='".$CodeOfDepart[$v]."', NumbOfSemestr='".$NumbOfSemestr[$v]."', LectSem='".$LectSem[$v]."', LabSem='".$LabSem[$v]."', PractSem='".$PractSem[$v]."', LectInW='".$LectInW[$v]."', LabInW='".$LabInW[$v]."', PractInW='".$PractInW[$v]."', KursWork='".$kursWork."', KursPrj='".$kursPrj."', Test='".$test."', Exam='".$exam."', RGR='".$RGR[$v]."', Synopsis='".$Synopsis[$v]."' where CodeOfSchPlanItem='$v'")
                    or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
            }
    mysql_close($Connection);
    Header ("Location: PlanEd.php?plan=$_POST[plan]&cicle=$_POST[cicle]&discip=$_POST[discip]");
?>