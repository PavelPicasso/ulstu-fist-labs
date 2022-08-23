<?
    include("../Plans/cfg.php");
    include("../Plans/Editor/PlanCalculatFunc.php");
    set_time_limit(60*3);   
    CreateConnection();

    $f = fopen("index.html", "w");
    ob_start();
    ?>

    <HTML>
    <HEAD>
    <TITLE>Учебные планы</TITLE>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">              
    <link rel="stylesheet" href="Abit.css" type="text/css"></HEAD>
    <BODY topmargin=1 leftmargin=5 marginheight=1 marginwidth=5>
    <center><font FACE='Times New Roman Cyr' size=4px><b>Объем занятий учебных планов вуза</b></font></center><BR>                               
    <table  class='ramka' border=0 cellpadding=0 cellspacing=0 width=60% align=center><tr><td cellpadding=0 cellspacing=0>                  
    <TABLE  class ='table_abit' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'><TR>                                                 
    <TD VALIGN=CENTER ALIGN=CENTER><strong style="font-size:15px;">Факультет</strong></TD>                                                  
    <TD VALIGN=CENTER ALIGN=CENTER><strong style="font-size:15px;">Специальность</strong></TD>   
    <TD VALIGN=CENTER ALIGN=CENTER><strong style="font-size:15px;">Квалификация</strong></TD></TR>   
<?
    $html = ob_get_contents();
    ob_end_clean();

    fwrite($f,$html);
    $Faculty = FetchArrays("SELECT * FROM faculty ORDER BY FacName"); 
    foreach ($Faculty as $k => $v) {                                  //1
            $Specs = FetchArrays("SELECT s.CodeOfSpecial, MinistryCode, SpcName, Type, CodeOfPlan FROM specials as s LEFT JOIN plans as p ON s.CodeOfSpecial=p.CodeOfSpecial WHERE CodeOfFaculty=$v[CodeOfFaculty] AND s.CodeOfSpecial>62 AND (DateArchive is NULL) ORDER BY Type,MinistryCode");
            $i = count($Specs);
            $l = 0;
            if ($i == 0) 
                fwrite($f,"<TR><TD VALIGN=CENTER ALIGN=CENTER><font style='font-size:15px;'><b>$v[Reduction]\n</b></font></TD>".
                    "<TD>&nbsp;</TD><TD>&nbsp;</TD></TR>\n");
            else{                                                     //2
                $x = 1;
                foreach ($Specs as $k => $vs) {                       //3
                    fwrite($f,"<TR>\n");
                    if ($x == 1){
                        fwrite($f,"<TD rowspan=$i VALIGN=CENTER ALIGN=CENTER><font style='font-size:15px;'><b>$v[Reduction]\n</b></font></TD>\n");    
                    }                                                 //-3
                    if ($vs[Type]=='enginer') $tmp = 'инженер';
                    elseif ($vs[Type]=='bakalavr') $tmp = 'бакалавр';
                    elseif ($vs[Type]=='magistr') $tmp = 'магистр';

                        $PlanData = GetPlanInfo($vs[CodeOfPlan]);
                        $tmp1 = ""; $tmp1_a = "";
                        $tmp2 = ""; $tmp2_a = "";
                        if (substr($PlanData["PlnName"],0,25)=="План подготовки инженеров" || substr($PlanData["PlnName"],0,28) == "План подготовки специалистов"){ //5
                            if (strpos($PlanData["PlnName"],"ТАПд")) {$tmp1 .=".ТАПд "; $tmp1_a .="_tapd";}
                            if (strpos($PlanData["PlnName"],"ТПМд")) {$tmp1 .=".ТПМд "; $tmp1_a .="_tpmd";}
                            if (strpos($PlanData["PlnName"],"САПР")) {$tmp1 .=".САПР "; $tmp1_a .="_sapr";}
                            if (strpos($PlanData["PlnName"],"-в")) {$tmp2 .="(очно-заочная ф./об."; $tmp2_a .="v";}
                            if (strpos($PlanData["PlnName"],"-з")) {$tmp2 .="(заочная ф./об."; $tmp2_a .="z";}
                            if (strpos($PlanData["PlnName"],"у")){    //6
                                if ($tmp2=="") $tmp2 .="(ускоренники)";
                                else $tmp2 .=",ускоренники)";
                                $tmp2_a .="y";
                            }
                            else if ($tmp2!="") $tmp2 .=")";                         //-6


                        }                                             //-5
                        if (substr($PlanData["PlnName"],0,25)=="План подготовки магистров"){
                            if (strpos($PlanData["PlnName"],"_05")) {$tmp1 .=".05"; $tmp1_a .="_05";}
                            if (strpos($PlanData["PlnName"],"_08")) {$tmp1 .=".08"; $tmp1_a .="_08";}
                        }
                     

                    fwrite($f,"<TD><a href='".$vs[MinistryCode]."".$tmp1_a."".$tmp2_a."_abit.html' style='font-size:15px;'>".$vs[MinistryCode]." - ".$vs[SpcName]." ".$tmp1." ".$tmp2."</a></TD><TD><center>".$tmp."</center></TD>\n</TR>\n");

                    $f_abit = fopen($vs[MinistryCode]."".$tmp1_a."".$tmp2_a."_abit.html","w");
                    fwrite($f_abit,"<HTML><HEAD>\n<TITLE>Объем занятий".$PlanData["PlanSpcCode"]."&nbsp;&nbsp;".$PlanData["PlnName"]."</TITLE>\n");
                    $html = "<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=windows-1251\">\n";
                    $html .= "<link rel=\"stylesheet\" href=\"Abit.css\" type=\"text/css\"></HEAD>\n";
                    $html .= "<BODY topmargin=1 leftmargin=1 marginheight=1 marginwidth=1>\n";
                    $html .= "<br>\n<h1>Объем занятий<br>\n";
                    $html .= "учебного плана"; 
                    if ($vs[Type]=='enginer') $html .= " специальности</h1>\n";
                    else  $html .= " направления</h1>\n";
                    fwrite($f_abit,$html);
                    fwrite($f_abit,"<h2>".$PlanData["PlanSpcCode"]."&nbsp;&nbsp;".$PlanData["PlanSpcName"]."</h2>\n");
                    ob_start();
?>
                    <table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center">
                    <tr><td cellpadding="0" cellspacing="0">
                    <TABLE  class='table_abit' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=3 WIDTH='100%'>
                    <TR>
                        <th class="h2"><strong>&nbsp;</strong></td>
                        <th class="h2"><strong>Дисциплина</strong></td>
                        <th class="h2"><strong>Аудиторных<BR>часов</strong></td>
                        <th class="h2"><strong>Самостоятельных<BR>часов</strong></td>
                        <th class="h2"><strong>Всего</strong></td>
                    </TR>   
<?

                    $html = ob_get_contents();
                    ob_end_clean();
                    fwrite($f_abit,$html);
                    list ($FirstKurs, $LastKurs, $FirstTerm, $LastTerm) = GetPeriod($vs[CodeOfPlan], "Y");
                    $TSem = array();
                    for($i=$FirstTerm; $i<=$LastTerm; $i++) 
                        $TSem[$i] = TeachWeek($vs[CodeOfPlan], $i);

                    $PlanDiscips = PlanDiscips($vs[CodeOfPlan],$TSem);  

                    foreach ($PlanDiscips["Cicles"] as $k => $v) {    //7
                        //Вывод всех циклов
                        $DisStr = "<TR><TD  bgcolor='white'><B>".$v["CicReduction"]."</B></FONT></TD>\n";
                        $DisStr .= "<TD bgcolor='white'><B>".$v["CicName"]."</B></FONT></TD>\n";
                        $DisStr .= "<TD bgcolor='white' align='center'><B>".$v["AudH"]."</B></FONT></TD>\n";
                        $DisStr .= "<TD bgcolor='white' align='center'><B>".$v["SelfH"]."</B></FONT></TD>\n";
                        $DisStr .= "<TD bgcolor='white' align='center'><B>".$v["AllH"]."</B></FONT></TD>\n";
                        $DisStr .= "</TR>\n";
 
                        fwrite($f_abit,$DisStr);
 
                        foreach ($v["UnderCicles"] as $ku => $vu) {   //8
                            if ($vu["UndCicReduction"] != "") {       //9
 
                                // Вывод строки подцикла
                                $DisStr = "<TR><TD  bgcolor='white'><B>$v[CicReduction].$vu[UndCicReduction]</B></FONT></TD>\n";
                                $DisStr .= "<TD bgcolor='white'><B>".$vu["UndCicName"]."</B></FONT></TD>\n";
                                $DisStr .= "<TD bgcolor='white' align='center'><B>".$vu["AudH"]."</B></FONT></TD>\n";
                                $DisStr .= "<TD bgcolor='white' align='center'><B>".$vu["SelfH"]."</B></FONT></TD>\n";
                                $DisStr .= "<TD bgcolor='white' align='center'><B>".$vu["AllH"]."</B></FONT></TD>\n";
                                $DisStr .= "</TR>\n";
                
                                fwrite($f_abit, $DisStr);
                            }                                         //-9
                            foreach ($vu["Discips"] as $kd => $vd) {  //10
 
                                //формируем строку для вывода
                                $DisStr = "<TR><TD  bgcolor='white'>$v[CicReduction]";
 
                                if ($vu["UndCicReduction"] != "")
                                    $DisStr .= ".$vu[UndCicReduction]";
                                if ($vd["UndCicCode"] != "")
                                    $DisStr .= ".$vd[UndCicCode]";
 
                                $DisStr .= "</FONT></TD>\n";
 
                                $DisStr .= "<TD bgcolor='white'>".$vd["DisName"]."</FONT></TD>\n";
                                $DisStr .= "<TD bgcolor='white' align='center'>".$vd["AudH"]."</FONT></TD>\n";
                                $DisStr .= "<TD bgcolor='white' align='center'>".$vd["SelfH"]."</FONT></TD>\n";
                                $DisStr .= "<TD bgcolor='white' align='center'>".$vd["AllH"]."</FONT></TD>\n";
                                $DisStr .= "</TR>\n";
 
                                fwrite($f_abit,$DisStr);
                            } //Вывод дисциплин*                          //-10
                        } //Вывод подциклов                               //-8
                    } //Вывод циклов                                      //-7
 
                    //Вывод итоговых данных
                    $DisStr = "<TR><TD>&nbsp;</TD>";
                    $DisStr .= "<TD bgcolor='white'><B>ВСЕГО</B></FONT></TD>\n";
                    $DisStr .= "<TD bgcolor='white' align='center'><B>".$PlanDiscips["AudH"]."</B></FONT></TD>\n";
                    $DisStr .= "<TD bgcolor='white' align='center'><B>".$PlanDiscips["SelfH"]."</B></FONT></TD>\n";
                    $DisStr .= "<TD bgcolor='white' align='center'><B>".$PlanDiscips["AllH"]."</B></FONT></TD>\n";
                    $DisStr .= "</TR>\n";

                    fwrite($f_abit,$DisStr);

                    fwrite($f_abit,"</TABLE></TD></TR></TABLE></BODY></HTML>");
                    fclose($f_abit);
                    echo "<a href='".$vs[MinistryCode]."".$tmp1_a."".$tmp2_a."_abit.html'>".$vs[MinistryCode]."".$tmp1_a."".$tmp2_a."_abit.html</a><br>";
                    
                    $x++;
                    $l += $val2;
                }                                                //-2
            }                                                    //-1
        }                                                        //

    fwrite($f,"</table>\n</BODY>\n</HTML>");
    fclose($f);
    echo "<a href='index.html'>index.html</a>";

?>