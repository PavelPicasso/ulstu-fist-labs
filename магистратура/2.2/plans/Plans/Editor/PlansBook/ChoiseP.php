<?php
   session_start();
   if (!(($_SESSION["status"] == 0)||($_SESSION["status"] == 2))){
      Header ("Location: ../Unreg.html");
      exit;
   }

?>
<HTML>
<HEAD>
<TITLE>Выбор учебного плана</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<link rel="stylesheet" href="../../../CSS/PlansEditor.css" type="text/css">
<SCRIPT language="JavaScript">
            <!--
            function Deleting(theForm) { return; }
            function Deleting(theForm)
            {   if(confirm(" Отмеченный Учебный план будет безвозвратно удален из базы данных. Удалить его?"))
                    {return ('../PlansBook/DoDelPlan.php'); }  
            return('../PlansBook/ChoiseP.php');
            }
        // -->
        </script></HEAD><BODY topmargin="1" leftmargin="5" marginheight="1" marginwidth="5">
<form name=fed method=post action="">
<em class='h1'><center>Выбор учебного плана</center></em><table border='0' width='100%' cellpadding='0' cellspacing='2'><tr><td height='5' bgcolor="#92a2d9"><img src="../img/line.gif" width=15 height=15 hspace=0 vspace=0 border='0' align='left'></td></tr></table><h2>Выберите план для редактирования</h2><TABLE border=0 align='center' cellspacing="5" celpadding="4" width="90%">
<TR>
<TD>&nbsp;</TD>
<TD width="8%">&nbsp;</TD>
<TD width="50%"><strong>Направление/Специальность/Специализация</strong><BR><BR></TD>
<TD>&nbsp;</TD>
<TD width="40%"><strong>Название плана</strong><BR><BR></TD>
<TD>&nbsp;</TD>
</TR>
<?php
   include("../PlanCalculatFunc.php");
   CreateConnection();

   if (!empty($_GET["arch"])) {	
   	$arch = $_GET['arch'];
        $name_trans = "Архив";
   	$DateArch = date('Y/m/d');
   	$q = "UPDATE plans SET Date='".$DateArch."' WHERE CodeOfPlan=".$arch;	
        $date_trans = date("Y-m-d h:i:s");
   	$time_b = getmicrotime();

   	mysql_query($q, $Connection)
     		or die("Unable to execute query:".mysql_errno().": ".mysql_error());
        $time_e = getmicrotime();
	$time_all = $time_e-$time_b;
        $id_sess = session_id();
	mysql_query("Insert into logs (name_trans, id_sess, date_trans, time_trans) values ('$name_trans', '$id_sess', '$date_trans', '$time_all')")
               or die("Unable to execute query:".mysql_errno().": ".mysql_error());

   }
	
   $time_all = 0;
   $q =  "";

   //Вывод учебных планов направлений
   if ($_SESSION["status"] == 2){$q = "SELECT * FROM directions where directions.CodeOfDepart=".$_SESSION["statusCode"]." order by MinistryCode"; }
   else { 
      if ((!isset($_SESSION["statusCode"])) || ($_SESSION["statusCode"] == 0) ){
         $q = "SELECT * FROM directions  order by MinistryCode";
      }
      else{
         $q = "SELECT * FROM directions where directions.CodeOfFaculty=".$_SESSION["statusCode"]." order by MinistryCode";
      }
   }
   $name_trans = "Фак_планы"; 
   $date_trans = date("Y-m-d h:i:s");
   $time_b = getmicrotime();

   //Получение данных о специальности
   $result = mysql_query($q, $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   $flag = 0;
   while ($row = mysql_fetch_object($result)){
      $MinCode = $row->MinistryCode;
      $NameDir = $row->DirName;
      $CodeDir = $row->CodeOfDirect;
      $resPlan = mysql_query("SELECT * FROM plans where (CodeOfDirect=".$CodeDir." and (CodeOfSpecial = '0') and (CodeOfSpecialization = '0') and (Date is NULL or Date = '0000-00-00')) order by PlnName", $Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
      while ($rowPl = mysql_fetch_object($resPlan)){
         $NamePlan = $rowPl->PlnName;
         $CodePlan = $rowPl->CodeOfPlan;
         echo "<TR>\n";
         if ($flag==0){
            echo "<TD align='center'  valign='top'><INPUT TYPE='RADIO' NAME='plan' VALUE='$CodePlan' CHECKED></INPUT></TD>\n";
            $flag = 1;
         }else { echo "<TD align='center'  valign='top'><INPUT TYPE='RADIO' NAME='plan' VALUE='$CodePlan' ></INPUT></TD>\n";}
         echo "<TD valign='top'><b>$MinCode</b></TD>\n";
         echo "<TD valign='top' width=40%>$NameDir</TD>\n";
         echo "<TD>&nbsp;</TD>\n";
         echo "<TD  valign='top'>$NamePlan</TD>\n";
	 echo "<TD><a href='ChoiseP.php?arch=".$CodePlan."' onclick='return confirm(\"Вы хотите убрать план в архив?\");'>убрать&nbsp;в&nbsp;архив</a></TD>\n";
         echo "</TR>\n";
      }
      mysql_free_result($resPlan);
   }

   $time_e = getmicrotime();
   $time_all += $time_e-$time_b;

   //Вывод учебных планов специальностей
   if ($_SESSION["status"] == 2){$q = "SELECT * FROM specials where specials.CodeOfDepart=".$_SESSION["statusCode"]." order by MinistryCode"; }
   else { 
      if ((!isset($_SESSION["statusCode"])) || ($_SESSION["statusCode"] == 0) ){
         $q = "SELECT * FROM specials  order by MinistryCode";
      }
      else{
         $q = "SELECT * FROM specials where specials.CodeOfFaculty=".$_SESSION["statusCode"]." order by MinistryCode";
      }
   }
   $time_b = getmicrotime();
   //Получение данных о специальности
   $result = mysql_query($q, $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   while ($row = mysql_fetch_object($result)){
      $MinCode = $row->MinistryCode;
      $NameSpecial = $row->SpcName;
      $CodeSpec = $row->CodeOfSpecial;
      $resPlan = mysql_query("SELECT * FROM plans where (CodeOfSpecial=".$CodeSpec." and (CodeOfSpecialization = '0') and (Date is NULL or Date = '0000-00-00')) order by PlnName", $Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
      while ($rowPl = mysql_fetch_object($resPlan)){
         $NamePlan = $rowPl->PlnName;
         $CodePlan = $rowPl->CodeOfPlan;
         echo "<TR>\n";
         if ($flag==0){
            echo "<TD align='center'  valign='top'><INPUT TYPE='RADIO' NAME='plan' VALUE='$CodePlan' CHECKED></INPUT></TD>\n";
            $flag = 1;
         }else { echo "<TD align='center'  valign='top'><INPUT TYPE='RADIO' NAME='plan' VALUE='$CodePlan' ></INPUT></TD>\n";}
         echo "<TD valign='top'><b>$MinCode</b></TD>\n";
         echo "<TD valign='top'>$NameSpecial</TD>\n";
         echo "<TD>&nbsp;</TD>\n";
         echo "<TD  valign='top'>$NamePlan</TD>\n";
	 echo "<TD><a href='../PlansBook/ChoiseP.php?arch=".$CodePlan."' onclick='return confirm(\"Вы хотите убрать план в архив?\");'>убрать&nbsp;в&nbsp;архив</a></TD>\n";
         echo "</TR>\n";
      }
      mysql_free_result($resPlan);
   }
   $time_e = getmicrotime();
   $time_all += $time_e-$time_b;

   //Вывод учебных планов специализаций
   if ($_SESSION["status"] == 2){$q = "SELECT * FROM specializations where specializations.CodeOfDepart=".$_SESSION["statusCode"]." order by MinistryCode"; }
   else { 
      if ((!isset($_SESSION["statusCode"])) || ($_SESSION["statusCode"] == 0) ){
         $q = "SELECT * FROM specializations  order by MinistryCode";
      }
      else{
         $q = "SELECT * FROM specializations where specializations.CodeOfFaculty=".$_SESSION["statusCode"]." order by MinistryCode";
      }
   }
   $time_b = getmicrotime();
   //Получение данных о специальности
   $result = mysql_query($q, $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   while ($row = mysql_fetch_object($result)){
      $MinCode = $row->MinistryCode;
      $NameSpz = $row->SpzName;
      $CodeSpz = $row->CodeOfSpecialization;
      $resPlan = mysql_query("SELECT * FROM plans where CodeOfSpecialization=".$CodeSpz." and (Date IS NULL OR Date = '0000-00-00') order by PlnName", $Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
      while ($rowPl = mysql_fetch_object($resPlan)){
         $NamePlan = $rowPl->PlnName;
         $CodePlan = $rowPl->CodeOfPlan;
         echo "<TR>\n";
         if ($flag==0){
            echo "<TD align='center'  valign='top'><INPUT TYPE='RADIO' NAME='plan' VALUE='$CodePlan' CHECKED></INPUT></TD>\n";
            $flag = 1;
         }else { echo "<TD align='center'  valign='top'><INPUT TYPE='RADIO' NAME='plan' VALUE='$CodePlan' ></INPUT></TD>\n";}
         echo "<TD valign='top'><b>$MinCode</b></TD>\n";
         echo "<TD valign='top'>$NameSpz</TD>\n";
         echo "<TD>&nbsp;</TD>\n";
         echo "<TD  valign='top'>$NamePlan</TD>\n";
	 echo "<TD><a href='../PlansBook/ChoiseP.php?arch=".$CodePlan."' onclick='return confirm(\"Вы хотите убрать план в архив?\");'>убрать&nbsp;в&nbsp;архив</a></TD>\n";
         echo "</TR>\n";
      }
      mysql_free_result($resPlan);
   }
   $time_e = getmicrotime();
   $time_all += $time_e-$time_b;
   $id_sess = session_id();
   mysql_query("Insert into logs (name_trans, id_sess, date_trans, time_trans) values ('$name_trans', '$id_sess', '$date_trans', '$time_all')")
               or die("Unable to execute query:".mysql_errno().": ".mysql_error());

   mysql_free_result($result);
   mysql_close($Connection);
?>
</TABLE><BR><center><TABLE BORDER=0 ALIGN=CENTER cellspacing="2">
<TR>
<TD WIDTH=300 align='center'><input type=submit width="300" value='Редактировать содержание УП' onClick=" fed.action='PlanEd.php' ">
</TD><TD WIDTH=300 align='center'><input type=submit width="300" value='Редактировать заголовок УП' onClick=" fed.action='PlanHEd.php' ">
</TD></TR><TR><TD WIDTH=600  colspan="2" align='center'><input type=submit value='Редактировать график учебного процесса' onClick="fed.action='schedules.php'">
</TD></TR><TR><TD WIDTH=600  colspan="2" align='center'><input type=submit value='Расширенный экспорт в формат RTF' onClick="fed.action='../RTFExport/Export.php?IF=1'">
</TD></TR><TR><TD WIDTH=600  colspan="2" align='center'><input type=submit value='Экспорт в формат RTF' onClick="fed.action='../RTFExport/Export.php?IF=0'">
</TD></TR><TR><TD WIDTH=600  colspan="2" align='center'><input type=submit value='Список дисциплин' onClick="fed.action='../RTFExport/Subscription.php'">
</TD></TR><TR><TD WIDTH=600  colspan="2" align='center'><input type=submit value='Удалить учебный план' onClick="fed.action=Deleting(this)">
</TD></TR><TR><TD WIDTH=600  colspan="2" align='center'><input type=submit value='Проверить план на соответствие стандартам' onClick="fed.action='CheckStandard.php'">
</TD></TR>

</TABLE>
</center>
</form>
<hr />
</body>
</html>