<?php
   if (!($REQUEST_METHOD=='POST' && $_POST['flag'])) {
     Header ("Location: DegreesBook.php");
     exit;
   }
   $alert="../alert.html";
   include("../../cfg.php");
   $Connection = mysql_connect($data_source , $dbi_user , $dbi_password)
      or die("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
   mysql_select_db("plans") 
      or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");
   /*  */
   $isGood = true;
   while ( $DegreeNum = each($flag)){
      $result =      mysql_query("select CodeOfDirect from directions where CodeOfDegree=".$DegreeNum[1],$Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error());
      $result1 =      mysql_query("select CodeOfSpecial from specials where CodeOfDegree=".$DegreeNum[1],$Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error());
      $result2 =      mysql_query("select CodeOfSpecialization from specializations where CodeOfDegree=".$DegreeNum[1],$Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error());
      if (($row = mysql_fetch_row($result))||($row = mysql_fetch_row($result1))||($row = mysql_fetch_row($result2))){
         $isGood = false;
      }else{
         mysql_query("Delete from degrees where CodeOfDegree=".$DegreeNum[1],$Connection)
            or die("Unable to execute query:".mysql_errno().": ".mysql_error());
      }
   }
   mysql_close($Connection);
   if (!$isGood){
      Header ("Location: alert.html");
      exit;
   }
   Header ("Location: DegreesBook.php");
?>
