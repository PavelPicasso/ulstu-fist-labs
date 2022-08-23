#define _CRT_SECURE_NO_WARNINGS 
#include <stdio.h> 
#include <conio.h> 
#include <iostream> 
using namespace std;

#define key 3 
#define rus 32 
#define eng 26 

void main() {
	setlocale(LC_CTYPE, "rus");
	FILE *f, *f1;
	f = fopen("C:\\t\\cipher code.txt", "rt");
	f1 = fopen("C:\\t\\cipher decode.txt", "wt");
	while (!feof(f)) { //пока не конец 
		char ch = ' ';
		fscanf(f, "%c", &ch);
		if (ch == ' ') {
			fprintf(f1, " ");
			continue;
		}

		if (ch >= 'Э' && ch <= 'Я') {
			ch = ch + key - rus;
			fprintf(f1, "%c", ch);
			continue;
		}

		if (ch >= 'X' && ch <= 'Z') {
			ch = ch + key - eng;
			fprintf(f1, "%c", ch);
			continue;
		}

		if (ch >= 'x') { // z 122 
			ch = ch - eng + key;
			fprintf(f1, "%c", ch);
			continue;
		}

		if (ch >= -3 && ch <= -1) { // -1 -2 -3 я ю э 
			ch = ch - rus + key;
			fprintf(f1, "%c", ch);
			continue;
		}

		ch = ch + key;
		fprintf(f1, "%c", ch);

	}
	fclose(f);
	fclose(f1);
}