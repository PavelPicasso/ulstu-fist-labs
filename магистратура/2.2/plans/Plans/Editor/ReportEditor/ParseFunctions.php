<?php
function ScanFormula($parameter) {
	global 	$letters; 

	$lexems = array();

	for ($i=0; $i<strlen($parameter); $i++) {
		//������������� ��� �������
		$ch = substr($parameter, $i, 1);
		
		if (is_numeric($ch)){
			//���� ��� �����

			$number = "";
			$point_count = 0;
			while (is_numeric($ch) || ($ch == "."&& $point_count==0)) {
				//���������� ��������� ����� ���� ��� ������� �� ����, ����� ������������� ��������� �����  "."
				if ($ch == ".")
					$point_count++;

				$number .=  $ch;
				$i++;
				$ch = substr($parameter, $i, 1);
			}
			$lexems[] = array ("type"=>"s_number", "value"=>$number);
			$i--; //����� �� 1 ������

		} //if is_numeric
		elseif ($ch == "-") {
			$lexems[] = array ("type"=>"s_minus", "value"=>"-");
		}
		elseif ($ch == "+") {
			$lexems[] = array ("type"=>"s_plus", "value"=>"+");
		}
		elseif ($ch == "*") {
			$lexems[] = array ("type"=>"s_multiply", "value"=>"*");
		}
		elseif ($ch == "/") {
			$lexems[] = array ("type"=>"s_division", "value"=>"/");
		}
		elseif ($ch == "(") {
			$lexems[] = array ("type"=>"s_lbracket", "value"=>"(");
		}
		elseif ($ch == ")") {
			$lexems[] = array ("type"=>"s_rbracket", "value"=>")");
		}
		elseif(in_array($ch, $letters)) {
			$variable = "";
			while (is_numeric($ch) || in_array($ch, $letters)) {
				$variable .=  $ch;
				$i++;
				$ch = substr($parameter, $i, 1);
			}
			$lexems[] = array ("type"=>"s_variable", "value"=>$variable);
			$i--; //����� �� 1 ������
		}
		else
			return false;

	}

	return $lexems;

}

function CheckVarName($varname) {
	global 	$letters; 

	if (!in_array(substr($varname, 0, 1), $letters))
		return false;

	for ($i=0; $i<strlen($varname); $i++) {
		$ch = substr($varname, $i, 1);
		if (!is_numeric($ch) && !in_array($ch, $letters)) 
			return false;
	}

	return true;
}

?>