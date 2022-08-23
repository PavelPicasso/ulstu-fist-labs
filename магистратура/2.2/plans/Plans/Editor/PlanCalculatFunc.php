<?php
$discip_types = array ("lb", "pr", "lc");

/*Этот файл содержит 
функции обеспечивающие подсчет величин по учебным планам
используемые во мноогих скриптах
*/
//------------------------------------------------------
//
//Возвращает массив с дисципли
//
function PlanDisciplines($plan, $cicle) {
    $Disciplines = FetchArrays("select schedplan.*, disciplins.DisName from schedplan, disciplins where schedplan.CodeOfPlan=$plan and schedplan.CodeOfCicle=$cicle and disciplins.CodeOfDiscipline=schedplan.CodeOfDiscipline order by schedplan.CodeOfUndCicle, schedplan.UndCicCode, disciplins.DisName");
    foreach ($Disciplines as $k => $v){
         $Disciplines[$k]["DiscipItems"] = FetchArrays("select * from schplanitems where CodeOfSchPlan=$v[CodeOfSchPlan] order by NumbOfSemestr");
    }
    return $Disciplines;
}

function FetchResult ($q) {

    global $Connection;

    $result = mysql_query($q,$Connection)
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
    if ($row = mysql_fetch_row($result))
        return ($row[0]);
    else
        return "";
}

function FetchFirstRow ($q) {
    global $Connection;

    $result = mysql_query($q,$Connection)
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
    if ($row = mysql_fetch_assoc($result))
        return ($row);
    else
        return "";
}

function FetchArrays($q) {
    global $Connection;

    $resArray = array();

    $result = mysql_query($q,$Connection)
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");

    while ($row = mysql_fetch_assoc($result)) {
          $resArray[] = $row; 
    }
    
    mysql_free_result($result);
    return $resArray; 
      
}

function FetchQuery($q) {
    global $Connection;
    mysql_query($q,$Connection)
        or die("Unable to execute query:".mysql_errno().": ".mysql_error());
}

function CreateConnection(){
    global $NumPeriod;
    global $PlansDir;
    global $db_name, $data_source, $dbi_user, $dbi_password;
    if (empty($NumPeriod))
         include("../../cfg.php");
    global $Connection;
    $Connection = mysql_connect($data_source , $dbi_user , $dbi_password)
        or die("Can't connect to $data_source:".mysql_errno().": ".mysql_error()."<BR>");
    mysql_select_db($db_name) 
        or die("Could not select database:".mysql_errno().": ".mysql_error()."<BR>");
}
/*
Определяет массив с различными видами обучения повсему плану
Параметры: код плана,
*/
function TWeekKinds($PCode) {
    global $Connection;
    
    $TKinds = array();
    list ($FirstKurs, $LastKurs, $FirstTerm, $LastTerm) = GetPeriod($PCode, "Y");
    $YearCount = $LastKurs-$FirstKurs+1;
    $TKinds["all"] = $YearCount * 52;
    $TKinds["to"] = 0;
    $TKinds["ex"] = 0;
    $TKinds["ia"] = 0;
    $TKinds["hl"] = 0;
    $TKinds["dp"] = 0;
    $TKinds["up"] = 0;
    $TKinds["ptp"] = 0;
    $TKinds["pp"] = 0;
    for ($i=$FirstTerm; $i<=$LastTerm; $i++) {
        $TKindTerm = TeachKindsTerm($PCode, $i);
        $TKinds["to"] += $TKindTerm["to"];
        $TKinds["ex"] += $TKindTerm["ex"];
        $TKinds["ia"] += $TKindTerm["ia"];
        $TKinds["hl"] += $TKindTerm["hl"];
        $TKinds["dp"] += $TKindTerm["dp"];
        $TKinds["up"] += $TKindTerm["up"];
        $TKinds["ptp"] += $TKindTerm["ptp"];
        $TKinds["pp"] += $TKindTerm["pp"];
    }

    return $TKinds;
}


/*
Определяет массив с различными видами обучения за семестр
Параметры: код плана, номер семестра
*/
function TeachKindsTerm($PCode, $Semestr) {

    global $Connection, $NumPeriod;

    $Kurs = ceil($Semestr/2);

    $resultGUP = mysql_query("select Period1, LengthOfPeriod1,
        Period2, LengthOfPeriod2, 
        Period3, LengthOfPeriod3, 
        Period4, LengthOfPeriod4, 
        Period5, LengthOfPeriod5, 
        Period6, LengthOfPeriod6, 
        Period7, LengthOfPeriod7, 
        Period8, LengthOfPeriod8, 
        Period9, LengthOfPeriod9, 
        Period10, LengthOfPeriod10, 
        Period11, LengthOfPeriod11, 
        Period12, LengthOfPeriod12, 
        Period13, LengthOfPeriod13, 
        Period14, LengthOfPeriod14, 
        Period15, LengthOfPeriod15, 
        Period16, LengthOfPeriod16  
        from schedules where CodeOfPlan=$PCode and KursNumb=$Kurs", $Connection)
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
    $WeekCount = array();
    $WeekCount["to"] = 0;
    $WeekCount["ex"] = 0;
    $WeekCount["ia"] = 0;
    $WeekCount["hl"] = 0;
    $WeekCount["dp"] = 0;
    $WeekCount["up"] = 0;
    $WeekCount["ptp"] = 0;
    $WeekCount["pp"] = 0;
    $Sm1 = $Semestr - $Kurs*2 + 1;
    if ($resGUP=mysql_fetch_row($resultGUP)) 
        for ($p=$NumPeriod*$Sm1; $p<$NumPeriod*($Sm1+1); $p++) {
          if (($resGUP[$p*2]=="ТО")||($resGUP[$p*2]=="ТГФ")||($resGUP[2*$p]=="ТП"))
                  $WeekCount["to"]+=$resGUP[$p*2+1];

          if (($resGUP[$p*2]=="Эк"))
                  $WeekCount["ex"]+=$resGUP[$p*2+1];

          if ($resGUP[$p*2]=="ГЭC")
                  $WeekCount["ia"]+=$resGUP[$p*2+1];

          if ($resGUP[$p*2]=="К")
                  $WeekCount["hl"]+=$resGUP[$p*2+1];

          if ($resGUP[$p*2]=="ВР")
                  $WeekCount["dp"]+=$resGUP[$p*2+1];

          if ($resGUP[$p*2]=="УП")
                  $WeekCount["up"]+=$resGUP[$p*2+1];

          if ($resGUP[$p*2]=="ПП")
                  $WeekCount["pp"]+=$resGUP[$p*2+1];

          if ($resGUP[$p*2]=="ДП")
                  $WeekCount["pp"]+=$resGUP[$p*2+1];
        }
    mysql_free_result($resultGUP);
    return $WeekCount;
}


/*
Опредляет число учебных недель в семестре
Параметры: код плана, номер семестра
*/
function TeachWeek($PCode, $Semestr, $Hl=0) {
    global $Connection, $NumPeriod;

    $Kurs = ceil($Semestr/2);
    $resultGUP = mysql_query("select Period1, LengthOfPeriod1,
        Period2, LengthOfPeriod2, 
        Period3, LengthOfPeriod3, 
        Period4, LengthOfPeriod4, 
        Period5, LengthOfPeriod5, 
        Period6, LengthOfPeriod6, 
        Period7, LengthOfPeriod7, 
        Period8, LengthOfPeriod8, 
        Period9, LengthOfPeriod9, 
        Period10, LengthOfPeriod10, 
        Period11, LengthOfPeriod11, 
        Period12, LengthOfPeriod12, 
        Period13, LengthOfPeriod13, 
        Period14, LengthOfPeriod14, 
        Period15, LengthOfPeriod15, 
        Period16, LengthOfPeriod16  
        from schedules where CodeOfPlan='$PCode' and KursNumb='$Kurs'", $Connection)
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
   	
    $WeekCount = 0;
    $Sm1 = $Semestr - $Kurs*2 + 1;
    if ($resGUP=mysql_fetch_row($resultGUP)){ 
        for ($p=$NumPeriod*$Sm1; $p<$NumPeriod*($Sm1+1); $p++) {
            if ($Hl) {
                if ($resGUP[$p*2]=="К")
                      $WeekCount+=$resGUP[$p*2+1];
            }
            else {
                if (($resGUP[$p*2]=="ТО")||($resGUP[$p*2]=="ТГФ")||($resGUP[2*$p]=="ТП"))
                  //Учитывается только  теоретическое обучение
                      $WeekCount+=$resGUP[$p*2+1];
            }
        }
    }	
    mysql_free_result($resultGUP);
    return $WeekCount;
}

//Подсчет объема занятий в плане $PCode
function TotalHours($PCode){

    global $Connection;

    $Summ = 0;
    //Подсчет суммы занятий с учетом дисциплин по выбору
    $result = mysql_query("select schedplan.CodeOfDiscipline, 
                 schedplan.AllH, schedplan.UndCicCode, cicles.CodeOfCicle, 
                 undercicles.CodeOfUnderCicle from schedplan, undercicles, cicles 
                 where schedplan.CodeOfUndCicle=undercicles.CodeOfUnderCicle  
                 and schedplan.CodeOfCicle=cicles.CodeOfCicle 
                 and CodeOfPlan=$PCode order by cicles.CodeOfCicle, 
                 schedplan.UndCicCode", $Connection)
        or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");

    $DisCodes = array();    //Массив для уже встречавшихся кодов дисциплин повыбору
    while ($row = mysql_fetch_object($result)){
        //Если это дисциплина по выбору то
        //проверяем подсчитывали ли уже дисциплину из этой группы
        if((strlen($row->UndCicCode)>2) && ($row->CodeOfUnderCicle==4)){
            $NewDC=substr($row->UndCicCode,0,2);
            $ToCount = 1;
            while ($DCode = each($DisCodes)){
                //если подсчитывали то заносим соответствующее значение
                if ((strcmp($NewDC, $DCode[1][0]) == 0)&&
                  ($row->CodeOfCicle == $DCode[1][1])){ $ToCount = 0;}
            }
            //Если таких дисциплин еще не встречалось то 
            //сохраняем данные о ней и суммируем
            if ($ToCount){
                $DisCodes[] = array($NewDC, $row->CodeOfCicle); 
                $Summ += $row->AllH;
            }
        }
        //Иначе сразу суммируем 
        else { $Summ += $row->AllH;}
    }
    mysql_free_result($result);
    return $Summ;
}

/*
Подсчет объема аудиторных занятий в плане $PCode
*/
function CalcAud($CodeOfPlan){

    global $Connection;

    $Summ = 0;
    //Подсчет суммы занятий с учетом дисциплин по выбору

    list ($FirstTerm, $LastTerm) = GetPeriod($CodeOfPlan);
    for ($i=$FirstTerm; $i<=$LastTerm; $i++){

        $Weeks=TeachWeek($CodeOfPlan, $i); 
        $result = mysql_query("
            select * from schedplan as s, undercicles as u, schplanitems as si where 
            s.CodeOfUndCicle=u.CodeOfUnderCicle 
            and s.CodeOfPlan=$CodeOfPlan 
            and si.CodeOfSchPlan = s.CodeOfSchPlan 
            and si.NumbOfSemestr=$i 
            ", $Connection)
            or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
        $DisCodes = array();    //Массив для уже встречавшихся кодов дисциплин повыбору
        while ($row = mysql_fetch_object($result)){
            if((strlen($row->UndCicCode)>2) && (strcmp($row->UndCicReduction,"В")==0)){
                $NewDC=substr($row->UndCicCode,0,2);
                $ToCount = 1;
                while ($DCode = each($DisCodes)){
                    //если подсчитывали то заносим соответствующее значение
                    if ((strcmp($NewDC, $DCode[1][0]) == 0)&&
                      ($row->CodeOfCicle == $DCode[1][1])){ $ToCount = 0;}
                }
                //Если таких дисциплин еще не встречалось то 
                //сохраняем данные о ней и суммируем
                if ($ToCount){
                    $Summ += ($row->LabInW+$row->PractInW+$row->LectInW)*$Weeks;
                    $DisCodes[] = array($NewDC, $row->CodeOfCicle); 
                }
            }else{
                 $Summ += ($row->LabInW+$row->PractInW+$row->LectInW)*$Weeks;
            }
        }
    }

    mysql_free_result($result);

    return $Summ;
}



/*
Подсчет объема лекций, аудиторных и практических занятий по данному плану $plan
за цикл $cicle
*/
function CalcPlanSumms($plan,$cicle=""){

    global $Connection;

    $Summs = array("LecInW"=>0, "LabInW"=>0, "PractInW"=>0, "AllH"=>0);
    $q = "select distinct schedplan.CodeOfSchPlan, schedplan.CodeOfDiscipline, schedplan.AllH, schedplan.CodeOfCicle, schedplan.UndCicCode, undercicles.UndCicReduction " .
          "from schedplan, undercicles";
    if ($cicle != "")
         $q .= ",cicles ";
    $q .= " where undercicles.CodeOfUnderCicle = schedplan.CodeOfUndCicle ".
          "and CodeOfPlan=$plan ";
    if ($cicle != "") 
        $q .= "and schedplan.CodeOfCicle=$cicle ";
    $q .= " order by schedplan.CodeOfUndCicle, schedplan.UndCicCode";
    $Discips = FetchArrays($q);
    $DisCodes = array();    //Массив для уже встречавшихся кодов дисциплин повыбору
    foreach ($Discips as $k => $v) {
        $ToCount = true;
        if((strlen($v["UndCicCode"])>2) && (strcmp($v["UndCicReduction"],"В")==0)){
            $NewDC=substr($v["UndCicCode"],0,2);
            while ($DCode = each($DisCodes)){
                //если подсчитывали то заносим соответствующее значение
                if ((strcmp($NewDC, $DCode[1][0]) == 0)&&($v["CodeOfCicle"] == $DCode[1][1]))
                     $ToCount = false;
            }
            //Если таких дисциплин еще не встречалось то 
            //сохраняем данные о ней и суммируем
            if ($ToCount)
                $DisCodes[] = array($NewDC, $v["CodeOfCicle"]); 
        }
        if ($ToCount){
                $result = mysql_query("select SUM(LectInW) as SumLecInW, SUM(LabInW) as SumLabInW, SUM(PractInW) as SumPractInW from schplanitems where CodeOfSchPlan=$v[CodeOfSchPlan]",$Connection)
                      or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
                $DiscipItems = mysql_fetch_assoc($result);
                $Summs["LecInW"] += $DiscipItems["SumLecInW"];
                $Summs["PractInW"] += $DiscipItems["SumPractInW"];
                $Summs["LabInW"] += $DiscipItems["SumLabInW"];
                $Summs["AllH"] += $v["AllH"];
        }
    }
    return $Summs;
}


/*  
Возвращает первый и последний семестр обучения для плана $CodeOfPlan
*/
function GetPeriod($CodeOfPlan, $inclKurs="N"){

     global $Connection;

     if (!empty ($CodeOfPlan)) {
          $Period = FetchFirstRow("select MIN(KursNumb), MAX(KursNumb) from schedules where CodeOfPlan=$CodeOfPlan");

          $FirstTerm = $Period["MIN(KursNumb)"] * 2 - 1;
          $LastTerm = $Period["MAX(KursNumb)"] * 2 ;

          if ($inclKurs == "Y")
                return array($Period["MIN(KursNumb)"], $Period["MAX(KursNumb)"], $FirstTerm, $LastTerm);
          else 
                return array($FirstTerm, $LastTerm);
     }
     else 
          return array("","");
}

function GetPlanInfo($CodeOfPlan) {

     global $Connection;

     $PlanData = FetchFirstRow ("select PlnName, YearCount, TeachForm, SpzName, SpcName, DirName, s.CodeOfSpecial, sz.CodeOfSpecialization, d.CodeOfDirect, sz.MinistryCode as SpzCode, s.MinistryCode as SpcCode, d.MinistryCode as DirCode from plans LEFT JOIN specials AS s ON s.CodeOfSpecial=plans.CodeOfSpecial LEFT JOIN specializations AS sz ON sz.CodeOfSpecialization=plans.CodeOfSpecialization  LEFT JOIN directions AS d ON d.CodeOfDirect=plans.CodeOfDirect where CodeOfPlan=$CodeOfPlan",$Connection);
    
     if (!empty($PlanData["CodeOfSpecialization"])) {

          $PlanData["PlanSpcCode"] = $PlanData["SpzCode"];
          $PlanData["PlanSpcName"] = $PlanData["SpzName"];
          $PlanData["PlanSpcRus"] = "СПЕЦИАЛИЗАЦИЯ";
          $PlanDepartData = FetchFirstRow("select department.Reduction as DepReduction, department.ZavSignature, faculty.Reduction as FacReduction, faculty.DeanSignature, degrees.DegreeName from specializations, department, faculty, degrees where specializations.CodeOfSpecialization=".$PlanData["CodeOfSpecialization"]." and  specializations.CodeOfDepart=department.CodeOfDepart and specializations.CodeOfFaculty=faculty.CodeOfFaculty and specializations.CodeOfDegree=degrees.CodeOfDegree");
          $PlanData = array_merge($PlanData,$PlanDepartData);
     }

     elseif (!empty($PlanData["SpcCode"])) {

          $PlanData["PlanSpcCode"] = $PlanData["SpcCode"];
          $PlanData["PlanSpcName"] = $PlanData["SpcName"];
          $PlanData["PlanSpcRus"] = "СПЕЦИАЛЬНОСТЬ";
          $PlanDepartData = FetchFirstRow("select department.Reduction as DepReduction, department.ZavSignature, faculty.Reduction as FacReduction, faculty.DeanSignature, degrees.DegreeName from specials, department, faculty, degrees where specials.CodeOfSpecial=".$PlanData["CodeOfSpecial"]." and  specials.CodeOfDepart=department.CodeOfDepart and specials.CodeOfFaculty=faculty.CodeOfFaculty and specials.CodeOfDegree=degrees.CodeOfDegree");
          $PlanData = array_merge($PlanData,$PlanDepartData);
     }

     elseif (!empty($PlanData["DirCode"])) {

          $PlanData["PlanSpcCode"] = $PlanData["DirCode"];
          $PlanData["PlanSpcName"] = $PlanData["DirName"];
          $PlanData["PlanSpcRus"] = "НАПРАВЛЕНИЕ";
          $PlanDepartData = FetchFirstRow("select department.Reduction as DepReduction, department.ZavSignature, faculty.Reduction as FacReduction, faculty.DeanSignature, degrees.DegreeName from directions, department, faculty, degrees where directions.CodeOfDirect=".$PlanData["CodeOfDirect"]." and  directions.CodeOfDepart=department.CodeOfDepart and directions.CodeOfFaculty=faculty.CodeOfFaculty and directions.CodeOfDegree=degrees.CodeOfDegree");
          $PlanData = array_merge($PlanData,$PlanDepartData);
     }

     return $PlanData;
}

//Подсчет распределения недель обучения по семестрам
function CalculateTSem($CodeOfPlan,$FirstTerm,$LastTerm) {

    $TSem = array();
    for ($i = $FirstTerm; $i<=$LastTerm; $i++) {
        $TSem[$i] = TeachWeek($CodeOfPlan, $i);
    }
    return $TSem;
}


//Получаеь список дисциплин плана, в формате для экспорта в формат RTF
function PlanDiscips($CodeOfPlan, $TSem, $IsRGR = 0) {

//Выбираем все циклы
     $PlandDiscips["Cicles"] = FetchArrays("select distinct cicles.CodeOfCicle, cicles.CicName, cicles.CicReduction from cicles, schedplan where cicles.CodeOfCicle=schedplan.CodeOfCicle and schedplan.CodeOfPlan='$CodeOfPlan' order by cicles.CodeOfCicle");
     $PlandDiscips["HoursInW"] = array(); //Массив с распределением занятий(практики, лекций, теории) по семестрам в плане
     $PlandDiscips["HoursTest"] = array(); //Массив с распределением экзаменов, зачетов, курсовых работ, курсовых проектов по семестрам в плане
     $PlandDiscips["Lect"] = 0;
     $PlandDiscips["Lab"] = 0;
     $PlandDiscips["Pract"] = 0;
     $PlandDiscips["AudH"] = 0;
     $PlandDiscips["AllH"] = 0;
     $PlandDiscips["SelfH"] = 0;

     //Проходим по всем циклам плана
     foreach ($PlandDiscips["Cicles"] as $k => $v) {
          $PlandDiscips["Cicles"][$k]["Lect"]  = 0;
          $PlandDiscips["Cicles"][$k]["Lab"]    = 0;
          $PlandDiscips["Cicles"][$k]["Pract"] = 0;
          $PlandDiscips["Cicles"][$k]["AudH"] = 0;
          $PlandDiscips["Cicles"][$k]["AllH"] = 0;
          $PlandDiscips["Cicles"][$k]["SelfH"] = 0;

          $PlandDiscips["Cicles"][$k]["HoursInW"] = array(); //Массив с распределением занятий по семестрам в цикле
          $PlandDiscips["Cicles"][$k]["HoursTest"] = array(); 

          //Выбор всех подциклов цикла
          $UnderCicles = FetchArrays("select distinct undercicles.CodeOfUnderCicle, undercicles.UndCicName, undercicles.UndCicReduction from undercicles, schedplan where undercicles.CodeOfUnderCicle=schedplan.CodeOfUndCicle and schedplan.CodeOfCicle='$v[CodeOfCicle]' and schedplan.CodeOfPlan='$CodeOfPlan' order by  undercicles.CodeOfUnderCicle");

          foreach ($UnderCicles as $uk => $uv) {
                $UnderCicles[$uk]["Lect"] = 0;
                $UnderCicles[$uk]["Lab"] = 0;
                $UnderCicles[$uk]["Pract"] = 0;
                $UnderCicles[$uk]["AllH"] = 0;
                $UnderCicles[$uk]["SelfH"] = 0;
                $UnderCicles[$uk]["AudH"] = 0;

                $UnderCicles[$uk]["HoursTest"] = array();
                $UnderCicles[$uk]["HoursInW"] = array();

                $Discips = FetchArrays("select * from schedplan, department, disciplins where CodeOfPlan='$CodeOfPlan' and CodeOfCicle='$v[CodeOfCicle]' and CodeOfUndCicle='$uv[CodeOfUnderCicle]' and disciplins.CodeOfDiscipline=schedplan.CodeOfDiscipline and department.CodeOfDepart=schedplan.CodeOfDepart order by UndCicCode, DisName");
                $UnderCicles[$uk]["LectInW"] = 0;

                $DisCodes = array(); //массив с уже встречавшимися кодами дисциплин

                foreach($Discips as $dk => $dv) {

                     $Discips[$dk]["HoursInW"] = array(); //Массив с распределением занятий по семестрам в дисциплине
                     $Discips[$dk]["HoursTest"] = array(); //Массив с распределением экзаменов, зачетов, курсовых работ, курсовых проектов по семестрам в дисциплине

                     $Discips[$dk]["Reduction"] = str_replace("неук."," ",$Discips[$dk]["Reduction"]); //Кафедра дисциплины

                     //Переменная, определяющая учитывать ли данную дисциплину при расчете суммарных значений

                     $Discips[$dk]["ToCountAll"] = 1;

                     //Если в коде дисциплины более 2х символов
                     if (($uv["UndCicReduction"] == 'В') && (strlen($dv["UndCicCode"])>2)) {
                          $NewDC=substr($dv["UndCicCode"],0,2);

                          //Если данный код уже встречался
                          while ($DCode = each($DisCodes))
                                if ($NewDC == $DCode[1])
                                     $Discips[$dk]["ToCountAll"] = 0;
                          
                         if ($Discips[$dk]["ToCountAll"])
                              $DisCodes[] = $NewDC;
                     }

                     //Заполнение нагрузки по дисциплине
                     $DiscipItems = FetchArrays("select * from schplanitems where CodeOfSchPlan='$dv[CodeOfSchPlan]' order by NumbOfSemestr");

                     $Discips[$dk]["Lect"]  = 0;
                     $Discips[$dk]["Lab"]   = 0;
                     $Discips[$dk]["Pract"] = 0;
                     //Обработка дисциплины
                     foreach ($DiscipItems as $ik => $iv) {
                          $Discips[$dk]["Lect"] += $iv["LectSem"];
                          $Discips[$dk]["Lab"] += $iv["LabSem"];
                          $Discips[$dk]["Pract"] += $iv["PractSem"];

//                          $Discips[$dk]["Lect"] += $iv["LectInW"]*$TSem[$iv["NumbOfSemestr"]];
//                          $Discips[$dk]["Lab"] += $iv["LabInW"]*$TSem[$iv["NumbOfSemestr"]];
//                          $Discips[$dk]["Pract"] += $iv["PractInW"]*$TSem[$iv["NumbOfSemestr"]];
                          if (empty($Discips[$dk]["HoursTest"][$iv["NumbOfSemestr"]]))
                                $HoursTest = array("Exam"=>0,"Test"=>0,"KursPrj"=>0,"KursWork"=>0);
                          else
                                $HoursTest = $Discips[$dk]["HoursTest"][$iv["NumbOfSemestr"]];

                          foreach ($HoursTest as $hk => $hv)
                                //Учет нагрузки по дисциплине
                                if ($iv[$hk]) {

                                     if(empty($Discips[$dk][$hk."Prn"])) 
                                          $Discips[$dk][$hk."Prn"] = $iv["NumbOfSemestr"];
                                     else
                                          $Discips[$dk][$hk."Prn"] .= "," . $iv["NumbOfSemestr"];
                                
                                     $HoursTest[$hk]++;
                                } elseif(empty($Discips[$dk][$hk."Prn"])) 
                                          $Discips[$dk][$hk."Prn"] = "";

                        
                          if ($iv["Synopsis"] || $iv["RGR"]) {
                                if (empty($Discips[$dk]["RGRPrn"])) 
                                     $Discips[$dk]["RGRPrn"] = $iv["NumbOfSemestr"].":".$iv["Synopsis"] + $iv["RGR"];
                                else
                                     $Discips[$dk]["RGRPrn"] .= ",".$iv["NumbOfSemestr"].":".$iv["Synopsis"] + $iv["RGR"];
                          } 

                          $Discips[$dk]["HoursTest"][$iv["NumbOfSemestr"]] = $HoursTest;
                          $Discips[$dk]["HoursInW"][$iv["NumbOfSemestr"]]=array("LectInW"=>$iv["LectInW"],"LabInW"=>$iv["LabInW"],"PractInW"=>$iv["PractInW"]);
                     }

                     $Discips[$dk]["AudH"] = $Discips[$dk]["Lect"]+$Discips[$dk]["Lab"]+$Discips[$dk]["Pract"];
                     $Discips[$dk]["SelfH"] = $Discips[$dk]["AllH"]-$Discips[$dk]["AudH"];

                     //Заполняем посеместровый массив лекций, лаб, практик
                     if ($Discips[$dk]["ToCountAll"]){
     
                          if ($Discips[$dk]["ToCount"]) {
     
                                $UnderCicles[$uk]["HoursTest"] = SumHoursArrays($UnderCicles[$uk]["HoursTest"], $Discips[$dk]["HoursTest"]);
                                $UnderCicles[$uk]["HoursInW"] = SumHoursArrays($UnderCicles[$uk]["HoursInW"], $Discips[$dk]["HoursInW"]);
     
                                $UnderCicles[$uk]["Lect"]  += $Discips[$dk]["Lect"];
                                $UnderCicles[$uk]["Lab"]    += $Discips[$dk]["Lab"];
                                $UnderCicles[$uk]["Pract"] += $Discips[$dk]["Pract"];
     
                          }
     
                          $UnderCicles[$uk]["AllH"] += $Discips[$dk]["AllH"];
                          $UnderCicles[$uk]["SelfH"] += $Discips[$dk]["SelfH"];
                          $UnderCicles[$uk]["AudH"] += $Discips[$dk]["AudH"];
     
                     }
                }

                $UnderCicles[$uk]["Discips"] = $Discips;

                $PlandDiscips["Cicles"][$k]["HoursTest"] = SumHoursArrays($PlandDiscips["Cicles"][$k]["HoursTest"], $UnderCicles[$uk]["HoursTest"]);
                $PlandDiscips["Cicles"][$k]["HoursInW"] = SumHoursArrays($PlandDiscips["Cicles"][$k]["HoursInW"], $UnderCicles[$uk]["HoursInW"]);

                $PlandDiscips["Cicles"][$k]["Lect"]  += $UnderCicles[$uk]["Lect"]; 
                $PlandDiscips["Cicles"][$k]["Lab"]  += $UnderCicles[$uk]["Lab"];  
                $PlandDiscips["Cicles"][$k]["Pract"] += $UnderCicles[$uk]["Pract"];

                $PlandDiscips["Cicles"][$k]["AllH"] += $UnderCicles[$uk]["AllH"];
                $PlandDiscips["Cicles"][$k]["AudH"] += $UnderCicles[$uk]["AudH"];
                $PlandDiscips["Cicles"][$k]["SelfH"] += $UnderCicles[$uk]["SelfH"];

          }

          $PlandDiscips["Cicles"][$k]["UnderCicles"] = $UnderCicles;

          $PlandDiscips["Lect"]  += $PlandDiscips["Cicles"][$k]["Lect"];
          $PlandDiscips["Lab"]  += $PlandDiscips["Cicles"][$k]["Lab"];
          $PlandDiscips["Pract"] += $PlandDiscips["Cicles"][$k]["Pract"];
          $PlandDiscips["AudH"]  += $PlandDiscips["Cicles"][$k]["AudH"];
          $PlandDiscips["AllH"]  += $PlandDiscips["Cicles"][$k]["AllH"];
          $PlandDiscips["SelfH"] += $PlandDiscips["Cicles"][$k]["SelfH"];
          $PlandDiscips["HoursInW"] = SumHoursArrays($PlandDiscips["HoursInW"], $PlandDiscips["Cicles"][$k]["HoursInW"]);
          $PlandDiscips["HoursTest"] = SumHoursArrays($PlandDiscips["HoursTest"], $PlandDiscips["Cicles"][$k]["HoursTest"]);
     }
     return $PlandDiscips;
}

function SumHoursArrays($arr1, $arr2) {
     if (empty($arr1))
          return $arr2;
     foreach ($arr2  as $k => $v) {
          if (is_array($v)) {
                if (empty($arr1[$k]))
                     $arr1[$k] = $v;
                else
                     $arr1[$k] = SumHoursArrays($arr1[$k], $v);
          }
          else {
              if (empty($arr1[$k]))
                     $arr1[$k] = $v;
              else 
                     $arr1[$k] += $v;
          }
     }
     return $arr1;
}

//Удаляет из плана и ГУП $CodeOfPlan дисциплины за курс $KursNum
function DelKurs($KursNum, $CodeOfPlan) {
     global $Connection;

     DelDisByKurs($KursNum, $CodeOfPlan);

     mysql_query("DELETE FROM schedules WHERE CodeOfPlan='$CodeOfPlan' AND KursNumb='$KursNum'")
            or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
}


//Удаляет из плана $CodeOfPlan дисциплины за курс $KursNum
function DelDisByKurs($KursNum, $CodeOfPlan) {
    $DeletedDiscips = FetchArrays("SELECT DISTINCT schedplan.CodeOfSchPlan FROM schplanitems, schedplan WHERE CodeOfPlan='$CodeOfPlan' and schedplan.CodeOfSchPlan=schplanitems.CodeOfSchPlan and (NumbOfSemestr='".($KursNum*2)."' or NumbOfSemestr='".($KursNum*2-1)."')");
    if (! empty($DeletedDiscips)) {
        foreach ($DeletedDiscips as $k => $v) {
            mysql_query("DELETE FROM schplanitems WHERE CodeOfSchPlan='$v[CodeOfSchPlan]' and (NumbOfSemestr='".($KursNum*2)."' or NumbOfSemestr='".($KursNum*2-1)."')")
            or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
            $IsRest = FetchArrays("SELECT CodeOfSchPlanItem FROM schplanitems WHERE CodeOfSchPlan='$v[CodeOfSchPlan]'");

            if (empty($IsRest)) {
                mysql_query("DELETE FROM schedplan WHERE CodeOfSchPlan='$v[CodeOfSchPlan]'")
                          or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
            }
        }    
    }
}

function QueryExec($q) {
     mysql_query($q)
          or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
}

//Осуществляет переход на страницу $page
function FuncRedirect($page) {
    Header ("Location: $page");
    exit;
}

//Возвращает русское название вида занятий 
function GetRusName($label) {
    switch ($label) {
        case "lb":
            return "лаб";
        case "pr":
            return "прак";
        case "lc":
            return "лек";
    }
    return "";
}

function getmicrotime(){ 
    list($usec, $sec) = explode(" ",microtime()); 
    return ((float)$usec + (float)$sec); 
}

function parseSchedule($sched, &$data, &$weeks){
	foreach($sched as $key=>$value){
		if ($value['stud']){
               		for($i=0; $i<16; $i++){
				$data['schedule'][$key][$i]['type'] = '';
				$data['schedule'][$key][$i]['len'] = 0;
			}
			$data['schedule'][$key]['stud'] = $value['stud'];
			$data['schedule'][$key]['group'] = $value['group']; 
			$col = strlen($value['chart']);
			$type = $value['chart'][0];
			$tlen = 0;
			$i_period = 0;
			$period = 1;
			for($i=0; $i<$col; $i++){
				if ($value['chart'][$i]==$type && $i!=$col-1)
					$tlen++;
				else{
					switch ($type){
						case 'Т': $typeDB = 'ТО';
							break;
						case 'К': $typeDB = 'К';
							break;
						case 'Э': $typeDB = 'Эк';
							break;
						case 'П': $typeDB = 'ПП';
							break;
						case 'У': $typeDB = 'УП';
							break;
						case 'Г': $typeDB = 'ГЭC';
							break;
						case 'А': $typeDB = 'ИА';
							break;
						case 'Д': $typeDB = 'ДП';
							break;
						default:
							$typeDB = '';
					}
					if ($i>23 && $period==1){
						$period = 0;
						$i_period = 8;
					}
					$period = ($i>23)? 0: 1;
					$data['schedule'][$key][$i_period]['type'] = $typeDB;
					$data['schedule'][$key][$i_period]['len'] = $tlen;
					if ($typeDB=='ТО')
						$weeks[2*$key-$period] = $tlen;
					$tlen = 1;
					$type = $value['chart'][$i]; 
					$i_period++;
					
				}
			}
		}
	}
	
}

function FileToArray($filename){
	$data = array();
	$file = fopen($filename,"r");
	while (!feof($file)){
		$data[] = fgets($file);
	}
	return $data;
} 

function ExamForm(&$planDis,$form, $i, $attrval){
	$exam = preg_split('//', $attrval, -1, PREG_SPLIT_NO_EMPTY);
	foreach($exam as $val){
		if ($val=='A' || $val=='a') $val = 10;							
		if ($val=='B' || $val=='b') $val = 11;
		if ($val=='C' || $val=='c') $val = 12;
		if ($val=='D' || $val=='d') $val = 13;
		$planDis[$i]['items'][$val][$form] = 1;
	}
	ksort($planDis[$i]['items']);
}
function DiscipRediction($disName){
        $words = preg_split("/[\s]+/", $disName);
        $reduct = "";
        if (count($words)>1){ 
        	foreach($words as $num=>$word){
                	$str = trim($word);
                	if (strlen($str)>4){
	                	$str = strtoupper($str);	
                		$word = substr($str,0,1);
			}
                	else
                		$word = $str;
                	$reduct .= $word;
               	}  
        }
        else $reduct = $disName;

        return $reduct;
}

function DiscipCode($disName,$depart){
	$disName = trim($disName);
	$q = "Select CodeOfDiscipline as id from disciplins where DisName='$disName' and CodeOfDepart=$depart";
	$res = FetchResult($q);
	if (!$res){
		$q = "Select max(CodeOfDiscipline) as id from disciplins";
		$res = FetchResult($q)+1;
		$disRed = DiscipRediction($disName);
		$q = "INSERT INTO disciplins (CodeOfDiscipline, DisName, DisReduction, CodeOfDepart) VALUES ($res,'$disName','$disRed',$depart)";
		FetchQuery($q);
	}	
	return $res;
}
function CiclesCode($ciclestr){
        $words = preg_split("/[\.]+/", $ciclestr);
	$cCode = 19;
	$undCCode = 1;
	$number = 1;
	$flag = 0;
	foreach ($words as $ciclitem){	
		if ($ciclitem=='ДН(М)') $ciclitem = 'ДН';
          	if ($ciclitem=='СД(M)') $ciclitem = 'СД';
          	if ($ciclitem=='НИРМ') $ciclitem = 'НИР';
		$res = FetchResult("SELECT CodeOfCicle as id from cicles WHERE CicReduction='$ciclitem'");		
		if ($res) $cCode = $res;
		else{
			if (strcmp($ciclitem[0],'В')==0){ 
				$number = $ciclitem[1];
				$ciclitem = 'В';
				$flag = 1;
			}
			$res = FetchResult("SELECT CodeOfUnderCicle as id from undercicles WHERE UndCicReduction='$ciclitem'");	
			if ($res) $undCCode = $res;
			elseif(is_numeric($ciclitem) && $flag==0) $number = $ciclitem;	
		}
			
	}
	if (strlen($number)<2) $number = '0'.$number;
	return array($cCode,$undCCode,$number);
}
?>