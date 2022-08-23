#include <iostream>
#include <cmath>
#include <mpi.h>
#include <time.h>
using namespace std;
float f(float x) {
	return 1 / sin(x);
}
double integral(int n, double a, double b)
{
	int i;
	double result, h;
	result = 0;
	h = (b - a) / n; //Шаг сетки
	for (i = 0; i < n; i++)
	{
		result += f(a + h * (i + 0.5)); //Вычисляем в средней точке и добавляем в сумму
	}
	result *= h;
	return result;
}
int main(int argc, char *argv[]){
    int n = 1000;
    double a = 1, b = 3;
    double res = 0;
    int process_id;
    int ierr;
    int process_num;
    MPI_Status status;
    int master = 0;
    MPI_Init (&argc, &argv);
    ierr = MPI_Comm_rank (MPI_COMM_WORLD, &process_id); //
    /*
Получение количества процессоров
*/
    ierr = MPI_Comm_size (MPI_COMM_WORLD, &process_num);
    double proc_arg[3];
    double delta = fabs(a - b) / (process_num);
    double proc_n = (double) n / (process_num);
    int tag = 1;
    clock_t start = clock();
    if(process_id == master){
        cout << "processors count " << process_num << endl;
        for (int process = 1; process < process_num; process++)
        {
            proc_arg[0] = a + delta * (process);
            proc_arg[1] = proc_arg[0] + delta;
            proc_arg[2] = proc_n;
            //x1=proc_arg[0]  x2= proc_arg[1]
            ierr = MPI_Send (proc_arg, 3, MPI_DOUBLE, process, tag, MPI_COMM_WORLD);
			//
        }
        
    } else{
        ierr = MPI_Recv(proc_arg, 3, MPI_DOUBLE, master, tag, MPI_COMM_WORLD, &status);
        //
    }
    ierr = MPI_Barrier (MPI_COMM_WORLD);
    if(process_id != master){
        double res_l = integral(proc_arg[2], proc_arg[0], proc_arg[1]);
        int target = master;
        tag = 2;
        ierr = MPI_Send (&res_l, 1, MPI_DOUBLE, target, tag, MPI_COMM_WORLD);
    } else {
        res = integral(proc_n, a, a+delta); // master process
        for(int i = 0; i < process_num - 1; i++){
            double res_l = 0;
            tag = 2;
            ierr = MPI_Recv (&res_l, 1, MPI_DOUBLE, MPI_ANY_SOURCE, tag, MPI_COMM_WORLD, &status);
            res += res_l;
        }
        clock_t end = clock();
        float during = ((double)(end - start) / CLOCKS_PER_SEC);
        cout << "res " << res << "; time " << during << endl;
    }
    ierr = MPI_Finalize ();
    return 0;
}
