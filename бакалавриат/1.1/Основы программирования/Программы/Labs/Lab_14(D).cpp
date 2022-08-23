#include <stdio.h>
#include <conio.h>
#include <string.h>
#include <stdlib.h>
#include <windows.h>
#include <iostream>
using namespace std;

struct Elem {
	char name[25];
	int phone;
	struct Elem *next;
	struct	Elem *prev;
};
struct Elem *listbegin = NULL;
struct Elem *tail = NULL;

void printListFromhead() {
	struct Elem *ptr = listbegin;
	while (ptr != NULL) {
		printf("(%s) ->", ptr->name);
		printf("(%d) ->", ptr->phone);
		ptr = ptr->next;
	}
}
void printListFromtail() {
	struct Elem *ptr = tail;
	while (ptr != NULL) {
		printf("(%s) ->", ptr->name);
		printf("(%d) ->", ptr->phone);
		ptr = ptr->prev;
	}
}
void addToHead(char str[], int nomer){
	Elem *newElem = (Elem*)malloc(sizeof(Elem));
	newElem->next = listbegin;
	newElem->prev = NULL;
	strcpy(newElem->name, str);
	newElem->phone = nomer;
	listbegin->prev = newElem;
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
			return 1;
		}
		else ptr = ptr->next;
	}
	printf("Not found");
	return 0;
}


void delete_unit(int value)
{
    struct Elem *ptr =listbegin;
    while(ptr){
        if(ptr->phone == value)
        {
			if(ptr==listbegin){
				listbegin=listbegin->next;
				if(listbegin)
					listbegin->prev=NULL;
                    else
                        tail=NULL;
                    delete ptr;
                    break;
                }
                if (ptr==tail){
                    tail=tail->prev;
                    if(tail)
                        tail->next=NULL;

                    delete ptr;
                    break;
                }
                ptr->prev->next=ptr->next;
                ptr->next->prev=ptr->prev;
            delete ptr;
            break;                   
        }	
          ptr=ptr->next;
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
			printListFromhead();
			printf("\n");
			printListFromtail();
			break;
	case 2:
		while (1) {
		char NamE[25];
			scanf("%s", &NamE);
			scanf("%d", &Phone);
			Elem *newElem = (Elem*)malloc(sizeof(Elem));
			newElem->next = NULL;
			newElem->prev = NULL;
			strcpy(newElem->name, NamE);
			newElem->phone = Phone;
			listbegin = newElem;
			tail = newElem;
			break;
		}
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
		scanf("%d", &Phone);
		delete_unit(Phone);
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
	//SetConsoleCP(1251);
	//SetConsoleOutputCP(1251);
	setlocale(LC_ALL, "Russian");
	menu();
}