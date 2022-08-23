#include <time.h> // ��� clock(), clock_t, CLOCKS_PER_SEC
#include <intrin.h> // ��� __rdtsc()
#include <windows.h> // ��� ������� ������������� HPET QueryPerformanceCounter(&tackts) � QueryPerformanceFrequency(&freq)
#include <math.h>
#include <iostream>
#include <string>

using namespace std;

#define SIZE_DARR 100000000

int dummy() {
	return 0;
}

double arr[SIZE_DARR];
void my_func(int count) { // ��������� ������ ��������
	for (int i = 0; i < count; i++) {
		arr[i] = (exp(i) + exp(-i)) / 2;
	}
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

	int n;


	do { // ���������, ����� ������� ������������� �����������
		clock_start = clock(); // ��������� �������

							   //my_func(sz);
		for (int i = 0; i < 1; i++)
			dummy();
		/*
		cin >> n;
		for(int i = 0; i < n; i++)
		dummy();
		*/

		clock_time = clock() - clock_start; // ����� �����������������
		cout << clock_start << ": " << clock_time << " ���� ������������� ������ ������ " << SIZE_DARR << endl;
	} while (sz != 0);

	cout << "\n���������� HPET\n\n";
	sz = SIZE_DARR; // ������ ����� ������������ �������� ��������� �������
	do { // ���������, ����� ������� ������������� �����������
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
			cout << hpet_start << ": " << hpet_time << " ������ ������������� ������ ������ " << sz;
			QueryPerformanceFrequency((LARGE_INTEGER *)&hpet_freq);
			cout << "\n������� = " << hpet_freq << "  ����� = " << int(1000 * hpet_time * 1.0 / hpet_freq) << " ����\n";
		}
		cout << "\n���������(1 - ��)?: ";
		cin >> ans; // ���� ������ �� ������
	} while (ans == '1');

	cout << "\n���������� RDTSC\n\n";
	sz = SIZE_DARR; // ������ ����� ������������ �������� ��������� �������
	do { // ���������, ����� ������� ������������� �����������
		rdtsc_start = __rdtsc();

		my_func(sz);
		/*
		for (int i = 0; i < SIZE; i++)
		dummy();
		*/

		rdtsc_time = __rdtsc() - rdtsc_start;
		cout << rdtsc_start << ": " << rdtsc_time << " ������ ������������� ������ ������ " << sz;
		rdtsc_freq = mhz_cpu();
		cout << "\n����� = " << int(0.001 * rdtsc_time / rdtsc_freq) << " ����   ������� = " << rdtsc_freq << " ���";
		cout << "\n���������(1 - ��)?: ";
		cin >> ans; // ���� ������ �� ������
	} while (ans == '1');
	printf("\n���������");
	return 0;
}



/*

5 ��� � ����� min

t = clock() + 1000;
while(clock < t);


��������� ���������� ��� dummy
*/