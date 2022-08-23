<?php
	session_start();
	if (!(($_SESSION["status"] == 0)||($_SESSION["status"] == 1)||($_SESSION["status"] == 2))){
		Header ("Location: ../Unreg.html");
	}
	include("../Display/StartPage.php");
	include("../Display/DisplayFunc.php");
	include("../Display/ValidationForm.php");

	include("../PlanCalculatFunc.php");
	CreateConnection();

	DisplayPageTitle("","Стандарты");
?>
<FORM NAME=STAND METHOD=POST ACTION="">
<table  class='ramka' border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
<tr><td cellpadding="0" cellspacing="0">
<TABLE  class='table' BORDER=0 ALIGN=CENTER CELLSPACING=1 CELLPADDING=1 WIDTH='100%'>
<?php  
	$edit = "EditStandard.php";
	$look = "LookStandard.php";
	echo "<TR ALIGN='CENTER' VALIGN='MIDDLE'>\n";
	echo "<TH width= 4%><strong></strong></TH>";
	echo "<TH width= 10%><strong>Мин. код</strong></TH>";
	echo "<TH width = 50%><strong>Стандарт специальности</strong></TH>";
	echo "<TH><strong>Дата утверждения</strong></TH>";
	echo "<TH width = 20%><strong>Типовые планы</strong></TH>";
	if ($_SESSION["status"] == 0){echo "<TH><strong>Редактирование ограничений</strong></TH>";}
	else { echo "<TH><strong>Просмотр ограничений</strong></TH>";}
	echo "</TR>";


	//Массив с кодами направлений 
	$result = mysql_query("select MinistryCode, CodeOfDirect, DirName, CodeOfFaculty from directions", $Connection)
		or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
	$DirectArray = array();
	while ($row = mysql_fetch_object($result)){
		$DirectArray[$row->CodeOfDirect] = array( $row->MinistryCode, $row->DirName, $row->CodeOfFaculty);
	}

	//Массив с кодами специальностей
	$result = mysql_query("select MinistryCode, CodeOfSpecial, SpcName, CodeOfFaculty from specials", $Connection)
		or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
	$SpecArray = array();
	while ($row = mysql_fetch_object($result)){
		$SpecArray[$row->CodeOfSpecial] = array( $row->MinistryCode, $row->SpcName, $row->CodeOfFaculty);
	}

	//Массив с кодами специализаций
	$result = mysql_query("select MinistryCode, CodeOfSpecialization, SpzName, CodeOfFaculty from specializations", $Connection)
		or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
	$SpzArray = array();
	while ($row = mysql_fetch_object($result)){
		$SpzArray[$row->CodeOfSpecialization] = array( $row->MinistryCode, $row->SpzName, $row->CodeOfFaculty);
	}

	$result = mysql_query("select * from metalimits", $Connection)
		or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
	$Stand= "";
	if ($row = mysql_fetch_object($result)){
		$Stand= $row->LimitsAdr;
	}
	$Tipl = $Stand."/plans/";
	$resSt = mysql_query("select * from specialslimit", $Connection)
		or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
	while ($rowSt = mysql_fetch_object($resSt)){
		$WasLimits = 1;
		$LimitFile=$rowSt->LimitFile;
		$CodeOfStandard = $rowSt->CodeOfStandard;
		if (strcmp($LimitFile,"")==0){$LimitFile="Sorry.html";};

		$MinCode = "------------";
		$NameSpecial = "~~~~~~~~~~~~"; 
		$FcCode = 0;
		if (isset($rowSt->CodeOfSpecialization) && !empty($rowSt->CodeOfSpecialization)){
			$MinCode = $SpzArray[$rowSt->CodeOfSpecialization][0];
			$NameSpecial = $SpzArray[$rowSt->CodeOfSpecialization][1];
			$FcCode = $SpzArray[$rowSt->CodeOfSpecialization][2];
		}else{
			if (isset($rowSt->CodeOfSpecial) && !empty($rowSt->CodeOfSpecial)){
				$MinCode = $SpecArray[$rowSt->CodeOfSpecial][0];
				$NameSpecial = $SpecArray[$rowSt->CodeOfSpecial][1];
				$FcCode = $SpecArray[$rowSt->CodeOfSpecial][2];
			}else{
				if (isset($rowSt->CodeOfDirect) && !empty($rowSt->CodeOfDirect)){
					$MinCode = $DirectArray[$rowSt->CodeOfDirect][0];
					$NameSpecial = $DirectArray[$rowSt->CodeOfDirect][1];
					$FcCode = $DirectArray[$rowSt->CodeOfDirect][2];
				}
			}
		}
		if (($_SESSION["status"] != 0) || ($_SESSION["statusCode"]==$FcCode) || ($_SESSION["statusCode"]==0)){
			echo "<TR ALIGN='LEFT' VALIGN='MIDDLE'>";
			echo "<TH ALIGN='CENTER'><INPUT TYPE='CHECKBOX' NAME='flag[$rowSt->CodeOfStandard]' VALUE=\"$CodeOfStandard\"></TH>";
			echo "<TH ALIGN='CENTER'>$MinCode</TH>";
			echo "<TH width=60% height=40>&nbsp;&nbsp;&nbsp;"."
				<a href=\"$Stand".$LimitFile."\" >$NameSpecial</a><br></TH>";
			$StDate = $rowSt->StDate	  ;
			$day = substr($StDate,8,2);
			$month = substr($StDate,5,2);
			$year = substr($StDate,0,4);
			echo "<TH ALIGN='CENTER'>$day-$month-$year</TH>";
			echo "<TH ALIGN='CENTER'>";
			$resTP = mysql_query("select * from standardplans where CodeOfStandard=".$CodeOfStandard, $Connection)
				or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
			while ($rowTP = mysql_fetch_object($resTP)){
				echo "<a href=\"$Stand".$rowTP->PlanFile."\" >$rowTP->PlanFile</a><br>";
			}
			echo " </TH>";
			if ($_SESSION["status"] == 0){ echo "<TH ALIGN='CENTER'><input type=submit value='Редактор' onClick=\"STAND.action='$edit?stCode=$CodeOfStandard'\"></TH>";}
			else { echo "<TH ALIGN='CENTER'><input type=submit value='Просмотр' onClick=\"STAND.action='$look?stCode=$CodeOfStandard'\"></TH>";}
			echo "</TR>";
			mysql_free_result($resTP);
		}
	}
	mysql_free_result($resSt);
	mysql_free_result($result);
	mysql_close($Connection);
	echo "</TABLE>";
	echo "</td></tr></table><BR>";
	if ($_SESSION["status"] == 0){
		echo "<CENTER><INPUT TYPE='SUBMIT' NAME='Add' VALUE='Добавить новый стандарт' onClick=\"STAND.action='ChoiseDirect.php'\"></INPUT>&nbsp;&nbsp;";
		echo "<INPUT TYPE='BUTTON' NAME='Del' VALUE='Удалить выделенные стандарты' onClick=\"javascript: if(Deleting(this)) { STAND.action='DoDelStandard.php'; STAND.submit();}\"><P>";
		echo "<INPUT TYPE='SUBMIT' NAME='Edit' VALUE='Изменить путь к файлам стандартов' onClick=\"STAND.action='EditMeta.php'\"></INPUT></CENTER>";
	}
?>
</FORM>
<HR>
<?php
	include("../Display/FinishPage.php");
?>
