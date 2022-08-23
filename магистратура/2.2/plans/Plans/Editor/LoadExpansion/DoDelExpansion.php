<?php
   if (!($REQUEST_METHOD=='POST' && $_POST['flag'])) {
     Header ("Location: LoadExpansionBook.php");
     exit;
   }
   $alert="../alert.html";
   include("../PlanCalculatFunc.php");
   CreateConnection();

   /*  */
   while ( $ExpNum = each($flag)){
      mysql_query("Delete from expansion where CodeOfExpansion=".$ExpNum[1],$Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error());
   }
   mysql_close($Connection);
   Header ("Location: LoadExpansionBook.php");
?>
