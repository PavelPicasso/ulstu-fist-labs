<?php
   session_start();
   if (!($_SESSION["status"] == 0)){
      Header ("Location: ../Unreg.html");
      exit;
   }
?>
<HTML>
<HEAD>
<TITLE>Справочник кафедр</TITLE>
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
            {return ('DoDelDepart.php');
                }
              return('DepartBook.php');
            }
        // -->
        </script></HEAD>



<BODY topmargin="1" leftmargin="1" marginheight="1" marginwidth="1">
<form name=fed method=post action="">
<em class='h1'><center>Справочник кафедр</center></em><table border='0' width='100%' cellpadding='0' cellspacing='2'><tr><td height='5' bgcolor="#92a2d9"><img src="../img/line.gif" width=15 height=15 hspace=0 vspace=0 border='0' align='left'></td></tr></table><br><table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center">
<tr><td cellpadding="0" cellspacing="0">
<TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
<TR ALIGN='CENTER' VALIGN='MIDDLE'>
<TH>&nbsp;</TD>
<TH><strong>Название кафедры</strong></TH>
<TH><strong>Сокр ащение</strong></TH>
<TH><strong>Факуль тет</strong></TH>
<TH><strong>Зав. кафедрой</strong></TH>
<TH><strong>Для подписи</strong></TH>
<TH><strong>URL</strong></TH>
<TH>&nbsp;</TH>
</TR>
<?php
   include("../../cfg.php");
   $Connection = mysql_connect($data_source , $dbi_user , $dbi_password)
      or die("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
   mysql_select_db("plans") 
      or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");
   //Массив с сокр. названиями и кодами факультетов
   $FacArray=array();
   $CodeFacArray=array();

   if ($_SESSION["statusCode"]==0){ $q="select Reduction, CodeOfFaculty from faculty";}
   else {$q="select Reduction, CodeOfFaculty from faculty where CodeOfFaculty=".$_SESSION["statusCode"];}

   $result = mysql_query($q, $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   $i=0;
   while ($row = mysql_fetch_object($result)){
      $FacArray[] = $row->Reduction;
      $CodeFacArray[] = $row->CodeOfFaculty;
   }

   if ($_SESSION["statusCode"]==0){ $q="select * from department order by DepName";}
   else {$q="select * from department where CodeOfFaculty=".$_SESSION["statusCode"]." order by DepName" ;}

   $result = mysql_query($q, $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   while ($row = mysql_fetch_object($result)){
      $DepartCode = $row->CodeOfDepart;
      $Depart = $row->DepName;
      $Reduction = $row->Reduction;
      $Adres = $row->Addres;
      $Faculty = $row->CodeOfFaculty;
      $ZavDep = $row->ZavDepart;
      $ZavPdp = $row->ZavSignature;
      $URL = $row->URL;
      echo "<TR>\n";
      echo "<TD align='center'><INPUT TYPE='CHECKBOX' NAME='del[$DepartCode]' VALUE=\"$DepartCode\"></TD>\n";
      echo "<TD align='center'><INPUT TYPE=TEXT NAME=\"Depart[$DepartCode]\"   SIZE='40' MAXLENGTH=50 VALUE='$Depart'\n";
      echo "              onChange=\"FillArrChenge(this,$DepartCode)\"></INPUT></TD>\n";
      echo "<TD align='center'><INPUT TYPE=TEXT NAME=\"Reduction[$DepartCode]\"   SIZE='7' MAXLENGTH=7 VALUE='$Reduction'\n";
      echo "              onChange=\"FillArrChenge(this,$DepartCode)\"></INPUT></TD>\n";
      echo "<td align='center'><SELECT NAME=\"FacCode[$DepartCode]\" onChange=\"FillArrChenge(this,$DepartCode)\">\n";
      reset($FacArray);
      reset($CodeFacArray);
      while (($Fc = each($FacArray)) && ($CodeFc = each($CodeFacArray))){
         if ($CodeFc[1] == $Faculty){ echo "<OPTION SELECTED VALUE=".$CodeFc[1].">".$Fc[1]."\n";}
         else { echo "<OPTION VALUE=".$CodeFc[1].">".$Fc[1]."\n";}
      }
      echo "</SELECT></TD>\n";
      echo "<TD align='center'><font size='-1'><INPUT TYPE=TEXT NAME=\"ZavDep[$DepartCode]\"   SIZE=25 MAXLENGTH=60 VALUE='$ZavDep'\n";
      echo "              onChange=\"FillArrChenge(this,$DepartCode)\">  </INPUT></font></TD>\n";
      echo "<TD align='center'><font size='-1'><INPUT TYPE=TEXT NAME=\"ZavPdp[$DepartCode]\"   SIZE=15 MAXLENGTH=60 VALUE='$ZavPdp'\n";
      echo "              onChange=\"FillArrChenge(this,$DepartCode)\">  </INPUT></font></TD>\n";
      echo "<TD align='center'><font size='-1'><INPUT TYPE=TEXT NAME=\"URL[$DepartCode]\"   SIZE=10 MAXLENGTH=100 VALUE='$URL'\n";
      echo "              onChange=\"FillArrChenge(this,$DepartCode)\">  </INPUT></font></TD>\n";
      echo "<TD align='center'>&nbsp;<a href='../Staff/DiscipVolume.php?depart=$DepartCode'>Расчет штатов</a>&nbsp;</TD>";
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
            onClick="fed.action='DoEditDepartInB.php'"></INPUT></CENTER></TD>
<TD><CENTER><INPUT TYPE='RESET' NAME='reset' VALUE='Отменить изменения'
            onClick="RefreshRecArr(this)"></INPUT></CENTER></TD>
</TR>

<TR>
<TD><CENTER><input type=submit           VALUE='Добавить в справочник новую запись' 
            onClick="fed.action='NewDepart.php'"> 
</CENTER></TD>
<TD><CENTER><INPUT TYPE='SUBMIT' NAME='Delete' VALUE='Удалить помеченные записи' 
            onClick="fed.action=Deleting(this)"></INPUT></CENTER></TD>
</TR>
</TABLE>



</center>
<input type='hidden' name='NumOfChangeRec' value=''></form>
<hr></body></html>