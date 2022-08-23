<?php
   if (!(isset($_GET['code']))) {
      Header ("Location: /cgi/title.pl");
      exit;
   }

   include("cfg.php");
   include("Editor/PlanCalculatFunc.php");
   $Connection=mysql_connect($data_source , $dbi_user , $dbi_password) 
      or die ("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
   mysql_select_db("plans") 
      or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");

   $TblName = substr($_GET['code'], 0 ,3);
   $TblCode = substr($_GET['code'], 3 );
   $pln=$_GET['plan'];
   $name_trans = "Поток_общ";
   $time_all = 0;
   $date_trans = date("Y-m-d h:i:s");
   $time_b = getmicrotime();
   if (strcmp($TblName,"dir") == 0){
      $q = "select * from directions, department  where directions.CodeOfDepart=department.CodeOfDepart and CodeOfDirect=".$TblCode;
      $qSt = "select * from specialslimit where CodeOfDirect=".$TblCode;
      $result=mysql_query($q, $Connection) 
         or die("Query failed:".mysql_errno().": ".mysql_error()."<BR>");
      $time_e = getmicrotime();
      if ($row = mysql_fetch_object($result)){
         $DirCode = $row->CodeOfDirect;
         $DirMinCode = $row->MinistryCode;
         $DirName = $row->DirName;
         $DepRed = $row->Reduction;
         $DepName = $row->DepName;
         $DepURL = $row->URL;
         echo "<HTML><HEAD><TITLE>$DirMinCode $DirName</TITLE>\n";
      }
   }else{
      if (strcmp($TblName,"spc") == 0){
         $q = "select * from specials, department where specials.CodeOfDepart=department.CodeOfDepart and CodeOfSpecial=".$TblCode;
         $qSt = "select * from specialslimit where CodeOfSpecial=".$TblCode;
         $result=mysql_query($q, $Connection) 
            or die("Query failed:".mysql_errno().": ".mysql_error()."<BR>");
         if ($row = mysql_fetch_object($result)){
            $SpcCode = $row->CodeOfSpecial;
            $SpcMinCode = $row->MinistryCode;
            $SpcName = $row->SpcName;
            $DepRed = $row->Reduction;
            $DepName = $row->DepName;
            $DepURL = $row->URL;
            echo "<HTML><HEAD><TITLE>$SpcMinCode $SpcName</TITLE>\n";
            if (isset($row->CodeOfDirect)){
               $qSt .= " and CodeOfDirect=".$row->CodeOfDirect;
               $result=mysql_query("select * from directions where CodeOfDirect=".$row->CodeOfDirect, $Connection) 
                  or die("Query failed:".mysql_errno().": ".mysql_error()."<BR>");
               if ($row = mysql_fetch_object($result)){
                  $DirCode = $row->CodeOfDirect;
                  $DirMinCode = $row->MinistryCode;
                  $DirName = $row->DirName;
               }
            }else{
               $qSt .= " and CodeOfDirect is NULL";
            }
         }
         $time_e = getmicrotime();
      }else{
         if (strcmp($TblName,"spz") == 0){
            $q = "select * from specializations, department where specializations.CodeOfDepart=department.CodeOfDepart and CodeOfSpecialization=".$TblCode;
            $qSt = "select * from specialslimit where CodeOfSpecialization=".$TblCode;
            $result=mysql_query($q, $Connection) 
               or die("Query failed:".mysql_errno().": ".mysql_error()."<BR>");
            if ($row = mysql_fetch_object($result)){
               $SpzCode = $row->CodeOfSpecialization;
               $SpzMinCode = $row->MinistryCode;
               $SpzName = $row->SpzName;
               $DepRed = $row->Reduction;
               $DepName = $row->DepName;
               $DepURL = $row->URL;
               echo "<HTML><HEAD><TITLE>$SpzMinCode $SpzName</TITLE>\n";
               if (isset($row->CodeOfDirect)){
                  $qSt .= " and CodeOfDirect=".$row->CodeOfDirect;
                  $result=mysql_query("select * from directions where CodeOfDirect=".$row->CodeOfDirect, $Connection) 
                     or die("Query failed:".mysql_errno().": ".mysql_error()."<BR>");
                  if ($row = mysql_fetch_object($result)){
                     $DirCode = $row->CodeOfDirect;
                     $DirMinCode = $row->MinistryCode;
                     $DirName = $row->DirName;
                  }else{
                     $qSt .= " and CodeOfDirect is NULL";
                  }
               }
               if (isset($row->CodeOfSpecial)){
                  $result=mysql_query("select * from specials where CodeOfSpecial=".$row->CodeOfSpecial, $Connection) 
                     or die("Query failed:".mysql_errno().": ".mysql_error()."<BR>");
                  if ($row = mysql_fetch_object($result)){
                     $qSt .= " and CodeOfSpecial=".$row->CodeOfSpecial;
                     $SpcCode = $row->CodeOfDirect;
                     $SpcMinCode = $row->MinistryCode;
                     $SpcName = $row->SpcName;
                  }else{
                     $qSt .= " and CodeOfspecial is NULL";
                  }
               }
            }//получение данных по специализации
            $time_e = getmicrotime();
         }//если нет код специализации
      }//если нет кода специальности
   }//если нет кода направления
   $time_all += $time_e-$time_b;
?>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<link rel="stylesheet" href="../CSS/Plans.css" type="text/css"></HEAD>

<BODY topmargin="2" leftmargin="2" marginheight="2" marginwidth="2" >
<br><CENTER><FONT FACE="Arial"><FONT COLOR="#000033">
<?php
if (isset ($DirMinCode)){ echo "<FONT SIZE=+2>Направление $DirMinCode &#151; $DirName</FONT><P>\n";}
if (isset ($SpcMinCode)){ echo "<FONT SIZE=+2>Специальность $SpcMinCode &#151; $SpcName</FONT><P>\n";}
if (isset ($SpzMinCode)){ echo "<FONT SIZE=+2>Специализация $SpzMinCode &#151; $SpzName</FONT><P>\n";}

echo "</CENTER>\n";

//Вывод ведущей кафедры
if (isset($DepURL)){echo "<em class=\"h2\">Ведущая кафедра&nbsp; <a href=\"http://".$DepURL."\">$DepName&nbsp;($DepRed)</a></em><P>\n";}
else {echo "<em class=\"h2\">Ведущая кафедра&nbsp; $DepName&nbsp;($DepRed)</em>\n";}
include ("ShowSpcFunc.php");
$time_b = getmicrotime();
ShowStandards($qSt);

ShowStreams();
$time_e = getmicrotime();
$time_all += $time_e-$time_b;
$id_sess = session_id();
mysql_query("Insert into logs (name_trans, id_sess, date_trans, time_trans) values ('$name_trans', '$id_sess', '$date_trans', '$time_all')")
               or die("Unable to execute query:".mysql_errno().": ".mysql_error());
mysql_close($Connection);
?>


<br>
<center><HR WIDTH="100%"></center>
</BODY>
</HTML>