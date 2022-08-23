#define _CRT_SECURE_NO_WARNINGS 
#include <stdio.h>
#include <Conio.h>
#include <windows.h>

int InputArray(int arr[], int n) {
	FILE *f;
	f = fopen("C:\\te\\text.txt", "rt");
	fscanf_s(f, "%d", &n);
	for (int i = 0; i < n; i++) {
		fscanf_s(f, "%d", &arr[i]);
	}
	return n;
	fclose(f);
}
int Updatemin(int arr[], int n) {
	int min = arr[0];
	for (int i = 0; i < n; i++) {
		if (min > arr[i]) {
			min = arr[i];
		}
	}
	return min;
}
int Updateimin(int arr[], int n) {
	int imin = 0;
	int min = arr[0];
	for (int i = 0; i < n; i++) {
		if (min > arr[i]) {
			imin = i;
		}
	}
	return imin;
}

int Updatemax(int arr[], int n) {
	int max = arr[0];
	for (int i = 0; i < n; i++) {
		if (max < arr[i]) {
			max = arr[i];
		}
	}
	return max;
}
int Updateimax(int arr[], int n) {
	int imax = 0;
	int max = arr[0];
	for (int i = 0; i < n; i++) {
		if (max < arr[i]) {
			imax = i;
		}
	}
	return imax;
}

void Outputresult(int arr[], int imin, int imax) {
	FILE *f, *f1;
	f = fopen("C:\\te\\fd.txt", "at");
	f1 = fopen("C:\\te\\text.txt", "rt");
	for (int i = imin; i < imax; i++) {
		if (arr[i] % 2 == 0) {
			fprintf(f, "%d ", arr[i]);
		}
	}
	fprintf(f, "\n");
	fclose(f);
	fclose(f1);
}

void OutputArray(int arr[], int n, int min, int max) {
	FILE *f;
	f = fopen("C:\\te\\fd.txt", "wt");
	fprintf(f, "n = %d", n);
	fprintf(f, "\n");
	fprintf(f, "min = %d", min);
	fprintf(f, "\n");
	fprintf(f, "max = %d", max);
	fprintf(f, "\n");
	fclose(f);
}

int main() {
	int *a;
	int n;
	int min, max;
	int imax, imin ;
	FILE *f;
	f = fopen("C:\\te\\text.txt", "rt");
	fscanf_s(f, "%d", &n);
	fclose(f);
	a = (int*)malloc(n * sizeof(int));
	InputArray(a, n);
	imin = Updateimin(a, n);
	min = Updatemin(a, n);
	imax = Updateimax(a, n);
	max = Updatemax(a, n);
	OutputArray(a, n, min, max);
	Outputresult(a, imin, imax);
//	free(a);
}

