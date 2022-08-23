#include <fstream>
#include <iostream>
#include <string>
#include <bitset>
using namespace std;
int main() {
	string buff, str1, str2, str3;
	string S = "S0 => ";
	string Step = "Tr1 = 00000000 Tr2 = 00000000 => ";
	string error = "";
	ifstream fin("import.txt");
	ofstream fout("output.txt");
	getline(fin, buff);
	if (buff.length() > 18) {
		error += "Error:many simbols\n";
	}
	for (int i = 0; i < buff.length() - 1; i++) {
		if (buff[i] != '0' && buff[i] != '1') {
			error += "Error:not bin\n";
			break;
		}
	}
	str1 = buff.substr(2, 8);
	str2 = buff.substr(10, 8);
	S += "S1 =>";
	Step += "Tr1 = ";
	Step += str1;
	Step += " Tr2 = ";
	Step += str2;
	Step += " => ";
	if (str2 == "00000000") {
		error += "Error:dividing by zero\n";
	}
	if ((buff[0] == '0' && buff[1] == '1') || (buff[0] == '1' && buff[1] == '0')) {
	}
	else {
		error += "Error:don't know operation ";
		error += buff[0];
		error += buff[1];
	}
	if (error == "") {
		if (buff[0] == '0' && buff[1] == '1') {
			fout << bitset<16>(strtol(str1.c_str(), NULL, 2) * strtol(str2.c_str(), NULL, 2));
			str3 = bitset<16>(strtol(str1.c_str(), NULL, 2) * strtol(str2.c_str(), NULL, 2)).to_string();
			Step += "Tr1 * Tr2 = ";
		}
		else if (buff[0] == '1' && buff[1] == '0') {
			fout << bitset<16>(strtol(str1.c_str(), NULL, 2) / strtol(str2.c_str(), NULL, 2));
			str3 = bitset<16>(strtol(str1.c_str(), NULL, 2) / strtol(str2.c_str(), NULL, 2)).to_string();
			Step += "Tr1 / Tr2 = ";
		}
	}
	else {
		cout << error;
	}
	S += "S0\n";
	cout << S;
	cout << Step << "Tr3 = " << str3;
}
