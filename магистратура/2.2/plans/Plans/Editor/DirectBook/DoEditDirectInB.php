<?php
   $alert="../DirectBook/alert.html";
   session_start();
   if (!(($_SESSION["status"] == 0)||($_SESSION["status"] == 2))){
      Header ("Location: ../Unreg.html");
      exit;
   }

   if (!($REQUEST_METHOD=='POST' && $_POST['NumOfChangeRec'])) {
     Header ("Location: DirectBook.php");
     exit;
   }
   include("../../cfg.php");
   $Connection = mysql_connect($data_source , $dbi_user , $dbi_password)
      or die("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
   mysql_select_db("plans") 
      or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");
   $numS = $_POST['NumOfChangeRec'];
   $num = explode(",", $numS); // преобразуем строку в массив
   while ($DirectNum = each($num)){

      $MinCod="MinCode".$DirectNum[1];
      $parMinCod = $_POST[$MinCod];

      $Dir="Dir".$DirectNum[1];
      $parDir = $_POST[$Dir];
      
      $Fac="Fac".$DirectNum[1];
      $parFac = $_POST[$Fac];
      
      $Depart="Depart".$DirectNum[1];
      $parDepart = $_POST[$Depart];
      
      $Type="Type".$DirectNum[1];
      $parType = $_POST[$Type];
      
      mysql_query("update directions set MinistryCode='$parMinCod', DirName='$parDir',  CodeOfFaculty=$parFac, CodeOfDepart=$parDepart, CodeOfDegree=$parType	where CodeOfDirect=".$DirectNum[1],$Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   }
   /*  */
   mysql_close($Connection);
   Header ("Location: DirectBook.php");
?>?>