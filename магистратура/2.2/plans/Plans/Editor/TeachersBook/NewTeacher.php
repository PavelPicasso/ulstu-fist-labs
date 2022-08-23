<?php
session_start();
if (!($_SESSION["status"] == 0)){
    Header ("Location: ../Unreg.html");
    exit;
}
?>
<HTML>
<HEAD>
    <TITLE>Ввод информации о новом преподавателе</TITLE>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
    <link rel="stylesheet" href="../../../CSS/PlansEditor.css" type="text/css">
</HEAD>

<BODY topmargin="1" leftmargin="5" marginheight="1" marginwidth="5">
<FORM METHOD='post' NAME='depform' ACTION='DoNewTeacher.php'>
    <em class='h1'><center>Ввод информации о новом преподавателе</center></em><table border='0' width='100%' cellpadding='0' cellspacing='2'><tr><td height='5' bgcolor="#92a2d9"><img src="../img/line.gif" width=15 height=15 hspace=0 vspace=0 border='0' align='left'></td></tr></table><H2>Заполните форму</H2><br><table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center">
        <tr><td cellpadding="0" cellspacing="0">
                <TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
                    <?php
                        if (isset($_GET['error']) && ($_GET['error'] == 1)) {
                            echo "<TR><TD class='ErrMessage' colspan='4'>Не введено Ф. И. О. преподавателя</TD></TR>";
                        }
                    ?>
                    <TR>
                        <TH><strong>Ф. И. О.</strong></TH>
                        <TH><strong>Для подписи</strong></TH>
                        <TH><strong>E-mail</strong></TH>
                        <TH><strong>Кафедра</strong></TH>
                    </TR>
                    <TR>
                        <TD align='center'><INPUT TYPE=TEXT NAME='TeacherName' SIZE=50 MAXLENGTH=80></INPUT></TD>
                        <TD align='center'><INPUT TYPE=TEXT NAME='Signature' SIZE=30 MAXLENGTH=80></INPUT></TD>
                        <TD align='center'><INPUT TYPE=TEXT NAME='Mail' SIZE=30 MAXLENGTH=80></INPUT></TD>
                        <?php
                        include("../../cfg.php");
                        $Connection = mysql_connect($data_source , $dbi_user , $dbi_password)
                        or die("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
                        mysql_select_db("plans")
                        or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");

                        echo "<TD align='center'><SELECT NAME=\"Department\">\n";
                        if ($_SESSION["statusCode"]==0){ $q="select Reduction, CodeOfDepart from department order by Reduction";}
                        else {$q="select Reduction, CodeOfDepart from department where CodeOfFaculty=".$_SESSION["statusCode"]." order by Reduction";}

                        $result = mysql_query($q, $Connection)
                        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
                        $i=0;
                        while ($row = mysql_fetch_object($result)){
                            if (isset($_GET['depart']) && ($_GET['depart'] == $row->CodeOfDepart)) {
                                echo "<OPTION SELECTED VALUE=".$row->CodeOfDepart.">".$row->Reduction."\n";
                            }
                            echo "<OPTION value=".$row->CodeOfDepart.">".$row->Reduction."\n";
                        }
                        echo "</SELECT>\n";
                        mysql_close($Connection);
                        ?>
                    </TR>
                </TABLE>
            </TD></TR></TABLE>
    <BR><br><CENTER>
        <TABLE><TR>
                <?php
                if (!empty($back) && $REQUEST_METHOD=='GET' && $_GET['back']){
                    echo "<INPUT TYPE='HIDDEN' NAME='back' value=\"$back\"></INPUT>\n";
                }
                else{
                    $sh = $_GET['sh'];
                    echo "<TD><INPUT TYPE='RADIO' NAME='shift' VALUE='0' ";
                    if(empty($sh) || $sh==0){echo "CHECKED ";}
                    echo ">Вернутся к списку преподавателей &nbsp;&nbsp;</TD>\n";
                    echo "<TD><INPUT TYPE='RADIO' NAME='shift' VALUE='1'";
                    if(!empty($sh) && $sh==1){echo "CHECKED ";}
                    echo ">Ввести данные о нескольких новых преподавателях</TD>\n";
                }
                ?>
            </TR></TABLE>
        <BR>
        <CENTER>
            <INPUT TYPE='SUBMIT' NAME='OK' VALUE='Добавить преподавателя в список'></INPUT>
        </CENTER>
</FORM>
<HR>
</BODY>
</HTML>
