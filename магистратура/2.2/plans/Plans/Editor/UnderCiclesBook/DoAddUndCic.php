<?php
   $alert="../alert.html";
   $fill="../fill.html";
   $goto="AddUndCic.php?shift=1";
   if (!($REQUEST_METHOD=='POST' && $_POST['UndCicle'])) {
     Header ("Location: UnderCiclesBook.php");
     exit;
   }
   include("../../cfg.php");
   $Connection = mysql_connect($data_source , $dbi_user , $dbi_password)
      or die("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
   mysql_select_db("plans") 
      or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");
   $UndCicName = $_POST['UndCicle'];
   $UndCicRed = $_POST['UndCicRed'];
   $sh = $_POST['shift'];               
   $result = mysql_query("SELECT * from undercicles where UndCicName='$UndCicName'", $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   if (!$res=mysql_fetch_row($result)){
      $result = mysql_query("select MAX(CodeOfUnderCicle) from undercicles", $Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
      $res=mysql_fetch_row($result);
      $i=$res[0];
      if ($i)
        $i+=1;
      else
        $i=1; 
      mysql_query("INSERT INTO undercicles (CodeOfUnderCicle, UndCicName, UndCicReduction)
      VALUES ($i, '$UndCicName', '$UndCicRed')",$Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");         /*  */
      mysql_free_result($result);
      /*  */
      mysql_close($Connection);
      if ($sh==0){Header ("Location: UnderCiclesBook.php");}  /* Редирект браузера на сайт PHP */
      if ($sh==1){Header ("Location: $goto");}
  }
  else{
      Header ("Location: $alert");
  }
  exit;
?>
