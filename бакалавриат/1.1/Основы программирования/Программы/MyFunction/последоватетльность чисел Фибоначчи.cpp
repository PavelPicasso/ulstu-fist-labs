#include <stdio.h>
#include <locale.h>
#include <stdlib.h>

void main() {
	setlocale(LC_ALL, "Rus");
	int n;
	printf("������� ������ �������������������: ");
	scanf("%d", &n);
	int *mas = new int[n];	
	int numb1, numb2;
	printf("������� ������ 2 �������� �������������������: ");
	scanf("%d %d", &numb1, &numb2);
	mas[0] = numb1;
	mas[1] = numb2;
	for (int i = 2; i < n; i++) {
		mas[i] = mas[i - 1] + mas[i - 2];
	}
	printf("������� ������������������� ����� ���������: \n");
	for (int i = 0; i < n; i++) {
		printf("%d ", mas[i]);
	}
	delete [] mas;
	system("pause");
}