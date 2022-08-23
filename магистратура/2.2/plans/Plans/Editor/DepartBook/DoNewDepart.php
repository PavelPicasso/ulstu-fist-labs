<?php
    include("../PlanCalculatFunc.php");
    CreateConnection();
    include("../Display/DisplayFunc.php");

    $IsIn = FetchResult("SELECT * from department where DepName='$DepartN'");
    if (!empty($IsIn)) 
        FuncAlert("База данных уже содержит данную запись");

    $id = FetchResult("SELECT MAX(CodeOfDepart) from department");
    if (!empty($id))
        $id++;
    else
         $id=1;
    QueryExec("INSERT INTO department values ('$id', '$DepartN', '$Reduction', $Faculty, '', '$FIO', '$Signature', '', '$URL')");

    mysql_close($Connection);

    if ($shift)
         FuncRedirect("NewDepart.php?sh=1");
    else
         FuncRedirect("DepartBook.php");
?>
