#include "stdafx.h"
#include <iostream>
#include <conio.h> 
using namespace std;

int main()
{
	int mastrix[10][1000] = {};
	int n, m;
	cin >> n;
	m = pow(2, n);
	for (int i = 0; i < m; i++) {
		mastrix[n - 1][i] = i;
	}

	for (int i = n - 1; i > 0; --i)
		for (int j = 0; j < m; j++) {
			mastrix[i - 1][j] = mastrix[i][j] / 2;
			mastrix[i][j] %= 2;
		}

	for (int i = 0; i < n; i++) {
		for (int j = 0; j < m; j++)
		{
			cout << mastrix[i][j] << " ";
		}
		cout << "\n";
	}
	_getch();
}