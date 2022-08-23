<?php 
    include("cfg.php"); 
?>
<HTML>
<HEAD>
<TITLE>Посеместровая нагрузка студентов.</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<link rel="stylesheet" href="../CSS/Plans.css" type="text/css"></HEAD>

<BODY topmargin="2" leftmargin="2" marginheight="2" marginwidth="2" >
<center>
<h1>Учебная нагрузка потока
<?php
   set_time_limit(60*3);
   if (!($_GET['strNm'])) {exit;}
   $StreamNm=$_GET['strNm'];
   $pln=$_GET['plan'];
   echo "$StreamNm специальности</h1>";
   include("Editor/PlanCalculatFunc.php");
   $Connection=mysql_connect($data_source , $dbi_user , $dbi_password) 
      or die ("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
   mysql_select_db("plans") 
      or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");
    
   $spc_condition = "";
   $name_trans = "Потоки";
   $time_all = 0;
   $date_trans = date("Y-m-d h:i:s");
   $time_b = getmicrotime();
   if (isset($_GET['spz'])){
      $result=mysql_query("select * from specializations where CodeOfSpecialization=".$_GET['spz'],$Connection) 
         or die("Query failed:".mysql_errno().": ".mysql_error()."<BR>");
      $time_e = getmicrotime();
      if($res=mysql_fetch_object($result)){
         echo "<h2>$res->MinistryCode";
         echo "&nbsp;&nbsp;&nbsp;";
         echo "$res->SpzName</h2>";
         echo "</center>";
      }
      $q = "select distinct streams.StreamName from streams, plans, specializations where streams.CodeOfPlan=plans.CodeOfPlan and plans.CodeOfSpecialization =".$_GET['spz']." and streams.CodeOfPlan=".$pln." order by streams.StreamName";
      $spc_condition = "plans.CodeOfSpecialization =".$_GET['spz'];
   }else{
      if (isset($_GET['spc'])){
         $result=mysql_query("select * from specials where CodeOfSpecial=".$_GET['spc'],$Connection) 
            or die("Query failed:".mysql_errno().": ".mysql_error()."<BR>");
         $time_e = getmicrotime();
         if($res=mysql_fetch_object($result)){
            echo "<h2>$res->MinistryCode";
            echo "&nbsp;&nbsp;&nbsp;";
            echo "$res->SpcName</h2>";
            echo "</center>";
         }
         $q = "select distinct streams.StreamName from streams, plans, specials where streams.CodeOfPlan=plans.CodeOfPlan and (plans.CodeOfSpecial =".$spc." and (plans.CodeOfSpecialization=0) and streams.CodeOfPlan=".$pln.") order by streams.StreamName ";
        $spc_condition = "(plans.CodeOfSpecial =".$_GET['spc']." and (plans.CodeOfSpecialization=0))";
      }else{
         if (isset ($_GET['dir'])){
            $result=mysql_query("select * from directions where CodeOfDirect=".$_GET['dir'],$Connection) 
               or die("Query failed:".mysql_errno().": ".mysql_error()."<BR>");
            $time_e = getmicrotime();
            if($res=mysql_fetch_object($result)){
               echo "<h2>$res->MinistryCode";
               echo "&nbsp;&nbsp;&nbsp;";
               echo "$res->DirName</h2>";
               echo "</center>";
            }
            $q = "select distinct streams.StreamName from streams, plans, directions where streams.CodeOfPlan=plans.CodeOfPlan and (plans.CodeOfDirect =".$dir." and (plans.CodeOfSpecial=0) and (plans.CodeOfSpecialization=0) and streams.CodeOfPlan=".$pln.") order by streams.StreamName ";
        $spc_condition = "(plans.CodeOfDirect =".$_GET['dir']." and (plans.CodeOfSpecial=0) and (plans.CodeOfSpecialization=0))";
         }else{
            echo "</body></html>\n";
            exit;
         }
      }
   }
   $time_all += $time_e-$time_b;
?>
<table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center">
<tr><td cellpadding="0" cellspacing="0"  bgcolor="0040A0">
<TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
<TR>
<td ALIGN=CENTER ROWSPAN="2"><strong>Курс</strong></td>
<td ALIGN=CENTER ROWSPAN="2"><strong>Семестр</strong></td>
<td ALIGN=CENTER ROWSPAN="2"><strong>Дисциплина</strong></td>
<td ALIGN=CENTER ROWSPAN="2"><strong>Экз.</strong></td>
<td ALIGN=CENTER ROWSPAN="2"><strong>Зач.</strong></td>
<td ALIGN=CENTER ROWSPAN="2"><strong>Курс. пр.</strong></td>
<td ALIGN=CENTER ROWSPAN="2"><strong>Курс. раб.</strong></td>
<td ALIGN=CENTER ROWSPAN="2"><strong>РГР</strong></td>
<td ALIGN=CENTER ROWSPAN="2"><strong>Реф.</strong></td>
<td ALIGN=CENTER COLSPAN="3"><strong>Занятий в неделю</strong></td>
</TR>
<TR>
<td ALIGN=CENTER><strong>Лекций</strong></td>
<td ALIGN=CENTER><strong>Лаб. раб.</strong></td>
<td ALIGN=CENTER><strong>Прак. зан.</strong></td>
</TR>
<?php 
   $time_b = getmicrotime();
   $result=mysql_query("select distinct CodeOfStream, GroupCount, StdCount, StreamName, Kurs, plans.CodeOfPlan from plans, streams where plans.CodeOfPlan=streams.CodeOfPlan and streams.StreamName='$StreamNm' and $spc_condition and streams.CodeOfPlan=".$pln." order by Kurs",$Connection) 
      or die("Query failed:".mysql_errno().": ".mysql_error()."<BR>");
   $time_e = getmicrotime();
   $time_all += $time_e-$time_b;
   while($res = mysql_fetch_object($result)){//проход по всем курсам
      $Kurs = $res->Kurs;
      $PlCode = $res->CodeOfPlan;
      $DisRowArrayCount=array();
      $index= 0;
      $DisRowArray=array();//Заполняем массив дисциплин 1-ого семестра
      for ($i=$Kurs*2-1; $i<=$Kurs*2; $i++) {
          $index ++;
          $DisRowArrayCount[$index]=0;
          $DisRowArray[$index]=array();//Заполняем массив дисциплин 1-ого семестра
          $time_b = getmicrotime();
          $resultDis = mysql_query("select distinct disciplins.DisName, schplanitems.LectInW, schplanitems.PractInW, schplanitems.LabInW , schplanitems.Exam, schplanitems.Test, schplanitems.KursWork, schplanitems.KursPrj, schplanitems.RGR, schplanitems.Synopsis from schplanitems, schedplan, disciplins where CodeOfPlan=$PlCode and NumbOfSemestr='$i' and disciplins.CodeOfDiscipline=schedplan.CodeOfDiscipline and schedplan.CodeOfSchPlan=schplanitems.CodeOfSchPlan",$Connection) 
              or die("Query failed:".mysql_errno().": ".mysql_error()."<BR>");
          $time_e = getmicrotime();
          $time_all += $time_e-$time_b;
          while ($resDis = mysql_fetch_object($resultDis)){
             $DisStr="<TD>$resDis->DisName</TD>\n";
             if ($resDis->Exam)
                 {$Exam="+";}
             else 
                 {$Exam="";}
             if ($resDis->Test)
                 {$Test="+";}
             else 
                 {$Test="";}
             if ($resDis->KursPrj){$KursPrj="+";}
             else {$KursPrj="";}
             if ($resDis->KursWork){$KursWork="+";}
             else {$KursWork="";}
             if ($resDis->RGR>0){$RGR=$resDis->RGR;}
             else{$RGR="";}
             if ($resDis->Synopsis>0){$Synopsis=$resDis->Synopsis;}
             else{$Synopsis="";}
             if ($resDis->LectInW>0){$LectInW=$resDis->LectInW;}
             else{$LectInW="";}
             if ($resDis->PractInW>0){$PractInW=$resDis->PractInW;}
             else{$PractInW="";}
             if (($resDis->LabInW)>0){$LabInW=$resDis->LabInW;}
             else{$LabInW="";}
             $DisStr.="<TD ALIGN=CENTER>$Exam</TD>\n";
             $DisStr.="<TD ALIGN=CENTER>$Test</TD>\n";
             $DisStr.="<TD ALIGN=CENTER>$KursPrj</TD>\n";
             $DisStr.="<TD ALIGN=CENTER>$KursWork</TD>\n";
             $DisStr.="<TD ALIGN=CENTER>$RGR</TD>\n";
             $DisStr.="<TD ALIGN=CENTER>$Synopsis</TD>\n";
             $DisStr.="<TD ALIGN=CENTER>$LectInW</TD>\n";
             $DisStr.="<TD ALIGN=CENTER>$PractInW</TD>\n";
             $DisStr.="<TD ALIGN=CENTER>$LabInW</TD>\n";
             $DisRowArray[$index][]=$DisStr;
             $DisRowArrayCount[$index]++;
          }
          if ($DisRowArrayCount[$index]==0){
             $DisRowArray[$index][]="<TD ALIGN=CENTER COLSPAN=\"10\"></TD>";
             $DisRowArrayCount[$index]++;
          }
      }
      echo  "<TR>\n";
      $Sum = $DisRowArrayCount["2"]+$DisRowArrayCount["1"];
      echo  "<TD ALIGN=\"CENTER\" ROWSPAN=\"".$Sum."\">$res->Kurs</TD>\n";
      //Вывод дисциплин курса
      echo  "<TD ALIGN=\"CENTER\" ROWSPAN=\"$DisRowArrayCount[1]\">".($Kurs*2-1)."</TD>\n";
      //Выводим  1-ю дисциплину
      echo  $DisRowArray[$index][0];
      echo  "</TR>\n";
      for ($i=1; $i<$DisRowArrayCount["1"]; $i++){
         echo  "<TR>\n";
         echo  $DisRowArray["1"][$i];
         echo  "</TR>\n";
      }
      echo  "<TR>\n";
      echo  "<TD ALIGN=\"CENTER\" ROWSPAN=\"$DisRowArrayCount[2]\">".($Kurs*2)."</TD>\n";
      echo  $DisRowArray["2"][0];
      echo  "</TR>\n";
      for ($i=1; $i<$DisRowArrayCount["2"]; $i++){
         echo  "<TR>\n";
         echo  $DisRowArray["2"][$i];
         echo  "</TR>\n";
      }
   }//вывод по курсам
   /* Освобождение resultset */
   mysql_free_result($result);
//   mysql_free_result($resultDis);
   /* Закрытие соединения */
   $id_sess = session_id();
   mysql_query("Insert into logs (name_trans, id_sess, date_trans, time_trans) values ('$name_trans', '$id_sess', '$date_trans', '$time_all')")
               or die("Unable to execute query:".mysql_errno().": ".mysql_error());
   mysql_close($Connection);
?>
</TABLE>
</tr></table></body>
</html>
