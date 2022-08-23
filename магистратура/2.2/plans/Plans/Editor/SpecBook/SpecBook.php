<?php
   session_start();
   if (!(($_SESSION["status"] == 0)||($_SESSION["status"] == 2))){
      Header ("Location: ../Unreg.html");
      exit;
   }
?>
<HTML>
<HEAD>
<TITLE>Справочник специальностей</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<link rel="stylesheet" href="../../../CSS/PlansEditor.css" type="text/css"><SCRIPT language="JavaScript">
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
                document.fed.NumOfChangeRec.value=ArrRecPtr;
                return (true); 
                        }

            function RefreshRecArr(theForm)
            {
             ArrRecPtr='';
             document.fed.NumOfChangeRec.value=ArrRecPtr;
             return (true);
            }

            function Deleting(theForm)
            { if(confirm(" Отмеченные строки будут безвозвратно удалены из базы данных. Удалить их?"))
                {return ('DoDelSpec.php');
                }
              return('SpecBook.php');
            }
        // -->
        </script></HEAD>



<BODY topmargin="1" leftmargin="1" marginheight="1" marginwidth="1">
<form name=fed method=post action="">
<em class='h1'><center>Справочник специальностей</center></em><table border='0' width='100%' cellpadding='0' cellspacing='2'><tr><td height='5' bgcolor="#92a2d9"><img src="../img/line.gif" width=15 height=15 hspace=0 vspace=0 border='0' align='left'></td></tr></table>
<br>
<table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center">
<tr><td cellpadding="0" cellspacing="0">
<TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
<TR ALIGN='CENTER' VALIGN='MIDDLE'>
<TH>&nbsp;</TD>
<TH><strong>Направление</strong></TH>
<TH><strong>Мин. код</strong></TH>
<TH><strong>Название специальности</strong></TH>
<TH><strong>Факультет</strong></TH>
<TH><strong>Кафедра</strong></TH>
<TH><strong>Тип</strong></TH>
</TR>
<?php
   include("../../cfg.php");
   $Connection = mysql_connect($data_source , $dbi_user , $dbi_password)
      or die("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
   mysql_select_db("plans") 
      or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");
   //Массив с кодами направлений
   $result = mysql_query("select MinistryCode, CodeOfDirect from directions order by CodeOfFaculty, CodeOfDepart", $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   $i=0;
   $DirectArray[] = "Без направления";
   $CodeDirectArray[] = 0;
   while ($row = mysql_fetch_object($result)){
      $DirectArray[] = $row->MinistryCode;
      $CodeDirectArray[] = $row->CodeOfDirect;
   }
   //Массив с сокр. названиями и кодами степеней
   $TypeArray=array();
   $CodeTypeArray=array();
   $result = mysql_query("select DegreeName, CodeOfDegree from degrees order by Sequence, DegreeName", $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   $i=0;
   while ($row = mysql_fetch_object($result)){
      $TypeArray[] = $row->DegreeName;
      $CodeTypeArray[] = $row->CodeOfDegree;
   }
   //Массив с сокр. названиями и кодами факультетов
   $FacArray=array();
   $CodeFacArray=array();
   if ($_SESSION["statusCode"] == 0){ $q = "select Reduction, CodeOfFaculty from faculty";}
   else{ $q = "select Reduction, CodeOfFaculty from faculty where CodeOfFaculty=".$_SESSION["statusCode"];}
   $result = mysql_query($q, $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   $i=0;
   while ($row = mysql_fetch_object($result)){
      $FacArray[] = $row->Reduction;
      $CodeFacArray[] = $row->CodeOfFaculty;
   }

   //Массив с сокр. названиями кафедрами
   $RedDepartArray=array();
   $CodeDepartArray=array();
   $result = mysql_query("select Reduction, CodeOfDepart from department order by Reduction", $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   while ($row = mysql_fetch_object($result)){
      $RedDepartArray[] = $row->Reduction;
      $CodeDepartArray[] = $row->CodeOfDepart;
   }

   if ($_SESSION["statusCode"] == 0){ $q = "select * from specials order by CodeOfDirect, CodeOfFaculty, CodeOfDepart, SpcName";}
   else{ $q = "select * from specials where CodeOfFaculty=".$_SESSION["statusCode"]." order by CodeOfDirect, CodeOfFaculty, CodeOfDepart, SpcName";}

   $result = mysql_query($q, $Connection)
      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   while ($row = mysql_fetch_object($result)){
      $SpcCode=$row->CodeOfSpecial;
      $MinCode=$row->MinistryCode;
      $Spc=$row->SpcName;
      $Depart=$row->CodeOfDepart;
      $Faculty=$row->CodeOfFaculty;
      $Type=$row->CodeOfDegree;
      $Direction = $row->CodeOfDirect;
      echo "<TR>\n";
      echo "<TD align='center'><INPUT TYPE='CHECKBOX' NAME='flag[$SpcCode]' VALUE=\"$SpcCode\"></TD>\n";
      echo "<TD align='center'><SELECT NAME=\"Dir$SpcCode\" onChange=\"FillArrChenge(this)\">\n";
      reset($DirectArray);
      reset($CodeDirectArray);
      while (($Dir = each($DirectArray)) && ($CodeDir = each($CodeDirectArray))){
         if ($CodeDir[1] == $Direction){ echo "<OPTION SELECTED VALUE=".$CodeDir[1].">".$Dir[1]."\n";}
         else { echo "<OPTION VALUE=".$CodeDir[1].">".$Dir[1];}
      }
      echo "<TD align='center'><INPUT TYPE=TEXT NAME=\"MinCode$SpcCode\"   SIZE='6' MAXLENGTH=6 VALUE='".trim($MinCode)."'\n";
      echo "              onChange=\"Validator(this)\"></INPUT></TD>\n";
      echo "<TD align='center'><INPUT TYPE=TEXT NAME=\"Spc$SpcCode\"   SIZE='35' MAXLENGTH=150 VALUE='".trim($Spc)."'\n";
      echo "              onChange=\"FillArrChenge(this)\"></INPUT></TD>\n";
      echo "<TD align='center'><SELECT NAME=\"Fac$SpcCode\" onChange=\"FillArrChenge(this)\">\n";
      reset($FacArray);
      reset($CodeFacArray);
      while (($Fc = each($FacArray)) && ($CodeFc = each($CodeFacArray))){
         if ($CodeFc[1] == $Faculty){ echo "<OPTION SELECTED VALUE=".$CodeFc[1].">".$Fc[1]."\n";}
         else { echo "<OPTION VALUE=".$CodeFc[1].">".$Fc[1];}
      }
      echo "</SELECT></TD>\n";
      //Выбор кафедры
      echo "<TD><SELECT NAME=\"Depart$SpcCode\" onChange=\"FillArrChenge(this)\">\n";
      reset($RedDepartArray);
      reset($CodeDepartArray);
      while (($Dep = each($RedDepartArray)) && ($CodeDep = each($CodeDepartArray))){
         if ($CodeDep[1] == $Depart){ echo "<OPTION SELECTED VALUE=".$CodeDep[1].">".$Dep[1]."\n";}
         else { echo "<OPTION VALUE=".$CodeDep[1].">".$Dep[1];}
      }
      echo "</SELECT></TD>\n";
      echo "<TD align='center'><SELECT NAME=\"Type$SpcCode\" onChange=\"FillArrChenge(this)\">\n";
      reset($TypeArray);
      reset($CodeTypeArray);
      while (($Tp =  each($TypeArray))&&($CodeTp =  each($CodeTypeArray))){
         if ( $Type == $CodeTp[1]){ echo "<OPTION SELECTED VALUE='".$CodeTp[1]."'>".$Tp[1]."\n";}
         else { echo "<OPTION VALUE='".$CodeTp[1]."'>".$Tp[1];}
      }
      echo "</SELECT></TD>\n";
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
            onClick="fed.action='DoEditSpecInB.php'"></INPUT></CENTER></TD>
<TD><CENTER><INPUT TYPE='RESET' NAME='reset' VALUE='Отменить изменения'
            onClick="RefreshRecArr(this)"></INPUT></CENTER></TD>
</TR>

<TR>
<TD><CENTER><input type=submit           VALUE='Добавить в справочник новую запись' 
            onClick="fed.action='NewSpec.php'"> 
</CENTER></TD>
<TD><CENTER><INPUT TYPE='SUBMIT' NAME='Delete' VALUE='Удалить помеченные записи' 
            onClick="fed.action=Deleting(this)"></INPUT></CENTER></TD>
</TR>

</TABLE>

</center>
<input type='hidden' name='NumOfChangeRec' value=''>
</body>
</html>