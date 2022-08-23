<?php

function SortParams($params) {
	global $positions;
	$modify = false;
	foreach ($params as $k=>$v) {
		$positions[$v["name"]] = GetPosition($v);
	}
	asort($positions);
}

function GetPosition($param) {
	global $positions;
	global $allparams;

	if (isset($positions[$param["name"]]))
		return $positions[$param["name"]];


	if (empty($param["ext_params"]))
		return 0;
	else {
		$max_position = 0;
		foreach ($param["ext_params"] as $k=>$v) {

			if (!isset($positions[$k])) {
				if (!isset ($allparams[$k]))
					$positions[$k] = 0;
				else
					$positions[$k] = GetPosition($allparams[$k]);
			}

			if ($max_position < $positions[$k])
				$max_position = $positions[$k];

		}
		return $max_position+1;
	}

}

function GenerateLib($params) {
	global $lib_file;
	global $positions;
	$lib_str = "<?php\n";

	foreach ($params as $k=>$v) {
		$lib_str .= "\n";
		$lib_str .= "function f_$v[name]() {\n";
		if ($v["ext_params"])
			foreach ($v["ext_params"] as $kp=>$vp)
				$lib_str .= "	global \$".$vp.";\n";
				
		$lib_str .= "	return $v[php_formula];\n";

		$lib_str .= "}\n\n";
		
	}

	$lib_str .= "?>";

	SaveLib($lib_file, $lib_str);
}

function AssignParamsLib($params) {
	global $local_variables;
	global $global_variables;
	global $positions;
	global $tipical_vars;
	$local_out_str = "\$local_output = array(  ";
	$global_out_str = "\$global_output = array(  ";

	$local_str = "<?php\n";
	$global_str = "<?php\n";

	foreach ($positions as $k=>$v) {
		if (isset($params[$k])) {

			$param_str = "\n";
			$param_str .= "\$".$k." = f_$k();\n";

			if ($params[$k]["isplan"]=="Y") {
				$local_str .= $param_str;
				if ($params[$k]["display"]=="Y")
					if (!isset($tipical_vars[$k]))
						$local_out_str .= "\"$k\"=>\"".$params[$k]["rusname"]."\", " ;
					
			}
			else {
//				$local_str .= "global \$".$k.";\n";
				$global_str .= $param_str;
				if ($params[$k]["display"]=="Y")
					$global_out_str .= "\"$k\"=>\"".$params[$k]["rusname"]."\", " ;
			}
		}
	}

	$local_out_str = substr($local_out_str, 0, -2);
	$global_out_str = substr($global_out_str, 0, -2);

	$local_out_str .= ");\n";
	$global_out_str .= ");\n";
	$global_str .= "\n".$local_out_str;
	$global_str .= "\n".$global_out_str;
	$local_str .= "\n?>";
	$global_str .= "\n?>";

	SaveLib($local_variables, $local_str);
	SaveLib($global_variables, $global_str);

}

function SaveLib($libname, $libtext) {
	$file = fopen($libname,"w");
		
	if (!$file || !fwrite($file,$libtext))
		return false;

	fclose($file);

	return true;
}

?>