#include <time.h> // там clock(), clock_t, CLOCKS_PER_SEC
#include <intrin.h> // там __rdtsc()
#include <windows.h> // там функции использования HPET QueryPerformanceCounter(&tackts) и QueryPerformanceFrequency(&freq)
#include <math.h>
#include <iostream>
#include <string>

using namespace std;

#define SIZE_DARR 100000000

int dummy() {
	return 0;
}

double arr[SIZE_DARR];
void my_func(int count) { // заполняет массив синусами
	for (int i = 0; i < count; i++) {
		arr[i] = (exp(i) + exp(-i)) / 2;
	}
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

	int n;


	do { // повторять, чтобы оценить повторяемость результатов
		clock_start = clock(); // стартовая засечка

							   //my_func(sz);
		for (int i = 0; i < 1; i++)
			dummy();
		/*
		cin >> n;
		for(int i = 0; i < n; i++)
		dummy();
		*/

		clock_time = clock() - clock_start; // замер продолжительности
		cout << clock_start << ": " << clock_time << " мсек инициировался массив длиной " << SIZE_DARR << endl;
	} while (sz != 0);

	cout << "\nИспытываем HPET\n\n";
	sz = SIZE_DARR; // задаем число инициируемых синусами элементов массива
	do { // повторять, чтобы оценить повторяемость результатов
		if (QueryPerformanceCounter((LARGE_INTEGER *)&hpet_start) != 0)
		{

			/*
			my_func(sz);
			/*
			for (int i = 0; i < SIZE; i++)
			dummy();
			*/

			QueryPerformanceCounter((LARGE_INTEGER *)&hpet_end);
			hpet_time = hpet_end - hpet_start;
			cout << hpet_start << ": " << hpet_time << " тактов инициировался массив длиной " << sz;
			QueryPerformanceFrequency((LARGE_INTEGER *)&hpet_freq);
			cout << "\nЧастота = " << hpet_freq << "  Время = " << int(1000 * hpet_time * 1.0 / hpet_freq) << " мсек\n";
		}
		cout << "\nПовторить(1 - да)?: ";
		cin >> ans; // ввод ответа на запрос
	} while (ans == '1');

	cout << "\nИспытываем RDTSC\n\n";
	sz = SIZE_DARR; // задаем число инициируемых синусами элементов массива
	do { // повторять, чтобы оценить повторяемость результатов
		rdtsc_start = __rdtsc();

		my_func(sz);
		/*
		for (int i = 0; i < SIZE; i++)
		dummy();
		*/

		rdtsc_time = __rdtsc() - rdtsc_start;
		cout << rdtsc_start << ": " << rdtsc_time << " тактов инициировался массив длиной " << sz;
		rdtsc_freq = mhz_cpu();
		cout << "\nВремя = " << int(0.001 * rdtsc_time / rdtsc_freq) << " мсек   Частота = " << rdtsc_freq << " Мгц";
		cout << "\nПовторить(1 - да)?: ";
		cin >> ans; // ввод ответа на запрос
	} while (ans == '1');
	printf("\nЗавершено");
	return 0;
}



/*

5 раз и взять min

t = clock() + 1000;
while(clock < t);


отключаем оптиизатор для dummy
*/