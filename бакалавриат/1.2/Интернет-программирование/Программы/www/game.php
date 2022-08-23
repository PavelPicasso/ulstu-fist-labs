<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Верста сайта</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style-info.css" rel="stylesheet">
    <script src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script src="js/menu.js"></script>
    <script language="JavaScript">


        function initArray() {
            this.length = initArray.arguments.length
            for (var i = 0; i < this.length; i++)
                this[i + 1] = initArray.arguments[i]
        }
        var pos = new initArray(9, 9, 9,
                                9, 9, 9,
                                9, 9, 9);
        var nummoves = 0;
        function random() {
            today = new Date();
            num = today.getTime();
            num = Math.round(Math.abs(Math.sin(num) * 1000000)) % 9;
            return num;
        }
        function display(pos) {
            for (var i = 0; i < 9; i++) {
                document.forms[0].elements[i].value = pos[i];
            }
            document.forms[0].moves.value = nummoves;
            win();
        }
        function move(num) {
            if (num < 8 && pos[num + 1] == 0) {
                pos[num + 1] = pos[num];
                pos[num] = 0;
                nummoves++;
            }
            else if (num > 0 && pos[num - 1] == 0) {
                pos[num - 1] = pos[num];
                pos[num] = 0;
                nummoves++;
            }
            else if (num > 2 && pos[num - 3] == 0) {
                pos[num - 3] = pos[num];
                pos[num] = 0;
                nummoves++;
            }
            else if (num < 6 && pos[num + 3] == 0) {
                pos[num + 3] = pos[num];
                pos[num] = 0;
                nummoves++;
            }
            display(pos);
        }
        function win() {
            if (pos[0] == 1 & pos[1] == 2 & pos[2] == 3 & pos[3] == 4 &
                pos[4] == 5 & pos[5] == 6 & pos[6] == 7 & pos[7] == 8 & pos[8] == 0) {
                if (confirm('Ты смог! Хочешь начать заново?')) newgame();
            }
        }
        function newgame() {
            var x = 1;
            for (var i = 0; i < 9; i++) {
                pos[i] = 9;
            }
            for (var i = 0; i < 9; i++) {
                randomnum = random();
                if (randomnum == 9) randomnum = 8;
                x = 1;
                for (var j = 0; j < 9; j++) {
                    if (j == i)
                        continue;
                    if (randomnum == pos[j]) {
                        x = 0;
                        break;
                    }
                }
                if (x == 0) {
                    i--;
                    continue;
                }
                pos[i] = randomnum;
            }
            nummoves = 0;
            display(pos);
        }

    </script>
</head>
<body onLoad="window.newgame()">
<header>
    <div class="container">
        <div class="row">
            <a href="index.php">
                <img class="logo" src="img/logo2.png" width="130" height="60" alt="JPG">
            </a>
            <ul>
                <li><a href="game.php">Игра</a></li>
                <li><a href="">Музыка</a></li>
                <li><a href="info.html">Инфо</a></li>
            </ul>
        </div>
    </div>
</header>


<div class="services">
    <div class="container1">

        <center>
            <h4 align=center>Магические квадраты</h4>
            <br>
            <form>
                <table border=0 celpadding=0 cellspacing=10>
                    <tr>
                        <td class="cool">
                            <input type="button" value=" 1 " onClick="window.move(0)">
                            <input type="button" value=" 2 " onClick="window.move(1)">
                            <input type="button" value=" 3 " onClick="window.move(2)"><br>
                            <input type="button" value=" 4 " onClick="window.move(3)">
                            <input type="button" value=" 5 " onClick="window.move(4)">
                            <input type="button" value=" 6 " onClick="window.move(5)"><br>
                            <input type="button" value=" 7 " onClick="window.move(6)">
                            <input type="button" value=" 8 " onClick="window.move(7)">
                            <input type="button" value=" 0 " onClick="window.move(8)">
                            <br>
                        </td>
                        </td>
                        <td valign=top>
                            <br>
                            <ol>
                                <li>Приведите в порядок числа так, чтобы они читались 1-8.</li>
                                <li>Этот 0 - 'пустое' окно.</li>
                                <li>Нажмите на любое число рядом с 0 и они поменяются местами.</li>
                                <li>Также можно воспользоваться позсказкой снизу</li>
                            </ol>
                        </td>
                    </tr>
                    <tr>
                        <td align=center>
                            <h3>Количество шагов:</h3>
                            <input name="moves" size=7 value="0">
                            <br>
                        </td>
                        <td align=center><br>
                            <input type="submit" class="button" value="Начать заново" onClick="window.newgame()">
                        </td>
                    </tr>
                </table>
            </form>

            <a onclick="gamebtn();" id="gamebtn" class="help">Включить подсказку</a>
            <div id="game">
                <center>
                    <br>
                    <img src="img/game.jpg" width="250" height="250" alt="JPG">
                </center>
                <div class="tn-progress"></div>
            </div>
            <script>
                function gamebtn(){
                    if ($("#game").css("display") == "block") {
                        $("#game").css("display","none");
                    } else {$("#game").css("display","block");}
                }
            </script>

        </center>
    </div>
</div>

</body>
</html>