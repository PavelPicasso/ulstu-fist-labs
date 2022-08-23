#include <stdio.h>
#include <stdlib.h>

struct Elem {
	int Data;
	Elem *next;
	Elem *prev;
};
Elem *head = NULL;
Elem *tail = NULL;

void printListFromtail() {
	struct Elem *ptr = tail;
	while (ptr != NULL) {
		printf("(%d) ->", ptr->Data);
		ptr = ptr->prev;
	}
}
void printListFromhead() {
	struct Elem *ptr = head;
	while (ptr != NULL) {
		printf("(%d) ->", ptr->Data);
		ptr = ptr->next;
	}
}

void addToHead(int value) {
	Elem *newElem = (Elem*)malloc(sizeof(Elem));
	newElem->next = head;
	newElem->prev = NULL;
	newElem->Data = value;
	head->prev = newElem;
	head = newElem;
}

void addToTail(int value) {
	Elem *newElem = (Elem*)malloc(sizeof(Elem));
	newElem->next = NULL;
	newElem->prev = tail;
	newElem->Data = value;
	tail->next = newElem;
	tail = newElem;
}



void main() {
	int chislo = 7;

	scanf("%d", &chislo);
	Elem *newElem = (Elem*)malloc(sizeof(Elem));
	newElem->next = NULL;
	newElem->prev = NULL;
	newElem->Data = chislo;
	head = newElem;
	tail = newElem;
	
	while (chislo != 0) {
		scanf("%d", &chislo);
		if (chislo != 0) {
			addToHead(chislo);
			//addToTail(chislo);
		}
	}

	printListFromhead();
	printf("\n");
	printListFromtail();
	system("pause");
}
