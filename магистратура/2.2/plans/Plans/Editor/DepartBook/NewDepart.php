<?php
   session_start();
   if (!($_SESSION["status"] == 0)){
      Header ("Location: ../Unreg.html");
      exit;
   }
?>
<HTML>
<HEAD>
<TITLE>Ввод новой кафедры</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<link rel="stylesheet" href="../../../CSS/PlansEditor.css" type="text/css">
</HEAD>

<BODY topmargin="1" leftmargin="5" marginheight="1" marginwidth="5">
<FORM METHOD='post' NAME='depform' ACTION='DoNewDepart.php'>
<em class='h1'><center>Ввод новой кафедры</center></em><table border='0' width='100%' cellpadding='0' cellspacing='2'><tr><td height='5' bgcolor="#92a2d9"><img src="../img/line.gif" width=15 height=15 hspace=0 vspace=0 border='0' align='left'></td></tr></table><H2>Заполните форму</H2><br><table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center">
<tr><td cellpadding="0" cellspacing="0">
<TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
<TR>
<TH><strong>Название кафедры</strong></TH>
<TH><strong>Сокращение</strong></TH>
<TH><strong>Факультет</strong></TH>
</TR>
<TR>
<TD align='center'><INPUT TYPE=TEXT NAME='DepartN' SIZE=50 MAXLENGTH=50></INPUT></TD>
<TD align='center'><INPUT TYPE=TEXT NAME='Reduction' SIZE=7 MAXLENGTH=7></INPUT></TD>
<?php
   include("../../cfg.php");
   $Connection = mysql_connect($data_source , $dbi_user , $dbi_password)
      or die("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
   mysql_select_db("plans") 
      or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");

   echo "<TD align='center'><SELECT NAME=\"Faculty\">\n";
   if ($_SESSION["statusCode"]==0){ $q="select Reduction, CodeOfFaculty from faculty";}
   else {$q="select Reduction, CodeOfFaculty from faculty where CodeOfFaculty=".$_SESSION["statusCode"];}

   $result = mysql_query($q, $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   $i=0;
   while ($row = mysql_fetch_object($result)){
      echo "<OPTION value=".$row->CodeOfFaculty.">".$row->Reduction."\n";
   }
   echo "</SELECT>\n";
   mysql_close($Connection);
?>
</TR>
</TABLE>
</TD></TR></TABLE>
<BR><br><table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center">
<tr><td cellpadding="0" cellspacing="0">
<TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
<TR>
<TH><strong>Ф.И.О. заведующего кафедрой</strong></TH>
<TH><strong>Для подписи</strong></TH>
<TH><strong>URL кафедры</strong></TH>
</TR>
<TR>
<TD align='center'><INPUT TYPE=TEXT NAME='FIO' SIZE=60 MAXLENGTH=60></INPUT></TD>
<TD align='center'><INPUT TYPE=TEXT NAME='Signature' SIZE=25 MAXLENGTH=60></INPUT></TD>
<TD align='center'><INPUT TYPE=TEXT NAME='URL' SIZE=25 MAXLENGTH=100></INPUT></TD>
</TR>
</TABLE>
</td></tr></table>
<BR><CENTER>
<TABLE><TR>
<?php
   if (!empty($back) && $REQUEST_METHOD=='GET' && $_GET['back']){
     echo "<INPUT TYPE='HIDDEN' NAME='back' value=\"$back\"></INPUT>\n";
   }
   else{
      echo "<TD><INPUT TYPE='RADIO' NAME='shift' VALUE='0' ";
      if(empty($sh) || $sh==0){echo "CHECKED ";}
      echo ">Вернутся к справочнику кафедр &nbsp;&nbsp;</TD>\n";
      echo "<TD><INPUT TYPE='RADIO' NAME='shift' VALUE='1'";
      if(!empty($sh) && $sh==1){echo "CHECKED ";}
      echo ">Ввести несколько новых кафедр</TD>\n";
   }
?>
</TR></TABLE>
<BR>
<CENTER>
<INPUT TYPE='SUBMIT' NAME='OK' VALUE='Добавить кафедру в список'></INPUT>
</CENTER>
</FORM>
<HR>
</BODY>
</HTML>
