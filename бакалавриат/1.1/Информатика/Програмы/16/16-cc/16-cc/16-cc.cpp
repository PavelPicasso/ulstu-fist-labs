#define _CRT_SECURE_NO_WARNINGS 
#include <stdio.h> 
#include <conio.h> 
#include <windows.h>
#include <iostream> 
#include <iomanip>
#include <string>
using namespace std;

#define maxlength 100

void main() {
	setlocale(LC_ALL,"Russian");
	int masn1[maxlength] = {};
	int masn2[maxlength] = {};
	int masn[maxlength] = {};
	int n1, n2, digit;
	int l = 0;

	cout << "‚ведите два естнадцатеричных числа : ";
	cin >> hex >> n1;
	cin >> hex >> n2;
	cout << hex << n1 << " + " << hex << n2 << " = ";

	int i = 0;
	while (n1 > 0) {
		digit = n1 % 16;
		masn1[i] = digit;
		i++;
		n1 = n1 / 16;
	}
	int n = i;

	int j = 0;
	while (n2 > 0) {
		digit = n2 % 16;
		masn2[j] = digit;
		j++;
		n2 = n2 / 16;
	}

	int m = j;
		i = 0;

		while ((n > i) || (m > i)) {
			masn[i] += (masn1[i] + masn2[i]) % 16;
			masn[i + 1] += (masn1[i] + masn2[i]) / 16;
			i++;
		}
		l = maxlength;
		for (int i = maxlength - 1; i >= 0; i--) {
			if (masn[i] == 0) { 
				l--;
			}
			else {
				break;
			}
		}
		for (int i = l - 1; i >= 0; i--) {

			switch (masn[i]) 
			{
				case 10:
					cout << 'a';
					break;
				case 11:
					cout << 'b';
					break;
				case 12:
					cout << 'c';
					break;
				case 13:
					cout << 'd';
					break;
				case 14:
					cout << 'e';
					break;
				case 15:
					cout << 'f';
					break;
				default:
					cout << masn[i];
				}
		}
	_getch();
}

