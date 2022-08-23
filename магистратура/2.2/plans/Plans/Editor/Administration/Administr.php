<?php
   session_start();
   if (!($_SESSION["status"] == 0)){
      Header ("Location: ../Unreg.html");
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
<FORM NAME=ADM METHOD=POST ACTION="">
<em class='h1'><center>Руководство университета</center></em>
<table border='0' width='100%' cellpadding='0' cellspacing='2'>
<tr><td height='5' bgcolor="#92a2d9">
<img src="../img/line.gif" width=15 height=15 hspace=0 vspace=0 border='0' align='left'></td></tr>
</table><br>
<table  class='ramka' border="0" cellpadding="0" cellspacing="0" width="80%" align="center">
<tr><td cellpadding="0" cellspacing="0">
<TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
<?php  
   echo "<TR ALIGN='CENTER' VALIGN='MIDDLE'>\n";
   echo "<TH width= 30% ><strong>Должность</strong></TH>";
   echo "<TH width= 45% ><strong>Ф. И. О.</strong></TH>";
   echo "<TH width = 25%><strong>Для подписи</strong></TH>";
   echo "</TR>";
   include("../../cfg.php");
   $Connection = mysql_connect($data_source , $dbi_user , $dbi_password)
      or die("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
   mysql_select_db("plans") 
      or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");
   $result = mysql_query("select * from administration", $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   if ($row = mysql_fetch_object($result)){
      $RFIO = $row->Rector;
      $RS = $row->RectorSignature;
      $HFIO = $row->HeadOfStudies;
      $HS = $row->HeadSignature;
      $PFIO = $row->ProRectorTW;
      $PS = $row->ProRectorTWSignature;
   }
   echo "<TR ALIGN='LEFT' VALIGN='MIDDLE'>";
   echo "<TH ALIGN='LEFT'>&nbsp;Ректор</TH>";
   echo "<TH ALIGN='CENTER'><INPUT TYPE=TEXT NAME='Rector' VALUE=\"$RFIO\" SIZE='40' MAXLENGTH=60></TH>";
   echo "<TH ALIGN='CENTER'><INPUT TYPE=TEXT NAME='RectorSignature' VALUE=\"$RS\" SIZE='20' MAXLENGTH=30></TH>";
   echo "</TR>";
   echo "<TR ALIGN='LEFT' VALIGN='MIDDLE'>";
   echo "<TH ALIGN='LEFT'>&nbsp;Начальник учебной части</TH>";
   echo "<TH ALIGN='CENTER'><INPUT TYPE=TEXT NAME='HeadOfStudies' VALUE=\"$HFIO\" SIZE='40' MAXLENGTH=60></TH>";
   echo "<TH ALIGN='CENTER'><INPUT TYPE=TEXT NAME='HeadSignature' VALUE=\"$HS\" SIZE='20' MAXLENGTH=30></TH>";
   echo "</TR>";
   echo "<TR ALIGN='LEFT' VALIGN='MIDDLE'>";
   echo "<TH ALIGN='LEFT'>&nbsp;Проректор по учебной работе</TH>";
   echo "<TH ALIGN='CENTER'><INPUT TYPE=TEXT NAME='ProRectorTW' VALUE=\"$PFIO\" SIZE='40' MAXLENGTH=60></TH>";
   echo "<TH ALIGN='CENTER'><INPUT TYPE=TEXT NAME='ProRectorTWSignature' VALUE=\"$PS\" SIZE='20' MAXLENGTH=30></TH>";
   echo "</TR>";
   /*  */
   mysql_free_result($result);
   /*  */
   mysql_close($Connection);
//Ссылка

?>
</TABLE>
</td></tr></table><BR>
<CENTER>
<TABLE BORDER=0 ALIGN=CENTER>
<TR>
<TD><CENTER><INPUT TYPE='SUBMIT' NAME='Edit' VALUE='Внести изменения'
            onClick="ADM.action='DoEditAdm.php'"></INPUT></CENTER></TD>
<TD><CENTER><INPUT TYPE='RESET' NAME='reset' VALUE='Отменить изменения'></INPUT></CENTER></TD>
</TR>
</TABLE>
</FORM>
<HR>
</BODY></HTML>
