#include <iostream>
#include <vector>
#include <map>
#include <list>
#include <fstream>
#include "HUFFMAN.h"

using namespace std;

HUFFMAN::Node::Node() { left = right = NULL; }

HUFFMAN::Node::Node(Node *L, Node *R) {
	left = L;
	right = R;
	a = L->a + R->a;
	c = NULL;
}

struct HUFFMAN::MyCompare {
	bool operator()(const Node* l, const Node* r) const { return l->a < r->a; }
};

void HUFFMAN::buildTable(Node *origin) {
	if (origin->left != NULL) {
		code.push_back(0);
		buildTable(origin->left);
	}

	if (origin->right != NULL) {
		code.push_back(1);
		buildTable(origin->right);
	}

	if (origin->left == NULL && origin->right == NULL)
		table[origin->c] = code;
	if (!code.empty())
		code.pop_back();
}

void HUFFMAN::printTree(Node* origin, unsigned k = 0) {
	if (origin != NULL) {
		printTree(origin->left, k + 3);

		for (unsigned i = 0; i < k; i++) {
			cout << " ";
		}

		if (origin->c) {
			cout << origin->a << " (" << origin->c << ")" << endl;
		}
		else {
			cout << origin->a << endl;
		}
		printTree(origin->right, k + 3);
	}
}

HUFFMAN::HUFFMAN() {
	m = {};
	numb = 0, buf = 0;
}

void HUFFMAN::numberCharacters() {
	f.open("C:/Users/Pavel/Desktop/rar/encodeMTF.bzip2", ios::out | ios::binary);
	while (1)
	{
		char c = f.get();
		if (f.eof())
			break;
		m[c]++;
	}
}

void HUFFMAN::recNode() {
	for (map<char, int>::iterator itr = m.begin(); itr != m.end(); ++itr)
	{
		Node *p = new Node;
		p->c = itr->first;
		p->a = itr->second;
		t.push_back(p);
	}
}

void HUFFMAN::creationTree() {
	while (t.size() != 1) {
		t.sort(MyCompare());

		Node *SonL = t.front();
		t.pop_front();
		Node *SonR = t.front();
		t.pop_front();

		Node *parent = new Node(SonL, SonR);
		t.push_back(parent);
	}
	root = t.front();   //root - указатель на вершину дерева
}

void HUFFMAN::printBuildTable() {
	for (auto it = table.begin(); it != table.end(); it++) {
		cout << it->first << ":";
		for (auto iter = it->second.begin(); iter != it->second.end(); iter++) {
			cout << *iter;
		}
		cout << endl;
	}
}

void HUFFMAN::outputCode() {
	f.clear(); f.seekg(0);// перемещаем указатель снова в начало файла
	g.open("C:/Users/Pavel/Desktop/rar/encodeHUFFMAN.bzip2", ios::out | ios::binary);

	while (1) {
		char c = f.get();
		if (f.eof()) {
			g << buf << (char)numb;
			break;
		}
		vector<bool> x = table[c];
		for (int n = 0; n < x.size(); n++) {
			buf = buf | x[n] << (7 - numb);
			numb++;
			if (numb == 8) {
				numb = 0;
				g << buf;
				buf = 0;
			}
		}
	}
	numb = 0;
	f.close();
	g.close();
}

void HUFFMAN::rewriteCode() {
	F.open("C:/Users/Pavel/Desktop/rar/encodeHUFFMAN.bzip2", ios::in | ios::binary);
	g.open("C:/Users/Pavel/Desktop/rar/decodeHUFFMAN.bzip2", ios::out | ios::binary);
	char ch3;
	char ch1, ch2;

	Node *p = root;
	bool byte = false;
	ch3 = F.get();
	ch2 = F.get();
	ch1 = F.get();
	while (1) {
		if (numb == 8) {
			ch3 = ch2;
			ch2 = ch1;
			ch1 = F.get();
			numb = 0;
		}
		if (F.eof()) {
			for (int i = 0; i < (int)ch2; i++) {
				byte = ch3 & 1 << (7 - i);
				if (byte)
					p = p->right;
				else
					p = p->left;
				if (p->left == NULL && p->right == NULL) {
					g << p->c;
					p = root;
				}
			}
			break;
		}
		byte = ch3 & 1 << (7 - numb);
		numb++;
		if (byte)
			p = p->right;
		else
			p = p->left;
		if (p->left == NULL && p->right == NULL) {
			g << p->c;
			p = root;
		}
	}
	g.close();
	F.close();
}