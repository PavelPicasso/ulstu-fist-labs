#include <stdio.h>
#include <string>
#include <cmath>
#include <algorithm>
#include <stdlib.h>
#include <iostream>
#include <windows.h>

using namespace std;

const string digits = "0123456789ABCDEFGHIJKLMNOPQRASUVWXYZ";

void validation(int base) {
	cout << "Error: исходное число является некорректной записью для системы с основанием " << base << ".\nВ ";
	cout << base << "-ой системе допустимы только следующие символы: ";
	for (int i = 0; i < base; i++) {
		cout << digits[i] << " ";
	}
}

string DecToAny(int number, int to_final) {
	string result;

	cout << endl << "Переводим целую часть " << number << " в " << to_final << "-ую систему последовательным делением на " << to_final << ":" << endl;

	do {
		cout << number << " / " << to_final << " = " << number / to_final << ", остаток:" << digits[number % to_final] << endl;
		result.push_back(digits[number % to_final]);
		number /= to_final;
	} while (number > 0);
	reverse(result.begin(), result.end());

	return result;
}

/*
Параметр: something
#1 принимает (любое значение) для перевода целого числа
#2 при вещественных числах принимает значение (-1), для отрицательной степени в переводе дробной части числа
*/
double ToDec(string number, int base, int something) {

	int mid;
	double result = 0;

	for (int i = 0; i < number.size(); i++) {

		int left = 0;
		int right = digits.size();

		while (true) {
			mid = left + (right - left) / 2;

			if (digits[mid] == number[i])
				break;

			if (digits[mid] > number[i])
				right = mid;
			else
				left = mid + 1;
		}
		if (something == -1) {
			cout << mid << " * " << base << " ^ -" << i + 1 << " + ";
			result += mid * pow(base, -1 * (i + 1));
		} else {
			cout << mid << " * " << base << " ^ " << i << " + ";
			result += mid * pow(base, i);
		}
		
	}

	cout << "\b\b= " << result << endl;
	return result;
}

string TransferDecimalToAny(string number, int base, int to_final, int accuracy) {
	cout << endl << "Переводим " << number << " в десятичную систему:" << endl;
	string result;

	int point = number.find('.');

	string str1 = number.substr(0, point);
	cout << str1 << " = ";
	reverse(str1.begin(), str1.end());

	int befor_int = ToDec(str1, base, 0);

	string str2 = number.substr(point + 1, number.size() - point);
	cout << str2 << " = ";
	double befor_fractional = ToDec(str2, base , -1);

	result = DecToAny(befor_int, to_final);
	result = result + ".";

	cout << "\nПереводим дробную часть " << befor_fractional << " в 2-ую систему:" << endl;

	int it = 0;
	do {
		cout << befor_fractional << " * " << to_final << " = " << befor_fractional * to_final << endl;
		befor_fractional *= to_final;
		int num = (int)befor_fractional;
		result = result + to_string(num);
		befor_fractional -= (int)befor_fractional;
		it++;
	} while (befor_fractional > 0.00000001 && it <= accuracy);

	return result;
}

void translation() {
	string number;

	cout << "Number: ";
	getline(cin, number);

	for (int i = 0; i < number.size(); i++) {
		if ((int)number[i] < 0) {
			cout << "Ввод произведен на русской раскладке клавиатуры.\nДопустимы только числа и буквы англиского алфавита:" << endl;
			cout << digits;
			return;
		}
	}

	if (number.find("-") != -1) {
		cout << "Error: исходное число является отрицательным";
		return;
	}

	int base;
	cout << "in ";
	cin >> base;

	for (int i = 0; i < number.size(); i++) {
		if (base > 10) {
			if (!(number[i] >= 'A' && number[i] <= base - 10 + 'A' || isdigit(number[i]) || number[i] == '.')) {
				validation(base);
				return;
			}
		}
		else {
			if (!(number[i] - '0' >= 0 && number[i] - '0' < base || number[i] == '.')) {
				validation(base);
				return;
			}
		}
	}

	if ((base < 2 || base > 36)) {
		cout << "Error: основание исходной системы должны быть в диапазоне от 2 до 36.";
		return;
	}

	int to_final;
	cout << "to ";
	cin >> to_final;

	if ((to_final < 2 || to_final > 36)) {
		cout << "Error: основание конечной системы должны быть в диапазоне от 2 до 36.";
		return;
	}


	if (number.find(".") != -1) {
		cout << endl << "Result: " << TransferDecimalToAny(number, base, to_final, 21).c_str();
		return;
	}

	int digit;
	if (base != 10) {
		cout << endl << "Переводим " << number << " в десятичную систему:" << endl;
		cout << number << " = ";

		reverse(number.begin(), number.end());
		digit = ToDec(number, base, 0);
		cout << endl << "Result: " << DecToAny(digit, to_final);
		return;
	}
	else {
		digit = atoi(number.c_str());
		cout << endl << "Result: " << DecToAny(digit, to_final);
		return;
	}
}

int main() {
	//setlocale(LC_ALL, "Russian");
	SetConsoleCP(1251);
	SetConsoleOutputCP(1251);

	translation();
	cout << endl;
	system("pause");
}