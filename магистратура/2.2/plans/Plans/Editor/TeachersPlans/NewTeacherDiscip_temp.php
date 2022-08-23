<?php
session_start();
if (!($_SESSION["status"] == 0)){
    Header ("Location: ../Unreg.html");
    exit;
}
?>
<HTML>
<HEAD>
    <TITLE>Выбор новых дисциплин преподавателя</TITLE>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
    <link rel="stylesheet" href="../../../CSS/PlansEditor.css" type="text/css">
</HEAD>

<BODY topmargin="1" leftmargin="5" marginheight="1" marginwidth="5">
<FORM METHOD='post' NAME='depform' ACTION='DoNewTeacher.php'>
    <em class='h1'><center>Выбор новых дисциплин преподавателя</center></em>
    <?php
    include("../Display/DisplayFunc.php");
    $CodeOfTeacher = $_POST['teacher'];
    if (empty($CodeOfTeacher)) {
        FuncAlert("Не выбран преподаватель","../TeachersBook/TeachersBook.php");
    }
    include("../PlanCalculatFunc.php");
    CreateConnection();
    $q="select TeacherName, CodeOfDepart from teachers where CodeOfTeacher={$CodeOfTeacher}";
    $result = FetchFirstRow($q);
    $fio = $result['TeacherName'];
    $CodeOfDepart = $result['CodeOfDepart'];
    echo "<br><em class='h2'><center>".$fio."</center></em><br>";
    ?>
    <table border='0' width='100%' cellpadding='0' cellspacing='2'><tr><td height='5' bgcolor="#92a2d9"><img src="../img/line.gif" width=15 height=15 hspace=0 vspace=0 border='0' align='left'></td></tr></table><H2>Выберите дисциплины</H2><br><table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center">
        <tr><td cellpadding="0" cellspacing="0">
                <TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
                    <TR>
                        <TH>&nbsp;</TH>
                        <TH><strong>План</strong></TH>
                        <TH><strong>Дисциплина</strong></TH>
                        <TH><strong>Семестр</strong></TH>
                        <TH><strong>№ группы</strong></TH>
                    </TR>
                    <?php
                    $q = "select CodeOfSchPlanItem from teachersDiscips";
                    $result = FetchArrays($q);
                    $AddQ = "";
                    while ($CodeOfSchPlanItem = array_shift($result)){
                        if (strcmp($AddQ,"")==0){
                            $AddQ = "schplanitems.CodeOfSchPlanItem not in ('".$CodeOfSchPlanItem['CodeOfSchPlanItem']."' ";
                        }
                        else {
                            $AddQ .= ",'".$CodeOfSchPlanItem['CodeOfSchPlanItem']."'" ;
                        }
                    }
                    if (strcmp($AddQ,"")!=0){
                        $AddQ .= ") ";
                    }
                    $q = "select CodeOfSchPlanItem from schplanitems where ".$AddQ." and CodeOfDepart = {$CodeOfDepart}";
                    $result = FetchArrays($q);
                    $AddQ = "";
                    while ($CodeOfSchPlanItem = array_shift($result)){
                        if (strcmp($AddQ,"")==0){
                            $AddQ = "schplanitems.CodeOfSchPlanItem in ('".$CodeOfSchPlanItem['CodeOfSchPlanItem']."' ";
                        }
                        else {
                            $AddQ .= ",'".$CodeOfSchPlanItem['CodeOfSchPlanItem']."'" ;
                        }
                    }
                    if (strcmp($AddQ,"")!=0){
                        $AddQ .= ") ";
                    }

                    $q="SELECT disciplins.DisName, ".
                        "schedplan.CodeOfSchPlan, schplanitems.CodeOfSchPlanItem, schplanitems.NumbOfSemestr ".
                        "FROM disciplins, schedplan, schplanitems ".
                        "WHERE ".$AddQ.
                        "AND schedplan.CodeOfSchPlan = schplanitems.CodeOfSchPlan ".
                        "AND disciplins.CodeOfDiscipline = schedplan.CodeOfDiscipline ".
                        "ORDER BY disciplins.DisName";

                    $result = FetchArrays($q);
                    foreach ($result as $row){
                        $CodeOfSchPlanItem = $row['CodeOfSchPlanItem'];
                        $CodeOfSchPlan = $row['CodeOfSchPlan'];
                        $DisName = $row['DisName'];
                        $GlobalSem = $row['NumbOfSemestr'];
                        if ($GlobalSem % 2 == 0) {
                            $Semestr = 2;
                        } else {
                            $Semestr = 1;
                        }

                        $sql = "select distinct plans.CodeOfPlan ".
                            "FROM schedplan ".
                            "LEFT JOIN plans ON (plans.CodeOfPlan = schedplan.CodeOfPlan) ".
                            "WHERE (plans.Date IS NULL OR plans.Date = '0000-00-00') ".
                            "AND schedplan.CodeOfSchPlan = {$CodeOfSchPlan}";
                        $res=FetchArrays($sql);

                        $AddQ = "";
                        while ($PlnCode = array_shift($res)){
                            if (strcmp($AddQ,"")==0){
                                $AddQ = "streams.CodeOfPlan in ('".$PlnCode['CodeOfPlan']."' ";
                            }
                            else {
                                $AddQ .= ",'".$PlnCode['CodeOfPlan']."'" ;
                            }
                        }
                        if (strcmp($AddQ,"")!=0){
                            $AddQ .= ") ";
                        }
                        $qstream = "select distinct streams.StreamName, plans.PlnName ".
                            "from streams, plans ".
                            "where plans.CodeOfPlan=streams.CodeOfPlan ";
                        if (strcmp($AddQ,"")!=0) {
                            $qstream .= "and ".$AddQ;
                        }
                            //"order by streams.StreamName";
                        $Streams = FetchArrays($qstream);
                        foreach ($Streams as $ks =>$vs) {
                            echo "<TR>\n";
                            echo "<TD align='center'><INPUT TYPE='CHECKBOX' NAME='insert[]' VALUE=\"$CodeOfSchPlanItem\"></TD>\n";
                            echo "<TD align='left' style='padding-left:3px'><em class='h2'>".$vs['PlnName']."</em></TD>\n";
                            echo "<TD align='left' style='padding-left:3px'><em class='h2'>".$DisName."</em></TD>\n";
                            echo "<TD align='center'><em class='h2'>".$Semestr."</em></TD>\n";

                            $StrName = $vs["StreamName"];		//Поток
                            if (!empty($StrName)) {
                                $kurs = $GlobalSem / 2;
                                if ($Semestr == 1) {
                                    $kurs += 0.5;
                                }
                                $Groop = $StrName."-".$kurs;
                            } else {
                                $Groop = "не указано";
                            }
                        }
                        echo "<TD align='left' style='padding-left:3px'><em class='h2'>".$Groop."</em></TD>\n";
                        echo "</TR>\n";
                    }
                    mysql_close($Connection);
                    ?>
                </TABLE>
            </TD></TR></TABLE>
    <BR><br>
        <CENTER>
            <INPUT TYPE='SUBMIT' NAME='OK' VALUE='Добавить выбранные дисциплины' action='DoNewTchDis.php?teacher="<?=$CodeOfTeacher?>"'></INPUT>
        </CENTER>
</FORM>
<HR>
</BODY>
</HTML>
