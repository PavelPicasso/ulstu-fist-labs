<form method="post" action="index.php">
    Введите отношения в виде двух пар элементов, такого вида: (_,_),(_,_)...<br>
    Отношение <input name="arr1" type="text" placeholder="Пара элементов">

    <input type="submit" name="button" value="Do it">
</form>

<?php
    ?><br><?
    $arr1_text = $_POST['arr1'];
    echo "Отношение: ";
    echo $arr1_text."<br>";

    $arr1_txt = explode(",", $arr1_text );
    $id1=0;
    $id2=0;
    for ($i = 0; $i < count( $arr1_txt) ; $i++ ) {
        if ($i % 2 != 0) {
            $arr2[$id2]=substr($arr1_txt[$i], 0, -1);
            $id2++;
        } else {
            $arr1[$id1]=substr($arr1_txt[$i],1);
            $id1++;
        }
    }

    if (Valid($arr1_text,$arr1_txt)) {
        echo "Valid"; ?><br><?; ?><br><?;
        echo "x&#1013;A (xRx);\n(x,x)&#1013;R"; ?><br><?;
        refleks($arr1, $arr2); ?><br><? ?><br><?;
        echo "(xRy)->(yRx);\n(xRy) и (yRx) -> (x=y);\n(x,y)&#1013;R и (y,x)&#1013;R"; ?><br><?;
        simmetr($arr1, $arr2); ?><br><? ?><br><?;
        echo "(x,y)&#949;R -> (y,x)&#8713;R, если x=y"; ?><br><?;
        kossimmetr($arr1, $arr2); ?><br><? ?><br><?;
        echo "(x,y)&#949;R и (y,z)&#949;R, то (x,z)&#949;R"; ?><br><?;
        tranz($arr1, $arr2);
    } else {
        echo "False: not Valid";
    }



function refleks($arr1,$arr2) {

    $sum = 0;
    for ( $i = 0; $i < count( $arr1) ; $i++ ) {
        for ( $j = 0 ; $j < count( $arr1); $j++ ) {
            if ($arr1[$i] == $arr1[$j] && $arr1[$j] == $arr2[$j]) {
                $sum++;
                break;
            }
        }
    }
    for ( $i = 0; $i < count( $arr2) ; $i++ ) {
        for ( $j = 0 ; $j < count( $arr1); $j++ ) {
            if ($arr2[$i] == $arr2[$j] && $arr2[$j] == $arr1[$j]) {
                $sum++;
                break;
            }
        }
    }
    if  ($sum == 2 * count($arr1)){
        echo 'Рефлексивно';
    } else {
        echo 'not Рефлексивно';
    }
}
function simmetr($arr1,$arr2) {

    $sum = 0;
    for ( $i = 0; $i < count( $arr1) ; $i++ ) {
        for ( $j = 0 ; $j < count( $arr1); $j++ )
            if ($arr1[$i] == $arr2[$j] && $arr1[$j] == $arr2[$i]){
                $sum++;
                break;
            }
    }
    if  ($sum == count($arr1)){
        echo 'Симметрично';
    } else {
        echo 'not Симметрично';
    }

}

function kossimmetr($arr1,$arr2) {

    $sum = 0;
    for ( $i = 0; $i < count( $arr1) ; $i++ ) {
        for ( $j = 0 ; $j < count( $arr1); $j++ )
            if ($arr1[$i] !== $arr2[$j] && $arr1[$j] !== $arr2[$i]){
                $sum++;
                break;
            }
    }
    if  ($sum == count($arr1)){
        echo 'Коссосимметрично';
    } else {
        echo 'not Коссосимметрично';
    }

}

function tranz($arr1,$arr2) {
    $bool = true;
    for ( $i = 0; $i < count( $arr1) ; $i++ ) {
        for ( $j = 0 ; $j < count( $arr1); $j++ ) {
            if ($arr2[$i] == $arr1[$j]) {
                $boll = false;
                for ( $k = 0 ; $k < count( $arr1); $k++ ) {
                    if ($arr1[$i] == $arr1[$k] && $arr2[$k] == $arr2[$j]) {
                        $kr1 = true;
                    }
                }
            }
            if ($bool == false) {
                break;
            }
        }
        if ($bool==false) {
            break;
        }
    }
    if  ($bool == true) {
        echo 'Транзитивно';
    } else {
        echo 'not Транзитивно';
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