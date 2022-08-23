<?php
   if (!($REQUEST_METHOD=='POST' && $_POST['flag'])) {
     Header ("Location: UnderCiclesBook.php");
     exit;
   }
   $alert="../alert.html";
   include("../../cfg.php");
   $Connection = mysql_connect($data_source , $dbi_user , $dbi_password)
      or die("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
   mysql_select_db("plans") 
      or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");
   /*  */
   while ( $UndCNum = each($flag)){
      mysql_query("update schedplan set CodeOfUndCicle=0 where CodeOfUndCicle=".$UndCNum[1],$Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error());
      mysql_query("Delete from undercicles where CodeOfUnderCicle=".$UndCNum[1],$Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error());
   }
   mysql_close($Connection);
   Header ("Location: UnderCiclesBook.php");
?>
