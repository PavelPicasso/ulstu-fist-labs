#include <time.h> // там clock(), clock_t, CLOCKS_PER_SEC
#include <intrin.h> // там __rdtsc()
#include <windows.h> // там функции использования HPET QueryPerformanceCounter(&tackts) и QueryPerformanceFrequency(&freq)
#include <math.h>
#include <iostream>
#include <string>

using namespace std;

#define SIZE_DARR 100000


double arr[SIZE_DARR];

double Dfdx(double x) {
	return cos(x) - (x * x * x);
}

double D2fdx2(double x) {
	return -sin(x) - 3 * (x * x);
}

void Newton(double x, double eps) { // заполняет массив синусами
	double f, df;

	do {
		f = Dfdx(x);
		df = D2fdx2(x);
		x = x - f / df;
	} while (fabs(f) > eps);
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
	int sz;
	clock_t clock_start, clock_time; // стартовое и измеренное время для clock()
	__int64 hpet_start, hpet_end, hpet_time, // стартовое, финишное и измеренное время для HPET
		hpet_freq; // частота HPET
	__int64 rdtsc_start, rdtsc_time, // стартовое, финишное и измеренное время для RDTSC
		rdtsc_freq; // частота счетчика тактов (процессора) в мегагерцах

	char ans;             // ответ на запрос о продолжении			  
	setlocale(LC_CTYPE, "rus"); // кириллизация
	cout << "Испытываем clock()\n\n";
	sz = SIZE_DARR; // задаем число инициируемых синусами элементов массива

	int n, i;

	do { // повторять, чтобы оценить повторяемость результатов
		for (double eps = 0.1; eps >= 0.0000000001; eps *= 0.1) {
			clock_start = clock(); // стартовая засечка
			for (int i = 0; i < SIZE_DARR; i++)
				Newton(0.5, eps);
			clock_time = clock() - clock_start; // замер продолжительности
			cout << clock_time << " мсек " << eps << "eps" << endl;
		}
		cout << "\nПовторить(1 - да)?: ";
		cin >> ans; // ввод ответа на запрос
	} while (ans == '1');

	cout << "\nИспытываем RDTSC\n\n";
	sz = SIZE_DARR; // задаем число инициируемых синусами элементов массива
	do { // повторять, чтобы оценить повторяемость результатов
		for (double eps = 0.1; eps >= 0.0000000001; eps *= 0.1) {
			rdtsc_start = __rdtsc();
			for (int i = 0; i < SIZE_DARR; i++)
				Newton(0.5, eps);
			rdtsc_time = __rdtsc() - rdtsc_start;
			cout << rdtsc_start << ": " << rdtsc_time << " тактов";
			rdtsc_freq = mhz_cpu();
			cout << "\nВремя = " << int(0.001 * rdtsc_time / rdtsc_freq) << " мсек " << eps << "eps";
		}
		cout << "\nПовторить(1 - да)?: ";
		cin >> ans; // ввод ответа на запрос
	} while (ans == '1');
	printf("\nЗавершено");
	return 0;
}