#include "/usr/include/mpi/mpi.h"
#include <stdio.h>
#include <math.h>
#include <clocale> 
#include <iostream>
#include <clocale>  
using namespace std;


static int KRepeat = 10;
static int IRepeat = 100;

double h;

double f(double x)
{
	return (exp(x) + exp(-x)) / 2;
}

int main(int argc, char *argv[])
{
	setlocale(LC_CTYPE, "rus");
	int  myid, numprocs, i;
	double myy, y, h, sum, x;
	int  namelen;
	char processor_name[MPI_MAX_PROCESSOR_NAME];
	double Integral = 0.0, Res = 0.0;
	double tmin = 100000.0, t;
	int num_hs;

	MPI_Init(&argc, &argv); // Инициализация подсистемы MPI
	MPI_Comm_size(MPI_COMM_WORLD, &numprocs); // Получить общее число процессов
	MPI_Comm_rank(MPI_COMM_WORLD, &myid); // Получить номер текущего процесса
	
	for (num_hs = 1000000; num_hs >= 100; num_hs /= 10)
	{
		printf("Granulyarnost: %d\n", num_hs);
		sum = 0.0;
		h = 1.0 / (double)num_hs;
		int n = num_hs - 1 
		double t1, t2;
		// чисто последовательное исполнение
		t1 =  MPI_Wtime();
		sum = 0.5 * (f(1.0) + f(2.0));
		for (i = 1; i <= n; i++)
		{
			x = 1 + (double)i * h;
			sum = sum + f(x);
		}
		y = h * sum;
		t2 = MPI_Wtime();
		s = 1;
		printf("Bez rassparall: y = %10.6f; t = %4.8f c; koeff uskoren = %4.3f \n", y, t2 - t1, s);
		s = t2 - t1;
		Res = 0.0;
		for (int k = 0; k < KRepeat; k++)
		{
			tmin = 100000.0;
			Integral = 0.0;
			t1 = MPI_Wtime();
			for (int i1 = 0; i1 < IRepeat; i1++)
			{
				sum = 0.5 * (f(1.0) + f(2.0));
				// Рассылка интервалов интегрирования процессам
				MPI_Bcast(&n, 1, MPI_INT, 0, MPI_COMM_WORLD); 
				for (i = myid + 1; i <= n; i += numprocs)
				{
					x = 1 + (double)i * h;
					sum = sum + f(x);
				}
				myy = h * sum;
				// Сбор результатов со всех процессов и сложение
				MPI_Reduce(&myy, &y, 1, MPI_DOUBLE, MPI_SUM, 0, MPI_COMM_WORLD); 
				Integral += y;
			}
			t2 = MPI_Wtime();
			t = (double)(t2 - t1) / IRepeat;
			if (t < tmin)
				tmin = t;
			Integral /= IRepeat;
			Res += Integral;
		}
		Res /= KRepeat;
		if (myid == 0)
		printf("kol potokov(%d): y = %10.6f; t = %4.8f c; koeff uskoren = %4.3f \n", numprocs, Res,  tmin, s / tmin);
	}
	MPI_Finalize();// Освобождение подистемы MPI
	return 0;
}
