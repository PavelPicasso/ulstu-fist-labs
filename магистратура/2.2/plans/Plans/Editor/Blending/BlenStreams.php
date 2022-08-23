<?php
if (empty($CodeOfDiscipline) || empty($NumbOfSemestr) || empty($plitem) || empty($CodeOfDepart))
Header ("Location: ../title.php");
	
include("../PlanCalculatFunc.php");
CreateConnection();
if (!empty ($updategroups) && $updategroups == "Y") {
	if (!empty($DivRate) && !empty($BlendStyle) && !empty($CodeOfStream)) {
		FetchQuery("DELETE FROM divdiscip WHERE CodeOfStream = '$CodeOfStream' and  CodeOfSchPlanItem = '$plitem' and BlendStyle = '$BlendStyle'");
		FetchQuery("REPLACE INTO divdiscip (CodeOfStream, CodeOfSchPlanItem, BlendStyle, DivRate) VALUES('$CodeOfStream', '$plitem', '$BlendStyle', '$DivRate');");
	}
	if (!empty($divgoups) && is_array($divgoups)) {
		if (!empty($divgoups['Delete']) && is_array($divgoups['Delete']))
			foreach ($divgoups['Delete'] as $k=>$v)
				FetchQuery("DELETE FROM divdiscip WHERE CodeOfDiv='$v'");
		if (!empty($divgoups['DivRate']) && is_array($divgoups['DivRate']))
			foreach ($divgoups['DivRate'] as $k=>$v)
				FetchQuery("UPDATE divdiscip SET DivRate='$v' WHERE CodeOfDiv='$k'");
			
		
	}

}
else 
foreach ($discip_types as $kd => $vd) {

	$blends = FetchArrays("select * from blending where CodeOfDiscipline = '$CodeOfDiscipline' and CodeOfDepart='$CodeOfDepart' and NumbOfSemestr='$NumbOfSemestr' and BlendStyle='$vd'");
	if (is_array($blends)) {
		$codes = '\'0\'';
		foreach ($blends as $k=>$v)
			$codes .= ",$v[CodeOfBlend]";
		FetchQuery("delete from streamblending where CodeOfBlend in ($codes)");

		FetchQuery("delete from blending where CodeOfDiscipline = '$CodeOfDiscipline' and CodeOfDepart='$CodeOfDepart' and NumbOfSemestr='$NumbOfSemestr' and BlendStyle='$vd'");
	}

	if (! empty($$vd) && is_array($$vd)) {
		$dt = $$vd;
		FetchQuery("INSERT INTO blending (CodeOfDiscipline, CodeOfDepart, NumbOfSemestr, BlendStyle, UnionRate) VALUES('$CodeOfDiscipline', '$CodeOfDepart', '$NumbOfSemestr','$vd',".count($dt).");");
		$CodeOfBlend = FetchResult("SELECT * FROM blending where CodeOfDiscipline = '$CodeOfDiscipline' and CodeOfDepart='$CodeOfDepart' and NumbOfSemestr='$NumbOfSemestr' and BlendStyle='$vd'");
		foreach ($dt as $ks=>$vs)
			FetchQuery("INSERT INTO streamblending (CodeOfBlend, CodeOfStream) VALUES('$CodeOfBlend', '$ks');");
	}
}


Header ("Location: BlenPlanItem.php?plitem=$plitem");
?>