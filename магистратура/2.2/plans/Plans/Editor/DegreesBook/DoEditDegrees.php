<?php
   if (!($REQUEST_METHOD=='POST' && $_POST['NumOfChangeRec'])) {
     Header ("Location: DegreesBook.php");
     exit;
   }
   include("../../cfg.php");
   $Connection = mysql_connect($data_source , $dbi_user , $dbi_password)
      or die("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
   mysql_select_db("plans") 
      or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");
   $numS = $_POST['NumOfChangeRec'];
   $num = explode(",", $numS); // преобразуем строку в массив
   while ( $DegreeNum = each($num)){
      $DegreeName="DegreeName".$DegreeNum[1];
      $parDegreeName = $_POST[$DegreeName];
      $parApprenticeship = 0;
      $Apprenticeship="Apprenticeship".$DegreeNum[1];
      if ($_POST[$Apprenticeship]){ $parApprenticeship = $_POST[$Apprenticeship];}
      $parFirstYear = 0;
      $FirstYear="FirstYear".$DegreeNum[1];
      if ($_POST[$FirstYear]){ $parFirstYear = $_POST[$FirstYear];}
      $parSequence = 0;
      $Sequence="Sequence".$DegreeNum[1];
      if ($_POST[$Sequence]){ $parSequence = $_POST[$Sequence];}
      mysql_query("Update degrees set DegreeName='$parDegreeName', 
      Apprenticeship = $parApprenticeship, 
      FirstYear=$parFirstYear, 
      Sequence=$parSequence 
      where CodeOfDegree=".$DegreeNum[1],$Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   }
   /*  */
   mysql_close($Connection);
   Header ("Location: DegreesBook.php");
?>