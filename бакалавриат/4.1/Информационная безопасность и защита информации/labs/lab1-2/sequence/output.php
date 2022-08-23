<?php
    function congruent_cube_generator($n) {
        // n - число записей в выходном файле, длина последовательности, которую создает генератор
        $sequence = "";
        $a0 = 0; //первое число в генерируемой последовательности
        $a = 106; $b = 1283; $c = 7; $d = 5; // 3 3 7 5

        $m = 6075; //модуль по которому будем приводить | 2
        $a_prev = $a0;
 
        for($count = 1; $count < $n + 1; $count++) {
            $a_next = $a * pow($a_prev, 3);
            $a_next = $a_next + $b * pow($a_prev, 2);
            $a_next = $a_next + $c * $a_prev;
            $a_next = $a_next + $d;
            $a_next = ($a_next % $m); //приведение результата по модулю
    
            $a_prev = $a_next;
    
            $sequence .= $a_next;
            $sequence .= ' ';
        }

        return $sequence;
    }

    function  Geffe($L1, $L2, $L3, $n) {
        $holder = "";
        for($i = 0; $i < $n; $i++) {
                $L1 = ($L1 << 1) | ((($L1 >> 29)^ ($L1 >> 28) ^ ($L1 >> 25) ^ ($L1 >> 23)) & 1);
                $L2 = ($L2 << 1) | ((($L2 >> 30)^ ($L2 >> 27)) & 1);
                $L3 = ($L3 << 1) | ((($L3 >> 31)^ ($L3 >> 30) ^ ($L3 >> 29) ^ ($L3 >> 28) ^ ($L3 >> 26) ^ ($L3 >> 24)) & 1 );
              
                $holder .= (((($L3 >> 32) & 1 )*(($L1 >> 30) & 1)) ^ (((($L3 >> 32) & 1) ^ 1) * (($L2 >> 31) & 1)) );
                $holder .= ' ';
        }
        return $holder;
    }


    function _Make64 ( $hi, $lo ) {
        // on x64, we can just use int
        if ( ((int)4294967296)!=0 )
            return (((int)$hi)<<32) + ((int)$lo);

        // workaround signed/unsigned braindamage on x32
        $hi = sprintf ( "%u", $hi );
        $lo = sprintf ( "%u", $lo );

        // use GMP or bcmath if possible
        if ( function_exists("gmp_mul") )
            return gmp_strval ( gmp_add ( gmp_mul ( $hi, "4294967296" ), $lo ) );

        if ( function_exists("bcmul") )
            return bcadd ( bcmul ( $hi, "4294967296" ), $lo );

        // compute everything manually
        $a = substr ( $hi, 0, -5 );
        $b = substr ( $hi, -5 );
        $ac = $a*42949; // hope that float precision is enough
        $bd = $b*67296;
        $adbc = $a*67296+$b*42949;
        $r4 = substr ( $bd, -5 ) +  + substr ( $lo, -5 );
        $r3 = substr ( $bd, 0, -5 ) + substr ( $adbc, -5 ) + substr ( $lo, 0, -5 );
        $r2 = substr ( $adbc, 0, -5 ) + substr ( $ac, -5 );
        $r1 = substr ( $ac, 0, -5 );
        while ( $r4>100000 ) { $r4-=100000; $r3++; }
        while ( $r3>100000 ) { $r3-=100000; $r2++; }
        while ( $r2>100000 ) { $r2-=100000; $r1++; }

        $r = sprintf ( "%d%05d%05d%05d", $r1, $r2, $r3, $r4 );
        $l = strlen($r);
        $i = 0;
        while ( $r[$i]=="0" && $i<$l-1 )
            $i++;
        return substr ( $r, $i );         
    }

    function  ANSIX($n) {
        $holder = "";
        $s = round(rand(0, 1));
        for($i = 0; $i < $n; $i++) {
                $I = crypt(strftime("%H").date("isdmY"), 'rl');
                $q = _Make64($I,round(rand(0, 1)));
                $x[$i] = $q^$s;
                $s = $x[$i]^$q; 
                $holder .= $x[$i];
                $holder .= ' ';
        }
        echo $holder;
        return $holder;
    }

    if($_POST['command-name'] == "debug") {
        $size = $_POST['Size'];
        $sequence = "";
        for($i = 0; $i < $size; $i++) {
            $sequence .= round(rand(0, 1));
            $sequence .= ' ';
        }

        // $sequence = congruent_cube_generator($size);

        // $l1 = round(rand(1, 29)); // 29
        // $l2 = round(rand(1, 30)); // 30
        // $l3 = round(rand(1, 31)); // 31
        // $sequence = Geffe($l1, $l2, $l1, $size);

        // $sequence = ANSIX($size);

        // //делается попытка создать его
        $fp = fopen("file.txt", "w");

        // записываем в файл текст
        fwrite($fp, $sequence);

        // закрываем
        fclose($fp);
    }

    function test_1($data, $len) {
        $s = 0;
        for($i = 0; $i < $len; $i++) {
            $s += 2 * $data[$i] - 1;
        }
        $result = $s / sqrt($len);
        if($result <= 1.82138636) {
            echo "<p>Частотный тест(S <= 1.82138636) - Accepted</p><p>$result <= 1.82138636</p>";
        } else {
            echo "<p>Частотный тест(S <= 1.82138636) - Falled</p><p>$result <= 1.82138636</p>";
        }
    }

    function r($first, $second) {
        if($first === $second) {
            return 0;
        } else {
            return 1;
        }
    }

    function test_2($data, $len) {
        $p = array_sum($data) / $len;
        $v = 1;
        for($i = 1; $i < $len - 1; $i++) {
            $v += r($data[$i], $data[$i + 1]);
        }
        $result = abs($v - 2 * $len * $p * (1 - $p)) / (2 * sqrt(2 * $len) * $p * (1 - $p));
        if($result <= 1.82138636) {
            echo "<p>Одинаковые биты(S <= 1.82138636) - Accepted</p><p>$result <= 1.82138636</p>";
        } else {
            echo "<p>Одинаковые биты(S <= 1.82138636) - Falled</p><p>$result <= 1.82138636</p>";
        }
    }

    function test_3($data, $len) {
        $s = array(); 
        $zero = $tmp = 0;
        for($i = 0; $i < $len; $i++) {
            $s[$i] += $tmp + 2 * $data[$i] - 1;
            $tmp = $s[$i];
            if($s[$i] == 0) {
                $zero++;
            }
        }
        $new_s = $s; 
        array_unshift($new_s, 0);
        array_push($new_s, 0);
        $new_len = count($new_s);
        for($i = 0; $i < $new_len; $i++) {
            if($new_s[$i] == 0)
                $zero++;
        }
        $l = $zero - 1;
        $j = array(
            -9 => 0,-8 => 0,-7 => 0,-6 => 0,-5 => 0,-4 => 0,-3 => 0,-2 => 0,-1 => 0,
            0 => 0, 1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0, 8 => 0, 9 => 0
        );
        
        for($i = -9; $i <= 9; $i++) {
            for($h = 0; $h < $new_len; $h++) {
                if($new_s[$h] == $i)
                    $j[$i]++;
            }
        }

        $y = array(
            -9 => 0,-8 => 0,-7 => 0,-6 => 0,-5 => 0,-4 => 0,-3 => 0,-2 => 0,-1 => 0,
            0 => 0, 1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0, 8 => 0, 9 => 0
        );

        for($i = -9; $i <= 9; $i++) {
            $y[$i] = (abs($j[$i] - $l)) / sqrt(2 * $l * (4 * abs($j) - 2));
            if($y[$i] <= 1.82138636) {
                $result = true;
            } else {
                $result = false;
                break;
            }
        }

        
        if($result) {
            echo "<p>Расширенный тест на произвольные отклонения - Accepted</p><p>$result <= 1.82138636</p>";
        } else {
            echo "<p>Расширенный тест на произвольные отклонения - Falled</p><p>$result <= 1.82138636</p>";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="css/custom.css" rel="stylesheet">
    <link href="css/options.css" rel="stylesheet">
    <title>lab1</title>
</head>
<body>
<div class="container mlogin">
    <div id="result">
        <h1><?php echo $_POST['command-name']?></h1>
        <div class="choice">
            <form action='output.php' id="sequence" method="post" name="sequence">
                <p>
                    <label for="request"> Size sequence<br>
                        <div class="form-group">
                            <input id="file" name="file" size="20" type="file">
                        </div>
                    </label>
                </p>
                <p class="submit"><input name="btn" class="button" type="submit" value="DoIt"></p>
            </form>
            <?php
                if($_POST['file']) {
                    $data = explode(" ", file_get_contents($_POST['file']));
                    $len = count($data) - 1;
                    echo '<div class=\'sequence\'><h3>sequence => size = ' . $len . '</h3>';
                    echo '<h4>';
                    for($i = 0; $i < $len; $i++) {
                        echo $data[$i] . ' ';
                    }
                    echo '</h4></div>';
                    test_1($data, $len);
                    test_2($data, $len);
                    test_3($data, $len);
                }
            ?>
            <a href="choice.php" name="btn" class="button">Choice Size</a>
        </div>
    </div>
</div>

</body>
</html>