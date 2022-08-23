<?php
   session_start();
   if (!(($_SESSION["status"] == 0)||($_SESSION["status"] == 2))){
      Header ("Location: ../Unreg.html");
      exit;
   }
   $alert="../alert.html";
   $MinCodeAlert="../DirectBook/MinCodeAlert.html";
   $goto = "NewDirect.php?shift=";
   if (!($REQUEST_METHOD=='POST' && $_POST['MinKod'])) {
     Header ("Location: ".$MinCodeAlert);
     exit;
   }
   include("../../cfg.php");
   $Connection = mysql_connect($data_source , $dbi_user , $dbi_password)
      or die("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
   mysql_select_db("plans") 
      or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");
   $MinistryCode = $_POST['MinKod'];
   $DirName = $_POST['DirectN'];
   $FacultyCode = $_POST['Faculty'];
   $DepartCode = $_POST['Depart'];
   $Type = $_POST['Type'];

   $sh = $_POST['shift'];               
   $goto .= $sh;

   $result = mysql_query("SELECT * from directions where MinistryCode='".$MinistryCode."' and DirName='".$DirName."'", $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   if (!$row=mysql_fetch_row($result)){
      $result = mysql_query("SELECT MAX(CodeOfDirect) from directions", $Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
      $res=mysql_fetch_row($result);
      $DirectCode=$res[0];
      if ($DirectCode){ $DirectCode += 1;}
      else { $DirectCode = 1;}
      mysql_query("INSERT INTO directions (CodeOfDirect, MinistryCode, DirName, CodeOfDepart, CodeOfFaculty, CodeOfDegree) values ($DirectCode, '$MinistryCode', '$DirName', $DepartCode, $FacultyCode, $Type)",$Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");         /*  */
      mysql_free_result($result);
      /*  */
      mysql_close($Connection);
      if (isset($back) && (strcmp($back,"") != 0)){
         Header ("Location: ".$back);
         exit;
      }
      if ($sh==0){Header ("Location: DirectBook.php");}  /*  */
      if ($sh==1){Header ("Location: $goto");
      }
  }
  else{
      Header ("Location: $alert");
  }
  exit;
?>
