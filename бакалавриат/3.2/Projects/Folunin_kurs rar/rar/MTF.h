#pragma once
#include <list>
#include <iostream>
#include <iterator>
#include <fstream>
#include <sstream>
#include <vector>
typedef unsigned char byte;
using namespace std;

class MoveToFront {
	ifstream f;
	ofstream g;
	byte symbolTable[256];

	//выполняем сдвиг в алфавите
	void moveToFront(int k);

	//заполняем алфавит
	void fillSymbolTable();

public:
	MoveToFront();

	void encode();

	void decode();
};
