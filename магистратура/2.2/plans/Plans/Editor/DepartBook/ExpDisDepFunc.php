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

//Опредляет число учебных недель в семестре
//Параметры: код плана, номер семестра,  количество недель
Function TeachWeek($PCode, $SmN, $KN){
   global $Connection;
   global $NumPeriod;
   $resultGUP = mysql_query("select Period1, LengthOfPeriod1,
      Period2, LengthOfPeriod2, 
      Period3, LengthOfPeriod3, 
      Period4, LengthOfPeriod4, 
      Period5, LengthOfPeriod5, 
      Period6, LengthOfPeriod6, 
      Period7, LengthOfPeriod7, 
      Period8, LengthOfPeriod8, 
      Period9, LengthOfPeriod9, 
      Period10, LengthOfPeriod10, 
      Period11, LengthOfPeriod11, 
      Period12, LengthOfPeriod12, 
      Period13, LengthOfPeriod13, 
      Period14, LengthOfPeriod14, 
      Period15, LengthOfPeriod15, 
      Period16, LengthOfPeriod16  
      from schedules where CodeOfPlan=$PCode and KursNumb=$KN",$Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   $WeekCount = 0;
   $Sm1 = $SmN - $KN*2 + 1;
   if ($resGUP=mysql_fetch_row($resultGUP)){
      for ($p=$NumPeriod*$Sm1; $p<$NumPeriod*($Sm1+1); $p++)
      {
        if ((strcmp($resGUP[$p*2],"ТО")==0)||(strcmp($resGUP[$p*2],"ТГФ")==0)||(strcmp($resGUP[2*$p],"ТП")==0))
           {//Учитывается только  теоретическое обучение
              $WeekCount+=$resGUP[$p*2+1];
           }
      }  	
   }
   mysql_free_result($resultGUP);
   return $WeekCount;
}

//Вывод строки дисциплины в файл $fl
Function PrintDiscip($fl){
   global $div;
   global $i;
   global $j;
   global $SpRed;
   global $PCode;
   global $StrName;
   global $res;
   global $CRed;
   global $DisName;
   global $DisCode;
   global $Kurs;
   global $Sm;
   global $Lec;
   global $Lab;
   global $Pract;
   global $Ex;
   global $Test;
   global $KP;
   global $KW;
   global $RGR;
   global $Ref;
   $All = $Lec + $Lab + $Pract;
   if ($Lec == 0){ $Lec = "-";}
   if ($Lab == 0){ $Lab = "-";}
   if ($Pract == 0){ $Pract = "-";}
   if ($All == 0){ $All = "-";}
   if ($Ex ==0 ){$Ex = "-";}
   else {$Ex = "да";}
   if ($Test ==0 ){$Test = "-";}
   else {$Test = "да";}
   if ($KW ==0 ){$KW = "-";}
   else {$KW = "да";}
   if ($KP ==0 ){$KP = "-";}
   else {$KP = "да";}
   $Prim = "";
   if (($RGR + $Ref) != 0 ){$Prim = "Предусмотрена расчетно-графическая работа (дом.раб.)\n";}
   fwrite($fl,"{\\listtext\\pard\\plain\\intbl\\fs20\\hich\\af0\\dbch\\af0\\loch\\f0 $i.\\tab}");
   if ($i > 1){fwrite($fl,"}\n");}
   fwrite($fl,"\\pard\\qc\\li0\\ri0\\widctlpar\\intbl\\jclisttab\\tx360\\aspalpha\\aspnum\\faauto\\");
   fwrite($fl,"ls".$j."\\adjustright\\rin0\\lin0 { }\n");
   fwrite($fl,"{\\fs20 \\cell}\n");
   fwrite($fl,"\\pard\\qc\\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\n");
   fwrite($fl,"{\\f32\\fs20 $SpRed\\cell}\n");
   fwrite($fl,"{\\f32\\fs20 $CRed\\cell}\n");
   fwrite($fl,"\\pard\\qj\\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\f32\n");
   fwrite($fl,"{\\fs20 $DisName}\n");
   fwrite($fl,"{\\fs20 \\cell}\n");
   fwrite($fl,"\\pard\\qc\\li0\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\n");
   fwrite($fl,"{\\fs20 $Kurs\\cell $Sm\\cell $All\\cell $Lec\\cell $Pract\\cell $Lab\\cell}\n");
   fwrite($fl,"{\\f32 $KP\\cell $KW\\cell $Test\\cell $Ex\\cell } \n");
   fwrite($fl,"{\\f32\\fs20 $Prim\\cell } \n");
   FileCopy($fl,$div);
   $i++; //переводим счетчик дисциплин
   //сбрасываем значения выводимых переменных в несуществующие
   $Kurs="";
   $DisCode = -1; //Код выодимой дисциплины
   $Sm = "";      //выводимые данные о семестрах
   $Lec = 0;      //часы лекций
   $Lab = 0;      //часы лекций
   $Pract = 0;    //часы лекций
   $Ex = 0;
   $Test = 0;
   $KP = 0;
   $KW = 0;
   $RGR = 0;
   $Ref = 0;
   $StrName = "";
}

?>