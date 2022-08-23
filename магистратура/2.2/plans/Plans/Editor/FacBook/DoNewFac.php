<?php
    include("../PlanCalculatFunc.php");
    CreateConnection();
    include("../Display/DisplayFunc.php");

    $IsIn = FetchResult("SELECT * from faculty where FacName='$FacName'");
    if (!empty($IsIn)) 
        FuncAlert("База данных уже содержит данную запись");

    $id = FetchResult("SELECT MAX(CodeOfFaculty) from faculty");
    if (!empty($id))
        $id++;
    else
         $id=1;
    QueryExec("INSERT INTO faculty (CodeOfFaculty, FacName, Reduction, Dean, DeanSignature) values ('$id', '$FacName', '$Reduction', '$Dean', '$DeanPdp')");

    mysql_close($Connection);

    if ($shift)
         FuncRedirect("NewFac.php?sh=1");
    else
         FuncRedirect("ReloadCPnl.html");
?>