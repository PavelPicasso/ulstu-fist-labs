<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="css/custom.css" rel="stylesheet">
    <link href="css/options.css" rel="stylesheet">
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <title>lab1</title>
</head>
<body>
<div class="container1 mlogin">
    <div id="login">
        <h1>sequence</h1>
        <form action='output.php' id="sequence" method="post" name="sequence">
        <input class="input" id="command-name" name="command-name" size="20" type="hidden" value="debug">
            <p>
                <label for="request"> Size sequence<br>
                    <div class="form-group">
                        <input class="input" id="text" name="text" size="20" type="text" value="" placeholder="text">
                    </div>
                </label>
            </p>
            <p class="submit"><input name="btn" class="button" type="submit" value="DoIt"></p>
        </form>
    </div>
</div>

</body>
</html>