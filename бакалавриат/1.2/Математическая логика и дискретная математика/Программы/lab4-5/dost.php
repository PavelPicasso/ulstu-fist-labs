<form method="post" action="dost.php">
    Матрица достижимости<br>
    <input name="size" type="text" placeholder="n"><br>
    Введите матрицу nxn:<br><textarea style="resize: none" rows="20" cols="50" name="matrix"><?= htmlspecialchars($_POST['matrix']) ?></textarea><br>
    <input type="submit" style="width: 300px;margin: 25px" value="Do it">
    <br><?php echo "n = ", $_POST['size']; ?>
</form>

<?php
define("SIZE", trim($_POST['size']), true);

if (isset($_POST['matrix'])) {
    $matrix = htmlspecialchars(trim($_POST['matrix']));
    $matrix = preg_replace('# {2,}#', ' ', $matrix);
    $matrix = preg_replace('#(?:\r?\n){2,}#', "\r\n", $matrix);
    $mas = explode("\r\n", $matrix);

    for ($i = 0; $i < count($mas); $i++) {
        $mas[$i] = trim($mas[$i]);
        $elem[$i] = explode(" ", $mas[$i]);
    }
    echo "Введенная матрица M", "<br>";
    for ($i = 0; $i < count($elem); $i++) {
        for ($j = 0; $j < count($elem[$i]); $j++) {
            echo($elem[$i][$j]);
            echo " ";
        }
        echo "<br>";
    }
    dost($elem);
}
function dost($elem) { //ищем матрицу достижиомости: возводиим исходную смежности в степень пока степень не будет равна количеству ребер и проводим между полученными матрциами операцию дизъюнкции
    for ($i = 0; $i < SIZE; $i++) {
        for ($j = 0; $j < SIZE; $j++) {
         $res[$i][$j] = 0;
        }
    }


//    echo "res", "<br>";
//    for ($i = 0; $i < SIZE; $i++) {
//        for ($j = 0; $j < SIZE; $j++) {
//            echo($res[$i][$j]);
//            echo " ";
//        }
//        echo "<br>";
//    }


    for ($i = 0; $i < SIZE; $i++) {
        for ($j = 0; $j < SIZE; $j++) {
            if ($i == $j){
                $new_matrix[$i][$j] = 1;
            } else {$new_matrix[$i][$j] = 0;}
        }
    }

//
//    echo "new_matrix", "<br>";
//    for ($i = 0; $i < SIZE; $i++) {
//        for ($j = 0; $j < SIZE; $j++) {
//            echo($new_matrix[$i][$j]);
//            echo " ";
//        }
//        echo "<br>";
//    }
//    echo "<br>";
echo "<br>";


$d = 1;
    for ($i = 0; $i < SIZE; $i++) {
        echo "M^",$d;echo "<br>";
        $new_matrix = matrix_multiply($new_matrix, $elem);
        //$res = $res OR $new_matrix;
        $res = matrix_sum($res, $new_matrix);
        $d++;
    }

    echo "Результат", "<br>";
    for ($i = 0; $i < SIZE; $i++) {
        for ($j = 0; $j < SIZE; $j++) {
            echo($res[$i][$j]);
            echo " ";
        }
        echo "<br>";
    }
    //return $res;
}

function matrix_multiply($new_matrix, $elem) {
    $product = array(); // Создаём массив значений
    $row = count($new_matrix); // Количество строк в первой матрице
    for ($z=0; $z < 1; $z++) {
        $col = count($elem[$z]); // Количество строк во втрой матрице
    }
    for ($i=0; $i < $row; $i++) {
        for ($j=0; $j < $col; $j++) {
            for ($k=0; $k < $col; $k++) {
                $product[$i][$j] += $new_matrix[$i][$k] * $elem[$k][$j];
            }
            echo $product[$i][$j] , " ";
        }
        echo "<br>";
    }
    echo "<br>";
    return $product;
}

function matrix_sum($res, $new_matrix) {
    for ($i = 0; $i < count($res); $i++) {
        for ($j = 0; $j < count($res[$i]); $j++) {
            if ($res[$i][$j] == 1 || $new_matrix[$i][$j] == 1){
                $res[$i][$j] = 1;
            } else {$res[$i][$j] = 0;}
        }
    }
    return $res;
}












function validation($elem) {
    $res = 1;
    for ($i = 0; $i < count($elem); $i++) {
        if (count($elem) != count($elem[$i])) {
            $res = 'Матрица не квадратная';
        }
    }

    for ($i = 0; $i < count($elem); $i++) {
        for ($j = 0; $j < count($elem); $j++) {
            if ($elem[$i][$j] == '0' || $elem[$i][$j] == '1') {
            } else {
                $res = 'Матрица не соотвествует бинарному виду';
                return $res;
            }
        }
    }
    return $res;
}

?>