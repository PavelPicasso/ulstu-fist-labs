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
	f = fopen("C:\\te\\cipher decode.txt", "rt");
	f1 = fopen("C:\\te\\result.txt", "wt");
	while (!feof(f)) { //пока _не_ конец 
		char ch = ' ';
		fscanf_s(f, "%c", &ch);

		if (ch == ' ') {
			fprintf(f1, " ");
			continue;
		}

		if (ch >= 'А' && ch <= 'В') {
			ch = ch - key + rus;
			fprintf(f1, "%c", ch);
			continue;
		}

		if (ch >= 'A' && ch <= 'C') {
			ch = ch - key + eng;
			fprintf(f1, "%c", ch);
			continue;
		}

		ch = ch - key;
		fprintf_s(f1, "%c", ch);

	}

	fclose(f);
	fclose(f1);

}
