#include <stdio.h>
#include <conio.h>
#include <string.h>
#include <stdlib.h>

struct Elem {
	char *Data;
	Elem *next;
	Elem *prev;
};
Elem *head = NULL;
Elem *tail = NULL;

void printListFromtail() {
	struct Elem *ptr = tail;
	while (ptr != NULL) {
		printf("(%s) ->", ptr->Data);
		ptr = ptr->prev;
	}
}
void printListFromhead() {
	struct Elem *ptr = head;
	while (ptr != NULL) {
		printf("(%s) ->", ptr->Data);
		ptr = ptr->next;
	}
}

void addToHead(char *value) {
	Elem *newElem = (Elem*)malloc(sizeof(Elem));
	newElem->next = head;
	newElem->prev = NULL;
	newElem->Data = value;
	head->prev = newElem;
	head = newElem;
}




void main() {

	bool flag = true;
	char Str[255];
	gets_s(Str);
	char *Token;

	Token = strtok(Str, " ");
	Elem *newElem = (Elem*)malloc(sizeof(Elem));
	newElem->next = NULL;
	newElem->prev = NULL;
	newElem->Data = Token;
	head = newElem;
	tail = newElem;
	Token = strtok(NULL, " ");

	while (flag) {
		while (Token != NULL) {
			addToHead(Token);
			Token = strtok(NULL, " ");
			if (strcmp(Token, "stop") == 0) {
				flag = false;
				break;
			}
		}
	}

	printListFromhead();
	printf("\n");
	printListFromtail();
	system("pause");
}
