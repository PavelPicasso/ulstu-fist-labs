<?
    include("../Plans/cfg.php");
    include("../Plans/Editor/PlanCalculatFunc.php");
    set_time_limit(60*3);   
    CreateConnection();

    $plans = FetchArrays("SELECT * FROM plans where DateArchive is NULL"); 
    foreach ($plans as $k => $v) {                                  //1
	    list ($FirstKurs, $LastKurs, $FirstTerm, $LastTerm) = GetPeriod($v[CodeOfPlan], "Y");
	    $TSem = array();
	    for($i=$FirstTerm; $i<=$LastTerm; $i++) 
	    	$TSem[$i] = TeachWeek($v[CodeOfPlan], $i);
   	    $raschas = FetchArrays("SELECT CodeOfSchPlanItem, NumbOfSemestr, LectInW, LabInW, PractInW FROM schplanitems si left join schedplan sp on si.CodeOfSchPlan=sp.CodeOfSchPlan WHERE sp.CodeOfPlan=$v[CodeOfPlan]");
	    foreach($raschas as $rch){
		$lect = $rch[LectInW]*$TSem[$rch[NumbOfSemestr]];
		$lab = $rch[LabInW]*$TSem[$rch[NumbOfSemestr]];
		$pr = $rch[PractInW]*$TSem[$rch[NumbOfSemestr]];
		$q = "UPDATE schplanitems set LectSem=$lect, LabSem=$lab, PractSem=$pr where CodeOfSchPlanItem=$rch[CodeOfSchPlanItem]";
		mysql_query($q)
                    or die("Unable to execute query:".mysql_errno().": ".mysql_error()."<BR>");
	}
	echo $plans[PlnName]."-ok";
    }
    mysql_close($Connection);

?>