// const { performance } = require('perf_hooks');

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

console.log('eps\t\tmilliseconds');
let times = [];
let eps = 0.1;
for (let i = 0; i < 9; i++) {
    let time = performance.now(); // стартовая засечка
    for (let i = 0; i < 100000; i++)
        result = Newton(0.5, eps);
    time = performance.now() - time;// замер продолжительности
    times.push(time);
    eps *= 0.1;
}

let e = 0.1;
for (let time of times) {
    console.log(`${e.toFixed(9)} \t ${time.toFixed(5)}`);
    e *= 0.1;
}