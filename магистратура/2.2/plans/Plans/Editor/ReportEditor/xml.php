<?php
class xml  {
    var $parser;
    var $depth;

    function xml()
    {
        $this->parser = xml_parser_create();

        xml_set_object($this->parser, &$this);
/*        xml_set_element_handler($this->parser, "tag_open", "tag_close");
        xml_set_character_data_handler($this->parser, "cdata");*/
    }

    function parse($data)
    { 
	$this->depth = 0;
        xml_parse($this->parser, $data);
    }


    function parse_tag($vals, $tag_start=0, $i=0) 
    {
	$parsed = array();
	if ($tag_start != 0)
		$tag_name = $vals[$tag_start-1]["tag"];
	else
		$tag_name = '';
	for($i = $tag_start; $i<count($vals); $i++) {
		if ($vals[$i]["type"] == "open") {
			$parsed[$vals[$i]["tag"]][] = $this->parse_tag($vals, $i+1, &$i);
		}
		elseif ($vals[$i]["type"] == "complete")
			$parsed[$vals[$i]["tag"]] = $vals[$i]["value"];
		elseif ($vals[$i]["type"] == "close" && $vals[$i]["tag"] == $tag_name)
			return $parsed;
	}
	return $parsed;
    }

    function parse_struct($data)
    { 
		$this->depth = 0;
    
		if (xml_parse_into_struct($this->parser, $data, $vals, $index)) {
			$parsed = $this->parse_tag($vals);
			return $parsed;
		}
		else {
			return false;
		}
    }

    function tag_open($parser, $tag, $attributes)
    { 
		for ($i=0; $i < $this->depth; $i++) {
			echo "\t";
		}
		echo "+$tag\n";
		$this->depth++;
#        var_dump($parser, $tag, $attributes); 
		
    }

    function cdata($parser, $cdata)
    {
		for ($i=0; $i < $this->depth; $i++) {
			echo "\t";
		}
		echo "[$cdata]\n";
#        var_dump($parser, $cdata);
    }

    function tag_close($parser, $tag)
    {
		$this->depth--;
		for ($i=0; $i < $this->depth; $i++) {
			echo "\t";
		}
		echo "-$tag\n";

#        var_dump($parser, $tag);
    }

} 

?>
