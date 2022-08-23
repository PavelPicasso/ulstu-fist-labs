#define CATCH_CONFIG_RUNNER
#include "catch.hpp"
#include "MTF.h"
#include "BWT.h"
#include "HUFFMAN.h"

ifstream f, g;
string str1, str2;

TEST_CASE("HUFFMAN") {
	f.open("C:/Users/forty/Desktop/rar/encodeMTF.bzip2", ios::out | ios::binary);
	g.open("C:/Users/forty/Desktop/rar/decodeHUFFMAN.bzip2", ios::out | ios::binary);
	while (1) {
		char c1 = f.get();
		char c2 = g.get();
		if (f.eof())
			break;
		str1 += c1;
		str2 += c2;
	}
	f.close();
	g.close();

	REQUIRE(str1 == str2);
}

TEST_CASE("MTF") {
	f.open("C:/Users/forty/Desktop/rar/encodeBWT.bzip2", ios::out | ios::binary);
	g.open("C:/Users/forty/Desktop/rar/decodeMTF.bzip2", ios::out | ios::binary);
	while (1) {
		char c1 = f.get();
		char c2 = g.get();
		if (f.eof())
			break;
		str1 += c1;
		str2 += c2;
	}
	f.close();
	g.close();

	REQUIRE(str1 == str2);
}

TEST_CASE("BWT") {
	f.open("C:/Users/forty/Desktop/rar/omg.txt", ios::out | ios::binary);
	g.open("C:/Users/forty/Desktop/rar/decodeBWT.txt", ios::out | ios::binary);
	while (1) {
		char c1 = f.get();
		char c2 = g.get();
		if (f.eof())
			break;
		str1 += c1;
		str2 += c2;
	}
	f.close();
	g.close();

	REQUIRE(str1 == str2);
}

int main(int argc, char *argv[]) {
	setlocale(LC_ALL, "Russian");
	string filename;
	cout << "������� ��� � ���������� ����� �������� � ����� rar �� ������ �����:\n";
	cin >> filename;

	string extension = filename.substr(filename.find_last_of('.') + 1, 4);

	/*//////////////////////////////////////////////////////////////////////////////////////////
										 BurrowsWheeler
	*///////////////////////////////////////////////////////////////////////////////////////////

	BWT bwt;

	cout << "�������� ����������� BWT\n";
	bwt.encode(filename);

	/*//////////////////////////////////////////////////////////////////////////////////////////
										  MoveToFront
	*///////////////////////////////////////////////////////////////////////////////////////////

	MoveToFront mtf;

	cout << "�������� ����������� MTW\n";

	mtf.encode();

	

	/*//////////////////////////////////////////////////////////////////////////////////////////
											HUFFMAN
	*///////////////////////////////////////////////////////////////////////////////////////////

	HUFFMAN huffman;

	cout << "�������� ����������� HUFFMAN\n";

	////// ������� ������� ��������	
	huffman.numberCharacters();

	/////// ���������� ��������� ���� � ������ list
	huffman.recNode();

	//////  ������� ������		
	huffman.creationTree();

	////// ������� ���� '������-���':			
	huffman.Table();

	////// ����� ������ ��������
	//huffman.Tree();

	////// ������� ���� '������-���': ��������
	//huffman.printBuildTable();

	////// ������� ���� � ���� output.txt
	huffman.outputCode();

	////// ���������� �� ����� output.txt � �������������� �������
	//huffman.rewriteCode();



	/*//////////////////////////////////////////////////////////////////////////////////////////
											�����
	*///////////////////////////////////////////////////////////////////////////////////////////

	cout << "�������� �������� HUFFMAN\n";
	huffman.rewriteCode();

	cout << "�������� �������� MTF\n";
	mtf.decode();

	cout << "�������� �������� BWT\n";
	bwt.decode(extension);

	////// ���������� ������������ ������������ ������� ���������
	Catch::Session().run();

	return 0;
}