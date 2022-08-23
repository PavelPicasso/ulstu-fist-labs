<?php
    include("../PlanCalculatFunc.php");
    CreateConnection();
   
    if (!empty($del)) {
        CreateConnection();
        foreach ($del as $k) {
            QueryExec("delete from cicles where CodeOfCicle='$k'");
            QueryExec("update schedplan set CodeOfCicle=NULL where CodeOfCicle='$k'");
        }
         mysql_close($Connection);
    }

    FuncRedirect("CiclesBook.php");
    mysql_close($Connection);
?>