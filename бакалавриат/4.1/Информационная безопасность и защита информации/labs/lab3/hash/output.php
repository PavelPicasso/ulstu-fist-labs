<?php
     function BinString($bin_string) {
        $mybitseq = "";
        $hex_seq = $bin_string[0];
        $end = strlen($bin_string);
        for($i = 1 ; $i <= $end; $i++){
            if(strlen($hex_seq) % 7 == 0) {
                $char = chr(bindec($hex_seq));
                $hex_seq = "";
                $mybitseq .= $char;
            }
            
            $hex_seq .= $bin_string[$i];

            if ($hex_seq === '100000') {
                $hex_seq = "";
                $mybitseq .= ' ';
                continue;
            }
        }
        return $mybitseq;
    }

    function StringBin($mystring) {    
        $mybitseq = "";
        $end = strlen($mystring);
        for($i = 0 ; $i < $end; $i++){
            $bin = decbin(ord($mystring[$i]));
            $mybitseq .= $bin;
        }
        return $mybitseq;
    }

    if($_POST['command-name'] == "debug") {
        $text = $_POST['text'];
        $bin = StringBin($text);

        // //делается попытка создать его
        $fp = fopen("file.txt", "w");

        // записываем в файл текст
        fwrite($fp, $bin);

        // закрываем
        fclose($fp);
    }

    function preProcess($message){
        /*
        Pre-processing:
        append the bit '1' to the message i.e. by adding 0x80 if characters are 8 bits. 
        append 0 ≤ k < 512 bits '0', thus the resulting message length (in bits)
           is congruent to 448 (mod 512)
        append ml, in a 64-bit big-endian integer. So now the message length is a multiple of 512 bits.
        */
            $originalSize = strlen($message) * 8;
            $message .= chr(128);
            while (((strlen($message) + 8) % 64) !== 0) {
                $message .= chr(0);
            }
            foreach (str_split(sprintf('%064b', $originalSize), 8) as $bin) {
                $message .= chr(bindec($bin));
            }
            return $message;
    }
    function rotl($x, $n) {
      return ($x << $n) | ($x >> (32 - $n));
    }
    function SHAfunction($step, $b, $c, $d)
    {
        switch ($step) {
            case 0;
                return ($b & $c) ^ (~$b & $d);
            case 1;
            case 3;
                return $b ^ $c ^ $d;
            case 2;
                return ($b & $c) ^ ($b & $d) ^ ($c & $d);
        }
    }
    function hash_sha1($input) {
        $h0 = 0x67452301;
        $h1 = 0xEFCDAB89;
        $h2 = 0x98BADCFE;
        $h3 = 0x10325476;
        $h4 = 0xC3D2E1F0;
        $K = array(0x5a827999, 0x6ed9eba1, 0x8f1bbcdc, 0xca62c1d6);
        $message = preProcess($input);
        // Process the message in successive 512-bit chunks:
        // break message into 512-bit chunks
        $chunks = str_split($message, 64);
        foreach ($chunks as $chunk) {
            // break chunk into sixteen 32-bit big-endian words w[i], 0 ≤ i ≤ 15
            $words = str_split($chunk, 4);
            foreach ($words as $i => $chrs) {
                $chrs = str_split($chrs);
                $word = '';
                foreach ($chrs as $chr) {
                    $word .= sprintf('%08b', ord($chr));
                }
                $words[$i] = bindec($word);
            }
            // Extend the sixteen 32-bit words into eighty 32-bit words:
            for ($i = 16; $i < 80; $i++) {
            // for i from 16 to 79
            //     w[i] = (w[i-3] xor w[i-8] xor w[i-14] xor w[i-16]) leftrotate 1
                $words[$i] = rotl($words[$i-3] ^ $words[$i-8] ^ $words[$i-14] ^ $words[$i-16], 1) & 0xffffffff;
            }
            // Initialize hash value for this chunk:
            $a = $h0; $b = $h1; $c = $h2; $d = $h3; $e = $h4;
            // Main loop:[39]
            foreach ($words as $i => $word) {
                $s = floor($i / 20);
                $f = SHAfunction($s, $b, $c, $d);
                $temp = rotl($a, 5) + $f + $e + $K[$s] + ($word) & 0xffffffff;
                $e = $d;
                $d = $c;
                $c = rotl($b, 30);
                $b = $a;
                $a = $temp;
            }
            // Add this chunk's hash to result so far:
            $h0 = ($h0 + $a) & 0xffffffff;
            $h1 = ($h1 + $b) & 0xffffffff;
            $h2 = ($h2 + $c) & 0xffffffff;
            $h3 = ($h3 + $d) & 0xffffffff;
            $h4 = ($h4 + $e) & 0xffffffff;
        }
        return sprintf('%08x%08x%08x%08x%08x', $h0, $h1, $h2, $h3, $h4);;
    }

    function  Geffe($L1, $L2, $L3, $n) {
        $holder = "";
        for($i = 0; $i < $n; $i++) {
                $L1 = ($L1 << 1) | ((($L1 >> 29)^ ($L1 >> 28) ^ ($L1 >> 25) ^ ($L1 >> 23)) & 1);
                $L2 = ($L2 << 1) | ((($L2 >> 30)^ ($L2 >> 27)) & 1);
                $L3 = ($L3 << 1) | ((($L3 >> 31)^ ($L3 >> 30) ^ ($L3 >> 29) ^ ($L3 >> 28) ^ ($L3 >> 26) ^ ($L3 >> 24)) & 1 );
              
                $holder .= (((($L3 >> 32) & 1 )*(($L1 >> 30) & 1)) ^ (((($L3 >> 32) & 1) ^ 1) * (($L2 >> 31) & 1)) );
                // $holder .= ' ';
        }
        return $holder;
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
    <script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
</head>
<body>
<div class="container mlogin">
    <div id="result">
        <h1><?php echo $_POST['command-name']?></h1>
        <div class="choice">
            <form action='output.php' id="text" method="post" name="text">
                <p>
                    <label for="request"> Text<br>
                        <div class="form-group">
                            <input id="password" type="password" name="password" id="password" placeholder='Пароль'>
                            <input id="file" name="file" size="20" type="file">
                        </div>
                    </label>
                </p>
                <p class="submit">
                    <input id="cls" name="cls" class="button" type="submit" value="DoIt">
                    <input id="check" name="btn" class="button check" type="submit" value="Check">
                </p>
            </form>
            <?php
                if($_POST['file']) {
                    $text = file_get_contents($_POST['file']);
                    $len = strlen($text);

                    $l1 = round(rand(1, 29)); // 29
                    $l2 = round(rand(1, 30)); // 30
                    $l3 = round(rand(1, 31)); // 31
                    $sequence = Geffe($l1, $l2, $l1, $len);
                    
                    echo '<h3>Generate: </h3>';
                    echo '<div class=\'sequence\'>bin text<p>' . $text. '</p>Geffe<p>' . $sequence . '</p>';
                    echo 'hash_sha1:<p>' . hash_sha1($text). '</p>';

                    $new_arr = '';
                    for($i = 0; $i < $len; $i++) {
                        $new_arr .= '' . (int)$text[$i] ^ (int)$sequence[$i];
                    }
                    echo 'encrypt text<p>' . $new_arr . '</p>decrypt text<p>';

                    $bin_text = '';
                    for($i = 0; $i < $len; $i++) {
                        $bin_text .= (int)$new_arr[$i] ^ (int)$sequence[$i];
                    }
                    echo $bin_text;
                    echo '</p>text<p>' . BinString($bin_text). '</p></div>';
                }
                if($_POST['password']) {
                    if(hash_sha1($_POST['password']) === '322f89ebdf649e7f850e07c1fd0bdf0d134ce6b9') {
                        echo "<script>
                            $('#file').css(\"display\", \"block\");
                            $('#cls').css(\"display\", \"block\");
                            $('#check').css(\"display\", \"none\");
                            $('#password').css(\"display\", \"none\");
                        </script>";
                    }
                }
            ?>
            <a href="choice.php" name="btn" class="button">Choice text</a>
        </div>
    </div>
</div>
</body>
</html>