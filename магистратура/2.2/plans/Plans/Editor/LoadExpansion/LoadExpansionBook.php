<?php
   session_start();
   if (!(($_SESSION["status"] == 0)||($_SESSION["status"] == 2))){
      Header ("Location: ../Unreg.html");
   }
   include("../Display/StartPage.php");
   include("../Display/DisplayFunc.php");

   include("../PlanCalculatFunc.php");
   CreateConnection();

   DisplayPageTitle("../down4.html","Расширение учебной нагрузки");
?>
<table  class='ramka' border="0" cellpadding="0" cellspacing="0"  align="center">
<tr><td cellpadding="0" cellspacing="0">
<TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
<TR ALIGN='CENTER' VALIGN='MIDDLE'>
<TH>&nbsp;</TD>
<TH><strong>Кафедра</strong></TH>
<TH><strong>Вид учебной работы</strong></TH>
<TH><strong>Семестр</strong></TH>
<TH><strong>Количество часов</strong></TH>
</TR>
<?php
   //Массив с сокр. названиями кафедрами
   $RedDepartArray=array();
   $CodeDepartArray=array();
   $result = mysql_query("select Reduction, CodeOfDepart from department order by Reduction", $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   while ($row = mysql_fetch_object($result)){
      $RedDepartArray[] = $row->Reduction;
      $CodeDepartArray[] = $row->CodeOfDepart;
   }
   $result = mysql_query("select * from expansion order by CodeOfDepart", $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   while ($row = mysql_fetch_object($result)) {
      $ExpCode = $row->CodeOfExpansion;
      $DepCode = $row->CodeOfDepart;
      $ExpName = $row->ExpansionName;
      $Semestr = $row->Semester;
      $Hours   = $row->Hours;
      echo "<TR ALIGN='LEFT' VALIGN='MIDDLE'>";
      echo "<TD align='center'><INPUT TYPE='CHECKBOX' NAME='flag[$ExpCode]' VALUE=\"$ExpCode\"></TD>\n";
      //Выбор кафедры
      echo "<TD><SELECT NAME=\"Depart$ExpCode\" onChange=\"FillArrChenge(this)\">\n";
      reset($RedDepartArray);
      reset($CodeDepartArray);
      while (($Dep = each($RedDepartArray)) && ($CodeDep = each($CodeDepartArray))){
         if ($CodeDep[1] == $DepCode){ echo "<OPTION SELECTED VALUE=".$CodeDep[1].">".$Dep[1]."\n";}
         else { echo "<OPTION VALUE=".$CodeDep[1].">".$Dep[1];}
      }
      echo "</SELECT></TD>\n";
      echo "<TD align='center'><INPUT TYPE=TEXT NAME=\"ExpName$ExpCode\"   SIZE='20' MAXLENGTH=220 VALUE='".trim($ExpName)."'";
      echo "          onChange=\"FillArrChenge(this)\"></INPUT></TD>\n";
      echo "<TD align='center'><SELECT NAME=\"Semestr$ExpCode\" onChange=\"FillArrChenge(this)\">\n";
      for ($i=1; $i<=2; $i++){
         echo   "<OPTION ";
         if ($i==$Semestr){ echo  "SELECTED";}
         echo  " VALUE=$i>$i\n";
      }
      echo "</SELECT></TD>\n";
      echo "<TD align='center'><INPUT TYPE=TEXT NAME=\"Hours$ExpCode\"   SIZE='7' MAXLENGTH=7 VALUE='".trim($Hours)."'";
      echo "          onChange=\"Validator(this)\"></INPUT></TD>\n";
      echo "</TR>";
   }

   /*  */
   mysql_free_result($result);
   /*  */
   mysql_close($Connection);
?>
</TABLE>
</td></tr></table><BR>
<input type='hidden' name='NumOfChangeRec' value=''>
<TABLE BORDER=0 ALIGN=CENTER>
<TR>
<TD><CENTER><INPUT TYPE='SUBMIT' NAME='Edit' VALUE='Внести изменения'
		onClick="ExpFrm.action='DoEditExpansion.php'"></INPUT></CENTER>
</TD>

<TD><CENTER><INPUT TYPE='RESET' NAME='reset' VALUE='Отменить изменения'
		onClick="RefreshRecArr(this)"></INPUT></CENTER>
</TD>
</TR>

<TR>
<TD><CENTER><input type=submit 	      VALUE='Добавить в справочник новую запись' 
		onClick="ExpFrm.action='AddExpansion.php'"></CENTER>
</TD>
<TD><CENTER><INPUT TYPE='SUBMIT' NAME='Delete' VALUE='Удалить помеченные записи' 
		onClick="ExpFrm.action=Deleting(this)"></INPUT></CENTER>
</TD>
</TR>
</TABLE>
<CENTER>
<HR>
</FORM>
</BODY>
</HTML>
