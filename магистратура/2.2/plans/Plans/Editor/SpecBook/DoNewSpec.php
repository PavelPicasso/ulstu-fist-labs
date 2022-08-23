<?php
   session_start();
   if (!(($_SESSION["status"] == 0)||($_SESSION["status"] == 2))){
      Header ("Location: ../Unreg.html");
      exit;
   }
   $alert="../alert.html";
   $MinCodeAlert="../SpecBook/MinCodeAlert.html";
   $goto = "NewSpec.php?shift=";
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
   $SName = $_POST['SpecN'];
   $FacultyCode = $_POST['Faculty'];
   $DepartCode = $_POST['Depart'];
   $Type = $_POST['Type'];
   $Direct = $_POST['Direction'];
   if ($Direct == 0){ $Direct = " NULL";}

   $sh = $_POST['shift'];               
   $goto .= $sh;

   $result = mysql_query("SELECT * from specials where MinistryCode='".$MinistryCode."' and SpcName='".$SName."'", $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   if (!$row=mysql_fetch_row($result)){
      $result = mysql_query("SELECT MAX(CodeOfSpecial) from specials", $Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
      $res=mysql_fetch_row($result);
      $SpecCode=$res[0];
      if ($SpecCode){ $SpecCode += 1;}
      else { $SpecCode = 1;}
      mysql_query("INSERT INTO specials (CodeOfSpecial, MinistryCode, SpcName, CodeOfDepart, CodeOfFaculty, CodeOfDegree, CodeOfDirect) values ($SpecCode, '$MinistryCode', '$SName', $DepartCode, $FacultyCode, $Type, $Direct)",$Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");         /*  */
      mysql_free_result($result);
      /*  */
      mysql_close($Connection);
      if (isset($back) && (strcmp($back,"") != 0)){
         Header ("Location: ".$back);
         exit;
      }
      if ($sh==0){Header ("Location: SpecBook.php");}  /*  */
      if ($sh==1){Header ("Location: $goto");
      }
  }
  else{
      Header ("Location: $alert");
  }
  exit;
?>
