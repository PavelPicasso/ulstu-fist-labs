<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<HTML>
<HEAD>
<TITLE>Обновление таблиц</TITLE>
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
mysql_query("INSERT INTO streams VALUES (1,'ЭВМд',1,3,60,75);")
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
mysql_query("INSERT INTO streams VALUES (2,'ЭВМд',2,3,48,75);")
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
mysql_query("INSERT INTO streams VALUES (3,'МЭВМд',6,1,6,49);")
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
mysql_query("INSERT INTO streams VALUES (4,'ЭВМд',3,3,51,75);")
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
mysql_query("INSERT INTO streams VALUES (6,'МЭВМд',6,1,6,50);")
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
mysql_query("INSERT INTO streams VALUES (7,'ЭВМд',4,3,52,75);")
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
mysql_query("INSERT INTO streams VALUES (9,'ЭВМд',5,2,44,75);")
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
mysql_query("INSERT INTO streams VALUES (24,'ИДРд',1,1,50,29);")
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
mysql_query("INSERT INTO streams VALUES (20,'ЭВМд',4,2,52,52);")
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
mysql_query("INSERT INTO streams VALUES (23,'МЭВМд',5,1,6,49);")
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
mysql_query("INSERT INTO streams VALUES (22,'МЭВМд',5,1,6,50);")
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
mysql_query("INSERT INTO streams VALUES (25,'ИДРд',4,1,35,29);")
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
mysql_query("INSERT INTO streams VALUES (26,'СОд',2,1,60,86);")
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
mysql_query("INSERT INTO streams VALUES (27,'СОд',1,1,50,86);")
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
mysql_query("INSERT INTO streams VALUES (28,'ИСТд',4,1,33,63);")
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
mysql_query("INSERT INTO streams VALUES (29,'ПМИд',3,1,20,61);")
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
mysql_query("INSERT INTO streams VALUES (30,'Лд',1,1,20,85);")
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
mysql_query("INSERT INTO streams VALUES (31,'Лд',2,1,20,85);")
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
mysql_query("INSERT INTO streams VALUES (32,'Лд',3,1,20,85);")
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
mysql_query("INSERT INTO streams VALUES (33,'Лд',4,1,20,85);")
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
mysql_query("INSERT INTO streams VALUES (34,'ИСТд',1,2,60,63);")
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
    mysql_close($Connection);
    echo "<br><h1>Данные планов обнавлены</h1><br>";
?>
</CENTER>
<HR>
</BODY>
</HTML>