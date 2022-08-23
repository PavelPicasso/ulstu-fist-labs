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
	булевска€ функци€ котора€ возвращает значение сравнени€ возрастов двух людей
	т.е если у человека а возраст меньше чем у человека b то вернет 1, в противно случае 0 
*/
bool compere(Human a, Human b) {
	return a.age < b.age;
}

int main() {
	setlocale(LC_ALL, "rus");

	Human people[4];
	
	int n;
	printf("¬ведите колличество человек:\n");
	scanf("%d", &n);

	for (int i = 0; i < n; i++) {
		printf("¬ведите »м€: ");
		scanf("%s", &people[i].Firstname);
		printf("¬ведите возраст: ");
		scanf("%d", &people[i].age);
	}

	// сортируе людей по возрасту использу€ функцию compare
	sort(people, people + n, compere);

	printf("–езультат после сортировки по ключу age\n");

	for (int i = 0; i < n; i++) {
		printf("%s ", people[i].Firstname);
		printf("%d\n", people[i].age);
	}
	
}
