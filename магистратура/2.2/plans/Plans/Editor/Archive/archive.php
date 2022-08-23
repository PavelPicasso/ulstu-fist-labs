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
</HEAD><BODY topmargin="1" leftmargin="5" marginheight="1" marginwidth="5">
<form name=fed method=post action="">
<em class='h1'><center>Архив учебных планов</center></em><table border='0' width='100%' cellpadding='0' cellspacing='2'><tr><td height='5' bgcolor="#92a2d9"><img src="../img/line.gif" width=15 height=15 hspace=0 vspace=0 border='0' align='left'></td></tr></table><h2>Выберите план для редактирования</h2><TABLE border=0 align='center' cellspacing="5" celpadding="4" width="90%">
<TR>
<TD width="8%">&nbsp;</TD>
<TD width="50%"><strong>Направление/Специальность/Специализация</strong><BR><BR></TD>
<TD>&nbsp;</TD>
<TD width="40%"><strong>Название плана</strong><BR><BR></TD>
<TD>&nbsp;</TD>
</TR>
<?php
   include("../PlanCalculatFunc.php");
   CreateConnection();
   $name_trans = "Восст_арх";
   $time_all = 0;
   $date_trans = date("Y-m-d h:i:s");   

   if (!empty($_GET['arch'])) { 
    $time_b = getmicrotime();
    $arch = $_GET['arch'];
    $q = "UPDATE plans SET Date=NULL WHERE CodeOfPlan=".$arch;    
    mysql_query($q, $Connection)
            or die("Unable to execute query:".mysql_errno().": ".mysql_error());
    $time_e = getmicrotime();
    $time_all += $time_e-$time_b;

   }
    
   if ((!isset($_SESSION["statusCode"])) || ($_SESSION["statusCode"] == 0) ){
       $q = "Select distinct Date from plans where (Date is not NULL and Date<>'0000-00-00') order by Date";
   }
   else{
    $q = "Select distinct Date from plans left join directions on plans.CodeOfDirect=directions.CodeOfDirect 
     left join specials on plans.CodeOfSpecial=specials.CodeOfSpecial 
     left join specializations on plans.CodeOfSpecialization=specializations.CodeOfSpecialization 
    where (directions.CodeOfFaculty=".$_SESSION["statusCode"]." or specials.CodeOfFaculty=".$_SESSION["statusCode"]."
         or specializations.CodeOfFaculty=".$_SESSION["statusCode"].") and (Date is not NULL and Date<>'0000-00-00') group by Date order by directions.MinistryCode,
     specials.MinistryCode, specializations.MinistryCode";
   }
   $time_b = getmicrotime();
   $date_arch = mysql_query($q, $Connection)
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   $time_e = getmicrotime();
   $time_all += $time_e-$time_b;
    
   while ($res = mysql_fetch_object($date_arch)){

        $arch_d = $res->Date;
        echo "<TR>\n";
        echo "<TD valign='top' colspan=5><b><u>Архив от ".$arch_d."</u></b></TD>\n";
        echo "</TR>\n";

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
        $time_b = getmicrotime();
        //Получение данных о специальности
        $result = mysql_query($q, $Connection)
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
        $time_e = getmicrotime();
    $time_all += $time_e-$time_b;

        $flag = 0;
        while ($row = mysql_fetch_object($result)){
            $MinCode = $row->MinistryCode;
            $NameDir = $row->DirName;
            $CodeDir = $row->CodeOfDirect;
            $time_b = getmicrotime();  
            $resPlan = mysql_query("SELECT * FROM plans where (CodeOfDirect=".$CodeDir." and (CodeOfSpecial = '0') and (CodeOfSpecialization = '0') and (Date='".$arch_d."')) order by PlnName", $Connection)
                or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
            $time_e = getmicrotime();
        $time_all += $time_e-$time_b;       

            while ($rowPl = mysql_fetch_object($resPlan)){
                $NamePlan = $rowPl->PlnName;
                $CodePlan = $rowPl->CodeOfPlan;
                echo "<TR>\n";
                echo "<TD valign='top'><b>$MinCode</b></TD>\n";
                echo "<TD valign='top' width=40%>$NameDir</TD>\n";
                echo "<TD>&nbsp;</TD>\n";
                echo "<TD  valign='top'>$NamePlan</TD>\n";
                echo "<TD><a href='archive.php?arch=".$CodePlan."' onclick='return confirm(\"Вы хотите восстановить план из архива?\");'>восстановить&nbsp;из&nbsp;архива</a></TD>\n";
                echo "</TR>\n";
            }
            mysql_free_result($resPlan);
        }

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
        $time_e = getmicrotime();
    $time_all += $time_e-$time_b;

        while ($row = mysql_fetch_object($result)){
            $MinCode = $row->MinistryCode;
            $NameSpecial = $row->SpcName;
            $CodeSpec = $row->CodeOfSpecial;
            $time_b = getmicrotime();
            $resPlan = mysql_query("SELECT * FROM plans where (CodeOfSpecial=".$CodeSpec." and (CodeOfSpecialization = '0') and (Date='".$arch_d."')) order by PlnName", $Connection)
                or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
            $time_e = getmicrotime();
        $time_all += $time_e-$time_b;

            while ($rowPl = mysql_fetch_object($resPlan)){
                $NamePlan = $rowPl->PlnName;
                $CodePlan = $rowPl->CodeOfPlan;
                echo "<TR>\n";
                echo "<TD valign='top'><b>$MinCode</b></TD>\n";
                echo "<TD valign='top'>$NameSpecial</TD>\n";
                echo "<TD>&nbsp;</TD>\n";
                echo "<TD  valign='top'>$NamePlan</TD>\n";
                echo "<TD><a href='Archive.php?arch=".$CodePlan."' onclick='return confirm(\"Вы хотите восстановить план из архива?\");'>восстановить&nbsp;из&nbsp;архива</a></TD>\n";
                echo "</TR>\n";
            }
            mysql_free_result($resPlan);
        }

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
    $time_e = getmicrotime();
        $time_all += $time_e-$time_b;

        while ($row = mysql_fetch_object($result)){
            $MinCode = $row->MinistryCode;
            $NameSpz = $row->SpzName;
            $CodeSpz = $row->CodeOfSpecialization;
            $time_b = getmicrotime();
            $resPlan = mysql_query("SELECT * FROM plans where CodeOfSpecialization=".$CodeSpz." and (Date='".$arch_d."') order by PlnName", $Connection)
                or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
        $time_e = getmicrotime();
        $time_all += $time_e-$time_b;

            while ($rowPl = mysql_fetch_object($resPlan)){
                $NamePlan = $rowPl->PlnName;
                $CodePlan = $rowPl->CodeOfPlan;
                echo "<TR>\n";
                echo "<TD valign='top'><b>$MinCode</b></TD>\n";
                echo "<TD valign='top'>$NameSpz</TD>\n";
                echo "<TD>&nbsp;</TD>\n";
                echo "<TD  valign='top'>$NamePlan</TD>\n";
                echo "<TD><a href='Archive.php?arch=".$CodePlan."' onclick='return confirm(\"Вы хотите восстановить план из архива?\");'>восстановить&nbsp;из&nbsp;архива</a></TD>\n";
                echo "</TR>\n";
            }
            mysql_free_result($resPlan);
        }
        mysql_free_result($result);
  }
  mysql_free_result($date_arch);
  $id_sess = session_id();
  mysql_query("Insert into logs (name_trans, id_sess, date_trans, time_trans) values ('$name_trans', '$id_sess', '$date_trans', '$time_all')")
               or die("Unable to execute query:".mysql_errno().": ".mysql_error());

  mysql_close($Connection);
?>
</TABLE><BR>
</form>
<hr />
</body>
</html>