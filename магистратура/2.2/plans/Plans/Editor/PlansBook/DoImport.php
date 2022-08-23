<?php
    include("../Display/DisplayFunc.php");

    if (!empty($sections) && !empty($export) && !empty($import) && empty($imported)) {
        include("../PlanCalculatFunc.php");
        CreateConnection();
        $SchedPlans = FetchArrays("SELECT * FROM schedplan where CodeOfPlan='$import' and CodeOfCicle IN (".implode(",",$sections).")");
        foreach ($export as $ki=>$CodeOfPlan) {

            list ($FirstTerm, $LastTerm) = GetPeriod($CodeOfPlan);

            foreach ($SchedPlans as $k=>$v) {
                $newCodeOfSchPlan = FetchResult("SELECT CodeOfSchPlan FROM schedplan WHERE CodeOfPlan='$CodeOfPlan' and  CodeOfDiscipline='$v[CodeOfDiscipline]'");

                $SchedPlanItems = FetchArrays("SELECT * FROM schplanitems where CodeOfSchPlan = '$v[CodeOfSchPlan]'");

                foreach ($SchedPlanItems as $kitem=>$vitem) {
                    if (($FirstTerm <= $vitem["NumbOfSemestr"]) && ($LastTerm >= $vitem["NumbOfSemestr"])) {

	                if (!empty ($newCodeOfSchPlan)) //если дисциплина уже есть в учебном плане проверяем есть ли по ней анологичная нагрузка
                            $newCodeOfSchPlanItem = FetchResult("SELECT CodeOfSchPlanItem FROM schplanitems WHERE CodeOfSchPlan = '$newCodeOfSchPlan' and NumbOfSemestr = '$vitem[NumbOfSemestr]' and CodeOfDepart = '$vitem[CodeOfDepart]'");
                        else { //если нет, то добавляем дицсциплину

                            mysql_query("INSERT INTO schedplan (CodeOfPlan, CodeOfDiscipline, CodeOfCicle, CodeOfUndCicle, UndCicCode, AllH, ToCount) VALUES ('$CodeOfPlan', '$v[CodeOfDiscipline]', '$v[CodeOfCicle]', '$v[CodeOfUndCicle]', '$v[UndCicCode]', '$v[AllH]', '$v[ToCount]')")
                                or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
                            $newCodeOfSchPlan = FetchResult("SELECT CodeOfSchPlan FROM schedplan WHERE CodeOfPlan='$CodeOfPlan' and  CodeOfDiscipline='$v[CodeOfDiscipline]'");
                        }
    
                        if (empty ($newCodeOfSchPlanItem))
                            mysql_query("INSERT INTO schplanitems (CodeOfSchPlan, CodeOfDepart, NumbOfSemestr, AuditH, LectInW, LabInW, PractInW, KursWork, KursPrj, Test, Exam, ControlWork, RGR, CalcW, TestW, Synopsis) VALUES ('$newCodeOfSchPlan', '$vitem[CodeOfDepart]', '$vitem[NumbOfSemestr]', '$vitem[AuditH]', '$vitem[LectInW]', '$vitem[LabInW]', '$vitem[PractInW]', '$vitem[KursWork]', '$vitem[KursPrj]', '$vitem[Test]', '$vitem[Exam]', '$vitem[ControlWork]', '$vitem[RGR]', '$vitem[CalcW]', '$vitem[TestW]', '$vitem[Synopsis]')")
                                 or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
                    }

                }
            }
        }
        mysql_close($Connection);
        FuncRedirect("DoImport.php?imported=y"); 
    }
    else {
        include("../Display/StartPage.php");
        DisplayPageTitle("","Импорт дисциплин учебного плана","Импорт выполнен");
        include("../Display/FinishPage.php");
    }
?>