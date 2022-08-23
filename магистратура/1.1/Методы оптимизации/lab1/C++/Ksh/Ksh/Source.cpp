#include <time.h> // ��� clock(), clock_t, CLOCKS_PER_SEC
#include <intrin.h> // ��� __rdtsc()
#include <windows.h> // ��� ������� ������������� HPET QueryPerformanceCounter(&tackts) � QueryPerformanceFrequency(&freq)
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

void Newton(double x, double eps) { // ��������� ������ ��������
	double f, df;

	do {
		f = Dfdx(x);
		df = D2fdx2(x);
		x = x - f / df;
	} while (fabs(f) > eps);
}

// ������� � ���������� ����� RDTSC
int mhz_cpu() {
	clock_t clock_tick1, clock_tick2;
	__int64 cpu_tick1, cpu_tick2;
	int rdtsc_tick;
	double usec; // ����� � �������������
	clock_tick1 = clock();
	while ((clock_tick2 = clock()) == clock_tick1); // ������� ������� �������� ���� clock
	cpu_tick1 = __rdtsc(); // ����� TSC
	while (clock() == clock_tick2); // ������ ������ ���� clock
	cpu_tick2 = __rdtsc() - cpu_tick1; // ������� ������� ������� TSC �� ���� ��� clock
									   // ��������� ������� � ���������� 
	usec = 1000000.0 / CLOCKS_PER_SEC; // ����� ������ ���� clock � �������������
	return int(cpu_tick2 / usec);
}

int main()
{
	int sz;
	clock_t clock_start, clock_time; // ��������� � ���������� ����� ��� clock()
	__int64 hpet_start, hpet_end, hpet_time, // ���������, �������� � ���������� ����� ��� HPET
		hpet_freq; // ������� HPET
	__int64 rdtsc_start, rdtsc_time, // ���������, �������� � ���������� ����� ��� RDTSC
		rdtsc_freq; // ������� �������� ������ (����������) � ����������

	char ans;             // ����� �� ������ � �����������			  
	setlocale(LC_CTYPE, "rus"); // ������������
	cout << "���������� clock()\n\n";
	sz = SIZE_DARR; // ������ ����� ������������ �������� ��������� �������

	int n, i;

	do { // ���������, ����� ������� ������������� �����������
		for (double eps = 0.1; eps >= 0.0000000001; eps *= 0.1) {
			clock_start = clock(); // ��������� �������
			for (int i = 0; i < SIZE_DARR; i++)
				Newton(0.5, eps);
			clock_time = clock() - clock_start; // ����� �����������������
			cout << clock_time << " ���� " << eps << "eps" << endl;
		}
		cout << "\n���������(1 - ��)?: ";
		cin >> ans; // ���� ������ �� ������
	} while (ans == '1');

	cout << "\n���������� RDTSC\n\n";
	sz = SIZE_DARR; // ������ ����� ������������ �������� ��������� �������
	do { // ���������, ����� ������� ������������� �����������
		for (double eps = 0.1; eps >= 0.0000000001; eps *= 0.1) {
			rdtsc_start = __rdtsc();
			for (int i = 0; i < SIZE_DARR; i++)
				Newton(0.5, eps);
			rdtsc_time = __rdtsc() - rdtsc_start;
			cout << rdtsc_start << ": " << rdtsc_time << " ������";
			rdtsc_freq = mhz_cpu();
			cout << "\n����� = " << int(0.001 * rdtsc_time / rdtsc_freq) << " ���� " << eps << "eps";
		}
		cout << "\n���������(1 - ��)?: ";
		cin >> ans; // ���� ������ �� ������
	} while (ans == '1');
	printf("\n���������");
	return 0;
}