#include <stdio.h>
#include <stdlib.h>

struct Elem{
	int Data; 
	Elem *next;
};
Elem *listbegin = NULL;

void printList() {
	struct Elem *ptr = listbegin;
	while (ptr != NULL) {
		printf("(%d) ->", ptr->Data);
		ptr = ptr->next;
	}
}

void addToHead(int value) {
	Elem *newElem = (Elem*)malloc(sizeof(Elem));
	newElem->next = listbegin;
	newElem->Data = value;
	listbegin = newElem;
}




void main() {
	int chislo = scanf("%d", &chislo);;

	while (chislo != 0) {
		addToHead(chislo);
		scanf("%d", &chislo);
	}

	printList();
	system("pause");
}
