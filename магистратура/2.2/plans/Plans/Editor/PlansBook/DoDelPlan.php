<?php
    include("../Display/DisplayFunc.php");
    include("../PlanCalculatFunc.php");
 
    CreateConnection();
    
    $plan = $_POST['plan'];
    if (!empty($plan)) {
        $CodeOfPlan = intval($plan);

        mysql_query("DELETE FROM plans WHERE CodeOfPlan='$CodeOfPlan'")
            or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");

        mysql_query("DELETE FROM schedules WHERE CodeOfPlan='$CodeOfPlan'")
            or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");

 
        $SchedPlans = FetchArrays("SELECT CodeOfSchPlan FROM schedplan where CodeOfPlan='$CodeOfPlan'");

        foreach ($SchedPlans as $k => $v) {
            mysql_query("DELETE FROM schplanitems WHERE CodeOfSchPlan='$v[CodeOfSchPlan]'")
                or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
        }

        mysql_query("DELETE FROM schedplan WHERE CodeOfPlan='$CodeOfPlan'")
            or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");

        mysql_query("DELETE FROM streams WHERE CodeOfPlan='$CodeOfPlan'")
            or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");

    }

    FuncRedirect("ChoiseP.php");
?>