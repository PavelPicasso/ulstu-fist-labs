<?php
session_start();
if (!($_SESSION["status"] == 0)){
    Header ("Location: ../Unreg.html");
    exit;
} else {
    include("../PlanCalculatFunc.php");
    CreateConnection();
    include("../Display/DisplayFunc.php");
    $CodeOfTeacher = $_SESSION["teacher"];
    /*$q = "select teachers.TeacherName, teachers.Mail, department.DepName from teachers, department where teachers.CodeOfTeacher = '".$CodeOfTeacher."' ".
        "and department.CodeOfDepart = teachers.CodeOfDepart";
    $teacher = FetchArrays($q);
    $teacherName = $teacher[0]["TeacherName"];
    $teacherMail = $teacher[0]["Mail"];
    $depName = $teacher[0]["DepName"];*/
}
?>
<HTML>
<HEAD>
    <TITLE>Учебный процесс</TITLE>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
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

        // -->
    </script>
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
    <em class='h1'><center>Учебный процесс</center></em>
    <?php
    $reportsArray = array();
    $q="SELECT teachersDiscips.CodeOfTeacherDiscip, disciplins.DisName, disciplins.CodeOfDiscipline, ".
        "schedplan.CodeOfSchPlan, schplanitemshours.CodeOfSchPlanItem, schplanitemshours.NumbOfSemestr ".
        "FROM teachersDiscips, disciplins, schedplan, schplanitemshours, plans ".
        "WHERE teachersDiscips.CodeOfTeacher = {$CodeOfTeacher} ".
        "AND schplanitemshours.CodeOfSchPlanItem = teachersDiscips.CodeOfSchPlanItem ".
        "AND schedplan.CodeOfSchPlan = schplanitemshours.CodeOfSchPlan ".
        "AND disciplins.CodeOfDiscipline = schedplan.CodeOfDiscipline ".
        "AND schedplan.CodeOfPlan = plans.CodeOfPlan ".
        "AND (plans.Date IS NULL OR plans.Date = '0000-00-00') ".
        "ORDER BY disciplins.DisName";
    $disciplines = FetchArrays($q);
    foreach ($disciplines as $dis) {
        $GlobalSem = $dis['NumbOfSemestr'];
        if ($GlobalSem % 2 == 0) {
            $Semestr = " (2 семестр)";
        } else {
            $Semestr = " (1 семестр)";
        }
        $q = "select distinct reports.CodeOfStudentGroup, studentgroups.GroupName ".
            "from reports, studentgroups ".
            "where studentgroups.CodeOfStudentGroup = reports.CodeOfStudentGroup ".
            "and reports.CodeOfSchPlanItem = {$dis['CodeOfSchPlanItem']} ".
            "and reports.CodeOfTeacher = {$CodeOfTeacher} ".
            "order by studentgroups.GroupName";
        $groups = FetchArrays($q);
        if (empty($groups)) {
            continue;
        }
        $groupsArray = array();
        foreach ($groups as $group) {
            $q = "select distinct reports.CodeOfReport, reports.ReportName, reports.Deadline ".
                "from reports ".
                "where reports.CodeOfStudentGroup = {$group['CodeOfStudentGroup']} ".
                "and reports.CodeOfSchPlanItem = {$dis['CodeOfSchPlanItem']} ".
                "and reports.CodeOfTeacher = {$CodeOfTeacher} ".
                "order by reports.ReportName";
            $reports = FetchArrays($q);
            if (empty($reports)) {
                continue;
            }
            $repNamesArray = array();
            foreach ($reports as $rep) {
                $q = "select CodeOfStudent, StudentName from students where CodeOfStudentGroup = {$group['CodeOfStudentGroup']}";
                $students = FetchArrays($q);

                $studentMarks = array();
                foreach ($students as $stud) {
                    $q = "select distinct CodeOfStudentMark, PassDate, Mark ".
                        "from studentmarks ".
                        "where CodeOfStudent = {$stud['CodeOfStudent']} ".
                        "and CodeOfReport = {$rep['CodeOfReport']}";
                    $marks = FetchFirstRow($q);
                    $CodeOfStudentMark = "";
                    $PassDate = "";
                    $Mark = "";
                    if (!empty($marks)) {
                        $CodeOfStudentMark = $marks['CodeOfStudentMark'];
                        $PassDate = $marks['PassDate'];
                        $Mark = $marks['Mark'];
                    }
                    $studentMarks[] = array("CodeOfStudent" => $stud['CodeOfStudent'],
                        "StudentName" => iconv("windows-1251", "UTF-8", $stud['StudentName']), "CodeOfStudentMark" => $CodeOfStudentMark,
                        "PassDate" => $PassDate, "Mark" => $Mark);
                }
                $repNamesArray[] = array("ReportCode" => $rep['CodeOfReport'],
                    "ReportName" => iconv("windows-1251", "UTF-8", $rep['ReportName']), "Deadline" => $rep['Deadline'],
                    "StudentMarks" => $studentMarks);
            }
            $groupsArray[] = array("GroupCode" => $group['CodeOfStudentGroup'],
                "GroupName" => iconv("windows-1251", "UTF-8", $group['GroupName']), "Reports" => $repNamesArray);
        }
        $reportsArray[] = array("CodeOfSchPlanItem" => $dis['CodeOfSchPlanItem'],
            "Dis" => iconv("windows-1251", "UTF-8", $dis['DisName'].$Semestr), "Groups" => $groupsArray);
    }
    mysql_close($Connection);
    //$reportsCount = count($reportsArray);
    //$groupsCount = count($groupsArray);
    //$repNamesCount = count($repNamesArray);
    //print_r($reportsArray);
    ?>
    <table border='0' width='100%' cellpadding='0' cellspacing='2'><tr><td height='5' bgcolor="#92a2d9"><img src="../img/line.gif" width=15 height=15 hspace=0 vspace=0 border='0' align='left'></td></tr></table><br><table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center">
        <tr><td cellpadding="0" cellspacing="0">
                <TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=0 CELLPADDING=10 WIDTH='100%'>
                    <TR ALIGN='CENTER' VALIGN='MIDDLE'>
                        <TD><a href="main.php">Персональные данные</a></TD>
                        <TD><a href="Plan.php">Индивидуальный план</a></TD>
                        <TD><em class='h2'>Учебный процесс</em></TD>
                    </TR>
                </TABLE>
                <TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=0 CELLPADDING=10 WIDTH='100%'>
                    <TR ALIGN='CENTER' VALIGN='MIDDLE'>
                        <TD><em class="h2">Дисциплина</em><select name="CodeOfSchPlanItem" onChange="getGroupsByDis(this.value)">
                                <?php
                                    foreach ($reportsArray as $dis) {
                                        ?>
                                        <option value="<?=$dis['CodeOfSchPlanItem']?>"><?=iconv("UTF-8", "windows-1251", $dis['Dis'])?></option>
                                        <?php
                                    }
                                ?>
                            <?/*<option value="Дис1">Дис1</option>
                            <option value="Дис2">Дис2</option>*/?>
                        </select></TD>
                        <TD><em class="h2">Группа</em><select name="CodeOfStudentGroup" onChange="getReportsByGroup(CodeOfSchPlanItem.value, this.value)">
                            <option value="N/A">N/A</option>
                        </select></TD>
                        <TD><em class="h2">Вид отчетности</em><select name="CodeOfReport" onChange="getMarksByReport(CodeOfSchPlanItem.value, CodeOfStudentGroup.value, this.value)">
                            <option value="N/A">N/A</option>
                        </select></TD>
                    </TR>
                </TABLE>
                <TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=0 CELLPADDING=10 WIDTH='100%'>
                    <TR ALIGN='CENTER' VALIGN='MIDDLE'>
                        <TD><em class='h2'>Срок сдачи</em></TD>
                        <TD><INPUT TYPE=date id='deadline' name='Deadline'></INPUT></TD>
                        <TD><INPUT TYPE='SUBMIT' NAME='EditTime' VALUE='Изменить'
                               onClick="fed.action='DoEditReports.php'"></INPUT></TD>
                    </TR>
                </TABLE>
                <TABLE  class='table' id='marks' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
                    <TR ALIGN='CENTER' VALIGN='MIDDLE'>
                        <TD style="width:200px"><strong>ФИО</strong></TD>
                        <TD><strong>Дата сдачи</strong></TD>
                        <TD><strong>Оценка</strong></TD>
                    </TR>
                    <?php /*<TR ALIGN='CENTER' VALIGN='MIDDLE'>
                        <TD style="width:200px"><em class='h2'>Петров Петр Петрович</em></TD>
                        <TD style="width:100px"><em class='h2'>11.11.2011</em></TD>
                        <TD style="width:100px"><em class='h2'>отлично</em></TD>
                    </TR>*/?>
                </TABLE>

            </td></tr></table><BR>
    <TABLE BORDER=0 ALIGN=CENTER>
        <TR>
            <TD colspan="2"><CENTER><INPUT TYPE='SUBMIT' NAME='EditMarks' VALUE='Внести изменения'
                               onClick="fed.action='DoEditReports.php'"></INPUT></CENTER></TD>
            <TD colspan="2"><CENTER><INPUT TYPE='SUBMIT' NAME='NewReport' VALUE='Новая отчетность'
                                           onClick="fed.action='NewReport.php'"></INPUT></CENTER></TD>
        </TR>
    </TABLE>

    <?/*<TABLE BORDER=0 ALIGN=CENTER>
                    <TR ALIGN='CENTER' VALIGN='MIDDLE'>
                        <TD><em class='h2'>Новая отчетность</em></TD>
                        <TD><INPUT TYPE=TEXT></INPUT></TD>
                        <TD><INPUT TYPE='SUBMIT' NAME='NewPeport' VALUE='Добавить'
                               onClick="fed.action='.php'"></INPUT></TD>
                    </TR>
                </TABLE>*/?>

    </center>
    <input type='hidden' name='NumOfChangeRec' value=''>
    <input type='hidden' name='teacher' value='<?=$CodeOfTeacher?>'>
</form>

<script type="text/javascript">
    var reports = <?php echo json_encode($reportsArray)?>;

    function getDisId(value){
        for (var i = 0; i < reports.length; i++) {
            if (reports[i].CodeOfSchPlanItem == value) {
                return i;
            }
        };
    }

    function getGroupsByDis(value) {
        //alert(value);
        //alert(reports[0].Dis);
        var disId = getDisId(value);
        //alert(value);
        var groups = reports[disId].Groups;
        var groupCount = groups.length;
        var groupList = document.forms["fed"].elements["CodeOfStudentGroup"];
        var groupListCount = groupList.options.length;
        groupList.length = 0; // удаляем все элементы из списка домов
        for (i = 0; i < groupCount; i++) {
            // далее мы добавляем необходимые дома в список
            if (document.createElement) {
                var newGroupList = document.createElement("OPTION");
                newGroupList.text = groups[i].GroupName;
                newGroupList.value = groups[i].GroupCode;
                // тут мы используем для добавления элемента либо метод IE, либо DOM, которые, alas, не совпадают по параметрам…
                (groupList.options.add) ? groupList.options.add(newGroupList) : groupList.add(newGroupList, null);
                //(oHouseList.options.add) ? oHouseList.options.add(newHouseListOption) : oHouseList.add(newHouseListOption, null);
            } else {
                // для NN3.x-4.x
                groupList.options[i] = new Option(groupNames[i], groupNames[i], false, false);
            }
        }
        getReportsByGroup(document.forms["fed"].elements["CodeOfSchPlanItem"].value,
            document.forms["fed"].elements["CodeOfStudentGroup"].value);
    }

    function getGroupId(groupsArr, value){
        for (var i = 0; i < groupsArr.length; i++) {
            if (groupsArr[i].GroupCode == value) {
                return i;
            }
        }
    }

    function getReportsByGroup(discip, group) {
        //alert(discip);
        //alert(group);
        var disId = getDisId(discip);
        var groups = reports[disId].Groups;
        var groupId = getGroupId(groups, group);
        var reportNames = groups[groupId].Reports;
        //alert(groupNames[1]);
        var reportCount = reportNames.length;
        var reportList = document.forms["fed"].elements["CodeOfReport"];
        var reportListCount = reportList.options.length;
        reportList.length = 0; // удаляем все элементы из списка домов
        for (i = 0; i < reportCount; i++) {
            // далее мы добавляем необходимые дома в список
            if (document.createElement) {
                var newReportList = document.createElement("OPTION");
                newReportList.text = reportNames[i].ReportName;
                newReportList.value = reportNames[i].ReportCode;
                // тут мы используем для добавления элемента либо метод IE, либо DOM, которые, alas, не совпадают по параметрам…
                (reportList.options.add) ? reportList.options.add(newReportList) : reportList.add(newReportList, null);
                //(oHouseList.options.add) ? oHouseList.options.add(newHouseListOption) : oHouseList.add(newHouseListOption, null);
            } else {
                // для NN3.x-4.x
                reportList.options[i] = new Option(reportNames[i], reportNames[i], false, false);
            }
        }
        getMarksByReport(document.forms["fed"].elements["CodeOfSchPlanItem"].value,
            document.forms["fed"].elements["CodeOfStudentGroup"].value,
            document.forms["fed"].elements["CodeOfReport"].value);
    }

    function getReportId(reportsArr, value){
        for (var i = 0; i < reportsArr.length; i++) {
            if (reportsArr[i].ReportCode == value) {
                return i;
            }
        }
    }

    function getMarksByReport(discip, group, report) {
        var disId = getDisId(discip);
        var groups = reports[disId].Groups;
        var groupId = getGroupId(groups, group);
        var reportItems = groups[groupId].Reports;
        var reportId = getReportId(reportItems, report);
        var studentMarks = reportItems[reportId].StudentMarks;
        var studentMarksCount = studentMarks.length;
        var deadline = document.getElementById("deadline");
        deadline.value = reportItems[reportId].Deadline;
        var studentTable = document.getElementById("marks").getElementsByTagName('TBODY')[0];
        while (studentTable.rows[1]) {
            studentTable.deleteRow(1);
        }
        for (var i = 0; i < studentMarksCount; i++) {
            var row = document.createElement("TR");
            studentTable.appendChild(row);

            // Создаем ячейки в вышесозданной строке
            // и добавляем тх
            var td1 = document.createElement("TD");
            var td2 = document.createElement("TD");
            var td3 = document.createElement("TD");

            row.appendChild(td1);
            row.appendChild(td2);
            row.appendChild(td3);

            // Наполняем ячейки
            td1.innerHTML = "<em class='h2'>" + studentMarks[i].StudentName + "</em>";
            td2.innerHTML = "<center><INPUT TYPE=date NAME='PassDate[" + studentMarks[i].CodeOfStudent + "]' VALUE='" + studentMarks[i].PassDate + "' onChange='FillArrChenge(this," + studentMarks[i].CodeOfStudent + ")'></INPUT></center>";
            td3.innerHTML = "<center><INPUT TYPE=TEXT NAME='Marks[" + studentMarks[i].CodeOfStudent + "]' VALUE='" + studentMarks[i].Mark + "' onChange='FillArrChenge(this," + studentMarks[i].CodeOfStudent + ")'></INPUT></center>";
        }
    }

    getGroupsByDis(document.forms["fed"].elements["CodeOfSchPlanItem"].value);
    getReportsByGroup(document.forms["fed"].elements["CodeOfSchPlanItem"].value,
        document.forms["fed"].elements["CodeOfStudentGroup"].value);
    getMarksByReport(document.forms["fed"].elements["CodeOfSchPlanItem"].value,
        document.forms["fed"].elements["CodeOfStudentGroup"].value,
        document.forms["fed"].elements["CodeOfReport"].value);
//-->
</script>
</body></html>