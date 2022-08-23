<?php
	include("../PlanCalculatFunc.php");
	CreateConnection();
    if (!empty($_GET['plan'])) $plan=$_GET['plan'];
    if (!empty($_POST['plan'])) $plan=$_POST['plan'];
    if (empty($plan)) {
        $message = "Plan is empty";
        include("../alert.php");
        exit;
    }
    include("../Display/StartPage.php");
    include("../Display/DisplayFunc.php");

    if (empty($cicle)) $cicle=0;
    if (empty($discip)) $discip=0;

    $result = mysql_query("select PlnName, SpzName, SpcName, DirName, specializations.MinistryCode as SpzCode, specials.MinistryCode as SpcCode, directions.MinistryCode as DirCode  from plans LEFT JOIN specials ON specials.CodeOfSpecial=plans.CodeOfSpecial LEFT JOIN specializations ON specializations.CodeOfSpecialization=plans.CodeOfSpecialization  LEFT JOIN directions ON directions.CodeOfDirect=plans.CodeOfDirect where CodeOfPlan=$plan",$Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
    $row = mysql_fetch_array($result);

    if (!empty($row["SpzCode"]))
      $PlTitle = "$row[SpzCode]&nbsp;&nbsp;&nbsp;$row[SpzName]&nbsp;&nbsp;&nbsp;";
    elseif (!empty($row["SpcCode"]))
      $PlTitle =  "$row[SpcCode]&nbsp;&nbsp;&nbsp;$row[SpcName]&nbsp;&nbsp;&nbsp;";
    elseif (!empty($row["DirCode"]))
      $PlTitle =  "$row[DirCode]&nbsp;&nbsp;&nbsp;$row[DirName]&nbsp;&nbsp;&nbsp;";

    $PlTitle .= $row["PlnName"];

    DisplayPageTitle("../downEd.php?plan=$plan","Редактирование учебного плана",$PlTitle);

?>
<form name=fed method=post action="">
<table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center" width="100%">
<tr><td>
<TABLE  class='table' BORDER=1 ALIGN=CENTER CELLSPACING=1 CELLPADDING=3 WIDTH='100%'>
<TR ALIGN='CENTER' VALIGN='MIDDLE'>
<TH ROWSPAN="2">&nbsp;</TH>
<TH width=250 ROWSPAN="2"><FONT SIZE='-2' COLOR='#1F4D1C'>Название дисциплины</FONT></TH>
<TH width=60  ROWSPAN="2"><FONT SIZE='-2' COLOR='#1F4D1C'>Код цикла</FONT></TH>
<TH width=60  ROWSPAN="2"><FONT SIZE='-2' COLOR='#1F4D1C'>Код компонента</FONT></TH>
<TH width=60  ROWSPAN="2"><FONT SIZE='-2' COLOR='#1F4D1C'>Номер в компоненте</FONT></TH>
<!--
<TH width=60 ROWSPAN="2"><FONT  SIZE='-2' COLOR='#1F4D1C'>Номер семестра</FONT></TH>
<TH width=30 ROWSPAN="2"><FONT SIZE='-2' COLOR='#1F4D1C'>Экз.</FONT></TH>
<TH width=30 ROWSPAN="2"><FONT SIZE='-2' COLOR='#1F4D1C'>Зач.</FONT></TH>
<TH width=30 ROWSPAN="2"><FONT SIZE='-2' COLOR='#1F4D1C'>Курс. пр.</FONT></TH>
<TH width=30 ROWSPAN="2"><FONT SIZE='-2' COLOR='#1F4D1C'>Курс. раб.</FONT></TH>
<TH width=30 ROWSPAN="2"><FONT SIZE='-2' COLOR='#1F4D1C'>РГР</FONT></TH>
<TH width=30 ROWSPAN="2"><FONT SIZE='-2' COLOR='#1F4D1C'>Реф.</FONT></TH>
-->
<TH width=150 ROWSPAN="1" COLSPAN="3"><FONT SIZE='-2' COLOR='#1F4D1C'>Часов в неделю</FONT></TH>
<TH width=50  ROWSPAN="2"><FONT SIZE='-2' COLOR='#1F4D1C'>Общ. объем часов</FONT></TH>
<TH width=30  ROWSPAN="2"><FONT SIZE='-2' COLOR='#1F4D1C'>Учит. при подсч.</FONT></TH>
<!--<TH width=100 ROWSPAN="2"><FONT SIZE='-2' COLOR='#1F4D1C'>Кафедра</FONT></TH>-->
</TR>

<TR>
<TH width=50 ROWSPAN="1"><FONT SIZE='-2' COLOR='#1F4D1C'>Лекц. </FONT></TH>
<TH width=50 ROWSPAN="1"><FONT SIZE='-2' COLOR='#1F4D1C'>Лаб. </FONT></TH>
<TH width=50 ROWSPAN="1"><FONT SIZE='-2' COLOR='#1F4D1C'>Практ.</FONT></TH>
</TR>
<?php
    $Departs = FetchArrays("select Reduction, CodeOfDepart from department order by Reduction");
    $UndCicles = FetchArrays("select  CodeOfUnderCicle, UndCicReduction from undercicles order by CodeOfUnderCicle");
    $Cicles = FetchArrays("select  CodeOfCicle, CicReduction from cicles order by CodeOfCicle");
    $CurrentCicles = FetchArrays("select distinct cicles.CodeOfCicle, cicles.CicName, cicles.CicReduction from cicles, schedplan where cicles.CodeOfCicle=schedplan.CodeOfCicle and schedplan.CodeOfPlan='$plan' order by cicles.CodeOfCicle");
    $CurrentCicles[] = array("CicName"=>"Без раздела", "CodeOfCicle"=>"0");

    list ($FirstKurs, $LastKurs, $FirstTerm, $LastTerm) = GetPeriod($plan, "Y");

    include ("../Display/ValidationForm.php");
    foreach ($CurrentCicles as $k => $v) {
        echo "<TR>";
        if ($cicle == $v["CodeOfCicle"])
            echo "<TD align=\"center\"><img src=\"../img/ActCicle.gif\"  border=0 height=18 width=18 hspace=0 vspace=0 alt=\"Активный раздел плана\"></TD>";
        else
            echo "<TD>&nbsp;</TD>";
        echo "<TD COLSPAN=4><a href=\"PlanEd.php?plan=$plan&cicle=$v[CodeOfCicle]\"><FONT SIZE='-1'><B>$v[CicName]<B></FONT></a></TD>\n";

        $Summs = CalcPlanSumms($plan, $v["CodeOfCicle"]);
        echo "<TD align='center'><FONT SIZE='-1'>$Summs[LecInW]</FONT></TD>\n";
        echo "<TD align='center'><FONT SIZE='-1'>$Summs[LabInW]</FONT></TD>\n";
        echo "<TD align='center'><FONT SIZE='-1'>$Summs[PractInW]</FONT></TD>\n";
        echo "<TH><FONT SIZE='-1'>$Summs[AllH]</FONT></TD>\n<TD>&nbsp;</TD>\n</TR>\n";
        if ($cicle == $v["CodeOfCicle"]) {
            //Выводим все дисциплины раздела
            $Disciplines = PlanDisciplines($plan, $cicle);
            foreach ($Disciplines as $kd => $vd ){
                echo "<TR>\n";
                echo "<TD align=\"center\"><INPUT TYPE='CHECKBOX' NAME='DelSchPlan[$vd[CodeOfSchPlan]]' VALUE=\"$vd[CodeOfSchPlan]\"></TD>\n\n";
                echo "<TD><FONT SIZE='-1'><A href=\"PlanEd.php?plan=$plan&cicle=$v[CodeOfCicle]&discip=$vd[CodeOfSchPlan]\">$vd[DisName]</A></FONT></TD>\n";
                echo "<TD align=\"center\"><SELECT NAME=\"CodeOfCicle[$vd[CodeOfSchPlan]]\" onChange=\"FillArrChange('Discip','$vd[CodeOfSchPlan]')\">";
                foreach ($Cicles as $kc => $vc) {
                    echo "<OPTION VALUE=$vc[CodeOfCicle]";
                    if ($vd["CodeOfCicle"] == $vc["CodeOfCicle"])
                        echo " SELECTED";
                    echo ">$vc[CicReduction]";
                }
                echo "</SELECT></TD>";
                echo "<TD align=\"center\"><SELECT NAME=\"CodeOfUndCicle[$vd[CodeOfSchPlan]]\" onChange=\"FillArrChange('Discip','$vd[CodeOfSchPlan]')\">";
                foreach ($UndCicles as $kc => $vc) {
                    echo "<OPTION VALUE=$vc[CodeOfUnderCicle]";
                    if ($vd["CodeOfUndCicle"] == $vc["CodeOfUnderCicle"])
                        echo " SELECTED";
                    echo ">$vc[UndCicReduction]";
                } 
                echo "</SELECT></TD>";
                echo "<TD align=\"center\"><INPUT TYPE=TEXT NAME=\"UndCicCode[$vd[CodeOfSchPlan]]\"  SIZE=3 MAXLENGTH=10 VALUE=\"$vd[UndCicCode]\" onChange=\"FillArrChange('Discip','$vd[CodeOfSchPlan]')\"></TD>\n";
                echo "<TD colspan=\"3\">&nbsp;</TD>";
                echo "<TD align=\"center\"><INPUT TYPE=TEXT NAME=\"AllH[$vd[CodeOfSchPlan]]\"  SIZE=3 MAXLENGTH=10 VALUE=\"$vd[AllH]\" onChange=\"Validator(this,'Discip'); FillArrChange('Discip','$vd[CodeOfSchPlan]')\"></TD>\n";
                echo "<TD align=\"center\"><INPUT TYPE='checkbox' NAME=\"ToCount[$vd[CodeOfSchPlan]]\" VALUE='1' onChange=\"FillArrChange('Discip','$vd[CodeOfSchPlan]')\"";
                if ($vd["ToCount"]!=0) 
                  echo " CHECKED";
                echo    "></TD>";
                echo "</TR>";
                if($vd["CodeOfSchPlan"] == $discip) {
                    echo "<TR>\n";
                    echo "<TD>&nbsp;</TD>\n";
                    echo "<TD colspan='9'>";
                    echo "<TABLE  class='distable' BORDER='0' ALIGN=CENTER CELLSPACING=0 CELLPADDING=1 WIDTH='100%'>";
                    echo "<TR>\n";
                    echo "<TH ROWSPAN='2'><FONT  SIZE='-2' COLOR='#1F4D1C'>Уд.</FONT></TH>\n";
                    echo "<TH width=60 ROWSPAN='2'><FONT  SIZE='-2' COLOR='#1F4D1C'>Номер семестра</FONT></TH>\n";
                    echo "<TH width=30 ROWSPAN='2'><FONT SIZE='-2' COLOR='#1F4D1C'>Экз.</FONT></TH>\n";
                    echo "<TH width=30 ROWSPAN='2'><FONT SIZE='-2' COLOR='#1F4D1C'>Зач.</FONT></TH>\n";
                    echo "<TH width=30 ROWSPAN='2'><FONT SIZE='-2' COLOR='#1F4D1C'>Курс. пр.</FONT></TH>\n";
                    echo "<TH width=30 ROWSPAN='2'><FONT SIZE='-2' COLOR='#1F4D1C'>Курс. раб.</FONT></TH>\n";
                    echo "<TH width=30 ROWSPAN='2'><FONT SIZE='-2' COLOR='#1F4D1C'>РГР</FONT></TH>\n";
                    echo "<TH width=30 ROWSPAN='2'><FONT SIZE='-2' COLOR='#1F4D1C'>Реф.</FONT></TH>\n";
                    echo "<TH width=150 ROWSPAN='1' COLSPAN='3'><FONT SIZE='-2' COLOR='#1F4D1C'>Часов в семестр</FONT></TH>\n";
                    echo "<TH width=150 ROWSPAN='1' COLSPAN='3'><FONT SIZE='-2' COLOR='#1F4D1C'>Часов в неделю</FONT></TH>\n";
                    echo "<TH width=100 ROWSPAN='2'><FONT SIZE='-2' COLOR='#1F4D1C'>Кафедра</FONT></TH>\n";
                    echo "<TH width=100 ROWSPAN='2'>&nbsp;</TH>\n";
                    echo "</TR>\n";
                    echo "<TR>\n";
                    echo "<TH width=50 ROWSPAN='1'><FONT SIZE='-2' COLOR='#1F4D1C'>Лекц. </FONT></TH>\n";
                    echo "<TH width=50 ROWSPAN='1'><FONT SIZE='-2' COLOR='#1F4D1C'>Лаб. </FONT></TH>\n";
                    echo "<TH width=50 ROWSPAN='1'><FONT SIZE='-2' COLOR='#1F4D1C'>Практ.</FONT></TH>\n";
                    echo "<TH width=50 ROWSPAN='1'><FONT SIZE='-2' COLOR='#1F4D1C'>Лекц. </FONT></TH>\n";
                    echo "<TH width=50 ROWSPAN='1'><FONT SIZE='-2' COLOR='#1F4D1C'>Лаб. </FONT></TH>\n";
                    echo "<TH width=50 ROWSPAN='1'><FONT SIZE='-2' COLOR='#1F4D1C'>Практ.</FONT></TH>\n";
                    echo "</TR>\n";

                    foreach ($vd["DiscipItems"] as $kdi => $vdi) 
                    if($vd["CodeOfSchPlan"] == $discip) {
                        echo "<TR>\n";
                        echo "<TD align=\"center\"><INPUT TYPE='CHECKBOX' NAME='DelSchPlanItem[$vdi[CodeOfSchPlanItem]]' VALUE=\"$vdi[CodeOfSchPlanItem]\"></TD>\n\n";
                        echo "<TD align=\"center\"><SELECT NAME=\"NumbOfSemestr[$vdi[CodeOfSchPlanItem]]\" onChange=\"FillArrChange('','$vdi[CodeOfSchPlanItem]')\">\n";
                        for ($i=$FirstTerm; $i<=$LastTerm; $i++){
                            echo "<OPTION VALUE=$i";
                            if ($vdi["NumbOfSemestr"] == $i)
                                echo " SELECTED";
                        echo ">$i";
                        } 
                        echo "</SELECT></TD>\n";
                        echo "<TD><INPUT TYPE='checkbox' NAME=\"Exam[$vdi[CodeOfSchPlanItem]]\" VALUE='1' onChange=\"FillArrChange('','$vdi[CodeOfSchPlanItem]')\"";
                        if ($vdi["Exam"])                                                                           
                            echo " CHECKED";
                        echo "></TD>\n";
                    
                        echo "<TD><INPUT TYPE='checkbox' NAME=\"Test[$vdi[CodeOfSchPlanItem]]\" VALUE='1' onChange=\"FillArrChange('','$vdi[CodeOfSchPlanItem]')\"";
                        if ($vdi["Test"]) 
                            echo " CHECKED";
                        echo "></TD>\n";
                    
                        echo "<TD><INPUT TYPE='checkbox' NAME=\"KursPrj[$vdi[CodeOfSchPlanItem]]\" VALUE='1' onChange=\"FillArrChange('','$vdi[CodeOfSchPlanItem]')\"";
                        if ($vdi["KursPrj"]) 
                            echo " CHECKED";
                        echo "></TD>\n";
                    
                        echo "<TD><INPUT TYPE='checkbox' NAME=\"KursWork[$vdi[CodeOfSchPlanItem]]\" VALUE='1' onChange=\"FillArrChange('','$vdi[CodeOfSchPlanItem]')\"";
                        if ($vdi["KursWork"]) 
                            echo " CHECKED";
                        echo "></TD>\n";
                    
                        echo "<TD align=\"center\"><INPUT TYPE=TEXT NAME=\"RGR[$vdi[CodeOfSchPlanItem]]\"  SIZE=3 MAXLENGTH=10 VALUE=\"$vdi[RGR]\" onChange=\"Validator(this,''); FillArrChange('','$vdi[CodeOfSchPlanItem]')\"></TD>\n";
                        echo "<TD align=\"center\"><INPUT TYPE=TEXT NAME=\"Synopsis[$vdi[CodeOfSchPlanItem]]\"  SIZE=3 MAXLENGTH=10 VALUE=\"$vdi[Synopsis]\" onChange=\"Validator(this,''); FillArrChange('','$vdi[CodeOfSchPlanItem]')\"></TD>\n";
                        echo "<TD align=\"center\"><INPUT TYPE=TEXT NAME=\"LectSem[$vdi[CodeOfSchPlanItem]]\"  SIZE=3 MAXLENGTH=10 VALUE=\"$vdi[LectSem]\" onChange=\"Validator(this,''); FillArrChange('','$vdi[CodeOfSchPlanItem]')\"></TD>\n";
                        echo "<TD align=\"center\"><INPUT TYPE=TEXT NAME=\"LabSem[$vdi[CodeOfSchPlanItem]]\"  SIZE=3 MAXLENGTH=10 VALUE=\"$vdi[LabSem]\" onChange=\"Validator(this,''); FillArrChange('','$vdi[CodeOfSchPlanItem]')\"></TD>\n";
                        echo "<TD align=\"center\"><INPUT TYPE=TEXT NAME=\"PractSem[$vdi[CodeOfSchPlanItem]]\"  SIZE=3 MAXLENGTH=10 VALUE=\"$vdi[PractSem]\" onChange=\"Validator(this,''); FillArrChange('','$vdi[CodeOfSchPlanItem]')\"></TD>\n";
                        echo "<TD align=\"center\"><INPUT TYPE=TEXT NAME=\"LectInW[$vdi[CodeOfSchPlanItem]]\"  SIZE=3 MAXLENGTH=10 VALUE=\"$vdi[LectInW]\" onChange=\"Validator(this,''); FillArrChange('','$vdi[CodeOfSchPlanItem]')\"></TD>\n";
                        echo "<TD align=\"center\"><INPUT TYPE=TEXT NAME=\"LabInW[$vdi[CodeOfSchPlanItem]]\"  SIZE=3 MAXLENGTH=10 VALUE=\"$vdi[LabInW]\" onChange=\"Validator(this,''); FillArrChange('','$vdi[CodeOfSchPlanItem]')\"></TD>\n";
                        echo "<TD align=\"center\"><INPUT TYPE=TEXT NAME=\"PractInW[$vdi[CodeOfSchPlanItem]]\"  SIZE=3 MAXLENGTH=10 VALUE=\"$vdi[PractInW]\" onChange=\"Validator(this,''); FillArrChange('','$vdi[CodeOfSchPlanItem]')\"></TD>\n";
                        echo "<TD align=\"center\"><SELECT NAME=\"CodeOfDepart[$vdi[CodeOfSchPlanItem]]\" onChange=\"FillArrChange('','$vdi[CodeOfSchPlanItem]')\">";
                        foreach ($Departs as $kc => $vc) {
                            echo "<OPTION VALUE=$vc[CodeOfDepart]";
                            if ($vdi["CodeOfDepart"] == $vc["CodeOfDepart"])
                                echo " SELECTED";
                            echo ">$vc[Reduction]\n";
                        } 
                        echo "</SELECT></TD>\n";
                        echo "<TD align=\"center\"><INPUT TYPE=BUTTON VALUE=\"Редактировать\" onClick=\"self.location='../Blending/BlenPlanItem.php?plitem=$vdi[CodeOfSchPlanItem]'\"></TD>\n";
                        echo "</TR>\n";
                    }//Вывод одной дисциплины
                    echo "</TABLE></TD></TR>\n";
                }
            }
        }
    }
?>

</TABLE>


</td>
</tr>
</table>
<BR>
<TABLE BORDER=0 ALIGN=CENTER>
<TR>
<TD><CENTER><INPUT TYPE='SUBMIT' NAME='Edit' VALUE='Внести изменения'
            onClick="fed.action='DoEditDisInP.php'"></INPUT></CENTER></TD>
<TD><CENTER><INPUT TYPE='RESET' NAME='reset' VALUE='Отменить изменения'
            onClick="RefreshRecArr()"></INPUT></CENTER></TD>
</TR>

<TR>
<TD><CENTER><input type=submit          VALUE='Добавить в план новую запись' 
            onClick="fed.action='AddRecIPlan.php'"> 
</CENTER></TD>
<TD><CENTER><INPUT TYPE='SUBMIT' NAME='Delete' VALUE='Удалить помеченные записи' 
            onClick="fed.action=Deleting()"></INPUT></CENTER></TD>
</TR>

<!--<TR>
<TD colspan="2"><CENTER><INPUT TYPE='SUBMIT' NAME='Copy' VALUE='Копировать помеченные записи' 
            onClick="fed.action='DoCopyDisInP.php'"></INPUT></CENTER></TD>
</TR> -->

</TABLE>
<?php
    echo "<input type='hidden' name='records' value=''>\n";
    echo "<input type='hidden' name='plan' value='$plan'>\n";
    echo "<input type='hidden' name='discip' value='$discip'>\n";
    echo "<input type='hidden' name='NumOfChangeRec' value=''>\n";
    echo "<input type='hidden' name='NumOfChangeDiscip' value=''>\n";
    echo "<input type='hidden' name='cicle' value='$cicle'>\n<hr>\n\n";
    include("../Display/FinishPage.php");
?>