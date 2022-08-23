#include <stdio.h>

int main() {
	int n = 5;
	int mass[5] = { 3, 1, 5, 2, 4 };

	int i, j, step;
	int tmp;
	for (step = n / 2; step > 0; step /= 2)
		for (i = step; i < n; i++) {
			tmp = mass[i];
			for (j = i; j >= step; j -= step) {
				if (tmp < mass[j - step])
					mass[j] = mass[j - step];
				else
					break;
			}
			mass[j] = tmp;
		}

	return 0;
}