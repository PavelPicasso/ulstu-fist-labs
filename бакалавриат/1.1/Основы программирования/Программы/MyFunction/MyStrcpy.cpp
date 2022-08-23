#include <stdio.h>
#include <stdlib.h>
#include <locale.h>
#include <string.h>

int MyStrlen(char arr[]) {
	int len = 0;
	for (int i = 0; arr[i] != '\0'; i++) {
		len++;
	}
	return len;
}

void MyStrspy(char str[], char c_str1[]) {
	str[0] = '\0';
	int len1 = MyStrlen(c_str1);
	for (int i = 0; i < len1; i++) {
		str[i] = c_str1[i];
	}
	str[len1] = '\0';
	printf("%s\n", str);
}

void main(){
	setlocale(LC_ALL, "Rus");
	char q[255];
	char q2[] = "asd";
	scanf("%s", &q);
	MyStrspy(q2,q);

	system("pause");
}