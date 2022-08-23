<?php
   session_start();
   if (!($_SESSION["status"] == 0)){
      Header ("Location: ../Unreg.html");
   }
?>
<HTML>
<HEAD>
<TITLE>Выбор контекста</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<link rel="stylesheet" href="../../../CSS/PlansEditor.css" type="text/css">
</HEAD><BODY topmargin="1" leftmargin="5" marginheight="1" marginwidth="5">
<CENTER>
<?php
   include("../../cfg.php");
   $Connection = mysql_connect($data_source , $dbi_user , $dbi_password)
      or die("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
   mysql_select_db("plans") 
      or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");
   $result = mysql_query("select * from faculty order by FacName", $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   while ($row = mysql_fetch_object($result)){
      $FacCode=$row->CodeOfFaculty;
      $Reduction=$row->Reduction;
      echo "<B>&nbsp;<a href=\"../Context/ChoiseContext.php?c=".$FacCode."\">".$Reduction."</a>&nbsp;</B>\n";
      echo "\n";
   }
   echo "<B>&nbsp;<a href=\"../Context/ChoiseContext.php?c=0\">ВСЕ ФАКУЛЬТЕТЫ</a>&nbsp;</B>\n";
   mysql_free_result($result);
   mysql_close($Connection);
?>
</CENTER>
</body>
</html>