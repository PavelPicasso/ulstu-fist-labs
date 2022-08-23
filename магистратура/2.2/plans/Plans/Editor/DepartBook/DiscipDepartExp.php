<?php
   if (!($REQUEST_METHOD=='POST' && $_POST['flag'] && $_POST['depart'])) {
     Header ("Location: DepartBook.php");
     exit;
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<HTML>
<HEAD>
<TITLE>Учебные планы</TITLE>
<META NAME=Author CONTENT="Карпова Анна">
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<link rel="stylesheet" href="../../../CSS/PlansEditor.css" type="text/css">
</HEAD>
<BODY topmargin=1 leftmargin=5 marginheight="1" marginwidth="5">
<em class='h1'><center>Получение отчетов о дисциплинах закрепленных за кафедрами</center></em>
<table border='0' width='100%' cellpadding='0' cellspacing='2'>
<tr><td height='5' bgcolor="#92a2d9">
<img src="../img/line.gif" width=15 height=15 hspace=0 vspace=0 border='0' align='left'>
</td></tr>
</table>
<H2>Отчет находится в файле:</H2>
<?php
   $DepFlag = $flag;
   include("../../cfg.php");
   $Connection = mysql_connect($data_source , $dbi_user , $dbi_password)
      or die("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
   mysql_select_db("plans") 
      or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");
   /*  */
   include ("ExpDisDepFunc.php");
   $start = "ExpParts/StartPage.rtf";
   $level = "ExpParts/Listlevel.rtf";
   $colonStart = "ExpParts/ColonTitulStart.rtf";
   $colonEnd = "ExpParts/ColonTitulEnd.rtf";
   $startText = "ExpParts/StartText.rtf";
   $head = "ExpParts/HeadTable.rtf";
   $div = "ExpParts/DivTable.rtf";
   $fin = "ExpParts/EndTable.rtf";
   $AddQ = "";
   while ( $StreamCode = array_shift($flag)){
      if (strcmp($AddQ,"")==0){$AddQ = " and (streams.CodeOfStream=$StreamCode ";}
      else {$AddQ .= " or streams.CodeOfStream=$StreamCode" ;}
   }
   if (strcmp($AddQ,"")!=0){ $AddQ .= ") ";}
   $DepCode=$_POST['depart'];
   $result = mysql_query("select department.DepName, department.Reduction, faculty.DeanSignature, faculty.Reduction, department.ZavSignature  from department, faculty  where department.CodeOfDepart=$DepCode and department.CodeOfFaculty=faculty.CodeOfFaculty",$Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   $res=mysql_fetch_row($result);
   $DepName=$res[0];
   $DepRed=$res[1];
   $FDepSign=$res[2];
   $FDepRed=$res[3];
   $DepSign=$res[4];
   echo "<P ALIGN='CENTER'><h2>$DepRed: ";
   //Подбор имени файла---------------------------
   $filename="../../../Exported/Depart";
   $i=0;
   while ( file_exists ($filename."_".$i.".rtf")){ $i++;}
   $PrintName = "Depart_".$i.".rtf";
   $filename .= "_".$i.".rtf";
   //---------------------------------------------
   $fl = fopen ($filename,"w")
      or die("Невозможно создать файл  $filename.<BR>");
   FileCopy($fl,$start);
   $q = "select distinct faculty.CodeOfFaculty, faculty.FacName, faculty.Reduction, faculty.DeanSignature ".
      "from streams,  schplan, faculty, directions, specials,". 
      "specializations, plans ".
      "where ".
      "streams.CodeOfPlan=schplan.CodeOfPlan and ".
      "(schplan.NumbOfSemestr=streams.Kurs*2 or schplan.NumbOfSemestr=streams.Kurs*2-1) and ".
      "schplan.CodeOfDepart=$DepCode and ".
      "schplan.CodeOfPlan=plans.CodeOfPlan and ".
      "(".
      "(plans.CodeOfDirect=directions.CodeOfDirect and ".
      "plans.CodeOfSpecial is NULL and ".
      "plans.CodeOfSpecialization is NULL and ".
      "directions.CodeOfFaculty=faculty.CodeOfFaculty) or".
      "(plans.CodeOfSpecial=specials.CodeOfSpecial and ".
      "plans.CodeOfSpecialization is NULL and ".
      "specials.CodeOfFaculty=faculty.CodeOfFaculty) or".
      "(plans.CodeOfSpecialization=specializations.CodeOfSpecial and ".
      "plans.CodeOfSpecialization is NULL and ".
      "specializations.CodeOfFaculty=faculty.CodeOfFaculty)".
      ")". 
      $AddQ;
   $result = mysql_query($q,$Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   $j=1;
   $Faulty = array();
   while ($row = mysql_fetch_object($result)){
      FileCopy($fl,$level);
      fwrite ($fl,"\\listid".($j+2)."}");
      $Faculty[] = array($row->CodeOfFaculty, $row->FacName, $row->DeanSignature, $row->Reduction);
      $j++;
   }
//   fwrite($fl,"}{\*\listoverridetable{\\listoverride\\listid1949892850\\listoverridecount0\\ls1}{\\listoverride\\listid408232120\\listoverridecount0\\ls2}{\\listoverride\\listid275453341\\listoverridecount0\\ls3}{\\listoverride\\listid1717509102\\listoverridecount0\\ls4}{\\listoverride\\listid2000377536\\listoverridecount0\\ls5}");
   fwrite($fl,"}{\\*\\listoverridetable");
   for ($i=1; $i<$j; $i++){
     fwrite($fl,"{\\listoverride\\listid".($i+2)."\\listoverridecount0\\ls$i}");
   }
   fwrite($fl,"} ");

   FileCopy($fl,$colonStart);
   fwrite ($fl,"Дисциплины кафедры $DepRed ");
   FileCopy($fl,$colonEnd);
      //Обрабатываем запрос
   $WC = 0;         //Число недель обучения
   $q = array();
   $j = 1;
   while ($fc = each($Faculty)){
      FileCopy($fl,$startText);
      fwrite($fl,"Перечень дисциплин, закрепленных за кафедрой } {\\f32\\fs28\\ul$DepName}");
      fwrite($fl,"{\\f32\\fs28  на факультете }");
      fwrite($fl,"{\\f32\\fs28\\ul $fc[1][1]");
      FileCopy($fl,$head);
      $i=1; //Счетчик дисциплин
      $q[] = "select * ".
         "from streams,  schplan, directions, plans, disciplins, cicles ".
         "where ".
         "streams.CodeOfPlan=schplan.CodeOfPlan and ".
         "(schplan.NumbOfSemestr=streams.Kurs*2 or schplan.NumbOfSemestr=streams.Kurs*2-1) and ".
         "schplan.CodeOfDepart=$DepCode and ".
         "disciplins.CodeOfDiscipline=schplan.CodeOfDiscipline and ".
         "cicles.CodeOfCicle=schplan.CodeOfCicle and ".
         "schplan.CodeOfPlan=plans.CodeOfPlan and ".
         "plans.CodeOfDirect=directions.CodeOfDirect ". 
         "and plans.CodeOfSpecial is NULL and plans.CodeOfSpecialization is NULL and ".
         "directions.CodeOfFaculty=".$fc[1][0].$AddQ.
         "order by directions.CodeOfDirect, cicles.CodeOfCicle, disciplins.DisName, streams.StreamName, schplan.NumbOfSemestr ";
      $q[] = "select * ".
         "from streams,  schplan, specials, plans, disciplins, cicles ".
         "where ".
         "streams.CodeOfPlan=schplan.CodeOfPlan and ".
         "(schplan.NumbOfSemestr=streams.Kurs*2 or schplan.NumbOfSemestr=streams.Kurs*2-1) and ".
         "schplan.CodeOfDepart=$DepCode and ".
         "disciplins.CodeOfDiscipline=schplan.CodeOfDiscipline and ".
         "cicles.CodeOfCicle=schplan.CodeOfCicle and ".
         "schplan.CodeOfPlan=plans.CodeOfPlan and ".
         "plans.CodeOfSpecial=specials.CodeOfSpecial and plans.CodeOfSpecialization is NULL and ".
         "specials.CodeOfFaculty=".$fc[1][0].$AddQ.
         "order by specials.CodeOfSpecial, cicles.CodeOfCicle, disciplins.DisName, streams.StreamName, schplan.NumbOfSemestr";
      $q[] = "select * ".
         "from streams,  schplan, specializations, plans, disciplins, cicles ".
         "where ".
         "streams.CodeOfPlan=schplan.CodeOfPlan and ".
         "(schplan.NumbOfSemestr=streams.Kurs*2 or schplan.NumbOfSemestr=streams.Kurs*2-1) and ".
         "schplan.CodeOfDepart=$DepCode and ".
         "disciplins.CodeOfDiscipline=schplan.CodeOfDiscipline and ".
         "cicles.CodeOfCicle=schplan.CodeOfCicle and ".
         "schplan.CodeOfPlan=plans.CodeOfPlan and ".
         "plans.CodeOfSpecialization=specializations.CodeOfSpecialization and ".
         "specializations.CodeOfFaculty=".$fc[1][0].$AddQ.
         "order by specializations.CodeOfSpecialization, cicles.CodeOfCicle, disciplins.DisName, streams.StreamName, schplan.NumbOfSemestr";
      while ($qw = each($q)){
         $result = mysql_query($qw[1],$Connection)
            or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
         while ($res = mysql_fetch_object($result)){
            $PCode = $res->CodeOfPlan;         //код плана
            $CCode = $res->CodeOfCicle;        //код цикла
            $CRed = $res->CicReduction;        //сокращенное название цикла дисциплин
            $SpCode = $res->CodeOfSpecial;     //Код специальности
            $SpRed = $res->MinistryCode;       //Минист. код специальности
            $DisName = $res->DisName;          //Название дисциплины
            $DisCode = $res->CodeOfDiscipline; //Название дисциплины
            $Sm = $res->NumbOfSemestr;         //номер семестра
            $Kurs = $res->Kurs;                //номер курса
            $Lec = $res->LectInW;              //лекций
            $Lab = $res->LabInW;               //лаб. раб. 
            $Pract = $res->PractInW;           //практик
            $KP = $res->KursPrj;               //курс. проект
            $KW = $res->KursWork;              //курс. работа
            $Ex = $res->Exam;                  //Экзамен
            $Test = $res->Test;                //Зачет
            $Ref = $res->Synopsis;             //Реферат
            $RGR = $res->RGR;                  //РГР
            $WC = TeachWeek($PCode,$Sm,$Kurs);
            $Lec = $WC*$Lec;
            $Lab = $WC*$Lab;
            $Pract = $WC*$Pract;
            $Ex = $Ex;
            $Test = $Test;
            $KP = $KP;
            $KW = $KW;
            $RGR = $RGR;
            $Ref = $Ref;
            //Выводим дисциплину
            PrintDiscip($fl);  //выводим последнюю дисциплину
         }//Вывод дисциплины
      }//вывод по направлениям, специальностям, специализациям
      FileCopy($fl,$fin);
      $j++;
   }//Вывод по факультетам
   //Подписанты
   fwrite($fl,"\\pard \\qj \\fi-142\\li0\\ri0\\sb240\\keep\\keepn\\widctlpar\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\itap0 ");
   fwrite($fl,"{\\f32\\fs28 Зав. кафедрой $DepRed ____________________$DepSign                                     Согласовано: \\par }");
   fwrite($fl,"\\pard \\qj \\fi9356\\li0\\ri0\\widctlpar\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\itap0 {\\f32\\fs28 Декан ".$FDepRed."______________ $FDepSign\\par }");
   //Закрываем файл
   fwrite($fl,"}");
   fclose($fl);
   echo "<A HREF=\"$filename\">$PrintName</A></h2></P>";
   mysql_free_result($result);
   mysql_close($Connection);

?>
<HR>
</BODY>
</HTML>
