#include <stdio.h>
#include <algorithm>
#include <stdlib.h>
#include <iostream> 
using namespace std;

struct Human {
	char Firstname[20];
	int age;
};

/* 
	��������� ������� ������� ���������� �������� ��������� ��������� ���� �����
	�.� ���� � �������� � ������� ������ ��� � �������� b �� ������ 1, � �������� ������ 0 
*/
bool compere(Human a, Human b) {
	return a.age < b.age;
}

int main() {
	setlocale(LC_ALL, "rus");

	Human people[4];
	
	int n;
	printf("������� ����������� �������:\n");
	scanf("%d", &n);

	for (int i = 0; i < n; i++) {
		printf("������� ���: ");
		scanf("%s", &people[i].Firstname);
		printf("������� �������: ");
		scanf("%d", &people[i].age);
	}

	// �������� ����� �� �������� ��������� ������� compare
	sort(people, people + n, compere);

	printf("��������� ����� ���������� �� ����� age\n");

	for (int i = 0; i < n; i++) {
		printf("%s ", people[i].Firstname);
		printf("%d\n", people[i].age);
	}
	
}
