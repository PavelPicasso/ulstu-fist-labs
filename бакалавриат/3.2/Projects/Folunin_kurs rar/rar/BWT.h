#pragma once
#include <iostream>
#include <string>
#include <cstring>
#include <vector>
#include <fstream>
#include <algorithm>
using namespace std;

typedef unsigned char byte;

class BWT {
	int Length;
	vector<int> Indexes;
	vector<byte> FileContent;
	ifstream f;
	ofstream g;

	void rar();

	int SortMatrix2();

	void write(int source[]);

public:

	BWT();

	void encode(string file);

	void decode(string extension);
};
