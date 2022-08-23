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
    <TITLE>Новая отчетность</TITLE>
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
    <em class='h1'><center>Новая отчетность</center></em>
    <?php
    $reportsArray = array();
    $q="SELECT teachersDiscips.CodeOfTeacherDiscip, disciplins.DisName, disciplins.CodeOfDiscipline, ".
        "schedplan.CodeOfSchPlan, schplanitemshours.CodeOfSchPlanItem, schplanitemshours.NumbOfSemestr, plans.CodeOfPlan ".
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
        $CodeOfTeachersDiscip = $dis['CodeOfTeacherDiscip'];
        $CodeOfSchPlan = $dis['CodeOfSchPlan'];
        $CodeOfPlan = $dis['CodeOfPlan'];
        $DisName = $dis['DisName'];

        //$qstream = "select distinct streams.StreamName from streams where streams.CodeOfPlan = ".$CodeOfPlan." order by streams.StreamName";
        //$Streams = FetchArrays($qstream);

        //$q = "select * from teachersDiscips ".
            //"where teachersDiscips.CodeOfTeacherDiscip = {$CodeOfTeachersDiscip}";
        $GlobalSem = $dis['NumbOfSemestr'];
        if ($GlobalSem % 2 == 0) {
            $Semestr = " (2 семестр)";
        } else {
            $Semestr = " (1 семестр)";
        }
        //$qstream = "select distinct streams.StreamName from streams where streams.CodeOfPlan = ".$CodeOfPlan." order by streams.StreamName";
        $q = "select distinct studentgroups.CodeOfStudentGroup, studentgroups.GroupName, studentgroups.CodeOfStream ".
            "from studentgroups, streams ".
            "where studentgroups.CodeOfStream = streams.CodeOfStream ".
            "and streams.CodeOfPlan = {$dis['CodeOfPlan']} ".
            "order by studentgroups.GroupName";
        $groups = FetchArrays($q);
        if (empty($groups)) {
            continue;
        }
        $groupsArray = array();
        foreach ($groups as $group) {
            $groupsArray[] = array("GroupCode" => $group['CodeOfStudentGroup'],
                "GroupName" => iconv("windows-1251", "UTF-8", $group['GroupName']));
        }
        $reportsArray[] = array("CodeOfSchPlanItem" => $dis['CodeOfSchPlanItem'],
            "Dis" => iconv("windows-1251", "UTF-8", $dis['DisName'].$Semestr), "Groups" => $groupsArray);
    }

    $q = "select CodeOfReportType, TypeName from reporttypes";
    $reportTypes = FetchArrays($q);
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
                    <?php
                    if (isset($_GET['error'])) {
                        if ($_GET['error'] == 1) {
                            echo "<TR><TD class='ErrMessage' colspan='4'>Не введено название отчетности</TD></TR>";
                        }
                        if ($_GET['error'] == 2) {
                            echo "<TR><TD class='ErrMessage' colspan='4'>Такая отчетность уже существует</TD></TR>";
                        }
                    }
                    ?>
                    <TR>
                        <TD><em class="h2">Дисциплина</em></TD>
                        <TD>
                            <select name="CodeOfSchPlanItem" onChange="getGroupsByDis(this.value)">
                                <?php
                                foreach ($reportsArray as $dis) {
                                    ?>
                                    <option value="<?=$dis['CodeOfSchPlanItem']?>"><?=iconv("UTF-8", "windows-1251", $dis['Dis'])?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </TD>
                    </TR>
                    <TR>
                        <TD><em class="h2">Группа</em></TD>
                        <TD><select name="CodeOfStudentGroup">
                                <option value="N/A">N/A</option>
                            </select></TD>
                    </TR>
                    <TR>
                        <TD><em class="h2">Вид отчетности</em></TD>
                        <TD>
                            <select name="CodeOfReportType">
                                <?php
                                foreach ($reportTypes as $type) {
                                    ?>
                                    <option value="<?=$type['CodeOfReportType']?>"><?=$type['TypeName']?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </TD>
                    </TR>
                    <TR>
                        <TD><em class="h2">Название</em></TD>
                        <TD><input name="ReportName" TYPE=TEXT SIZE=50 MAXLENGTH=80></INPUT></TD>
                    </TR>
                    <TR>
                        <TD><em class="h2">Срок сдачи</em></TD>
                        <TD><INPUT TYPE=date id='deadline' name='Deadline'></INPUT></TD>
                    </TR>
                </TABLE>

            </td></tr></table><BR>
    <TABLE BORDER=0 ALIGN=CENTER>
        <TR>
            <TD colspan="2"><CENTER><INPUT TYPE='SUBMIT' NAME='NewReport' VALUE='Добавить отчетность'
                                           onClick="fed.action='DoNewReport.php'"></INPUT></CENTER></TD>
            <TD colspan="2"><CENTER><INPUT TYPE='SUBMIT' NAME='Cancel' VALUE='Отменить'
                                           onClick="fed.action='StudentMarks.php'"></INPUT></CENTER></TD>
        </TR>
    </TABLE>

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
    }

    getGroupsByDis(document.forms["fed"].elements["CodeOfSchPlanItem"].value);
</script>
</body></html>