#include <stdio.h>
#include <Conio.h>
#include <iostream>
#include <windows.h>
#include <string>
using namespace std;

void main() {
	char s[255];
	bool flag = 0;
	freopen("lab11(D).txt", "r", stdin);
	freopen("output.html", "w", stdout);
	printf("%s ", "<HTML>\n  ""<HEAD>\n   ""<TITLE>Самый простой HTML-документ</TITLE>\n  ""</head>\n""  <body>\n");
	printf("%s ", "   <H1>");
	while (scanf("%s ", &s) != EOF) {
		if (strchr(s, 'а') || strchr(s, 'А')) {
			printf("<b>NOP</b> ");
			continue;
		}
		if (strcmp(s, "<BR>")== 0 && !flag) {
			flag = 1;
			printf("%s ", " </H1>\n");
		}
		printf("%s ", s);
	}

	printf("%s ", " <BR>\n");
	printf("%s ", " </body>\n");
	printf("%s", "</HTML>");
}