<?php

    include("../PlanCalculatFunc.php");
    include("../Display/DisplayFunc.php");
    CreateConnection();
    include("../Display/StartPage.php");
    if (!empty($_GET['plan'])) $plan = $_GET['plan'];
    if (!empty($_POST['plan'])) $plan = $_POST['plan'];
    DisplayPageTitle("","Получение выписки дисциплин");
 
    if (!(isset($plan)))  
        return;


    $PlanInfo = GetPlanInfo($plan);

    $spcCode=$PlanInfo["PlanSpcCode"];

    //Имя файла
    $filename="../../../Exported/".$spcCode;
    $i=0;
    while ( file_exists ($filename."_".$i.".rtf")){
     $i++;
     }
    $PrintName = $spcCode."_".$i.".rtf";
    $filename .= "_".$i.".rtf";
    /* Освобождение resultset */
    //Создаем файл
    $fl = fopen ($filename,"w")
      or die("Невозможно создать файл  $filename.<BR>");
    include ("ExportFunc.php");
 
    $head = "SubscrParts/StartPage.rtf";
    $start = "SubscrParts/StartTable.rtf";
    $div = "SubscrParts/DivTable.rtf"; 
 
    //Заголовок файла формата rtf
    FileCopy($fl,$head);
    fwrite($fl,"Специальность $spcCode");
    FileCopy($fl,$start);

    //Получаем список дисциплин
    $result = mysql_query("
          select distinct s.CodeOfSchPlan, disciplins.DisName, s.CodeOfDiscipline, 
          s.AllH, s.UndCicCode, s.CodeOfUndCicle, s.CodeOfCicle 
          from schedplan as s, schplanitems as si, disciplins where 
          s.CodeOfDiscipline=disciplins.CodeOfDiscipline and 
          s.CodeOfSchPlan = si.CodeOfSchPlan and 
          s.CodeOfPlan=$plan 
          and (si.Test>0 or si.Exam>0) and s.AllH>0 
          order by s.CodeOfCicle, s.CodeOfUndCicle, s.UndCicCode 
          ",$Connection)
       or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
    $Disciplins = array();   
    $DisCodes = array();   //Массив для уже встречавшихся кодов дисциплин повыбору
    $All = 0;
    $StrCount = 0;
    while ($row=mysql_fetch_object($result)){

        $Grade = "экзамен";
       
        $resultExam = FetchFirstRow("select CodeOfSchPlanItem from schplanitems where CodeOfSchPlan=".$row->CodeOfSchPlan." and Exam>0",$Connection);
        if (empty($resultExam))
             $Grade = "зачет";
       
        $Dis = $row->DisName;
       
        if((strlen($row->UndCicCode)>2) && ($row->CodeOfUndCicle==4)){
           $NewDC=substr($row->UndCicCode,0,2);
           $ToCount = 1;
           while ($DCode = each($DisCodes)){
              //если подсчитывали то заносим соответствующее значение
              if ((strcmp($NewDC, $DCode[1][0]) == 0)&&
                ($row->CodeOfCicle == $DCode[1][1])){ $ToCount = 0;}
           }
           //Если таких дисциплин еще не встречалось то 
           //сохраняем данные о ней и суммируем
           if ($ToCount){
              $DisCodes[] = array($NewDC, $row->CodeOfCicle); 
              $Disciplins[] = array($Dis, $row->AllH, $Grade); 
              $All += $row->AllH;
              $StrCount ++;
           }else{
              $Disciplins[$StrCount-1][0] .= "/".$Dis;
           }
        }else{
            $Disciplins[] = array($Dis, $row->AllH, $Grade); 
            $All += $row->AllH;
            $StrCount ++;
        }
       
    }
    $i=1;
    while ($Discip = each($Disciplins)){
       fwrite($fl,"{\\pntext\\pard\\plain\\intbl\\f29\\fs18 \\hich\\af29\\dbch\\af0\\loch\\f29 $i.\\tab}\n");
       fwrite($fl,"\\pard \\ql \\fi-360\\li360\\ri0\\keepn\\widctlpar\\intbl\\jclisttab\\tx360\n");
       fwrite($fl,"{\\*\\pn \\pnlvlbody\\ilvl0\\ls8\\pnrnot0\\pndec\\pnstart1\\pnindent360\\pnsp120\\pnhang{\\pntxta .}}\n");
       fwrite($fl,"\\aspalpha\\aspnum\\faauto\\ls8\\adjustright\\rin0\\lin360\n");
       fwrite($fl,"{\\f29\\fs18 ".$Discip[1][0]."\\cell }\\pard \\ql \\li0\\ri0\\keepn\\widctlpar\\intbl\n");
       fwrite($fl,"{\\*\\pn \\pnlvlcont\\ilvl0\\ls0\\pnrnot0\\pndec }\n");
       fwrite($fl,"\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\n");
       fwrite($fl,"{\\fs18 ".$Discip[1][1]."\\cell }{\\f29\\fs18 ".$Discip[1][2]."\\cell }\n");
       FileCopy($fl,$div);
       $i++;
    }
    //Вывод курсовых работ и проектов
    fwrite($fl,"\\pard\\plain \\s2\\ql \\li0\\ri0\\keepn\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\outlinelevel1\\adjustright\\rin0\\lin0 \\fs18\\lang1049\\langfe1049\\cgrid\\langnp1049\\langfenp1049 \n");
    fwrite($fl,"{\\b\\f29\\fs18 КУРСОВЫЕ ПРОЕКТЫ И РАБОТЫ\\cell }\\pard \\ql \\li0\\ri0\\keepn\\widctlpar\\intbl\n");
    fwrite($fl,"{\\*\\pn \\pnlvlcont\\ilvl0\\ls0\\pnrnot0\\pndec }\n");
    fwrite($fl,"\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\n");
    fwrite($fl,"{\\fs18 \\cell }{\\f29\\fs18 \\cell }\n");
    FileCopy($fl,$div);
    $result = mysql_query("
          select distinct disciplins.DisName, 
          s.AllH, s.UndCicCode, s.CodeOfUndCicle, s.CodeOfCicle,
          si.KursWork, si.KursPrj 
          from schedplan as s, schplanitems as si, disciplins where 
          s.CodeOfDiscipline=disciplins.CodeOfDiscipline and 
          s.CodeOfPlan=$plan 
          and (si.KursWork>0 or si.KursPrj>0) and s.AllH>0 and 
          s.CodeOfSchPlan = si.CodeOfSchPlan 
          order by si.KursWork, s.CodeOfCicle, s.CodeOfUndCicle, s.UndCicCode 
          ",$Connection)
       or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
    $Disciplins = array();   
    $DisCodes = array();   //Массив для уже встречавшихся кодов дисциплин повыбору
    $StrCount = 0;
    while ($row=mysql_fetch_object($result)){
       $Dis=$row->DisName;
       if ($row->KursPrj>0){$Dis .= "(проект)";}
       else{$Dis .= "(работа)";}
       if((strlen($row->UndCicCode)>2) && ($row->CodeOfUndCicle==4)){
          $NewDC=substr($row->UndCicCode,0,2);
          $ToCount = 1;
          while ($DCode = each($DisCodes)){
             //если подсчитывали то заносим соответствующее значение
             if ((strcmp($NewDC, $DCode[1][0]) == 0)&&
               ($row->CodeOfCicle == $DCode[1][1])){ $ToCount = 0;}
          }
          //Если таких дисциплин еще не встречалось то 
          //сохраняем данные о ней и суммируем
          if ($ToCount){
             $DisCodes[] = array($NewDC, $row->CodeOfCicle); 
             $Disciplins[] = array($Dis, $row->AllH); 
             $StrCount ++;
          }else{
             $Disciplins[$StrCount-1][0] .= "/".$Dis;
          }
       }else{
           $Disciplins[] = array($Dis, $row->AllH); 
           $StrCount ++;
       }
    }
    $i=1;
    while ($Discip = each($Disciplins)){
       fwrite($fl,"{\\pntext\\pard\\plain\\intbl\\f29\\fs18 \\hich\\af29\\dbch\\af0\\loch\\f29 $i.\\tab}\n");
       fwrite($fl,"\\pard \\ql \\fi-360\\li360\\ri0\\keepn\\widctlpar\\intbl\\jclisttab\\tx360\n");
       fwrite($fl,"{\\*\\pn \\pnlvlbody\\ilvl0\\ls7\\pnrnot0\\pndec\\pnstart1\\pnindent360\\pnsp120\\pnhang{\\pntxta .}}\n");
       fwrite($fl,"\\aspalpha\\aspnum\\faauto\\ls7\\adjustright\\rin0\\lin360\n");
       fwrite($fl,"{\\f29\\fs18 ".$Discip[1][0]."\\cell }\\pard \\ql \\li0\\ri0\\keepn\\widctlpar\\intbl\n");
       fwrite($fl,"{\\*\\pn \\pnlvlcont\\ilvl0\\ls0\\pnrnot0\\pndec }\n");
       fwrite($fl,"\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\n");
       fwrite($fl,"{\\fs18 \\cell }{\\f29\\fs18 \\cell }\n");
       if ($i != $StrCount){FileCopy($fl,$div);}
       $i++;
    }
    fwrite($fl,"\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\n");
    fwrite($fl,"{\\fs18 \\trowd \\irow23\\irowband23\\ts11\\trgaph108\\trleft-108\\trftsWidth3\\trwWidth10772\\trftsWidthB3\\trftsWidthA3\\trpaddl108\\trpaddr108\\trpaddfl3\\trpaddfr3 \\clvertalt\n");
    fwrite($fl,"\\clbrdrt\\brdrtbl \\clbrdrl\\brdrtbl \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrtbl \\cltxlrtb\\clftsWidth3\\clwWidth7937\\clshdrawnil \\cellx7829\\clvertalt\\clbrdrt\\brdrtbl \\clbrdrl\\brdrtbl \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrtbl\n");
    fwrite($fl,"\\cltxlrtb\\clftsWidth3\\clwWidth1134\\clshdrawnil \\cellx8963\\clvertalt\\clbrdrt\\brdrtbl \\clbrdrl\\brdrtbl \\clbrdrb\\brdrs\\brdrw10 \\clbrdrr\\brdrtbl \\cltxlrtb\\clftsWidth3\\clwWidth1701\\clshdrawnil \\cellx10664\\row }\n");
    fwrite($fl,"\\trowd \\irow24\\irowband24\\ts11\\trgaph108\\trleft-108\\trftsWidth3\\trwWidth10772\\trftsWidthB3\\trftsWidthA3\\trpaddl108\\trpaddr108\\trpaddfl3\\trpaddfr3 \\clvertalt\\clbrdrt\\brdrtbl \\clbrdrl\\brdrtbl \\clbrdrb\\brdrtbl \\clbrdrr\brdrtbl \\cltxlrtb\\clftsWidth3\\clwWidth7937\\clshdrawnil \n");
    fwrite($fl,"\\cellx7829\\clvertalt\\clbrdrt\\brdrtbl \\clbrdrl\\brdrtbl \\clbrdrb\\brdrtbl \\clbrdrr\\brdrtbl \\cltxlrtb\\clftsWidth3\\clwWidth1134\\clshdrawnil \\cellx8963\\clvertalt\\clbrdrt\\brdrtbl \\clbrdrl\\brdrtbl \\clbrdrb\\brdrtbl \\clbrdrr\\brdrtbl \n");
    fwrite($fl,"\\cltxlrtb\\clftsWidth3\\clwWidth1701\\clshdrawnil \\cellx10664\\pard \\ql \\li0\\ri0\\keepn\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 \n");
    fwrite($fl,"{\\f29\\fs18 Всего \\cell }{\\field{\\*\\fldinst {\\fs18 =SUM(ABOVE) }}\n");
    fwrite($fl,"{\\fldrslt {\\fs18\\lang1024\\langfe1024\\noproof $All}}}\n");
    fwrite($fl,"{\\fs18 \\cell}{\\f29\\fs18 \\cell }\n");
    fwrite($fl,"\n");
    fwrite($fl,"\n");
    FileCopy($fl,$div);
    $Aud = CalcAud($plan);
    fwrite($fl,"\\pard \\ql \\fi-360\\li360\\ri0\\keepn\\widctlpar\\intbl\\jclisttab\\tx360\n");
    fwrite($fl,"\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin360\n");
    fwrite($fl,"{\\f29\\fs18 В том числе аудиторных\\cell }\\pard \\ql \\li0\\ri0\\keepn\\widctlpar\\intbl\n");
    fwrite($fl,"{\\*\\pn \\pnlvlcont\\ilvl0\\ls0\\pnrnot0\\pndec }\n");
    fwrite($fl,"\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\n");
    fwrite($fl,"{\\fs18 $Aud\\cell }{\\f29\\fs18 \\cell }\n");
    FileCopy($fl,$div);
    fwrite($fl,"\\pard \\qc \\fi-360\\li360\\ri0\\keepn\\widctlpar\\intbl\\jclisttab\\tx360\n");
    fwrite($fl,"\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin360\n");
    fwrite($fl,"{\\f29\\fs18\\b КОНЕЦ ДОКУМЕНТА\\cell }\\pard \\ql \\li0\\ri0\\keepn\\widctlpar\\intbl\n");
    fwrite($fl,"{\\*\\pn \\pnlvlcont\\ilvl0\\ls0\\pnrnot0\\pndec }\n");
    fwrite($fl,"\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\n");
    fwrite($fl,"{\\fs18 \\cell }{\\f29\\fs18 \\cell }\n");
    FileCopy($fl,$div);
 
    //Закрытие файла
    fwrite($fl,"}");
    fclose($fl);
 
    /* Закрытие соединения */
    mysql_free_result($result);
 
    echo "<BR><H2>Экспорт выполнен в ";
    echo "<BR><A HREF=\"$filename\">$PrintName</A></H2>";
 
    include("../Display/FinishPage.php");
    mysql_close($Connection);
?>