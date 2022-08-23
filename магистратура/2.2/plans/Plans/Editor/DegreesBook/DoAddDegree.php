<?php
   $alert="../alert.html";
   $fill="../fill.html";
   $goto="AddDegree.php?shift=1";
   if (!($REQUEST_METHOD=='POST' && $_POST['DegreeName'])) {
     Header ("Location: DegreesBook.php");
     exit;
   }
   include("../../cfg.php");
   $Connection = mysql_connect($data_source , $dbi_user , $dbi_password)
      or die("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
   mysql_select_db("plans") 
      or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");
   $DegreeName = $_POST['DegreeName'];
   $Apprenticeship = 0;
   if ($_POST['Apprenticeship']){ $Apprenticeship = $_POST['Apprenticeship'];}
   $FirstYear = 0;
   if ($_POST['FirstYear']){ $FirstYear = $_POST['FirstYear'];}
   $Sequence = 0;
   if ($_POST['Sequence']){ $Sequence = $_POST['Sequence'];}
   $sh = $_POST['shift'];               
   $result = mysql_query("SELECT * from degrees where DegreeName='$DegreeName'", $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   if (!$res=mysql_fetch_row($result)){
      $result = mysql_query("select MAX(CodeOfDegree) from degrees", $Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
      $res=mysql_fetch_row($result);
      $i=$res[0];
      if ($i)
        $i+=1;
      else
        $i=1; 
      mysql_query("INSERT INTO degrees (CodeOfDegree, DegreeName, Apprenticeship, FirstYear, Sequence) 
      VALUES ($i, '$DegreeName', $Apprenticeship, $FirstYear, $Sequence)",$Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");         /*  */
      mysql_free_result($result);
      /*  */
      mysql_close($Connection);
      if ($sh==0){Header ("Location: DegreesBook.php");}  /* Редирект браузера на сайт PHP */
      if ($sh==1){Header ("Location: $goto");}
  }
  else{
      Header ("Location: $alert");
  }
  exit;
?>
