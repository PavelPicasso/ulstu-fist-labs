<?php
   include("../Display/DisplayFunc.php");
   if (empty($plan) ||empty($CodeOfDiscipline) || empty($AllH)){
       FuncAlert("Незаполнены все обязательные поля");
   }
   
   include("../PlanCalculatFunc.php");

   CreateConnection();

   $CodeOfSchPlan = FetchResult("SELECT CodeOfSchPlan FROM schedplan where COdeOfPlan='$plan' and CodeOfDiscipline='$CodeOfDiscipline'");
   if ($CodeOfSchPlan == ''){
       mysql_query("INSERT INTO schedplan (CodeOfPlan, CodeOfDiscipline, CodeOfCicle, CodeOfUndCicle, UndCicCode, AllH, ToCount) VALUES ('$plan', '$CodeOfDiscipline', '$CodeOfCicle', '$CodeOfUndCicle', '$UndCicCode', '$AllH', '$ToCount')")
          or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
       $CodeOfSchPlan = FetchResult("SELECT CodeOfSchPlan FROM schedplan where COdeOfPlan='$plan' and CodeOfDiscipline='$CodeOfDiscipline'");
   }
   else
       mysql_query("UPDATE schedplan SET CodeOfCicle='$CodeOfCicle', CodeOfUndCicle='$CodeOfUndCicle' , UndCicCode='$UndCicCode', AllH='$AllH', ToCount='$ToCount'  WHERE CodeOfSchPlan='$CodeOfSchPlan'")
          or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   if (empty ($KursWork))
      $KursWork = 0;
   if (empty ($KursPrj))
      $KursPrj = 0;
   if (empty ($Test))
      $Test = 0;
   if (empty ($Exam))
      $Exam =0;

   mysql_query("INSERT INTO schplanitems (CodeOfSchPlan, CodeOfDepart, NumbOfSemestr, LectInW, LabInW, PractInW, KursWork, KursPrj, Test, Exam, RGR, Synopsis) VALUES ('$CodeOfSchPlan', '$CodeOfDepart', '$NumbOfSemestr', '$LectInW', '$LabInW', '$PractInW', '$KursWork', '$KursPrj', '$Test', '$Exam', '$RGR', '$Synopsis')")
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");

   if ($shift)
      FuncRedirect("AddRecIPlan.php?shift=1&plan=$plan&discip=$discip&cicle=$cicle");
   else             
      FuncRedirect("PlanEd.php?plan=$plan&discip=$discip&cicle=$cicle");
?>