#include <stdio.h>
#include <Conio.h>
#include <iostream>
#include <windows.h>
#include <string>
using namespace std;

void main() {
	char s[255] = {};
	FILE *f, *f2;
	char *Token;
	bool enter = true;
	f = fopen("C:\\te\\lab11.txt", "rt");
	f2 = fopen("C:\\te\\output.html", "wt");
	fprintf(f2, "%s ", "<HTML>\n  ""<HEAD>\n   ""<TITLE>Самый простой HTML-документ</TITLE>\n  ""</head>\n""  <body>\n");
	fprintf(f2, "%s ", " <H1>");

	int i = 0;
	char s2[255] = {};

	while (!feof(f)) {

		s[i] = fgetc(f);

		if (s[i] == '\n') {
			if (enter) {
				fprintf(f2, "%s ", " <BR></H1>");
				enter = false;
			}
			else {
				fprintf(f2, "%s ", " <BR>");
			}
		}

		strcat(s2, s);
		if (s[i] == ' ') {
			Token = strtok(s2, " ");
			if (strchr(Token, 'а') || strchr(Token, 'А')) {
				fprintf(f2, "<b>NOP</b> ");
				s2[0] = 0;
				continue;
			}else
			{
				fprintf(f2, "%s ", Token);
				s2[0] = 0;
				continue;
			}
		}
	}


	printf("%s ", " <BR>\n");
	printf("%s ", " </body>\n");
	printf("%s", "</HTML>");
}

