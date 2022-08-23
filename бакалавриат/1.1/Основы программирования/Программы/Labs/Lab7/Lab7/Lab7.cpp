#include <stdio.h>
#include <Conio.h>


int InputArray(int arr[]) {
	int n;
	printf("Please enter a number:\n");
	scanf_s("%d", &n);
	printf("Vvedite element9i massiva:\n");
	for (int i = 0; i < n; i++) {
		scanf_s("%d", &arr[i]);
	}
	return n;
}

void UpdateArray(int arr[], int n) {
	int min = arr[0];
	int max = arr[0];
	int imax = 0, imin = 0;
	for (int i = 0; i < n; i++) {
		if (max < arr[i]) {
			max = arr[i];
			imax = i;
		}
		if (min > arr[i]) {
			min = arr[i];
			imin = i;
		}
	}

	printf("min: %d", min);
	printf("\n");
	printf("max: %d", max);
	printf("\n");
	printf("Chetn9ie element9i massiva ot min do max");
	printf("\n");

	for (int i = imin; i < imax; i++) {
		if (arr[i] % 2 == 0) {
			printf("%d ",arr[i]);
		}
	}
}



int main() {
	int a[10];
	int n = InputArray(a);
	UpdateArray(a, n);
	_getch();
}

