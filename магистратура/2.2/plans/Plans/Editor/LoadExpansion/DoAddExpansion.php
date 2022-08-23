<?php
   $alert="../alert.html";
   $fill="../fill.html";
   $goto="AddExpansion.php?shift=1";
   if (!($REQUEST_METHOD=='POST' && $_POST['ExpName'] && $_POST['Hours'])) {
     Header ("Location: ExpansionBook.php");
     exit;
   }
   include("../PlanCalculatFunc.php");
   CreateConnection();

   $ExpName=$_POST['ExpName'];
   $DepCode=$_POST['Depart'];
   $Semestr=$_POST['Semestr'];
   $Hours=$_POST['Hours'];
   $sh = $_POST['shift'];               
   $result = mysql_query("SELECT * from expansion where ExpansionName='$ExpName' and CodeOfDepart = $DepCode and Semester='$Semester'", $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   if (!$res=mysql_fetch_row($result)){
      $result = mysql_query("select MAX(CodeOfExpansion) from expansion", $Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
      $res=mysql_fetch_row($result);
      $i=$res[0];
      if ($i)
        $i+=1;
      else
        $i=1; 
      mysql_query("INSERT INTO expansion (CodeOfExpansion, CodeOfDepart, ExpansionName, Semester, Hours) 
      VALUES ($i, $DepCode, '$ExpName', $Semestr, $Hours)",$Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");         /*  */
      mysql_free_result($result);
      /*  */
      mysql_close($Connection);
      if ($sh==0){Header ("Location: LoadExpansionBook.php");}  /* Редирект браузера на сайт PHP */
      if ($sh==1){Header ("Location: $goto");}
  }
  else{
      Header ("Location: $alert");
  }
  exit;
?>
