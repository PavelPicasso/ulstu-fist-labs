<?php
    class CRSA {
 
    // Ключи шифрования и дешифровки
    private $e=0x0;
    private $d=0x0;
    private $n=0x0;
 
    // Расшифрованая строка и вектор с зашифрованными символами
    private $decriptedData="";
    private $encriptedData=array();
 
    // Реализация расширеного алгоритма Эвклида для поиска
    // ключа дешифрования
    function generateDecripringKey() {
        $str = shell_exec('C:\Users\Pavel\AppData\Local\Programs\Python\Python36-32\python.exe C:\Users\Pavel\Anaconda3\Lib\site-packages\number.py');
        $date = explode(" ", $str);
        $this->e = $date[0];
        $this->n = $date[1];
        $this->d = $date[2];
    }
 
    // Ну тут даже пояснить ничего не надо
    function getEncripringKey() {
        return $this->e;
    }
 
    // Тут тоже
    function getDecriptingKey() {
        return $this->d;
    }
 
    // Тут тоже
    function getN() {
        return $this->n;
    }
 
 
    // Ф-я шифрования, может работать опираясь как на ранее 
    // ининциированные свойства, так и напрямую от параметров -   
    // ключа шифрации и занчения n(p,q);
    function EncriptX($data, $e=0x0, $n=0x0)
    {
        if ($e>0x0 && $n>0x0)
        {
            $this->n=$n;
            $this->e=$e;
        }
 
        for ($j=0x0; $j<strlen($data); $j++)
        {
            $b=ord($data[$j]);
            $result = 1;
            for($i=0x0; $i<$this->e; $i++)
            {
                $result = ($result*$b) % $this->n;
            }
            $this->encriptedData[$j]=$result;
            echo $this->encriptedData[$j];
        }
    }
 
    // Аналогично ф-ии шифрования
    function DecriptX($d=0x0, $n=0x0)
    {
        if ($d>0x0 && $n>0x0)
        {
            $this->d=$d;
            $this->n=$n;
        }
 
        $result = 1;
        for ($j=0x0; $j<count($this->encriptedData); $j++)
        {
            $b=($this->encriptedData[$j]);
            $result = 1;
            for($i=0x0; $i<$this->d; $i++)
            {
                $result = ($result*$b) % $this->n;
            }
            $this->decriptedData .= chr($result);
        }
 
        return $this->decriptedData;
    }

    function isPrime($n, $accuracy) {

        //if the given number is 2 or 3, then return 1, because is obvious!
        if($n == 2 || $n == 3) {
            return 1;
        }

        //if the given number is - or even, then return 0!
        if($n<=1 || !($n&1)) {
            return 0;
        }


        //first steps we must to write n-1 as 2^s*d, so s=? and d=?
        $s = 0;
        //step 1, compute exponent of two 2^s
        for($m=$n-1;!($m&1);$s++,$m >>=1);

        $exponent = $s;

        //step 2, compute d of 2^s*d that have to be odd
        $d = ($n-1)/(1<<$s);

        for($i = 1; $i <= $accuracy; $i++) {
        //pick a random number as possible witness for compositeness of n

        $possibleWitness = $this->pick_random(2, $n - 2);
        if($this->isWitness($possibleWitness, $exponent, $d, $n)) {
            //return composite
            return 0;
        }
        }
        //probably n is prime
        return 1;
    }

    function pick_random($a,$b) {
        $c = (rand()%($b-$a)) + $a;
        return $c;
    }

    function isWitness($possibleWitness, $exponent, $d, $p) {


        $possibleWitness = $this->modexp($possibleWitness, $d, $p);

        // if the possible witness is congruent with 1 and -1,
        // then go to next iteration and
        // pick up another possible witness for compositeness of n
        if($possibleWitness == 1 || $possibleWitness == ($p - 1)){
        return 0;
        }

        //possibleWitness = 2^r(0..s)*d
        for($r = 0; $r < $exponent; $r++) {
            $possibleWitness = $this->modexp($possibleWitness, 2, $p);

            if($possibleWitness == 1) {
            //return composite
            return 1;

            }

            if($possibleWitness == ($p-1)) {
            //return nextIteration
            return 0;
            }
        }
        //return composite
        return 1;
    }

    function modexp($x, $y,$mod) {

        $sol = 1;

        for($i = 0; (1<<$i) <= $y; ++$i) {
            if( ((1<<$i)&$y) > 0 ) {
                $sol = ($sol * $x) % $mod;
            }
            $x = ($x * $x ) % $mod;
        }
        return $sol;
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
            <?php
                if($_POST['command-name'] == "debug") {
                    $text = $_POST['text'];
                    $fp = fopen("text.txt", "w");
                    fwrite($fp, $text);
                    fclose($fp);

                    $output_array = shell_exec('C:\Users\Pavel\AppData\Local\Programs\Python\Python36-32\python.exe C:\OpenServer\domains\RSA\number.py');
                    // var_dump(explode("/", $output_array));
                    $output = explode("/", $output_array);
                    foreach ($output as $o) {
                        if(isset($o))
                            echo "<p> $o </p>";
                    }
                    // $rsa = new CRSA();
                    // echo $rsa->isPrime(32,3);
                    // $rsa->generateDecripringKey();
                    // echo '<br>';
                    // $rsa->EncriptX($_POST['text'], $rsa->getEncripringKey(), $rsa->getN());
                    // echo "<br>";
                    // echo $rsa->DecriptX($rsa->getDecriptingKey(), $rsa->getN());
                    // echo "<br>";
                }
            ?>
            <a href="choice.php" name="btn" class="button">Choice Size</a>
        </div>
    </div>
</div>

</body>
</html>