function Model() {

    var weights = [];
    var pathes = [];

    this.addDot = function() {
        for (var i = 0; i < weights.length; i++) {
            weights[i].push(Infinity);
            pathes[i].push(i + 1);
        }
        var newRow = (new Array(weights.length)).fill(Infinity);
        newRow.push(0);
        weights.push(newRow);
        pathes.push(new Array(pathes.length + 1).fill(pathes.length + 1));
    };

    this.setConnectionWeight = function (source, target, weight) {
        weights[source][target] = weight;
    };

    this.getWeights = function() {
        return weights;
    };

    this.getPathes = function() {
        return pathes;
    };

    this.calculate = function() {
        $(".highlighted").removeClass("highlighted");
        if (validateInitialConditions()) {
            
            
            let time = performance.now(); // стартовая засечка
			for(let w = 0; w < 100000; w++) {
				var firstTop = +$("#firstTop").val();
				var secondTop = +$("#secondTop").val();
				var result = $.extend(true, [], weights);
				var solutionPathes = $.extend(true, [], pathes);
				for (var k = 0; k < result.length; k++) {
					for (var i = 0; i < result.length; i++) {
						for (var j = 0; j < result[i].length; j++) {
							if (result[i][j] > result[i][k] + result[k][j]) {
								result[i][j] = result[i][k] + result[k][j];
								solutionPathes[i][j] = k + 1;
							}
							if (i == j && result[i][j] < 0) {
								alert("Обнаружен контур отрицательной длины! Дальнейшие вычисления невозможны.\n" +
									"Вершина, через которую проходит контур отрицательной длины: " + (k + 1));
								return false;
							}
						}
					}
				}
			}
            time = performance.now() - time;// замер продолжительности
            console.log(`${time.toFixed(5)}`);

            showShortestPath(solutionPathes, firstTop, secondTop);
            alert("Длина кратчайшего пути: " + result[firstTop - 1][secondTop - 1] + "\n" +
            "Кратчайший путь: " + getShortestPath(solutionPathes, firstTop, secondTop).join(", ") + "\n" +
            "Матрица длин путей С:" + "\n" + arrayToString(result) +
            "Дополнительная матрица V:" + "\n" + arrayToString(solutionPathes));
        }
    };

    function validateInitialConditions() {
        var isValid = true;
        if (weights.length > 1) {
            var firstTop = $("#firstTop").val();
            var secondTop = $("#secondTop").val();

            if (firstTop === "" || secondTop === "") {
                alert("Укажите начальную и конечную вершины");
                firstTop === "" ? $("#firstTop").focus() : $("#secondTop").focus();
                isValid = false;
            }

            if (+firstTop > weights.length + 1 || +secondTop > weights.length + 1) {
                alert("Введите номер вершины в диапазоне от " + 1 + " до " + weights.length);
                isValid = false;
            }
        } else {
            alert("Сначала добавьте несколько вершин");
        }
        return isValid;
    }
}

window.model = new Model();