<?php
   session_start();
   if (!(($_SESSION["status"] == 0)||($_SESSION["status"] == 2))){
      Header ("Location: ../Unreg.html");
      exit;
   }
?>
<HTML>
<HEAD>
<TITLE>Импорт учебного плана</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<link rel="stylesheet" href="../../../CSS/PlansEditor.css" type="text/css"></HEAD><BODY topmargin="1" leftmargin="5" marginheight="1" marginwidth="5">
<form name=fed method=post action=DoImport.php>
<em class='h1'><center>Импорт учебного плана</center></em><table border='0' width='100%' cellpadding='0' cellspacing='2'><tr><td height='5' bgcolor="#92a2d9"><img src="../img/line.gif" width=15 height=15 hspace=0 vspace=0 border='0' align='left'></td></tr></table>
<H3>Отметьте разделы плана, которые нужно будет импортировать</H3>
<TABLE align='center' border=0>
<?php
 
   include("../../cfg.php");
   $Connection = mysql_connect($data_source , $dbi_user , $dbi_password)
      or die("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
   mysql_select_db("plans") 
      or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");

   $result = mysql_query("select CodeOfCicle, CicName from cicles order by CodeOfCicle", $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");

   $i = 0;
   //Вывод таблицы с разделами планов
   while ($row = mysql_fetch_object($result)){
      if ($i%2 == 0){echo "<TR>\n";}
      echo "<TD>\n";
      echo "<INPUT TYPE='checkbox' NAME='sections[".$row->CodeOfCicle."]'  VALUE=".$row->CodeOfCicle.">\n";
      echo $row->CicName;
      echo "</TD>\n";
      $i ++;
      if ($i%2 == 0){echo "</TR>\n";}
      echo "\n";
   }
   echo "<TD>\n";
   echo "<INPUT TYPE='checkbox' NAME='sections[0]'  VALUE='0'>Без раздела\n";
   echo "</TD>\n";
   $i ++;
   if ($i%2 == 0){echo "</TR>\n";}

   if ($i%2 != 0){echo "<TD></TD></TR>\n";}
   echo "</TABLE>";
?>
<hr />
<br>
<table border="0" cellpadding="0" cellspacing="0" width="90%" align="center">
<tr><td cellpadding="0" cellspacing="0" bgcolor="0040A0">
<TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
<TR>
<TD rowspan=2  align=center VALIGN='TOP'  width='10%'><strong>Откуда импортировать</strong></TD>
<TD colspan=2  align=center ><strong>Список планов</strong></TD>
<TD rowspan=2 align=center VALIGN='TOP'  width='10%'><strong>Куда импортировать</strong></TD>
</TR>
<TR>
	<TD  width=40%><CENTER><FONT SIZE='-1' COLOR='PURPLE'>Специальность</FONT></CENTER>	</TD>
	<TD  width=40%><CENTER><FONT SIZE='-1' COLOR='PURPLE'>Название плана</FONT></CENTER></TD>
</TR>
<?php
   //Вывод таблицы с учебными планами

   //Вывод учебных планов
   $q = array();
   if (($_SESSION["status"] == 2)||(($_SESSION["status"] == 0) && ($_SESSION["statusCode"]==0))){
/*      $q[] = "select CodeOfPlan, MinistryCode, DirName, PlnName, CodeOfDepart from directions, plans where (plans.CodeOfDirect=directions.CodeOfDirect and (plans.CodeOfSpecialization is NULL) and (plans.CodeOfSpecial is NULL)) order by MinistryCode, PlnName";
      $q[] = "select CodeOfPlan, MinistryCode, SpcName, PlnName, CodeOfDepart from specials, plans where (plans.CodeOfSpecial=specials.CodeOfSpecial and plans.CodeOfSpecialization is NULL) order by MinistryCode, PlnName";
      $q[] = "select CodeOfPlan, MinistryCode, SpzName, PlnName, CodeOfDepart from specializations, plans where plans.CodeOfSpecialization=specializations.CodeOfSpecialization order by MinistryCode, PlnName";*/
      $q[] = "select CodeOfPlan, MinistryCode, SpcName, PlnName, CodeOfDepart from specials, plans where (plans.CodeOfSpecial=specials.CodeOfSpecial and plans.DateArchive is NULL) order by MinistryCode, PlnName";
   }
   else {
/*      $q[] = "select CodeOfPlan, MinistryCode, DirName, PlnName, CodeOfDepart from directions, plans where (plans.CodeOfDirect=directions.CodeOfDirect and (plans.CodeOfSpecialization is NULL) and (plans.CodeOfSpecial is NULL)) and directions.CodeOfFaculty=".$_SESSION["statusCode"]." order by MinistryCode, PlnName";
      $q[] = "select CodeOfPlan, MinistryCode, SpcName, PlnName, CodeOfDepart from specials, plans where (plans.CodeOfSpecial=specials.CodeOfSpecial and (plans.CodeOfSpecialization is NULL)) and specials.CodeOfFaculty=".$_SESSION["statusCode"]." order by MinistryCode, PlnName";
      $q[] = "select CodeOfPlan, MinistryCode, SpzName, PlnName, CodeOfDepart from specializations, plans where plans.CodeOfSpecialization=specializations.CodeOfSpecialization and specializations.CodeOfFaculty=".$_SESSION["statusCode"]." order by MinistryCode, PlnName";*/
      $q[] = "select CodeOfPlan, MinistryCode, SpcName, PlnName, CodeOfDepart from specials, plans where (plans.CodeOfSpecial=specials.CodeOfSpecial and (plans.DateArchive is NULL)) and specials.CodeOfFaculty=".$_SESSION["statusCode"]." order by MinistryCode, PlnName";
   }
   while ($qw = each ($q)){
      $result = mysql_query($qw[1], $Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
      $i=0;
      while ($row = mysql_fetch_row($result)){
         $CodePlan = $row[0];
         $MinCode = $row[1];
         $SpcName = $row[2];
         $NamePlan = $row[3];
         $CodeDepart = $row[4];
         echo "<TR>\n";
         echo "<TD><CENTER>\n";
         echo "<INPUT TYPE='RADIO' NAME='import' VALUE='",$CodePlan,"' ></INPUT>\n";
         echo "</CENTER></TD>\n";
         echo "<TD  width=40%>\n";
         echo "<table  border='0' width='100%'>\n";
         echo "<tr>\n";
         echo "<td valign='top' width='17%'>&nbsp;".$MinCode."<a></td>\n";
         echo "<td valign='top' width='83%'>&nbsp;".$SpcName."</td>\n";
         echo "</tr>\n";
         echo "</table>\n";
         echo "</TD>\n";
         echo "<TD width=40%> &nbsp ".$NamePlan."</TD>\n";
         echo "<TD><CENTER>\n";
         if (($_SESSION["status"] != 2)||($_SESSION["statusCode"] == $CodeDepart)){
            echo "<INPUT TYPE='CHECKBOX' NAME='export[$CodePlan]' VALUE=$CodePlan></INPUT>\n";
         }
         echo "</CENTER></TD>\n";
         echo "</TR>\n";
      }
   }

   mysql_free_result($result);
   mysql_close($Connection);
?>
</TABLE>
</td>
</tr>
</table>
<br>
<center>
<div align='center'>
<input type=submit value='Импортировать план'>
</div></center></form><hr />
</body></html>