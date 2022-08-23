<?php
    include("../PlanCalculatFunc.php");
    if (!empty($del)) {
        CreateConnection();
        $undelited=0;
        foreach ($del as $k) {
            if (!(FetchResult("SELECT COUNT(CodeOfSchPlan) from schedplan where CodeOfDepart='$k'")
            || FetchResult("SELECT COUNT(CodeOfDirect) from directions where CodeOfDepart='$k'")
            || FetchResult("SELECT COUNT(CodeOfSpecial) from specials where CodeOfDepart='$k'")
            || FetchResult("SELECT COUNT(CodeOfSpecialization) from specializations where CodeOfDepart='$k'")))
                QueryExec("delete from department where CodeOfDepart='$k'");
            else
                $undelited=1;
         }
         mysql_close($Connection);
         if ($undelited)
              FuncRedirect("alert.html");
    }
    FuncRedirect("DepartBook.php");
?>
