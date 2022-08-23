<?php
   session_start();
   if (!(($_SESSION["status"] == 0)||($_SESSION["status"] == 1)||($_SESSION["status"] == 2))){
      Header ("Location: Unreg.html");
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE>Учебные планы</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<LINK rel=stylesheet href="../../CSS/PlansEditor.css" type=text/css>
</HEAD>
<BODY topmargin=1 leftmargin=5 marginheight="1" marginwidth="5">
<FORM NAME=CHOISE METHOD=POST ACTION="GuestBook.php">
<CENTER>
<em class='h1'>
<center>Замечания и предложения</center></em>
<table border='0' width='100%' cellpadding='0' cellspacing='2'>
<tr><td height='5' bgcolor="#92a2d9">
<img src="img/line.gif" width=15 height=15 hspace=0 vspace=0 border='0' align='left'>
</td></tr></table>
<h2>Просмотр замечаний и предложений</h2>
<HR>
</CENTER>
<?php  
   include("../cfg.php");
   $Connection = mysql_connect($data_source , $dbi_user , $dbi_password)
      or die("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
   mysql_select_db("plans") 
      or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");
   if ($REQUEST_METHOD=='POST' && $_POST["DELETE"] && ($_SESSION["status"] == 0)) {
     reset($Del);
     while ($id=(current($Del))){
       mysql_db_query("plans","DELETE FROM mesages WHERE mesage_id=$id",$Connection)
       or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
       next($Del);  
     }  
   }
   $result = mysql_query("SELECT * from mesages ORDER BY mesage_id DESC",$Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   while($row = mysql_fetch_object($result)) {
      $msg = str_replace("\n", "<br>", htmlspecialchars($row->Mesage));
      echo "<h2><P align=left>$row->Author</h2></P><P>$msg<P>$row->Date";
      if ($_SESSION["status"] == 0){ echo "</P><P><INPUT TYPE='checkbox'  VALUE='$row->mesage_id' NAME=Del[$row->mesage_id]></P>";}
      echo "<HR WIDTH='100%'><P>";
   }
   /* */
   mysql_free_result($result);
   /* */
   mysql_close($Connection);
      if ($_SESSION["status"] == 0){ echo "<CENTER><INPUT TYPE=SUBMIT NAME=DELETE VALUE='Удалить отмеченные замечания'><P>";}
?>
<P>
</FORM></BODY></HTML>