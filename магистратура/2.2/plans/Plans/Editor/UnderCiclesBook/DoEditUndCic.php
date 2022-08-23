<?php
   if (!($REQUEST_METHOD=='POST' && $_POST['NumOfChangeRec'])) {
     Header ("Location: UnderCiclesBook.php");
     exit;
   }

   include("../../cfg.php");
   $Connection = mysql_connect($data_source , $dbi_user , $dbi_password)
      or die("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
   mysql_select_db("plans") 
      or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");

   $numS = $_POST['NumOfChangeRec'];
   $num = explode(",", $numS); // преобразуем строку в массив
   while ( $UndCNum = each($num)){
      $UndCicle="UndCicle".$UndCNum[1];
      $UndCicRed="UndCicRed".$UndCNum[1];
      $parUndCicle = $_POST[$UndCicle];
      $parUndCicRed = $_POST[$UndCicRed];
      mysql_query("Update undercicles set UndCicName='$parUndCicle', UndCicReduction='$parUndCicRed'  
      where CodeOfUnderCicle=".$UndCNum[1],$Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   }
   /*  */
   mysql_close($Connection);
   Header ("Location: UnderCiclesBook.php");
?>