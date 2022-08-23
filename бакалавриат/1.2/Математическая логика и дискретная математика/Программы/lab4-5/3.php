<form method="post" action="3.php">
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
    define("Top", $_POST['top']-1, true);
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
    if(Top+1>SIZE || Top<0){ //Проверка ввода
        echo "Такого города не существует";
        return 0;
    }
//Инициализация вершин и расстояний
    for($i=0;$i<SIZE;$i++){
        $d[$i]=10000;
        $v[$i]=0;
        $prev[$i]=-1;//матрица предков
    }
    $d[Top]=0;


// Шаг алгоритма
    do {
        $minindex=10000;
        $min=10000;
        for($i=0;$i<SIZE;$i++){
            if ($v[$i]==0 && $d[$i]<$min){
                $min=$d[$i];
                $minindex=$i;
            }
        }
        // Добавляем найденный минимальный веск текущему весу вершины и сравниваем с текущим минимальным весом вершины
        if($minindex != 10000){
            for($i=0;$i<SIZE;$i++){
                if ($a[$minindex][$i] > 0) {
                    $temp = $min + $a[$minindex][$i];
                    //echo $a[$minindex][$i]," ", $min,"|";
                    if ($temp < $d[$i]){
                        $d[$i] = $temp;
                        $prev[$i]=$minindex;//Запоминаем предка посещенного города
                    }
                }
            }
            $v[$minindex]=1;
        }
    } while ($minindex<10000);



    echo "Длина пути из города ";
    echo Top+1 . " до ", Next;
    echo "<br><br>";
    for($z=Next - 1;$z<Next;$z++){
        if($d[$z]!=10000){
            echo "Расстояние из города ";
            echo Top+1 ." до города ";
            echo $z+1 ." = ";
            echo $d[$z];
            echo " Путь : ";

            $finalpath = array();
            $prev[Top]=Top;
            $k=0;

            $index=$z;

            echo Top+1;
            echo " => ";
            //echo "|",$prev[$index],"|";
            while($prev[$index]!= Top){
                $finalpath[$k]=$prev[$index]+1;
                $index=$prev[$index];
                $k++;
            }

            for($j=count($finalpath)-1;$j>=0;$j--){
                echo $finalpath[$j];
                echo " => ";
            }
            echo $z+1;

            echo "<br>";
        }
    }
}
?>