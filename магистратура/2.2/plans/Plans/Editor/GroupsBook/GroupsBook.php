<?php
   session_start();
   if (!(($_SESSION["status"] == 0)||($_SESSION["status"] == 1))){
      Header ("Location: ../Unreg.html");
   }
?>
<HTML>
<HEAD>
<TITLE>Справочник потоков</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<link rel="stylesheet" href="../../../CSS/PlansEditor.css" type="text/css"><SCRIPT language="JavaScript">
        <!--
            function Validator(theForm,RecPtr) { return; }
            function Deleting() { return; }
            function FillArrChenge(theForm,RecPtr) { return; }
            function RefreshRecArr() { return; }
        //-->
    </SCRIPT>

<SCRIPT language="JavaScript">
        <!--
                        var ArrRecPtr='';

function Validator(theForm,RecPtr) 
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
                    r=FillArrChenge(theForm,RecPtr);
    return (true); 
            }
                        
function FillArrChenge(theForm,RecPtr) 
{ 
    if (ArrRecPtr!="") ArrRecPtr+=',';
                    ArrRecPtr+=RecPtr;
    document.fed.NumOfChangeRec.value=ArrRecPtr;
    return (true); 
}

function RefreshRecArr()
{
 ArrRecPtr='';
 document.fed.NumOfChangeRec.value=ArrRecPtr;
 return (true);
}

function Deleting()
{ if(confirm(" Отмеченные строки будут безвозвратно удалены из базы данных. Удалить их?"))
    {return ('DoDelGroup.php');
    }
  return('GroupsBook.php');
}
// -->
</script></HEAD>
<BODY topmargin="1" leftmargin="1" marginheight="1" marginwidth="1">
<form name=fed method=post action="">
<em class='h1'><center>Справочник потоков</center></em>
<table border='0' width='100%' cellpadding='0' cellspacing='2'><tr>
<td height='5' bgcolor="#92a2d9"><img src="../img/line.gif" width=15 height=15 hspace=0 vspace=0 border='0' align='left'></td></tr></table>
<br>
<table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center" WIDTH='90%'>
<tr><td cellpadding="0" cellspacing="0">
<TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
<TR ALIGN='CENTER' VALIGN='MIDDLE'>
<TH>&nbsp;</TH>
<TH><strong>Название потока</strong></TH>
<TH WIDTH='5%'><strong>Курс</strong></TH>
<TH WIDTH='8%'><strong>Кол-во групп</strong></TH>
<TH WIDTH='5%'><strong>Кол-во студентов</strong></TH>
<TH><strong>Учебный план</strong></TH>
</TR>
<?php
   include("../PlanCalculatFunc.php");
   CreateConnection();

   //Массив с сокр. названиями и кодами планов
   $PlnArray = array();    //массив имен планов     
   $CodePlnArray = array();//массив индексов планов 
   $SpcArray = array();    //массив кодов планов    

   $q = array();
   if (($_SESSION["status"] == 0) && ($_SESSION["statusCode"]==0)){
      $q[] = "select CodeOfPlan, MinistryCode, PlnName from directions, plans where (plans.CodeOfDirect=directions.CodeOfDirect and (plans.CodeOfSpecialization ='') and (plans.CodeOfSpecial ='')) order by MinistryCode, PlnName";
      $q[] = "select CodeOfPlan, MinistryCode, PlnName from specials, plans where (plans.CodeOfSpecial=specials.CodeOfSpecial and plans.CodeOfSpecialization ='') order by MinistryCode, PlnName";
      $q[] = "select CodeOfPlan, MinistryCode, PlnName from specializations, plans where plans.CodeOfSpecialization=specializations.CodeOfSpecialization order by MinistryCode, PlnName";
   }
   else {
      $q[] = "select CodeOfPlan, MinistryCode, PlnName from directions, plans where (plans.CodeOfDirect=directions.CodeOfDirect and (plans.CodeOfSpecialization ='') and (plans.CodeOfSpecial ='')) and directions.CodeOfFaculty=".$_SESSION["statusCode"]." order by MinistryCode, PlnName";
      $q[] = "select CodeOfPlan, MinistryCode, PlnName from specials, plans where (plans.CodeOfSpecial=specials.CodeOfSpecial and (plans.CodeOfSpecialization ='')) and specials.CodeOfFaculty=".$_SESSION["statusCode"]." order by MinistryCode, PlnName";
      $q[] = "select CodeOfPlan, MinistryCode, PlnName from specializations, plans where plans.CodeOfSpecialization=specializations.CodeOfSpecialization and specializations.CodeOfFaculty=".$_SESSION["statusCode"]." order by MinistryCode, PlnName";
   }
   
   while ($qw = each ($q)){
      $result = mysql_query($qw[1], $Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
      $i=0;
      while ($row = mysql_fetch_object($result)){
         $PlnArray[] = $row->PlnName;
         $CodePlnArray[] = $row->CodeOfPlan;
         $SpcArray[] = $row->MinistryCode;
      }
   }

   if (($_SESSION["status"] == 0) && ($_SESSION["statusCode"]==0)){$q = "select CodeOfStream, StreamName, Kurs, StdCount, GroupCount, PlnName, YearCount,  streams.CodeOfPlan from streams, plans where streams.CodeOfPlan=plans.CodeOfPlan order by CodeOfDirect, CodeOfSpecial, CodeOfSpecialization, StreamName, Kurs";}
   else {$q = "select distinct CodeOfStream, StreamName, Kurs, StdCount, GroupCount, PlnName, YearCount, streams.CodeOfPlan from streams, plans, directions, specials, specializations where streams.CodeOfPlan=plans.CodeOfPlan and ((plans.CodeOfDirect=directions.CodeOfDirect and plans.CodeOfSpecial ='' and plans.CodeOfSpecialization ='' and directions.CodeOfFaculty=".$_SESSION["statusCode"].") or (plans.CodeOfSpecial=specials.CodeOfSpecial and plans.CodeOfSpecialization ='' and specials.CodeOfFaculty=".$_SESSION["statusCode"].") or (plans.CodeOfSpecialization=specializations.CodeOfSpecialization and specializations.CodeOfFaculty=".$_SESSION["statusCode"].")) order by plans.CodeOfDirect, plans.CodeOfSpecial, plans.CodeOfSpecialization, StreamName, Kurs";}
   $result = mysql_query($q, $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   while ($row = mysql_fetch_object($result)){
      $StreamCode = $row->CodeOfStream;
      $Group = $row->StreamName;
      $Kurs = $row->Kurs;
      $GrpCount = $row->GroupCount;
      $StdCount = $row->StdCount;
      $PlanCode = $row->CodeOfPlan;
      $KursNumb=$row->YearCount;

      $result2 = mysql_query("select * from schedules where CodeOfPlan=$PlanCode order by KursNumb", $Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
      $rowFK= mysql_fetch_object($result2);
      $FKurs=$rowFK->KursNumb;

      echo "<TR>\n";
      echo "<TD align='center'><INPUT TYPE='CHECKBOX' NAME='del[$StreamCode]' VALUE=\"$StreamCode\"></TD>\n";
      echo "<TD align='center'><INPUT TYPE=TEXT NAME=\"Stream[$StreamCode]\"   SIZE='30' MAXLENGTH=50 VALUE='".trim($Group)."'\n";
      echo "       onChange=\"FillArrChenge(this,[$StreamCode])\"></INPUT></TD>\n";
      echo "<TD align='center'><SELECT NAME=\"Kurs[$StreamCode]\" onChange=\"FillArrChenge(this,'$StreamCode')\">\n";
      for ($i=$FKurs; $i<=$KursNumb+$FKurs-1; $i++){
         echo   "<OPTION ";
         if ($i==$Kurs){ echo  "SELECTED";}
         echo  " VALUE=$i>$i\n";
      }
      echo "</SELECT></TD>\n";
      echo "<TD align='center'><INPUT TYPE=TEXT NAME=\"GrpCount[$StreamCode]\"   SIZE='1' MAXLENGTH=1 VALUE='$GrpCount'\n";
      echo "          onChange=\"Validator(this,'$StreamCode')\"></INPUT></TD>\n";
      echo "<TD align='center'><INPUT TYPE=TEXT NAME=\"StdCount[$StreamCode]\"   SIZE='2' MAXLENGTH=2 VALUE='$StdCount'\n";
      echo "          onChange=\"Validator(this,'$StreamCode')\"></INPUT></TD>\n";
      echo "<TD align='center'><SELECT NAME=\"plan[$StreamCode]\" onChange=\"FillArrChenge(this,'$StreamCode')\">\n";
      reset($PlnArray);
      reset($CodePlnArray);
      reset($SpcArray);
      while (($Pln = each($PlnArray)) && ($CodePln = each($CodePlnArray)) && ($Spc = each($SpcArray))){
          if ($PlanCode == $CodePln[1]){ echo "<OPTION SELECTED VALUE=".$CodePln[1].">".$Spc[1]."&nbsp;&nbsp;&nbsp;".$Pln[1];}
          else{ print  "<OPTION VALUE=".$CodePln[1].">".$Spc[1]."&nbsp;&nbsp;&nbsp;".$Pln[1];}
      }
      echo "</SELECT></TD>\n";
      echo "</TR>\n";
      mysql_free_result($result2);
   }
   mysql_free_result($result);
   mysql_close($Connection);
?>
</TABLE>


</td></tr></table><BR>
<TABLE BORDER=0 ALIGN=CENTER>
<TR>
<TD><CENTER><INPUT TYPE='SUBMIT' NAME='Edit' VALUE='Внести изменения'
            onClick="fed.action='DoEditGroupInB.php'"></INPUT></CENTER></TD>
<TD><CENTER><INPUT TYPE='RESET' NAME='reset' VALUE='Отменить изменения'
            onClick="RefreshRecArr()"></INPUT></CENTER></TD>
</TR>

<TR>



<TD><CENTER><input type=submit           VALUE='Добавить в справочник новую запись'
            onClick="fed.action='NewGroup.php'">
</CENTER></TD>

<TD><CENTER><INPUT TYPE='SUBMIT' NAME='Delete' VALUE='Удалить помеченные записи' 
            onClick="fed.action=Deleting()"></INPUT></CENTER></TD>
</TR>

</TABLE>



</center>
<input type='hidden' name='NumOfChangeRec' value=''>
<BR>
<hr></body></html>