<?php
   include("../Display/DisplayFunc.php");
    include("../PlanCalculatFunc.php");
    $plan = $_POST["plan"];
    $PlnName = $_POST["PlnName"];
    $TchForm = $_POST["TchForm"];
   if (empty($plan) || empty($PlnName) || empty($TchForm)){
       FuncRedirect("ChoiseP.php");
   }

   CreateConnection();

   mysql_query("update plans set PlnName='$PlnName', TeachForm='$TchForm' where CodeOfPlan='$plan'")
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");


   mysql_close($Connection);
   FuncRedirect("PlanHEd.php?plan=".$plan);
?>