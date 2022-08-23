<?php
   $alert="../SpecBook/alert.html";
   session_start();
   if (!(($_SESSION["status"] == 0)||($_SESSION["status"] == 2))){
      Header ("Location: ../Unreg.html");
      exit;
   }
   if (!($REQUEST_METHOD=='POST' && $_POST['flag'])) {
     Header ("Location: UnderCiclesBook.php");
     exit;
   }
   include("../../cfg.php");
   $Connection = mysql_connect($data_source , $dbi_user , $dbi_password)
      or die("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
   mysql_select_db("plans") 
      or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");
   /*  */
   $Ok = 1; //флаг того, что удаление прошло благополучно
   while ( $delflag = each($flag)){
      $result = mysql_query("SELECT * from plans where CodeOfSpecial=".$delflag[1], $Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error());
      if (mysql_fetch_row($result)){$Ok = 0;}
      else{
         mysql_query("delete from specials where CodeOfSpecial=".$delflag[1], $Connection)
            or die("Unable to execute query:".mysql_errno().": ".mysql_error());
      }
      mysql_free_result($result);
   }
   mysql_close($Connection);
   if ($Ok){ Header ("Location: SpecBook.php");}
   else {Header ("Location: ".$alert);}
?>
