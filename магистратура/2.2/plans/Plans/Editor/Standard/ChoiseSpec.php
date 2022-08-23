<?php
   session_start();
   if (!(($_SESSION["status"] == 0)||($_SESSION["status"] == 2))){
      Header ("Location: ../Unreg.html");
      exit;
   }
?>
<HTML>
<HEAD>
<?php
if (isset ($shift) && (strcmp($shift,"editSpc")==0)){
  echo "<TITLE>Редактирование специальности стандарта</TITLE>";
}else{
  echo "<TITLE>Создание нового стандарта</TITLE>";
}
?>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<link rel="stylesheet" href="http://plans.nilaos.ustu/CSS/PlansEditor.css" type="text/css">
</HEAD><BODY topmargin="1" leftmargin="5" marginheight="1" marginwidth="5">
<form name=fed method=post action=ChoiseSpz.php>
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
      //Если редактируется заголовок, то выводятся данные о предыдущих направлении, специальности. специализации
      echo "<center>Редактирование специальности стандарта</center></em>";
      echo "<table border='0' width='100%' cellpadding='0' cellspacing='2'><tr><td height='5' bgcolor=\"#92a2d9\"><img src=\"../img/line.gif\" width=15 height=15 hspace=0 vspace=0 border='0' align='left'></td></tr></table>";
      $result = mysql_query("select CodeOfDirect, CodeOfSpecial, CodeOfSpecialization from specialslimit where CodeOfStandard=".$standard,$Connection)
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

   //Вывод списка специальностей
   $Direct = $_POST['Dir'];
   $i = 0;

   if (!empty($Direct)){
      echo "<h2>Выберите специальность&nbsp;&nbsp;&nbsp;</h2>";
      $DirectResult = mysql_query("select MinistryCode, DirName from directions where CodeOfDirect=".$Direct, $Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
      if ($row = mysql_fetch_object($DirectResult)){
         echo "<strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Направление ".$row->MinistryCode." ".$row->DirName."</storng><P>\n";
      }
      echo "<CENTER>\n";
      echo "<table  border=0  align='center'>\n";
      echo "<TR><TD width=4%  valign='top' align='center'><INPUT TYPE='RADIO' NAME='Spec' VALUE='0' CHECKED></INPUT></TD>";
      echo "<TD width=5%   valign='top'>--------</TD>";
      echo "<TD width=40% valign='top'>Без специальности</TD>";
      echo "<td width=2%></td>";
      $i ++;

      $q= "select CodeOfSpecial, MinistryCode, SpcName from specials where CodeOfDirect=".$Direct;
      if($_SESSION["status"] == 2){//Если пользователь кафедра то отбираем специальности по кафедре
         $q .= " and CodeOfDepart = ".$_SESSION["statusCode"];
      }
      else{ //если пользователь уч. часть и выбран факультет, то отбираем специальности по факультету
         if($_SESSION["statusCode"]!=0){$q .= " and CodeOfFaculty = ".$_SESSION["statusCode"];}
      }
      $result = mysql_query($q, $Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   }else{
      //Если направление не определено проверяем существуют ли 
      //специальности "Без направления" 

      $q= "select CodeOfSpecial, MinistryCode, SpcName from specials where CodeOfDirect='0'";
      if($_SESSION["status"] == 2){//Если пользователь кафедра то отбираем специальности по кафедре
         $q .= " and CodeOfDepart = ".$_SESSION["statusCode"];
      }
      else{ //если пользователь уч. часть и выбран факультет, то отбираем специальности по факультету
         if($_SESSION["statusCode"]!=0){$q .= " and CodeOfFaculty = ".$_SESSION["statusCode"];}
      }
      $result = mysql_query($q, $Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");

      if (mysql_num_rows($result) != 0){
         echo "<h2>Выберите специальность&nbsp;&nbsp;&nbsp;</h2>";
         echo "<CENTER>\n";
         echo "<table  border=0  align='center'>\n";
      }else{
         echo "<h2>В справочнике специальностей отсутствуют специальности \"Без направления\".&nbsp;&nbsp;&nbsp;</h2>";
      }
   }

   while($row = mysql_fetch_object($result)) {
      if (($i % 2) == 0){ echo "<TR>\n";}
      $NameSpec = $row->SpcName;
      $CodeSpec = $row->CodeOfSpecial;
      $MinistryCode = $row->MinistryCode;
      if ($i == 0){echo "<TD width=4%  valign='top' align='center'><INPUT TYPE='RADIO' NAME='Spec' VALUE='".$CodeSpec."' CHECKED></INPUT></TD>";}
      else{echo "<TD width=4%  valign='top' align='center'><INPUT TYPE='RADIO' NAME='Spec' VALUE='".$CodeSpec."'></INPUT></TD>"; }
      echo "<TD width=5%   valign='top'>".$MinistryCode."</TD>";
      echo "<TD width=40% valign='top'>".$NameSpec."</TD>";
      
      if (($i % 2) == 1){ echo "</TR>\n";}
      else {echo "<TD width=2%></TD>";}
      $i ++;
   }

   if (($i % 2) == 1){ echo "</TR>\n";}
   mysql_close($Connection);

   if ($i != 0){ 
      echo "<input type='hidden' name='Dir' value=$Direct>";
      echo "</TABLE>";
      echo "</CENTER>";
      echo "<BR>";

      echo "<center>";
      echo "<input type=\"submit\" name=\"subm\" value=\"Дальше\">";
   }
   if (isset ($shift) && (strcmp($shift,"editSpc")==0)&& isset ($standard)){
      echo "<input type='hidden' name='shift' value='editSpc'>\n";
      echo "<input type='hidden' name='CodeOfStandard' value='$standard'>\n";
   }
?>
</center></form><hr /></body></html>