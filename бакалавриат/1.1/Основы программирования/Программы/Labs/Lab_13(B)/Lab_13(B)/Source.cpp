#include <stdio.h>
#include <conio.h>
#include <string.h>
#include <stdlib.h>

struct Elem {
	char *Data;
	Elem *next;
};
struct Elem *listbegin = NULL;

void printList() {
	struct Elem *ptr = listbegin;
	while (ptr != NULL) {
		printf("(%s) ->", ptr->Data);
		ptr = ptr->next;
	}
}

void addToHead(char *value) {
	Elem *newElem = (Elem*)malloc(sizeof(Elem));
	newElem->next = listbegin;
	newElem->Data = value;
	listbegin = newElem;
}




void main() {
	bool flag = true;
	char Str[255];
	gets_s(Str);
	char *Token;
	while (flag) {
		Token = strtok(Str, " ");
		while (Token != NULL) {
				addToHead(Token);
				Token = strtok(NULL, " ");
				if (strcmp(Token, "stop") == 0) {
					flag = false;
					break;
				}
		}
	}

	printList();
	system("pause");
}