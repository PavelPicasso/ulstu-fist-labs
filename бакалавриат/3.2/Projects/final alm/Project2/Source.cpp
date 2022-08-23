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
	cout << "������� x1 x2 x3 x4 ����� ������" << endl;
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
			cout << "������" << endl;
			cout << "��� ������" << endl;
			step = 2;
			break;
		case 2:
			cout << "��� ������" << endl;
			if (F[0] == 0) {
				step = 2;
				cout << "����� ";
				get_input();
			}
			else {
				step = 3;
			}
			break;
		case 3:
			cout << "��� ������" << endl;
			if (F[1] == 0) {
				step = 2;
				cout << "���������� ����! ����� ";
				get_input();
			}
			else {
				step = 4;
			}
			break;
		case 4:
			cout << "��� ���������" << endl;
			state[0] = 0;
			state[1] = 0;
			state[2] = 1;
			cout << "y1 = " << ((!state[0] & !state[1] & state[2]) | (state[0] & !state[1] & !state[2])) << endl;
			cout << "y2 = " << ((!state[0] & state[2]) | (!state[0] & state[1] & !state[2])) << endl;
			cout << "y3 = " << ((!state[0] & !state[1] & state[2]) | (state[0]) & !state[1] & !state[2]) << endl;
			step = 5;
			break;
		case 5:
			cout << "��� �����" << endl;
			if (F[2] == 1) {
				step = 9;
			}
			else {
				step = 6;
			}
			break;
		case 6:
			cout << "��� ������" << endl;
			state[0] = 1;
			state[1] = 0;
			state[2] = 0;
			cout << "y1 = " << ((!state[0] & !state[1] & state[2]) | (state[0] & !state[1] & !state[2])) << endl;
			cout << "y3 = " << ((!state[0] & !state[1] & state[2]) | (state[0]) & !state[1] & !state[2]) << endl;
			step = 7;
			break;
		case 7:
			cout << "��� �������" << endl;
			state[0] = 1;
			state[1] = 0;
			state[2] = 1;
			cout << "y5 = " << ((!state[0] & state[1] & state[2]) | (state[0] & !state[1] & state[2])) << endl;
			cout << "y6 = " << ((state[0] & !state[1] & state[2])) << endl;
			step = 8;
			break;
		case 8:
			cout << "��� �������" << endl;
			step = 13;
			break;
		case 9:
			cout << "��� �������" << endl;
			state[0] = 0;
			state[1] = 1;
			state[2] = 0;
			cout << "y2 = " << ((!state[0] & state[2]) | (!state[0] & state[1] & !state[2])) << endl;
			cout << "y4 = " << ((!state[0] & state[1] & !state[2])) << endl;
			step = 10;
			break;
		case 10:
			cout << "��� �������" << endl;
			if (F[1] == 0) {
				step = 7;
			}
			else {
				step = 11;
				cycle++;
			}
			break;
		case 11:
			cout << "��� �����������" << endl;
			state[0] = 0;
			state[1] = 1;
			state[2] = 1;
			cout << "y2 = " << ((!state[0] & state[2]) | (!state[0] & state[1] & !state[2])) << endl;
			cout << "y5 = " << ((!state[0] & state[1] & state[2]) | (state[0] & !state[1] & state[2])) << endl;
			step = 12;
			break;
		case 12:
			cout << "��� �����������" << endl;
			step = 9;
			break;
		case 13:
			cout << "��� �����������" << endl;
			cout << "�����" << endl;
			step = 14;
			break;

		default:
			break;
		}
		if (cycle > 1) {
			cout << "!!!!����������� ����!!!!" << endl;
			break;
		}
	}
	return 0;
}
