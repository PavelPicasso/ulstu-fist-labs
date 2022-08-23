<?php
    header("Content-Type: text/html; charset=cp866");

    function ping($numberRequest, $lifeTime, $numberRoutes, $ipAddress) {
        $numberRequest ? $numberRequest = '-n ' . $numberRequest : '';
        $lifeTime ? $lifeTime = '-i ' . $lifeTime : '';
        $numberRoutes ? $numberRoutes = '-r ' . $numberRoutes : '';
        $request = "ping $numberRequest $lifeTime $numberRoutes $ipAddress";
        exec($request, $output_array, $return_val);
        echo "<h2> Request: $request </h2>";
        echo "<p> Returned $return_val </p> <div class=\"output\">";
        foreach ($output_array as $o) {
            if(isset($o))
                echo "<p> $o </p>";
        }
        echo "</div>";
    }

    function ipconfig($Request) {
        $request = "ipconfig $Request";
        exec($request, $output_array, $return_val);
        echo "<h2> Request: $request </h2>";
        echo "<p> Returned $return_val </p> <div class=\"output\">";
        foreach ($output_array as $o) {
            if(isset($o))
                echo "<p> $o </p>";
        }
        echo "</div>";
    }
    
    function pathping($listNode, $numberHops, $numberRequest, $timeout, $ipAddress) {
        $listNode ? $listNode = '-g ' . $listNode : '';
        $numberHops ? $numberHops = '-h ' . $numberHops : '';
        $numberRequest ? $numberRequest = '-q ' . $numberRequest : '';
        $timeout ? $timeout = '-w ' . $timeout : '';
        $ipAddress ? $ipAddress = $ipAddress : '';
        
        $request = "pathping $listNode $numberHops $numberRequest $timeout $ipAddress";
        exec($request, $output_array, $return_val);
        echo "<h2> Request: $request </h2>";
        echo "<p> Returned $return_val </p> <div class=\"output\">";
        foreach ($output_array as $o) {
            if(isset($o))
                echo "<p> $o </p>";
        }
        echo "</div>";
    }

    function arp($Request) {
        $request = "arp $Request";
        exec($request, $output_array, $return_val);
        echo "<h2> Request: $request </h2>";
        echo "<p> Returned $return_val </p> <div class=\"output\">";
        foreach ($output_array as $o) {
            if(isset($o))
                echo "<p> $o </p>";
        }
        echo "</div>";
    }

    //print_r($_POST);
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
                switch($_POST['command-name']) {
                    case 'ping':
                        ping($_POST['number-request'], $_POST['life-time'], $_POST['number-routes'], $_POST['ip-address']);
                        break;
                    case 'ipconfig':
                        ipconfig($_POST['request']);
                        break;
                    case 'pathping':
                        pathping($_POST['list-node'], $_POST['number-hops'], $_POST['number-request'], $_POST['timeout'], $_POST['ip-address']);
                        break;
                    case 'arp':
                        arp($_POST['request']);
                        break;
                    default:
                        break;
                }
            ?>

            <a href="choice.php" name="btn" class="button">Choice of the utility</a>
        </div>
    </div>
</div>

</body>
</html>