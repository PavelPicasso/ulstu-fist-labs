#include <iostream>
#include <fstream>
#include <string>
#include <vector>

using namespace std;

int main() {
	setlocale(LC_ALL, "Russian");
	string S = "S0 =>";

	ifstream f;
	ofstream g;
	string programm = "";
	f.open("test.pas");
	g.open("newTest.pas");

	S += "S1 => ";

	while (!f.eof()) {
		programm += f.get();
	}
	programm.resize(programm.length() - 1);
	vector<string> arr;
	string delim("\n");
	size_t prev = 0;
	size_t next;
	size_t delta = delim.length();

	S += "S2 => ";

	while ((next = programm.find(delim, prev)) != string::npos) {
		//�������-start
		string tmp = programm.substr(prev, next - prev);
		//�������-end
		arr.push_back(programm.substr(prev, next - prev));
		prev = next + delta;
	}

	//�������-start
	string tmp = programm.substr(prev);
	//�������-end
	arr.push_back(programm.substr(prev));

	S += "S3 => ";

	string index = arr[3].substr(7, 1); //�������� ������ i 
	int id = arr[7].find("to");
	string index1 = arr[7].substr(13, id - 14); //��������  ���������� i 
	string index2 = arr[3].substr(4, 1); //��������  ������ n 
	int id1 = arr[6].find(";");
	string index3 = arr[6].substr(9, id1 - 9); //��������  ���������� n 
	string index4 = arr[6].substr(4, 1); //�������� ����������� n 
	string index5 = arr[7].substr(18, 1); //�������� n � �����
	string index6 = arr[7].substr(8, 1); //�������� i � �����

	S += "S4 => ";

	g << arr[0] + "\n" << arr[1] + "\n" << arr[2] + "\n";
	g << "\t" + index2 + ", " << index + ": integer;";
	g << arr[4] + "\n" << arr[5] + "\n" << "\t" + index4 + " := " << index3 + ";\n";
	g << "\t" + index + " := " + index1 + ";\n";
	g << "\trepeat\n";
	g << arr[8] + "\n";

	S += "S5 => ";

	if (stol(index3) > stol(index1)) {
		g << "\t\t" + index6 + ":= " << index6 + " + 1;\n";
		g << "\tuntil " + index6 + " > " + index2 + ";\n";
	}
	else {
		g << "\t\t" + index6 + ":= " << index6 + " - 1;\n";
		g << "\tuntil " + index6 + " < " + index2 + ";\n";
	}
	g << arr[9];

	f.close();
	g.close();
	S += "S0";

	cout << "���������:\n" + S;
	return 0;
}