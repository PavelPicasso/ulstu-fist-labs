<?php
   session_start();
   if (!(($_SESSION["status"] == 0)||($_SESSION["status"] == 2))){
      Header ("Location: ../Unreg.html");
      exit;
   }
   if (isset($_GET['Start'])) $Start = $_GET['Start'];
   if (isset($_POST['Start'])) $Start = $_POST['Start'];
   if (isset($_POST['ToEdit'])) $ToEdit = $_POST['ToEdit'];
   include("../../cfg.php");
   $Connection = mysql_connect($data_source , $dbi_user , $dbi_password)
      or die("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
   mysql_select_db("plans") 
      or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");
   if (!($_POST['NumOfChangeRec'])){ 
       Header ("Location: ../DiscipBook/DiscipBook.php?Start=".$Start);
       exit;
   }

   $StrNum = $_POST['NumOfChangeRec'];
   $Nums = split (',',$StrNum);
   $Ok = 1;//���� ����, ��� �� ���� ����������, �������������� � ������
   $DiscipNums = "";
   $DiscipToChange = array(); //������ ������ ���������� � ������� ����� ���� ��������
   $DiscipNotChange = array(); //������ ������ ���������� � ������� �� ����� ���� ��������
   $DiscipDuble = array(); //������ ������ ���������� � ������� �� ����� ���� ��������

   $DirectArray = array();
   $SpecArray = array();
   $SpzArray = array();
   //������ �� ������ ���������� ���������
   while (($numflag = each($Nums)) && is_numeric($numflag[1])){
      $result = mysql_query("
                  select distinct plans.CodeOfDirect, plans.CodeOfSpecial, plans.CodeOfSpecialization, plans.PlnName, plans.CodeOfPlan 
                  from plans, schedplan
                  where plans.CodeOfPlan=schedplan.CodeOfPlan and
                  schedplan.CodeOfDiscipline=".$numflag[1] 
                ,$Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
      if (($row = mysql_fetch_object($result))&&(!isset($ToEdit))){
         if ($Ok){        
            //���� ���������� ��� �� ����������� 
            //��������� ������� � ������� �����������, ��������������, �������������
         
            //������ � ������ ����������� 
            $MinResult = mysql_query("select MinistryCode, CodeOfDirect, CodeOfDepart from directions", $Connection)
               or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
            while ($row1 = mysql_fetch_object($MinResult)){
               $DirectArray[$row1->CodeOfDirect] = array( $row1->MinistryCode, $row1->CodeOfDepart);
            }
         
            //������ � ������ ��������������
            $MinResult = mysql_query("select MinistryCode, CodeOfSpecial, CodeOfDepart from specials", $Connection)
               or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
            while ($row1 = mysql_fetch_object($MinResult)){
               $SpecArray[$row1->CodeOfSpecial] = array( $row1->MinistryCode, $row1->CodeOfDepart);
            }
         
            //������ � ������ �������������
            $MinResult = mysql_query("select MinistryCode, CodeOfSpecialization, CodeOfDepart from specializations", $Connection)
               or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
            while ($row1 = mysql_fetch_object($MinResult)){
               $SpzArray[$row1->CodeOfSpecialization] = array( $row1->MinistryCode, $row1->CodeOfDepart);
            }
            //�������� �������� �� ������ ������� ������
            echo "<HTML>\n";
            echo "<HEAD>\n";
            echo "<TITLE>�������������� ���������</TITLE>\n";
            echo "<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=windows-1251\">\n";
            echo "<link rel=stylesheet href=\"/css/PlansEditor.css\" type=text/css>\n";
            echo "</HEAD>\n";
            echo "<BODY topmargin=\"1\" leftmargin=\"1\" marginheight=\"1\" marginwidth=\"1\">\n";
            echo "<CENTER><em class='h1'>������ ��������������� ��������� ����������</center></em>\n";
            echo "<table border='0' width='100%' cellpadding='0' cellspacing='2'><tr><td height='5' bgcolor=\"#92a2d9\"><img src=\"../img/line.gif\" width=15 height=15 hspace=0 vspace=0 border='0' align='left'></td></tr></table>\n";
            echo "<br>\n";
         }
         $MinCode = "------------";
         $Depart = 0;
         if (isset($row->CodeOfSpecialization)){
            $MinCode = $SpzArray[$row->CodeOfSpecialization][0];
            $Depart = $SpzArray[$row->CodeOfSpecialization][1];
         }else{
            if (isset($row->CodeOfSpecial)){
               $MinCode = $SpecArray[$row->CodeOfSpecial][0];
               $Depart = $SpecArray[$row->CodeOfSpecial][1];
            }else{
               if (isset($row->CodeOfDirect)){
                  $MinCode = $DirectArray[$row->CodeOfDirect][0];
                  $Depart = $DirectArray[$row->CodeOfDirect][1];
               }
            }
         }
         $PlansList[] = array($MinCode, $row->PlnName, $row->CodeOfPlan); //������ ������ � ������� ����������� ����������
         //���� ���� ����� � ����� �����������
         $Ok = 0; //���� ����, ��� ���� ����������, �������������� � ������
         $ToChange = 1; //���� ����� ������������ �������� ������ ����������
         if (($_SESSION["status"] == 2)&&($_SESSION["statusCode"] != $Depart)){
            $ToChange = 0;
         }
         //�������� �� ���� ������ � ������� ������������ ����������
         while ($row = mysql_fetch_object($result)){
            $MinCode = "------------";
            $Depart = 0;
            if (isset($row->CodeOfSpecialization)){
               $MinCode = $SpzArray[$row->CodeOfSpecialization][0];
               $Depart = $SpzArray[$row->CodeOfSpecialization][1];
            }else{
               if (isset($row->CodeOfSpecial)){
                  $MinCode = $SpecArray[$row->CodeOfSpecial][0];
                  $Depart = $SpecArray[$row->CodeOfSpecial][1];
               }else{
                  if (isset($row->CodeOfDirect)){
                     $MinCode = $DirectArray[$row->CodeOfDirect][0];
                     $Depart = $DirectArray[$row->CodeOfDirect][1];
                  }
               }
            }
            $PlansList[] = array ($MinCode, $row->PlnName, $row->CodeOfPlan);;
            if (($_SESSION["status"] == 2)&&($_SESSION["statusCode"] != $Depart)){
               $ToChange = 0;
            }
         }
         $result = mysql_query("select DisName from disciplins where CodeOfDiscipline=".$numflag[1], $Connection)
             or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
         $row =  mysql_fetch_object($result);
         if ($ToChange){//���� ���������� ����� ���� ��������
            $DiscipToChange[]= array($row->DisName,$PlansList);
            $Depart="Depart".$numflag[1];
            $Discip="Discipline".$numflag[1];
            $Reduct = "Reduction".$numflag[1];
            $Standart = "Standart".$numflag[1];
            $URL = "URL".$numflag[1];
            if (strcmp($DiscipNums,"") == 0){ 
               $DiscipNums = $numflag[1];
               echo "<form name=fed method=post action=\"\">\n";
            }
            else{ $DiscipNums .= ",".$numflag[1];}
            //������� ��������� ���������� ��� ����������� ���������
            echo "<input type='hidden' name='".$Depart."' value='".$_POST[$Depart]."'>\n";
            echo "<input type='hidden' name='".$Discip."' value='".$_POST[$Discip]."'>\n";
            echo "<input type='hidden' name='".$Reduct."' value='".$_POST[$Reduct]."'>\n";
            echo "<input type='hidden' name='".$Standart."' value='".$_POST[$Standart]."'>\n";
            echo "<input type='hidden' name='".$URL."' value='".$_POST[$URL]."'>\n";
         }
         else{
            $DiscipNotChange[] = array($row->DisName, $PlansList);
         }
      }
      else {
         //����� ������ ������ ���������
         $Depart="Depart".$numflag[1];
         $parDepart=	$_POST[$Depart];
         $Discip="Discipline".$numflag[1];
         $parDiscip=	$_POST[$Discip];
         $Reduct="Reduction".$numflag[1];
         $parReduct=	$_POST[$Reduct];
         $Standart="Standart".$numflag[1];
         $parStandart=	$_POST[$Standart];
         $URL="URL".$numflag[1];
         $parURL=	$_POST[$URL];
         mysql_query("update disciplins set  DisName='".$parDiscip."', NumbOfStandart='".$parStandart."',  CodeOfDepart=".$parDepart.", URL_UMK='".$parURL."', DisReduction='".$parReduct."'  where CodeOfDiscipline=".$numflag[1], $Connection);
      }
   }
   if ($Ok){ Header ("Location: ../DiscipBook/DiscipBook.php?Start=".$_POST['Start']);}
   else {
     //������� ������� � ������ � ������� ��������� ����������
     if (count($DiscipNotChange)>0){
        echo "<h2>��������� ���������� �� ����� ���� ��������, ��� ����������� � ������ �� ��������� ��� ��������������:</h2>\n";
        reset($DiscipNotChange);
        while ($Dis = each($DiscipNotChange)){
           echo "<h4>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$Dis[1][0]."</h4>\n";
           while ($Pl = each ($Dis[1][1])){
              echo "<B>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$Pl[1][0]."</B> <a href=\"/cgi/planFull.pl?plan=".$Pl[1][2]."\" >".$Pl[1][1]. "</a><BR>";
           }
        }
     }
     if (count($DiscipToChange)>0){
        echo "<h2>��������� �� ���������� ��������� ������������ � ������� ������:</h2>\n";
        reset($DiscipToChange);
        while ($Dis = each($DiscipToChange)){
           echo "<h4>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$Dis[1][0]."</h4>\n";
           while ($Pl = each ($Dis[1][1])){
              echo "<B>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$Pl[1][0]."</B> <a href=\"/cgi/planFull.pl?plan=".$Pl[1][2]."\" >".$Pl[1][1]. "</a><BR>";
           }
        }
         echo "<INPUT TYPE='HIDDEN' NAME='Start' value=".$_POST['Start'].">\n";
         echo "<INPUT TYPE='HIDDEN' NAME='ToEdit' value=1>\n";
         echo "<input type='hidden' name='NumOfChangeRec' value='".$DiscipNums."'>\n";
         echo "<BR>\n";
         echo "<TABLE BORDER=0 ALIGN=CENTER>\n";
         echo "<TR>\n";
         echo "<TD><INPUT TYPE='SUBMIT' NAME='Edit' VALUE='������ ���������' OnClick=\"fed.action='DoEditDisInB.php'\"></TD>\n";
         echo "<TD><INPUT TYPE='SUBMIT' NAME='Cans' VALUE='��������' OnClick=\"fed.action='DiscipBook.php'\"></TD>\n";
         echo "</TR>\n";
         echo "</TABLE>\n";
         echo "<BR><HR><em class='h2'>&nbsp;&nbsp;���� �� �� ������ ������ ��������� ������������ ���������� �� ��� ��������� �����, �� ������� \"������\" � �������� ����� ������ � ����������� ���������</em>\n";
     }
     echo "</BODY></HTML>\n";
   }
?>
