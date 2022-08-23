<?php
    include("../PlanCalculatFunc.php");
    CreateConnection();
    include("../Display/DisplayFunc.php");

    $IsIn = FetchResult("SELECT * from cicles where CicName='$Cicle'");
    if (!empty($IsIn)) 
        FuncAlert("База данных уже содержит данную запись");

    $id = FetchResult("SELECT MAX(CodeOfCicle) from cicles");
    if (!empty($id))
        $id++;
    else
         $id=1;
    QueryExec("INSERT INTO cicles (CodeOfCicle, CicName, CicReduction) values ('$id', '$Cicle', '$CicRed')");

    mysql_close($Connection);

    if ($shift)
         FuncRedirect("NewCicle.php?sh=1");
    else
         FuncRedirect("CiclesBook.php");

?>