<?php
    session_start();
    if (!(($_SESSION["status"] == 0)||($_SESSION["status"] == 2))){
        Header ("Location: ../Unreg.html");
        exit;
    }
    include("../Display/DisplayFunc.php");
    include("../PlanCalculatFunc.php");
 
    CreateConnection();
    if (isset($_POST['Start'])) $Start = $_POST['Start'];

    if (!($_POST)){ 
        Header ("Location: ../DiscipBook/DiscipBook.php?Start=".$Start);
        exit;
    }
    if (isset($_POST['del'])) $del = $_POST['del'];
    if (isset($_POST['ToEdit'])) $ToEdit = $_POST['ToEdit'];
    $Ok = 1;//флаг того, что не было дисциплины, присутствующие в планах
    $DiscipNums = "";
    $DiscipToDel = array(); //список планов дисциплины в которых могут быть изменены
    $DiscipNotDel = array(); //список планов дисциплины в которых не могут быть изменены
    //Проход по списку измененных дисциплин
$DirectArray = array();
$SpecArray = array();
$SpzArray = array();
if (isset($del)){
   while (($numflag = each($del)) && is_numeric($numflag[1])){
      $result = mysql_query("
                  select distinct plans.CodeOfDirect, plans.CodeOfSpecial, plans.CodeOfSpecialization, plans.PlnName, plans.CodeOfPlan 
                  from plans, schedplan 
                  where plans.CodeOfPlan=schedplan.CodeOfPlan and 
                  schedplan.CodeOfDiscipline=".$numflag[1] 
                ,$Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
      if (($row = mysql_fetch_object($result))&&(!isset($ToEdit))){
         if ($Ok){        
            //Если дисциплина еще не встречалась 
            //Заполняем массивы с коддами направлений, специальностей, специализаций
         
            //Массив с кодами направлений 
            $MinResult = mysql_query("select MinistryCode, CodeOfDirect, CodeOfDepart from directions", $Connection)
               or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
            while ($row1 = mysql_fetch_object($MinResult)){
               $DirectArray[$row1->CodeOfDirect] = array( $row1->MinistryCode, $row1->CodeOfDepart);
            }
         
            //Массив с кодами специальностей
            $MinResult = mysql_query("select MinistryCode, CodeOfSpecial, CodeOfDepart from specials", $Connection)
               or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
            while ($row1 = mysql_fetch_object($MinResult)){
               $SpecArray[$row1->CodeOfSpecial] = array( $row1->MinistryCode, $row1->CodeOfDepart);
            }
         
            //Массив с кодами специализаций
            $MinResult = mysql_query("select MinistryCode, CodeOfSpecialization, CodeOfDepart from specializations", $Connection)
               or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
            while ($row1 = mysql_fetch_object($MinResult)){
               $SpzArray[$row1->CodeOfSpecialization] = array( $row1->MinistryCode, $row1->CodeOfDepart);
            }


            include("../Display/StartPage.php");
	    DisplayPageTitle("../down2.html","Нельзя отредактировать некоторые дисциплины");
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
         $PlansList[] = array($MinCode, $row->PlnName, $row->CodeOfPlan); //список планов в которых присутсвует дисциплина
         //если есть планы с такой дисциплиной
         $Ok = 0; //флаг того, что были дисциплины, присутствующие в планах
         $ToDel = 1; //флаг права пользователя изменять данную дисциплину
         if (($_SESSION["status"] == 2)&&($_SESSION["statusCode"] != $Depart)){
            $ToDel = 0;
         }

         //проходим по всем планам в которых присутствует дисциплина
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
               $ToDel = 0;
            }
         }
         $result = mysql_query("select DisName from disciplins where CodeOfDiscipline=".$numflag[1] 
                ,$Connection)
         or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
         $row =  mysql_fetch_object($result);
         if ($ToDel){//если дисциплина может быть изменена
            $DiscipToDel[]= array($row->DisName,$PlansList);
            if (strcmp($DiscipNums,"") == 0){ 
               $DiscipNums = $numflag[1];
               echo "<form name=fed method=post action=\"\">\n";
            }
            else{ $DiscipNums .= ",".$numflag[1];}
         }
         else{
            $DiscipNotDel[] = array($row->DisName, $PlansList);
         }
      }
      else {
         //иначе просто удаляем дисциплину
         mysql_query("delete from disciplins where CodeOfDiscipline=".$numflag[1], $Connection);
      }
   }
}
   if (isset($ToEdit)&&$_POST['NumOfDelRec']){
      $StrNum = $_POST['NumOfDelRec'];
      $Nums = split (',',$StrNum);
      while (($numflag = each($Nums)) && is_numeric($numflag[1])){

         mysql_query("delete from disciplins where CodeOfDiscipline=".$numflag[1], $Connection)
            or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
 
         $SchedPlans = FetchArrays("SELECT CodeOfSchPlan FROM schedplan where CodeOfDiscipline=".$numflag[1]);
         foreach ($SchedPlans as $k=>$v) { 
             mysql_query("DELETE FROM schplanitems WHERE CodeOfSchPlan='$v[CodeOfSchPlan]'")
                 or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
	 }

         mysql_query("delete from schedplan where CodeOfDiscipline=".$numflag[1], $Connection)
            or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
      }
   }
   if ($Ok){ Header ("Location: ../DiscipBook/DiscipBook.php?Start=".$_POST['Start']);}
   else {
     //Выводим свдения о планах в которых участвует дисциплина
     if (count($DiscipNotDel)>0){
        echo "<h2>Некоторые дисциплины не могут быть удалены, они содержаться в планах не доступных для редактирования:</h2>\n";
        reset($DiscipNotDel);
        while ($Dis = each($DiscipNotDel)){
           echo "<h4>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$Dis[1][0]."</h4>\n";
           while ($Pl = each ($Dis[1][1])){
              echo "<B>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$Pl[1][0]."</B> <a href=\"/cgi/planFull.pl?plan=".$Pl[1][2]."\" >".$Pl[1][1]. "</a><BR>";
           }
        }
     }
     if (count($DiscipToDel)>0){
        echo "<h2>Некоторые из удаляемых дисциплин присутствуют в учебных планах:</h2>\n";
        reset($DiscipToDel);
        while ($Dis = each($DiscipToDel)){
           echo "<h4>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$Dis[1][0]."</h4>\n";
           while ($Pl = each ($Dis[1][1])){
              echo "<B>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$Pl[1][0]."</B> <a href=\"/cgi/planFull.pl?plan=".$Pl[1][2]."\" >".$Pl[1][1]. "</a><BR>";
           }
        }
         echo "<INPUT TYPE='HIDDEN' NAME='Start' value=".$_POST['Start'].">\n";
         echo "<INPUT TYPE='HIDDEN' NAME='ToEdit' value=1>\n";
         echo "<input type='hidden' name='NumOfDelRec' value='".$DiscipNums."'>\n";
         echo "<BR>\n";
         echo "<TABLE BORDER=0 ALIGN=CENTER>\n";
         echo "<TR>\n";
         echo "<TD><INPUT TYPE='SUBMIT' NAME='Edit' VALUE='Удалить дисциплины' OnClick=\"fed.action='DoDelDisInB.php'\"></TD>\n";
         echo "<TD><INPUT TYPE='SUBMIT' NAME='Cans' VALUE='Отменить' OnClick=\"fed.action='DiscipBook.php'\"></TD>\n";
         echo "</TR>\n";
         echo "</TABLE>\n";
     }
     include("../Display/FinishPage.php");
   }
?>
