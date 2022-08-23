<?php

function f_plan1() {
	global $lec;
	global $wc;
	global $test2;
	return $lec*$wc-$test2;
}


function f_test2() {
	return 54+8*5;
}


function f_test3() {
	return 2+3*(4-2);
}


function f_test_visit() {
	global $lab;
	global $wc;
	return $lab*$wc;
}


function f_test_work_review() {
	return 12+5*4;
}

?>