<?php
    include("../PlanCalculatFunc.php");
    if (!empty($del)) {
        CreateConnection();
        $undelited=0;
        foreach ($del as $k) {
            if (!(FetchResult("SELECT COUNT(CodeOfDepart) from department where CodeOfFaculty=$k")
            || FetchResult("SELECT COUNT(CodeOfDirect) from directions where CodeOfFaculty=$k")
            || FetchResult("SELECT COUNT(CodeOfSpecial) from specials where CodeOfFaculty=$k")
            || FetchResult("SELECT COUNT(CodeOfSpecialization) from specializations where CodeOfFaculty=$k")))
                QueryExec("delete from faculty where CodeOfFaculty='$k'");
            else
                $undelited=1;
         }
         mysql_close($Connection);
         if ($undelited)
              FuncRedirect("alert.html");
    }
    FuncRedirect("ReloadCPnl.html");
?>
