#define _CRT_SECURE_NO_WARNINGS 
#include <stdio.h> 
#include <conio.h> 
#include <windows.h>
#include <iostream> 
#include <iomanip>
#include <math.h>
#include <string>
using namespace std;

int masout[33];
int masa[17];
int masb[9];
int masp[7] = { 1,0,0,0,0,0,0 }; // Разумно эти 128 значений разделить поровну между положительными и отрицательными значениями порядка
int i, j, n, p16, dlinna, digit = 0;

void dvoichA(int a) {
	while (a > 0) {
		digit = a % 2;
		masa[i] = digit;
		i++;
		n++;
		dlinna++;
		a = a / 2;
	}
}

void dvoichB(double b) {
	while (dlinna + i < 24) {
		b = b * 2;
		if ((int)b == 1) {
			masb[i] = 1;
			b = abs(b - 1);
		}
		else
		{
			masb[i] = 0;
		}
		i++;
		n++;
	}
}

void dvoichP(int p16) {
	digit = 0;
	i = 6;
	n = 0;
	while (p16 > 0) {
		digit = p16 % 2;
		masp[i] = digit;
		i--;
		n++;
		p16 = p16 / 2;
	}
}


void main(){
	SetConsoleCP(1251);
	SetConsoleOutputCP(1251);
	printf("Запрограммировать перевод числа с плавающей запятой в его внутреннее представление в компьютере.");
	printf("\n");
	double chislo = -50044.2300045;
	printf("Дано число : ");
	printf("%lf\n", chislo);

	int a;
	a = abs((int)chislo);
	printf("Целая часть числа : ");
	printf("%d\n", a);

	double b;
	b = abs(chislo + a);
	printf("Дробная часть числа : ");
	printf("%lf\n", b);

	dvoichA(a);

	printf("Двоичная запись челой части : ");
	for (int i = n - 1; i >= 0;i--) {
		printf("%d", masa[i]);
	}

	i = 0;
	n = 0;
	dvoichB(b);

	printf("\n");
	printf("Двоичная запись дробной части : ");
	for (int i = 0; i < n;i++) {
		printf("%d", masb[i]);
	}

	printf("\n");

	p16 = dlinna;
	printf("Порядок смещения : ");
	printf("%d", p16);

	dvoichP(p16);

	printf("\n");
	printf("Двоичная запись порядка смещения : ");
	for (int i = 0; i <= 6; i++) {
		printf("%d", masp[i]);
	}

	printf("\n");

	masout[0] = 1;
	for (int i = 0; i <= 7; i++) {
		masout[i + 1] = masp[i];
	}
	n = 15;
	for (int i = 8; i < 24; i++) {
		masout[i] = masa[n];
		n--;
	}
	n = 0;
	for (int i = 24; i < 32; i++) {
		masout[i] = masb[n];
		n++;
	}
	printf("Это и есть искомый результат : ");
	printf("\nВ двоичной записи : ");
	for (int i = 0; i < 32; i++) {
		printf("%d", masout[i]);
	}
	printf("\nВ шестнадцатеричной : ");
	string s;
	int length;
	int rank = 4;
	for (int i = 0; i < 32; i++) {
		s += char(masout[i] + '0');
		length = s.size();
		if (length == rank) {
			if (s == "0000") std::cout << "0";
			else if (s == "0001") std::cout << "1";
			else if (s == "0010") std::cout << "2";
			else if (s == "0011") std::cout << "3";
			else if (s == "0100") std::cout << "4";
			else if (s == "0101") std::cout << "5";
			else if (s == "0110") std::cout << "6";
			else if (s == "0111") std::cout << "7";
			else if (s == "1000") std::cout << "8";
			else if (s == "1001") std::cout << "9";
			else if (s == "1010") std::cout << "a";
			else if (s == "1011") std::cout << "b";
			else if (s == "1100") std::cout << "c";
			else if (s == "1101") std::cout << "d";
			else if (s == "1110") std::cout << "e";
			else if (s == "1111") std::cout << "f";
			s = "";
		}
	}
	printf("\n\ncomment : ");
	printf("\nПервая 1 в двоичной записи означает что число отрицательное");
	_getch();
}