#include <stdio.h>
#include <conio.h>
#include <string.h>
#include <stdlib.h>
#include <windows.h>

struct Elem {
	char name[25];
	int phone;
	struct Elem *next;
};
struct Elem *listbegin = NULL;

void printList() {
	struct Elem *ptr = listbegin;
	while (ptr != NULL) {
		printf("(%s) ->", ptr->name);
		printf("(%d) ->", ptr->phone);
		ptr = ptr->next;
	}
}
void addToHead(char str[], int nomer){
	Elem *newElem = (Elem*)malloc(sizeof(Elem));
	newElem->next = listbegin;
	strcpy(newElem->name, str);
	newElem->phone = nomer;
	listbegin = newElem;
}

void clearList()
{
	while (listbegin != NULL)
	{
		struct Elem *delNode = listbegin;
		listbegin = listbegin->next;
		free(delNode);
	}
}

int contains(int value)
{
	struct Elem *ptr = listbegin;
	while (ptr != NULL) {
		if (value == ptr->phone) {
			printf("%s", ptr->name);
			return;
		}
		else ptr = ptr->next;
	}
	printf("Not found");
	return;
}

void delete_cotains() {
	int PHone;
	scanf("%d", &PHone);
	struct Elem *i = listbegin;
	struct Elem *j = nullptr;
	while (i != nullptr){
		if (i->phone == PHone){
			if (j != nullptr)
				j->next = i->next;
			else {
				listbegin = i->next;
			}
			delete i;
			return;
			}
		j = i;
		i = i->next;
	}
}


void menu() {
	printf("1. Показать справочник");
	printf("\n");
	printf("2. Добавить запись");
	printf("\n");
	printf("3. Найти по номеру");
	printf("\n");
	printf("4. Удалить по номеру");
	printf("\n");
	printf("5. Очистить справочник");
	printf("\n");
	printf("6. выход");
	printf("\n");


	int number;
	scanf("%d", &number);
	switch (number)
	{
	int Phone;
	case 1:
		printList();
		break;
	case 2:
		while (1) {
			char Name[25];
			scanf("%s", &Name);
			if (strcmp(Name, "stop") == 0) {
				break;
			}
			scanf("%d", &Phone);
			addToHead(Name, Phone);
		}
		break;
	case 3:
		scanf("%d", &Phone);
		contains(Phone);
		break;
	case 4:
		delete_cotains();
		break;
	case 5:
		clearList();
		break;
	case 6:
		exit(0);
		break;
	}
	printf("\n");
	menu();
}



void main() {
	SetConsoleCP(1251);
	SetConsoleOutputCP(1251);

	menu();
}