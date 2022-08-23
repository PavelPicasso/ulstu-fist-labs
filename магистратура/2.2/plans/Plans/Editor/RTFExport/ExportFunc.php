<?php
//Копирует в уже открытый для чтения файл $fl
//все строки из файла с именем $FName
Function FileCopy($fTo,$fName){
   $fFrom = fopen ($fName,"r")
     or die("Невозможно открыть файл  $fName<BR>");
   $contents = fread ($fFrom, filesize ($fName));
   fwrite($fTo,$contents);
   fclose($fFrom);

}

//Выводит в таблицу графика учебного процесса 
//строку с номером семестра $KursNum
Function OutputKursGUP($KursNum){
   global $T, $TSem, $Ek, $PTP, $Dp, $IA, $Sb, $K, $PP, $NIP, $MD, $GE;
   global $plan;
   global $NumPeriod;
   global $Connection;
   global $fl;
   global $tbl2div;
   fwrite($fl,"{\\fs20 ".$KursNum."\\cell }\n{\\f31\\fs20 ");
   $WeekCount=0; //количество  недель в семетре 
   for ($j=1;$j<=$NumPeriod*2;$j++){
      $result = mysql_query("select Period".$j.", LengthOfPeriod".$j." from schedules where CodeOfPlan=".$plan." and KursNumb=".$KursNum,$Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
      $res=mysql_fetch_row($result);
      if ((!is_null($res[0]))&&(strcmp ( $res[0] , "-" ) != 0 )&&($res[1]>0)){
         $k = $res[1];
         $ch = $res[0];
         //подсчет часов
         if(strcmp ( $ch , "ТО") == 0)  {
           $T[$KursNum]+= $k;
           if ($j>=$NumPeriod) {$TSem[$KursNum*2] += $k;}
           else {$TSem[$KursNum*2 - 1] += $k;}
         };
         if(strcmp ( $ch , "Эк") == 0)  {$Ek[$KursNum]+= $k;};
         if(strcmp ( $ch , "ПП") == 0)  {$PTP[$KursNum]+= $k;};
         if(strcmp ( $ch , "ДП") == 0)  {$Dp[$KursNum]+= $k;};
         if(strcmp ( $ch , "ИА") == 0)  {$IA[$KursNum]+= $k; $GE[$KursNum]+= $k;};
         if(strcmp ( $ch , "Сб") == 0)  {$Sb[$KursNum]+= $k;};
         if(strcmp ( $ch , "К") == 0)   {$K[$KursNum]+= $k;};
         if(strcmp ( $ch , "МД") == 0)  {$MD[$KursNum]+= $k;};
         if(strcmp ( $ch , "ВР") == 0)  {$IA[$KursNum]+= $k;};
         if(strcmp ( $ch , "ГЭC") == 0) {$IA[$KursNum]+= $k;};
         if(strcmp ( $ch , "НП") == 0)  {$NIP[$KursNum]+= $k;};
         if(strcmp ( $ch , "ТГФ") == 0) {
            $GE[$KursNum]+= $k; 
            $T[$KursNum]+= $k;
            if ($j>=$NumPeriod) {$TSem[$KursNum*2] += $k;}
            else {$TSem[$KursNum*2 - 1] += $k;}
         };
         if(strcmp ( $ch , "ТГИ") == 0) {$GE[$KursNum]+= $k; };
         if(strcmp ( $ch , "ТП") == 0)  {
            $PP[$KursNum]+= $k; 
            $T[$KursNum]+= $k;
            if ($j>=$NumPeriod) {$TSem[$KursNum*2] += $k;}
            else {$TSem[$KursNum*2 - 1] += $k;}
         };
         //замена обозначений на более короткие
         $ch = str_replace("ТО", "Т", $ch);
         $ch = str_replace("Эк", "Э", $ch);
         $ch = str_replace("УП", "У", $ch);
         $ch = str_replace("ПП", "П", $ch);
         $ch = str_replace("ДП", "Д", $ch);
         $ch = str_replace("ИА", "И", $ch);
         $ch = str_replace("Сб", "С", $ch);
         $ch = str_replace("МД", "М", $ch);
         $ch = str_replace("НП", "Н", $ch);
         $ch = str_replace("ТГФ","ТФ", $ch);
         $ch = str_replace("ВР", "В", $ch);
         $ch = str_replace("ГЭC","ГС", $ch);
         while (($WeekCount<52)&&($k>0)){
           fwrite($fl,$ch." \\cell ");
           $WeekCount++;
           $k--;
         }
      }
   }
   while (($WeekCount<52)){
     fwrite($fl," -- \\cell");
     $WeekCount++;
   }
   fwrite($fl,"}\n");
   FileCopy($fl,$tbl2div); //пишем разделитель для сторок таблицы
   $T[0]   = $T[0]   + $T[$KursNum];
   $Ek[0]  = $Ek[0]  + $Ek[$KursNum];
   $PTP[0] = $PTP[0] + $PTP[$KursNum];
   $Dp[0]  = $Dp[0]  + $Dp[$KursNum];
   $IA[0]  = $IA[0]  + $IA[$KursNum];
   $Sb[0]  = $Sb[0]  + $Sb[$KursNum];
   $K[0]   = $K[0]   + $K[$KursNum];
   $PP[0]  = $PP[0]  + $PP[$KursNum];
   $NIP[0] = $NIP[0] + $NIP[$KursNum];
   $MD[0]  = $MD[0]  + $MD[$KursNum];
   $GE[0]  = $GE[0]  + $GE[$KursNum];

}


Function OutpPlan($StartCount,$SemCount,$tblStart, $tblHead, $tblHeadp2, $tblHeadp3, $tblDiv, $tblDiv1){
   global $T,$TSem;
   global $plan, $endstr;
   global $NumPeriod;
   global $Connection;
   global $fl;
   global $IF;
   global $ShowRGR;
   global $PlanData;
   FileCopy($fl,$tblStart);
   fwrite ($fl,"{\\b\\f31\\fs24                   ОБЪЕМ ЗАНЯТИЙ ПЛАНА ПОДГОТОВКИ ".$PlanData["DegreeName"]."ОВ ПО СПЕЦИАЛЬНОСТИ - ".$PlanData["PlanSpcName"]." \\cell }");
   FileCopy($fl,$tblHead);
   for ($i=($StartCount+1)/2; $i<=$SemCount/2; $i++) { fwrite($fl,"{\\f31 ".$i." \\'ea\\'f3\\'f0\\'f1\\cell }");}
   FileCopy($fl,$tblHeadp2);
   for ($i=$StartCount; $i<=$SemCount; $i++) { fwrite($fl,"$i \\cell ");}
   FileCopy($fl,$tblHeadp3);
   fwrite($fl,"{\\cell \\cell \\cell \\cell \\cell \\cell \\cell \\cell \\cell \\cell ");
   if($ShowRGR){fwrite($fl," \\cell ");}
   if($IF){fwrite($fl," \\cell ");}
   for ($i=$StartCount; $i<=$SemCount; $i++) { fwrite($fl,$TSem[$i]." \\cell ");}
   fwrite($fl," \\cell \\cell \\cell }");
   FileCopy($fl,$tblDiv1);
   $PlanDiscips = PlanDiscips($plan, $TSem);

   foreach ($PlanDiscips["Cicles"] as $k => $v) {
       $DisStr = "";
       //Вывод всех циклов
       $DisStr .= "{\\b\\f31\\fs20 ".$v["CicReduction"]."\\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 \n";
       $DisStr .= "{\\b\\f31\\fs20 ".$v["CicName"]." \\cell }\\pard \\qc \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 \n";
       $DisStr .= "{\\b\\f31\\fs20 \\cell \\cell \\cell \\cell ";

       if ($ShowRGR) 
           $DisStr .= "\\cell";

       $DisStr .= "}\n {\\b\\f31\\fs20 ".$v["AudH"]." \\cell ".$v["Lect"]." \\cell ".$v["Lab"]." \\cell ".$v["Pract"]." \\cell \n";

       if ($IF)
           $DisStr .="лек \\par лаб \\par прак \\cell\n";

       for ($sn=$StartCount; $sn<=$SemCount; $sn++){

           if ( !empty($v["HoursInW"][$sn]["LectInW"]) || !empty($v["HoursInW"][$sn]["LabInW"]) || !empty($v["HoursInW"][$sn]["PractInW"])){
               if ($IF)
                   $DisStr .= empty($v["HoursInW"][$sn]["LectInW"])?0:$v["HoursInW"][$sn]["LectInW"]." \\par ".empty($v["HoursInW"][$sn]["LabInW"])?0:$v["HoursInW"][$sn]["LabInW"]." \\par ".empty($v["HoursInW"][$sn]["PractInW"])?0:$v["HoursInW"][$sn]["PractInW"];
               else
                   $DisStr .= array_sum($v["HoursInW"][$sn]);
           }

           $DisStr .=" \\cell \n";
       }

       $DisStr .=$v["SelfH"]." \\cell ".$v["AllH"]." \\cell\n";
       $DisStr .= " \\cell \n}";
       fwrite($fl,$DisStr);
       FileCopy($fl,$tblDiv);

       foreach ($v["UnderCicles"] as $ku => $vu) {
           if ($vu["UndCicReduction"] != "") {
               // Вывод строки подцикла
               $DisStr = "{\\b\\f31\\fs20 $v[CicReduction].$vu[UndCicReduction] \\cell }".
               
               "\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 ".
               "{\\b\\f31\\fs20 ".$vu["UndCicName"]." \\cell }\\pard \\qc \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 ".
               "{\\b\\f31\\fs20  \\cell  \\cell \\cell  \\cell ";
               
               if ($ShowRGR)
                   $DisStr.=" \\cell ";
               
               $DisStr .= $vu["AudH"]. "\\cell ".$vu["Lect"]." \\cell ".$vu["Lab"]." \\cell ".$vu["Pract"]."\\cell ";
               if($IF){ $DisStr .= "лек \\par лаб \\par прак \\cell " ;}
               
               for ($sn=$StartCount; $sn<=$SemCount; $sn++){
                   if ( !empty($vu["HoursInW"][$sn]["LectInW"]) || !empty($vu["HoursInW"][$sn]["LabInW"]) || !empty($vu["HoursInW"][$sn]["PractInW"])){
                       if ($IF)
                           $DisStr .= empty($vu["HoursInW"][$sn]["LectInW"])?0:$vu["HoursInW"][$sn]["LectInW"]." \\par ".empty($vu["HoursInW"][$sn]["LabInW"])?0:$vu["HoursInW"][$sn]["LabInW"]." \\par ".empty($vu["HoursInW"][$sn]["PractInW"])?0:$vu["HoursInW"][$sn]["PractInW"];
                       else
                           $DisStr .= array_sum($vu["HoursInW"][$sn]);
                   }
               
                   $DisStr .= " \\cell ";
               }
               
               $DisStr .= $vu["SelfH"]." \\cell ".$vu["AllH"]." \\cell \\cell}\n";
               
               fwrite($fl,$DisStr);
               FileCopy($fl,$tblDiv);
           }
           foreach ($vu["Discips"] as $kd => $vd) {

               //формируем строку для вывода
               $DisStr = "{\\f31\\fs20 $v[CicReduction]";

               if ($vu["UndCicReduction"] != "")
                   $DisStr .= ".$vu[UndCicReduction]";
               if ($vd["UndCicCode"] != "")
                   $DisStr .= ".$vd[UndCicCode]";

               $DisStr .= " \\cell }".

               "\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 \n".
               "{\\f31\\fs20 ".$vd["DisName"]." \\cell }\\pard \\qc \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 \n".
               "{\\f31\\fs20 ".$vd["ExamPrn"]." \\cell ".$vd["TestPrn"]." \\cell ".$vd["KursPrjPrn"]." \\cell ".$vd["KursWorkPrn"]." \\cell ";
               if ($ShowRGR){$DisStr .= $vd["RGRPrn"]." \\cell ";}
               $DisStr .= $vd["AudH"]. "\\cell ".$vd["Lect"]." \\cell ".$vd["Lab"]." \\cell ".$vd["Pract"]."\\cell ";
               if($IF){ $DisStr .= "лек \\par лаб \\par прак \\cell " ;}
               for ($sn=$StartCount; $sn<=$SemCount; $sn++){

                   if ( !empty($vd["HoursInW"][$sn]["LectInW"]) || !empty($vd["HoursInW"][$sn]["LabInW"]) || !empty($vd["HoursInW"][$sn]["PractInW"])){
                       if ($IF)
                           $DisStr .= empty($vd["HoursInW"][$sn]["LectInW"])?0:$vu["HoursInW"][$sn]["LectInW"]." \\par ".empty($vu["HoursInW"][$sn]["LabInW"])?0:$vu["HoursInW"][$sn]["LabInW"]." \\par ".empty($vu["HoursInW"][$sn]["PractInW"])?0:$vu["HoursInW"][$sn]["PractInW"];
                       else
                           $DisStr .= array_sum($vd["HoursInW"][$sn]);
                   }

                  $DisStr .= " \\cell ";
               }

               $DisStr .= $vd["SelfH"]." \\cell ".$vd["AllH"]." \\cell\n";
               $DisStr .=$vd["Reduction"]." \\cell}";

               fwrite($fl,$DisStr);
               FileCopy($fl,$tblDiv);
           } //Вывод дисциплин
       } //Вывод подциклов
   } //Вывод циклов

   //Вывод итоговых данных
   fwrite ($fl,"{\\b\\fs20 \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 \n");
   fwrite ($fl,"{\\b\\f31\\fs20 Всего часов теоретич. обучения\\cell }\\pard \\qc \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 \n");
   fwrite ($fl,"{\\fs20 \\cell \\cell \\cell \\cell");
   if ($ShowRGR){ fwrite ($fl, "\\cell " );}
   fwrite ($fl," }\n {\\b\\fs20 ".$PlanDiscips["AudH"]."\\cell ".$PlanDiscips["Lect"]."\\cell ".$PlanDiscips["Lab"]."\\cell ".$PlanDiscips["Pract"]."\\cell\n");
   if ($IF){ fwrite ($fl,"\\cell ");}                                                                     
   for ($sn=$StartCount; $sn<=$SemCount; $sn++){fwrite($fl," \\cell");};
   fwrite ($fl," \\cell ".$PlanDiscips["AllH"]." \\cell \\cell \n}");
   fwrite ($fl," \n \n \n ");
   FileCopy($fl,$tblDiv);
   fwrite ($fl,"{\\b\\fs20 \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 \n");
   fwrite ($fl,"{\\b\\f31\\fs20 Всего часов в неделю \\cell }\\pard \\qc \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 \n");
   fwrite ($fl,"{\\fs20 \\cell \\cell \\cell \\cell ");

   if ($ShowRGR)
       fwrite ($fl, "\\cell ");

   fwrite ($fl," }\n {\\b\\fs20  \\cell  \\cell  \\cell \\cell \n");

   if ($IF) 
       fwrite ($fl,"\\cell ");

   $PlanSr=0;
   for ($sn=$StartCount; $sn<=$SemCount; $sn++){
      $pl = empty($PlanDiscips["HoursInW"][$sn])?0:array_sum($PlanDiscips["HoursInW"][$sn]);
      fwrite($fl,$pl." \\cell \n");
      $PlanSr += $pl;
   }
   $PlanSr = $PlanSr/($SemCount-$StartCount+1); 
   fwrite ($fl," \\cell ".$PlanSr." \\cell \\cell \n}");
   FileCopy($fl,$tblDiv);

   fwrite ($fl,"{\\b\\fs20 \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 \n");
   fwrite ($fl,"{\\b\\f31\\fs20 Число курсовых проектов \\cell }\\pard \\qc \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 \n");
   fwrite ($fl,"{\\fs20 \\cell \\cell \\cell \\cell ");
   if ($ShowRGR){ fwrite ($fl, "\\cell ");}
   fwrite ($fl,"}\n {\\b\\fs20  \\cell  \\cell  \\cell \\cell \n");
   if ($IF){ fwrite ($fl,"\\cell ");}
   $PlanSr=0;
   for ($sn=$StartCount; $sn<=$SemCount; $sn++){
       fwrite($fl,(empty($PlanDiscips["HoursTest"][$sn])?0:$PlanDiscips["HoursTest"][$sn]["KursPrj"])." \\cell \n");
       $PlanSr += (empty($PlanDiscips["HoursTest"][$sn])?0:$PlanDiscips["HoursTest"][$sn]["KursPrj"]);
   }
   fwrite ($fl," \\cell ".$PlanSr." \\cell \\cell \n}");
   FileCopy($fl,$tblDiv);
   fwrite ($fl,"{\\b\\fs20 \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 \n");
   fwrite ($fl,"{\\b\\f31\\fs20 Число курсовых работ \\cell }\\pard \\qc \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 \n");
   fwrite ($fl,"{\\fs20 \\cell \\cell \\cell \\cell ");
   if ($ShowRGR){ fwrite ($fl, "\\cell ");}
   fwrite ($fl,"}\n {\\b\\fs20  \\cell  \\cell  \\cell \\cell \n");
   if ($IF){ fwrite ($fl,"\\cell ");}
   $PlanSr=0;
   for ($sn=$StartCount; $sn<=$SemCount; $sn++){
       fwrite($fl,(empty($PlanDiscips["HoursTest"][$sn])?0:$PlanDiscips["HoursTest"][$sn]["KursWork"])." \\cell \n");
       $PlanSr += (empty($PlanDiscips["HoursTest"][$sn])?0:$PlanDiscips["HoursTest"][$sn]["KursWork"]);
   }
   fwrite ($fl," \\cell ".$PlanSr." \\cell \\cell \n}");
   FileCopy($fl,$tblDiv);
   fwrite ($fl,"{\\b\\fs20 \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 \n");
   fwrite ($fl,"{\\b\\f31\\fs20 Число экзаменов \\cell }\\pard \\qc \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 \n");
   fwrite ($fl,"{\\fs20 \\cell \\cell \\cell \\cell ");
   if ($ShowRGR){ fwrite ($fl, "\\cell ");}
   fwrite ($fl,"}\n {\\b\\fs20  \\cell  \\cell  \\cell \\cell \n");
   if ($IF){ fwrite ($fl,"\\cell ");}
   $PlanSr=0;
   for ($sn=$StartCount; $sn<=$SemCount; $sn++){
       fwrite($fl,(empty($PlanDiscips["HoursTest"][$sn]["Exam"])?0:$PlanDiscips["HoursTest"][$sn]["Exam"])." \\cell \n");
       $PlanSr += (empty($PlanDiscips["HoursTest"][$sn])?0:$PlanDiscips["HoursTest"][$sn]["Exam"]);
   }
   fwrite ($fl," \\cell ".$PlanSr." \\cell \\cell \n}");
   fwrite ($fl," \n \n \n ");
   FileCopy($fl,$tblDiv);
   fwrite ($fl," \n \n \n ");
   fwrite ($fl,"{\\b\\fs20 \\cell }\\pard \\ql \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 \n");
   fwrite ($fl,"{\\b\\f31\\fs20 Число зачетов \\cell }\\pard \\qc \\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0 \n");
   fwrite ($fl,"{\\fs20 \\cell \\cell \\cell \\cell ");
   if ($ShowRGR){ fwrite ($fl, "\\cell ");}
   fwrite ($fl,"}\n {\\b\\fs20  \\cell  \\cell  \\cell \\cell \n");
   if ($IF){ fwrite ($fl,"\\cell ");}
   $PlanSr=0;
   for ($sn=$StartCount; $sn<=$SemCount; $sn++){
       fwrite($fl,(empty($PlanDiscips["HoursTest"][$sn])?0:$PlanDiscips["HoursTest"][$sn]["Test"])." \\cell \n");
       $PlanSr += (empty($PlanDiscips["HoursTest"][$sn])?0:$PlanDiscips["HoursTest"][$sn]["Test"]);
   }
   fwrite ($fl," \\cell ".$PlanSr." \\cell \\cell \n}");
   FileCopy($fl,$tblDiv);
   fwrite($fl,$endstr);
}


?>