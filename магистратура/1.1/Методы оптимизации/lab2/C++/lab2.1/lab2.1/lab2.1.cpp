#include <iostream>
#include <math.h>
#include <conio.h>
#include <intrin.h> // там __rdtsc()
#include <iomanip>

using namespace std;

double func(double x) // функция вызова функции
{
    return 1.5 * sin(1.5 * pow(x, 1) + 3);
    //return  (10 * x * log10(x) / log10(2.7) - (x * x) / 2); // заданная задачей функция 
}



void mZolotSech(double eps, double a, double b) // сам метод
{
    double x1, x2, y1, y2, ymin, xmin, t, er, ea; // er -> расчетная погрешность, еа->вычисляемая погрешность.
    int m, j;
    t = (sqrtl(5) - 1) / 2; // то самое phi, отношение золотого сечения
    //er = (b - a) / (2 * pow(t, (N - 1)));
    x1 = b + (b - a) / t;   y1 = func(x1);
    x2 = a - (b - a) / t;   y2 = func(x2);
    m = 2;
    while ((b - a) > eps)
    {
        if (y1 < y2)
        {
            b = x2;
            x2 = x1; y2 = y1;
            x1 = b - (b - a) * t;
            y1 = func(x1);
            //cout << "m=" << m << " x1=" << x1 << " x2=" << x2 << " y1=" << y1 << " y2=" << y2 << endl;
        }
        else
        {
            a = x1;
            x1 = x2; y1 = y2;
            x2 = a + (b - a) * t;
            y2 = func(x2);
            //cout << "m=" << m << " x1=" << x1 << " x2=" << x2 << " y1=" << y1 << " y2=" << y2 << endl;
        }
        m++;
    }
    // m < N
    if (y1 < y2) b = x2;
    else a = x1;
    //cout << endl;
    //cout << a << b;
    xmin = (a + b) / 2.0; ymin = func(xmin); ea = (b - a) / 2.0;
    //cout << endl;
    //cout << setw(15) << xmin << setw(15) << ymin << setw(15) << er << setw(15) << ea << endl;
}

// частота в мегагерцах через RDTSC
int mhz_cpu() {
    clock_t clock_tick1, clock_tick2;
    __int64 cpu_tick1, cpu_tick2;
    int rdtsc_tick;
    double usec; // время в микросекундах
    clock_tick1 = clock();
    while ((clock_tick2 = clock()) == clock_tick1); // пропуск остатка текущего тика clock
    cpu_tick1 = __rdtsc(); // взять TSC
    while (clock() == clock_tick2); // отсчет одного тика clock
    cpu_tick2 = __rdtsc() - cpu_tick1; // сколько натикал счетчик TSC за один тик clock
                                       // вычисляем частоту в мегагерцах 
    usec = 1000000.0 / CLOCKS_PER_SEC; // время одного тика clock в микросекундах
    return int(cpu_tick2 / usec);
}


int main()
{
    clock_t clock_start, clock_time; // стартовое и измеренное время для clock()
    __int64 hpet_start, hpet_end, hpet_time, // стартовое, финишное и измеренное время для HPET
        hpet_freq; // частота HPET
    __int64 rdtsc_start, rdtsc_time, // стартовое, финишное и измеренное время для RDTSC
        rdtsc_freq; // частота счетчика тактов (процессора) в мегагерцах

    setlocale(LC_ALL, "Russian");
    cout << "Добро Пожаловать в программу\n";
    double a, b, eps;
    cout << "\nВведите a="; cin >> a; cout << "\nВведите b="; cin >> b;
    for (double eps = 0.1; eps > 0.0000000001; eps *= 0.1)
    {
        rdtsc_start = __rdtsc();
        mZolotSech(eps, a, b);
        rdtsc_time = __rdtsc() - rdtsc_start;
        cout << rdtsc_start << ": " << rdtsc_time << " тактов";
        rdtsc_freq = mhz_cpu();
        cout << "\nВремя = " << double(0.001 * rdtsc_time / rdtsc_freq) << " мсек ";
    }
}
