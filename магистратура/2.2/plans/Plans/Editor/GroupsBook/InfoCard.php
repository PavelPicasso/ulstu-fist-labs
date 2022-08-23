<?php
   include("../../cfg.php"); 
   set_time_limit(60*3);   
   if (!($REQUEST_METHOD=='POST' && $_POST['spc'] && $_POST['semestr'])) {
      Header ("Location: ../GroupsBook/GroupsBook.php");
      exit;
   }
   $SpcStr = $_POST['spc'];
   $SpcArr = explode(",",$SpcStr);
   $q1 = "";
   $q2 = "";
   $q3 = "";
   if ((strcmp($SpcArr[0],"direction")==0) && is_numeric($SpcArr[1])){
      $q1 = "select * from directions where CodeOfDirect=".$SpcArr[1];
      $q2 = "select distinct  streams.CodeOfStream ". 
            "from directions, plans, streams ".
            "where plans.CodeOfPlan = streams.CodeOfPlan and ". 
            "plans.CodeOfDirect = directions.CodeOfDirect and ". 
            "(plans.CodeOfSpecial is NULL or plans.CodeOfSpecial=0) and ".
            "(plans.CodeOfSpecialization is NULL or plans.CodeOfSpecialization=0) ".
            "and directions.CodeOfDirect=".$SpcArr[1];
      $q3 = "select distinct streams.CodeOfStream, streams.CodeOfPlan, ". 
            "streams.StreamName, streams.Kurs ". 
            "from directions, plans, streams ".
            "where plans.CodeOfPlan = streams.CodeOfPlan and ".
            "plans.CodeOfDirect = directions.CodeOfDirect and ".
            "(plans.CodeOfSpecial is NULL or plans.CodeOfSpecial=0) and ".
            "(plans.CodeOfSpecialization is NULL or plans.CodeOfSpecialization=0) ".
            "and directions.CodeOfDirect=".$SpcArr[1];
      $q4 = "select faculty.DeanSignature, department.ZavSignature ". 
            "from faculty, department, directions ".
            "where directions.CodeOfDepart=department.CodeOfDepart and ".
            "faculty.CodeOfFaculty=department.CodeOfFaculty ".
            "and directions.CodeOfDirect=".$SpcArr[1];
   }else{
      if ((strcmp($SpcArr[0],"special")==0) && is_numeric($SpcArr[1])){
         $q1 = "select * from specials where CodeOfSpecial=".$SpcArr[1];
         $q2 = "select distinct  streams.CodeOfStream ". 
               "from specials, plans, streams ".
               "where plans.CodeOfPlan = streams.CodeOfPlan and ". 
               "plans.CodeOfSpecial = specials.CodeOfSpecial and ". 
               "(plans.CodeOfSpecialization is NULL or plans.CodeOfSpecialization=0) ".
               "and specials.CodeOfSpecial=".$SpcArr[1];
         $q3 = "select distinct streams.CodeOfStream, streams.CodeOfPlan, ". 
               "streams.StreamName, streams.Kurs ". 
               "from specials, plans, streams ".
               "where plans.CodeOfPlan = streams.CodeOfPlan and ".
               "plans.CodeOfSpecial = specials.CodeOfSpecial and ".
               "(plans.CodeOfSpecialization is NULL or plans.CodeOfSpecialization=0) ".
               "and specials.CodeOfSpecial=".$SpcArr[1];
         $q4 = "select faculty.DeanSignature, department.ZavSignature ". 
               "from faculty, department, specials ".
               "where specials.CodeOfDepart=department.CodeOfDepart and ".
               "faculty.CodeOfFaculty=department.CodeOfFaculty ".
               "and specials.CodeOfSpecial=".$SpcArr[1];
      }else{
         if ((strcmp($SpcArr[0],"specialization")==0) && is_numeric($SpcArr[1])){
            $q1 = "select * from specializations where CodeOfSpecialization=".$SpcArr[1];
            $q2 = "select distinct  streams.CodeOfStream ". 
                  "from specializations, plans, streams ".
                  "where plans.CodeOfPlan = streams.CodeOfPlan and ". 
                  "plans.CodeOfSpecialization = specializations.CodeOfSpecialization ". 
                  "and specializations.CodeOfSpecialization=".$SpcArr[1];
            $q3 = "select distinct  streams.CodeOfStream, streams.CodeOfPlan, ". 
                  "streams.StreamName, streams.Kurs ". 
                  "from specializations, plans, streams ".
                  "where plans.CodeOfPlan = streams.CodeOfPlan and ".
                  "plans.CodeOfSpecialization = specializations.CodeOfSpecialization ".
                  "and specializations.CodeOfSpecialization=".$SpcArr[1];
            $q4 = "select faculty.DeanSignature, department.ZavSignature ". 
                  "from faculty, department, specializations ".
                  "where specializations.CodeOfDepart=department.CodeOfDepart and ".
                  "faculty.CodeOfFaculty=department.CodeOfFaculty ".
                  "and specializations.CodeOfSpecialization=".$SpcArr[1];
         }else{
            Header ("Location: ../GroupsBook/GroupsBook.php");
            exit;
         }
      }
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<HTML>
<HEAD>
<TITLE>Учебные планы</TITLE>
<META NAME=Author CONTENT="Карпова Анна">
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<link rel="stylesheet" href="http://plans.nilaos.ustu/CSS/PlansEditor.css" type="text/css">
</HEAD>
<BODY topmargin=1 leftmargin=5 marginheight="1" marginwidth="5">
<em class='h1'><center>Получение Иформационной карты экзаменов и зачетов</center></em>
<table border='0' width='100%' cellpadding='0' cellspacing='2'>
<tr><td height='5' bgcolor="#92a2d9">
<img src="../img/line.gif" width=15 height=15 hspace=0 vspace=0 border='0' align='left'>
</td></tr>
</table>
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
   $Sm = $_POST['semestr'];
   //Подбор имени файла---------------------------
   $filename="../../../Exported/IC";
   $i=0;
   while ( file_exists ($filename."_".$i.".rtf")){ $i++;}
   $PrintName = "IC_".$i.".rtf";
   $filename .= "_".$i.".rtf";
   //---------------------------------------------
   $fl = fopen ($filename,"w")
      or die("Невозможно создать файл  $filename.<BR>");
   include("../PlanCalculatFunc.php");
   CreateConnection();

   //Получение данных о специальности
   $result = mysql_query($q1, $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   $row=mysql_fetch_object($result);
   //print_r($row);
   $MinCode = $row->MinistryCode;
   //Файлы с фрагментами
   $start = "InfoCardParts/HeadPage.rtf";
   $level = "InfoCardParts/ListLevel.rtf";
   $colonStart = "InfoCardParts/ColonTitulStart.rtf";
   $colonEnd = "InfoCardParts/ColonTitulEnd.rtf";
   $head = "InfoCardParts/HeadTable.rtf";
   $div1 = "InfoCardParts/DivTable1.rtf";
   $div2 = "InfoCardParts/DivTable2.rtf";
   //Создание файла
   $fl = fopen ($filename,"w")
      or die("Невозможно создать файл  $filename.<BR>");
   FileCopy($fl,$start);

   $result = mysql_query($q2, $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   $j=1;
   while ($row = mysql_fetch_object($result)){
      for ($k=1; $k<=2; $k++){ //по 2 списка ля каждого потока
         FileCopy($fl,$level);
         fwrite ($fl,"\\listid".($j+2)."}");
         $j++;
      }
   }
   fwrite($fl,"}{\\*\\listoverridetable");
   for ($i=1; $i<$j; $i++){
     fwrite($fl,"{\\listoverride\\listid".($i+2)."\\listoverridecount0\\ls$i}");
   }
   fwrite($fl,"} ");

   FileCopy($fl,$colonStart);
   fwrite ($fl,"Экзамены и зачеты специальности $MinCode ");
   FileCopy($fl,$colonEnd);
   $year="";
   if (date("m")>6){$year=date("Y")."/".date("Y",strtotime ("+1 year"));}
   else{$year=date("Y",strtotime ("-1 year"))."/".date("Y");}
   fwrite ($fl,"Информационная карта экзаменов и зачетов \\par \n");
   fwrite ($fl,"Специальности $MinCode \\par \n");
   fwrite ($fl,"$Sm семестр  $year уч год\\par} \n");
   FileCopy($fl,$head);
   $result = mysql_query($q3, $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   //Все потоки
   $Stream = 1;
   while ($row = mysql_fetch_object($result)){
      //print_r($row);
      $StName = $row->StreamName;
      $StKurs = $row->Kurs;
      $StPlan = $row->CodeOfPlan;
      $StSm = ($StKurs - 1)*2 + $Sm; 
      fwrite($fl,"{\\fs24\\f31 $StName"."-"."$StKurs");
      FileCopy($fl,$div1);
      $resultEx = mysql_query("
         select disciplins.DisName, 
         schedplan.UndCicCode, schedplan.CodeOfUndCicle, undercicles.UndCicReduction, schedplan.CodeOfCicle
         from schedplan, disciplins, undercicles, schplanitems 
         where  
         schedplan.CodeOfUndCicle=undercicles.CodeOfUnderCicle and 
         disciplins.CodeOfDiscipline=schedplan.CodeOfDiscipline and
         schedplan.CodeOfSchPlan=schplanitems.CodeOfSchPlan
         and schplanitems.Exam>0 
         and schedplan.CodeOfPlan=$StPlan 
         and schplanitems.NumbOfSemestr=$StSm 
         order by schedplan.CodeOfCicle, schedplan.CodeOfUndCicle, schedplan.UndCicCode
         ",$Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
      $resultTs = mysql_query("
         select disciplins.DisName, schplanitems.KursWork, schplanitems.KursPrj,
         schedplan.UndCicCode, schedplan.CodeOfUndCicle, undercicles.UndCicReduction, schedplan.CodeOfCicle
         from schedplan, disciplins, undercicles, schplanitems 
         where 
         schedplan.CodeOfUndCicle=undercicles.CodeOfUnderCicle and 
         disciplins.CodeOfDiscipline=schedplan.CodeOfDiscipline and
         schedplan.CodeOfSchPlan=schplanitems.CodeOfSchPlan
         and (schplanitems.Test>0 
         or schplanitems.KursWork>0 
         or schplanitems.KursPrj>0) 
         and schedplan.CodeOfPlan=$StPlan 
         and schplanitems.NumbOfSemestr=$StSm 
         order by schedplan.CodeOfCicle, schedplan.CodeOfUndCicle, schedplan.UndCicCode
         ",$Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
      $DisTestCodes = array();   //Массив для уже встречавшихся кодов дисциплин повыбору
      $DisExamCodes = array();   //Массив для уже встречавшихся кодов дисциплин повыбору
      $Exams = array();   
      $Tests = array();   
      $StrCount = 0;
      $rowTs = mysql_fetch_object($resultTs);                                       
//      print_r($rowTs);
      $rowEx = mysql_fetch_object($resultEx);
//      print_r($rowEx);
      //Заполняем массив
      while (($rowTs)||($rowEx)){
         $TsDis="";    
         $ExDis="";
         if ($rowTs){
             $TsDis=$rowTs->DisName;
             if ($rowTs->KursWork!=0){$TsDis .= "(кр.р.)";}
             if ($rowTs->KursPrj!=0){$TsDis .= "(кр.пр.)";}
             if((strlen($rowTs->UndCicCode)>2) && ($rowTs->CodeOfUndCicle==4)){
                $NewDC=substr($rowTs->UndCicCode,0,2);
                $ToCount = 1;
                while ($DCode = each($DisTestCodes)){
                   //если подсчитывали то заносим соответствующее значение
                   if ((strcmp($NewDC, $DCode[1][0]) == 0)&&
                     ($rowTs->CodeOfCicle == $DCode[1][1])){ $ToCount = 0;}
                }
                //Если таких дисциплин еще не встречалось то 
                //сохраняем данные о ней и суммируем
                if ($ToCount){
                   $DisTestCodes[] = array($NewDC, $rowTs->CodeOfCicle); 
                   $Tests[] = $TsDis; 
                }else{
                   $Tests[$StrCount-1] .= "/".$TsDis;
                }
             }else{ $Tests[] = $TsDis;}
         }
         if ($rowEx){
             $ExDis=$rowEx->DisName;
             if((strlen($rowEx->UndCicCode)>2) && ($rowEx->CodeOfUndCicle==4)){
                $NewDC=substr($rowEx->UndCicCode,0,2);
                $ToCount = 1;
                while ($DCode = each($DisExamCodes)){
                   //если подсчитывали то заносим соответствующее значение
                   if ((strcmp($NewDC, $DCode[1][0]) == 0)&&
                     ($rowEx->CodeOfCicle == $DCode[1][1])){ $ToCount = 0;}
                }
                //Если таких дисциплин еще не встречалось то 
                //сохраняем данные о ней и суммируем
                if ($ToCount){
                   $DisExamCodes[] = array($NewDC, $rowEx->CodeOfCicle); 
                   $Exams[] = $ExDis; 
                }else{
                   $Exams[$StrCount-1] .= "/".$ExDis;
                }
             }else{ $Exams[] = $ExDis;}

         }
         $rowTs = mysql_fetch_object($resultTs);
         $rowEx = mysql_fetch_object($resultEx);
         $StrCount++;
      }
      $StTs = each($Tests);
      $StEx = each($Exams);
      $i = 1;
      while (($StTs)||($StEx)){
         if ($StTs){
            fwrite($fl,"{\\listtext\\pard\\plain\\intbl\\fs24\\lang1033\\langfe1049\\langnp103 \\hich\\af0\\dbch\\af0\\loch\\f0 $i.\\tab}\\pard \\ql \\fi-360\\li720\\ri0\\widctlpar\\intbl\n");
            fwrite($fl,"\\jclisttab\\tx720\\aspalpha\\aspnum\\faauto\\ls".($Stream*2 - 1)."\\adjustright\\rin0\\lin720\n");
            fwrite($fl,"{\\f31\\fs24\\lang1033\\langfe1049\\langnp1033 ".$StTs[1]."\\cell\n");
         }else {fwrite ($fl,"\\pard \\ql \\li360\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin360 {\\cell");}
         if ($StEx){
            fwrite($fl,"{\\listtext\\pard\\plain\\intbl\\fs24\\lang1033\\langfe1049\\langnp1033 \\hich\\af0\\dbch\\af0\\loch\\f0 $i.\\tab}\n");
            fwrite($fl,"\\pard \\ql \\fi-360\\li720\\ri0\\widctlpar\\intbl\\jclisttab\\tx720\\aspalpha\\aspnum\\faauto\\ls".($Stream*2)."\\adjustright\\rin0\\lin720\n");
            fwrite($fl,"{\\f31\\fs24\\lang1033\\langfe1049\\langnp1033 ".$StEx[1]." \\cell }}\n");
         }else { fwrite($fl,"\\pard \\ql \\li360\\ri0\\widctlpar\\intbl\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin360 \\cell }\n");}
         FileCopy($fl,$div2);
         //Выбираем следующие дисциплины
         $i++;
         $StTs = each($Tests);
         $StEx = each($Exams);
      }
      mysql_free_result($resultEx);
      mysql_free_result($resultTs);
      $Stream++;
   }

   //Подписанты
   $result = mysql_query($q4, $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   $row=mysql_fetch_object($result);
   fwrite($fl,"\\pard \\qj \\fi-142\\li0\\ri0\\sb240\\keep\\keepn\\widctlpar\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\itap0 ");
   fwrite($fl,"{\\f31\\fs24 Зав. кафедрой                                   ".$row->ZavSignature."\\par}");
   fwrite($fl,"{\\f31\\fs24 Декан                                       ".$row->DeanSignature."\\par}");
   //Закрываем файл
   fwrite($fl,"}");
   fclose($fl);
   echo "<H2>Информационная карта находится в файле: <A HREF=\"$filename\">$PrintName</A></h2></P>";
   mysql_free_result($result);
   mysql_close($Connection);

?>
<HR>
</BODY>
</HTML>
