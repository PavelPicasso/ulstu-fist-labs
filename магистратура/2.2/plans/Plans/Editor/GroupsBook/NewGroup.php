<?php
   session_start();
   if (!(($_SESSION["status"] == 0)||($_SESSION["status"] == 1))){
      Header ("Location: ../Unreg.html");
   }
?>
<HTML>
<HEAD>
<TITLE>Ввод новой группы</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<link rel="stylesheet" href="../../../CSS/PlansEditor.css" type="text/css">
<SCRIPT language="JavaScript">
<!--
    function Validator(theForm) { return; }
//-->
</SCRIPT>

<SCRIPT language="JavaScript">
<!--
    var ArrRecPtr='';

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
    return (true); 
}
// -->
</script>
</HEAD>

<BODY topmargin="1" leftmargin="5" marginheight="1" marginwidth="5">
<em class='h1'><center>Ввод новой группы</center></em><table border='0' width='100%' cellpadding='0' cellspacing='2'><tr><td height='5' bgcolor="#92a2d9"><img src="../img/line.gif" width=15 height=15 hspace=0 vspace=0 border='0' align='left'></td></tr></table><H2>Заполните форму</H2><FORM METHOD='post' NAME='depform' ACTION='/cgi/Editor/GroupsBook/DoNewGroup.pl'>
<br><table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center">
<tr><td cellpadding="0" cellspacing="0">
<TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
<TR>
<TH><strong>Название потока</strong></TH>
<TH><strong>Курс</strong></TH>
<TH><strong>Кол-во групп</strong></TH>
<TH><strong>Кол-во студентов</strong></TH>
<TH><strong>Учебный план</strong></TH>
</TR>
<TR>
<TD align='center'><INPUT TYPE=TEXT NAME="Stream"   SIZE='30' MAXLENGTH=50></INPUT></TD>
<?php
   include("../PlanCalculatFunc.php");
   CreateConnection();

   echo "<TD><SELECT NAME=\"Kurs\">\n";
   for ($i=1; $i<=6; $i++){
      echo "<OPTION VALUE=$i>$i\n";
   }
   echo "</TD>\n";

   echo "<TD align='center'><INPUT TYPE=TEXT NAME=\"GrpCount\"   SIZE='1' MAXLENGTH=1 onChange=\"Validator(this)\"></INPUT></TD>\n";
   echo "<TD align='center'><INPUT TYPE=TEXT NAME=\"StdCount\"   SIZE='2' MAXLENGTH=2 onChange=\"Validator(this)\"></INPUT></TD>\n";

   echo "<TD>\n";
   echo "<SELECT NAME=\"plan\">\n";

   $q = array();
   if (($_SESSION["status"] == 0) && ($_SESSION["statusCode"]==0)){
      $q[] = "select CodeOfPlan, MinistryCode, PlnName from directions, plans where (plans.CodeOfDirect=directions.CodeOfDirect and (plans.CodeOfSpecialization is NULL) and (plans.CodeOfSpecial is NULL)) order by MinistryCode, PlnName";
      $q[] = "select CodeOfPlan, MinistryCode, PlnName from specials, plans where (plans.CodeOfSpecial=specials.CodeOfSpecial and plans.CodeOfSpecialization is NULL) order by MinistryCode, PlnName";
      $q[] = "select CodeOfPlan, MinistryCode, PlnName from specializations, plans where plans.CodeOfSpecialization=specializations.CodeOfSpecialization order by MinistryCode, PlnName";
   }
   else {
      $q[] = "select CodeOfPlan, MinistryCode, PlnName from directions, plans where (plans.CodeOfDirect=directions.CodeOfDirect and (plans.CodeOfSpecialization is NULL) and (plans.CodeOfSpecial is NULL)) and directions.CodeOfFaculty=".$_SESSION["statusCode"]." order by MinistryCode, PlnName";
      $q[] = "select CodeOfPlan, MinistryCode, PlnName from specials, plans where (plans.CodeOfSpecial=specials.CodeOfSpecial and (plans.CodeOfSpecialization is NULL)) and specials.CodeOfFaculty=".$_SESSION["statusCode"]." order by MinistryCode, PlnName";
      $q[] = "select CodeOfPlan, MinistryCode, PlnName from specializations, plans where plans.CodeOfSpecialization=specializations.CodeOfSpecialization and specializations.CodeOfFaculty=".$_SESSION["statusCode"]." order by MinistryCode, PlnName";
   }

   while ($qw = each ($q)){
      $result = mysql_query($qw[1], $Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
      $i=0;
      while ($row = mysql_fetch_object($result)){
         echo "<OPTION VALUE=".$row->CodeOfPlan.">".$row->MinistryCode."&nbsp;&nbsp;&nbsp;".$row->PlnName."\n";
      }
   }

   echo "</SELECT></TD>\n";
   echo "\n";
   echo "\n";
   echo "\n";

   mysql_close($Connection);
?>
</TR>
</TABLE>
</TD></TR></TABLE>
<BR>
<TABLE  align='center'><TR>
<?php
   if (!isset($shift)){$sh=0;}
   else {$sh = 1;}
   
   echo "<TD><INPUT TYPE='RADIO' NAME='shift' VALUE='0' ";
   if($sh==0){echo "CHECKED ";}
   echo ">Вернутся к справочнику групп &nbsp;&nbsp;</TD>\n";
   echo "<TD><INPUT TYPE='RADIO' NAME='shift' VALUE='1'";
   if($sh==1){echo "CHECKED ";}
   echo ">Ввести несколько новых групп</TD>\n";
?>
</TR>
</TABLE>
<BR>
<INPUT TYPE='HIDDEN' NAME='hist'></INPUT>
<INPUT TYPE='HIDDEN' NAME='plan'></INPUT>
<CENTER><INPUT TYPE='SUBMIT' NAME='OK' VALUE='Добавить группу в справочник'></INPUT></CENTER>
</FORM>
<HR>
</BODY>
</HTML>
