<?php
   session_start();
   if (!($_SESSION["status"] == 0)){
      Header ("Location: ../Unreg.html");
      exit;
   }
?>
<HTML>
<HEAD>
<?php
if (isset ($shift) && (strcmp($shift,"editSpc")==0)){
  echo "<TITLE>Редактирование направления стандарта</TITLE>";
}else{
  echo "<TITLE>Создание нового стаедарта</TITLE>";
}
?>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<link rel="stylesheet" href="http://plans.nilaos.ustu/CSS/PlansEditor.css" type="text/css">
</HEAD><BODY topmargin="1" leftmargin="5" marginheight="1" marginwidth="5">
<form name=fed method=post action="ChoiseSpec.php">
<script  language="JavaScript">
<!--
	top.LeftDown.location='../downNewP.html';	
//-->
</script>
<em class='h1'>
<?php
   include("../../cfg.php");
   $Connection = mysql_connect($data_source , $dbi_user , $dbi_password)
      or die("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
   mysql_select_db("plans") 
      or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");
   if (isset ($shift) && (strcmp($shift,"editSpc")==0)){

      $standard = $_POST['CodeOfStandard'];
      echo "<center>Редактирование направления стандарта</center></em>";
      echo "<table border='0' width='100%' cellpadding='0' cellspacing='2'><tr><td height='5' bgcolor=\"#92a2d9\"><img src=\"../img/line.gif\" width=15 height=15 hspace=0 vspace=0 border='0' align='left'></td></tr></table>";

      $result = mysql_query("select CodeOfDirect, CodeOfSpecial, CodeOfSpecialization from specialslimit where CodeOfStandard=".$standard, $Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
      if ($row=mysql_fetch_object($result)){
         $CodeOfDir = $row->CodeOfDirect;
         $CodeOfSpc = $row->CodeOfSpecial;
         $CodeOfSpz = $row->CodeOfSpecialization;
         if (isset($CodeOfSpz)){
            $result = mysql_query("select MinistryCode, SpzName from specializations where specializations.CodeOfSpecialization=".$CodeOfSpz, $Connection)
               or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
            if ($row = mysql_fetch_object($result)){
               echo "<h2>".$row->MinistryCode."&nbsp;".$row->SpzName."&nbsp;</h2>";
            }
         }else{
            if (isset($CodeOfSpc)){
               $result = mysql_query("select MinistryCode, SpcName from specials where specials.CodeOfSpecial=".$CodeOfSpc, $Connection)
                  or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
               if ($row = mysql_fetch_object($result)){
                  echo "<h2>".$row->MinistryCode."&nbsp;".$row->SpcName."&nbsp;</h2>";
               }
            }else{
               if (isset($CodeOfDir)){
                  $result = mysql_query("select MinistryCode, DirName from directions where directions.CodeOfDirect=".$CodeOfDir, $Connection)
                     or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
                  if($row = mysql_fetch_object($result)){
                     echo "<h2>".$row->MinistryCode."&nbsp;".$row->DirName."&nbsp;</h2>";
                  }
               }
            }
         }
      }
   }else{
      echo "<center>Создание нового стандарта</center></em>";
      echo "<table border='0' width='100%' cellpadding='0' cellspacing='2'><tr><td height='5' bgcolor=\"#92a2d9\"><img src=\"../img/line.gif\" width=15 height=15 hspace=0 vspace=0 border='0' align='left'></td></tr></table>";
   }
?>

<h2>Выберите направление&nbsp;&nbsp;&nbsp;</h2>
<CENTER>
<table  border=0  align='center'>
<TR>
<?php
   $q= "select CodeOfDirect, MinistryCode, DirName from directions";
   if($_SESSION["status"] == 2){//Если пользователь кафедра то отбираем направления по кафедре
      $q .= " where CodeOfDepart = ".$_SESSION["statusCode"];
   }
   else{ //если пользователь уч. часть и выбран факультет, то отбираем направления по факультету
      if($_SESSION["statusCode"]!=0){$q .= " where CodeOfFaculty = ".$_SESSION["statusCode"];}
   }
   echo "<TD width=4%  valign='top' align='center'><INPUT TYPE='RADIO' NAME='Dir' VALUE='0' CHECKED></INPUT></TD>";
   echo "<TD width=5%   valign='top'>--------</TD>";
   echo "<TD width=40% valign='top'>Без направления</TD>";
   echo "<td width=2%></td>";
   $i = 1;

   //Получение данных о специальности
   $result = mysql_query($q, $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");

   while($row = mysql_fetch_object($result)) {
      if (($i % 2) == 0){ echo "<TR>\n";}
      $NameDir = $row->DirName;
      $CodeDir = $row->CodeOfDirect;
      $MinistryCode = $row->MinistryCode;
      echo "<TD width=4%  valign='top' align='center'><INPUT TYPE='RADIO' NAME='Dir' VALUE='".$CodeDir."'></INPUT></TD>";
      echo "<TD width=5%   valign='top'>".$MinistryCode."</TD>";
      echo "<TD width=40% valign='top'>".$NameDir."</TD>";
      
      if (($i % 2) == 1){ echo "</TR>\n";}
      else {echo "<TD width=2%></TD>";}
      $i ++;
   }

   if (($i % 2) == 1){ echo "</TR>\n";}
   if (isset ($shift) && (strcmp($shift,"editSpc")==0)&& isset($standard)){
      echo "<input type='hidden' name='shift' value='editSpc'>\n";
      echo "<input type='hidden' name='CodeOfStandard' value='$standard'>\n";
   }
   mysql_free_result($result);
   mysql_close($Connection);
?>
</TABLE>
</CENTER>
<BR>
<center><input type="submit" name="subm" value="Дальше" /></center></form><hr /></body></html>