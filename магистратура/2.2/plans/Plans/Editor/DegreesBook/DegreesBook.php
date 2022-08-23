<?php
   session_start();
   if (!($_SESSION["status"] == 0)){
      Header ("Location: ../Unreg.html");
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<HTML>
<HEAD>
<TITLE>Учебные планы</TITLE>
<META NAME=Author CONTENT="Карпова Анна">
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<link rel="stylesheet" href="../../../CSS/PlansEditor.css" type="text/css">
<SCRIPT language="JavaScript">
<!--
    function Validator(theForm) { return; }
    function Deleting(theForm) { return; }
    function FillArrChenge(theForm) { return; }
    function RefreshRecArr(theForm) { return; }
//-->
</SCRIPT>

<SCRIPT language="JavaScript">
<!--
        var ArrRecPtr='';
    
    function FillArrChenge(theForm) 
    { 
        var checkOK="0123456789";
        var checkStr = theForm.name;
        var flag=true;
        var RecPtr=0;
        i=checkStr.length-1;
        while (flag && i>=0)
        {
          ch = checkStr.charAt(i);
          j=0;
          while( (ch != checkOK.charAt(j)) && j<checkOK.length)
           {    j++;   }                
          if (j == checkOK.length)  flag = false;
          else 
          {     mnogitel=1;
            for (q=0;q<checkStr.length-1-i;q++)
            { 
             mnogitel*=10;  
            }
            RecPtr+=(ch*mnogitel);
          }
          i--;
        }
        if (ArrRecPtr!="") ArrRecPtr+=',';
                ArrRecPtr+=RecPtr;
        document.DegreeFrm.NumOfChangeRec.value=ArrRecPtr;
        return (true); 
        }

    function RefreshRecArr(theForm)
    {
     ArrRecPtr='';
     document.DegreeFrm.NumOfChangeRec.value=ArrRecPtr;
     return (true);
    }

    function Deleting(theForm)
    { if(confirm(" Отмеченные строки будут безвозвратно удалены из базы данных. Удалить их?"))
        {return ('DoDelDegree.php');
        }
      return('DegreesBook.php');
    }
function Validator(theForm) 
            { 
                var checkOK="0123456789";
                var checkStr = theForm.value;
                var allValid = true;
                for (i=0; i<checkStr.length; i++)
                {
                    ch = checkStr.charAt(i);
                    for(j=0;j<checkOK.length;j++)
                        if (ch == checkOK.charAt(j)) break;
                    if (j == checkOK.length)
                    {
                        allValid = false;
                        break;
                    }
                }
                    if (allValid == false)
                    {
                        alert("В данное поле разрешен ввод только цифр");
                        theForm.value=theForm.defaultValue;
                        theForm.focus();
                        return (false);
                    }
                                r=FillArrChenge(theForm);
                return (true); 
                        }
// -->
</SCRIPT>
</HEAD>
<BODY topmargin=1 leftmargin=5 marginheight="1" marginwidth="5">
<form name=DegreeFrm method=post action="">
<em class='h1'><center>Справочник наименований квалификации выпускника</center></em>
<table border='0' width='100%' cellpadding='0' cellspacing='2'>
<tr><td height='5' bgcolor="#92a2d9">
<img src="../img/line.gif" width=15 height=15 hspace=0 vspace=0 border='0' align='left'></td></tr>
</table><br>
<table  class='ramka' border="0" cellpadding="0" cellspacing="0"  align="center" WIDTH ='80%'>
<tr><td cellpadding="0" cellspacing="0">
<TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
<TR ALIGN='CENTER' VALIGN='MIDDLE'>
<TH WIDTH ='10%'>&nbsp;</TD>
<TH WIDTH ='30%'><strong>Название степени</strong></TH>
<TH WIDTH ='18%'><strong>Срок обучения<BR>(по умолчанию)</strong></TH>
<TH WIDTH ='18%'><strong>Начальный курс<BR>(по умолчанию)</strong></TH>
<TH WIDTH ='14%'><strong>Порядок</strong></TH>
</TR>
<?php
   include("../../cfg.php");
   $Connection = mysql_connect($data_source , $dbi_user , $dbi_password)
      or die("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
   mysql_select_db("plans") 
      or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");
   $result = mysql_query("select * from degrees order by Sequence, DegreeName", $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   while ($row = mysql_fetch_object($result)) {
      $CodeOfDegree = $row->CodeOfDegree;
      $DegreeName = $row->DegreeName;
      $Apprenticeship = $row->Apprenticeship;
      $FirstYear = $row->FirstYear;
      $Sequence = $row->Sequence;
      echo "<TR ALIGN='LEFT' VALIGN='MIDDLE'>";
      echo "<TD align='center'><INPUT TYPE='CHECKBOX' NAME='flag[".$CodeOfDegree."]' VALUE=\"$CodeOfDegree\"></TD>\n";
      echo "<TD align='center'><INPUT TYPE=TEXT NAME=\"DegreeName".$CodeOfDegree."\"   SIZE='30' MAXLENGTH=30 VALUE='".trim($DegreeName)."'";
      echo "          onChange=\"FillArrChenge(this)\"></INPUT></TD>\n";
      echo "<TD align='center'><INPUT TYPE=TEXT NAME=\"Apprenticeship".$CodeOfDegree."\"   SIZE='3' MAXLENGTH=3 VALUE='".$Apprenticeship."'";
      echo "          onChange=\"Validator(this)\"></INPUT></TD>\n";
      echo "<TD align='center'><INPUT TYPE=TEXT NAME=\"FirstYear".$CodeOfDegree."\"   SIZE='3' MAXLENGTH=3 VALUE='".$FirstYear."'";
      echo "          onChange=\"Validator(this)\"></INPUT></TD>\n";
      echo "<TD align='center'><INPUT TYPE=TEXT NAME=\"Sequence".$CodeOfDegree."\"   SIZE='3' MAXLENGTH=3 VALUE='".$Sequence."'";
      echo "          onChange=\"Validator(this)\"></INPUT></TD>\n";
      echo "</TR>";
   }
   /*  */
   mysql_free_result($result);
   /*  */
   mysql_close($Connection);
?>
</TABLE>
</td></tr></table><BR>
<TABLE BORDER=0 ALIGN=CENTER>
<TR>
<TD><CENTER><INPUT TYPE='SUBMIT' NAME='Edit' VALUE='Внести изменения'
        onClick="DegreeFrm.action='DoEditDegrees.php'"></INPUT></CENTER>
</TD>

<TD><CENTER><INPUT TYPE='RESET' NAME='reset' VALUE='Отменить изменения'
        onClick="RefreshRecArr(this)"></INPUT></CENTER>
</TD>
</TR>

<TR>
<TD><CENTER><input type=submit        VALUE='Добавить в справочник новую запись' 
        onClick="DegreeFrm.action='AddDegree.php'"></CENTER>
</TD>
<TD><CENTER><INPUT TYPE='SUBMIT' NAME='Delete' VALUE='Удалить помеченные записи' 
        onClick="DegreeFrm.action=Deleting(this)"></INPUT></CENTER>
</TD>
</TR>
</TABLE>
<CENTER>
<HR>
<input type='hidden' name='NumOfChangeRec' value=''>
</FORM>
</BODY>
</HTML>
