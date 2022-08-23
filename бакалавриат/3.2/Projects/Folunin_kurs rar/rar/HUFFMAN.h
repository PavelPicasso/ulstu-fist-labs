#pragma once
#include <iostream>
#include <vector>
#include <map>
#include <list>
#include <fstream>

using namespace std;

class HUFFMAN {
	ifstream f, F;
	ofstream g;
	vector<bool> code;
	map<char, vector<bool> > table;
	map<char, int> m;
	int numb; char buf;

	struct Node {
	public:
		int a;
		char c;
		Node *left, *right;

		Node();

		Node(Node *L, Node *R);
	};
	list<Node*> t;
	Node *root;

	struct MyCompare;

	void buildTable(Node *origin);

	void printTree(Node* origin, unsigned k);

public:
	HUFFMAN();

	void numberCharacters();

	void recNode();

	void creationTree();

	void Table() { buildTable(root); }

	void Tree() { printTree(root, 0); }

	void printBuildTable();

	void outputCode(); 

	void rewriteCode();
};


