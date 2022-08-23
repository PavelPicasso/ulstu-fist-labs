<?php
   session_start();
   if (!(($_SESSION["status"] == 0)||($_SESSION["status"] == 2))){
      Header ("Location: ../Unreg.html");
      exit;
   }
?>
<HTML>
<HEAD>
<TITLE>Ввод новой специыльности</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<link rel="stylesheet" href="../../../CSS/PlansEditor.css" type="text/css"><script language="JavaScript">
		<!--
			function Validator(theForm) { return; }
		//-->
    </script>
<script language="JavaScript1.1">
		<!--
			function Validator(theForm) 
			{ 
				var checkOK="0123456789";
				var checkStr = theForm.value;
				var allValid = true;
				for (i=0; i<checkStr.length; i++)
				{
					ch = checkStr.charAt(i);
					for(j=0;j<checkOK.length;j++)
						if (ch == checkOK.charAt(j)) break;
					if (j == checkOK.length)
					{
						allValid = false;
						break;
					}
				}
					if (allValid == false)
					{
						alert("В данное поле разрешен ввод только цифр");
						theForm.value=theForm.defaultValue;
						theForm.focus();
						return (false);
					}

				return (true); 
                        }
		// -->
	</script></HEAD>



<BODY topmargin="1" leftmargin="1" marginheight="1" marginwidth="1">
<form method="post" action="DoNewSpec.php" enctype="application/x-www-form-urlencoded" metod="post">
<em class='h1'><center>Ввод новой специальности</center></em><table border='0' width='100%' cellpadding='0' cellspacing='2'><tr><td height='5' bgcolor="#92a2d9"><img src="../img/line.gif" width=15 height=15 hspace=0 vspace=0 border='0' align='left'></td></tr></table><BR><H2>Заполните форму</H2><br><table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center">
<tr><td cellpadding="0" cellspacing="0">
<TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
<TR>	
<TH><strong>Направление</strong></TH>
<TH><strong>Мин. код</strong></TH>
<TH><strong>Название специальности</strong></TH></TR>
<TR>	
<?php
   include("../../cfg.php");
   $Connection = mysql_connect($data_source , $dbi_user , $dbi_password)
      or die("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
   mysql_select_db("plans") 
      or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");

   $result = mysql_query("select MinistryCode, CodeOfDirect from directions order by CodeOfFaculty, CodeOfDepart", $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   $i=0;
   echo "<TD align='center'><SELECT NAME=\"Direction\">\n";
   echo "<OPTION VALUE=0>Без направления\n";
   while ($row = mysql_fetch_object($result)){
      echo "<OPTION VALUE=".$row->CodeOfDirect.">".$row->MinistryCode."\n";
   }
   echo "</SELECT></TD>\n";
?>
<TD align='center'><INPUT TYPE=TEXT NAME='MinKod' SIZE=6 MAXLENGTH=6 onChange="Validator(this)"></INPUT></TD>
<TD align='center'><INPUT TYPE=TEXT NAME='SpecN' MAXLENGTH=150 SIZE=50></INPUT></TD></TR></TABLE></td></tr></table><BR><br><table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center">
<tr><td cellpadding="0" cellspacing="0">
<TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
<TR>
<TH><strong>Факультет</strong></TH>
<TH><strong>Выпускающая кафедра</strong></TH>
<TH><strong>Тип</strong></TH>
</TR>
<?php

   if ($_SESSION["statusCode"] == 0){ $q = "select Reduction, CodeOfFaculty from faculty";}
   else{ $q = "select Reduction, CodeOfFaculty from faculty where CodeOfFaculty=".$_SESSION["statusCode"];}
   $result = mysql_query($q, $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   echo "<TR><TD align='center'><SELECT NAME=\"Faculty\">\n";
   while ($row = mysql_fetch_object($result)){
      echo "<OPTION VALUE=".$row->CodeOfFaculty.">".$row->Reduction."\n";
   }
   echo "</SELECT></TD>\n";
   echo "<TD><CENTER>&nbsp&nbsp&nbsp\n";
   echo "<SELECT NAME='Depart'>\n";

   $result = mysql_query("select Reduction, CodeOfDepart from department order by Reduction", $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   while ($row = mysql_fetch_object($result)){
      echo "<OPTION VALUE=".$row->CodeOfDepart.">".$row->Reduction."\n";
   }          
   echo "</SELECT>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp\n";
   echo "</CENTER></TD>	<TD>\n";
   echo "<SELECT NAME='Type'>\n";
   $result = mysql_query("select DegreeName, CodeOfDegree from degrees order by Sequence, DegreeName", $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   while ($row = mysql_fetch_object($result)){
      echo "<OPTION VALUE=".$row->CodeOfDegree.">".$row->DegreeName."\n";
   }

   mysql_free_result($result);
   mysql_close($Connection);
?>
</td>
</tr>
</table>
</td>
</tr>
</table>
<br>
<TABLE align='center'><TR>
<?php
   if (!isset($shift)){$sh=0;}
   else {$sh = 1;}
   
   echo "<TD><INPUT TYPE='RADIO' NAME='shift' VALUE='0' ";
   if($sh==0){echo "CHECKED ";}
   echo ">Вернутся к справочнику специальностей &nbsp;&nbsp;</TD>\n";
   echo "<TD><INPUT TYPE='RADIO' NAME='shift' VALUE='1'";
   if($sh==1){echo "CHECKED ";}
   echo ">Ввести несколько новых специальностей</TD>\n";
?>
</TR>
</TABLE>
<BR>
<center>
<input type="submit" name="subm" value="Добавить специальность в список специальностей" />
</center>
</form><BR><hr />
</body></html>