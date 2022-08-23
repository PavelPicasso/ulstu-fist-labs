<?php
session_start();
if (!($_SESSION["status"] == 0)){
    Header ("Location: ../Unreg.html");
    exit;
}
?>
<HTML>
<HEAD>
    <TITLE>Преподаватели</TITLE>
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
                return ('DoDelTeacher.php');
            }
            return('TeachersBook.php');
        }
        // -->
    </script></HEAD>



<BODY topmargin="1" leftmargin="1" marginheight="1" marginwidth="1">
<form name=fed method=post action="">
    <em class='h1'><center>Преподаватели</center></em><table border='0' width='100%' cellpadding='0' cellspacing='2'><tr><td height='5' bgcolor="#92a2d9"><img src="../img/line.gif" width=15 height=15 hspace=0 vspace=0 border='0' align='left'></td></tr></table><br><table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center">
        <tr><td cellpadding="0" cellspacing="0">
                <TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
                    <TR ALIGN='CENTER' VALIGN='MIDDLE'>
                        <TH>&nbsp;</TD>
                        <TH><strong>Ф. И. О.</strong></TH>
                        <TH><strong>Для подписи</strong></TH>
                        <TH><strong>Кафедра</strong></TH>
                        <TH><strong>E-mail</strong></TH>
                        <TH>&nbsp;</TH>
                    </TR>
                    <?php
                    include("../../cfg.php");
                    $Connection = mysql_connect($data_source , $dbi_user , $dbi_password)
                    or die("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
                    mysql_select_db("plans")
                    or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");
                    //Массив с сокр. названиями и кодами кафедр
                    $DepArray=array();
                    $CodeDepArray=array();

                    //statusCode - код факультета (в шапке)
                    if ($_SESSION["statusCode"]==0){ $q="select Reduction, CodeOfDepart from department order by Reduction";}
                    else {$q="select Reduction, CodeOfDepart from department where CodeOfFaculty=".$_SESSION["statusCode"]." order by Reduction";}

                    $result = mysql_query($q, $Connection)
                    or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
                    $i=0;
                    while ($row = mysql_fetch_object($result)){
                        $DepArray[] = $row->Reduction;
                        $CodeDepArray[] = $row->CodeOfDepart;
                    }

                    if ($_SESSION["statusCode"]==0){ $q="select distinct teachers.CodeOfTeacher, teachers.TeacherName, teachers.TeacherSignature, teachers.CodeOfDepart, department.Reduction, teachers.Mail".
                        " from teachers, department where teachers.CodeOfDepart = department.CodeOfDepart order by CodeOfDepart, TeacherName";}
                    else {$q="select distinct teachers.CodeOfTeacher, teachers.TeacherName, teachers.TeacherSignature, teachers.CodeOfDepart, department.Reduction, teachers.Mail".
                        " from teachers, department".
                        " where teachers.CodeOfDepart=department.CodeOfDepart".
                        " and department.CodeOfFaculty=".$_SESSION["statusCode"]." order by CodeOfDepart, TeacherName" ;}

                    $result = mysql_query($q, $Connection)
                    or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
                    while ($row = mysql_fetch_object($result)){
                        $CodeOfTeacher = $row->CodeOfTeacher;
                        $TeacherName = $row->TeacherName;
                        $TeacherSignature = $row->TeacherSignature;
                        $CodeOfDepart = $row->CodeOfDepart;
                        $DepartReduction = $row->Reduction;
                        $Mail = $row->Mail;
                        echo "<TR>\n";
                        echo "<TD align='center'><INPUT TYPE='CHECKBOX' NAME='del[]' VALUE=\"$CodeOfTeacher\"></TD>\n";
                        echo "<TD align='center'><INPUT TYPE=TEXT NAME=\"TeacherName[$CodeOfTeacher]\"   SIZE='30' MAXLENGTH=80 VALUE='$TeacherName'\n";
                        echo "              onChange=\"FillArrChenge(this,$CodeOfTeacher)\"></INPUT></TD>\n";
                        echo "<TD align='center'><INPUT TYPE=TEXT NAME=\"Signature[$CodeOfTeacher]\"   SIZE='15' MAXLENGTH=80 VALUE='$TeacherSignature'\n";
                        echo "              onChange=\"FillArrChenge(this,$CodeOfTeacher)\"></INPUT></TD>\n";
                        echo "<td align='center'><SELECT NAME=\"CodeOfDepart[$CodeOfTeacher]\" onChange=\"FillArrChenge(this,$CodeOfTeacher)\">\n";
                        reset($DepArray);
                        reset($CodeDepArray);
                        while (($Dep = each($DepArray)) && ($CodeDep = each($CodeDepArray))){
                            if ($CodeDep[1] == $CodeOfDepart){ echo "<OPTION SELECTED VALUE=".$CodeDep[1].">".$Dep[1]."\n";}
                            else { echo "<OPTION VALUE=".$CodeDep[1].">".$Dep[1]."\n";}
                        }
                        echo "</SELECT></TD>\n";
                        echo "<TD align='center'><font size='-1'><INPUT TYPE=TEXT NAME=\"Mail[$CodeOfTeacher]\"   SIZE=20 MAXLENGTH=100 VALUE='$Mail'\n";
                        echo "              onChange=\"FillArrChenge(this,$CodeOfTeacher)\">  </INPUT></font></TD>\n";
                        echo "<TD align='center'>&nbsp;<a href='../TeachersPlans/TeacherDiscip.php?teacher=$CodeOfTeacher'>Нагрузка</a>&nbsp;</TD>\n";
                        echo "</TR>\n";
                    }
                    mysql_free_result($result);
                    mysql_close($Connection);
                    ?>
                </TABLE>

            </td></tr></table><BR>
    <TABLE BORDER=0 ALIGN=CENTER>
        <TR>
            <TD><CENTER><INPUT TYPE='SUBMIT' NAME='Edit' VALUE='Внести изменения'
                               onClick="fed.action='DoEditTeacher.php'"></INPUT></CENTER></TD>
            <TD><CENTER><INPUT TYPE='RESET' NAME='reset' VALUE='Отменить изменения'
                               onClick="RefreshRecArr(this)"></INPUT></CENTER></TD>
        </TR>

        <TR>
            <TD><CENTER><input type=submit VALUE='Добавить в справочник новую запись'
                               onClick="fed.action='NewTeacher.php?depart=0'">
                </CENTER></TD>
            <TD><CENTER><INPUT TYPE='SUBMIT' NAME='Delete' VALUE='Удалить помеченные записи'
                               onClick="fed.action=Deleting(this)"></INPUT></CENTER></TD>
        </TR>
    </TABLE>



    </center>
    <input type='hidden' name='NumOfChangeRec' value=''></form>
<hr></body></html>