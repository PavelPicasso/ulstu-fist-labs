<?php
    include("../PlanCalculatFunc.php");
    CreateConnection();
    include("../Display/DisplayFunc.php");

    $IsIn = FetchResult("SELECT * from streams where StreamName='$StreamName' and Kurs=$Kurs and CodeOfPlan=$plan");
    if (!empty($IsIn)) 
        FuncAlert("База данных уже содержит данную запись");

    $id = FetchResult("SELECT MAX(CodeOfStream) from streams");
    if (!empty($id))
        $id++;
    else
         $id=1;
    QueryExec("INSERT INTO streams (CodeOfStream, StreamName, Kurs, GroupCount, StdCount, CodeOfPlan) values ('$id', '$StreamName', '$Kurs', '$GrpCount', '$StdCount', '$plan')");

    mysql_close($Connection);

    if ($shift)
         FuncRedirect("NewGroup.php?sh=1");
    else
         FuncRedirect("GroupsBook.php");

?>