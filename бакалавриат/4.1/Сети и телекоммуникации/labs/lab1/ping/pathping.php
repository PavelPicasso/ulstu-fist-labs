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
<div class="container1 mlogin">
    <div id="login">
        <h1>pathping</h1>
        <form action="output.php" id="pathping" method="post" name="pathping">
            <input class="input" id="command-name" name="command-name" size="20" type="hidden" value="pathping">
            <p>
                <label for="list-node"> list of node <br>
                    <div class="form-group">
                        <input class="input" id="list-node" name="list-node" size="20" type="text" placeholder="">
                    </div>
                </label>
            </p>
            <p>
                <label for="number-hops"> number of hops <br>
                    <div class="form-group">
                        <input class="input" id="number-hops" name="number-hops" size="20" type="text" placeholder="">
                    </div>
                </label>
            </p>
            <p>
                <label for="number-request"> number request <br>
                    <div class="form-group">
                        <input class="input" id="number-request" name="number-request" size="20" type="text" placeholder="">
                    </div>
                </label>
            </p>
            <p>
                <label for="timeout"> timeout <br>
                    <div class="form-group">
                        <input class="input" id="timeout" name="timeout" size="20" type="text" placeholder="">
                    </div>
                </label>
            </p>
            <p>
                <label for="ip-address"> ip address <br>
                    <div class="form-group">
                        <input class="input" id="ip-address" name="ip-address" size="20" type="text" placeholder="">
                    </div>
                </label>
            </p>
            <p class="submit"><input name="btn" class="button" type="submit" value="DoIt"></p>
            <p><a href="choice.php" class="reg">Выбор утилиты</a></p>
        </form>
    </div>
</div>

</body>
</html>