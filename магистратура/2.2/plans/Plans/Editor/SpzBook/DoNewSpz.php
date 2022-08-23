<?php
   session_start();
   if (!(($_SESSION["status"] == 0)||($_SESSION["status"] == 2))){
      Header ("Location: ../Unreg.html");
      exit;
   }
   $alert="../alert.html";
   $MinCodeAlert="../SpzBook/MinCodeAlert.html";
   $goto = "NewSpz.php?shift=";
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
   $SName = $_POST['SpzN'];
   $FacultyCode = $_POST['Faculty'];
   $DepartCode = $_POST['Depart'];
   $Type = $_POST['Type'];
   $DS = explode(",", $_POST['DS']);
   $Direct = $DS[0];
   $Spec = $DS [1];
   if ($Direct == 0){ $Direct = " NULL";}
   if ($Spec == 0){ $Spec = " NULL";}

   $sh = $_POST['shift'];               
   $goto .= $sh;

   $result = mysql_query("SELECT * from specializations where MinistryCode='".$MinistryCode."' and SpzName='".$SName."'", $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   if (!$row=mysql_fetch_row($result)){
      $result = mysql_query("SELECT MAX(CodeOfSpecialization) from specializations", $Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
      $res=mysql_fetch_row($result);
      $SpzCode=$res[0];
      if ($SpzCode){ $SpzCode += 1;}
      else { $SpzCode = 1;}
      mysql_query("INSERT INTO specializations (CodeOfSpecialization, MinistryCode, SpzName, CodeOfDepart, CodeOfFaculty, CodeOfDegree, CodeOfDirect, CodeOfSpecial) values ($SpzCode, '$MinistryCode', '$SName', $DepartCode, $FacultyCode, $Type, $Direct, $Spec)",$Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");         /*  */
      mysql_free_result($result);
      /*  */
      mysql_close($Connection);
      if (isset($back) && (strcmp($back,"") != 0)){
         Header ("Location: ".$back);
         exit;
      }
      if ($sh==0){Header ("Location: SpzBook.php");}  /*  */
      if ($sh==1){Header ("Location: $goto");
      }
  }
  else{
      Header ("Location: $alert");
  }
  exit;
?>
