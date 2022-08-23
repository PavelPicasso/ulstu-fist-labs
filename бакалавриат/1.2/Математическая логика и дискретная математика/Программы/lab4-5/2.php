<form method="post" action="2.php">
    Graf <br>
    <input name="size" type="text" placeholder="n = Колличество вершин">
    <?php
    if (isset($_POST['button'])) {
        if (is_numeric($_POST['size'])) {
            echo 'в поле содержится число</br>';
        } else {
            die();
        }
    }
    ?>
    <br>Инициализация вершин начинается с 0 и до n - 1<br>
    <input name="top" size="25" type="text" placeholder="В какой верщине мы находимся?"><?php echo "a = ", $_POST['top']; ?>
    <br>
    <input name="next" size="25" type="text" placeholder="В какую верщину мы идем?"><?php echo "b = ", $_POST['next']; ?>
    <br><br>
    Инициализация матрицы [<?= $_POST['size'] ?>][<?= $_POST['size'] ?>](весов графа)
    <p><textarea name="links" rows="9" cols="35"
                 style="resize: none;"><?= htmlspecialchars($_POST['links']) ?></textarea></p>
    <p><input type="submit" name="button" value="сделать"/></p>
</form>
<?php //echo "n = ", $_POST['size']; ?><!--<br>-->

<?php
if (isset($_POST['button'])) {
//    echo "Квадратная матрица[", $_POST['size'], "][", $_POST['size'], "] для хранения весов графа", "<br/>";
    define("SIZE", $_POST['size'], true);
    define("Top", $_POST['top'], true);
    define("Next", $_POST['next'], true);

    $b = preg_split("/[\r, ]/", $_POST['links']);
    $a = array_chunk($b, SIZE);
    $d[SIZE]; // минимальное расстояние
    $v[SIZE]; // посещенные вершины


// Вывод матрицы связей
//    for ($i = 0; $i < SIZE; $i++) {
//        for ($j = 0; $j < SIZE; $j++) {
//            echo $a[$i][$j], " ";
//        }
//        echo "<br/>";
//    }
    echo "<br/>";
    for ($k = Top; $k <= Top; $k++) {
        //Инициализация вершин и расстояний
        for ($i = 0; $i < SIZE; $i++) {
            $d[$i] = 10000;
            $v[$i] = 1;
        }
        $d[$k] = 0;
        $minindex = 0;
        // Шаг алгоритма
        do {
            $minindex = 10000;
            $min = 10000;
            for ($i = 0; $i < SIZE; $i++) { // Если вершину ещё не обошли и вес меньше min
                if (($v[$i] == 1) && ($d[$i] < $min)) { // Переприсваиваем значения
                    $min = $d[$i];
                    $minindex = $i;
                }
            }
            // Добавляем найденный минимальный веск текущему весу вершины и сравниваем с текущим минимальным весом вершины
            if ($minindex != 10000) {
                for ($i = 0; $i < SIZE; $i++) {
                    if ($a[$minindex][$i] > 0) {

                        //echo $a[$minindex][$i]," ", $min,"|";

                        $temp = $min + $a[$minindex][$i];
                        if ($temp < $d[$i]){
                            $d[$i] = $temp;
//                            $mas[$i] = $minindex;
//                            echo $mas[$i];
                        }
                    }
                }
                $v[$minindex] = 0;
                if ($v[$minindex] == $v[Next - 1]){break;}
            }
        } while ($minindex < 10000);
        echo "</br>";
        for ($i = Next - 1; $i < Next; $i++) {
            echo "Минимальное расстояние от ", $k , " до ", $i + 1, ":", $d[$i + 1];
            echo "<br/>";
        }
    }
}
?>
