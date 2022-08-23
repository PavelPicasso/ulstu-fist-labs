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
    mysql_close($Connection);
}
?>
<HTML>
<HEAD>
    <TITLE>Личный кабинет</TITLE>
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
        // -->
    </script></HEAD>



<BODY topmargin="1" leftmargin="1" marginheight="1" marginwidth="1">
<form name=fed method=post action="">
    <em class='h1'><center>Личный кабинет</center></em><table border='0' width='100%' cellpadding='0' cellspacing='2'><tr><td height='5' bgcolor="#92a2d9"><img src="../img/line.gif" width=15 height=15 hspace=0 vspace=0 border='0' align='left'></td></tr></table><br><table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center">
        <tr><td cellpadding="0" cellspacing="0">
                <TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=0 CELLPADDING=10 WIDTH='100%'>
                    <TR ALIGN='CENTER' VALIGN='MIDDLE'>
                        <TD><em class='h2'>Персональные данные</em></TD>
                        <TD><a href="Plan.php">Индивидуальный план</a></TD>
                        <TD><a href="StudentMarks.php">Учебный процесс</a></TD>
                    </TR>
                </TABLE>
                <TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=0 CELLPADDING=10 WIDTH='100%'>
                    <TR ALIGN='CENTER' VALIGN='MIDDLE'>                  
                        <TD><em class='h2'>ФИО</em></TD>    
                        <TD><em class='h2'><?=$teacherName?></em></TD>
                    </TR>    
                    <TR ALIGN='CENTER' VALIGN='MIDDLE'>                  
                        <TD><em class='h2'>Кафедра</em></TD>    
                        <TD><em class='h2'><?=$depName?></em></TD>
                    </TR>       
                    <?/*<TR ALIGN='CENTER' VALIGN='MIDDLE'>
                        <TD><em class='h2'>Контактный телефон</em></TD>    
                        <TD><INPUT TYPE=TEXT></INPUT></TD>
                    </TR>   */?>
                    <TR ALIGN='CENTER' VALIGN='MIDDLE'>                  
                        <TD><em class='h2'>Контактный E-mail</em></TD>
                        <TD><INPUT TYPE=TEXT name="mail" value="<?=$teacherMail?>"></INPUT></TD>
                        <input type="hidden" name="CodeOfTeacher" value="<?=$CodeOfTeacher?>">
                    </TR>
                </TABLE>

            </td></tr></table><BR>
    <TABLE BORDER=0 ALIGN=CENTER>
        <TR>
            <TD><CENTER><INPUT TYPE='SUBMIT' NAME='Edit' VALUE='Внести изменения'
                               onClick="fed.action='DoEditInfo.php'"></INPUT></CENTER></TD>
            <TD><CENTER><INPUT TYPE='RESET' NAME='reset' VALUE='Отменить изменения'
                               onClick="RefreshRecArr(this)"></INPUT></CENTER></TD>
        </TR>
    </TABLE>



    </center>
    <input type='hidden' name='NumOfChangeRec' value=''></form>
<hr></body></html>