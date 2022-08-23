<?
if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	if (!empty($_POST["CodeOfEvent"])) {
		include ("../SQLFunc.php");
		$db = DataBase::getDB();

		// получение списка студентов
		if (!empty($_POST["CodeOfStudentGroup"])) {
			// группы
			$q = "select students.CodeOfStudent, students.StudentName ".
				"from students, studentgroups ".
				"where students.CodeOfStudentGroup = studentgroups.CodeOfStudentGroup ".
				"and studentgroups.CodeOfStudentGroup = {?} ".
				"order by students.StudentName asc";
			$resStudents = $db->select($q, array($_POST["CodeOfStudentGroup"]));
		} elseif (!empty($_POST["Kurs"])) {
			// курса и факультета
			$q = "select students.CodeOfStudent, students.StudentName ".
				"from students, studentgroups, streams, department ".
				"where students.CodeOfStudentGroup = studentgroups.CodeOfStudentGroup ".
				"and studentgroups.CodeOfStream = streams.CodeOfStream ".
				"and streams.Kurs = {?} ".
				"and studentgroups.CodeOfDepart = department.CodeOfDepart ".
				"and department.CodeOfFaculty = {?} ".
				"order by students.StudentName asc";
			$resStudents = $db->select($q, array($_POST["Kurs"], $_POST["CodeOfFaculty"]));
		} elseif (!empty($_POST["CodeOfFaculty"])) {
			// факультета
			$q = "select students.CodeOfStudent, students.StudentName ".
				"from students, studentgroups, department ".
				"where students.CodeOfStudentGroup = studentgroups.CodeOfStudentGroup ".
				"and studentgroups.CodeOfDepart = department.CodeOfDepart ".
				"and department.CodeOfFaculty = {?} ".
				"order by students.StudentName asc";
			$resStudents = $db->select($q, array($_POST["CodeOfFaculty"]));
		} else {
			// все студенты
			$q = "select students.CodeOfStudent, students.StudentName ".
				"from students ".
				"order by students.StudentName asc";
			$resStudents = $db->select($q);
		}

		// степени участия
		$q = "select CodeOfEventPartDegree, DegreeName from eventpartdegree order by RatingNorm asc";
		$partsDegree = $db->select($q);

		$html = "";
		foreach ($resStudents as $student) {
			$q = "select CodeOfEventPartDegree ".
				"from studentevents ".
				"where CodeOfStudent = {?} and CodeOfEvent = {?}";
			$studentPartDegree = $db->selectCell($q, array($student["CodeOfStudent"], $_POST["CodeOfEvent"]));
			$html .= '<tr>
						<td>'.$student["StudentName"].'</td>
						<td>
							<select class="form-control" name="StudentPartDegree['.$student["CodeOfStudent"].']">
								<option value=""></option>';
								foreach ($partsDegree as $degree) {
									$selected = "";
									if ($degree["CodeOfEventPartDegree"] == $studentPartDegree) {
										$selected = " selected";
									}
			$html .=				'<option value="'.$degree["CodeOfEventPartDegree"].'"'.$selected.'>'.$degree["DegreeName"].'</option>';
								}
			$html .=		'</select>
						</td>
					</tr>';
		}
		echo json_encode(array("html" => $html/*, "students" => $resStudents*/));
	}
}
?>