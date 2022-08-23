<form method="post" action="index.php">
    Введите отношения в виде двух пар элементов, такого вида: (_,_),(_,_)...<br>
    Множество A <textarea name="arr2" rows="1" cols="25" ><?= htmlspecialchars($_POST['arr2']) ?></textarea><br>
    Множество B <textarea name="arr3" rows="1" cols="25" ><?= htmlspecialchars($_POST['arr3']) ?></textarea><br>
    Отношение R <textarea name="arr1" rows="1" cols="25" ><?= htmlspecialchars($_POST['arr1']) ?></textarea><br>
    <input type="submit" name="button" value="сделать">
</form>
<?php
    ?><br><?
    $arr2_text = $_POST['arr2'];
    echo "Множество A: ";
    echo $arr2_text."<br>";
    $arr3_text = $_POST['arr3'];
    echo "Множество B: ";
    echo $arr3_text."<br>";
    $arr1_text = $_POST['arr1'];
    echo "Отношение: ";
    echo $arr1_text."<br>";
    $arr1_txt = explode(",", $arr1_text );
    $id1=0;
    $id2=0;
    for ($i = 0; $i < count( $arr1_txt) ; $i++ ) {
        if ($i % 2 != 0) {
            $arr2[$id2] = substr($arr1_txt[$i], 0, -1);
            $id2++;
        } else {
            $arr1[$id1] = substr($arr1_txt[$i],1);
            $id1++;
        }
    }
if (Valid1 ($arr2_text) && Valid1 ($arr3_text)) {
    if (Valid($arr1_text, $arr1_txt)) {
        $arr2_text = substr($arr2_text, 0, -1);
        $arr2_text = substr($arr2_text, 1);
        $A1 = explode(",", $arr2_text);
        echo "валид"; ?><br><?
        Check($arr1, $A1);
    } else {
        echo "не прошла валидацию";
    }
} else {
        echo "не валид";
}
function Check ($arr1,$A1) {
    $mas[] = null;
    $mas = array_count_values($A1);
    $mas1[] = null;
    $mas1 = array_count_values($arr1);
    $i = 0;
    $bool = 0;
    for ($i = 0; $i < count( $A1); $i++) {
        if ($mas[$A1[$i]] == $mas1[$arr1[$i]]) {
            $bool = 1;
        } else {
            $bool = 0;
            echo "не функция";
            break;
        }
        $i++;
    }
    if ($bool === 1) {
        echo "функция";
    }
}
function Valid1 ($arr2_text) {
        if ($arr2_text[strlen($arr2_text) - 1] == ")" && $arr2_text[0] == "(") {
            return true;
        } else {
            return false;
        }
}
function Valid ($arr,$arr1_txt) {
    $count1 = substr_count($arr,'(');
    $count2 = substr_count($arr,',');
    $count3 = substr_count($arr,')');
    if ($count2 == count($arr1_txt) - 1 && (2 * $count3) == count($arr1_txt) && (2 * $count1) == count($arr1_txt)) {
        return true;
    } else {
        return false;
    }
}
?>