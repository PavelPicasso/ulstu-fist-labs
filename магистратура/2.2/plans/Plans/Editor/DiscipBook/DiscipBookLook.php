<?php
   session_start();
   if (!($_SESSION["status"] == 1)){
      Header ("Location: ../Unreg.html");
      exit;
   }
?>
<HTML>
<HEAD>
<TITLE>Справочник дисциплин</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<link rel="stylesheet" href="../../../CSS/PlansEditor.css" type="text/css">
</HEAD>
<BODY topmargin="1" leftmargin="1" marginheight="1" marginwidth="1">
<form name=fed method=post action="">
<em class='h1'><center>Справочник дисциплин</center></em><table border='0' width='100%' cellpadding='0' cellspacing='2'><tr><td height='5' bgcolor="#92a2d9"><img src="../img/line.gif" width=15 height=15 hspace=0 vspace=0 border='0' align='left'></td></tr></table>
<br>
<?php
   include("../../cfg.php");
   include("DiscipBookFunc.php");
   $Connection = mysql_connect($data_source , $dbi_user , $dbi_password)
      or die("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
   mysql_select_db("plans") 
      or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");
   //Массив с названиями факультетов
   $LinkArray=array();
   $PrevSymbol = "";
   echo "<H2>";
   if (isset($Start)){ echo "$Start<BR>";}
   $result = mysql_query("select DisName from disciplins order by DisName", $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   while ($row = mysql_fetch_object($result)){
      $NewSymbol = ToUpper(substr ($row->DisName,0,1));
      if ( strcmp($PrevSymbol,$NewSymbol) != 0){ 
         if (!isset($Start)){ 
            $Start = $NewSymbol;
            echo "$Start<BR>"; 
         }
         echo  "&nbsp;<a href=\"../DiscipBook/DiscipBookLook.php?Start=$NewSymbol\" >$NewSymbol</a>&nbsp;\n";
         $Link[] = $NewSymbol;
      }
      $PrevSymbol = $NewSymbol;
   }
   echo "</H2>";
?>
<table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center" WIDTH='80%'>
<tr><td cellpadding="0" cellspacing="0">
<TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
<TR ALIGN='CENTER' VALIGN='MIDDLE'>
<TH WIDTH='5%'>&nbsp;</TH>
<TH><strong>Стандарт</strong></TH>
<TH><strong>Название дисциплины</strong></TH>
<TH><strong>Сокращение</strong></TH>
<TH><strong>Кафедра</strong></TH>
<TH><strong>URL УМК</strong></TH>
</TR>
<?php
   $result = mysql_query("select disciplins.CodeOfDiscipline, disciplins.DisName, disciplins.DisReduction, disciplins.URL_UMK, disciplins.NumbOfStandart, department.Reduction from disciplins, department where disciplins.CodeOfDepart=department.CodeOfDepart order by DisName", $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   $i = 1;
   while ($row = mysql_fetch_object($result)){
      if (strcmp($Start, ToUpper(substr($row->DisName,0,1))) == 0){
        //вывод дисциплины
        $DisciplineCode=$row->CodeOfDiscipline;
        $Discipline=$row->DisName;
        $Reduct=$row->DisReduction;
        $Depart=$row->Reduction;
        $URL=$row->URL_UMK;
        $Standart=$row->NumbOfStandart;
        if (strcmp($Standart,"")==0) {$Standart="&nbsp;";} 
        echo "<TR>\n";
        echo "<TD align='center'>$i</TD>\n";
        echo "<TD align='center'>$Standart</TD>\n";
        echo "<TD align='left'>$Discipline</TD>\n";
        echo "<TD  align='center'>$Reduct</TD>\n";
        echo "<TD  align='center'>$Depart</TD>\n";
        echo "<TD align='center'>$URL</TD>\n";
        echo "</TR>\n";
        $i++;
      }
   }
   mysql_free_result($result);
   mysql_close($Connection);
?>
</TABLE>
</td></tr></table><BR>
<HR>
</center>
</body>
</html>