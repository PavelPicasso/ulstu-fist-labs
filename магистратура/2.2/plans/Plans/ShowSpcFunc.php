<?php
Function ShowStreams(){
   global $TblName;
   global $TblCode;
   global $Connection;
   global $pln;
   echo "<h1>Учебная нагрузка</h1>";
   if (strcmp($TblName,"spz")==0){
      echo "<em class=\"h2\">Потоки специализации: <em>";
      $q = "select distinct streams.StreamName from streams, plans, specializations where streams.CodeOfPlan=plans.CodeOfPlan and plans.CodeOfSpecialization =".$TblCode." order by streams.StreamName";
   }else{
      if (strcmp($TblName,"spc")==0){
         echo "<em class=\"h2\">Потоки специальности: </em>";
         $q = "select distinct streams.StreamName from streams, plans where streams.CodeOfPlan=plans.CodeOfPlan and plans.CodeOfSpecial =".$TblCode." and plans.CodeOfSpecialization=0 order by streams.StreamName ";
      }else{
         if (strcmp($TblName,"dir")==0){
            echo "<em class=\"h2\">Потоки направления: </em>";
            $q = "select distinct streams.StreamName from streams, plans, directions where streams.CodeOfPlan=plans.CodeOfPlan and (plans.CodeOfDirect =".$TblCode." and (plans.CodeOfSpecial=0) and (plans.CodeOfSpecialization=0)) order by streams.StreamName ";
         }else{
            echo "</body></html>\n";
            exit;
         }
      }
   }
  
   $result=mysql_query($q, $Connection) 
      or die("Query failed:".mysql_errno().": ".mysql_error()."<BR>");
   $i = 0;
   while($row=mysql_fetch_object($result)){
      echo  "<strong>";
      if ($i>0){echo ",&nbsp;";}
      echo  "<a href=\"StreamPlan.php?strNm=$row->StreamName&plan=$pln";
      if (strcmp($TblName,"spz")==0){
         echo "&spz=$TblCode\">";
      }else{
         if (strcmp($TblName,"spc")==0){
            echo "&spc=$TblCode\">";
         }else{
            if (strcmp($TblName,"dir")==0){
               echo "&dir=$TblCode\">";
            }
         }
      }
      echo  "$row->StreamName</strong></a>";
      $i++;
   }
   if ($i == 0){echo "<em class=\"h2\">неопределены</em>";}
}

//Функция выводит даные о стандарте 
Function ShowStandards($q){
   global $Connection;

   //Вывод информации о стандартах
   $result=mysql_query($q, $Connection) 
      or die("Query failed:".mysql_errno().": ".mysql_error()."<BR>");
   if (mysql_num_rows($result)>0){
      echo "<h1>Стандарт:</h1>\n";
      echo "<br><table   class='ramka' border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"97%\" align=\"center\" bgcolor=\"0040A0\">\n";
      echo "<tr><td cellpadding=\"0\" cellspacing=\"0\">\n";
      echo "<TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>\n";
      echo "<TR>\n";
      echo "<TD ALIGN=CENTER bgcolor=\"ffffff\"><font  face=\"arial\" size=\"2\"><b>Всего часов обучения</b></font></TD>\n";
      echo "<TD ALIGN=CENTER bgcolor=\"ffffff\"><font  face=\"arial\" size=\"2\"><b>Общий объем занятий (недели)</b></font></TH>\n";
      echo "<TD ALIGN=CENTER bgcolor=\"ffffff\"><font  face=\"arial\" size=\"2\"><b>Теоретическое обучение (недели)</b></font></TD>\n";
      echo "<TD ALIGN=CENTER bgcolor=\"ffffff\"><font  face=\"arial\" size=\"2\"><b>Экзаменационная сессия (недели)</b></font></TD>\n";
      echo "<TD ALIGN=CENTER bgcolor=\"ffffff\"><font  face=\"arial\" size=\"2\"><b>Практика (недели)</b></font></TD>\n";
      echo "<TD ALIGN=CENTER bgcolor=\"ffffff\"><font  face=\"arial\" size=\"2\"><b>Итоговая аттестация (недели)</b></font></TD>\n";
      echo "<TD ALIGN=CENTER bgcolor=\"ffffff\"><font  face=\"arial\" size=\"2\"><b>Каникулы (недели)</b></font></TD>\n";
      echo "<TD ALIGN=CENTER bgcolor=\"ffffff\"><font  face=\"arial\" size=\"2\"><b>Дата утверждения</b></font></TD>\n";
      echo "<TD ALIGN=CENTER bgcolor=\"ffffff\"><font  face=\"arial\" size=\"2\"><b>Файл стандарта</b></font></TD>\n";
      echo "<TD ALIGN=CENTER bgcolor=\"ffffff\"><font  face=\"arial\" size=\"2\"><b>Файлы типовых планов</b></font></TD>\n";
      echo "</TR>\n";
      $MetaResult = mysql_query("select * from metalimits", $Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
      $Stand= "";
      if ($row = mysql_fetch_object($MetaResult)){
         $Stand= $row->LimitsAdr;
      }
   }else{ return;}
   
   while ($row = mysql_fetch_object($result)){
      $StDate = $row->StDate     ;
      $day = substr($StDate,8,2);
      $month = substr($StDate,5,2);
      $year = substr($StDate,0,4);
      $Practice =$row->TeachPractice+$row->WorkPractice+$row->DiplomPractice;       
      echo "<TR>\n";
      echo " <TD align='center' bgcolor=\"ffffff\"><font  face=\"arial\" size=\"2\">".$row->TotalHours."</font></TD>\n";
      echo " <TD align='center' bgcolor=\"ffffff\"><font  face=\"arial\" size=\"2\">".$row->TotalVolume."</font></TD>\n";
      echo " <TD align='center' bgcolor=\"ffffff\"><font  face=\"arial\" size=\"2\">".$row->TheorlTeach."</font></TD>\n";
      echo " <TD align='center' bgcolor=\"ffffff\"><font  face=\"arial\" size=\"2\">".$row->Exams."</font></TD>\n";
      echo " <TD align='center' bgcolor=\"ffffff\"><font  face=\"arial\" size=\"2\">".$Practice."</font></TD>\n";
      echo " <TD align='center' bgcolor=\"ffffff\"><font  face=\"arial\" size=\"2\">".$row->Attestation."</font></TD>\n";
      echo " <TD align='center' bgcolor=\"ffffff\"><font  face=\"arial\" size=\"2\">".$row->Vacation."</font></TD>\n";
      echo " <TD align='center' bgcolor=\"ffffff\"><font  face=\"arial\" size=\"2\">".$day."-".$month."-".$year."</font></TD>\n";
      echo " <TD align='center' bgcolor=\"ffffff\"><font  face=\"arial\" size=\"2\"><a href=\"".$Stand.$row->LimitFile."\" >".$row->LimitFile."</a></font></TD>\n";
      echo " <TD align='center' bgcolor=\"ffffff\">";
      $resTP = mysql_query("select * from standardplans where CodeOfStandard=".$row->CodeOfStandard, $Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
      while ($rowTP = mysql_fetch_object($resTP)){
         echo "<font  face=\"arial\" size=\"2\"><a href=\"".$Stand.$rowTP->PlanFile."\" >".$rowTP->PlanFile."</a></font><BR>\n";
      }
      echo " </TD>\n";
      echo " </TR>\n";

   }

   echo " </TABLE></TD></TR></TABLE><BR>\n";
}
?>