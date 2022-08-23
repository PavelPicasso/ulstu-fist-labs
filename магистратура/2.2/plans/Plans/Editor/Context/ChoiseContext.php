<?php
   session_start();
   if (!($_SESSION["status"] == 0)){
      Header ("Location: ../Unreg.html");
   }

   $c = $_GET['c']; 	
   if (isset($c)){
      include("../../cfg.php");
      $Connection = mysql_connect($data_source , $dbi_user , $dbi_password)
         or die("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
      mysql_select_db("plans") 
         or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");
      $_SESSION["statusCode"] = $c;
     // mysql_query("update metausers set statusCode=".$c." where CodeOfUser=".$_SESSION["uCode"],$Connection)
       //  or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");         /*  */
      mysql_close($Connection);
   }
//   Header ("Location: ../Context/ShowContext.php");
//   exit;
?>
<HTML>
<HEAD>
<SCRIPT LANGUAGE="JAVASCRIPT">
<!-- Hide the JavaScripts
parent.Body.location.reload();
location.replace("../Context/ShowContext.php");
//Stop hiding the code-->
</SCRIPT>
</HEAD>
</HTML>