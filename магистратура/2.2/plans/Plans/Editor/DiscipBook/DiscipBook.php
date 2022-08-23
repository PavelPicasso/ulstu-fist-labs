<?php
   session_start();
   if (!(($_SESSION["status"] == 0)||($_SESSION["status"] == 2))){
      Header ("Location: ../Unreg.html");
      exit;
   }
   if (isset($_GET['Start'])) $Start = $_GET['Start'];
?>
<HTML>
<HEAD>
<TITLE>Справочник дисциплин</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<link rel="stylesheet" href="../../../CSS/PlansEditor.css" type="text/css"><SCRIPT language="JavaScript">
        <!--
            function Deleting() { return; }
            function FillArrChenge(theForm,RecPtr) { return; }
            function RefreshRecArr(theForm) { return; }
        //-->
    </SCRIPT>

<SCRIPT language="JavaScript">
<!--
        var ArrRecPtr='';
    
    function FillArrChenge(theForm,RecPtr) 
    { 
        if (ArrRecPtr!="") ArrRecPtr+=',';
                ArrRecPtr+=RecPtr;
        document.fed.NumOfChangeRec.value=ArrRecPtr;
        return (true); 
        }

    function RefreshRecArr(theForm)
    {
     ArrRecPtr='';
     document.fed.NumOfChangeRec.value=ArrRecPtr;
     return (true);
    }
    
    function Deleting()
    { if(confirm(" Отмеченные строки будут безвозвратно удалены из базы данных. Удалить их?"))
        {return ('DoDelDisInB.php');
        }
      return('DiscipBook.php');
    }
// -->
</script></HEAD>
<BODY topmargin="1" leftmargin="1" marginheight="1" marginwidth="1">
<form name=fed method=post action="">
<em class='h1'><center>Справочник дисциплин</center></em><table border='0' width='100%' cellpadding='0' cellspacing='2'><tr><td height='5' bgcolor="#92a2d9"><img src="../img/line.gif" width=15 height=15 hspace=0 vspace=0 border='0' align='left'></td></tr></table>
<br>
<?php
   include("../../cfg.php");
   include("DiscipBookFunc.php");
   $Connection = mysql_connect($data_source , $dbi_user , $dbi_password)
      or die("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
   mysql_select_db("plans") 
      or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");
   //Массив с названиями факультетов
   $LinkArray=array();
   $SymbArray = array('0','1','2','3','4','5','6','7','8','9',
		'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
		'А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Э','Ю','Я');
   $SymbArrayCount = array();
   $PrevSymbol = "";
   echo "<H2>";
   if (isset($Start)){ echo "$Start<BR>";}
   $result = mysql_query("select ltrim(DisName) as dn from disciplins order by dn", $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   while ($row = mysql_fetch_object($result)){
      $NewSymbol = ToUpper(substr ($row->dn,0,1));
      $key = array_search($NewSymbol,$SymbArray,true);
      if ($key!==false){
	$SymbArrayCount[$key] = 1;
      }		
   }
   ksort($SymbArrayCount);
   foreach($SymbArrayCount as $key=>$val){
         if (!isset($Start)){ 
            $Start = $SymbArray[$key];
            echo "$Start<BR>"; 
         }
         echo  "&nbsp;<a href=\"../DiscipBook/DiscipBook.php?Start=".$SymbArray[$key]."\" >".$SymbArray[$key]."</a>&nbsp;\n";
         $Link[] = $SymbArray[$key];
   }
   echo "</H2>";


   //Массив с сокр. названиями кафедрами
   $RedDepartArray=array();
   $CodeDepartArray=array();
   $result = mysql_query("select Reduction, CodeOfDepart from department order by Reduction", $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   while ($row = mysql_fetch_object($result)){
      $RedDepartArray[] = $row->Reduction;
      $CodeDepartArray[] = $row->CodeOfDepart;
   }
?>
<table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center">
<tr><td cellpadding="0" cellspacing="0">
<TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
<TR ALIGN='CENTER' VALIGN='MIDDLE'>
<TH>&nbsp;</TH>
<TH><strong>Стандарт</strong></TH>
<TH><strong>Название дисциплины</strong></TH>
<TH><strong>Сокращение</strong></TH>
<TH><strong>Кафедра</strong></TH>
<TH><strong>URL УМК</strong></TH>
</TR>
<?php
   $result = mysql_query("select * from disciplins order by DisName", $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   while ($row = mysql_fetch_object($result)){
      if (strcmp($Start, ToUpper(substr($row->DisName,0,1))) == 0){
        //вывод дисциплины
        $DisciplineCode = $row->CodeOfDiscipline;
        $Discipline = $row->DisName;
        $Reduct = $row->DisReduction;
        $Depart = $row->CodeOfDepart;
        $URL = $row->URL_UMK;
        $Standart = $row->NumbOfStandart;
        if (strcmp($Standart,"")==0) {$Standart="&nbsp;";} 
        echo "<TR>\n";
        echo "<TD align='center'><INPUT TYPE='CHECKBOX' NAME='del[$DisciplineCode]' VALUE=\"$DisciplineCode\"></TD>\n";
        echo "<TD align='center'><INPUT TYPE=TEXT NAME=\"Standart$DisciplineCode\"   SIZE=7 MAXLENGTH=7 VALUE='$Standart'\n";
        echo "      onChange=\"FillArrChenge(this,'$DisciplineCode')\">  </INPUT></TD>\n";
        echo "<TD align='center'><INPUT TYPE=TEXT NAME=\"Discipline$DisciplineCode\"   SIZE=40 MAXLENGTH=150 VALUE='$Discipline'\n";
        echo "      onChange=\"FillArrChenge(this,'$DisciplineCode')\">  </INPUT></TD>\n";
        echo "<TD align='center'><INPUT TYPE=TEXT NAME=\"Reduction$DisciplineCode\"   SIZE=10 MAXLENGTH=30 VALUE='$Reduct'\n";
        echo "      onChange=\"FillArrChenge(this,'$DisciplineCode')\">  </INPUT></TD>\n";
        //Выбор кафедры
        echo "<TD><SELECT NAME=\"Depart$DisciplineCode\" onChange=\"FillArrChenge(this,'$DisciplineCode')\">\n";
        reset($RedDepartArray);
        reset($CodeDepartArray);
        while (($Dep = each($RedDepartArray)) && ($CodeDep = each($CodeDepartArray))){
           if ($CodeDep[1] == $Depart){ echo "<OPTION SELECTED VALUE=".$CodeDep[1].">".$Dep[1]."\n";}
           else { echo "<OPTION VALUE=".$CodeDep[1].">".$Dep[1]."\n";}
        }
        echo "</SELECT></TD>\n";
        echo "<TD align='center'><INPUT TYPE=TEXT NAME=\"URL$DisciplineCode\"   SIZE=20 MAXLENGTH=100 VALUE='$URL'\n";
        echo "      onChange=\"FillArrChenge(this,'$DisciplineCode')\">  </INPUT></TD>\n";
        echo "</TR>\n";
      }
   }
   mysql_free_result($result);
   mysql_close($Connection);
?>
</TABLE>
</td></tr></table><BR>
<TABLE BORDER=0 ALIGN=CENTER>
<TR>
<?php
  echo "<INPUT TYPE='HIDDEN' NAME='Start' value=\"$Start\"></INPUT>"
?>
<TD><CENTER><INPUT TYPE='SUBMIT' NAME='Edit' VALUE='Внести изменения'
            onClick="fed.action='DoEditDisInB.php'"></INPUT></CENTER></TD>
<TD><CENTER><INPUT TYPE='RESET' NAME='reset' VALUE='Отменить изменения'
            onClick="RefreshRecArr(this)"></INPUT></CENTER></TD>
</TR>

<TR>
<TD><CENTER><input type=submit        VALUE='Добавить в справочник новую запись' 
            onClick="fed.action='NewDiscip.php'"> 
</CENTER></TD>
<TD><CENTER><INPUT TYPE='SUBMIT' NAME='Delete' VALUE='Удалить помеченные записи' 
            onClick="fed.action=Deleting()"></INPUT></CENTER></TD>
</TR>

</TABLE>


<HR>
</center>
<input type='hidden' name='NumOfChangeRec' value=''>
</body></html>