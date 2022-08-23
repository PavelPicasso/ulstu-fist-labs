<?php
   if (!($REQUEST_METHOD=='POST' && $_POST['NumOfChangeRec'])) {
     Header ("Location: LoadExpansionBook.php");
     exit;
   }
   include("../PlanCalculatFunc.php");
   CreateConnection();

   $numS = $_POST['NumOfChangeRec'];
   $num = explode(",", $numS); // преобразуем строку в массив
   while ( $ExpNum = each($num)){
      $ExpName="ExpName".$ExpNum[1];
      $DepCode="Depart".$ExpNum[1];
      $Semestr="Semestr".$ExpNum[1];
      $Hours="Hours".$ExpNum[1];
      $parExpName = $HTTP_POST_VARS[$ExpName];
      $parDepCode = $_POST[$DepCode];
      $parSemestr = $HTTP_POST_VARS[$Semestr];
      $parHours = $_POST[$Hours];
      mysql_query("Update expansion SET ExpansionName = '$parExpName', CodeOfDepart = '$parDepCode', Semester= '$parSemestr', Hours= $parHours 
      where CodeOfExpansion=".$ExpNum[1],$Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   }
   /*  */
   mysql_close($Connection);
   Header ("Location: UnderCiclesBook.php");
?>