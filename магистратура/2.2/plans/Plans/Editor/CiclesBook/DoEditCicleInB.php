<?php
    include("../PlanCalculatFunc.php");
    CreateConnection();
    $update = array_unique(split(',',$NumOfChangeRec));

    foreach ($update as $k) 
        QueryExec("update cicles set CicName='$Cicle[$k]', CicReduction='$CicRed[$k]'  where CodeOfCicle=$k");

    FuncRedirect("CiclesBook.php");
    mysql_close($Connection);
?>