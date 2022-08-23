<?php
    include("../PlanCalculatFunc.php");
    CreateConnection();
   
    if (!empty($del)) {
        CreateConnection();
        foreach ($del as $k) 
            QueryExec("delete from streams where CodeOfStream='$k'");
         mysql_close($Connection);
    }

    FuncRedirect("GroupsBook.php");
    mysql_close($Connection);
?>