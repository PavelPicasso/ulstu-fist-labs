<?php
   session_start();
   if (!($_SESSION["status"] == 1)){
      Header ("Location: ../Unreg.html");
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
			{	if(confirm(" Отмеченный Учебный план будет безвозвратно удален из базы данных. Удалить его?"))
					{return ('DoDelPlan.pl');	}  
			return('ChoiseP.pl');
			}
		// -->
		</script></HEAD><BODY topmargin="1" leftmargin="5" marginheight="1" marginwidth="5">
<form name=fed method=post action="">
<em class='h1'><center>Выбор учебного плана</center></em><table border='0' width='100%' cellpadding='0' cellspacing='2'><tr><td height='5' bgcolor="#92a2d9"><img src="../img/line.gif" width=15 height=15 hspace=0 vspace=0 border='0' align='left'></td></tr></table><h2>Выберите план для редактирования</h2><TABLE border=0 align='center' width=97%>
<TR>
<TD></TD>
<TD align='center' width=45% colspan=2><strong>Специальность</strong></TD>
<TD align='center' width=50%><strong>Название плана</strong></TD>
</TR>
<?php
   $send="/cgi/planFull.pl?plan=";
   include("../../cfg.php");

   $Connection = mysql_connect($data_source , $dbi_user , $dbi_password)
      or die("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
   mysql_select_db("plans") 
      or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");

   //Массив с кодами направлений 
   $result = mysql_query("select MinistryCode, CodeOfDirect, DirName from directions", $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   $DirectArray = array();
   while ($row = mysql_fetch_object($result)){
      $DirectArray[$row->CodeOfDirect] = array( $row->MinistryCode, $row->DirName);
   }

   //Массив с кодами специальностей
   $result = mysql_query("select MinistryCode, CodeOfSpecial, SpcName from specials", $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   $SpecArray = array();
   while ($row = mysql_fetch_object($result)){
      $SpecArray[$row->CodeOfSpecial] = array( $row->MinistryCode, $row->SpcName);
   }

   //Массив с кодами специализаций
   $result = mysql_query("select MinistryCode, CodeOfSpecialization, SpzName from specializations", $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   $SpzArray = array();
   while ($row = mysql_fetch_object($result)){
      $SpzArray[$row->CodeOfSpecialization] = array( $row->MinistryCode, $row->SpzName);
   }

   $flag = 0;
   $resPlan = mysql_query("SELECT * FROM plans  order by CodeOfDirect, CodeOfSpecial, CodeOfSpecialization, PlnName", $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   while ($row = mysql_fetch_object($resPlan)){
      $NamePlan = $row->PlnName;
      $CodePlan = $row->CodeOfPlan;

      $MinCode = "------------";
      $NameSpecial = "~~~~~~~~~~~~"; 
      if (!empty($row->CodeOfSpecialization)){
         $MinCode = $SpzArray[$row->CodeOfSpecialization][0];
         $NameSpecial = $SpzArray[$row->CodeOfSpecialization][1];
      }else{
         if (!empty($row->CodeOfSpecial)){
            $MinCode = $SpecArray[$row->CodeOfSpecial][0];
            $NameSpecial = $SpecArray[$row->CodeOfSpecial][1];
         }else{
            if (!empty($row->CodeOfDirect)){
               $MinCode = $DirectArray[$row->CodeOfDirect][0];
               $NameSpecial = $DirectArray[$row->CodeOfDirect][1];
            }
         }
      }
      echo "<TR>\n";
      if ($flag==0){
         echo "<TD align='center'  valign='top'><INPUT TYPE='RADIO' NAME='plan' VALUE='$CodePlan' CHECKED></INPUT></TD>\n";
         $flag = 1;  
      }else { 
         echo "<TD align='center'  valign='top'><INPUT TYPE='RADIO' NAME='plan' VALUE='$CodePlan' ></INPUT></TD>\n";
      }
      echo "<TD valign='top'><b>$MinCode</b></TD>\n";
      echo "<TD valign='top' width=40%>$NameSpecial</TD>\n";
      echo "<TD  valign='top' width=50%>&nbsp <a href=\"$send".$CodePlan."\" >$NamePlan</a></TD>\n";
      echo "</TR>\n";
   }
   mysql_free_result($resPlan);
   mysql_close($Connection);
?>
</TABLE><BR><center><TABLE BORDER=0 ALIGN=CENTER cellspacing="2">
<TR>
</TD></TR><TR><TD WIDTH=600  colspan="2" align='center'><input type=submit value='Расширенный экспорт в формат RTF' onClick="fed.action='../RTFExport/Export.php?IF=1'">
</TD></TR><TR><TD WIDTH=600  colspan="2" align='center'><input type=submit value='Экспорт в формат RTF' onClick="fed.action='../RTFExport/Export.php?IF=0'">
</TD></TR><TR><TD WIDTH=600  colspan="2" align='center'><input type=submit value='Список дисциплин' onClick="fed.action='../RTFExport/Subscription.php'">
</TD></TR>

</TABLE>
</center>
</form>
<hr />
</body>
</html>