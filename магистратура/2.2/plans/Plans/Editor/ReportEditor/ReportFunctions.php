<?php
require_once('../ReportEditor/xml.php');

function ExistingParamsList() {
	global$dir;
	global $allparams;
	global $plan_params;
	global $common_params;
	$params = array();

	if ($handle = opendir($dir)) {
	    while (false !== ($file = readdir($handle))) { 
			if (is_file($dir."/".$file)) {

				$param = ParseXML($dir."/".$file);
				if (!isset($param["FUNCTION"][0]["RUSNAME"]))
						$param["FUNCTION"][0]["RUSNAME"]=$param["FUNCTION"][0]["NAME"];
				if (is_array($param) && !empty($param["FUNCTION"][0]["NAME"])) {
					if ($param["FUNCTION"][0]["ISPLAN"] == "Y")
						$plan_params[$param["FUNCTION"][0]["NAME"]] = $param["FUNCTION"][0]["RUSNAME"];
					else
						$common_params[$param["FUNCTION"][0]["NAME"]] = $param["FUNCTION"][0]["RUSNAME"];

					$ext_params = array();
					if (!empty($param["FUNCTION"][0]["EXTERNALPARAMETERS"]) && is_array($param["FUNCTION"][0]["EXTERNALPARAMETERS"])) {
						foreach ($param["FUNCTION"][0]["EXTERNALPARAMETERS"] as $k=>$v) 
							$ext_params[$v["EXTERNALPARAMETERSNAME"]] = $v["EXTERNALPARAMETERSNAME"];
					}

					$comment="";
					$php_formula = "";

					foreach ($param["FUNCTION"][0]["FORMULA"][0]["OPERATOR"] as $k=>$v) {
						if ($v["TYPE"] == "variable")
							$php_formula .= "\$";

						$php_formula .= $v["VALUE"];
						$comment .= $v["VALUE"];
					}
					
					$params [$param["FUNCTION"][0]["NAME"]] = array ("name"=>$param["FUNCTION"][0]["NAME"], "rusname"=>$param["FUNCTION"][0]["RUSNAME"], "formula"=>$comment, "php_formula"=>$php_formula, "ext_params"=>$ext_params, "isplan"=>$param["FUNCTION"][0]["ISPLAN"], "display"=>$param["FUNCTION"][0]["DISPLAY"]);

				}

			}
		}

	    closedir($handle);
	}
	return ($params);
}

function ParseXML($filename) {
	$xml = new xml();
	$contents = file_get_contents($filename);
	$params = $xml->parse_struct($contents);
	return $params;
}

//Удаляет из массива параметров
//все па  раметры, в формулах которых 
//участвует параметр $fname
function ExcludeParams($params,$fname) {
	global $allparams;
	global $common_params, $plan_params;
	
	unset($common_params[$fname]);
	unset($plan_params[$fname]);
	foreach ($params as $k=>$v) {

		if (!empty($allparams[$k]["ext_params"])) {
			foreach ($allparams[$k]["ext_params"] as $kp=>$vp) {
				if ($vp == $fname) {
					unset($common_params[$k]);
					unset($plan_params[$k]);
				}
			}
			ExcludeParams($allparams[$k]["ext_params"],$fname);
		}
	}
}

function OutputButtons($buttons, $rows=3, $comments=true) {
	
	echo "<TABLE>";

	$width = ceil(100/$rows);
	$i = 0;

	foreach ($buttons as $k=>$v) {
		if (bcmod($i,$rows) == 0)
			echo "<TR>";
		
		$comment= "";
		if ($comments)
			 $comment=" - $v";
		echo "<TD width='$width%'><INPUT type='button' value=' $k ' onclick=\"javascript: AddParam('$k');\">$comment</TD>";
		$i++;

		if (bcmod($i,$rows) == 0)
			echo "</TR>";

	}

	if (bcmod($i,$rows) != 0) {
		for ($j=bcmod($i,$rows); $j<$rows; $j++)
			echo "<TD width='$width%'>&nbsp;</TD>";
		echo "</TR>";
	}

	echo "</TABLE>";

}

function HasChild($params,$fname) {
	global $allparams;
	global $common_params, $plan_params;
	
	unset($common_params[$fname]);
	unset($plan_params[$fname]);
	foreach ($params as $k=>$v) {

		if (!empty($allparams[$k]["ext_params"])) {
			foreach ($allparams[$k]["ext_params"] as $kp=>$vp) {
				if ($vp == $fname) {
					return true;
				}
			}
			if (HasChild($allparams[$k]["ext_params"],$fname))
				return true;
		}
	}
	return false;
}

?>