<?php
   session_start();
   if (!($_SESSION["status"] == 0)){
      Header ("Location: ../Unreg.html");
   }
?>
<HTML>
<HEAD>
<TITLE>Справочник факультетов</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<link rel="stylesheet" href="../../../CSS/PlansEditor.css" type="text/css"><SCRIPT language="JavaScript">
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
                {return ('DoDelFac.php');
                }
              return('FacBook.php');
            }
        // -->
        </script></HEAD>



<BODY topmargin="1" leftmargin="1" marginheight="1" marginwidth="1">
<form name=fed method=post action="">
<em class='h1'><center>Справочник факультетов</center></em><table border='0' width='100%' cellpadding='0' cellspacing='2'><tr><td height='5' bgcolor="#92a2d9"><img src="../img/line.gif" width=15 height=15 hspace=0 vspace=0 border='0' align='left'></td></tr></table><br><table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center">
<tr><td cellpadding="0" cellspacing="0">
<TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
<TR ALIGN='CENTER' VALIGN='MIDDLE'>
<TH>&nbsp;</TD>
<TH><strong>Название факультета</strong></TH>
<TH><strong>Сокращение</strong></TH>
<TH><strong>Декан</strong></TH>
<TH><strong>Для подписи</strong></TH>
</TR>
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
      $Faculty=$row->FacName;
      $Dean=$row->Dean;
      $DeanPdp=$row->DeanSignature;
      echo "<TR>\n";
      echo "<TD align='center'><INPUT TYPE='CHECKBOX' NAME='del[$FacCode]' VALUE=\"$FacCode\"></TD>\n";
      echo "<TD align='center'><INPUT TYPE=TEXT NAME=\"FacName[$FacCode]\"   SIZE='50' MAXLENGTH=50 VALUE='$Faculty'\n";
      echo "              onChange=\"FillArrChange(this,'$FacCode')\"></INPUT></TD>\n";
      echo "<TD align='center'><INPUT TYPE=TEXT NAME=\"Reduction[$FacCode]\"   SIZE='7' MAXLENGTH=7 VALUE='$Reduction'\n";
      echo "              onChange=\"FillArrChange(this,'$FacCode')\"></INPUT></TD>\n";
      echo "<TD align='center'><font size='-1'><INPUT TYPE=TEXT NAME=\"Dean[$FacCode]\"   SIZE=25` MAXLENGTH=60 VALUE='$Dean'\n";
      echo "              onChange=\"FillArrChange(this,'$FacCode')\"></INPUT></TD>\n";
      echo "<TD align='center'><font size='-1'><INPUT TYPE=TEXT NAME=\"DeanPdp[$FacCode]\"   SIZE=25` MAXLENGTH=60 VALUE='$DeanPdp'\n";
      echo "              onChange=\"FillArrChange(this,'$FacCode')\"></INPUT></TD>\n";
      echo "</TR>\n";
   }
   mysql_free_result($result);
   mysql_close($Connection);
?>

</TABLE>


</td></tr></table><BR>
<TABLE BORDER=0 ALIGN=CENTER>
<TR>
<TD><CENTER><INPUT TYPE='SUBMIT' NAME='Edit' VALUE='Внести изменения'
            onClick="fed.action='DoEditFacInB.php'"></INPUT></CENTER></TD>
<TD><CENTER><INPUT TYPE='RESET' NAME='reset' VALUE='Отменить изменения'
            onClick="RefreshRecArr(this)"></INPUT></CENTER></TD>
</TR>

<TR>
<TD><CENTER><input type=submit        VALUE='Добавить в справочник новую запись' 
            onClick="fed.action='NewFac.php'"> 
</CENTER></TD>
<TD><CENTER><INPUT TYPE='SUBMIT' NAME='Delete' VALUE='Удалить помеченные записи' 
            onClick="fed.action=Deleting()"></INPUT></CENTER></TD>
</TR>

</TABLE>



</center>
<input type='hidden' name='NumOfChangeRec' value=''>
<hr /></body></html>