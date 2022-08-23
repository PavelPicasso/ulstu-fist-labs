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
int strcmp(char *c_str1, char *c_str2)
{
	if (MyStrlen(c_str1) < MyStrlen(c_str2)) {
		for (int i = 0; i < MyStrlen(c_str2); i++) {
			if (c_str1[i] == c_str2[i]) {
				continue;
			}
			else
			{
				if (c_str1[i] > c_str2[i]) {
					return 1;
				}
				else
					return -1;
			}
		}
	}
	if (MyStrlen(c_str1) > MyStrlen(c_str2)) {
		for (int i = 0; i < MyStrlen(c_str1); i++) {
			if (c_str1[i] == c_str2[i]) {
				continue;
			}
			else
			{
				if (c_str1[i] > c_str2[i]) {
					return 1;
				}
				else
					return -1;
			}
		}
	}
	if (MyStrlen(c_str1) == MyStrlen(c_str2)) {
		for (int i = 0; i < MyStrlen(c_str2); i++) {
			if (c_str1[i] == c_str2[i]) {
				continue;
			}else
			{
				if (c_str1[i] > c_str2[i]) {
					return 1;
				}
				else
					return -1;
			}
		}
		return 0;
	}
}

void main() {
	setlocale(LC_ALL, "Rus");
	char string_1[100];
	char string_2[100];
	printf("�������� ��� ������ ��� ���������: \n");
	scanf("%s", &string_1);
	scanf("%s", &string_2);
	int compare = strcmp(string_1, string_2);
	if (compare == 1) {
		printf("�������� = %d \n�������� ������ ���� ��������� �� ��, ���\n", compare);
		printf("C����� <%s> ������ ������ <%s> \n", string_1, string_2);
	}
	if (compare == 0) {
		printf("�������� = %d \n������� �������� ������� � ���, ��� ��� ������ �����.\n", compare);
		printf("C����� <%s> ����� <%s> \n", string_1, string_2);
	}
	if (compare == -1) {
		printf("�������� = %d \n�������� ������ ���� ��������� �� ��, ���\n", compare);
		printf("C����� <%s> ������ <%s> \n", string_1, string_2);
	}
	system("pause");
}