#include <iostream>
#include <fstream>
#include <string>
#include <bitset>
#include <ctime> 
using namespace std;

ifstream inFile;
ofstream test, Sgraf, Slogic;
bool state[4];
string inputx, error;
int x[23], s[10];
bool y[38];
bool w[4];
bool u[4];
int cycle = 0;

void reader() {

	//inFile.open("test.txt");
	//getline(inFile, inputx);
	Sgraf.open("Sgraf.txt", ios_base::app);
	
	inFile.open("input.txt");
	getline(inFile, inputx);

	if (inputx.size() > 23 || inputx.size() < 23)
		error += "Error:Входные данные должны содержать 23 позиции!\n";

	for (int i = 0; i <= inputx.size() - 1; i++) {
		if (inputx[i] != '0' && inputx[i] != '1') {
			error += "Error:Введенная строка должна содержать 0 или 1!\n";
			break;
		}
	}
	if (!error.empty()) {
		cout << error;
		system("pause");
		exit(0);
	}

	for (int i = 0; i < inputx.size(); i++) {
		x[i] = inputx[i] - '0';
	}

	inFile.close();
}

void graf() {
	state[0] = 0; state[1] = 0; state[2] = 0; state[3] = 0;
	
	while (1) {
		if (state[0] == 0 && state[1] == 0 && state[2] == 0 && state[3] == 0 && !x[0] && x[1] && !x[2] && !x[3] && !x[4]) {
			cout << "S0 -> S1\noutput: y1 y2 y3\n";
			Sgraf << "S0 -> S1\noutput: y1 y2 y3\n";
			state[0] = 0; state[1] = 0; state[2] = 0; state[3] = 1;
		}
		if (state[0] == 0 && state[1] == 0 && state[2] == 0 && state[3] == 0 && !x[0] && !x[1] && !x[3] && !x[4]) {
			cout << "S0 -> S1\noutput:y1 y2 y3\n";
			Sgraf << "S0 -> S1\noutput:y1 y2 y3\n";
			state[0] = 0; state[1] = 0; state[2] = 0; state[3] = 1;
		}
		if (state[0] == 0 && state[1] == 0 && state[2] == 0 && state[3] == 0 && !x[0] && x[1] && !x[2] && x[3] && !x[15]) {
			cout << "S0 -> S2\noutput: -\n";
			Sgraf << "S0 -> S2\noutput: -\n";
			state[0] = 0; state[1] = 0; state[2] = 0; state[3] = 1;
		}
		if (state[0] == 0 && state[1] == 0 && state[2] == 0 && state[3] == 0 && x[0]) {
			cout << "S0 -> S2\noutput: -\n";
			Sgraf << "S0 -> S2\noutput: -\n";
			state[0] = 0; state[1] = 0; state[2] = 1; state[3] = 0;
		}
		if (state[0] == 0 && state[1] == 0 && state[2] == 0 && state[3] == 0 && !x[0] && x[1] && !x[2] && !x[3] && x[4]) {
			cout << "S0 -> S2\noutput: -\n";
			Sgraf << "S0 -> S2\noutput: -\n";
			state[0] = 0; state[1] = 0; state[2] = 1; state[3] = 0;
		}
		if (state[0] == 0 && state[1] == 0 && state[2] == 0 && state[3] == 0 && !x[0] && !x[1] && !x[3] && x[4]) {
			cout << "S0 -> S2\noutput: -\n";
			Sgraf << "S0 -> S2\noutput: -\n";
			state[0] = 0; state[1] = 0; state[2] = 1; state[3] = 0;
		}
		if (state[0] == 0 && state[1] == 0 && state[2] == 0 && state[3] == 0 && !x[0] && x[1] && !x[2] && !x[3] && x[15]) {
			cout << "S0 -> S4\noutput: -\n";
			Sgraf << "S0 -> S4\noutput: -\n";
			state[0] = 0; state[1] = 1; state[2] = 0; state[3] = 0;
		}
		if (state[0] == 0 && state[1] == 0 && state[2] == 0 && state[3] == 0 && !x[0] && x[1] && x[2]) {
			cout << "S0 -> S4\noutput: -\n";
			Sgraf << "S0 -> S4\noutput: -\n";
			state[0] = 0; state[1] = 1; state[2] = 0; state[3] = 0;
		}
		if (state[0] == 0 && state[1] == 0 && state[2] == 0 && state[3] == 1 && !x[5]) {
			if (cycle > 1) {
				cout << "Возник Цикл в состоянии S1";
				Sgraf << "Возник Цикл в состоянии S1";
				break;
			}
			cout << "S1 -> S1\noutput: -\n";
			Sgraf << "S1 -> S1\noutput: -\n";
			state[0] = 0; state[1] = 0; state[2] = 0; state[3] = 1;
			cycle++;
		}
		if (state[0] == 0 && state[1] == 0 && state[2] == 0 && state[3] == 1 && x[5]) {
			cout << "S1 -> S2\noutput: y4\n";
			Sgraf << "S1 -> S2\noutput: y4\n";
			state[0] = 0; state[1] = 0; state[2] = 1; state[3] = 0;
		}
		if (state[0] == 0 && state[1] == 0 && state[2] == 1 && state[3] == 0 && x[6] && x[7] && x[8]) {
			cout << "S2 -> S3\noutput: y5y6y7y8\n";
			Sgraf << "S2 -> S3\noutput: y5y6y7y8\n";
			state[0] = 0; state[1] = 0; state[2] = 1; state[3] = 1;
		}
		if (state[0] == 0 && state[1] == 0 && state[2] == 1 && state[3] == 0 && !x[6] && x[9] && x[16]) {
			cout << "S2 -> S3\noutput: y21y22y23\n";
			Sgraf << "S2 -> S3\noutput: y21y22y23\n";
			state[0] = 0; state[1] = 0; state[2] = 1; state[3] = 1;
		}
		if (state[0] == 0 && state[1] == 0 && state[2] == 1 && state[3] == 0 && !x[6] && x[9] && !x[16] && !x[20]) {
			cout << "S2 -> S5\noutput: y34\n";
			Sgraf << "S2 -> S5\noutput: y34\n";
			state[0] = 0; state[1] = 1; state[2] = 0; state[3] = 1;
		}
		if (state[0] == 0 && state[1] == 0 && state[2] == 1 && state[3] == 0 && x[6] && x[7] && !x[8]) {
			cout << "S2 -> S7\noutput: y9y10y11\n";
			Sgraf << "S2 -> S7\noutput: y9y10y11\n";
			state[0] = 0; state[1] = 1; state[2] = 1; state[3] = 1;
		}
		if (state[0] == 0 && state[1] == 0 && state[2] == 1 && state[3] == 0 && x[6] && !x[7]) {
			cout << "S2 -> S7\noutput: y9y10y11\n";
			Sgraf << "S2 -> S7\noutput: y9y10y11\n";
			state[0] = 0; state[1] = 1; state[2] = 1; state[3] = 1;
		}
		if (state[0] == 0 && state[1] == 0 && state[2] == 1 && state[3] == 0 && !x[6] && !x[9]) {
			cout << "S2 -> S7\noutput: y9y10y11\n";
			Sgraf << "S2 -> S7\noutput: y9y10y11\n";
			state[0] = 0; state[1] = 1; state[2] = 1; state[3] = 1;
		}
		if (state[0] == 0 && state[1] == 0 && state[2] == 1 && state[3] == 0 && !x[6] && x[9] && !x[16] && x[20]) {
			cout << "S2 -> S8\noutput: y29y30y31\n";
			Sgraf << "S2 -> S8\noutput: y29y30y31\n";
			state[0] = 1; state[1] = 0; state[2] = 0; state[3] = 0;
		}
		if (state[0] == 0 && state[1] == 0 && state[2] == 1 && state[3] == 1 && !x[17]) {
			if (cycle > 1) {
				cout << "Возник Цикл в состоянии S3";
				Sgraf << "Возник Цикл в состоянии S3";
				break;
			}
			cout << "S3 -> S3\noutput: -\n";
			Sgraf << "S3 -> S3\noutput: -\n";
			state[0] = 0; state[1] = 0; state[2] = 1; state[3] = 1;
			cycle++;
		}
		if (state[0] == 0 && state[1] == 0 && state[2] == 1 && state[3] == 1 && x[17] && !x[18]) {
			cout << "S3 -> S4\noutput: y24\n";
			Sgraf << "S3 -> S4\noutput: y24\n";
			state[0] = 0; state[1] = 1; state[2] = 0; state[3] = 0;
		}
		if (state[0] == 0 && state[1] == 0 && state[2] == 1 && state[3] == 1 && x[17] && x[18]) {
			cout << "S3 -> S9\noutput: y25y26y27y28\n";
			Sgraf << "S3 -> S9\noutput: y25y26y27y28\n";
			state[0] = 1; state[1] = 0; state[2] = 0; state[3] = 1;
		}
		if (state[0] == 0 && state[1] == 1 && state[2] == 0 && state[3] == 0) {
			cout << "S4 -> S5\noutput: y33\n";
			Sgraf << "S4 -> S5\noutput: y33\n";
			state[0] = 0; state[1] = 1; state[2] = 0; state[3] = 1;
		}
		if (state[0] == 0 && state[1] == 1 && state[2] == 0 && state[3] == 1) {
			cout << "S5 -> S6\noutput: y35y36y37\n";
			Sgraf << "S5 -> S6\noutput: y35y36y37\n";
			state[0] = 0; state[1] = 1; state[2] = 1; state[3] = 0;
		}
		if (state[0] == 0 && state[1] == 1 && state[2] == 1 && state[3] == 0 && !x[22]) {
			if (cycle > 1) {
				cout << "Возник Цикл в состоянии S6";
				Sgraf << "Возник Цикл в состоянии S6";
				break;
			}
			cout << "S6 -> S6\noutput: -\n";
			Sgraf << "S6 -> S6\noutput: -\n";
			state[0] = 0; state[1] = 1; state[2] = 1; state[3] = 0;
			cycle++;
		}
		if (state[0] == 0 && state[1] == 1 && state[2] == 1 && state[3] == 0 && x[22]) {
			cout << "S6 -> S0\noutput: y38\n";
			Sgraf << "S6 -> S0\noutput: y38\n";
			state[0] = 0; state[1] = 0; state[2] = 0; state[3] = 0;
			break;
		}
		if (state[0] == 0 && state[1] == 1 && state[2] == 1 && state[3] == 1 && !x[10]) {
			if (cycle > 1) {
				cout << "Возник Цикл в состоянии S7";
				Sgraf << "Возник Цикл в состоянии S7";
				break;
			}
			cout << "S7 -> S7\noutput: -\n";
			Sgraf << "S7 -> S7\noutput: -\n";
			state[0] = 0; state[1] = 1; state[2] = 1; state[3] = 1;
			cycle++;
		}
		if (state[0] == 0 && state[1] == 1 && state[2] == 1 && state[3] == 1 && x[10] && !x[11]) {
			if (cycle > 1) {
				cout << "Возник Цикл в состоянии S7";
				Sgraf << "Возник Цикл в состоянии S7";
				break;
			}
			cout << "S7 -> S2\noutput: y12y13\n";
			Sgraf << "S7 -> S2\noutput: y12y13\n";
			state[0] = 0; state[1] = 0; state[2] = 1; state[3] = 0;
			cycle++;
		}
		if (state[0] == 0 && state[1] == 1 && state[2] == 1 && state[3] == 1 && x[10] && x[11] && !x[12] && !x[13] && !x[14]) {
			if (cycle > 1) {
				cout << "Возник Цикл в состоянии S7";
				Sgraf << "Возник Цикл в состоянии S7";
				break;
			}
			cout << "S7 -> S2\noutput: y12y13\n";
			Sgraf << "S7 -> S2\noutput: y12y13\n";
			state[0] = 0; state[1] = 0; state[2] = 1; state[3] = 0;
			cycle++;
		}
		if (state[0] == 0 && state[1] == 1 && state[2] == 1 && state[3] == 1 && x[10] && x[11] && !x[12] && !x[13] && x[14]) {
			cout << "S7 -> S4\noutput: y20\n";
			Sgraf << "S7 -> S4\noutput: y20\n";
			state[0] = 0; state[1] = 1; state[2] = 0; state[3] = 0;
		}
		if (state[0] == 0 && state[1] == 1 && state[2] == 1 && state[3] == 1 && x[10] && x[11] && !x[12] && x[13]) {
			cout << "S7 -> S8\noutput: y17y18y19\n";
			Sgraf << "S7 -> S8\noutput: y17y18y19\n";
			state[0] = 1; state[1] = 0; state[2] = 0; state[3] = 0;
		}
		if (state[0] == 0 && state[1] == 1 && state[2] == 1 && state[3] == 1 && x[10] && x[11] && x[12]) {
			cout << "S7 -> S3\noutput: y14y15y16\n";
			Sgraf << "S7 -> S3\noutput: y14y15y16\n";
			state[0] = 0; state[1] = 0; state[2] = 1; state[3] = 1;
		}
		if (state[0] == 1 && state[1] == 0 && state[2] == 0 && state[3] == 0 && !x[21]) {
			if (cycle > 1) {
				cout << "Возник Цикл в состоянии S8";
				Sgraf << "Возник Цикл в состоянии S8";
				break;
			}
			cout << "S8 -> S8\noutput: -\n";
			Sgraf << "S8 -> S8\noutput: -\n";
			state[0] = 1; state[1] = 0; state[2] = 0; state[3] = 0;
			cycle++;
		}
		if (state[0] == 1 && state[1] == 0 && state[2] == 0 && state[3] == 0 && x[21]) {
			cout << "S8 -> S4\noutput: y32\n";
			Sgraf << "S8 -> S4\noutput: y32\n";
			state[0] = 0; state[1] = 1; state[2] = 0; state[3] = 0;
		}
		if (state[0] == 1 && state[1] == 0 && state[2] == 0 && state[3] == 1 && !x[19]) {
			if (cycle > 1) {
				cout << "Возник Цикл в состоянии S9";
				Sgraf << "Возник Цикл в состоянии S9";
				break;
			}
			cout << "S9 -> S9\noutput: -\n";
			Sgraf << "S9 -> S9\noutput: -\n";
			state[0] = 1; state[1] = 0; state[2] = 0; state[3] = 1;
			cycle++;
		}
		if (state[0] == 1 && state[1] == 0 && state[2] == 0 && state[3] == 1 && x[19]) {
			cout << "S9 -> S4\noutput: -\n";
			Sgraf << "S9 -> S4\noutput: -\n";
			state[0] = 0; state[1] = 1; state[2] = 0; state[3] = 0;
		}
	}
	Sgraf << "|------------------|\n";
	Sgraf.close();
}

void logic() {
	string S;

	while (1) {
		// выходные
		y[0] = y[1] = y[2] = (!state[0] & !state[1] & !state[2] & !state[3] & !x[0] & x[1] & !x[2] & !x[3] & !x[4]) | (!state[0] & !state[1] & !state[2] & !state[3] & !x[0] & !x[1] & !x[3] & !x[4]);

		y[3] = !state[0] & !state[1] & !state[2] & state[3] & x[5];

		y[4] = y[5] = y[6] = y[7] = !state[0] & !state[1] & state[2] & !state[3] & x[6] & x[7] & x[8];

		y[8] = y[9] = y[10] = (!state[0] & !state[1] & state[2] & !state[3] & x[6] & x[7] & !x[8]) | (!state[0] & !state[1] & state[2] & !state[3] & x[6] & !x[7]) | (0 & 0 & 1 & 0 & !x[6] & !x[9]);

		y[11] = y[12] = (!state[0] & state[1] & state[2] & state[3] & x[10] & !x[11]) | (!state[0] & state[1] & state[2] & state[3] & x[10] & x[11] & !x[12] & !x[13] & !x[14]);

		y[13] = y[14] = y[15] = !state[0] & state[1] & state[2] & state[3] & x[10] & x[11] & x[12];

		y[16] = y[17] = y[18] = !state[0] & state[1] & state[2] & state[3] & x[10] & x[11] & !x[12] & x[13];

		y[19] = !state[0] & state[1] & state[2] & !state[3] & x[10] & x[11] & !x[12] & !x[13] & x[14];

		y[20] = y[21] = y[22] = !state[0] & !state[1] & state[2] & !state[3] & !x[6] & x[9] & x[16];

		y[23] = !state[0] & !state[1] & state[2] & state[3] & x[17] & !x[18];

		y[24] = y[25] = y[26] = y[27] = !state[0] & !state[1] & state[2] & state[3] & x[17] & x[18];

		y[28] = y[29] = y[30] = !state[0] & !state[1] & state[2] & !state[3] & !x[6] & x[9] & !x[16] & !x[20];

		y[31] = state[0] & !state[1] & !state[2] & !state[3] & x[21];

		y[32] = !state[0] & state[1] & !state[2] & !state[3];

		y[33] = !state[0] & !state[1] & state[2] & !state[3] & !x[6] & x[9] & !x[16] & !x[20];

		y[34] = y[35] = y[36] = !state[0] & state[1] & !state[2] & state[3];

		y[37] = !state[0] & state[1] & state[2] & !state[3] & x[22];


		//функции перехода
		w[0] = (!state[0] & !state[1] & state[2] & !state[3] & !x[6] & x[9] & !x[16] & x[20]) | (!state[0] & !state[1] & state[2] & state[3] & x[17] & x[18]) | (!state[0] & state[1] & state[2] & state[3] & x[10] & x[11] & !x[12] & x[13]);

		w[1] = (!state[0] & !state[1] & !state[2] & !state[3] & !x[0] & x[1] & !x[2] & x[3] & x[4]) | (!state[0] & !state[1] & !state[2] & !state[3] & !x[0] & x[1] & x[2]) | (!state[0] & !state[1] & state[2] & !state[3] & !x[6] & x[9] & !x[16] & !x[20]) | (!state[0] & !state[1] & state[2] & !state[3] & x[6] & x[7] & !x[8]) | (!state[0] & !state[1] & state[2] & !state[3] & x[6] & !x[7]) | (!state[0] & !state[1] & state[2] & !state[3] & !x[6] & !x[9]) | (!state[0] & !state[1] & state[2] & state[3] & x[17] & !x[18]) | (state[0] & !state[1] & !state[2] & !state[3] & x[21]) | (state[0] & !state[1] & !state[2] & state[3] & x[19]);

		w[2] = (!state[0] & !state[1] & !state[2] & !state[3] & x[0]) | (!state[0] & !state[1] & !state[2] & !state[3] & !x[0] & x[1] & !x[2] & !x[3] & x[4]) | (!state[0] & !state[1] & !state[2] & state[3] & x[5]) | (!state[0] & !state[1] & !state[3] & x[4]) | (!state[0] & state[1] & !state[2] & state[3]);

		w[3] = (!state[0] & !state[1] & !state[2] & !state[3] & !x[0] & x[1] & !x[2] & !x[3] & !x[4]) | (!state[0] & !state[1] & !state[2] & !state[2] & !state[3] & !x[0] & !x[1] & !x[3] & !x[4]) | (!state[0] & !state[1] & !state[2] & !state[3] & !x[0] & x[1] & !x[2] & x[3] & !x[15]) | (!state[0] & !state[1] & state[2] & !state[3] & x[6] & x[7] & x[8]) | (!state[0] & !state[1] & state[2] & !state[3] & !x[6] & x[9] & x[16]) | (!state[0] & !state[1] & state[2] & !state[3] & !x[6] & x[9] & !x[16] & !x[20]) | (!state[0] & !state[1] & state[2] & !state[3] & x[6] & x[7] & !x[8]) | (!state[0] & !state[1] & state[2] & !state[3] & x[6] & !x[7]) | (!state[0] & !state[1] & state[2] & !state[3] & !x[6] & !x[9]) | (!state[0] & state[1] & !state[2] & !state[3]);

		u[0] = (state[0] & !state[1] & !state[2] & !state[3] & x[21]) | (state[0] & !state[1] & !state[2] & state[3] & x[19]);

		u[1] = (!state[0] & state[1] & state[2] & !state[3] & x[22]) | (!state[0] & state[1] & state[2] & state[3] & x[10] & !x[11]) | (!state[0] & state[1] & state[2] & state[3] & x[10] & x[11] & !x[12] & !x[13] & !x[14]) | (!state[0] & state[1] & state[2] & state[3] & x[10] & x[11] & !x[12] & x[13]) | (!state[0] & state[1] & state[2] & state[3] & x[10] & x[11] & x[12]);

		u[2] = (!state[0] & !state[1] & state[2] & !state[3] & !x[6] & x[9] & !x[16] & !x[20]) | (!state[0] & !state[1] & state[2] & !state[3] & !x[6] & x[9] & !x[16] & x[20]) | (!state[0] & !state[1] & state[2] & state[3] & x[17] & !x[18]) | (!state[0] & !state[1] & state[2] & state[3] & x[17] & x[18]) | (!state[0] & state[1] & state[2] & !state[3] & x[22]) | (!state[0] & state[1] & state[2] & state[3] & x[10] & x[11] & !x[12] & !x[13] & x[14]) | (!state[0] & state[1] & state[2] & state[3] & x[10] & x[11] & !x[12] & x[13]);

		u[3] = (!state[0] & !state[1] & !state[2] & state[3] & x[5]) | (!state[0] & !state[1] & state[2] & state[3] & x[17] & !x[18]) | (!state[0] & state[1] & state[2] & state[3] & x[10] & !x[11]) | (!state[0] & state[1] & state[2] & state[3] & x[10] & x[11] & !x[12] & !x[13] & !x[14]) | (!state[0] & state[1] & state[2] & !state[3] & x[10] & x[11] & !x[12] & !x[13] & x[14]) | (!state[0] & state[1] & state[2] & state[3] & x[10] & x[11] & !x[12] & x[13]) | (state[0] & !state[1] & !state[2] & state[3] & x[19]) | (!state[0] & state[1] & !state[2] & state[3]);

		for (int i = 0; i < 4; i++) {
			cout << state[i];
			if (state[i] == 1)
				S += '1';
			else
				S += '0';
		}

		for (int i = 0; i < 4; i++) {
			if (w[i])
				state[i] = 1;
			if (u[i])
				state[i] = 0;
		}

		cout << " -> ";

		for (int i = 0; i < 4; i++) {
			cout << state[i];
		}
		cout << " ";

		for (int i = 0; i < 38; i++) {
			if (y[i])
				cout << "y" << i + 1 << " ";
		}
		cout << ": ";

		for (int i = 0; i < 38; i++) {
			cout << y[i];
		}

		if (state[0] == 0 && state[1] == 0 && state[2] == 0 && state[3] == 0)
			break;

		if (s[bitset<4>(S).to_ulong()] > 7) {
			cout << "\nЗацикливание...\n";
			break;
		} else
			s[bitset<4>(S).to_ulong()]++;
		cout << "\n";
	}
}


void testing() {
	int i = 0;
	string buf;
	while (i < 8388608) {
		Sgraf.open("Sgraf.txt", ios_base::app);
		test.open("test.txt");
		buf = bitset<23>(i).to_string();
		cout << "\n\n|" << buf << "|\n\n";
		test << bitset<23>(i).to_string();
		test.close();
		reader();
		Sgraf << "Набор x: " << buf;
		Sgraf << "\n";
		graf();
		i++;
	}
}

int main() {
	double start, end;
	srand(time(0));
	setlocale(LC_ALL, "Russian");
	int choice;

	//testing();

	reader();
	cout << "Выбор алгоритма(Выбирите числовой пункт):\n 1 Логическое выражение \n 2 Граф-схема\nВаш выбор: ";
	cin >> choice;
	switch (choice) {
	case 1:
		start = clock();
		logic();
		end = clock();
		break;
	case 2:
		start = clock();
		graf();
		end = clock();
		break;
	default:
		cout << "Выбран неправильный пункт!\n";
		system("pause");
		exit(0);
		break;
	}

	cout << "\nВремя выполнения: " << end - start << " мс" << endl;

	return 0;
	}