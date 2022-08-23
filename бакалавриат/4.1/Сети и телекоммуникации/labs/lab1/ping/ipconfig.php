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
        <h1>ipconfig</h1>
        <form action="output.php" id="ipconfig" method="post" name="ipconfig">
            <input class="input" id="command-name" name="command-name" size="20" type="hidden" value="ipconfig">
            <p>
                <label for="request"> Request <br>
                    <div class="form-group">
                        <input class="input" id="request" name="request" size="20" type="text" placeholder="">
                    </div>
                </label>
            </p>
            <div class="form-group">
                <label for="1">
                    Примеры использования
                    <a href="https://ip-calculator.ru/blog/ask/sovety-po-ispolzovaniyu-ipconfig-v-windows/" class="reg" target="_blank">ipconfig</a>
                </label>
            </div>
            <p class="submit"><input name="btn" class="button" type="submit" value="DoIt"></p>
            <p><a href="choice.php" class="reg">Выбор утилиты</a></p>
        </form>
    </div>
</div>

</body>
</html>