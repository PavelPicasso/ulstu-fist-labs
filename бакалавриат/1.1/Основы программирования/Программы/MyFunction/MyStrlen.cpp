#include <stdio.h>
#include <stdlib.h>
#include <locale.h>

int MyStrlen(char arr[]) {
	int len = 0;
	for (int i = 0; arr[i] != '\0'; i++) {
		len++;
	}
	return len;
}

void main(){
	setlocale(LC_ALL, "Rus");
	int len = 0;
	char str[255];
	printf("�������� ������: ");
	gets_s(str);
	len = MyStrlen(str);
	printf("����� ������ %s = %d \n", str, len);
	
	system("pause");
}