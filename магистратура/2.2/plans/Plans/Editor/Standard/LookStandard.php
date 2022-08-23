<?php
   if (!($_GET['stCode'])) {
     Header ("Location: Standard.php");
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
<?php
   include("../../cfg.php");
   $Connection = mysql_connect($data_source , $dbi_user , $dbi_password)
      or die("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
   mysql_select_db("plans") 
      or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");
   $StCode = $_GET['stCode'];
   $result = mysql_query("select * from specialslimit where CodeOfStandard=$StCode", $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   if ($row = mysql_fetch_object($result)){
      $DirCode = $row->CodeOfDirect;
      $SpcCode = $row->CodeOfSpecial;
      $SpzCode = $row->CodeOfSpecialization;
      $TotalHours  = $row->TotalHours ; 
      $StDate      = $row->StDate     ;
      $TotalVolume = $row->TotalVolume;
      $TheorlTeach = $row->TheorlTeach;
      $Exams       = $row->Exams      ;
      $Practice    = $row->Practice   ;
      $Attestation = $row->Attestation;
      $Vacation    = $row->Vacation   ;
      $LimitFile   = $row->LimitFile  ;
      $day = substr($StDate,8,2);
      $month = substr($StDate,5,2);
      $year = substr($StDate,0,4);
   }
   echo "<em class='h1'><center>Просмотр данных стандарта специальности<BR>";
   if (isset($SpzCode)){
      $result = mysql_query("select * from specializations where CodeOfSpecialization=".$SpzCode, $Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
      if ($row = mysql_fetch_object($result)){ echo "специализации ".$row->MinistryCode." ".$row->SpzName." </center></em>";}
   }else{
      if (isset($SpcCode)){
         $result = mysql_query("select * from specials where CodeOfSpecial=".$SpcCode, $Connection)
            or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
         if ($row = mysql_fetch_object($result)){ echo "специальности ".$row->MinistryCode." ".$row->SpcName." </center></em>";}
      }else{
         if (isset($DirCode)){
            $result = mysql_query("select * from directions where CodeOfDirect=".$DirCode, $Connection)
               or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
            if ($row = mysql_fetch_object($result)){ echo "направления ".$row->MinistryCode." ".$row->DirName." </center></em>";}
         }
      }
   }
?>
<table border='0' width='100%' cellpadding='0' cellspacing='2'>
<tr><td height='5' bgcolor="#92a2d9">
<img src="../img/line.gif" width=15 height=15 hspace=0 vspace=0 border='0' align='left'>
</td></tr>
</table>
<FORM METHOD='post' NAME='UpdLimits' ACTION=''>
<br><table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center" WIDTH='100%'>
<tr><td cellpadding="0" cellspacing="0">
<TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
<TR>
<TH><strong>Всего часов обучения</strong></TH>
<TH><strong>Общий объем занятий (недели)</strong></TH>
<TH><strong>Теоретическое обучение (недели)</strong></TH>
<TH><strong>Экзаменационная сессия (недели)</strong></TH>
<TH><strong>Практика (недели)</strong></TH>
<TH><strong>Итоговая аттестация (недели)</strong></TH>
<TH><strong>Каникулы (недели)</strong></TH>
</TR>
<TR>
<?php
   echo " <TD align='center'>$TotalHours </TD>\n";
   echo " <TD align='center'>$TotalVolume</TD>\n";
   echo " <TD align='center'>$TheorlTeach</TD>\n";
   echo " <TD align='center'>$Exams      </TD>\n";
   echo " <TD align='center'>$Practice   </TD>\n";
   echo " <TD align='center'>$Attestation</TD>\n";
   echo " <TD align='center'>$Vacation   </TD>\n";
   echo " </TR></TABLE></TD></TR></TABLE><BR>\n";
   echo " <br><table  class='ramka' border=\"0\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\" WIDTH='40%'>\n";
   echo " <tr><td cellpadding=\"0\" cellspacing=\"0\">\n";
   echo " <TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>\n";
   echo " <TR>\n";
   echo " <TH><strong>Дата утверждения</strong></TH>\n";
   echo " <TH><strong>Файл стандарта</strong></TH>\n";
   echo " <TH><strong>Файлы типовых планов</strong></TH>\n";
   echo " </TR>\n";
   echo " <TR>\n";
   echo " <TD align='center'> $day-$month-$year</TD>\n";
   echo " <TD align='center'>$LimitFile</TD>\n";
   echo " <TD align='center'>";
   $resTP = mysql_query("select * from standardplans where CodeOfStandard=".$StCode, $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   while ($rowTP = mysql_fetch_object($resTP)){
      echo "$rowTP->PlanFile<BR>\n";
   }
   echo " </TD>\n";
   echo " </TR></TABLE></TD></TR></TABLE><BR>\n";
   echo " <CENTER><TABLE  align='center'><TR>\n";
   echo " <TD>&nbsp;&nbsp;&nbsp;&nbsp;\n";
   echo " <INPUT TYPE='SUBMIT' NAME='Esk' VALUE='Вернуться к списку стандартов' ONCLICK = \"UpdLimits.action='Standard.php'\"></INPUT>\n";
   echo " &nbsp;&nbsp;&nbsp;&nbsp;</TD>\n";
   mysql_free_result($result);
   mysql_free_result($resTP);
   mysql_close($Connection);
?>
</TR>
</TABLE>

</CENTER>
</FORM>
<HR>
</BODY>
</HTML>
