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

char* MyStrcat(char string_1[], char c_str2[]) {
	int len1 = MyStrlen(string_1);
	int len2 = MyStrlen(c_str2);
	char *str = new char[len1 + len2 + 1];
	for (int i = 0; i < len1; i++) {
		str[i] = string_1[i];
	}
	for (int i = 0; i < len2; i++) {
		str[len1 + i] = c_str2[i];
	}
	str[len1 + len2] = '\0';
	return str;
}


void main(){
	setlocale(LC_ALL, "Rus");
	int len = 0;
	char str[255];
	printf("Ввведите строку: ");
	gets_s(str);
	len = MyStrlen(str);
	printf("Длина строки %s = %d \n", str, len);
	char string_1[100];
	char string_2[100];
	printf("Ввведите две строки для слияния: ");
	scanf("%s", &string_1);
	scanf("%s", &string_2);
	printf("Сълияние двух строк с помощью strcat: ");
	MyStrcat(string_1, string_2);
	printf("%s", MyStrcat(string_1, string_2));

	system("pause");
}