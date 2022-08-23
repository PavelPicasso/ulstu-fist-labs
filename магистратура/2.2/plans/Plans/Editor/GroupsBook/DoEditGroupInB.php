<?php
    include("../Display/DisplayFunc.php");
    include("../PlanCalculatFunc.php");
    CreateConnection();
    $update = array_unique(split(',',$NumOfChangeRec));

    $all_updated = 1;
    foreach ($update as $k) {
//        $res = FetchResult("SELECT COUNT(CodeOfStream) from streams where StreamName='$Stream[$k]' and Kurs='$Kurs[$k]' and CodeOfPlan='$plan[$k]'");
//        if (empty($res))
//		echo "update streams set StreamName='$Stream[$k]', Kurs='$Kurs[$k]', GroupCount='$GrpCount[$k]', StdCount='$StdCount[$k]',  CodeOfPlan='$plan[$k]' where CodeOfStream='$k'";
            FetchQuery("update streams set StreamName='$Stream[$k]', Kurs='$Kurs[$k]', GroupCount='$GrpCount[$k]', StdCount='$StdCount[$k]',  CodeOfPlan='$plan[$k]' where CodeOfStream='$k'");
//        else
//            $all_updated = 0;
    }

    if (!$all_updated) 
        FuncAlert("Некоторые из записей не были обнавлены, т.к. записи с данными значениями уже содержаться в базе данных","GroupsBook.php");
    else
        FuncRedirect("GroupsBook.php");
    mysql_close($Connection);
?>