<?php
   if (!empty($_GET['plan'])) $plan = $_GET['plan'];
   if (!empty($_POST['plan'])) $plan = $_POST['plan'];
   if (!empty($_GET['new'])) $new = $_GET['new'];
   if (!empty($_POST['new']))  $new = $_POST['new'];
   if (!empty($_GET['NumOfChangeKurs'])) $NumOfChangeKurs = $_GET['NumOfChangeKurs'];
   if (!empty($_POST['NumbOfChangeKurs'])) $NumOfChangeKurs = $_POST['NumOfChangeKurs'];
   if (!empty($_GET['NumOfChangePer'])) $NumOfChangePer = $_GET['NumOfChangePer'];
   if (!empty($_POST['NumOfChangePer'])) $NumOfChangePer = $_POST['NumOfChangePer'];
   if (!empty($_GET['Spec'])) $Spec = $_GET['Spec'];
   if (!empty($_POST['Spec'])) $Spec = $_POST['Spec'];
   if (!empty($_GET['Spz'])) $Spz = $_GET['Spz'];
   if (!empty($_POST['Spz'])) $Spz = $_POST['Spz'];
   if (!empty($_GET['Dir'])) $Dir = $_GET['Dir'];
   if (!empty($_POST['Dir'])) $Dir = $_POST['Dir'];
   if (!empty($_GET['PlanName'])) $PlanName = $_GET['PlanName'];
   if (!empty($_POST['PlanName'])) $PlanName = $_POST['PlanName'];
   if (!empty($_GET['IsExists'])) $IsExists = $_GET['IsExists'];
   if (!empty($_GET['IsExists'])) $IsExists = $_POST['IsExists'];
   
   if (empty($plan) && (empty($new) || $new!=1)) {
       $message = "Plan is empty";
       include("../alert.php");
       exit;
   }
   include("../PlanCalculatFunc.php");
   include("../Display/DisplayFunc.php");

   $YearCount = 0;
   $FirstKurs = 0;

   if (empty($new))
       $new = 0;

   if (! empty($plan))
       $CodeOfPlan = intval($plan);
   elseif ($new != 1) {
       FuncRedirect("../title.php"); 
   }

   CreateConnection();

   if ($new == 2) { //������ ��������� � ��� ��������� ������ ��

       if (!empty($NumOfChangeKurs) && !empty($NumOfChangePer)) {
           $ChangedKurs = split(",",$NumOfChangeKurs); 
           $ChangedPeriods = split(",",$NumOfChangePer); 

           foreach ($ChangedKurs as $k => $v) {
               $UpdatePeriodStr = "";
               foreach ($ChangedPeriods as $kp => $vp) {
                   $PeriodParam="Period".$vp."_".$v;
                   if ($UpdatePeriodStr != "")
                       $UpdatePeriodStr .= ",";
                   $UpdatePeriodStr .= " Period".$vp."='".$$PeriodParam."' ";
                   $LenParam="LengthOfPeriod".$vp."_".$v;
                   $UpdatePeriodStr .= ", LengthOfPeriod".$vp."='".$$LenParam."' ";
               }
               mysql_query("update schedules set $UpdatePeriodStr where KursNumb='$v' and CodeOfPlan='$CodeOfPlan'")
                   or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
    
           }

       }
       $NewFirstKurs = intval($FirstSm);
       $NewYearCount = intval($yCnt);
       $NewLastKurs = $NewFirstKurs + $NewYearCount - 1;

       list ($FirstKurs, $LastKurs, $FirstTerm, $LastTerm) = GetPeriod($CodeOfPlan, "Y");
       $YearCount = $LastKurs - $FirstKurs + 1;

       if (($FirstKurs != $NewFirstKurs || $YearCount != $NewYearCount) && $NewYearCount <= 6) { 
           //���� ����� �������� �� ���������, �� ��������� ��� ������� ����� ��������

           //������� ������ ����� 
           for ($i=$FirstKurs; $i<=$LastKurs; $i++) {
               if (($i < $NewFirstKurs) || ($i > $NewLastKurs))
                   DelKurs($i, $CodeOfPlan);
           }

           //��������� ����� �����
           for ($i=$NewFirstKurs; $i<=$NewLastKurs; $i++) {
               if (($i < $FirstKurs) || ($i > $LastKurs))
                   mysql_query("INSERT INTO schedules (CodeOfPlan, KursNumb) VALUES ('$CodeOfPlan', '$i')")
                       or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
           }

           if ($YearCount != $NewYearCount)
               mysql_query("update plans set YearCount='$NewYearCount' where CodeOfPlan='$CodeOfPlan'")
                   or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");

       }


       FuncRedirect("schedules.php?plan=$plan"); 
   }

   if ($new == 1){ //���� ���� �������� ������ �����, ��������� �� ������������ ��������� ������
       $SpcConditions = "";
       $SpcConditions .= " and CodeOfSpecial='$Spec'";
       $SpcConditions .= " and CodeOfSpecialization='$Spz'";
       $SpcConditions .= " and CodeOfDirect='$Dir'";

       $IsExists = FetchResult ("SELECT * from plans where PlnName='$PlanName' $SpcConditions");
       if (! empty($IsExists)) {
           FuncAlert ("���� � ����� ��������� ��� ����������");
           exit;
       } else {
           $YearCount=$yCnt; //���������� ��� ��������
           $FirstKurs=$FirstSm; //����� ������� ��������
           $LastKurs = $FirstKurs + $YearCount - 1;
       }

   } else {
       list ($FirstKurs, $LastKurs, $FirstTerm, $LastTerm) = GetPeriod($CodeOfPlan, "Y");
       $YearCount = $LastKurs - $FirstKurs + 1;
   }

   if ($new != 1) { //���� ����������� ��� ��������� ����
      $PlanData = GetPlanInfo($CodeOfPlan);
      $PlTitle = $PlanData["PlanSpcCode"]."&nbsp;&nbsp;".$PlanData["PlanSpcName"]."<BR>".$PlanData["PlnName"];
   
      DisplayPageTitle("/$PlansDir/Plans/Editor/downEd.php?plan=$plan","������ �������� ��������",$PlTitle);
      echo "<form name=fed method=post action='schedules.php'>\n";
   }
   else {
      DisplayPageTitle("/$PlansDir/Plans/Editor/downNewP.html","�������� ������ �����","������ �������� ��������");
      echo "<form name=fed method=post action='AddPlan.php'>\n";
   }

   include("../Display/StartPage.php");
   include ("../Display/ShedValiditionForm.php");

   //����� �������������� ��������
   $PeriodKeys = array('-------','��','�','��','��','��','��','��','��','��','��','���','���','��','��', '��C');
   $NO = 16; //���������� ��������� ����� ��������

   echo "<em class='h2'>���� ��������:&nbsp;";
   echo "<INPUT TYPE=TEXT NAME='yCnt' SIZE='2' MAXLENGTH='10' VALUE='$YearCount' onChange='ValidCount(this)'>&nbsp;���.";
   echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;����� ���������� �����:&nbsp;";
   echo "<INPUT TYPE=TEXT NAME='FirstSm' SIZE=2 MAXLENGTH=10 VALUE='$FirstKurs' onChange='ValidFirst(this)'>";
   echo "&nbsp;</em><br>";

?>
<br><table  class='ramka' border="0" cellpadding="0" cellspacing="0" align="center">
<tr><td cellpadding="0" cellspacing="0">
<TABLE  class='table' BORDER=1 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
<TR ALIGN='CENTER' VALIGN='MIDDLE'>
<TH><strong>����</strong></TH>
<TH><strong>�������</strong></TH>
<?php
    for ($i=1; $i<=$NumPeriod; $i++)
        echo "<TH COLSPAN=2><strong>������ $i</strong></TH>\n";
echo "</TR>\n";
?>
<?php
    if ($new == 1) {
        $Periods = array();
        for ($i=$FirstKurs; $i<=$LastKurs; $i++) {
            $Periods[$i] = array("KursNumb"=>$i);
            for ($s=1;$s<=2;$s++) 
                for ($j=($s-1)*$NumPeriod+1; $j<=$NumPeriod*$s; $j++) {
                    $Periods[$i]["Period".$j] = "";
                    $Periods[$i]["LengthOfPeriod".$j] = 0;
                }
        }
    }
    else
        $Periods = FetchArrays("select * from schedules where CodeOfPlan='$CodeOfPlan' order by KursNumb");

    foreach ($Periods as $k=>$v) {
        echo "<TR>\n";
        echo "<TD align='center' ROWSPAN=2 valign='center'>$v[KursNumb]</TD>\n";
        for ($s=1;$s<=2;$s++) {
            if ($s > 1) 
                echo "<TR>\n";
            echo "<TD align='center'> ������� $s </TD>";
            for ($i=($s-1)*$NumPeriod+1; $i<=$NumPeriod*$s; $i++) {

                echo "<TD align='center'><SELECT NAME='Period".$i."_".$v["KursNumb"]."' onChange='FillArrChenge(this)'>\n";
                
                for ($k=0; $k<$NO; $k++){ 
                    echo "<OPTION "; 
                    if ($PeriodKeys[$k] == $v["Period".$i])
                        echo "SELECTED ";
                    echo "VALUE='".$PeriodKeys[$k]."' >".$PeriodKeys[$k]."</OPTION>\n";
                }
                echo "</SELECT></TD>\n";

                echo "<TD align='center'><INPUT TYPE=TEXT NAME='LengthOfPeriod".$i."_".$v["KursNumb"]."' SIZE='1' MAXLENGTH='2' VALUE='".$v["LengthOfPeriod".$i]."' onChange='Validator(this)'></TD>\n";
    
            }
            print    "</TR>";
        }
    }
?>
</TABLE>
</td></tr></table><br>
<TABLE BORDER=0 ALIGN=CENTER>
<TR>
<TD align="center"><?php
if ($new==1)
    echo "<INPUT TYPE='SUBMIT' NAME='Edit' VALUE='������� ����� ����'>";
else
    echo "<INPUT TYPE='SUBMIT' NAME='Edit' VALUE='������ ���������'  onClick=\"fed.action=Changing(this)\">";

?></TD>
<TD align="center"><INPUT TYPE='RESET' NAME='reset' VALUE='�������� ���������' onClick="RefreshRecArr(this)"></TD>
</TR>
</TABLE>

<input type='hidden' name='NumOfChangeKurs' value=''>
<input type='hidden' name='NumOfChangePer' value=''>
<?php
if ($new != 1) {
    echo "<input type='hidden' name='new' value=2>";
    echo "<input type='hidden' name='plan' value=$CodeOfPlan>";
} else {
    echo "<input type='hidden' name='action' value='NewPlan'>";
    echo "<input type='hidden' name='Dir' value='$Dir'>";
    echo "<input type='hidden' name='Spec' value='$Spec'>";
    echo "<input type='hidden' name='Spz' value='$Spz'>";
    echo "<input type='hidden' name='PlanName' value='$PlanName'>";
    echo "<input type='hidden' name='TchForm' value='$TchForm'>";
    echo "<input type='hidden' name='yCnt' value='$YearCount'>";
    echo "<input type='hidden' name='FirstSm' value='$FirstKurs'>";
    if (empty($day))
       $day="";
    echo "<input type='hidden' name='day' value='$day'>";
    if (empty($mon))
        $mon="";
    echo "<input type='hidden' name='mon' value='$mon'>";
    if (empty($year))
        $year="";
    echo "<input type='hidden' name='year' value='$year'>";
}
?>
</form><hr /><br><em class='h2'>&nbsp;&nbsp;�����������:</em><P>
<table border=0 cellpadding="0" cellspacing="2">
<tr><td>&nbsp;&nbsp;��  - ������������� ��������                    </td><td>&nbsp;&nbsp;��  - �����                                                        </td></tr> 
<tr><td>&nbsp;&nbsp;�   - ��������                                  </td><td>&nbsp;&nbsp;��  - ���������� ������������ �����������                          </td></tr> 
<tr><td>&nbsp;&nbsp;��  - ��������                                  </td><td>&nbsp;&nbsp;��  - ������-����������������� ��������                            </td></tr> 
<tr><td>&nbsp;&nbsp;��  - ������� ��������                          </td><td>&nbsp;&nbsp;��� - ���������� �� ���������+��������.��������                    </td></tr> 
<tr><td>&nbsp;&nbsp;��  - ���������������� ��������                 </td><td>&nbsp;&nbsp;��� - ���������� �� ������������ �����+������.��������             </td></tr> 
<tr><td>&nbsp;&nbsp;��  - ������������� ��������                    </td><td>&nbsp;&nbsp;��  - ������������� ��������, ���������� � �������������� ���������</td></tr> 
<tr><td>&nbsp;&nbsp;��  - �������� ����������                       </td><td>&nbsp;&nbsp;��� - ���������� �� �������������                                  </td></tr> 
<tr><td>&nbsp;&nbsp;��  - ��������� ������, ��������� ��������������</td><td>&nbsp;&nbsp;--  - �������������� ������                                        </td></tr> 
</table><BR>
</form>
<?php
   include("../Display/FinishPage.php");
   mysql_close($Connection);
?>