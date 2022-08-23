<?php
   session_start();
   if (!(($_SESSION["status"] == 0)||($_SESSION["status"] == 2))){
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
		document.UndCFrm.NumOfChangeRec.value=ArrRecPtr;
		return (true); 
        }

	function RefreshRecArr(theForm)
	{
	 ArrRecPtr='';
	 document.UndCFrm.NumOfChangeRec.value=ArrRecPtr;
	 return (true);
	}

	function Deleting(theForm)
	{ if(confirm(" Отмеченные строки будут безвозвратно удалены из базы данных. Удалить их?"))
		{return ('DoDelUndCic.php');
		}
	  return('UnderCiclesBook.php');
	}
// -->
</SCRIPT>
</HEAD>
<BODY topmargin=1 leftmargin=5 marginheight="1" marginwidth="5">
<form name=UndCFrm method=post action="">
<em class='h1'><center>Справочник компонетов</center></em>
<table border='0' width='100%' cellpadding='0' cellspacing='2'>
<tr><td height='5' bgcolor="#92a2d9">
<img src="../img/line.gif" width=15 height=15 hspace=0 vspace=0 border='0' align='left'></td></tr>
</table><br>
<table  class='ramka' border="0" cellpadding="0" cellspacing="0"  align="center">
<tr><td cellpadding="0" cellspacing="0">
<TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
<TR ALIGN='CENTER' VALIGN='MIDDLE'>
<TH>&nbsp;</TD>
<TH><strong>Название компонента</strong></TH>
<TH><strong>Сокращение</strong></TH>
</TR>
<?php
   include("../../cfg.php");
   $Connection = mysql_connect($data_source , $dbi_user , $dbi_password)
      or die("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
   mysql_select_db("plans") 
      or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");
   $result = mysql_query("select * from undercicles order by CodeOfUnderCicle", $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   while ($row = mysql_fetch_object($result)) {
      $UndCicCode = $row->CodeOfUnderCicle;
      $UndCicName = $row->UndCicName;
      $UndCicRed = $row->UndCicReduction;
      echo "<TR ALIGN='LEFT' VALIGN='MIDDLE'>";
      echo "<TD align='center'><INPUT TYPE='CHECKBOX' NAME='flag[$UndCicCode]' VALUE=\"$UndCicCode\"></TD>\n";
      echo "<TD align='center'><INPUT TYPE=TEXT NAME=\"UndCicle$UndCicCode\"   SIZE='50' MAXLENGTH=100 VALUE='".trim($UndCicName)."'";
      echo "          onChange=\"FillArrChenge(this)\"></INPUT></TD>\n";
      echo "<TD align='center'><INPUT TYPE=TEXT NAME=\"UndCicRed$UndCicCode\"   SIZE='7' MAXLENGTH=7 VALUE='".trim($UndCicRed)."'";
      echo "          onChange=\"FillArrChenge(this)\"></INPUT></TD>\n";
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
		onClick="UndCFrm.action='DoEditUndCic.php'"></INPUT></CENTER>
</TD>

<TD><CENTER><INPUT TYPE='RESET' NAME='reset' VALUE='Отменить изменения'
		onClick="RefreshRecArr(this)"></INPUT></CENTER>
</TD>
</TR>

<TR>
<TD><CENTER><input type=submit 	      VALUE='Добавить в справочник новую запись' 
		onClick="UndCFrm.action='AddUndCic.php'"></CENTER>
</TD>
<TD><CENTER><INPUT TYPE='SUBMIT' NAME='Delete' VALUE='Удалить помеченные записи' 
		onClick="UndCFrm.action=Deleting(this)"></INPUT></CENTER>
</TD>
</TR>
</TABLE>
<CENTER>
<HR>
<input type='hidden' name='NumOfChangeRec' value=''>
</FORM>
</BODY>
</HTML>
