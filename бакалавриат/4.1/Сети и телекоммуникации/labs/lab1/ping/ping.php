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
        <h1>ping</h1>
        <form action="output.php" id="ping" method="post" name="ping">
            <input class="input" id="command-name" name="command-name" size="20" type="hidden" value="ping">
            <p>
                <label for="number-request"> Число запросов <br>
                    <div class="form-group">
                        <input class="input" id="number-request" name="number-request" size="20" type="text" placeholder="">
                    </div>
                </label></p>
            <p>
                <label for="life-time"> Время жизни <br>
                    <div class="form-group">
                        <input class="input" id="life-time" name="life-time" size="20" type="text">
                    </div>
                </label>
            </p>
            <p>
                <label for="number-routes"> Количество маршрутов <br>
                    <div class="form-group">
                        <input class="input" id="number-routes" name="number-routes" size="20" type="text">
                    </div>
                </label>
            </p>
            <p>
                <label for="ip-address">IP-адрес или имя узла <br>
                    <div class="form-group">
                        <input class="input" id="ip-address" name="ip-address" size="20" type="text">
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