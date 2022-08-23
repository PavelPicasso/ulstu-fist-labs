<?php
include("../PlanCalculatFunc.php");
CreateConnection();

while (isset($_POST["LectNew"])) {
    if (is_int($_POST["LectNew"]) || $_POST["LectNew"] < 0) {
        break;
    }
    if ($_POST["LectNew"] == $_POST["LectFact"]) {
        break;
    }
    if ($_POST["LectNew"] < $_POST["LectFact"]) {
        $rest = $_POST["LectFact"] - $_POST["LectNew"];
        QueryExec("update schplanitemshoursrest set LectInSem = (LectInSem + {$rest}) where CodeOfSchPlanItem = {$_POST['CodeOfSchPlanItem']}");
        QueryExec("update teachersDiscips set LectInSem = (LectInSem - {$rest}) where CodeOfSchPlanItem = {$_POST['CodeOfSchPlanItem']}");
        break;
    } else {
        if ($_POST["LectRest"] == 0) {
            break;
        }
        $rest = $_POST["LectNew"] - $_POST["LectFact"];
        if ($rest > $_POST["LectRest"]) {
            $rest = $_POST["LectRest"];
        }
        QueryExec("update schplanitemshoursrest set LectInSem = (LectInSem - {$rest}) where CodeOfSchPlanItem = {$_POST['CodeOfSchPlanItem']}");
        QueryExec("update teachersDiscips set LectInSem = (LectInSem + {$rest}) where CodeOfSchPlanItem = {$_POST['CodeOfSchPlanItem']}");
        break;
    }
}
while (isset($_POST["LabNew"])) {
    if (is_int($_POST["LabNew"]) || $_POST["LabNew"] < 0) {
        break;
    }
    if ($_POST["LabNew"] == $_POST["LabFact"]) {
        break;
    }
    if ($_POST["LabNew"] < $_POST["LabFact"]) {
        $rest = $_POST["LabFact"] - $_POST["LabNew"];
        QueryExec("update schplanitemshoursrest set LabInSem = (LabInSem + {$rest}) where CodeOfSchPlanItem = {$_POST['CodeOfSchPlanItem']}");
        QueryExec("update teachersDiscips set LabInSem = (LabInSem - {$rest}) where CodeOfSchPlanItem = {$_POST['CodeOfSchPlanItem']}");
        break;
    } else {
        if ($_POST["LabRest"] == 0) {
            break;
        }
        $rest = $_POST["LabNew"] - $_POST["LabFact"];
        if ($rest > $_POST["LabRest"]) {
            $rest = $_POST["LabRest"];
        }
        QueryExec("update schplanitemshoursrest set LabInSem = (LabInSem - {$rest}) where CodeOfSchPlanItem = {$_POST['CodeOfSchPlanItem']}");
        QueryExec("update teachersDiscips set LabInSem = (LabInSem + {$rest}) where CodeOfSchPlanItem = {$_POST['CodeOfSchPlanItem']}");
        break;
    }
}
while (isset($_POST["PractNew"])) {
    if (is_int($_POST["PractNew"]) || $_POST["PractNew"] < 0) {
        break;
    }
    if ($_POST["PractNew"] == $_POST["PractFact"]) {
        break;
    }
    if ($_POST["PractNew"] < $_POST["PractFact"]) {
        $rest = $_POST["PractFact"] - $_POST["PractNew"];
        QueryExec("update schplanitemshoursrest set PractInSem = (PractInSem + {$rest}) where CodeOfSchPlanItem = {$_POST['CodeOfSchPlanItem']}");
        QueryExec("update teachersDiscips set PractInSem = (PractInSem - {$rest}) where CodeOfSchPlanItem = {$_POST['CodeOfSchPlanItem']}");
        break;
    } else {
        if ($_POST["PractRest"] == 0) {
            break;
        }
        $rest = $_POST["PractNew"] - $_POST["PractFact"];
        if ($rest > $_POST["PractRest"]) {
            $rest = $_POST["PractRest"];
        }
        QueryExec("update schplanitemshoursrest set PractInSem = (PractInSem - {$rest}) where CodeOfSchPlanItem = {$_POST['CodeOfSchPlanItem']}");
        QueryExec("update teachersDiscips set PractInSem = (PractInSem + {$rest}) where CodeOfSchPlanItem = {$_POST['CodeOfSchPlanItem']}");
        break;
    }
}
while (isset($_POST["KursWorkNew"])) {
    if (is_int($_POST["KursWorkNew"]) || $_POST["KursWorkNew"] < 0) {
        break;
    }
    if ($_POST["KursWorkNew"] == $_POST["KursWorkFact"]) {
        break;
    }
    if ($_POST["KursWorkNew"] < $_POST["KursWorkFact"]) {
        $rest = $_POST["KursWorkFact"] - $_POST["KursWorkNew"];
        QueryExec("update schplanitemshoursrest set KursWork = (KursWork + {$rest}) where CodeOfSchPlanItem = {$_POST['CodeOfSchPlanItem']}");
        QueryExec("update teachersDiscips set KursWork = (KursWork - {$rest}) where CodeOfSchPlanItem = {$_POST['CodeOfSchPlanItem']}");
        break;
    } else {
        if ($_POST["KursWorkRest"] == 0) {
            break;
        }
        $rest = $_POST["KursWorkNew"] - $_POST["KursWorkFact"];
        if ($rest > $_POST["KursWorkRest"]) {
            $rest = $_POST["KursWorkRest"];
        }
        QueryExec("update schplanitemshoursrest set KursWork = (KursWork - {$rest}) where CodeOfSchPlanItem = {$_POST['CodeOfSchPlanItem']}");
        QueryExec("update teachersDiscips set KursWork = (KursWork + {$rest}) where CodeOfSchPlanItem = {$_POST['CodeOfSchPlanItem']}");
        break;
    }
}
while (isset($_POST["KursPrjNew"])) {
    if (is_int($_POST["KursPrjNew"]) || $_POST["KursPrjNew"] < 0) {
        break;
    }
    if ($_POST["KursPrjNew"] == $_POST["KursPrjFact"]) {
        break;
    }
    if ($_POST["KursPrjNew"] < $_POST["KursPrjFact"]) {
        $rest = $_POST["KursPrjFact"] - $_POST["KursPrjNew"];
        QueryExec("update schplanitemshoursrest set KursPrj = (KursPrj + {$rest}) where CodeOfSchPlanItem = {$_POST['CodeOfSchPlanItem']}");
        QueryExec("update teachersDiscips set KursPrj = (KursPrj - {$rest}) where CodeOfSchPlanItem = {$_POST['CodeOfSchPlanItem']}");
        break;
    } else {
        if ($_POST["KursPrjRest"] == 0) {
            break;
        }
        $rest = $_POST["KursPrjNew"] - $_POST["KursPrjFact"];
        if ($rest > $_POST["KursPrjRest"]) {
            $rest = $_POST["KursPrjRest"];
        }
        QueryExec("update schplanitemshoursrest set KursPrj = (KursPrj - {$rest}) where CodeOfSchPlanItem = {$_POST['CodeOfSchPlanItem']}");
        QueryExec("update teachersDiscips set KursPrj = (KursPrj + {$rest}) where CodeOfSchPlanItem = {$_POST['CodeOfSchPlanItem']}");
        break;
    }
}
while (isset($_POST["TestNew"])) {
    if (is_int($_POST["TestNew"]) || $_POST["TestNew"] < 0) {
        break;
    }
    if ($_POST["TestNew"] == $_POST["TestFact"]) {
        break;
    }
    if ($_POST["TestNew"] < $_POST["TestFact"]) {
        $rest = $_POST["TestFact"] - $_POST["TestNew"];
        QueryExec("update schplanitemshoursrest set Test = (Test + {$rest}) where CodeOfSchPlanItem = {$_POST['CodeOfSchPlanItem']}");
        QueryExec("update teachersDiscips set Test = (Test - {$rest}) where CodeOfSchPlanItem = {$_POST['CodeOfSchPlanItem']}");
        break;
    } else {
        if ($_POST["TestRest"] == 0) {
            break;
        }
        $rest = $_POST["TestNew"] - $_POST["TestFact"];
        if ($rest > $_POST["TestRest"]) {
            $rest = $_POST["TestRest"];
        }
        QueryExec("update schplanitemshoursrest set Test = (Test - {$rest}) where CodeOfSchPlanItem = {$_POST['CodeOfSchPlanItem']}");
        QueryExec("update teachersDiscips set Test = (Test + {$rest}) where CodeOfSchPlanItem = {$_POST['CodeOfSchPlanItem']}");
        break;
    }
}
while (isset($_POST["ExamNew"])) {
    if (is_int($_POST["ExamNew"]) || $_POST["ExamNew"] < 0) {
        break;
    }
    if ($_POST["ExamNew"] == $_POST["ExamFact"]) {
        break;
    }
    if ($_POST["ExamNew"] < $_POST["ExamFact"]) {
        $rest = $_POST["ExamFact"] - $_POST["ExamNew"];
        QueryExec("update schplanitemshoursrest set Exam = (Exam + {$rest}) where CodeOfSchPlanItem = {$_POST['CodeOfSchPlanItem']}");
        QueryExec("update teachersDiscips set Exam = (Exam - {$rest}) where CodeOfSchPlanItem = {$_POST['CodeOfSchPlanItem']}");
        break;
    } else {
        if ($_POST["ExamRest"] == 0) {
            break;
        }
        $rest = $_POST["ExamNew"] - $_POST["ExamFact"];
        if ($rest > $_POST["ExamRest"]) {
            $rest = $_POST["ExamRest"];
        }
        QueryExec("update schplanitemshoursrest set Exam = (Exam - {$rest}) where CodeOfSchPlanItem = {$_POST['CodeOfSchPlanItem']}");
        QueryExec("update teachersDiscips set Exam = (Exam + {$rest}) where CodeOfSchPlanItem = {$_POST['CodeOfSchPlanItem']}");
        break;
    }
}
while (isset($_POST["SynopsisNew"])) {
    if (is_int($_POST["SynopsisNew"]) || $_POST["SynopsisNew"] < 0) {
        break;
    }
    if ($_POST["SynopsisNew"] == $_POST["SynopsisFact"]) {
        break;
    }
    if ($_POST["SynopsisNew"] < $_POST["SynopsisFact"]) {
        $rest = $_POST["SynopsisFact"] - $_POST["SynopsisNew"];
        QueryExec("update schplanitemshoursrest set Synopsis = (Synopsis + {$rest}) where CodeOfSchPlanItem = {$_POST['CodeOfSchPlanItem']}");
        QueryExec("update teachersDiscips set Synopsis = (Synopsis - {$rest}) where CodeOfSchPlanItem = {$_POST['CodeOfSchPlanItem']}");
        break;
    } else {
        if ($_POST["SynopsisRest"] == 0) {
            break;
        }
        $rest = $_POST["SynopsisNew"] - $_POST["SynopsisFact"];
        if ($rest > $_POST["SynopsisRest"]) {
            $rest = $_POST["SynopsisRest"];
        }
        QueryExec("update schplanitemshoursrest set Synopsis = (Synopsis - {$rest}) where CodeOfSchPlanItem = {$_POST['CodeOfSchPlanItem']}");
        QueryExec("update teachersDiscips set Synopsis = (Synopsis + {$rest}) where CodeOfSchPlanItem = {$_POST['CodeOfSchPlanItem']}");
        break;
    }
}
while (isset($_POST["RGRNew"])) {
    if (is_int($_POST["RGRNew"]) || $_POST["RGRNew"] < 0) {
        break;
    }
    if ($_POST["RGRNew"] == $_POST["RGRFact"]) {
        break;
    }
    if ($_POST["RGRNew"] < $_POST["RGRFact"]) {
        $rest = $_POST["RGRFact"] - $_POST["RGRNew"];
        QueryExec("update schplanitemshoursrest set RGR = (RGR + {$rest}) where CodeOfSchPlanItem = {$_POST['CodeOfSchPlanItem']}");
        QueryExec("update teachersDiscips set RGR = (RGR - {$rest}) where CodeOfSchPlanItem = {$_POST['CodeOfSchPlanItem']}");
        break;
    } else {
        if ($_POST["RGRRest"] == 0) {
            break;
        }
        $rest = $_POST["RGRNew"] - $_POST["RGRFact"];
        if ($rest > $_POST["RGRRest"]) {
            $rest = $_POST["RGRRest"];
        }
        QueryExec("update schplanitemshoursrest set RGR = (RGR - {$rest}) where CodeOfSchPlanItem = {$_POST['CodeOfSchPlanItem']}");
        QueryExec("update teachersDiscips set RGR = (RGR + {$rest}) where CodeOfSchPlanItem = {$_POST['CodeOfSchPlanItem']}");
        break;
    }
}

FuncRedirect("EditHours.php?teacher={$_POST['teacher']}&CodeOfTeachersDiscip={$_POST['CodeOfTeachersDiscip']}");
mysql_close($Connection);
?>