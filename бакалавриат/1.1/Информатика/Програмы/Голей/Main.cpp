#include <iostream>
#include <cctype>
#include <fstream>
#include <algorithm>
#include <string>
#include <vector>
#include <list>
#include <map>
using namespace std;

enum MODE { DECOMPRESSION, COMPRESSION };
string inputFileName;

class HAFFMAN {
private:
	MODE mode;
	string *res = new string;
	map<char, int> m;
	vector<bool> code;
	map<char, vector<bool>> table;
	struct Node {
		int weight;
		char c = 0;
		Node *left, *right;

		Node() {
			left = NULL;
			right = NULL;
		}
		Node(Node *l, Node *r) {
			left = l;
			right = r;
			weight = l->weight + r->weight;
		}
	};
	list<Node*> l;
	Node *root;
	struct Cmp {
		bool operator()(const Node *l, const Node *r) const {
			return l->weight < r->weight;
		}
	};

	void initTree() {
		//Инициализация
		for (auto i = m.begin(); i != m.end(); i++) {
			Node *n = new Node();
			n->c = i->first;
			n->weight = i->second;
			l.push_back(n);
		}

		//Создание
		while (l.size() != 1) {
			l.sort(Cmp());

			Node *leftChild = l.front();
			l.pop_front();
			Node *rightChild = l.front();
			l.pop_front();

			Node *parent = new Node(leftChild, rightChild);
			l.push_back(parent);
		}

		//Задание корня
		root = l.front();
	}

	void buildTable(Node *root) {
		if (root->left != NULL) {
			code.push_back(0);
			buildTable(root->left);
		}

		if (root->right != NULL) {
			code.push_back(1);
			buildTable(root->right);
		}

		if (root->left == NULL && root->right == NULL)
			table[root->c] = code;

		if (!code.empty())
			code.pop_back();
	}

	void tree(Node *root, int k = 0) {
		if (root == NULL)
			return;

		k++;
		tree(root->left, k);

		for (int i = 1; i < k; i++)
			cout << "\t";
		cout << root->weight;
		if (root->c)
			cout << " (" << root->c << ")";
		cout << "\n";

		tree(root->right, k);
	}

	void compress() {
		ifstream inputFile(inputFileName, ios::in | ios::binary);

		//init map
		while (!inputFile.eof()) {
			string s;
			getline(inputFile, s);
			for (int i = 0; i < s.size(); i++)
				m[s[i]]++;
		}
		initTree();
		buildTable(root);

		//Таблица
		(*res) += to_string(table.size()) + " ";
		for (auto i = m.begin(); i != m.end(); i++) {
			string s = "1";
			s[0] = char(i->first);
			s += " " + to_string(i->second) + " ";
			(*res) += s;
		}
		(*res) += char(27);

		inputFile.clear(); 
		inputFile.seekg(0);

		//Закодированное сообщение
		int count = 0; char buf = 0;
		while (!inputFile.eof()) {
			string s;
			getline(inputFile, s);
			for (int i = 0; i < s.size(); i++) {
				vector<bool> x = table[s[i]];
				for (int n = 0; n < x.size(); n++) {
					buf = buf | x[n] << (7 - count);
					count++;
					if (count == 8) {
						(*res) += buf;
						count = buf = 0;
					}
				}
			}
		}
		(*res) += buf;

		inputFile.close();
	}

	void decompress() {
		ifstream inputFile(inputFileName, ios::in | ios::binary);

		//init map
		{
			char c, _c;
			_c = inputFile.get();
			_c = inputFile.get();
			int n;
			inputFile >> n;
			_c = inputFile.get();
			for (int i = 0; i < n; i++) {
				c = inputFile.get();
				_c = inputFile.get();
				int x;
				inputFile >> x;
				m[c] = x;
				_c = inputFile.get();
			}
		}
		initTree();
		buildTable(root);

		inputFile.clear();
		inputFile.seekg(0);

		char c = 'a';
		do {
			c = inputFile.get();
		} while (c != 27);

		Node *p = root;
		int count = 0;
		char byte = inputFile.get();
		while (!inputFile.eof()) {
			bool b = byte & (1 << (7 - count));
			if (b)
				p = p->right;
			else
				p = p->left;
			if (p->left == NULL && p->right == NULL) {
				(*res) += char(p->c);
				p = root;
			}
			count++;
			if (count == 8) {
				count = 0;
				byte = inputFile.get();
			}
		}

		inputFile.close();
	}

public:
	HAFFMAN() {}
	HAFFMAN(MODE _mode) {
		mode = _mode;
		switch (mode) {
		case DECOMPRESSION:
			decompress();
			break;
		case COMPRESSION:
			compress();
			break;
		}
	}

	int size() {
		return 2 + (*res).size();
	}

	void saveFile(string outputFileName) {
		ofstream outputFile(outputFileName, ios::out | ios::binary);
		if (mode == COMPRESSION)
			outputFile << "H ";
		outputFile << (*res);
		outputFile.close();
	}

	void showOnScreen() {
		cout << endl << (*res) << endl;
	}

	void printTree() {
		tree(root);
	}

	void printTable() {
		for (auto i = table.begin(); i != table.end(); i++) {
			cout << "'" << i->first << "'" << "\t";
			vector<bool> x = i->second;
			for (int j = 0; j < x.size(); j++)
				cout << x[j];
			cout << endl;
		}
	}

	map<char, int> getMap() {
		return m;
	}
};

class RLE {
private:
	MODE mode;
	string *res = new string;
	map<char, int> m;

	void compress() {
		ifstream inputFile(inputFileName, ios::in | ios::binary);

		char p = inputFile.get();
		int len = 1;
		while (!inputFile.eof()) {
			char c = inputFile.get();
			if (p == c)
				len++;
			else {
				(*res) += to_string(len) + " " + p + " ";
				m[p] = max(m[p], len);

				p = c;
				len = 1;
			}
		}

		inputFile.close();
	}

	void decompress() {
		ifstream inputFile(inputFileName, ios::in | ios::binary);
		
		char _c = inputFile.get();
		_c = inputFile.get();

		while (!inputFile.eof()) {
			int len;
			inputFile >> len;
			_c = inputFile.get();
			if (_c == -1)
				break;
			char c = inputFile.get();
			for (int i = 0; i < len; i++)
				(*res) += c;
			_c = inputFile.get();
		}

		inputFile.close();
	}

public:
	RLE() {}
	RLE(MODE _mode) {
		mode = _mode;
		switch (mode) {
		case DECOMPRESSION:
			decompress();
			break;
		case COMPRESSION:
			compress();
			break;
		}
	}

	int size() {
		return 2 + (*res).size();
	}

	void saveFile(string outputFileName) {
		ofstream outputFile(outputFileName, ios::out | ios::binary);
		if (mode == COMPRESSION)
			outputFile << "R ";
		outputFile << (*res);
		outputFile.close();
	}

	void showOnScreen() {
		cout << endl << (*res) << endl;
	}

	map<char, int> getMap() {
		return m;
	}

};

class LZW {
private:
	MODE mode;
	string *res = new string;
	map<int, string> table;

	void initTable() {
		for (int c = 0; c < 256; c++) {
			string s = "1";
			s[0] = char(c);
			table[c] = s;
		}
	}

	void compress() {
		ifstream inputFile(inputFileName, ios::in | ios::binary);

		string s = "1";
		char c = inputFile.get();
		s[0] = c;

		while (!inputFile.eof()) {
			c = inputFile.get();
			string ns = s + "1";
			ns[ns.size() - 1] = c;

			bool inTable = 0;
			for (auto i = table.begin(); i != table.end(); i++)
				if (i->second == ns) 
					inTable = 1;

			if (inTable)
				s = ns;
			else {
				table[table.size()] = ns;
				
				if (s.size() == 1)
					(*res) += s;
				else {
					auto i = table.begin();
					for (i; i != table.end(); i++)
						if (i->second == s)
							break;
					(*res) += char(27);
					(*res) += to_string(i->first - 255);
					(*res) += char(27);
				}

				s = "1";
				s[0] = c;
			}

		}

		inputFile.close();
	}

	void decompress() {
		ifstream inputFile(inputFileName, ios::in | ios::binary);

		char _c;
		_c = inputFile.get();
		_c = inputFile.get();

		int stc = int(inputFile.get());
		(*res) += table[stc];

		while (!inputFile.eof()) {
			int nc = int(inputFile.get());

			if (nc == 27) {
				inputFile >> nc;
				nc += 255;
				_c = inputFile.get();
			} 

			(*res) += table[nc];
			
			table[table.size()] = table[stc] + table[nc][0];

			stc = nc;
		}

		inputFile.close();
	}
public:
	LZW() {}
	LZW(MODE _mode) {
		mode = _mode;
		initTable();
		switch (mode) {
		case DECOMPRESSION:
			decompress();
			break;
		case COMPRESSION:
			compress();
			break;
		}
	}

	int size() {
		return 2 + (*res).size();
	}

	void saveFile(string outputFileName) {
		ofstream outputFile(outputFileName, ios::out | ios::binary);
		if (mode == COMPRESSION)
			outputFile << "L ";
		outputFile << (*res);
		outputFile.close();
	}

	void showOnScreen() {
		cout << endl << (*res) << endl;
	}

	map<int, string> getMap() {
		return table;
	}
};

class KWE {
private:
	MODE mode;
	string *res = new string;
	map<char, string> m;

	void compress() {
		ifstream inputFile(inputFileName, ios::in | ios::binary);

		//Input -> Table
		vector<pair<int, string>> v;
		{
			map<string, int> m;
			while (!inputFile.eof()) {
				string s;
				inputFile >> s;
				if (s.find(" ") == string::npos) 
					m[s]++;
			}
			for (auto i = m.begin(); i != m.end(); i++)
				v.push_back(make_pair(i->second, i->first));
		}

		sort(v.begin(), v.end());
		while (v.size() > 255)
			v.pop_back();
		
		map<string, char> lm;
		for (int i = 0; i < v.size(); i++) {
			lm[v[i].second] = char(i);
			m[i] = v[i].second;
		}

		//Таблица -> RES
		(*res) += to_string(v.size()) + " ";
		for (int i = 0; i < v.size(); i++) {
			(*res) += char(i);
			(*res) += v[i].second + " ";
		}

		//Закодированное -> RES
		inputFile.clear();
		inputFile.seekg(0);

		while (!inputFile.eof()) {
			string s;
			inputFile >> s;
			if (lm.find(s) != lm.end()) {
				(*res) += char(27);
				(*res) += lm[s];
			} else
				(*res) += s + " ";
		}

		inputFile.close();
	}

	void decompress() {
		ifstream inputFile(inputFileName, ios::in | ios::binary);

		char _c;
		_c = inputFile.get();
		_c = inputFile.get();

		int n;
		inputFile >> n;
		_c = inputFile.get();

		for (int i = 0; i < n; i++) {
			char c = inputFile.get();
			string s = "";
			char sc = inputFile.get();
			while (sc != ' ') {
				s += sc;
				sc = inputFile.get();
			}
			m[c] = s;
		}
		
		while (!inputFile.eof()) {
			string s;
			getline(inputFile, s);
			for (int i = 0; i < s.size(); i++) {
				char c = s[i];
				if (c == 27) {
					i++;
					c = s[i];
					(*res) += m[c] + " ";
				} else 
					(*res) += c;
			}
		}

		inputFile.close();
	}

public:
	KWE() {}
	KWE(MODE _mode) {
		mode = _mode;
		switch (mode) {
		case DECOMPRESSION:
			decompress();
			break;
		case COMPRESSION:
			compress();
			break;
		}
	}

	int size() {
		return (*res).size();
	}

	void saveFile(string outputFileName) {
		ofstream outputFile(outputFileName, ios::out | ios::binary);
		if (mode == COMPRESSION)
			outputFile << "K ";
		outputFile << (*res);
		outputFile.close();
	}

	void showOnScreen() {
		cout << endl << (*res) << endl;
	}
	
	map<char, string> getMap() {
		return m;
	}
};

int decompressedSize() {
	int size = 0;
	ifstream inputFile(inputFileName, ios::in | ios::binary);

	while (!inputFile.eof()) {
		string s;
		getline(inputFile, s);
		size += s.size();
	}

	inputFile.close();
	return size;
}

int main() {
	setlocale(LC_ALL, "Russian");

	int d;
	{
		cout << "Сжать \t\t 1" << endl << "Распаковать \t 2" << endl << "Выход \t\t 3" << endl << "Действие: ";
		cin >> d;

		if (d == 3)
			return 0;

		system("cls");

		cout << "Полное имя файла: ";
		cin >> inputFileName;
		system("cls");

		cout << "Анализ файла...";
	}

	//Сжатие
	if (d == 1) {
		RLE rle(COMPRESSION);
		LZW lzw(COMPRESSION);
		HAFFMAN haffman(COMPRESSION);
		KWE kwe(COMPRESSION);

		int decSize = decompressedSize();
		int rleSize = rle.size();
		int lzwSize = lzw.size();
		int haffSize = haffman.size();
		int kweSize = kwe.size();
		
		int d2 = 0;

		while (1) {
			{
				{
					system("cls");

					cout << "Размер файла:" << endl;

					char bar = '=';

					cout << "\tДо сжатия\t" << decSize << "\tбайт\t";
					for (int i = 0; i < (3 + decSize % 10); i++)
						cout << bar;
					cout << endl;

					cout << "\tRLE\t\t" << rleSize << "\tбайт\t";
					for (int i = 0; i < ceil(1.0 * (3 + decSize % 10) * rleSize / decSize); i++)
						cout << bar;
					cout << endl;

					cout << "\tLZW\t\t" << lzwSize << "\tбайт\t";
					for (int i = 0; i < ceil(1.0 * (3 + decSize % 10) * lzwSize / decSize); i++)
						cout << bar;
					cout << endl;

					cout << "\tHAFFMAN\t\t" << haffSize << "\tбайт\t";
					for (int i = 0; i < ceil(1.0 * (3 + decSize % 10) * haffSize / decSize); i++)
						cout << bar;
					cout << endl;

					cout << "\tKWE\t\t" << kweSize << "\tбайт\t";
					for (int i = 0; i < ceil(1.0 * (3 + decSize % 10) * kweSize / decSize); i++)
						cout << bar;
					cout << endl << endl;
				}

				vector<pair<int, string>> p;
				{
					p.push_back(make_pair(decSize, "dec"));
					p.push_back(make_pair(rleSize, "rle"));
					p.push_back(make_pair(lzwSize, "lzw"));
					p.push_back(make_pair(haffSize, "haff"));
					p.push_back(make_pair(kweSize, "kwe"));
					sort(p.begin(), p.end());
				}

				if (p[0].second == "dec")
					cout << "Очевидно, ваш файл невозможно оптимально сжать. Лучше оставьте так как есть...";
				if (p[0].second == "rle") {
					map<char, int> m = rle.getMap();
					vector<pair<int, char>> v;
					for (auto i = m.begin(); i != m.end(); i++)
						v.push_back(make_pair(i->second, i->first));
					sort(v.begin(), v.end());
					cout << "Из-за строчки из символа '";
					cout << ((char)v[v.size() - 1].second);
					cout << "', длинной " + to_string(v[v.size() - 1].first) + ", алгоритм RLE самый выгодный.";
				}
				if (p[0].second == "haff") {
					map<char, int> m = haffman.getMap();
					vector<pair<int, char>> v;
					for (auto i = m.begin(); i != m.end(); i++)
						v.push_back(make_pair(i->second, i->first));
					sort(v.begin(), v.end());
					cout << "Из-за часто встечающихся символов ";
					for (int i = v.size() - 1; i >= max(0, (int)v.size() - 6); i--)
						cout << "'" << v[i].second << "' ";
					cout << "алгоритм Хаффмана самый выгодный.";
				}
				if (p[0].second == "lzw") {
					map<int, string> m = lzw.getMap();
					vector<string> v;
					for (auto i = m.begin(); i != m.end(); i++)
						v.push_back(i->second);
					sort(v.begin(), v.end());
					cout << "Из-за часто встечающихся подстрок ";
					for (int i = v.size() - 1; i >= max(0, (int)v.size() - 6); i--)
						cout << "'" << v[i] << "' ";
					cout << "алгоритм LZW самый выгодный.";
				}
				if (p[0].second == "kwe") {
					cout << "kwe!";
				}
				cout << endl << endl;
			}
			if (d2 == 0) {
				cout << "Сохранить \t\t\t\t 1" << endl << "Подробнее про алгоритм RLE \t\t 2" << endl << "Подробнее про алгоритм LZW \t\t 3" << endl << "Подробнее про алгоритм Хаффмана \t 4" << endl << "Подробнее про алгоритм KWE \t\t 5" << endl << "Выход \t\t\t\t\t 6";
				cout << endl << endl;
				cout << "Действие: ";
				cin >> d2;
				system("cls");
			}
			if (d2 == 1) {
				cout << "Полное имя сохраняемого файла: ";
				string outputFileName;
				cin >> outputFileName;

				cout << endl;
				cout << "Сохранить как:" << endl;
				cout << "\tRLE\t1" << endl;
				cout << "\tLZW\t2" << endl;
				cout << "\tHAFFMAN\t3" << endl;
				cout << "\tKWE\t4" << endl;
				cout << endl;

				cout << "Действие: ";
				int d3;
				cin >> d3;

				if (d3 == 1)
					rle.saveFile(outputFileName);
				if (d3 == 2)
					lzw.saveFile(outputFileName);
				if (d3 == 3)
					haffman.saveFile(outputFileName);
				if (d3 == 4)
					kwe.saveFile(outputFileName);
				cout << endl;
				system("pause");
			}
			if (d2 == 2) {
				cout << "Самые длинные серии символов:" << endl;
				map<char, int> m = rle.getMap();
				vector<pair<int, char>> v;
				for (auto i = m.begin(); i != m.end(); i++)
					v.push_back(make_pair(i->second, i->first));
				sort(v.begin(), v.end());

				for (int i = 0; i < min(5, (int)v.size()); i++) {
					cout << "\t'";
					cout << ((char)v[i].second);
					cout << "' ";
					cout << v[i].first << endl;
				}
				cout << endl;
				system("pause");
			}
			if (d2 == 3) {
				cout << "Таблица LZW:" << endl;
				map<int, string> m = lzw.getMap();
				for (auto i = m.begin(); i != m.end(); i++)
					if (i->first > 255)
						cout << "\t" << to_string(i->first) << " - " << i->second << endl;
				cout << endl;
				system("pause");
			}
			if (d2 == 4) {
				cout << "Таблица Хаффмана:" << endl;
				haffman.printTable();
				cout << endl << endl;
				cout << "Дерево Хаффмана:" << endl;
				haffman.printTree();
				cout << endl << endl;
				system("pause");
			}
			if (d2 == 5) {
				cout << "Таблица KWE:" << endl;
				map<char, string> m = kwe.getMap();
				for (auto i = m.begin(); i != m.end(); i++)
					cout << "\t" << (char(i->first)) << " (" << to_string(int(i->first)) << ") - " << i->second << endl;
				cout << endl;
				system("pause");
			}
			if (d2 == 6)
				break;

			system("cls");
			d2 = 0;
		}
		
	}

	//Распаковка
	if (d == 2) {
		RLE rle;
		LZW lzw;
		HAFFMAN haffman;
		KWE kwe;

		ifstream inputFile(inputFileName, ios::in | ios::binary);
		char c = inputFile.get();
		inputFile.close();

		int compSize = decompressedSize();
		int decompSize = 0;

		if (c == 'H') {
			haffman = HAFFMAN(DECOMPRESSION);
			decompSize = haffman.size();
		}
		if (c == 'R') {
			rle = RLE(DECOMPRESSION);
			decompSize = rle.size();
		}
		if (c == 'L') {
			lzw = LZW(DECOMPRESSION);
			decompSize = lzw.size();
		}
		if (c == 'K') {
			kwe = KWE(DECOMPRESSION);
			decompSize = kwe.size();
		}
		decompSize -= 2;

		system("cls");

		int d2 = 0;

		while (1) { 
			{
				cout << "Размер:" << endl;

				char bar = '=';

				cout << "\tСжатого файла\t\t" << compSize << "\tбайт\t";
				for (int i = 0; i < (3 + compSize % 10); i++)
					cout << bar;
				cout << endl;

				cout << "\tРазжатого файла\t\t" << decompSize << "\tбайт\t";
				for (int i = 0; i < ceil(1.0 * (3 + compSize % 10) * decompSize / compSize); i++)
					cout << bar;
				cout << endl << endl;

				cout << "Коэфициент сжатия: " << (1.0 * decompSize / compSize) << " %";
				cout << endl << endl;
			}
			if (d2 == 0) {
				cout << "Вывести сообщение на экран \t 1" << endl << "Сохранить \t\t\t 2" << endl << "Выход \t\t\t\t 3";
				cout << endl << endl;
				cout << "Действие: ";
				cin >> d2;
				system("cls");
			}
			if (d2 == 1) {
				if (c == 'R')
					rle.showOnScreen();
				if (c == 'L')
					lzw.showOnScreen();
				if (c == 'H')
					haffman.showOnScreen(); 
				if (c == 'K')
					kwe.showOnScreen();

				cout << endl;
				system("pause");
			}
			if (d2 == 2) {
				cout << "Полное имя сохраняемого файла: ";
				string outputFileName;
				cin >> outputFileName;

				if (c == 'R')
					rle.saveFile(outputFileName);
				if (c == 'L')
					lzw.saveFile(outputFileName);
				if (c == 'H')
					haffman.saveFile(outputFileName);
				if (c == 'K')
					kwe.saveFile(outputFileName);

				cout << endl;
				system("pause");
			}
			if (d2 == 3)
				break;
			system("cls");
			d2 = 0;
		}
	}
}
