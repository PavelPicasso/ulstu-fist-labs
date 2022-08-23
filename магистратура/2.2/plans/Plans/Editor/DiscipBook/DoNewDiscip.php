<?php
    include("../PlanCalculatFunc.php");
    CreateConnection();
    include("../Display/DisplayFunc.php");

    $IsIn = FetchResult("SELECT * from disciplins where DisName='".$_POST['DiscipN']."'");
    if (!empty($IsIn)) 
        FuncAlert("База данных уже содержит данную запись");

    $id = FetchResult("SELECT MAX(CodeOfDiscipline) from disciplins");
    if (!empty($id))
        $id++;
    else
         $id=1;
    QueryExec("INSERT INTO disciplins (CodeOfDiscipline,DisName,DisReduction,CodeOfDepart,NumbOfStandart) values ('$id', '".$_POST['DiscipN']."', '".$_POST['Reduction']."', ".$_POST['DepartN'].", '".$_POST['standart']."')");

    mysql_close($Connection);

    if ($_POST['shift'])
         FuncRedirect("NewDiscip.php?sh=1");
    elseif (!empty($Start))
         FuncRedirect("DiscipBook.php?Start='$Start'");
    else
         FuncRedirect("DiscipBook.php");
?>