<?php
   $alert="../SpzBook/alert.html";
   session_start();
   if (!(($_SESSION["status"] == 0)||($_SESSION["status"] == 2))){
      Header ("Location: ../Unreg.html");
      exit;
   }

   if (!($REQUEST_METHOD=='POST' && $_POST['NumOfChangeRec'])) {
     Header ("Location: SpzBook.php");
     exit;
   }
   include("../../cfg.php");
   $Connection = mysql_connect($data_source , $dbi_user , $dbi_password)
      or die("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
   mysql_select_db("plans") 
      or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");
   $numS = $_POST['NumOfChangeRec'];
   $num = explode(",", $numS); // преобразуем строку в массив
   while ($SpzNum = each($num)){

      $MinCod="MinCode".$SpzNum[1];
      $parMinCod = $_POST[$MinCod];

      $Spz="Spz".$SpzNum[1];
      $parSpz = $_POST[$Spz];
      
      $Fac="Fac".$SpzNum[1];
      $parFac = $_POST[$Fac];
      
      $Depart="Depart".$SpzNum[1];
      $parDepart = $_POST[$Depart];

      $DS="DS".$SpzNum[1];
      $parDS = explode(",", $_POST[$DS]);
      $Direct = $parDS[0];
      $Spec = $parDS [1];
      if ($Direct == 0){ $Direct = " NULL";}
      if ($Spec == 0){ $Spec = " NULL";}
      
      $Type="Type".$SpzNum[1];
      $parType = $_POST[$Type];
      
      mysql_query("update plans set CodeOfDirect=$Direct, CodeOfSpecial=$Spec where CodeOfSpecialization=".$SpzNum[1],$Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
      mysql_query("update specialslimit set CodeOfDirect=$Direct, CodeOfSpecial=$Spec where CodeOfSpecialization=".$SpzNum[1],$Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
      mysql_query("update specializations set MinistryCode='$parMinCod', SpzName='$parSpz',  CodeOfFaculty=$parFac, CodeOfDepart=$parDepart, CodeOfDegree=$parType, CodeOfDirect=$Direct, CodeOfSpecial=$Spec where CodeOfSpecialization=".$SpzNum[1],$Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   }
   /*  */
   mysql_close($Connection);
   Header ("Location: SpzBook.php");
?>?>