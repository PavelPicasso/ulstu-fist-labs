<?php
    include("../PlanCalculatFunc.php");
    CreateConnection();
    $update = array_unique(split(',',$NumOfChangeRec));

    foreach ($update as $k) 
        QueryExec("update department set  DepName='$Depart[$k]', Reduction='$Reduction[$k]',  CodeOfFaculty=$FacCode[$k], ZavDepart='$ZavDep[$k]', ZavSignature='$ZavPdp[$k]', mail='', URL='$URL[$k]'  where CodeOfDepart=$k");

    FuncRedirect("DepartBook.php");
    mysql_close($Connection);
?>