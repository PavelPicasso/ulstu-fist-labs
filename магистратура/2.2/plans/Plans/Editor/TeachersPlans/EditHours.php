<?php
session_start();
if (!($_SESSION["status"] == 0)){
    Header ("Location: ../Unreg.html");
    exit;
}
?>
<HTML>
<HEAD>
    <TITLE>Нагрузка по дисциплине</TITLE>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
    <link rel="stylesheet" href="../../../CSS/PlansEditor.css" type="text/css">
    <link rel="stylesheet" href="../../../CSS/PlansEditor.css" type="text/css"><SCRIPT language="JavaScript">
        <!--
        function Deleting() { return; }
        function FillArrChenge(theForm,RecPtr) { return; }
        function RefreshRecArr(theForm) { return; }
        //-->
    </SCRIPT>

    <SCRIPT language="JavaScript">
        <!--
        var ArrRecPtr='';

        function FillArrChenge(theForm,RecPtr)
        {
            if (ArrRecPtr!="") ArrRecPtr+=',';
            ArrRecPtr+=RecPtr;
            document.fed.NumOfChangeRec.value=ArrRecPtr;
            return (true);
        }

        function RefreshRecArr(theForm)
        {
            ArrRecPtr='';
            document.fed.NumOfChangeRec.value=ArrRecPtr;
            return (true);
        }

        function Deleting() {
            if(confirm(" Отмеченные строки будут безвозвратно удалены из базы данных. Удалить их?")) {
                return ('DoDelTeacherDiscip.php');
            }
            return('TeacherDiscip.php');
        }
        // -->
    </script>
</HEAD>

<BODY topmargin="1" leftmargin="1" marginheight="1" marginwidth="1">
<?php
    include("../Display/DisplayFunc.php");
    $CodeOfTeacher = $_GET['teacher'];
    if (empty($CodeOfTeacher)) {
        FuncAlert("Не выбран преподаватель","../TeachersBook/TeachersBook.php");
    }
    $CodeOfTeachersDiscip = $_GET['CodeOfTeachersDiscip'];
    if (empty($CodeOfTeachersDiscip)) {
        FuncAlert("Не выбрана дисциплина","../TeachersBook/TeachersBook.php");
    }
?>
<form name=fed method=post action="DoEditHours.php">
    <em class='h1'><center>Нагрузка преподавателя</center></em>
    <?php
        include("../PlanCalculatFunc.php");
        CreateConnection();
        $q="select TeacherName from teachers where CodeOfTeacher={$CodeOfTeacher}";
        $fio = FetchResult($q);
        echo "<br><a href='TeacherDiscip.php?teacher=".$CodeOfTeacher."'><em class='h2'><center>".$fio."</center></em></a><br>";
        $q = "select teachersDiscips.CodeOfTeacherDiscip, disciplins.DisName, disciplins.CodeOfDiscipline, schplanitems.NumbOfSemestr, schplanitems.CodeOfSchPlanItem ".
            "from teachersDiscips, disciplins, schedplan, schplanitems ".
            "where teachersDiscips.CodeOfTeacherDiscip = {$CodeOfTeachersDiscip} ".
            "and schplanitems.CodeOfSchPlanItem = teachersDiscips.CodeOfSchPlanItem ".
            "and schedplan.CodeOfSchPlan = schplanitems.CodeOfSchPlan ".
            "and disciplins.CodeOfDiscipline = schedplan.CodeOfDiscipline ";
        $dis = FetchArrays($q);
        $dis = $dis[0];
        $disName = $dis["DisName"];
        $NumbOfSemestr = $dis["NumbOfSemestr"];

        //echo $dis["CodeOfPlan"]." ".$NumbOfSemestr;
        if ($NumbOfSemestr % 2 == 0) {
            $NumbOfSemestr = 2;
        } else {
            $NumbOfSemestr = 1;
        }
        echo "<em class='h3'><center>".$disName.": ".$NumbOfSemestr." семестр</center></em><br>";
    ?>
    <table border='0' width='100%' cellpadding='0' cellspacing='2'><tr><td height='5' bgcolor="#92a2d9"><img src="../img/line.gif" width=15 height=15 hspace=0 vspace=0 border='0' align='left'></td></tr></table><br><table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center">
        <tr><td cellpadding="0" cellspacing="0">
                <TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
                    <TR ALIGN='CENTER' VALIGN='MIDDLE'>
                        <TH style="width:200px"><strong>Вид нагрузки</strong></TH>
                        <TH style="width:100px"><strong>Часы по плану</strong></TH>
                        <TH style="width:100px"><strong>Занято</strong></TH>
                        <TH style="width:100px"><strong>Доступно</strong></TH>
                        <TH style="width:120px"><strong>Фактические часы</strong></TH>
                    </TR>
                    <?php

                    $q="select * from schplanitemshours ".
                        "where schplanitemshours.CodeOfSchPlanItem = {$dis["CodeOfSchPlanItem"]}";
                    $planItem = FetchArrays($q);
                    $planItem = $planItem[0];

                    $q="select * from schplanitemshoursrest ".
                        "where schplanitemshoursrest.CodeOfSchPlanItem = {$dis["CodeOfSchPlanItem"]}";
                    $restItem = FetchArrays($q);
                    $restItem = $restItem[0];

                    $q = "select * from teachersDiscips ".
                        "where teachersDiscips.CodeOfSchPlanItem = {$dis["CodeOfSchPlanItem"]} ".
                        "and teachersDiscips.CodeOfTeacherDiscip != {$CodeOfTeachersDiscip}";
                    $engagedItem = FetchArrays($q);
                    $engItem = array("LectInSem" => 0, "LabInSem" => 0, "PractInSem" => 0, "KursWork" => 0, "KursPrj" => 0, "Test" => 0,
                        "Exam" => 0, "ControlWork" => 0, "RGR" => 0, "CalcW" => 0, "TestW" => 0, "Synopsis" => 0);
                    foreach ($engagedItem as $item) {
                        $engItem["LectInSem"] += $item["LectInSem"];
                        $engItem["LabInSem"] += $item["LabInSem"];
                        $engItem["PractInSem"] += $item["PractInSem"];
                        $engItem["KursWork"] += $item["KursWork"];
                        $engItem["KursPrj"] += $item["KursPrj"];
                        $engItem["Test"] += $item["Test"];
                        $engItem["Exam"] += $item["Exam"];
                        $engItem["ControlWork"] += $item["ControlWork"];
                        $engItem["RGR"] += $item["RGR"];
                        $engItem["CalcW"] += $item["CalcW"];
                        $engItem["TestW"] += $item["TestW"];
                        $engItem["Synopsis"] += $item["Synopsis"];
                    }

                    $q = "select * from teachersDiscips ".
                        "where teachersDiscips.CodeOfTeacherDiscip = {$CodeOfTeachersDiscip}";
                    $factItem = FetchArrays($q);
                    $factItem = $factItem[0];
                    if ($factItem["LectInSem"] == 0 && $factItem["LabInSem"] == 0 && $factItem["PractInSem"] == 0 &&
                        $factItem["KursWork"] == 0 && $factItem["KursPrj"] == 0 && $factItem["Test"] == 0 &&
                        $factItem["Exam"] == 0 && $factItem["ControlWork"] == 0 && $factItem["RGR"] == 0 &&
                        $factItem["CalcW"] == 0 && $factItem["TestW"] == 0 && $factItem["Synopsis"] == 0) {
                        QueryExec("delete from teachersDiscips where CodeOfTeacherDiscip = {$CodeOfTeachersDiscip}");
                        FuncAlert("Дисциплина удалена","TeacherDiscip.php?teacher={$CodeOfTeacher}");
                    }

                    if ($planItem["LectInSem"] > 0) {
                        echo "<tr>";
                        echo "<TD align='left' style='padding-left:3px'><em class='h2'>Лекции</em></TD>\n";
                        echo "<TD align='left' style='padding-left:3px'><em class='h2'>".$planItem["LectInSem"]."</em></TD>\n";
                        echo "<TD align='left' style='padding-left:3px'><em class='h2'>".$engItem["LectInSem"]."</em></TD>\n";
                        echo "<TD align='left' style='padding-left:3px'><em class='h2'>".$restItem["LectInSem"]."</em></TD>\n";
                        echo "<TD align='center'><INPUT TYPE=TEXT NAME=\"LectNew\" SIZE='30' MAXLENGTH=80 VALUE='".$factItem['LectInSem']."'\n";
                        echo " onChange=\"FillArrChenge(this,$CodeOfTeachersDiscip)\"></INPUT></TD>\n";
                        echo "<input type='hidden' name='LectRest' value=".$restItem["LectInSem"].">";
                        echo "<input type='hidden' name='LectFact' value=".$factItem["LectInSem"].">";
                        echo "</tr>";
                    }
                    if ($planItem["LabInSem"] > 0) {
                        echo "<tr>";
                        echo "<TD align='left' style='padding-left:3px'><em class='h2'>Лабораторные</em></TD>\n";
                        echo "<TD align='left' style='padding-left:3px'><em class='h2'>".$planItem["LabInSem"]."</em></TD>\n";
                        echo "<TD align='left' style='padding-left:3px'><em class='h2'>".$engItem["LabInSem"]."</em></TD>\n";
                        echo "<TD align='left' style='padding-left:3px'><em class='h2'>".$restItem["LabInSem"]."</em></TD>\n";
                        echo "<TD align='center'><INPUT TYPE=TEXT NAME=\"LabNew\" SIZE='30' MAXLENGTH=80 VALUE='".$factItem['LabInSem']."'\n";
                        echo " onChange=\"FillArrChenge(this,$CodeOfTeachersDiscip)\"></INPUT></TD>\n";
                        echo "<input type='hidden' name='LabRest' value=".$restItem["LabInSem"].">";
                        echo "<input type='hidden' name='LabFact' value=".$factItem["LabInSem"].">";
                        echo "</tr>";
                    }
                    if ($planItem["PractInSem"] > 0) {
                        echo "<tr>";
                        echo "<TD align='left' style='padding-left:3px'><em class='h2'>Практики</em></TD>\n";
                        echo "<TD align='left' style='padding-left:3px'><em class='h2'>".$planItem["PractInSem"]."</em></TD>\n";
                        echo "<TD align='left' style='padding-left:3px'><em class='h2'>".$engItem["PractInSem"]."</em></TD>\n";
                        echo "<TD align='left' style='padding-left:3px'><em class='h2'>".$restItem["PractInSem"]."</em></TD>\n";
                        echo "<TD align='center'><INPUT TYPE=TEXT NAME=\"PractNew\" SIZE='30' MAXLENGTH=80 VALUE='".$factItem['PractInSem']."'\n";
                        echo " onChange=\"FillArrChenge(this,$CodeOfTeachersDiscip)\"></INPUT></TD>\n";
                        echo "<input type='hidden' name='PractRest' value=".$restItem["PractInSem"].">";
                        echo "<input type='hidden' name='PractFact' value=".$factItem["PractInSem"].">";
                        echo "</tr>";
                    }
                    if ($planItem["KursWork"] > 0) {
                        echo "<tr>";
                        echo "<TD align='left' style='padding-left:3px'><em class='h2'>Курсовая работа</em></TD>\n";
                        echo "<TD align='left' style='padding-left:3px'><em class='h2'>".$planItem["KursWork"]."</em></TD>\n";
                        echo "<TD align='left' style='padding-left:3px'><em class='h2'>".$engItem["KursWork"]."</em></TD>\n";
                        echo "<TD align='left' style='padding-left:3px'><em class='h2'>".$restItem["KursWork"]."</em></TD>\n";
                        echo "<TD align='center'><INPUT TYPE=TEXT NAME=\"KursWorkNew\" SIZE='30' MAXLENGTH=80 VALUE='".$factItem['KursWork']."'\n";
                        echo " onChange=\"FillArrChenge(this,$CodeOfTeachersDiscip)\"></INPUT></TD>\n";
                        echo "<input type='hidden' name='KursWorkRest' value=".$restItem["KursWork"].">";
                        echo "<input type='hidden' name='KursWorkFact' value=".$factItem["KursWork"].">";
                        echo "</tr>";
                    }
                    if ($planItem["KursPrj"] > 0) {
                        echo "<tr>";
                        echo "<TD align='left' style='padding-left:3px'><em class='h2'>Курсовой проект</em></TD>\n";
                        echo "<TD align='left' style='padding-left:3px'><em class='h2'>".$planItem["KursPrj"]."</em></TD>\n";
                        echo "<TD align='left' style='padding-left:3px'><em class='h2'>".$engItem["KursPrj"]."</em></TD>\n";
                        echo "<TD align='left' style='padding-left:3px'><em class='h2'>".$restItem["KursPrj"]."</em></TD>\n";
                        echo "<TD align='center'><INPUT TYPE=TEXT NAME=\"KursPrjNew\" SIZE='30' MAXLENGTH=80 VALUE='".$factItem['KursPrj']."'\n";
                        echo " onChange=\"FillArrChenge(this,$CodeOfTeachersDiscip)\"></INPUT></TD>\n";
                        echo "<input type='hidden' name='KursPrjRest' value=".$restItem["KursPrj"].">";
                        echo "<input type='hidden' name='KursPrjFact' value=".$factItem["KursPrj"].">";
                        echo "</tr>";
                    }
                    if ($planItem["Test"] > 0) {
                        echo "<tr>";
                        echo "<TD align='left' style='padding-left:3px'><em class='h2'>Зачет</em></TD>\n";
                        echo "<TD align='left' style='padding-left:3px'><em class='h2'>".$planItem["Test"]."</em></TD>\n";
                        echo "<TD align='left' style='padding-left:3px'><em class='h2'>".$engItem["Test"]."</em></TD>\n";
                        echo "<TD align='left' style='padding-left:3px'><em class='h2'>".$restItem["Test"]."</em></TD>\n";
                        echo "<TD align='center'><INPUT TYPE=TEXT NAME=\"TestNew\" SIZE='30' MAXLENGTH=80 VALUE='".$factItem['Test']."'\n";
                        echo " onChange=\"FillArrChenge(this,$CodeOfTeachersDiscip)\"></INPUT></TD>\n";
                        echo "<input type='hidden' name='TestRest' value=".$restItem["Test"].">";
                        echo "<input type='hidden' name='TestFact' value=".$factItem["Test"].">";
                        echo "</tr>";
                    }
                    if ($planItem["Exam"] > 0) {
                        echo "<tr>";
                        echo "<TD align='left' style='padding-left:3px'><em class='h2'>Экзамен</em></TD>\n";
                        echo "<TD align='left' style='padding-left:3px'><em class='h2'>".$planItem["Exam"]."</em></TD>\n";
                        echo "<TD align='left' style='padding-left:3px'><em class='h2'>".$engItem["Exam"]."</em></TD>\n";
                        echo "<TD align='left' style='padding-left:3px'><em class='h2'>".$restItem["Exam"]."</em></TD>\n";
                        echo "<TD align='center'><INPUT TYPE=TEXT NAME=\"ExamNew\" SIZE='30' MAXLENGTH=80 VALUE='".$factItem['Exam']."'\n";
                        echo " onChange=\"FillArrChenge(this,$CodeOfTeachersDiscip)\"></INPUT></TD>\n";
                        echo "<input type='hidden' name='ExamRest' value=".$restItem["Exam"].">";
                        echo "<input type='hidden' name='ExamFact' value=".$factItem["Exam"].">";
                        echo "</tr>";
                    }
                    if ($planItem["Synopsis"] > 0) {
                        echo "<tr>";
                        echo "<TD align='left' style='padding-left:3px'><em class='h2'>Реферат</em></TD>\n";
                        echo "<TD align='left' style='padding-left:3px'><em class='h2'>".$planItem["Synopsis"]."</em></TD>\n";
                        echo "<TD align='left' style='padding-left:3px'><em class='h2'>".$engItem["Synopsis"]."</em></TD>\n";
                        echo "<TD align='left' style='padding-left:3px'><em class='h2'>".$restItem["Synopsis"]."</em></TD>\n";
                        echo "<TD align='center'><INPUT TYPE=TEXT NAME=\"SynopsisNew\" SIZE='30' MAXLENGTH=80 VALUE='".$factItem['Synopsis']."'\n";
                        echo " onChange=\"FillArrChenge(this,$CodeOfTeachersDiscip)\"></INPUT></TD>\n";
                        echo "<input type='hidden' name='SynopsisRest' value=".$restItem["Synopsis"].">";
                        echo "<input type='hidden' name='SynopsisFact' value=".$factItem["Synopsis"].">";
                        echo "</tr>";
                    }
                    if ($planItem["RGR"] > 0) {
                        echo "<tr>";
                        echo "<TD align='left' style='padding-left:3px'><em class='h2'>РГР</em></TD>\n";
                        echo "<TD align='left' style='padding-left:3px'><em class='h2'>".$planItem["RGR"]."</em></TD>\n";
                        echo "<TD align='left' style='padding-left:3px'><em class='h2'>".$engItem["RGR"]."</em></TD>\n";
                        echo "<TD align='left' style='padding-left:3px'><em class='h2'>".$restItem["RGR"]."</em></TD>\n";
                        echo "<TD align='center'><INPUT TYPE=TEXT NAME=\"RGRNew\" SIZE='30' MAXLENGTH=80 VALUE='".$factItem['RGR']."'\n";
                        echo " onChange=\"FillArrChenge(this,$CodeOfTeachersDiscip)\"></INPUT></TD>\n";
                        echo "<input type='hidden' name='RGRRest' value=".$restItem["RGR"].">";
                        echo "<input type='hidden' name='RGRFact' value=".$factItem["RGR"].">";
                        echo "</tr>";
                    }
                    mysql_close($Connection);
                    ?>
                </TABLE>

            </td></tr></table><BR>
    <TABLE BORDER=0 ALIGN=CENTER>
        <TR>
            <TD><CENTER><input type=submit VALUE='Редактировать' onClick="fed.action='DoEditHours.php'">
                </CENTER></TD>
        </TR>
    </TABLE>
    </center>
    <input type='hidden' name='teacher' value='<?=$CodeOfTeacher?>'>
    <input type='hidden' name='CodeOfTeachersDiscip' value='<?=$CodeOfTeachersDiscip?>'>
    <input type='hidden' name='CodeOfSchPlanItem' value='<?=$dis["CodeOfSchPlanItem"]?>'>
</form>
<hr></body></html>