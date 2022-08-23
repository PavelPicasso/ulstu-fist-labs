#include <iostream> 
#include <algorithm> 
#include <vector>
#include <cstdio>
using namespace std;
vector<bool> F(3);
vector<bool> state(4);
int step = 1;
void get_input() {
	int x1, x2, x3, x4;
	cout << "Введите x1 x2 x3 x4 через пробел" << endl;
	cin >> x1 >> x2 >> x3 >> x4;
	F[0] = x2 | !x3 | x1;
	F[1] = ((x1 & !x3 & !x4) | (x2 & !x3) | (x2 & x4) | (x1 & x3) | (!x2 & x3));
	F[2] = x2 | !x1 | !x3;
}
int main() {
	setlocale(LC_ALL, "Russian");
	get_input();
	cout << "F1 = " << F[0] << endl << "F2 = " << F[1] << endl << "F3 = " << F[2] << endl;
	int cycle = 0;
	while (1) {
		switch (step)
		{
		case 1:
			cout << "Начало" << endl;
			cout << "Шаг первый" << endl;
			step = 2;
			break;
		case 2:
			cout << "Шаг второй" << endl;
			if (F[0] == 0) {
				step = 2;
				cout << "Снова ";
				get_input();
			}
			else {
				step = 3;
			}
			break;
		case 3:
			cout << "Шаг третий" << endl;
			if (F[1] == 0) {
				step = 2;
				cout << "Встретился цикл! Снова ";
				get_input();
			}
			else {
				step = 4;
			}
			break;
		case 4:
			cout << "Шаг четвертый" << endl;
			state[0] = 0;
			state[1] = 0;
			state[2] = 1;
			cout << "y1 = " << ((!state[0] & !state[1] & state[2]) | (state[0] & !state[1] & !state[2])) << endl;
			cout << "y2 = " << ((!state[0] & state[2]) | (!state[0] & state[1] & !state[2])) << endl;
			cout << "y3 = " << ((!state[0] & !state[1] & state[2]) | (state[0]) & !state[1] & !state[2]) << endl;
			step = 5;
			break;
		case 5:
			cout << "Шаг пятый" << endl;
			if (F[2] == 1) {
				step = 9;
			}
			else {
				step = 6;
			}
			break;
		case 6:
			cout << "Шаг шестой" << endl;
			state[0] = 1;
			state[1] = 0;
			state[2] = 0;
			cout << "y1 = " << ((!state[0] & !state[1] & state[2]) | (state[0] & !state[1] & !state[2])) << endl;
			cout << "y3 = " << ((!state[0] & !state[1] & state[2]) | (state[0]) & !state[1] & !state[2]) << endl;
			step = 7;
			break;
		case 7:
			cout << "Шаг седьмой" << endl;
			state[0] = 1;
			state[1] = 0;
			state[2] = 1;
			cout << "y5 = " << ((!state[0] & state[1] & state[2]) | (state[0] & !state[1] & state[2])) << endl;
			cout << "y6 = " << ((state[0] & !state[1] & state[2])) << endl;
			step = 8;
			break;
		case 8:
			cout << "Шаг восьмой" << endl;
			step = 13;
			break;
		case 9:
			cout << "Шаг девятый" << endl;
			state[0] = 0;
			state[1] = 1;
			state[2] = 0;
			cout << "y2 = " << ((!state[0] & state[2]) | (!state[0] & state[1] & !state[2])) << endl;
			cout << "y4 = " << ((!state[0] & state[1] & !state[2])) << endl;
			step = 10;
			break;
		case 10:
			cout << "Шаг десятый" << endl;
			if (F[1] == 0) {
				step = 7;
			}
			else {
				step = 11;
				cycle++;
			}
			break;
		case 11:
			cout << "Шаг одинадцатый" << endl;
			state[0] = 0;
			state[1] = 1;
			state[2] = 1;
			cout << "y2 = " << ((!state[0] & state[2]) | (!state[0] & state[1] & !state[2])) << endl;
			cout << "y5 = " << ((!state[0] & state[1] & state[2]) | (state[0] & !state[1] & state[2])) << endl;
			step = 12;
			break;
		case 12:
			cout << "Шаг двенадцатый" << endl;
			step = 9;
			break;
		case 13:
			cout << "Шаг тринадцатый" << endl;
			cout << "Конец" << endl;
			step = 14;
			break;

		default:
			break;
		}
		if (cycle > 1) {
			cout << "!!!!Бесконечный цикл!!!!" << endl;
			break;
		}
	}
	return 0;
}
