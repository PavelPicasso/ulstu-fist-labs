<?php

    include("../Display/DisplayFunc.php");

    if (!empty($kurs) && !empty($export) && !empty($import) && empty($imported)) {
       include("../PlanCalculatFunc.php");
         CreateConnection();

        foreach ($kurs as $k=>$v) {
            $KursSchedule = FetchFirstRow("select * from schedules where CodeOfPlan='$import' and KursNumb='$v'");
            if (! empty($KursSchedule)) {
                $qStr="UPDATE schedules set "; 
                for ($i=1; $i<=$NumPeriod*2; $i++) {	
                    $pInd="Period".$i;
                    $qStr.=" $pInd='".$KursSchedule[$pInd]."',";
                    $lInd="LengthOfPeriod".$i;

                    if(empty($KursSchedule[$lInd])) 
                        $KursSchedule[$lInd] = 0;

                    $qStr.=" $lInd=".$KursSchedule[$lInd];

                    if ($i != $NumPeriod*2)
                        $qStr .= ",";
                }

                foreach ($export as $ke=>$ve) {
                    mysql_query($qStr . " where CodeOfPlan='$ve' and KursNumb='$v'")
                        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
                }

            }
        }
        FuncRedirect("DoImportGUP.php?imported=y"); 
        mysql_close($Connection);
    }
    else {
        include("../Display/StartPage.php");
        DisplayPageTitle("","Импорт графика учебного процесса","Импорт выполнен");
        include("../Display/FinishPage.php");
    }
?>