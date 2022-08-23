<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<HTML>
<HEAD>
<TITLE>Обновление таблиц</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<LINK rel=stylesheet href="../../CSS/Plans.css" type=text/css>
</HEAD>
<BODY topmargin=1 leftmargin=5 marginheight="1" marginwidth="5">
<CENTER><B><FONT FACE='Times New Roman Cyr' SIZE=5>
<?php 
    $data_source = "localhost:3306";
    $dbi_user = "vsbk";
    $dbi_password = "dbplanspass";
    $Connection=mysql_connect($data_source , $dbi_user , $dbi_password) 
       or die ("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
    mysql_select_db("plans") 
       or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");
    mysql_query(
     "CREATE TABLE schedplan (\n".
     "  CodeOfSchPlan int(11) NOT NULL AUTO_INCREMENT,\n".
     "  CodeOfPlan int(11) default NULL,\n".
     "  CodeOfDiscipline int(11) default NULL,\n".
     "  CodeOfCicle int(11) default NULL,\n".
     "  CodeOfUndCicle int(11) default NULL,\n".
     "  CodeOfDepart int(11) default NULL,\n".
     "  UndCicCode varchar(7) default NULL,\n".
     "  AllH int(11) default NULL,\n".
     "  ToCount int(11) default NULL,\n".
     "  PRIMARY KEY (CodeOfSchPlan)\n".
     ") TYPE=MyISAM;")
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
    mysql_query(
     "CREATE TABLE schplanitems (\n".
     "  CodeOfSchPlanItem int(11) NOT NULL AUTO_INCREMENT,\n".
     "  CodeOfSchPlan int(11) default NULL,\n".
     "  CodeOfDepart int(11) default NULL,\n".
     "  NumbOfSemestr int(11) default NULL,\n".
     "  AuditH int(11) default NULL,\n".
     "  LectInW int(11) default NULL,\n".
     "  LabInW int(11) default NULL,\n".
     "  PractInW int(11) default NULL,\n".
     "  KursWork int(11) default NULL,\n".
     "  KursPrj int(11) default NULL,\n".
     "  Test int(11) default NULL,\n".
     "  Exam int(11) default NULL,\n".
     "  ControlWork int(11) default NULL,\n".
     "  RGR int(11) default NULL,\n".
     "  CalcW int(11) default NULL,\n".
     "  TestW int(11) default NULL,\n".
     "  Synopsis int(11) default NULL,\n".
     "  PRIMARY KEY (CodeOfSchPlanItem)\n".
     ") TYPE=MyISAM;")
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
    $result = mysql_query("select * from schplan order by CodeOfPlan, CodeOfDiscipline")
       or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
    $OldPlan = -1;
    $OldDiscip = -1;
    $CodeOfSchPlan = 0;
    while($row = mysql_fetch_object($result)) {
        if ($row->CodeOfPlan != $OldPlan || $row->CodeOfDiscipline != $OldDiscip) {
            mysql_query("INSERT INTO schedplan (CodeOfPlan, CodeOfDiscipline, CodeOfCicle, CodeOfUndCicle, CodeOfDepart, UndCicCode, AllH, ToCount) VALUES ('$row->CodeOfPlan', '$row->CodeOfDiscipline', '$row->CodeOfCicle', '$row->CodeOfUndCicle', '$row->CodeOfDepart', '$row->UndCicCode', '$row->AllH', '$row->ToCount')")
               or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
            $result1 = mysql_query("SELECT CodeOfSchPlan FROM schedplan WHERE CodeOfPlan='$row->CodeOfPlan' and  CodeOfDiscipline='$row->CodeOfDiscipline'")
               or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
            $temp  = mysql_fetch_array($result1);
            $CodeOfSchPlan = $temp["CodeOfSchPlan"];
        }    
        mysql_query("INSERT INTO schplanitems (CodeOfSchPlan, CodeOfDepart, NumbOfSemestr, AuditH, LectInW, LabInW, PractInW, KursWork, KursPrj, Test, Exam, ControlWork, RGR, CalcW, TestW, Synopsis) VALUES ('$CodeOfSchPlan', '$row->CodeOfDepart', '$row->NumbOfSemestr', '$row->AuditH', '$row->LectInW', '$row->LabInW', '$row->PractInW', '$row->KursWork', '$row->KursPrj', '$row->Test', '$row->Exam', '$row->ControlWork', '$row->RGR', '$row->CalcW', '$row->TestW', '$row->Synopsis')")
           or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
        $OldPlan = $row->CodeOfPlan;
        $OldDiscip = $row->CodeOfDiscipline;
    }
    mysql_close($Connection);
    echo "<br><h1>Данные планов обнавлены</h1><br>";
?>
</CENTER>
<HR>
</BODY>
</HTML>