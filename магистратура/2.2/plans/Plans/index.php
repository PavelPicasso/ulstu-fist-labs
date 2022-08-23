<?php
    include("cfg.php");
    include("Editor/PlanCalculatFunc.php");

    CreateConnection();
?>
<HTML>
<HEAD>
<TITLE>Учебные планы</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<link rel="stylesheet" href="../CSS/Plans.css" type="text/css"></HEAD>
<BODY topmargin="1" leftmargin="5" marginheight="1" marginwidth="5">
<CENTER><B><FONT FACE='Times New Roman Cyr'>Министерство образования и науки Российской Федерации<p>Ульяновский Государственный Технический Университет</FONT></B></CENTER>
<BR>
<CENTER><IMG SRC=img/SchP2.jpg HEIGHT=43 WIDTH=320></CENTER>
<form action='WhatDisciplins.php' method='POST'>
<CENTER>
<TABLE BORDER=0 COLS=2 WIDTH='90%' >
<TR ALIGN=LEFT VALIGN=CENTER>
<TD width='40%'>&nbsp</TD>
<TD width='10%' align='center'><B><I><U>Факультеты:</U></I></B></TD>
<TD width='40%'>&nbsp</TD>
</TR>
<?php
    $name_trans = "Фак_отчеты"; 
    $date_trans = date("Y-m-d h:i:s");
    $time_b = getmicrotime();
        
    $Faculty = FetchArrays("select * from faculty order by FacName");
    $time_e = getmicrotime();
    $time_all = $time_e-$time_b;
    $i = 0;
    foreach ($Faculty as $k => $v) {
        if (($i % 2) == 0)
            echo "<TR>\n<TD><A HREF=\"faculty.php?faculty=$v[CodeOfFaculty]\">$v[FacName]</A></TD>\n";
        else 
            echo "<TD>&nbsp;</TD>\n<TD><A HREF=\"faculty.php?faculty=$v[CodeOfFaculty]\">$v[FacName]</A></TD>\n</TR>\n";
        $i ++;
    }
    if (($i % 2) != 0)
        echo "<TD width='10%'>&nbsp;</TD>\n<TD width='40%'>&nbsp;</TD>\n</TR>\n";
?>
<TR>
<TD>&nbsp;</TD>
<TD align='center'><BR><B><I><U>Отчеты:</U></I></B><BR></TD>
<TD>&nbsp;</TD>
</TR>
<TR>
<TD><a href='departs.php'>Аудиторная нагрузка кафедр</a></TD>
<TD>&nbsp;</TD>
<TD>
<SELECT name='depart'>
<?php
    $time_b = getmicrotime();
    $Departs = FetchArrays("select Reduction, CodeOfDepart from department order by Reduction");
    $time_e = getmicrotime();
    foreach ($Departs as $k => $v)
        echo "<OPTION value='$v[CodeOfDepart]'>$v[Reduction]</OPTION>\n";
?>
</SELECT>
<INPUT TYPE='SUBMIT' NAME='lala' VALUE='Дисциплины кафедры'></INPUT></TD></TR>
<TR>
<TD><a href='departsload.php'>Раскладка аудиторной нагрузки кафедр</a></TD>
<TD>&nbsp;</TD>
<TD>&nbsp;</TD>
</TR>
<TR>
<TD><a href='disciplins.php'>Общий список дисциплин</a></TD>
<TD>&nbsp;</TD>
<TD>&nbsp;</TD>
</TR>
<TR>
<TD><a href='svodtable.php'>Сводные таблицы</a></TD>
<TD>&nbsp;</TD>
<TD>&nbsp;</TD>
</TR>
</TABLE>
</form>
<BR><BR>
<center><font FACE='Times New Roman Cyr' size=4px><b>Наличие рабочих учебных планов</b></font></center>
<BR>
<table  class='ramka' border="0" cellpadding="0" cellspacing="0" width="60%" align="center">
<tr><td cellpadding="0" cellspacing="0">
<TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>

<TR>                                          
    <TD VALIGN=CENTER ALIGN=CENTER><strong style="font-size:15px;">Факультет</strong></TD>
    <TD VALIGN=CENTER ALIGN=CENTER><strong style="font-size:15px;">Специальность</strong></TD>
    <TD VALIGN=CENTER ALIGN=CENTER><strong style="font-size:15px;">Кол-во уч.планов</strong></TD>
</TR>                                                                   
<?php 
        $Faculty = FetchArrays("select * from faculty order by FacName");
        foreach ($Faculty as $k => $v) {
            $Specs = FetchArrays("select distinct MinistryCode, pl.CodeOfSpecial, SpcName from plans pl left join specials sp on sp.CodeOfSpecial=pl.CodeOfSpecial and (pl.FixDate is NULL or pl.FixDate='0000-00-00') where CodeOfFaculty=$v[CodeOfFaculty] order by Type,MinistryCode");
            //$Specs = FetchArrays("select distinct MinistryCode, pl.CodeOfSpecial, SpcName from plans pl left join specials sp on sp.CodeOfSpecial=pl.CodeOfSpecial and (pl.DateArchive is NULL or pl.DateArchive='0000-00-00') where CodeOfFaculty=$v[CodeOfFaculty] order by Type,MinistryCode");
            $i = count($Specs);
            $l = 0;
            if ($i == 0) 
                echo "<TR><TD VALIGN=CENTER ALIGN=CENTER><font style='font-size:15px;'><b>$v[Reduction]\n</b></font></TD>".
                    "<TD>&nbsp;\n</TD><TD VALIGN=CENTER ALIGN=CENTER><font style='font-size:15px;'><b>0\n</b></font></TD></TR>";
            else{
                $x = 1;
                foreach ($Specs as $k => $vs) {
                    echo "<TR>";
                    if ($x == 1){
                        echo "<TD rowspan=$i VALIGN=CENTER ALIGN=CENTER><font style='font-size:15px;'><b>$v[Reduction]\n</b></font></TD>";    
                    }
                    echo "<TD><font style='font-size:15px;'>$vs[MinistryCode] - $vs[SpcName]\n</font></TD>";
                    $Plans = FetchArrays("select count(*) from plans where (FixDate is NULL) and CodeOfSpecial=$vs[CodeOfSpecial]");
                    //$Plans = FetchArrays("select count(*) from plans where (DateArchive is NULL) and CodeOfSpecial=$vs[CodeOfSpecial]");
                    $pl = each($Plans);
                    list($key, $val) = $Plans;
                    list($key2,$val2) = each ($key);
                    echo "<TD VALIGN=CENTER ALIGN=CENTER><font style='font-size:15px;'><b>$val2</b></font></TD></TR>";
                    $x++;
                    $l += $val2;
                }
            }
            echo "<TR><TD colspan=3><i><b>Итого $v[FacName] предоставил $l ";
            if ($l == 1) 
                echo "уч.план";
            elseif ($l>=2 && $l<=4)
                echo "уч.плана";
            else
                echo "уч.планов"; 
            echo "</b></i></td></tr>";
        }


?>
</table>
</TD></TR></TABLE>
<br><br>
<CENTER class="comments">Designed by</CENTER>
<CENTER class="comments"><a href="mailto:i.v.salmova@gmail.com">Salmova Irina</a>, Karpova Anna, Kornyschev Vsevolod</CENTER>
<?php
    $time_all += $time_e-$time_b;
    $id_sess = session_id();
    mysql_query("Insert into logs (name_trans, id_sess, date_trans, time_trans) values ('$name_trans', '$id_sess', '$date_trans', '$time_all')")
               or die("Unable to execute query:".mysql_errno().": ".mysql_error());
    mysql_close($Connection);
?>
</BODY>
</HTML>