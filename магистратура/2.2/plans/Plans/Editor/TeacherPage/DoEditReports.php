<?php
include("../PlanCalculatFunc.php");
CreateConnection();

//var_dump($_POST);

if (isset($_POST['EditTime'])) {
    QueryExec("update reports set DeadLine='{$_POST["Deadline"]}' where CodeOfReport='{$_POST["CodeOfReport"]}'");
}

if (isset($_POST['EditMarks'])) {
    $update = array_unique(explode(',', $_POST['NumOfChangeRec']));
    $CodeOfReport = $_POST['CodeOfReport'];
    foreach ($update as $k) {
        $Mark = $_POST['Marks'][$k] + 0;
        if (empty($Mark)) {
            continue;
        }
        if (!is_int($Mark)) {
            continue;
        }
        if ($Mark < 3 || $Mark > 5) {
            continue;
        }
        $PassDate = $_POST['PassDate'][$k];
        if (empty($PassDate)) {
            $PassDate = date("Y-m-d");
        }
        $CodeOfStudentMark = FetchResult("select CodeOfStudentMark from studentmarks where CodeOfReport={$CodeOfReport} and CodeOfStudent={$k}");
        if (empty($CodeOfStudentMark)) {
            $id = FetchResult("SELECT MAX(CodeOfStudentMark) from studentmarks");
            if (!empty($id))
                $id++;
            else
                $id=1;
            QueryExec("insert into studentmarks values ('{$id}', '{$CodeOfReport}', '{$k}', '{$PassDate}', '{$Mark}')");
        } else {
            QueryExec("update studentmarks set PassDate='{$PassDate}', Mark='{$Mark}' where CodeOfStudentMark={$CodeOfStudentMark}");
        }
    }
}


mysql_close($Connection);
FuncRedirect("StudentMarks.php");
?>