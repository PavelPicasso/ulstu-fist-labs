#include <stdio.h>
#include <Conio.h>
#include <iostream>
#include <windows.h>
#include <string>
using namespace std;



struct MYPOINT
{
	int x, y;
	int F;
	char s[255];
	int R, G, B;

};

void main() {
	SetConsoleCP(1251);
	SetConsoleOutputCP(1251);
	int n;
	printf("Введите колличество структур : ");
	scanf("%d", &n);
	MYPOINT *points = new MYPOINT[n];
	FILE *f;
	f = fopen("C:\\te\\output\\lab 12(D).bin", "wb");
	for (int i = 0; i < n; i++) {
		printf("Координаты X  Y : ");
		scanf("%d %d", &points[i].x, &points[i].y);
		printf("Размер шрифта и сам текст: ");
		scanf("%d ", &points[i].F);	
		gets_s(points[i].s);
		printf("Цвет надписи R  G  B : ");
		scanf("%d %d %d", &points[i].R, &points[i].G, &points[i].B);
	}
	fwrite(&n, sizeof(int), 1, f);
	fwrite(points, sizeof(MYPOINT), n, f);
	fclose(f);
}