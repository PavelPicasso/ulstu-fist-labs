<?php
   if (!($REQUEST_METHOD=='POST')) {
     Header ("Location: Administr.php");
     exit;
   }
   include("../../cfg.php");
   $Connection = mysql_connect($data_source , $dbi_user , $dbi_password)
      or die("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
   mysql_select_db("plans") 
      or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");
   $Rector = $_POST['Rector'];
   $RectorSignature = $_POST['RectorSignature'];
   $HeadOfStudies = $_POST['HeadOfStudies'];
   $HeadSignature = $_POST['HeadSignature'];
   $ProRectorTW = $_POST['ProRectorTW'];
   $ProRectorTWSignature = $_POST['ProRectorTWSignature'];
   mysql_query("UPDATE administration  SET Rector='$Rector', 
   RectorSignature='$RectorSignature', 
   HeadOfStudies='$HeadOfStudies', 
   HeadSignature='$HeadSignature', 
   ProRectorTW='$ProRectorTW', 
   ProRectorTWSignature='$ProRectorTWSignature' ",$Connection)
   or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");         /*  */

   mysql_close($Connection);

   Header ("Location: Administr.php");
?>
