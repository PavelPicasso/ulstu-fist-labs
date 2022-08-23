<?php
    include("../PlanCalculatFunc.php");
    include("../Display/DisplayFunc.php");
    CreateConnection();
    include("../Display/StartPage.php");

    DisplayPageTitle("","Экспорт учебного плана в формат RTF");
    if (!empty($_GET['plan'])) $plan=$_GET['plan'];
    if (!empty($_POST['plan'])) $plan=$_POST['plan'];
    if (!(isset($plan)))
        return;
    else
        $CodeOfPlan = intval($plan);

    $PlanData = GetPlanInfo($CodeOfPlan);

    //Имя файла
    $filename="../../../Exported/".$PlanData["PlanSpcCode"];
//    $filename="/tmp/".$PlanData["PlanSpcCode"];
    $i=0;
    while ( file_exists ($filename."_".$i.".rtf")){
     $i++;
     }
    $PrintName = $PlanData["PlanSpcCode"]."_".$i.".rtf";
    $filename .= "_".$i.".rtf";
    /* Освобождение resultset */
    //Создаем файл
    $fl = fopen ($filename,"w")
      or die("Невозможно создать файл  $filename.<BR>");

    include ("ExportFunc.php");
 
    $headS = "RTFParts/HeaderSt.rtf";  //имя файла с заголовком файла формата rtf
    $headE = "RTFParts/HeaderEnd.rtf";  //имя файла с заголовком файла формата rtf
    $tbl1 = "RTFParts/tbl1Start.rtf"; //имя файла с 1-ой таблицей
    $tbl1end = "RTFParts/tbl1End.rtf"; //имя файла с 1-ой таблицей
    $tbl2 = "RTFParts/tbl2GUP.rtf"; //имя файла сО 2-ой таблицей
    $tbl2div = "RTFParts/tbl2div.rtf"; //имя файла сО 2-ой таблицей
    $tbl3 = "RTFParts/tbl3Start.rtf"; //имя файла с 3-Ей таблицей
    $tbl3hd = "RTFParts/tbl3HeadDiv.rtf"; //имя файла с 3-Ей таблицей
    $tbl3div = "RTFParts/tbl3div.rtf"; //имя файла с 3-Ей таблицей
    $tbl3end = "RTFParts/tbl3end.rtf"; //имя файла с 3-Ей таблицей
    $tbl5 = "RTFParts/tbl5start.rtf"; //имя файла с 5-oй таблицей
    $tbl5div="RTFParts/tbl5div.rtf"; //имя файла с 5-oй таблицей
 
    //Заголовок файла формата rtf
    FileCopy($fl,$headS);
    fwrite($fl,"$PlanData[PlanSpcCode] - $plan");
    FileCopy($fl,$headE);
 
    //таблица-шапка
    //--------------------------------------------------------------
    FileCopy($fl,$tbl1);
    $result = mysql_query("select ProRectorTWSignature from administration",$Connection)
       or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
    $row = mysql_fetch_object($result);
    fwrite($fl,"{\\f31\\fs20                      ".$row->ProRectorTWSignature." \\cell }");
    FileCopy($fl,$tbl1end);
    $endstr="\\pard \\ql \\li0\\ri0\\widctlpar\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\itap0{\\par }";//строка завершающая таблицу
    //динамически генерируемая часть таблицы
    //--------------------------------------------------------------
    $KursCount = 0; //Количество курсов 
    $st = "лет";
    if ($PlanData["YearCount"] < 4){$st = "года";}
    if ($PlanData["YearCount"] == 1){$st = "год";}
 
    if (isset ($PlanData["CodeOfDirect"])){
        fwrite($fl,"\\cell \\cell {\\f31\\fs20 НАПРАВЛЕНИЕ ".$PlanData["DirCode"]."  - ".$PlanData["DirName"]."}\\cell\\row");
    }
    if (isset ($PlanData["CodeOfSpecial"])){
          fwrite($fl,"\\cell \\cell {\\f31\\fs20 СПЕЦИАЛЬНОСТЬ ".$PlanData["SpcCode"]."  - ".$PlanData["SpcName"]."}\\cell\\row");
    }
    if (isset ($PlanData["CodeOfSpecialization"])){
          fwrite($fl,"\\cell \\cell {\\f31\\fs20 СПЕЦИАЛИЗАЦИЯ ".$PlanData["SpzCode"]."  - ".$PlanData["SpzName"]."}\\cell\\row");
    }
    fwrite($fl,"{\\f31\\fs20     _______________ 20__  г.}\\cell \\cell{\\f31\\fs20 КВАЛИФИКАЦИЯ ВЫПУСКНИКА - ");
    fwrite($fl,"$PlanData[DegreeName] }\\cell\\row ");
 
    fwrite($fl,"\\cell \\cell {\\f31\\fs20 СРОК ОБУЧЕНИЯ - $PlanData[YearCount] $st   ");
    if (strcmp($PlanData["DegreeName"],"МАГИСТР")==0){
       fwrite($fl," (после 4-летней подготовки)  ");
    }

    $spcF = "----";
    $TeachForms = array("classroom" =>"ОЧНАЯ", "correspondence" => "ЗАОЧНАЯ", "night" => "ВЕЧЕРНЯЯ", "distance" => "ДИСТАНЦИОННАЯ", "external" => "ЭКСТЕРНАТ");

    fwrite($fl,"ФОРМА ОБУЧЕНИЯ - ".$TeachForms[$PlanData["TeachForm"]]."}");
    fwrite($fl,"\\cell \\row ");
    fwrite($fl,$endstr);
 
    //таблица - ГРАФИК УЧЕБНОГО ПРОЦЕССА
    //--------------------------------------------------------------
    FileCopy($fl,$tbl2);
    //массивы для хранения сводных данных
    $T = array(0,0,0,0,0,0,0);
    $TSem = array(0,0,0,0,0,0,0,0,0,0,0,0,0);
    $Ek = array(0,0,0,0,0,0,0);
    $PTP = array(0,0,0,0,0,0,0);
    $Dp = array(0,0,0,0,0,0,0);
    $IA = array(0,0,0,0,0,0,0);
    $Sb = array(0,0,0,0,0,0,0);
    $K = array(0,0,0,0,0,0,0);
    $PP = array(0,0,0,0,0,0,0);
    $NIP = array(0,0,0,0,0,0,0);
    $MD = array(0,0,0,0,0,0,0);
    $GE = array(0,0,0,0,0,0,0);
    //динамически генерируемая часть таблицы
    //--------------------------------------------------------------
    list ($FirstKurs, $LastKurs, $FirstTerm, $LastTerm) = GetPeriod($CodeOfPlan, "Y");
    
    for($i=$FirstKurs; $i<=$LastKurs; $i++){
      OutputKursGUP($i);
    }
    //Закрытие таблицы
    fwrite($fl,$endstr);
    //таблица - СВОДНЫЕ ДАННЫЕ ПО БЮДЖЕТУ ВРЕМЕНИ
    //--------------------------------------------------------------
    FileCopy($fl,$tbl3);
 
    if ( strcmp($PlanData["DegreeName"],"МАГИСТР")==0){
       fwrite($fl,"{\\f31\\fs20 Курс\\cell Теор. Обуч.\\cell".
       "Экзам. Сессия\\cell Педагогич. Практика\\cell".
       "Научно-иссл. практика\\cell Подгот. магистрской диссертации\\cell ".
       "Госэкзамены и защита магист. диссертации\\cell Каникулы\\cell Всего\\cell }");
       FileCopy($fl,$tbl3hd);
       //Проход по всем семестрам
       $All=0;
       for($i=$FirstKurs; $i<=$LastKurs; $i++){
          $Summ = $T[$i] + $Ek[$i] + $NIP[$i] + $MD[$i] + $K[$i] + $IA[$i];
          fwrite($fl,"\n{\\f31\\fs20 ".$i."\\cell }".
          "{\\fs20 ".$T[$i]." \\cell ".$Ek[$i]." \\cell ".$PP[$i]."' \\cell ".$NIP[$i]." \\cell ".$MD[$i]." \\cell ".$GE[$i]."' \\cell ".$K[$i]." \\cell ".$Summ." \\cell }\n");
          FileCopy($fl,$tbl3div);
         $All += $Summ;
       }
       fwrite($fl,"\n{\\f31\\fs20 ".$i."\\cell }".
      "{\\fs20 ".$T[0]." \\cell ".$Ek[0]." \\cell ".$PP[0]."' \\cell ".$NIP[0]." \\cell ".$MD[0]." \\cell ".$GE[0]."' \\cell ".$K[0]." \\cell ".$All." \\cell }\n");
    }
    else{
      fwrite($fl,"{\\f31\\fs20 Курс\\cell Теоретическое Обучение\\cell".
      "Экзаменационная Сессия\\cell Ппроизв.-Техн.  Практика\\cell".
      "Преддипломная практика \\cell Итоговая аттестация\\cell ".
      "Сборы\\cell Каникулы\\cell Всего\\cell }");
       FileCopy($fl,$tbl3hd);
       //Проход по всем семестрам
       $All=0;
       for($i = $FirstKurs; $i<=$LastKurs; $i++){
          $Summ = $T[$i] + $Ek[$i] + $PTP[$i] + $Dp[$i]+ $IA[$i]+ $Sb[$i] + $K[$i];
          fwrite($fl,"\n{\\f31\\fs20 ".$i."\\cell }".
          "{\\fs20 ".$T[$i]." \\cell ".$Ek[$i]." \\cell ".$PTP[$i]." \\cell ".$Dp[$i]." \\cell ".$IA[$i]." \\cell ".$Sb[$i]." \\cell ".$K[$i]." \\cell ".$Summ." \\cell }\n");
          FileCopy($fl,$tbl3div);
          $All += $Summ;
       }
       fwrite($fl,"\n{\\f31\\fs20 Всего \\cell }".
       "{\\fs20 ".$T[0]." \\cell ".$Ek[0]." \\cell ".$PTP[0]." \\cell ".$Dp[0]." \\cell ".$IA[0]." \\cell ".$Sb[0]." \\cell ".$K[0]." \\cell ".$All." \\cell }\n");
    }
    //Закрытие таблицы
    FileCopy($fl,$tbl3end);
 
    //!!!!!!Вставка перенос на следубщую страницу
    //fwrite ($fl, " \\par \\page \\par ");
 
    //таблица - ОБЪЕМ ЗАНЯТИЙ ПЛАНА
    $result = mysql_query("select SUM(RGR), SUM(Synopsis) from schplanitems, schedplan where schplanitems.CodeOfSchPlan=schedplan.CodeOfSchPlan and schedplan.CodeOfPlan=".$plan,$Connection)
       or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
    $res = mysql_fetch_array($result);
    $ShowRGR = 0;
    if(($res[0]!=0)||($res[1]!=0)){ $ShowRGR = 1;}
    //--------------------------------------------------------------
    $yCnt = $LastKurs - $FirstKurs + 1;
    if ($IF){
       if($ShowRGR){
          $tblStart = "RTFParts/".$yCnt."/RASPRGRForm/tblOZ".$yCnt."StartRASPRGR.rtf";
          $tblHead = "RTFParts/".$yCnt."/RASPRGRForm/tblOZ".$yCnt."HeadRASPRGR.rtf";
          $tblHeadp2 = "RTFParts/".$yCnt."/RASPRGRForm/tblOZ".$yCnt."Headp2RASPRGR.rtf";
          $tblHeadp3 = "RTFParts/".$yCnt."/RASPRGRForm/tblOZ".$yCnt."Headp3RASPRGR.rtf";
          $tblDiv = "RTFParts/".$yCnt."/RASPRGRForm/tblOZ".$yCnt."DivRASPRGR.rtf";
          $tblDiv1 = "RTFParts/".$yCnt."/RASPRGRForm/tblOZ".$yCnt."Div1RASPRGR.rtf";
       }
       else{
          $tblStart = "RTFParts/".$yCnt."/RASPForm/tblOZ".$yCnt."StartRASP.rtf";
          $tblHead = "RTFParts/".$yCnt."/RASPForm/tblOZ".$yCnt."HeadRASP.rtf";
          $tblHeadp2 = "RTFParts/".$yCnt."/RASPForm/tblOZ".$yCnt."Headp2RASP.rtf";
          $tblHeadp3 = "RTFParts/".$yCnt."/RASPForm/tblOZ".$yCnt."Headp3RASP.rtf";
          $tblDiv = "RTFParts/".$yCnt."/RASPForm/tblOZ".$yCnt."DivRASP.rtf";
          $tblDiv1 = "RTFParts/".$yCnt."/RASPForm/tblOZ".$yCnt."Div1RASP.rtf";
       }
    }
    else{
       if($ShowRGR){
          $tblStart = "RTFParts/".$yCnt."/RGRForm/tblOZ".$yCnt."StartRGR.rtf";
          $tblHead = "RTFParts/".$yCnt."/RGRForm/tblOZ".$yCnt."HeadRGR.rtf";
          $tblHeadp2 = "RTFParts/".$yCnt."/RGRForm/tblOZ".$yCnt."Headp2RGR.rtf";
          $tblHeadp3 = "RTFParts/".$yCnt."/RGRForm/tblOZ".$yCnt."Headp3RGR.rtf";
          $tblDiv = "RTFParts/".$yCnt."/RGRForm/tblOZ".$yCnt."DivRGR.rtf";
          $tblDiv1 = "RTFParts/".$yCnt."/RGRForm/tblOZ".$yCnt."Div1RGR.rtf";
       }
       else{
          $tblStart = "RTFParts/".$yCnt."/Form/tblOZ".$yCnt."Start.rtf";
          $tblHead = "RTFParts/".$yCnt."/Form/tblOZ".$yCnt."Head.rtf";
          $tblHeadp2 = "RTFParts/".$yCnt."/Form/tblOZ".$yCnt."Headp2.rtf";
          $tblHeadp3 = "RTFParts/".$yCnt."/Form/tblOZ".$yCnt."Headp3.rtf";
          $tblDiv = "RTFParts/".$yCnt."/Form/tblOZ".$yCnt."Div.rtf";
          $tblDiv1 = "RTFParts/".$yCnt."/Form/tblOZ".$yCnt."Div1.rtf";
       }
    }
    //Вывод таблицы объем занятий
    OutpPlan($FirstTerm, $LastTerm, $tblStart, $tblHead, $tblHeadp2, $tblHeadp3, $tblDiv, $tblDiv1);
    //таблица - ПОДПИСАНТЫ
    //--------------------------------------------------------------
    FileCopy($fl,$tbl5);
///Выводим Зав. кафедрой
    fwrite($fl,"{\\fs20 \\cell }{\\f31\\fs20 Заф. кафедрой ".$PlanData["DepReduction"]. ": \\cell ".$PlanData["ZavSignature"]." \\cell }".
    "{\\f31\\fs20 \\cell Согласовано: \\cell }");
    $SoglRed = "";
    $SoglPdp = "";
    $resSogl = mysql_query("select distinct faculty.Reduction, faculty.DeanSignature from schedplan,department,faculty where CodeOfPlan=".$plan." and department.CodeOfDepart=schedplan.CodeOfDepart and faculty.CodeOfFaculty=department.CodeOfFaculty order by Reduction",$Connection)
       or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
    while ((strcmp($SoglRed,"")==0)&&($rowSogl = mysql_fetch_object($resSogl))){
      if(strcmp ( $rowSogl->Reduction , $PlanData["FacReduction"]) != 0 ){
         $SoglRed = "Декан ".$rowSogl->Reduction;
         $SoglPdp = $rowSogl->DeanSignature;
      }
    }
    fwrite($fl,"{\\f31\\fs20 ".$SoglRed."   ".$SoglPdp." \\cell \\cell }");
    FileCopy($fl,$tbl5div);
///Выводим Декана
    fwrite($fl,"{\\fs20 \\cell }{\\f31\\fs20 Декан ".$PlanData["FacReduction"]. ": \\cell ".$PlanData["DeanSignature"]." \\cell }".
    "{\\f31\\fs20 \\cell  \\cell }");
    $SoglRed = "";
    $SoglPdp = "";
    while ((strcmp($SoglRed,"")==0)&&($rowSogl = mysql_fetch_object($resSogl))){
      if(strcmp ( $rowSogl->Reduction , $PlanData["FacReduction"]) != 0 ){
         $SoglRed = "Декан ".$rowSogl->Reduction;
         $SoglPdp = $rowSogl->DeanSignature;
      }
    }
    fwrite($fl,"{\\f31\\fs20 ".$SoglRed."    ".$SoglPdp." \\cell \\cell }");
    FileCopy($fl,$tbl5div);
    //Выводим Нач. уч. части
    $result = mysql_query("select HeadSignature from administration",$Connection)
       or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
    $resUch = mysql_fetch_row($result);
    fwrite($fl,"{\\fs20 \\cell }{\\f31\\fs20 Начальник учебной части: \\cell ".$resUch[0]." \\cell }".
    "{\\f31\\fs20 \\cell \\cell }");
    $SoglRed = "";
    $SoglPdp = "";
    while ((strcmp($SoglRed,"")==0)&&($rowSogl = mysql_fetch_object($resSogl))){
      if(strcmp ( $rowSogl->Reduction , $PlanData["FacReduction"]) != 0 ){
         $SoglRed = "Декан ".$rowSogl->Reduction;
         $SoglPdp = $rowSogl->DeanSignature;
      }
    }
    fwrite($fl,"{\\f31\\fs20 ".$SoglRed."    ".$SoglPdp." \\cell \\cell }");
    FileCopy($fl,$tbl5div);
    //вывгодим оставшихся для согласования
    while ($rowSogl = mysql_fetch_object($resSogl)){
      $SoglRed = "";
      $SoglPdp = "";
      if(strcmp ( $rowSogl->Reduction , $PlanData["FacReduction"]) != 0 ){
         $SoglRed = "Декан ".$rowSogl->Reduction;
         $SoglPdp = $rowSogl->DeanSignature;
         fwrite($fl,"{\\fs20 \\cell }{\\f31\\fs20  \\cell  \\cell }".
         "{\\f31\\fs20 \\cell \\cell }");
         fwrite($fl,"{\\f31\\fs20 ".$SoglRed."    ".$SoglPdp." \\cell \\cell }");
         FileCopy($fl,$tbl5div);
      }
    }
    //Закрытие таблицы
    fwrite($fl,$endstr);
 
    //Закрытие файла
    fwrite($fl,"}");
    fclose($fl);
 
 
    echo "<BR><H2>Экспорт выполнен в ";
    echo "<BR><A HREF=\"$filename\">$PrintName</A></H2>";
 
    include("../Display/FinishPage.php");
    mysql_close($Connection);
?>