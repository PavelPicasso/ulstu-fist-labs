<?php
session_start();
if (!($_SESSION["status"] == 0)){
    Header ("Location: ../Unreg.html");
    exit;
}
?>
<HTML>
<HEAD>
    <TITLE>Нагрузка преподавателя</TITLE>
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

        function Deleting() {
            if(confirm(" Отмеченные строки будут безвозвратно удалены из базы данных. Удалить их?")) {
                return ('DoDelTeacherDiscip.php');
            }
            return('TeacherDiscip.php');
        }
        // -->
    </script></HEAD>



<BODY topmargin="1" leftmargin="1" marginheight="1" marginwidth="1">
<?php
    include("../Display/DisplayFunc.php");
    $CodeOfTeacher = $_GET['teacher'];
    if (empty($CodeOfTeacher)) {
        FuncAlert("Не выбран преподаватель","../TeachersBook/TeachersBook.php");
    }
?>
<form name=fed method=post action="">
    <em class='h1'><center>Нагрузка преподавателя</center></em>
    <?php
        include("../PlanCalculatFunc.php");
        CreateConnection();
        $q="select TeacherName from teachers where CodeOfTeacher={$CodeOfTeacher}";
        $fio = FetchResult($q);
        echo "<br><em class='h2'><center>".$fio."</center></em><br>";
    ?>
    <table border='0' width='100%' cellpadding='0' cellspacing='2'><tr><td height='5' bgcolor="#92a2d9"><img src="../img/line.gif" width=15 height=15 hspace=0 vspace=0 border='0' align='left'></td></tr></table><br><table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center">
        <tr><td cellpadding="0" cellspacing="0">
                <TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
                    <TR ALIGN='CENTER' VALIGN='MIDDLE'>
                        <TH>&nbsp;</TD>
                        <TH style="width:350px"><strong>Дисциплина</strong></TH>
                        <TH style="width:400px"><strong>План</strong></TH>
                        <TH style="width:60px"><strong>Семестр</strong></TH>
                        <TH style="width:100px"><strong>№ группы</strong></TH>
                        <TH style="width:100px"><strong>Виды нагрузки</strong></TH>
                    </TR>
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

                        /*$sql = "select distinct(plans.CodeOfPlan) as cp, plans.plnName ".
                            "FROM schedplan ".
                            "LEFT JOIN plans ON (plans.CodeOfPlan = schedplan.CodeOfPlan) ".
                            "WHERE (plans.Date IS NULL OR plans.Date = '0000-00-00') ".
                            "AND schedplan.CodeOfSchPlan = {$CodeOfSchPlan}";
                        $res=FetchArrays($sql);

                        $AddQ = "";
                        while ($PlnCode = array_shift($res)){
                            if (strcmp($AddQ,"")==0){
                                $AddQ = "streams.CodeOfPlan in ('".$PlnCode['cp']."' ";
                            }
                            else {
                                $AddQ .= ",'".$PlnCode['cp']."'" ;
                            }
                        }
                        if (strcmp($AddQ,"")!=0){
                            $AddQ .= ") ";
                        }
                        $qstream = "select distinct streams.StreamName ".
                            "from streams, plans ".
                            "where plans.CodeOfPlan=streams.CodeOfPlan ".
                            "and ".$AddQ.
                            "order by streams.StreamName";*/
                        $qstream = "select distinct streams.StreamName from streams where streams.CodeOfPlan = ".$CodeOfPlan." order by streams.StreamName";
                        $Streams = FetchArrays($qstream);

                        echo "<TR>\n";
                        echo "<TD align='center'><INPUT TYPE='CHECKBOX' NAME='del[]' VALUE=\"$CodeOfTeachersDiscip\"></TD>\n";
                        echo "<TD align='left' style='padding-left:3px'><em class='h2'>".$PlnName."</em></TD>\n";
                        echo "<TD align='left' style='padding-left:3px'><em class='h2'>".$DisName."</em></TD>\n";
                        echo "<TD align='center'><em class='h2'>".$Semestr."</em></TD>\n";
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
                        echo "<TD align='left' style='padding-left:3px'><em class='h2'>".$GroopsAll."</em></TD>\n";
                        echo "<TD align='center'>&nbsp;<a href='EditHours.php?teacher={$CodeOfTeacher}&CodeOfTeachersDiscip={$CodeOfTeachersDiscip}'>Редактировать</a>&nbsp;</TD>\n";
                        echo "</TR>\n";
                    }
                    mysql_close($Connection);
                    ?>
                </TABLE>

            </td></tr></table><BR>
    <TABLE BORDER=0 ALIGN=CENTER>
        <TR>
            <TD><CENTER><input type=submit VALUE='Добавить в справочник новую запись'
                               onClick="fed.action='NewTeacherDiscip.php'">
                </CENTER></TD>
            <TD><CENTER><INPUT TYPE='SUBMIT' NAME='Delete' VALUE='Удалить помеченные записи'
                               onClick="fed.action=Deleting(this)"></INPUT></CENTER></TD>
        </TR>
        <TR>
            <TD colspan="2"><CENTER><INPUT TYPE='SUBMIT' NAME='GetPlan' VALUE='Получить индивидуальный план'
                               onClick="fed.action='TeacherPlan.php'"></INPUT></CENTER></TD>
        </TR>
    </TABLE>



    </center>
    <input type='hidden' name='NumOfChangeRec' value=''>
    <input type='hidden' name='teacher' value='<?=$CodeOfTeacher?>'>
</form>
<hr></body></html>