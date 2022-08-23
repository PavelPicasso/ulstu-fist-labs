#include <list>
#include <iostream>
#include <iterator>
#include <fstream>
#include <sstream>
#include <vector>
#include "MTF.h"
typedef unsigned char byte;
using namespace std;

byte symbolTable[256];
MoveToFront::MoveToFront() {}
//выполняем сдвиг в алфавите
void MoveToFront::moveToFront(int k) {
	byte t = symbolTable[k];
	for (int i = k - 1; i >= 0; i--)
		symbolTable[i + 1] = symbolTable[i];
	symbolTable[0] = t;
}

//заполняем алфавит
void MoveToFront::fillSymbolTable() {
	for (int i = 0; i < 256; i++)
		symbolTable[i] = i;
}

void MoveToFront::encode() {
	f.open("C:/Users/Pavel/Desktop/rar/encodeBWT.bzip2", std::ios::out | std::ios::binary);
	g.open("C:/Users/Pavel/Desktop/rar/encodeMTF.bzip2", std::ios::out | std::ios::binary);
	fillSymbolTable();
	while (1) {
		byte c = f.get();
		if (f.eof()) {
			f.close();
			g.close();
			return;
		}
		for (int i = 0; i < 256; i++) {
			if (c == symbolTable[i]) {
				g << (byte)i;
				moveToFront(i);
				break;
			}
		}
	}
}

void MoveToFront::decode() {
	f.open("C:/Users/Pavel/Desktop/rar/decodeHUFFMAN.bzip2", std::ios::out | std::ios::binary);
	g.open("C:/Users/Pavel/Desktop/rar/decodeMTF.bzip2", std::ios::out | std::ios::binary);
	fillSymbolTable();
	while (1) {
		byte c = f.get();
		if (f.eof()) {
			f.close();
			g.close();
			return;
		}
		g << symbolTable[c];
		moveToFront(c);
	}
}