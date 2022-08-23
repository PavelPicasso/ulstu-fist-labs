<?php
    include("../PlanCalculatFunc.php");
    CreateConnection();
    $update = array_unique(split(',',$NumOfChangeRec));

    foreach ($update as $k) 
        QueryExec("update faculty set FacName='$FacName[$k]', Reduction='$Reduction[$k]',  Dean='$Dean[$k]',  DeanSignature='$DeanPdp[$k]' where CodeOfFaculty=$k");

    FuncRedirect("ReloadCPnl.html");
    mysql_close($Connection);
?>