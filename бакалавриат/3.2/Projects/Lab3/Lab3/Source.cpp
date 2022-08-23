#include <math.h>
#include <iostream>
#include <omp.h>
using namespace std;

#define SIZE_DARR 1000000
double stime, ftime; // время начала и конца рассчета

// значение вычисленного интеграла
double res = 0.0;

double InFunction(double x) //Подынтегральная функция
{
	return (exp(x) + exp(-x)) / 2;
}

double CalcIntegral(double a, double b, int n, int flow)
{
	int i;
	double sum, h;

	sum = 0.0;

	// n - количество отрезков интегрирования
	h = (b - a) / n; //Шаг сетки

	omp_set_dynamic(0);
	#pragma omp parallel for private(i) reduction(+:sum) num_threads(flow)
	for (i = 0; i < n; i++) {
		sum += InFunction(a + h * (i + 0.5)); //Вычисляем в средней точке и добавляем в сумму
	}

	sum *= h;

	return sum;
}

double experiment(int i, int flow)
{
	double a = 1.0; // левая граница интегрирования
	double b = 8.0; // правая граница интегрирования

	
	double tmin = 100000000.0;
	for(int k = 1; k <= 100000000 / i; k++) 
	{
		stime = omp_get_wtime();

		// вызов функции интегрирования
		res = CalcIntegral(a, b, i, flow);

		ftime = omp_get_wtime();

		if (tmin > ftime - stime)
			tmin = ftime - stime;
	}
	
	return tmin;
}

int main() 
{
	setlocale(LC_CTYPE, "rus");

	int flow, i;

	// время проведенного эксперимента
	double time;
	
	// speedup
	for (flow = 1; flow <= 8; flow++) 
	{
		for (i = SIZE_DARR; i <= SIZE_DARR; i *= 10)
		{
			//stime = 0.0, ftime = 0.0, res = 0.0;

			cout << endl << "Поток " << flow << ": " << i << " элементов" << endl << "Время (с) \tРезультат \t\n";

			time = experiment(i, flow);

			cout.width(10);
			cout.setf(ios::right);
			cout << time << "\t" << res << endl;
		}		
	}
	
	system("pause");
	return 0;
}