<?php
   if (!(isset($_GET['faculty']))) {
      Header ("Location: index.php");
      exit;
   }

   $CodeOfFaculty = $_GET['faculty'];
   if (isset($_GET['sort_key']) && ($_GET['sort_key']>=1) && ($_GET['sort_key']<=7)){ $Sr = $_GET['sort_key']; }
   else{ $Sr = 3; }
 
   $PlanHref="planFull.php?if=0\n";
   $FullPlanHref="planFull.php?if=1\n";
   $ReportPlanHref="ShowStreams.php\n";
   $SpcHref="ShowSpc.php?\n";
   $HoursPlanHref="hours.php\n";
   $SocrPlanHref="PlanSocr.php\n";
   $self = "faculty.php?faculty=".$CodeOfFaculty;
   $TForms = array ("classroom"=>"ОЧНАЯ", 
                  "zaoch"=>"ЗАОЧНАЯ", 
                  "night"=>"ВЕЧЕРНЯЯ",
                  "distance"=>"ДИСТАНЦИОННАЯ", 
                  "external"=>"ЭКСТЕРНАТ");

   $Osn = array(0=>"ПОЛНАЯ", 1=>"УСКОР");
   include("cfg.php");
   include("Editor/PlanCalculatFunc.php");
   $Connection=mysql_connect($data_source , $dbi_user , $dbi_password) 
      or die ("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
   mysql_select_db("plans") 
      or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");
   $result=mysql_query("select * from faculty where CodeOfFaculty=".$CodeOfFaculty,$Connection) 
      or die("Query failed:".mysql_errno().": ".mysql_error()."<BR>");

   if ($row = mysql_fetch_object($result)){
     $FName = $row->FacName;
     echo "<HTML><HEAD><TITLE>$FName</TITLE>\n";
   }else{
      Header ("Location: index.php");
      exit;
   }
?>

<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<link rel="stylesheet" href="../CSS/Plans.css" type="text/css"></HEAD>

<BODY topmargin="2" leftmargin="2" marginheight="2" marginwidth="2" >
<br><CENTER><FONT FACE="Arial"><FONT COLOR="#000033">
<?php
   echo "<FONT SIZE=+2>$FName</FONT></FONT></FONT></CENTER>\n";
?>

<br>

<table   class='ramka' border="0" cellpadding="0" cellspacing="0" width="97%" align="center" bgcolor="0040A0">
<tr><td cellpadding="0" cellspacing="0">
<TABLE class='table'  BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH="100%">
<TR VALIGN=CENTER>

<?php
   echo "<TD VALIGN=CENTER ALIGN=CENTER WIDTH=\"10%\">&nbsp;<B><FONT FACE=\"times new roman cyr\" COLOR=\"#000066\"><a href=\"".$self."&sort_key=1\">Напр. Спец. Специализ.</a></FONT></B>\n";
   echo "</TD>\n";
   echo "<TD VALIGN=CENTER ALIGN=CENTER WIDTH=\"40%\">&nbsp;<B><FONT FACE=\"times new roman cyr\" COLOR=\"#000066\"><a href=\"".$self."&sort_key=2\">Наименование направления/специализации</a></FONT></B>\n";
   echo "</TD>\n";
   echo "<TD VALIGN=CENTER ALIGN=CENTER WIDTH=\"5%\">&nbsp;<B><FONT FACE=\"times new roman cyr\" COLOR=\"#000066\"><a href=\"".$self."&sort_key=7\">Основа обучения</a></FONT></B>\n";
   echo "</TD>\n";
   echo "<TD VALIGN=CENTER ALIGN=CENTER WIDTH=\"10%\">&nbsp;<B><FONT FACE=\"times new roman cyr\" COLOR=\"#000066\"><a href=\"".$self."&sort_key=3\">Квали фикация</a></FONT></B>\n";
   echo "</TD>\n";
   echo "<TD VALIGN=CENTER ALIGN=CENTER WIDTH=\"5%\">&nbsp;<B><FONT FACE=\"times new roman cyr\" COLOR=\"#000066\"><a href=\"".$self."&sort_key=4\">Выпуск. кафедра</a></FONT></B>\n";
   echo "</TD>\n";
   echo "<TD VALIGN=CENTER ALIGN=CENTER WIDTH=\"5%\">&nbsp;<B><FONT FACE=\"times new roman cyr\" COLOR=\"#000066\"><a href=\"".$self."&sort_key=5\">Форма обучения</a></FONT></B>\n";
   echo "</TD>\n";
   echo "<TD VALIGN=CENTER ALIGN=CENTER WIDTH=\"5%\">&nbsp;<B><FONT FACE=\"times new roman cyr\" COLOR=\"#000066\"><a href=\"".$self."&sort_key=6\">Дата</a></FONT></B>\n";
   echo "</TD>\n";
   echo "<TD VALIGN=CENTER ALIGN=CENTER WIDTH=\"30%\"  COLSPAN=\"4\">&nbsp;<B><FONT FACE=\"times new roman cyr\" COLOR=\"#000066\">Форма плана</FONT></B>\n";
   echo "</TD></TR>\n";

   $Codes = array();
   $Degrees = array();
   $Names = array();
   $AllCodes = array();
   $Data = array();	
   $name_trans = "Фак_планы";
   $date_trans = date("Y-m-d h:i:s");   
   $time_b = getmicrotime();
   $sort = "";

   switch ($Sr){
	case 1: $sort = "order by MinCode";
		break;
	case 2: $sort = "order by spc_name, spz_name";
		break;
	case 3: $sort = "order by Degree, MinCode";
	        break;
	case 4: $sort = "order by depart, MinCode";
	        break;
	case 5: $sort = "order by TeachForm, MinCode";
		break;
	case 6: $sort = "order by FixDate, MinCode";
		break;
	case 7: $sort = "order by Fast, MinCode";
		break;
	default: 
   }	

     $q = "select (case when pl.CodeOfSpecial<>0 then spc.MinistryCode else ".
	"(case when pl.CodeOfDirect<>0 then dir.MinistryCode else ".
	"(case when pl.CodeOfSpecialization<>0 then spz.MinistryCode else '--' end) end) end) MinCode, ".
	"(case when pl.CodeOfSpecialization<>0 then deg_spz.DegreeName else ".
	"(case when pl.CodeOfSpecial<>0 then deg_spc.DegreeName else ".
	"(case when pl.CodeOfDirect<>0 then deg_dir.DegreeName else '--' end) end) end) Degree, ".
	"(case when pl.CodeOfDirect<>0 then dir.DirName else (case when pl.CodeOfSpecial<>0 then spc.SpcName else '--' end) end) spc_name, ".
	"(case when pl.CodeOfSpecialization<>0 then spz.SpzName else 0 end) spz_name, ".
	"(case when pl.CodeOfSpecialization<>0 then dep_spz.Reduction else ".
	"(case when pl.CodeOfSpecial<>0 then dep_spc.Reduction else ".
	"(case when pl.CodeOfDirect<>0 then dep_dir.Reduction else 0 end) end) end) depart, ".
	"CodeOfPlan, PlnName, Fast, FixDate, TeachForm, pl.CodeOfDirect, pl.CodeOfSpecial, pl.CodeOfSpecialization ".
	"from plans pl left join directions dir on dir.CodeOfDirect=pl.CodeOfDirect ".
	"left join specials spc on spc.CodeOfSpecial=pl.CodeOfSpecial ".
	"left join specializations spz on spz.CodeOfSpecialization=pl.CodeOfSpecialization " .
	"left join degrees deg_spz on spz.CodeOfDegree=deg_spz.CodeOfDegree ".
	"left join degrees deg_spc on spc.CodeOfDegree=deg_spc.CodeOfDegree ".
	"left join degrees deg_dir on dir.CodeOfDegree=deg_dir.CodeOfDegree ".
	"left join department dep_spz on spz.CodeOfDepart=dep_spz.CodeOfDepart ".
	"left join department dep_spc on spc.CodeOfDepart=dep_spc.CodeOfDepart ".
	"left join department dep_dir on dir.CodeOfDepart=dep_dir.CodeOfDepart ".
	"where (pl.FixDate is NULL or pl.FixDate='0000-00-00') and ".
	//"where (pl.DateArchive is NULL or pl.DateArchive='0000-00-00') and ".
	"(dir.CodeOfFaculty=$CodeOfFaculty or spc.CodeOfFaculty=$CodeOfFaculty or spz.CodeOfFaculty=$CodeOfFaculty) $sort";

   $result = mysql_query($q,$Connection) 
      or die("Query failed:".mysql_errno().": ".mysql_error()."<BR>");	

   while($row = mysql_fetch_object($result)){
      $k = "??";
      if (isset($row->CodeOfSpecialization) && ($row->CodeOfSpecialization<>0)){
         $k = "spz".$row->CodeOfSpecialization;
      }else{
         if (isset($row->CodeOfSpecial) && ($row->CodeOfSpecial<>0)){
            $k = "spc".$row->CodeOfSpecial;
         }else{
            if (isset($row->CodeOfDirect) && ($row->CodeOfDirect<>0)){
               $k = "dir".$row->CodeOfDirect;
            }
         }
      }

      $StrDate = $row->FixDate;
      $day = substr($StrDate,8,2);
      $month = substr($StrDate,5,2);
      $year = substr($StrDate,0,4);
      $MinName = (strcmp($row->spc_name,"0")!=0) ? $row->spc_name."<br>":"";
      $MinName .= (strcmp($row->spz_name,"0")!=0) ? " (".$row->spz_name.")<br>":"";

      echo "<TR><TD VALIGN=CENTER ALIGN=CENTER HEIGHT='50px'><B><font  face=\"courier\">".$row->MinCode."</FONT></B></TD>";
      echo "<TD VALIGN=CENTER ALIGN=LEFT>&nbsp;<font  face=\"courier\"><a href=\"".$SpcHref."&code=".$k."&plan=".$row->CodeOfPlan."\">".$MinName."</a></FONT>";
      echo "</TD>";
      echo "<TD VALIGN=CENTER ALIGN=CENTER><font  face=\"courier\">".$Osn[$row->Fast]."</FONT>";
      echo "</TD>";
      echo "<TD VALIGN=CENTER ALIGN=CENTER><font  face=\"courier\">".$row->Degree."</FONT>";
      echo "</TD>";
      echo "<TD VALIGN=CENTER ALIGN=CENTER>&nbsp;<font  face=\"courier\">".$row->depart."</FONT>";
      echo "</TD>";
      echo "<TD VALIGN=CENTER ALIGN=CENTER><font  face=\"courier\">".$TForms[$row->TeachForm]."</FONT>";
      echo "</TD>";
      echo "<TD VALIGN=CENTER ALIGN=CENTER><font  face=\"courier\">".$day."-".$month."-".$year."</FONT>";
      echo "</TD>";
      echo "<TD VALIGN=CENTER ALIGN=CENTER><font  face=\"courier\" size=\"-1\"><a href=\"".$PlanHref."&plan=".$row->CodeOfPlan."\">Tипо вой</a></font>";
      echo "</TD>";
      echo "<TD VALIGN=CENTER ALIGN=CENTER><font    face=\"courier\" size=\"-1\"><a href=\"".$FullPlanHref."&plan=".$row->CodeOfPlan."\">Деталь ный</a></font>";
      echo "</TD>";
      echo "<TD VALIGN=CENTER ALIGN=CENTER><font    face=\"courier\" size=\"-1\"><a href=\"".$SocrPlanHref."?plan=".$row->CodeOfPlan."\">Сокра щенный</a></font>";
      echo "</TD>";
      echo "<TD VALIGN=CENTER ALIGN=CENTER><font    face=\"courier\" size=\"-1\"><a href=\"".$HoursPlanHref."?plan=".$row->CodeOfPlan."\" >Объем занятий</a></font>";
      echo "</TD></TR>";
   }
    $time_e = getmicrotime();
    $time_all= $time_e-$time_b;
    $id_sess = session_id();
    mysql_query("Insert into logs (name_trans, id_sess, date_trans, time_trans) values ('$name_trans', '$id_sess', '$date_trans', '$time_all')")
               or die("Unable to execute query:".mysql_errno().": ".mysql_error());


   /* Закрытие соединения */
   mysql_close($Connection);
?>
</TABLE>
</TD></TR></TABLE>

<br>
<center><HR WIDTH="100%"></center>
</BODY>
</HTML>