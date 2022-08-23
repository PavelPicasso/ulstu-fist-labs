<?php
//Функция выводит учебные планы факультета упорядоченые 
//по параметру таблицы учебных планов - $param
Function ShowByPlanParams($param){
   global $AllCodes;
   global $Names;
   global $Codes;
   global $Degrees;
   global $CodeOfFaculty;
   global $Connection;
   global $TForms;
   global $PlanHref;
   global $FullPlanHref;
   global $SpcHref;
   global $ReportPlanHref;
   global $SocrPlanHref;
   global $HoursPlanHref;

   $q = "select distinct plans.CodeOfPlan, plans.CodeOfDirect, plans.CodeOfSpecial, ". 
        "plans.CodeOfSpecialization, plans.PlnName, ". 
        "plans.FixDate, plans.TeachForm from plans, directions,". 
        "specials, specializations ". 
        "where ((plans.CodeOfDirect=directions.CodeOfDirect and ". 
        "plans.CodeOfDirect<>0 and ".
        "directions.CodeOfFaculty=".$CodeOfFaculty.") or ".
        "(plans.CodeOfSpecial=specials.CodeOfSpecial and ". 
        "plans. CodeofSpecial<>0 and ".
        "specials.CodeOfFaculty=".$CodeOfFaculty.") or ".
        "(plans.CodeOfSpecialization=specializations.CodeOfSpecialization and ". 
        "plans.CodeOfSpecialization<>0 and ".
		"specializations.CodeOfFaculty=".$CodeOfFaculty.")) and (plans.FixDate is NULL)" .
	//"specializations.CodeOfFaculty=".$CodeOfFaculty.")) and (plans.DateArchive is NULL)" .
        "order by plans.".$param.", plans.CodeOfDirect, plans.CodeOfSpecial, plans.CodeOfSpecialization, plans.PlnName";
   $result = mysql_query($q, $Connection) 
      or die("Query failed:".mysql_errno().": ".mysql_error()."<BR>");
   while ($row = mysql_fetch_object($result)){
      $k = "??";
      if (isset($row->CodeOfSpecialization) && ($row->CodeOfSpecialization<>0)){
         $k = "spz".$row->CodeOfSpecialization;
      }else{
         if (isset($row->CodeOfSpecial) && ($row->CodeOfSpecial<>0)){
            $k = "spc".$row->CodeOfSpecial;
         }else{
            if (isset($row->CodeOfDirect) && ($row->CodeOfDirect<>0)){
               $k = "dir".$row->CodeOfDirect;
            }
         }
      }
      if (isset($AllCodes[$k])){
         unset ($AllCodes[$k]);
      }

      $MinCode = $Codes[$k];

      $MinName = $Names[$k];

      $Degree = $Degrees[$k];

      $TForm = $TForms[$row->TeachForm];

      $StrDate = $row->FixDate;
      $day = substr($StrDate,8,2);
      $month = substr($StrDate,5,2);
      $year = substr($StrDate,0,4);
      $PlCode = $row->CodeOfPlan;
      $PlName = $row->PlnName;

      echo "<TR><TD VALIGN=CENTER ALIGN=CENTER><B><font  face=\"courier\" size=\"4\">".$MinCode."</FONT></B>";
      echo "</TD>";
      echo "<TD VALIGN=CENTER ALIGN=LEFT>&nbsp;<font  face=\"courier\" size=\"4\"><a href=\"".$SpcHref."&code=".$k."&plan=".$PlCode."\">".$MinName."</a></FONT>";
      echo "</TD>";
      echo "<TD VALIGN=CENTER ALIGN=CENTER><font  face=\"courier\" size=\"3\">".$Degree."</FONT>";
      echo "</TD>";
      echo "<TD VALIGN=CENTER ALIGN=LEFT>&nbsp;<font  face=\"courier\" size=\"4\">".$PlName."</FONT>";
      echo "</TD>";
      echo "<TD VALIGN=CENTER ALIGN=CENTER><font  face=\"courier\" size=\"4\">".$TForm."</FONT>";
      echo "</TD>";
      echo "<TD VALIGN=CENTER ALIGN=CENTER><font  face=\"courier\" size=\"4\">".$day."-".$month."-".$year."</FONT>";
      echo "</TD>";
      echo "<TD VALIGN=CENTER ALIGN=CENTER><font  face=\"courier\" size=\"-1\"><a href=\"".$PlanHref."&plan=".$PlCode."\">Tипо вой</a></font>";
      echo "</TD>";
      echo "<TD VALIGN=CENTER ALIGN=CENTER><font    face=\"courier\" size=\"-1\"><a href=\"".$FullPlanHref."&plan=".$PlCode."\">Деталь ный</a></font>";
      echo "</TD>";
      echo "<TD VALIGN=CENTER ALIGN=CENTER><font    face=\"courier\" size=\"-1\"><a href=\"".$SocrPlanHref."?plan=".$PlCode."\">Сокра щенный</a></font>";
      echo "</TD>";
      echo "<TD VALIGN=CENTER ALIGN=CENTER><font    face=\"courier\" size=\"-1\"><a href=\"".$HoursPlanHref."?plan=".$PlCode."\" >Объем занятий</a></font>";
      echo "</TD></TR>";
   }
}


//Функция выводит учебные плны упорядоченные 
//по параметру направления, специальности или специализации
//$param - код параметра
Function ShowBySpcParams($param){
   global $Names;
   global $Codes;
   global $Degrees;
   global $Connection;
   global $TForms;
   global $PlanHref;
   global $SpcHref;
   global $FullPlanHref;
   global $ReportPlanHref;
   global $SocrPlanHref;
   global $HoursPlanHref;
   global $AllCodes;
   
   if ($param == 1){
      $SortArr = $Codes;
   }else{
      if ($param == 2){
         $SortArr = $Names;
      }else{
         if ($param == 3){
            $SortArr = $Degrees;
         }
      }
   }
   
   asort($SortArr);
   reset($SortArr);

   while ($SortKey = each($SortArr)){
      if (isset($AllCodes[$SortKey[0]])){
         $MinCode = $Codes[$SortKey[0]];
   
         $MinName = $Names[$SortKey[0]];
   
         $Degree = $Degrees[$SortKey[0]];
   
         $TblName = substr($SortKey[0], 0 ,3);
         $TblCode = substr($SortKey[0], 3 );
         if (strcmp($TblName,"dir") == 0){
            $q = "select * from plans where plans.CodeOfSpecial=0 and plans.CodeOfSpecialization=0 and plans.CodeOfDirect=".$TblCode." and (plans.FixDate is NULL)";
			//$q = "select * from plans where plans.CodeOfSpecial=0 and plans.CodeOfSpecialization=0 and plans.CodeOfDirect=".$TblCode." and (plans.DateArchive is NULL)";
         }else{
            if (strcmp($TblName,"spc") == 0){
               $q = "select * from plans where plans.CodeOfSpecialization=0 and plans.CodeOfSpecial=".$TblCode." and (plans.FixDate is NULL)";
				//$q = "select * from plans where plans.CodeOfSpecialization=0 and plans.CodeOfSpecial=".$TblCode." and (plans.DateArchive is NULL)";
            }else{
               if (strcmp($TblName,"spz") == 0){
                  $q = "select * from plans where plans.CodeOfSpecialization=".$TblCode." and (plans.FixDate is NULL)";
					//$q = "select * from plans where plans.CodeOfSpecialization=".$TblCode." and (plans.DateArchive is NULL)";
               }
            }
         }
         $result = mysql_query($q, $Connection) 
            or die("Query failed:".mysql_errno().": ".mysql_error()."<BR>");
         if (mysql_num_rows ($result)>0){
            while ($row = mysql_fetch_object($result)){
               $TForm = $TForms[$row->TeachForm];
         
               $StrDate = $row->FixDate;
               $day = substr($StrDate,8,2);
               $month = substr($StrDate,5,2);
               $year = substr($StrDate,0,4);
               $PlCode = $row->CodeOfPlan;
               $PlName = $row->PlnName;
   
               echo "<TR><TD VALIGN=CENTER ALIGN=CENTER><B><font  face=\"courier\" size=\"4\">".$MinCode."</FONT></B>";
               echo "</TD>";
               echo "<TD VALIGN=CENTER ALIGN=LEFT>&nbsp;<font face=\"courier\" size=\"4\"><a href=\"".$SpcHref."code=".$SortKey[0]."&plan=".$PlCode."\">".$MinName."</a></FONT>";
               echo "</TD>";
               echo "<TD VALIGN=CENTER ALIGN=CENTER><font face=\"courier\" size=\"3\">".$Degree."</FONT>";
               echo "</TD>";
               echo "<TD VALIGN=CENTER ALIGN=LEFT>&nbsp;<font face=\"courier\" size=\"4\">".$PlName."</FONT>";
               echo "</TD>";
               echo "<TD VALIGN=CENTER ALIGN=CENTER><font face=\"courier\" size=\"4\">".$TForm."</FONT>";
               echo "</TD>";
               echo "<TD VALIGN=CENTER ALIGN=CENTER><font face=\"courier\" size=\"4\">".$day."-".$month."-".$year."</FONT>";
               echo "</TD>";
               echo "<TD VALIGN=CENTER ALIGN=CENTER><font face=\"courier\" size=\"-1\"><a href=\"".$PlanHref."&plan=".$PlCode."\">Tипо вой</a></font>";
               echo "</TD>";
               echo "<TD VALIGN=CENTER ALIGN=CENTER><font face=\"courier\" size=\"-1\"><a href=\"".$FullPlanHref."&plan=".$PlCode."\">Деталь ный</a></font>";
               echo "</TD>";
               echo "<TD VALIGN=CENTER ALIGN=CENTER><font face=\"courier\" size=\"-1\"><a href=\"".$SocrPlanHref."?plan=".$PlCode."\">Сокр-ый</a></font>";
               echo "</TD>";
               echo "<TD VALIGN=CENTER ALIGN=CENTER><font face=\"courier\" size=\"-1\"><a href=\"".$HoursPlanHref."?plan=".$PlCode."\" >Объем занятий</a></font>";
               echo "</TD></TR>";
            }
         }
      }
   }
}


?>