<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<HTML>
<HEAD>
<TITLE>���������� ������</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<LINK rel=stylesheet href="../../../CSS/Plans.css" type=text/css>
</HEAD>
<BODY topmargin=1 leftmargin=5 marginheight="1" marginwidth="5">
<CENTER><B><FONT FACE='Times New Roman Cyr' SIZE=5>
<?php 
   include("../PlanCalculatFunc.php");
   CreateConnection();
    mysql_query("DELETE FROM streams;")
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
mysql_query("INSERT INTO streams VALUES (1,'����',1,3,60,75);")
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
mysql_query("INSERT INTO streams VALUES (2,'����',2,3,48,75);")
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
mysql_query("INSERT INTO streams VALUES (3,'�����',6,1,6,49);")
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
mysql_query("INSERT INTO streams VALUES (4,'����',3,3,51,75);")
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
mysql_query("INSERT INTO streams VALUES (6,'�����',6,1,6,50);")
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
mysql_query("INSERT INTO streams VALUES (7,'����',4,3,52,75);")
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
mysql_query("INSERT INTO streams VALUES (9,'����',5,2,44,75);")
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
mysql_query("INSERT INTO streams VALUES (24,'����',1,1,50,29);")
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
mysql_query("INSERT INTO streams VALUES (20,'����',4,2,52,52);")
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
mysql_query("INSERT INTO streams VALUES (23,'�����',5,1,6,49);")
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
mysql_query("INSERT INTO streams VALUES (22,'�����',5,1,6,50);")
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
mysql_query("INSERT INTO streams VALUES (25,'����',4,1,35,29);")
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
mysql_query("INSERT INTO streams VALUES (26,'���',2,1,60,86);")
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
mysql_query("INSERT INTO streams VALUES (27,'���',1,1,50,86);")
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
mysql_query("INSERT INTO streams VALUES (28,'����',4,1,33,63);")
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
mysql_query("INSERT INTO streams VALUES (29,'����',3,1,20,61);")
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
mysql_query("INSERT INTO streams VALUES (30,'��',1,1,20,85);")
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
mysql_query("INSERT INTO streams VALUES (31,'��',2,1,20,85);")
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
mysql_query("INSERT INTO streams VALUES (32,'��',3,1,20,85);")
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
mysql_query("INSERT INTO streams VALUES (33,'��',4,1,20,85);")
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
mysql_query("INSERT INTO streams VALUES (34,'����',1,2,60,63);")
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
    mysql_close($Connection);
    echo "<br><h1>������ ������ ���������</h1><br>";
?>
</CENTER>
<HR>
</BODY>
</HTML>