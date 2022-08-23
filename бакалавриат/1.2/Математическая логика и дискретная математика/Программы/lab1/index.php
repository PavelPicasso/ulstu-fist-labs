<form method="post" action="index.php">
Множество имеет такой вид : bci , где b-буква, i-четная цифра, j- нечетная цифра<br>Элементы множества вводить через пробел <br>
    <input name="arr1" type="text" placeholder="Массив 1">
    <input name="arr2" type="text" placeholder="Массив 2">
    <input type="submit" name="button" value="Do it">
</form>

<?php
require('../lab1/simple_html_dom.php');
/*
    ?><br><? $arr1_text = $_POST['arr1'];echo "math A: ";echo $arr1_text."<br>";$arr1 = explode(" ", $arr1_text);
    $arr2_text = $_POST['arr2'];echo "math B: ";echo $arr2_text."<br>";$arr2 = explode(" ", $arr2_text);
function arrayUnique($arr3, $size) {
    for ($counter1 = 0; $counter1 < $size; $counter1++) {
        for ($counter2 = $counter1 + 1; $counter2 < $size ; $counter2++) {
            if ( $arr3[$counter1] == $arr3[$counter2] ) {
                for ($counter_shift = $counter2; $counter_shift < $size -1; $counter_shift++) {
                    $arr3[$counter_shift] = $arr3[$counter_shift + 1];
                }
                $size -= 1;
                if ($arr3[$counter1] == $arr3[$counter2]) {
                    $counter2--;
                }
            }
        }
    }
    for ($counter1 = 0; $counter1 < $size; $counter1++) {
        echo $arr3[$counter1] ," ";
    }
}
    function numb1($arr1,$arr2) {
        $n = count($arr1);
        for ($i = 0;$i < $n;$i++) {
            $arr3[$i] = $arr1[$i];
        }
        $j = 0;
        $n1 = count($arr2);
        for ($i = 0;$i < $n1;$i++) {
                $j++;
                $arr3[$n + $j] = $arr2[$i];
        }
        $long = $n + $n1 + 2;
        arrayUnique($arr3,$long);
    }
    function numb2($arr1, $arr2) {
        $n = count($arr1);
        $n1 = count($arr2);
        for ($i = 0;$i < $n;$i++) {
            for ($j = 0; $j < $n1;$j++) {
                if ($arr1[$i] == $arr2[$j]) {
                    $arr3[$i] = $arr1[$i];
                }
            }
        }
        $size = $n + $n1 + 1;
        echo "A&cap;B: ";
        for ($counter1 = 0; $counter1 < $size; $counter1++) {
            echo $arr3[$counter1] ," ";
        }
    }

    function numb3($arr1,$arr2) {
        $n = count($arr1);
        $n1 = count($arr2);
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n1; $j++) {
                if ($arr1[$i] !== $arr2[$j]) {
                    $kr = true;
                } else {$kr = false;break;}
            }
            if ($kr == true) {
                echo $arr1[$i], " ";
            }
        }
    }
    function numb4($arr1,$arr2) {
        return  numb1(numb3($arr1,$arr2),numb3($arr2,$arr1));
    }
    function chet($num) {
        if($num % 2 == 0){return true;
        } else {return "Error: Number is not valid.";}
    }
    function Valid ($arr1) {
        for ($i = 0; $i < count($arr1); $i++) {
            if(strlen($arr1[$i]) == 3) {
                if (preg_match('@[A-z]@u',$arr1[$i][0])) {
                    if (preg_match('@[0-9]@u',$arr1[$i][1])) {
                        if ($arr1[$i][2] % 2 == 0) {
                            return true;
                        } else {
                            echo "False: chet \n";
                            break;
                        }
                    } else {
                        echo "False: Number \n";
                        break;
                    }
                } else {
                    echo "False: not Буква \n";
                    break;
                }
            } else {
                if (strlen($arr1[$i]) % 3 == 0) {
                    $k = 0;
                    $m = 1;
                    $n = 2;
                    for($j = 0; $j < strlen($arr1[$i]) / 3; $j++) {
                        if (preg_match('@[A-z]@u',$arr1[$i][$k])) {
                            if (preg_match('@[0-9]@u',$arr1[$i][$m])) {
                                if ($arr1[$i][$n] % 2 == 0) {
                                    return true;
                                } else {
                                    echo "False: chet \n";
                                    break;
                                }
                            } else {
                                echo "False: Number \n";
                                break;
                            }
                        } else {
                            echo "False: not Буква \n";
                            break;
                        }
                        $k = $k + 3;
                        $m = $m + 3;
                        $n = $n + 3;
                    }
                } else {
                    echo "Element not valid";
                    break;
                }
            }
        }
    }
    if (Valid($arr1)) {
        if (Valid($arr2)) {
            echo "A&cup;B: ";
            numb1($arr1,$arr2); ?><br><?
            numb2($arr1,$arr2); ?><br><?
            echo "A\\B: ";
            print_r(numb3($arr1,$arr2)); ?><br><?
            echo "A&#9651;B: ";
            print_r(numb4($arr1,$arr2)); ?><br><?
        }
    }
*/
function currentweektype(){
    $today = date_create(date('Y-m-d'));
    $studyBeginning = date_create('2018-02-05');
    $interval = date_diff($today, $studyBeginning);

    $days = $interval->days;
    $days = intdiv($days, 7);

    if ($days % 2 == 0){
        return 1;
    }
    else{
        return 2;
    }
}

function today() {
    $today = date('l');
    switch($today){
        case 'Monday':
            return 'Пнд';
            break;
        case 'Tuesday':
            return 'Втр';
            break;
        case 'Wednesday':
            return 'Срд';
            break;
        case 'Thursday':
            return 'Чтв';
            break;
        case 'Friday':
            return 'Птн';
            break;
        case 'Saturday':
            return 'Сбт';
            break;
        case 'Sunday':
            return 'nioh';
            break;
    }
}
function extract_true_tr_from_garbage($s) {
    $tr_starting_pos = strpos($s, "<tr>");
    $tr_closing_pos = strpos($s, "</TR>");
    return substr($s, $tr_starting_pos + 4, $tr_closing_pos - $tr_starting_pos - 5);
}



$html = file_get_html('http://www.ulstu.ru/schedule/students/raspisan.htm');

$day = today();
$group = 'ИВТАПбд-21';
global $group_href;

foreach($html->find('a') as $element){
    if ($element->plaintext == $group) {
        $group_href = $element->href;
        break;
    }
}

$html->clear();
unset($html);

$table = file_get_html('http://www.ulstu.ru/schedule/students/' . $group_href);

$flag = currentweektype();

foreach($table->find('tr') as $element){
    $table_day = strip_tags($element->find('td p', 0));
    if ($day === $table_day) {
        $pair = extract_true_tr_from_garbage($element);
        if ($flag == 1) {
            echo $pair;
            break;
        } else {
            $flag--;
        }
    }
}

$table->clear();
unset($table);


?>




