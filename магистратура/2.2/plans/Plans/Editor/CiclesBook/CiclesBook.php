<?php
   session_start();
   if (!(($_SESSION["status"] == 0)||($_SESSION["status"] == 2))){
      Header ("Location: ../Unreg.html");
   }
?>
<HTML>
<HEAD>
<TITLE>Справочник циклов</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<link rel="stylesheet" href="../../../CSS/PlansEditor.css" type="text/css">
<SCRIPT language="JavaScript">
        <!--
            function FillArrChange(theForm,RecPtr) { return; }
            function RefreshRecArr(theForm) { return; }
            function Deleting() { return; }
        //-->
    </SCRIPT>

<SCRIPT language="JavaScript">
        <!--
                        var ArrRecPtr='';
            
            function FillArrChange(theForm,RecPtr) 
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
                {return ('DoDelCicle.php');
                }
              return('CiclesBook.php');
            }
        // -->
        </script></HEAD>



<BODY topmargin="1" leftmargin="1" marginheight="1" marginwidth="1">
<form name=fed method=post action="">
<em class='h1'><center>Справочник циклов</center></em><table border='0' width='100%' cellpadding='0' cellspacing='2'><tr><td height='5' bgcolor="#92a2d9"><img src="../img/line.gif" width=15 height=15 hspace=0 vspace=0 border='0' align='left'></td></tr></table><br><table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center">
<tr><td cellpadding="0" cellspacing="0">
<TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
<TR ALIGN='CENTER' VALIGN='MIDDLE'>
<TH>&nbsp;</TD>
<TH><strong>Название цикла дисциплин</strong></TH>
<TH><strong>Сокращение</strong></TH>
</TR>

<?php
   include("../../cfg.php");
   $Connection = mysql_connect($data_source , $dbi_user , $dbi_password)
      or die("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
   mysql_select_db("plans") 
      or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");
   $result = mysql_query("select * from cicles order by CicName", $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   while ($row = mysql_fetch_object($result)) {
      $CicCode = $row->CodeOfCicle;
      $CicName = $row->CicName;
      $CicRed = $row->CicReduction;
      echo "<TR ALIGN='LEFT' VALIGN='MIDDLE'>";
      echo "<TD align='center'><INPUT TYPE='CHECKBOX' NAME='del[$CicCode]' VALUE=\"$CicCode\"></TD>\n";
      echo "<TD align='center'><INPUT TYPE=TEXT NAME=\"Cicle[$CicCode]\"   SIZE='50' MAXLENGTH=150 VALUE='".trim($CicName)."'";
      echo "          onChange=\"FillArrChange(this,'$CicCode')\"></INPUT></TD>\n";
      echo "<TD align='center'><INPUT TYPE=TEXT NAME=\"CicRed[$CicCode]\"   SIZE='7' MAXLENGTH=7 VALUE='".trim($CicRed)."'";
      echo "          onChange=\"FillArrChange(this,'$CicCode')\"></INPUT></TD>\n";
      echo "</TR>";
   }

   /*  */
   mysql_free_result($result);
   /*  */
   mysql_close($Connection);
//<tr><td align="center"><a href="../UnderCiclesBook/UnderCiclesBook.php" target="Body" onClick="parent.LeftDown.location='down4.html'"><img src="img/UnderCicles.gif" width=120 height=30 border=0 hspace="0" vspace="0" alt=""></a></td></tr>
?>

</TABLE>


</td></tr></table><BR>
<TABLE BORDER=0 ALIGN=CENTER>
<TR>
<TD><CENTER><INPUT TYPE='SUBMIT' NAME='Edit' VALUE='Внести изменения'
            onClick="fed.action='DoEditCicleInB.php'"></INPUT></CENTER></TD>
<TD><CENTER><INPUT TYPE='RESET' NAME='reset' VALUE='Отменить изменения'
            onClick="RefreshRecArr(this)"></INPUT></CENTER></TD>
</TR>

<TR>
<TD><CENTER><input type=submit        VALUE='Добавить в справочник новую запись' 
            onClick="fed.action='NewCicle.php'"> 
</CENTER></TD>
<TD><CENTER><INPUT TYPE='SUBMIT' NAME='Delete' VALUE='Удалить помеченные записи' 
            onClick="fed.action=Deleting()"></INPUT></CENTER></TD>
</TR>

</TABLE>



</center>
<input type='hidden' name='NumOfChangeRec' value=''></form>
<hr /></body></html>