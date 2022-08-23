<?php
    include("../PlanCalculatFunc.php");
    include("../Display/DisplayFunc.php");
    CreateConnection();
    include("../Display/StartPage.php");

    DisplayPageTitle("","������� �������� ����� � ������ RTF");
    if (!empty($_GET['plan'])) $plan=$_GET['plan'];
    if (!empty($_POST['plan'])) $plan=$_POST['plan'];
    if (!(isset($plan)))
        return;
    else
        $CodeOfPlan = intval($plan);

    $PlanData = GetPlanInfo($CodeOfPlan);

    //��� �����
    $filename="../../../Exported/".$PlanData["PlanSpcCode"];
//    $filename="/tmp/".$PlanData["PlanSpcCode"];
    $i=0;
    while ( file_exists ($filename."_".$i.".rtf")){
     $i++;
     }
    $PrintName = $PlanData["PlanSpcCode"]."_".$i.".rtf";
    $filename .= "_".$i.".rtf";
    /* ������������ resultset */
    //������� ����
    $fl = fopen ($filename,"w")
      or die("���������� ������� ����  $filename.<BR>");

    include ("ExportFunc.php");
 
    $headS = "RTFParts/HeaderSt.rtf";  //��� ����� � ���������� ����� ������� rtf
    $headE = "RTFParts/HeaderEnd.rtf";  //��� ����� � ���������� ����� ������� rtf
    $tbl1 = "RTFParts/tbl1Start.rtf"; //��� ����� � 1-�� ��������
    $tbl1end = "RTFParts/tbl1End.rtf"; //��� ����� � 1-�� ��������
    $tbl2 = "RTFParts/tbl2GUP.rtf"; //��� ����� �� 2-�� ��������
    $tbl2div = "RTFParts/tbl2div.rtf"; //��� ����� �� 2-�� ��������
    $tbl3 = "RTFParts/tbl3Start.rtf"; //��� ����� � 3-�� ��������
    $tbl3hd = "RTFParts/tbl3HeadDiv.rtf"; //��� ����� � 3-�� ��������
    $tbl3div = "RTFParts/tbl3div.rtf"; //��� ����� � 3-�� ��������
    $tbl3end = "RTFParts/tbl3end.rtf"; //��� ����� � 3-�� ��������
    $tbl5 = "RTFParts/tbl5start.rtf"; //��� ����� � 5-o� ��������
    $tbl5div="RTFParts/tbl5div.rtf"; //��� ����� � 5-o� ��������
 
    //��������� ����� ������� rtf
    FileCopy($fl,$headS);
    fwrite($fl,"$PlanData[PlanSpcCode] - $plan");
    FileCopy($fl,$headE);
 
    //�������-�����
    //--------------------------------------------------------------
    FileCopy($fl,$tbl1);
    $result = mysql_query("select ProRectorTWSignature from administration",$Connection)
       or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
    $row = mysql_fetch_object($result);
    fwrite($fl,"{\\f31\\fs20                      ".$row->ProRectorTWSignature." \\cell }");
    FileCopy($fl,$tbl1end);
    $endstr="\\pard \\ql \\li0\\ri0\\widctlpar\\aspalpha\\aspnum\\faauto\\adjustright\\rin0\\lin0\\itap0{\\par }";//������ ����������� �������
    //����������� ������������ ����� �������
    //--------------------------------------------------------------
    $KursCount = 0; //���������� ������ 
    $st = "���";
    if ($PlanData["YearCount"] < 4){$st = "����";}
    if ($PlanData["YearCount"] == 1){$st = "���";}
 
    if (isset ($PlanData["CodeOfDirect"])){
        fwrite($fl,"\\cell \\cell {\\f31\\fs20 ����������� ".$PlanData["DirCode"]."  - ".$PlanData["DirName"]."}\\cell\\row");
    }
    if (isset ($PlanData["CodeOfSpecial"])){
          fwrite($fl,"\\cell \\cell {\\f31\\fs20 ������������� ".$PlanData["SpcCode"]."  - ".$PlanData["SpcName"]."}\\cell\\row");
    }
    if (isset ($PlanData["CodeOfSpecialization"])){
          fwrite($fl,"\\cell \\cell {\\f31\\fs20 ������������� ".$PlanData["SpzCode"]."  - ".$PlanData["SpzName"]."}\\cell\\row");
    }
    fwrite($fl,"{\\f31\\fs20     _______________ 20__  �.}\\cell \\cell{\\f31\\fs20 ������������ ���������� - ");
    fwrite($fl,"$PlanData[DegreeName] }\\cell\\row ");
 
    fwrite($fl,"\\cell \\cell {\\f31\\fs20 ���� �������� - $PlanData[YearCount] $st   ");
    if (strcmp($PlanData["DegreeName"],"�������")==0){
       fwrite($fl," (����� 4-������ ����������)  ");
    }

    $spcF = "----";
    $TeachForms = array("classroom" =>"�����", "correspondence" => "�������", "night" => "��������", "distance" => "�������������", "external" => "���������");

    fwrite($fl,"����� �������� - ".$TeachForms[$PlanData["TeachForm"]]."}");
    fwrite($fl,"\\cell \\row ");
    fwrite($fl,$endstr);
 
    //������� - ������ �������� ��������
    //--------------------------------------------------------------
    FileCopy($fl,$tbl2);
    //������� ��� �������� ������� ������
    $T = array(0,0,0,0,0,0,0);
    $TSem = array(0,0,0,0,0,0,0,0,0,0,0,0,0);
    $Ek = array(0,0,0,0,0,0,0);
    $PTP = array(0,0,0,0,0,0,0);
    $Dp = array(0,0,0,0,0,0,0);
    $IA = array(0,0,0,0,0,0,0);
    $Sb = array(0,0,0,0,0,0,0);
    $K = array(0,0,0,0,0,0,0);
    $PP = array(0,0,0,0,0,0,0);
    $NIP = array(0,0,0,0,0,0,0);
    $MD = array(0,0,0,0,0,0,0);
    $GE = array(0,0,0,0,0,0,0);
    //����������� ������������ ����� �������
    //--------------------------------------------------------------
    list ($FirstKurs, $LastKurs, $FirstTerm, $LastTerm) = GetPeriod($CodeOfPlan, "Y");
    
    for($i=$FirstKurs; $i<=$LastKurs; $i++){
      OutputKursGUP($i);
    }
    //�������� �������
    fwrite($fl,$endstr);
    //������� - ������� ������ �� ������� �������
    //--------------------------------------------------------------
    FileCopy($fl,$tbl3);
 
    if ( strcmp($PlanData["DegreeName"],"�������")==0){
       fwrite($fl,"{\\f31\\fs20 ����\\cell ����. ����.\\cell".
       "�����. ������\\cell ���������. ��������\\cell".
       "������-����. ��������\\cell ������. ����������� �����������\\cell ".
       "����������� � ������ ������. �����������\\cell ��������\\cell �����\\cell }");
       FileCopy($fl,$tbl3hd);
       //������ �� ���� ���������
       $All=0;
       for($i=$FirstKurs; $i<=$LastKurs; $i++){
          $Summ = $T[$i] + $Ek[$i] + $NIP[$i] + $MD[$i] + $K[$i] + $IA[$i];
          fwrite($fl,"\n{\\f31\\fs20 ".$i."\\cell }".
          "{\\fs20 ".$T[$i]." \\cell ".$Ek[$i]." \\cell ".$PP[$i]."' \\cell ".$NIP[$i]." \\cell ".$MD[$i]." \\cell ".$GE[$i]."' \\cell ".$K[$i]." \\cell ".$Summ." \\cell }\n");
          FileCopy($fl,$tbl3div);
         $All += $Summ;
       }
       fwrite($fl,"\n{\\f31\\fs20 ".$i."\\cell }".
      "{\\fs20 ".$T[0]." \\cell ".$Ek[0]." \\cell ".$PP[0]."' \\cell ".$NIP[0]." \\cell ".$MD[0]." \\cell ".$GE[0]."' \\cell ".$K[0]." \\cell ".$All." \\cell }\n");
    }
    else{
      fwrite($fl,"{\\f31\\fs20 ����\\cell ������������� ��������\\cell".
      "��������������� ������\\cell �������.-����.  ��������\\cell".
      "������������� �������� \\cell �������� ����������\\cell ".
      "�����\\cell ��������\\cell �����\\cell }");
       FileCopy($fl,$tbl3hd);
       //������ �� ���� ���������
       $All=0;
       for($i = $FirstKurs; $i<=$LastKurs; $i++){
          $Summ = $T[$i] + $Ek[$i] + $PTP[$i] + $Dp[$i]+ $IA[$i]+ $Sb[$i] + $K[$i];
          fwrite($fl,"\n{\\f31\\fs20 ".$i."\\cell }".
          "{\\fs20 ".$T[$i]." \\cell ".$Ek[$i]." \\cell ".$PTP[$i]." \\cell ".$Dp[$i]." \\cell ".$IA[$i]." \\cell ".$Sb[$i]." \\cell ".$K[$i]." \\cell ".$Summ." \\cell }\n");
          FileCopy($fl,$tbl3div);
          $All += $Summ;
       }
       fwrite($fl,"\n{\\f31\\fs20 ����� \\cell }".
       "{\\fs20 ".$T[0]." \\cell ".$Ek[0]." \\cell ".$PTP[0]." \\cell ".$Dp[0]." \\cell ".$IA[0]." \\cell ".$Sb[0]." \\cell ".$K[0]." \\cell ".$All." \\cell }\n");
    }
    //�������� �������
    FileCopy($fl,$tbl3end);
 
    //!!!!!!������� ������� �� ��������� ��������
    //fwrite ($fl, " \\par \\page \\par ");
 
    //������� - ����� ������� �����
    $result = mysql_query("select SUM(RGR), SUM(Synopsis) from schplanitems, schedplan where schplanitems.CodeOfSchPlan=schedplan.CodeOfSchPlan and schedplan.CodeOfPlan=".$plan,$Connection)
       or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
    $res = mysql_fetch_array($result);
    $ShowRGR = 0;
    if(($res[0]!=0)||($res[1]!=0)){ $ShowRGR = 1;}
    //--------------------------------------------------------------
    $yCnt = $LastKurs - $FirstKurs + 1;
    if ($IF){
       if($ShowRGR){
          $tblStart = "RTFParts/".$yCnt."/RASPRGRForm/tblOZ".$yCnt."StartRASPRGR.rtf";
          $tblHead = "RTFParts/".$yCnt."/RASPRGRForm/tblOZ".$yCnt."HeadRASPRGR.rtf";
          $tblHeadp2 = "RTFParts/".$yCnt."/RASPRGRForm/tblOZ".$yCnt."Headp2RASPRGR.rtf";
          $tblHeadp3 = "RTFParts/".$yCnt."/RASPRGRForm/tblOZ".$yCnt."Headp3RASPRGR.rtf";
          $tblDiv = "RTFParts/".$yCnt."/RASPRGRForm/tblOZ".$yCnt."DivRASPRGR.rtf";
          $tblDiv1 = "RTFParts/".$yCnt."/RASPRGRForm/tblOZ".$yCnt."Div1RASPRGR.rtf";
       }
       else{
          $tblStart = "RTFParts/".$yCnt."/RASPForm/tblOZ".$yCnt."StartRASP.rtf";
          $tblHead = "RTFParts/".$yCnt."/RASPForm/tblOZ".$yCnt."HeadRASP.rtf";
          $tblHeadp2 = "RTFParts/".$yCnt."/RASPForm/tblOZ".$yCnt."Headp2RASP.rtf";
          $tblHeadp3 = "RTFParts/".$yCnt."/RASPForm/tblOZ".$yCnt."Headp3RASP.rtf";
          $tblDiv = "RTFParts/".$yCnt."/RASPForm/tblOZ".$yCnt."DivRASP.rtf";
          $tblDiv1 = "RTFParts/".$yCnt."/RASPForm/tblOZ".$yCnt."Div1RASP.rtf";
       }
    }
    else{
       if($ShowRGR){
          $tblStart = "RTFParts/".$yCnt."/RGRForm/tblOZ".$yCnt."StartRGR.rtf";
          $tblHead = "RTFParts/".$yCnt."/RGRForm/tblOZ".$yCnt."HeadRGR.rtf";
          $tblHeadp2 = "RTFParts/".$yCnt."/RGRForm/tblOZ".$yCnt."Headp2RGR.rtf";
          $tblHeadp3 = "RTFParts/".$yCnt."/RGRForm/tblOZ".$yCnt."Headp3RGR.rtf";
          $tblDiv = "RTFParts/".$yCnt."/RGRForm/tblOZ".$yCnt."DivRGR.rtf";
          $tblDiv1 = "RTFParts/".$yCnt."/RGRForm/tblOZ".$yCnt."Div1RGR.rtf";
       }
       else{
          $tblStart = "RTFParts/".$yCnt."/Form/tblOZ".$yCnt."Start.rtf";
          $tblHead = "RTFParts/".$yCnt."/Form/tblOZ".$yCnt."Head.rtf";
          $tblHeadp2 = "RTFParts/".$yCnt."/Form/tblOZ".$yCnt."Headp2.rtf";
          $tblHeadp3 = "RTFParts/".$yCnt."/Form/tblOZ".$yCnt."Headp3.rtf";
          $tblDiv = "RTFParts/".$yCnt."/Form/tblOZ".$yCnt."Div.rtf";
          $tblDiv1 = "RTFParts/".$yCnt."/Form/tblOZ".$yCnt."Div1.rtf";
       }
    }
    //����� ������� ����� �������
    OutpPlan($FirstTerm, $LastTerm, $tblStart, $tblHead, $tblHeadp2, $tblHeadp3, $tblDiv, $tblDiv1);
    //������� - ����������
    //--------------------------------------------------------------
    FileCopy($fl,$tbl5);
///������� ���. ��������
    fwrite($fl,"{\\fs20 \\cell }{\\f31\\fs20 ���. �������� ".$PlanData["DepReduction"]. ": \\cell ".$PlanData["ZavSignature"]." \\cell }".
    "{\\f31\\fs20 \\cell �����������: \\cell }");
    $SoglRed = "";
    $SoglPdp = "";
    $resSogl = mysql_query("select distinct faculty.Reduction, faculty.DeanSignature from schedplan,department,faculty where CodeOfPlan=".$plan." and department.CodeOfDepart=schedplan.CodeOfDepart and faculty.CodeOfFaculty=department.CodeOfFaculty order by Reduction",$Connection)
       or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
    while ((strcmp($SoglRed,"")==0)&&($rowSogl = mysql_fetch_object($resSogl))){
      if(strcmp ( $rowSogl->Reduction , $PlanData["FacReduction"]) != 0 ){
         $SoglRed = "����� ".$rowSogl->Reduction;
         $SoglPdp = $rowSogl->DeanSignature;
      }
    }
    fwrite($fl,"{\\f31\\fs20 ".$SoglRed."   ".$SoglPdp." \\cell \\cell }");
    FileCopy($fl,$tbl5div);
///������� ������
    fwrite($fl,"{\\fs20 \\cell }{\\f31\\fs20 ����� ".$PlanData["FacReduction"]. ": \\cell ".$PlanData["DeanSignature"]." \\cell }".
    "{\\f31\\fs20 \\cell  \\cell }");
    $SoglRed = "";
    $SoglPdp = "";
    while ((strcmp($SoglRed,"")==0)&&($rowSogl = mysql_fetch_object($resSogl))){
      if(strcmp ( $rowSogl->Reduction , $PlanData["FacReduction"]) != 0 ){
         $SoglRed = "����� ".$rowSogl->Reduction;
         $SoglPdp = $rowSogl->DeanSignature;
      }
    }
    fwrite($fl,"{\\f31\\fs20 ".$SoglRed."    ".$SoglPdp." \\cell \\cell }");
    FileCopy($fl,$tbl5div);
    //������� ���. ��. �����
    $result = mysql_query("select HeadSignature from administration",$Connection)
       or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
    $resUch = mysql_fetch_row($result);
    fwrite($fl,"{\\fs20 \\cell }{\\f31\\fs20 ��������� ������� �����: \\cell ".$resUch[0]." \\cell }".
    "{\\f31\\fs20 \\cell \\cell }");
    $SoglRed = "";
    $SoglPdp = "";
    while ((strcmp($SoglRed,"")==0)&&($rowSogl = mysql_fetch_object($resSogl))){
      if(strcmp ( $rowSogl->Reduction , $PlanData["FacReduction"]) != 0 ){
         $SoglRed = "����� ".$rowSogl->Reduction;
         $SoglPdp = $rowSogl->DeanSignature;
      }
    }
    fwrite($fl,"{\\f31\\fs20 ".$SoglRed."    ".$SoglPdp." \\cell \\cell }");
    FileCopy($fl,$tbl5div);
    //�������� ���������� ��� ������������
    while ($rowSogl = mysql_fetch_object($resSogl)){
      $SoglRed = "";
      $SoglPdp = "";
      if(strcmp ( $rowSogl->Reduction , $PlanData["FacReduction"]) != 0 ){
         $SoglRed = "����� ".$rowSogl->Reduction;
         $SoglPdp = $rowSogl->DeanSignature;
         fwrite($fl,"{\\fs20 \\cell }{\\f31\\fs20  \\cell  \\cell }".
         "{\\f31\\fs20 \\cell \\cell }");
         fwrite($fl,"{\\f31\\fs20 ".$SoglRed."    ".$SoglPdp." \\cell \\cell }");
         FileCopy($fl,$tbl5div);
      }
    }
    //�������� �������
    fwrite($fl,$endstr);
 
    //�������� �����
    fwrite($fl,"}");
    fclose($fl);
 
 
    echo "<BR><H2>������� �������� � ";
    echo "<BR><A HREF=\"$filename\">$PrintName</A></H2>";
 
    include("../Display/FinishPage.php");
    mysql_close($Connection);
?>