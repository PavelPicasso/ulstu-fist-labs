
#include "cuda_runtime.h"
#include "device_launch_parameters.h"

#include <iostream>
#include <math.h>

#include <time.h>
#include <cuda_runtime.h>
using namespace std;

#define SIZE_DARR 1000000
float hres[SIZE_DARR] = { 0 };
float hh[1] = { 0 };
float* dres, *dh;
float gpuTime;

int threadsPerBlock = 1024;
int blocksPerGrid = (SIZE_DARR + threadsPerBlock - 1) / threadsPerBlock;


__global__ void CalcIntegralGPU(int n, float* dres, float* dh)
{
	float a = 0.0;
	float b = 1.0;
	float x = 0.0;

	dh[0] = (b - a) / n;

	int tid = threadIdx.x + blockIdx.x * blockDim.x;
	
	while (tid < n)
	{
		x = a + dh[0] * (tid + 0.5);
		dres[tid] += (exp(x) + exp(-x)) / 2;
		tid += blockDim.x * gridDim.x;		
	}	

}

float InFunction(float x) //Подынтегральная функция
{
	return (exp(x) + exp(-x)) / 2;
}

float CalcIntegral(int n)
{
	int i;
	float sum, h;
	float a = 0.0;
	float b = 1.0;

	sum = 0.0;

	// n - количество отрезков интегрирования
	h = (b - a) / n; //Шаг сетки

	for (i = 0; i < n; i++) {
		sum += InFunction(a + h * (i + 0.5)); //Вычисляем в средней точке и добавляем в сумму
	}

	sum *= h;

	return sum;
}

void experiment(int n)
{
	hres[SIZE_DARR] = { 0 };
	hh[1] = { 0 };

	cudaMalloc((void**)&dres, sizeof(float) * SIZE_DARR);
	cudaMalloc((void**)&dh, sizeof(float));
	cudaMemcpy(dres, hres, sizeof(float) * SIZE_DARR, cudaMemcpyKind::cudaMemcpyHostToDevice);
	cudaMemcpy(dh, hh, sizeof(float) * SIZE_DARR, cudaMemcpyKind::cudaMemcpyHostToDevice);

	cudaEvent_t start, stop;
	gpuTime = 0.0f;

	cout << endl << n << " Элементов" << endl << "Время (ms) \n";

	cudaEventCreate(&start);
	cudaEventCreate(&stop);

	cudaEventRecord(start);
		
	CalcIntegralGPU<<<blocksPerGrid, threadsPerBlock>>> (n, dres, dh);
	cudaThreadSynchronize();

	cudaEventRecord(stop);
	cudaEventSynchronize(stop);
	cudaDeviceSynchronize();

	cudaEventElapsedTime(&gpuTime, start, stop);

	cudaEventDestroy(start);
	cudaEventDestroy(stop);


	cudaMemcpy(hres, dres, sizeof(float) * SIZE_DARR, cudaMemcpyKind::cudaMemcpyDeviceToHost);
	cudaMemcpy(hh, dh, sizeof(float), cudaMemcpyKind::cudaMemcpyDeviceToHost);
	
	float result = 0.0f;
	for (int i = 0; i < n; i++)
	{
		result += hres[i];
	}
	result *= hh[0];

	cout.width(10);
	cout.setf(ios::right);
	cout << gpuTime << endl;

	cudaFree(dres);
	cudaFree(dh);
}

int main()
{
	setlocale(LC_CTYPE, "rus");
	long long freq = CLOCKS_PER_SEC;

	for (int i = 100; i <= SIZE_DARR; i *= 10)
	{
		long long st = clock();
		CalcIntegral(i);
		st = clock() - st;
		cout << endl << i << " Элементов" << endl << "Время (ms) \n";
		cout.width(10);
		cout.setf(ios::right);
		cout << 1000 * st / freq << endl;
	}


	cout << endl << "CUDA kernel launch with " << blocksPerGrid << " blocks of " << threadsPerBlock << " threads" << endl;

	for (int i = 100; i <= SIZE_DARR; i *= 10)
	{
		experiment(i);
	}

	getchar();
    return 0;
}