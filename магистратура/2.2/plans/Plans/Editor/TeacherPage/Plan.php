<?php
session_start();
if (!($_SESSION["status"] == 0)){
    Header ("Location: ../Unreg.html");
    exit;
}
if (!isset($_SESSION["teacher"])) {
    Header ("Location: ../Unreg.html");
    exit;
} else {
    include("../PlanCalculatFunc.php");
    CreateConnection();
    include("../Display/DisplayFunc.php");
    $CodeOfTeacher = $_SESSION["teacher"];
    $q = "select teachers.TeacherName, teachers.Mail, department.DepName from teachers, department where teachers.CodeOfTeacher = '".$CodeOfTeacher."' ".
        "and department.CodeOfDepart = teachers.CodeOfDepart";
    $teacher = FetchArrays($q);
    $teacherName = $teacher[0]["TeacherName"];
    $teacherMail = $teacher[0]["Mail"];
    $depName = $teacher[0]["DepName"];
}
?>
<HTML>
<HEAD>
    <TITLE>Индивидуальный план</TITLE>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
    <link rel="stylesheet" href="../../../CSS/PlansEditor.css" type="text/css">
</HEAD>
      
<BODY topmargin="1" leftmargin="1" marginheight="1" marginwidth="1">
<?php
    /*include("../Display/DisplayFunc.php");
    $CodeOfTeacher = $_GET['teacher'];
    if (empty($CodeOfTeacher)) {
        FuncAlert("Не выбран преподаватель","../TeachersBook/TeachersBook.php");
    }    */
?>
<form name=fed method=post action="">
    <em class='h1'><center>Индивидуальный план</center></em>
    <table border='0' width='100%' cellpadding='0' cellspacing='2'><tr><td height='5' bgcolor="#92a2d9"><img src="../img/line.gif" width=15 height=15 hspace=0 vspace=0 border='0' align='left'></td></tr></table><br><table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center">
        <tr><td cellpadding="0" cellspacing="0">
                <TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=0 CELLPADDING=10 WIDTH='100%'>
                    <TR ALIGN='CENTER' VALIGN='MIDDLE'>
                        <TD><a href="main.php">Персональные данные</a></TD>
                        <TD><em class='h2'>Индивидуальный план</em></TD>
                        <TD><a href="StudentMarks.php">Учебный процесс</a></TD>
                    </TR>
                </TABLE>
                <?php
                    $rowOfTable = "<TR ALIGN='CENTER' VALIGN='MIDDLE'>".
                                    "<TH style='width:350px'><strong>Дисциплина</strong></TH>".
                                    //"<TH style='width:60px'><strong>Семестр</strong></TH>".
                                    "<TH style='width:100px'><strong>№ группы</strong></TH>".
                                    "<TH style='width:100px'><strong>Вид нагрузки</strong></TH>".
                                    "<TH style='width:150px'><strong>Количество часов</strong></TH></TR>";

                    $tableFirstSem = $rowOfTable;
                    $tableSecondSem = $rowOfTable;
                ?>

                    <?php /*<TR ALIGN='CENTER' VALIGN='MIDDLE'>
                        <TH style="width:350px"><strong>Дисциплина</strong></TH>
                        <TH style="width:60px"><strong>Семестр</strong></TH>
                        <TH style="width:100px"><strong>№ группы</strong></TH>
                        <TH style="width:100px"><strong>Вид нагрузки</strong></TH>
                        <TH style="width:150px"><strong>Количество часов</strong></TH>
                    </TR>*/ ?>

                    <?php

                    $q="SELECT teachersDiscips.CodeOfTeacherDiscip, disciplins.DisName, disciplins.CodeOfDiscipline, ".
                        "schedplan.CodeOfSchPlan, schplanitemshours.CodeOfSchPlanItem, schplanitemshours.NumbOfSemestr, plans.CodeOfPlan, plans.PlnName ".
                        "FROM teachersDiscips, disciplins, schedplan, schplanitemshours, plans ".
                        "WHERE teachersDiscips.CodeOfTeacher = {$CodeOfTeacher} ".
                        "AND schplanitemshours.CodeOfSchPlanItem = teachersDiscips.CodeOfSchPlanItem ".
                        "AND schedplan.CodeOfSchPlan = schplanitemshours.CodeOfSchPlan ".
                        "AND disciplins.CodeOfDiscipline = schedplan.CodeOfDiscipline ".
                        "AND schedplan.CodeOfPlan = plans.CodeOfPlan ".
                        "AND (plans.Date IS NULL OR plans.Date = '0000-00-00') ".
                        "ORDER BY schplanitemshours.NumbOfSemestr, disciplins.DisName";

                    $result = FetchArrays($q);

                    foreach ($result as $row){
                        $CodeOfTeachersDiscip = $row['CodeOfTeacherDiscip'];
                        $CodeOfSchPlan = $row['CodeOfSchPlan'];
                        $CodeOfPlan = $row['CodeOfPlan'];
                        $PlnName = $row['PlnName'];
                        $DisName = $row['DisName'];
                        $GlobalSem = $row['NumbOfSemestr'];
                        if ($GlobalSem % 2 == 0) {
                            $Semestr = 2;
                        } else {
                            $Semestr = 1;
                        }

                        $qstream = "select distinct streams.StreamName from streams where streams.CodeOfPlan = ".$CodeOfPlan." order by streams.StreamName";
                        $Streams = FetchArrays($qstream);

                        $q = "select * from teachersDiscips ".
                            "where teachersDiscips.CodeOfTeacherDiscip = {$CodeOfTeachersDiscip}";
                        $factItem = FetchArrays($q);
                        $factItem = $factItem[0];

                        $GroopsAll = "";
                        foreach ($Streams as $ks =>$vs) {
                            $StrName = $vs["StreamName"];		//Поток
                            $kurs = $GlobalSem / 2;
                            if ($Semestr == 1) {
                                $kurs += 0.5;
                            }
                            $Groop = $StrName."-".$kurs;
                            if ($GroopsAll != "") {
                                $GroopsAll .= "<br>";
                            }
                            $GroopsAll .= $Groop;
                        }

                        $rowOfTable = "";

                        if ($factItem["LectInSem"] > 0) {
                            $rowOfTable.= "<TR>\n";
                            $rowOfTable.= "<TD align='left' style='padding-left:3px'><em class='h2'>".$DisName."</em></TD>\n";
                            //$rowOfTable.= "<TD align='center'><em class='h2'>".$Semestr."</em></TD>\n";
                            $rowOfTable.= "<TD align='left' style='padding-left:3px'><em class='h2'>".$GroopsAll."</em></TD>\n";
                            $rowOfTable.= "<TD align='left' style='padding-left:3px'><em class='h2'>Лекции</em></TD>\n";
                            $rowOfTable.= "<TD align='left' style='padding-left:3px'><em class='h2'>".$factItem["LectInSem"]."</em></TD>\n";
                            $rowOfTable.= "</tr>";
                        }
                        if ($factItem["LabInSem"] > 0) {
                            $rowOfTable.= "<tr>";
                            $rowOfTable.= "<TD align='left' style='padding-left:3px'><em class='h2'>".$DisName."</em></TD>\n";
                            //$rowOfTable.= "<TD align='center'><em class='h2'>".$Semestr."</em></TD>\n";
                            $rowOfTable.= "<TD align='left' style='padding-left:3px'><em class='h2'>".$GroopsAll."</em></TD>\n";
                            $rowOfTable.= "<TD align='left' style='padding-left:3px'><em class='h2'>Лабораторные</em></TD>\n";
                            $rowOfTable.= "<TD align='left' style='padding-left:3px'><em class='h2'>".$factItem["LabInSem"]."</em></TD>\n";
                            $rowOfTable.= "</tr>";
                        }
                        if ($factItem["PractInSem"] > 0) {
                            $rowOfTable.= "<tr>";
                            $rowOfTable.= "<TD align='left' style='padding-left:3px'><em class='h2'>".$DisName."</em></TD>\n";
                            //$rowOfTable.= "<TD align='center'><em class='h2'>".$Semestr."</em></TD>\n";
                            $rowOfTable.= "<TD align='left' style='padding-left:3px'><em class='h2'>".$GroopsAll."</em></TD>\n";
                            $rowOfTable.= "<TD align='left' style='padding-left:3px'><em class='h2'>Практики</em></TD>\n";
                            $rowOfTable.= "<TD align='left' style='padding-left:3px'><em class='h2'>".$factItem["PractInSem"]."</em></TD>\n";
                            $rowOfTable.= "</tr>";
                        }
                        if ($factItem["KursWork"] > 0) {
                            $rowOfTable.= "<tr>";
                            $rowOfTable.= "<TD align='left' style='padding-left:3px'><em class='h2'>".$DisName."</em></TD>\n";
                            //$rowOfTable.= "<TD align='center'><em class='h2'>".$Semestr."</em></TD>\n";
                            $rowOfTable.= "<TD align='left' style='padding-left:3px'><em class='h2'>".$GroopsAll."</em></TD>\n";
                            $rowOfTable.= "<TD align='left' style='padding-left:3px'><em class='h2'>Курсовая работа</em></TD>\n";
                            $rowOfTable.= "<TD align='left' style='padding-left:3px'><em class='h2'>".$factItem["KursWork"]."</em></TD>\n";
                            $rowOfTable.= "</tr>";
                        }
                        if ($factItem["KursPrj"] > 0) {
                            $rowOfTable.= "<tr>";
                            $rowOfTable.= "<TD align='left' style='padding-left:3px'><em class='h2'>".$DisName."</em></TD>\n";
                            //$rowOfTable.= "<TD align='center'><em class='h2'>".$Semestr."</em></TD>\n";
                            $rowOfTable.= "<TD align='left' style='padding-left:3px'><em class='h2'>".$GroopsAll."</em></TD>\n";
                            $rowOfTable.= "<TD align='left' style='padding-left:3px'><em class='h2'>Курсовой проект</em></TD>\n";
                            $rowOfTable.= "<TD align='left' style='padding-left:3px'><em class='h2'>".$factItem["KursPrj"]."</em></TD>\n";
                            $rowOfTable.= "</tr>";
                        }
                        if ($factItem["Test"] > 0) {
                            $rowOfTable.= "<tr>";
                            $rowOfTable.= "<TD align='left' style='padding-left:3px'><em class='h2'>".$DisName."</em></TD>\n";
                            //$rowOfTable.= "<TD align='center'><em class='h2'>".$Semestr."</em></TD>\n";
                            $rowOfTable.= "<TD align='left' style='padding-left:3px'><em class='h2'>".$GroopsAll."</em></TD>\n";
                            $rowOfTable.= "<TD align='left' style='padding-left:3px'><em class='h2'>Зачет</em></TD>\n";
                            $rowOfTable.= "<TD align='left' style='padding-left:3px'><em class='h2'>".$factItem["Test"]."</em></TD>\n";
                            $rowOfTable.= "</tr>";
                        }
                        if ($factItem["Exam"] > 0) {
                            $rowOfTable.= "<tr>";
                            $rowOfTable.= "<TD align='left' style='padding-left:3px'><em class='h2'>".$DisName."</em></TD>\n";
                            //$rowOfTable.= "<TD align='center'><em class='h2'>".$Semestr."</em></TD>\n";
                            $rowOfTable.= "<TD align='left' style='padding-left:3px'><em class='h2'>".$GroopsAll."</em></TD>\n";
                            $rowOfTable.= "<TD align='left' style='padding-left:3px'><em class='h2'>Экзамен</em></TD>\n";
                            $rowOfTable.= "<TD align='left' style='padding-left:3px'><em class='h2'>".$factItem["Exam"]."</em></TD>\n";
                            $rowOfTable.= "</tr>";
                        }
                        if ($factItem["Synopsis"] > 0) {
                            $rowOfTable.= "<tr>";
                            $rowOfTable.= "<TD align='left' style='padding-left:3px'><em class='h2'>".$DisName."</em></TD>\n";
                            //$rowOfTable.= "<TD align='center'><em class='h2'>".$Semestr."</em></TD>\n";
                            $rowOfTable.= "<TD align='left' style='padding-left:3px'><em class='h2'>".$GroopsAll."</em></TD>\n";
                            $rowOfTable.= "<TD align='left' style='padding-left:3px'><em class='h2'>Реферат</em></TD>\n";
                            $rowOfTable.= "<TD align='left' style='padding-left:3px'><em class='h2'>".$factItem["Synopsis"]."</em></TD>\n";
                            $rowOfTable.= "</tr>";
                        }
                        if ($factItem["RGR"] > 0) {
                            $rowOfTable.= "<tr>";
                            $rowOfTable.= "<TD align='left' style='padding-left:3px'><em class='h2'>".$DisName."</em></TD>\n";
                            //$rowOfTable.= "<TD align='center'><em class='h2'>".$Semestr."</em></TD>\n";
                            $rowOfTable.= "<TD align='left' style='padding-left:3px'><em class='h2'>".$GroopsAll."</em></TD>\n";
                            $rowOfTable.= "<TD align='left' style='padding-left:3px'><em class='h2'>РГР</em></TD>\n";
                            $rowOfTable.= "<TD align='left' style='padding-left:3px'><em class='h2'>".$factItem["RGR"]."</em></TD>\n";
                            $rowOfTable.= "</tr>";
                        }

                        if ($Semestr == 1) {
                            $tableFirstSem .= $rowOfTable;
                        } else {
                            $tableSecondSem .= $rowOfTable;
                        }
                    }

                    mysql_close($Connection);
                    ?>

                    <?/*<TR ALIGN='CENTER' VALIGN='MIDDLE'>
                        <TD style="width:350px"><em class='h2'>Программирование</em></TD>
                        <TD style="width:60px"><em class='h2'>1</em></TD>
                        <TD style="width:100px"><em class='h2'>ИВТбд-11</em></TD>
                        <TD style="width:100px"><em class='h2'>лекции</em></TD>
                        <TD style="width:150px"><em class='h2'>60</em></TD>
                    </TR>*/?>

                    <?php
                    if ($tableFirstSem != "") {
                        ?>
                        <TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=0 CELLPADDING=10 WIDTH='100%'>
                            <TR ALIGN='CENTER' VALIGN='MIDDLE'>
                                <TD><em class='h2'><center>1 семестр</center></em></TD>
                            </TR>
                        </TABLE>
                        <?php
                        echo "<TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>";
                        echo $tableFirstSem;
                        echo "</TABLE>";
                    }
                    if ($tableSecondSem != "") {
                        ?>
                        <TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=0 CELLPADDING=10 WIDTH='100%'>
                            <TR ALIGN='CENTER' VALIGN='MIDDLE'>
                                <TD><em class='h2'><center>2 семестр</center></em></TD>
                            </TR>
                        </TABLE>
                        <?php
                        echo "<TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>";
                        echo $tableSecondSem;
                        echo "</TABLE>";
                    }
                    ?>

            </td></tr></table><BR>
    <TABLE BORDER=0 ALIGN=CENTER>
        <TR>
            <TD colspan="2"><CENTER><INPUT TYPE='SUBMIT' NAME='GetPlan' VALUE='Получить индивидуальный план'
                               onClick="fed.action='../TeachersPlans/TeacherPlan.php?teacher=<?=$CodeOfTeacher?>'"></INPUT></CENTER></TD>
        </TR>
    </TABLE>



    </center>
    <input type='hidden' name='NumOfChangeRec' value=''>
    <input type='hidden' name='teacher' value='<?=$CodeOfTeacher?>'>
</form>
<hr></body></html>