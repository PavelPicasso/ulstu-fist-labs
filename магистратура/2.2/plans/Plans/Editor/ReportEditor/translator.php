<?php
class translator  {
    var $global_vars;
	var $formula;
	var $lexems;
	var $error;
	var $current_symbol;

    function translator($lexems)
    {
        $this->global_vars = array();
        $this->formula = array();
        $this->lexems = $lexems;
        $this->error = "";
    }

    function parse()
    { 
        if (!$this->expression()) 
			return $this->error;
		return false;
    }

	function expression() {
        if (!$this->term())
			return false;

        if (!$this->moreterms())
			return false;

		return true;
	}

	function term() {
		if (!$this->factor())
			return false;
        if (!$this->morefactors())
			return false;

		return true;
	}

	function factor() {
		$symbol =  current($this->lexems);

		if ($symbol["type"] == "s_minus") {
			$this->formula[] = array("type"=>"operation", "value"=>"-");
			next($this->lexems);
		}

        if (!$this->simplefactor())
			return false;

		return true;
	}

	function moreterms() {
		$symbol =  current($this->lexems);
		if ($symbol["type"] == "s_plus") {

			next($this->lexems);

			$this->formula[] = array("type"=>"operation", "value"=>"+");

			if (!$this->term())
				return false;

			if (!$this->moreterms())
				return false;

		}

		if ($symbol["type"] == "s_minus") {

			next($this->lexems);

			$this->formula[] = array("type"=>"operation", "value"=>"-");

			if (!$this->term())
				return false;

			if (!$this->moreterms())
				return false;

		}

		return true;
	}

	function morefactors() {
		$symbol =  current($this->lexems);

		if ($symbol["type"] == "s_multiply") {

			next($this->lexems);

			$this->formula[] = array("type"=>"operation", "value"=>"*");

			if (!$this->factor())
				return false;

			if (!$this->morefactors())
				return false;

		}

		if ($symbol["type"] == "s_division") {

			next($this->lexems);

			$this->formula[] = array("type"=>"operation", "value"=>"/");

			if (!$this->factor())
				return false;

			if (!$this->morefactors())
				return false;

		}

		return true;
	}

	function simplefactor() {
		$symbol =  current($this->lexems);

		if ($symbol["type"] == "s_lbracket") {
			$this->formula[] = array("type"=>"operation", "value"=>"(");

			next($this->lexems);

			if (!$this->expression())
				return false;

			$symbol =  current($this->lexems);
			
			if ($symbol["type"] != "s_rbracket") {
				$this->error = "Неверный формат формулы: должна быть )";
				return false;
			}
			$this->formula[] = array("type"=>"operation", "value"=>")");

			next($this->lexems);
		}
		elseif ($symbol["type"] == "s_number") {

			$this->formula[] = array("type"=>"number", "value"=>$symbol["value"]);
			next($this->lexems);

		}
		elseif ($symbol["type"] == "s_variable") {

			$this->formula[] = array("type"=>"variable", "value"=>$symbol["value"]);
			$this->global_vars[] = $symbol["value"];

			next($this->lexems);

		}
		else {
			$this->error = "Неверный формат формулы: должно быть выражение или число или переменная $symbol[type]->$symbol[value]";
			return false;
		}
		return true;

	}

	function createxml($parameter_name) {
		global $dir;

		$file = fopen($dir."/".$parameter_name.".xml","w");
		
		if (!$file || !fwrite($file,$this->generatexml($parameter_name)))
			return false;

		fclose($file);

		return true;

	}

	function generatexml($parameter_name) {
		global $rus_name;
		global $parameter_type;
		global $display;

		$xml_str = "<function>\n";
		$xml_str .= "<name>$parameter_name</name>\n";
	
		if (!empty($rus_name))
			$xml_str .= "<rusname>$rus_name</rusname>\n";
		
		$isplan = ($parameter_type=="common")?"N":"Y";
		$xml_str .= "<isplan>$isplan</isplan>\n";
		$display = (!empty($display))?"Y":"N";
		$xml_str .= "<display>$display</display>\n";

		if (is_array($this->global_vars)) {

				foreach($this->global_vars as $k=>$v) {
					$xml_str .= "<externalparameters>\n";
					$xml_str .= "<externalparametersname>$v</externalparametersname>\n";
					$xml_str .= "</externalparameters>\n";
				}
		}

		$xml_str .= "<formula>\n";

		foreach($this->formula as $k=>$v) {
			$xml_str .= "<operator>\n";
			$xml_str .= "<type>$v[type]</type>\n";
			$xml_str .= "<value>$v[value]</value>\n";
			$xml_str .= "</operator>\n";
		}

		$xml_str .= "</formula>\n";
		$xml_str .= "</function>\n";
		return $xml_str;
	}
} 

?>