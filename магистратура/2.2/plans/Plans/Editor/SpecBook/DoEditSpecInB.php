<?php
   $alert="../SpecBook/alert.html";
   session_start();
   if (!(($_SESSION["status"] == 0)||($_SESSION["status"] == 2))){
      Header ("Location: ../Unreg.html");
      exit;
   }

   if (!($REQUEST_METHOD=='POST' && $_POST['NumOfChangeRec'])) {
     Header ("Location: SpecBook.php");
     exit;
   }
   include("../../cfg.php");
   $Connection = mysql_connect($data_source , $dbi_user , $dbi_password)
      or die("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
   mysql_select_db("plans") 
      or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");
   $numS = $_POST['NumOfChangeRec'];
   $num = explode(",", $numS); // преобразуем строку в массив
   while ($SpecNum = each($num)){

      $MinCod="MinCode".$SpecNum[1];
      $parMinCod = $_POST[$MinCod];

      $Spc="Spc".$SpecNum[1];
      $parSpc = $_POST[$Spc];
      
      $Fac="Fac".$SpecNum[1];
      $parFac = $_POST[$Fac];
      
      $Depart="Depart".$SpecNum[1];
      $parDepart = $_POST[$Depart];

      $Direct="Dir".$SpecNum[1];
      $parDirect = $_POST[$Direct];
      if ($parDirect == 0){ $parDirect = " NULL";}
      
      $Type="Type".$SpecNum[1];
      $parType = $_POST[$Type];
      
      mysql_query("update plans set CodeOfDirect=$parDirect where CodeOfSpecial=".$SpecNum[1],$Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
      mysql_query("update specialslimit set CodeOfDirect=$parDirect where CodeOfSpecial=".$SpecNum[1],$Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
      mysql_query("update specials set MinistryCode='$parMinCod', SpcName='$parSpc',  CodeOfFaculty=$parFac, CodeOfDepart=$parDepart, CodeOfDegree=$parType, CodeOfDirect=$parDirect where CodeOfSpecial=".$SpecNum[1],$Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   }
   /*  */
   mysql_close($Connection);
   Header ("Location: SpecBook.php");
?>?>