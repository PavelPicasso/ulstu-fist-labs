<?php
   include("../Display/DisplayFunc.php");
   include("../PlanCalculatFunc.php");

   CreateConnection();

   if (! empty($DelSchPlan))
   foreach ($DelSchPlan as $k) {
       mysql_query("DELETE FROM schedplan WHERE CodeOfSchPlan='$k'")
          or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
       mysql_query("DELETE FROM schplanitems WHERE CodeOfSchPlan='$k'")
          or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   }
   if (! empty($DelSchPlanItem))
   foreach ($DelSchPlanItem as $k) {
       $CodeOfSchPlan = FetchResult("SELECT CodeOfSchPlan FROM schplanitems where CodeOfSchPlanItem='$k'");
       mysql_query("DELETE FROM schplanitems WHERE CodeOfSchPlanItem='$k'")
          or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
       //Удаляем запись дисциплины если нагрузка по ней отсуствует
       $CodeOfSchPlanItem = FetchResult("SELECT COUNT(CodeOfSchPlanItem) FROM schplanitems where CodeOfSchPlan='$CodeOfSchPlan'");
       if (empty($CodeOfSchPlanItem))
          mysql_query("DELETE FROM schedplan WHERE CodeOfSchPlan='$CodeOfSchPlan'")
              or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   }
   FuncRedirect("PlanEd.php?plan=$plan&cicle=$cicle&discip=$discip");
?>