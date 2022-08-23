function Dfdx(x) {
    return Math.cos(x) - (x * x * x);
}

function D2fdx2(x) {
    return -Math.sin(x) - 3 * (x * x);
}

function Newton(x, eps) {
    let f, df;
    
    do {
        f = Dfdx(x);
        df = D2fdx2(x);
        x = x - f / df;
    } while (Math.abs(f) > eps);

    return x;
}

function procesar(formulario) {
    let times = [];
    let iter = 0,
        eps = 0.1;
    let x_1,
        result,
        x = parseFloat(formulario.x.value);
    let resultado = "<table><thead><tr><td>i</td><td>x</td></tr></thead><tbody>";
    
    do {
        x_1 = x;
        console.log(`Dfdx(x) = ${Dfdx(x)}`);
        console.log(`D2fdx2(x) = ${D2fdx2(x)}`);

        x = x - Dfdx(x) / D2fdx2(x);
        resultado +=
        "<tr><td>x<sub>" +
        x_1.toFixed(5) +
        "</sub></td><td>" +
        x.toFixed(5) +
        "</td></tr>";
        iter++;
    } while (x.toFixed(5) != x_1.toFixed(5) && iter < 20000);

    if (D2fdx2(x) < 0) {
		document.getElementById("resultado").innerHTML = resultado + `</tbody></table><p>Решение: <span>${x.toFixed(5)}</span></p>`;
	}
	else {
		document.getElementById("resultado").innerHTML = resultado + `</tbody></table><p>Экстремум на этом интервале не является максимумом!</p><p>Решение: <span>${x.toFixed(5)}</span></p>`;
	}

    let x0 = parseFloat(formulario.x.value);

    for (let i = 0; i < 9; i++) {
        let time = performance.now(); // стартовая засечка
        for (let i = 0; i < 100000; i++)
            result = Newton(0.5, eps);
        time = performance.now() - time;// замер продолжительности
        times.push(time);
        eps *= 0.1;
    }

    console.log(result);
    let e = 0.1;
    let parametros = "<h2>Результат:</h2><table><thead><tr><td>eps</td><td>time</td></tr></thead><tbody>";
    if (D2fdx2(x) < 0) {
        for (let time of times) {
            parametros += `<tr><td>${e.toFixed(9)}</td><td>${time.toFixed(5)}</td></tr>`;
            e *= 0.1;
        }
        parametros += "</table>"
        document.getElementById("time").innerHTML = parametros;
    }
    else {
        parametros += "<p>Экстремум на этом интервале не является максимумом!</p>"
        for (let time of times) {
            parametros += `<tr><td>${e.toFixed(9)}</td><td>${time.toFixed(5)}</td></tr>`;
            e *= 0.1;
        }
        parametros += "</table>"
        document.getElementById("time").innerHTML = parametros;
    }

    return false;
}