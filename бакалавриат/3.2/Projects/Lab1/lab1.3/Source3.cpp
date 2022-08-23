#include <time.h> // ��� clock(), clock_t, CLOCKS_PER_SEC
#include <intrin.h> // ��� __rdtsc()
#include <windows.h> // ��� ������� QPC QueryPerformanceCounter(&tackts) � QueryPerformanceFrequency(&freq)
#include <math.h>
#include <iostream>
#include <string>
#include <cstdlib>
#include <ctime>

using namespace std;

#define SIZE_DARR 200000000

double arr[SIZE_DARR];
class Clocks { // ����� ��� ���� ����� �����
public:
	string name; //   �������� ���� �����
	__int64 time, freq; // ����� � �������
	void my_func(int arcount) { // ��������� arcount ��������� ������� arr 
		for (int i = 0; i < arcount; i++) {
			arr[i] = (exp(i) + exp(-i)) / 2;
		}
	}

	int dummy() {
		return 0;
	}

	virtual void measure(int arcount) {}; // ������� ��������� �������
	void measSeries(int scount, int arcount); // ����� �� scount ��������� � ������� ��������� arcount
};


void Clocks::measSeries(int scount, int arcount) {
	//cout << endl << name << ": " << arcount << " ���������" << endl
	//	<< "����� (�) \t������� (��)\n";
	for (int i = 0; i < scount; i++) {
		measure(arcount);
		cout << endl << name << ": " << arcount << " ���������" << endl
			<< "����� (�c) \t������� (��)\n";
		cout.width(10);
		cout.setf(ios::right);
		cout << time << "\t" << freq << endl;
	}
}

class TSC : public Clocks { // ���� �� ������ Time Stamp Counter
public:
	TSC() { name = "TSC"; }
	void measure(int sz) {
		freq = hz_cpu();
		__int64 t = __rdtsc(); // ��������� �������
							   //my_func(sz);
		for (int i = 0; i < sz; i++)
			dummy();
		t = __rdtsc() - t; // ����� �����������������
		time = 1000000000 * t / freq;
	}
	__int64 hz_cpu() { // ����� �������
		clock_t t_clock;
		__int64 t_tsc;
		t_clock = clock() + CLOCKS_PER_SEC;
		t_tsc = __rdtsc(); // ����� TSC
		while (clock() < t_clock); // ������ ����� �������
		return (__rdtsc() - t_tsc); // ������� � ������
	}
};





class Clock : public Clocks { // ���� �� ������ ������� clock()
public:
	Clock() { freq = CLOCKS_PER_SEC; name = "clock"; }
	void measure(int sz) {
		__int64 t = clock(); // ��������� ������� � ��������
		my_func(sz);
		//for (int i = 0; i < sz; i++)
		//	dummy();
		t = clock() - t; // ����� ����������������� � ��������
		time = t * 1000 / freq;
	}
};



class QPC : public Clocks { // ���� �� ������ QPC
public:
	QPC() { QueryPerformanceFrequency((LARGE_INTEGER *)&freq); name = "QPC"; }
	void measure(int sz) {
		LONGLONG t_start, t_end;
		QueryPerformanceCounter((LARGE_INTEGER *)&t_start); // ��������� ������ � ������� � ������
		my_func(sz);
		//for (int i = 0; i < sz; i++)
		//	dummy();
		QueryPerformanceCounter((LARGE_INTEGER *)&t_end);// ��������� ������ � ������� � �����
		time = 1000000 * (t_end - t_start) / freq;
	}
};



int main()
{
	Clock  cl;
	QPC  qpc;
	TSC  tsc;
	setlocale(LC_CTYPE, "rus");
	__int64 sum = 0;
	int sz = 10;
	for (int i = 0; i < 10; i++)
	{
		do {
			//cl.measSeries(1, sz);
			//qpc.measSeries(4, sz);
			tsc.measSeries(1, sz);
			sz += 10;
		} while (tsc.time < 3);
		sum += sz;
	}
	cout << endl << "������� �������� ����������� �����������: " << sum / 10 << " ���������" << endl;

	system("pause");
	return 0;
}
