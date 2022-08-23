#include <time.h> // там clock(), clock_t, CLOCKS_PER_SEC
#include <intrin.h> // там __rdtsc()
#include <windows.h> // там функции QPC QueryPerformanceCounter(&tackts) и QueryPerformanceFrequency(&freq)
#include <math.h>
#include <iostream>
#include <string>

using namespace std;

#define SIZE_DARR 200000000

double arr[SIZE_DARR];
class Clocks { // Общее для всех видов часов
public:
	string name; //   название типа часов
	__int64 time, freq; // время и частота
	void my_func(int arcount) { // заполняет arcount элементов массива arr 
		for (int i = 0; i < arcount; i++) {
			arr[i] = (exp(i) + exp(-i)) / 2;
		}
	}
	int dummy() {
		return 0;
	}
	virtual void measure(int arcount) {}; // функция измерения времени
	void measSeries(int scount, int arcount); // серия из scount измерений с рабочей нагрузкой arcount
};
void Clocks::measSeries(int scount, int arcount) {
	cout << endl << name << ": " << arcount << " элементов" << endl
		<< "Время (нс) \tЧастота (гц)\n";
	for (int i = 0; i < scount; i++) {
		measure(arcount);
		cout.width(10);
		cout.setf(ios::right);
		cout << time << "\t" << freq << endl;
	}
}
class Clock : public Clocks { // часы на основе функции clock()
public:
	Clock() { freq = CLOCKS_PER_SEC; name = "clock"; }
	void measure(int sz) {
		__int64 t = clock(); // стартовая засечка
		//my_func(sz);
		for (int i = 0; i < sz; i++)
			dummy();
		t = clock() - t; // замер продолжительности
		time = 1000000000 * t / freq;
	}
};


class QPC : public Clocks { // часы на основе QPC
public:
	QPC() { QueryPerformanceFrequency((LARGE_INTEGER *)&freq); name = "QPC"; }
	void measure(int sz) {
		LONGLONG t_start, t_end;
		QueryPerformanceCounter((LARGE_INTEGER *)&t_start); // стартовая засечка
		//my_func(sz);
		for (int i = 0; i < sz; i++)
			dummy();
		QueryPerformanceCounter((LARGE_INTEGER *)&t_end);
		time = 1000000000 * (t_end - t_start) / freq;
	}
};


class TSC : public Clocks { // часы на основе Time Stamp Counter
public:
	TSC() { name = "TSC"; }
	void measure(int sz) {
		freq = hz_cpu();
		__int64 t = __rdtsc(); // стартовая засечка
		//my_func(sz);
		for (int i = 0; i < sz; i++)
			dummy();
		t = __rdtsc() - t; // замер продолжительности
		time = 1000000000 * t / freq;
	}
	__int64 hz_cpu() { // замер частоты
		clock_t t_clock;
		__int64 t_tsc;
		t_clock = clock() + CLOCKS_PER_SEC;
		t_tsc = __rdtsc(); // взять TSC
		while (clock() < t_clock); // отсчет одной секунды
		return (__rdtsc() - t_tsc); // частота в герцах
	}
};

int main()
{
	Clock  cl;
	QPC  qpc;
	TSC  tsc;
	setlocale(LC_CTYPE, "rus");

	for (int sz = SIZE_DARR; sz >= 1; sz == 2 ? sz /= 2 : sz /= 10) {
		//cl.measSeries(4, sz);
		qpc.measSeries(4, sz);
		//tsc.measSeries(4, sz);
	}
	system("pause");
	return 0;
}