#include <stdio.h>
#include <algorithm>
#include <iostream>
#include <Math.h>
#include <string>
using namespace std;


/*
����� ������� ������������ ���������������������
f(i) - ������ ��� �� �������, ����. ���������������� � i

1 4 2 3 8 5 2 7 

1 2 2 3 4 4 2 1

*/

int answers[100010] = {};

int lis(int a[], int n) {

	for (int i = 0; i <= n; i++) {
		answers[i] = 1;
		//����� ����, ������� ���� ���������� a[i]
		//��� ����� �������
		for (int j = 0; j < i; j++) {
			if (a[j] < a[i]) {
				answers[i] = max(answers[i], answers[j] + 1);
			}
		}
	}
	return *max_element(a, a + n);
}


/*
f(n, m) - ������� �������� ������� n �� m

f(1,1) = 1
f(n, m) = 1

f(1, n) = n


	int answer[10][10] = {};
	for (int items = 1; items <= 10; items++) {
		answer[1][items] = items;
		for (int choose = 2; choose < items; choose++) {
			answer[items][choose] = answer[items - 1][choose] + answer[items - 1][choose - 1];
		}
		answer[items][items] = 1;
	}


*/

/*

string a = "algorithm";
string b = "logarithm";

int answer[10][10] = {};

for (int ka = 1; ka <= a.size(); ka++) {
	for (int kb = 1; kb < b.size(); kb++) {
		if (a[ka - 1] == b[kb - 1]) {
			answer[ka][kb] = 1 + answer[ka - 1][kb - 1];
		} else {
			answer[ka][kb] = max(answer[ka - 1][kb], answer[ka][kb - 1]);
			//answer[ka][kb] = 0; ���������� ����� ���������
		}
	}
}

*/

/*
���� �������  2��3 3��4 4��1 1��2
��� ����������� ���������� �������

int bestMult(int l, int r, int a[]) {
	if (l == r)
		return answer[l][r];
	answerReady[l][r] = 1;

	if(l == r)
		return answer[l][r] = 0

	answer[l][r] = 1 << 30;
	for (int i = l + 1; i <= r; i++) {
		answer[l][r] = min(answer[l][r], bestMult(l, i - 1, a) + bestMult(i, r, a) + a[l] * a[i] * a[r]);
	}
	answer[l][r];
}

*/
	/*
	������ � �������

	1 ����: ����������� ��������
	2 ����: �����

	�����     ����
1)    3			5
2)	  2			8
3)	  2			10
4)	  1			2
5)	  2			3
6)	  5			7

				 ����� �������
	  0  1  2  3  4  5  6  7  8  9
	  0 -1 -1 -1 -1 -1 -1 -1 -1 -1
	  0        5
	  0     8  5    13
	  0    10  5 18 15    23
	*/



/*
��������� � ����� ������� � ������������� �������

insert(T value)
erase(T alue)
find(T value)

value - ����� ����� �� 0 �� 99
class SimpleSet {
	bool mas[100];
public:
	SimpleSet{
		fill(a, a + 100, 0);
	}
	void insert(int id){
		a[id] = 1;
	}
	void erase(int id){
		a[id] = 0; 
	}
	void find(int id){
		return a[id];
	}
}

class BitSet {
	unsigned char a[13];
public:
	BitSet{
		fill(a, a + 13, 0);
	}
	void insert(int id){
		int index = id / 8;
		int bit = id % 8;
		unsigned char mask = 1 << bit;
		a[index] |= mask;
	}
	void erase(int id){
		int index = id / 8;
		int bit = id % 8;
		unsigned char mask = `(1 << bit);
		a[index] |= mask;
	}
	void find(int id){
		int index = id / 8;
		int bit = id % 8;
		unsigned char mask = 1 << bit;
		return a[index] & mask;
	}
}


bitset<100> bs;


hash ������� 

class HashSet {
	int a[100];
public:
	void insert(int x) {
		int index = hash(x);
		while (a[index] != 0 && a[index] != -1)
			index = (index + 1) % 100;
		a[index] = x;

	}
	void erase(int x) {
		int index = hesh(x);
		while (a[index] != 0 && a[index] != x)
			index = (index + 1) % 100;
		if(a[index] == x)
			a[index] = -1;		
	}
	void find(int id) {
		int index = hesh(x);
		while (a[index] != 0 && a[index] != x)
			index = (index + 1) % 100;
		return a[index] == x;
	}
}


1) ����� �������
2) �������� ���������
3) ���������������� ��������(+1)
4)�������� ��������(+i)/������������(+i^2) 


����������� �������
��� ����������� ����� ��������������� ������
����� ���-�����


�������� ������ ������

��������������� ������ set(��������� ��� ����������)

int n;
cin >> n;
set<int> s;
for(int i = 0; i< n; i++) {
	int x;
	cin >> x;
	s.insert(x);
}

for(set<int>::iterator i = s.begin(); i != s.end(); i++) {
	cout << *i << " ";
}

s.erase(3);
cout << "\n";

VECTOR � SET ��� ���!!!


��� � ��� set 

1) ������������� ������� ��������� (���������� ������ - size())
2) �� ���������� (����� ������� �� begin() �� end() - ������� root)
3) insert �� log(N)
4) erase �� log(N)
5) find (���������� �������� ��� end() �� log(N))
6) cout (���������� 0 ��� 1 �� log(N))
!!! set ����� �����, ��� ������ find, count � ��. (�� �������. algorithm)
7) lower_bound � upper_bound �� log(N)

multiset ������(���� set) ������� ����� ������� �����, �� 


template <class T1, class T2>
pair<T1, T2> {
	T1 first;
	T2 second;
}


� set ���� ���� ������� ���� map

map<string, int> m;   ���� � ��������
������� ����� ���� ��� ��� ������

m["Ianov"] = 123;
m["Petro"] = 456;
cout << m["Ianov"];
*/


/*

���������� ������

1) �� ������ ��� �������� ��������� �������� ������ ������
2) �� ����������� ��������� ��������

���������� ������ ������ ������ ���������� � ������� �� ������ �� ���������

������ Treap

	struct Node {
		int key;
		int priority;
		int value;
		int valueSum;
		int elementsCount;
		Node *left, *right;
		Node(int k, int v) {
			key = k;
			value = v;
			priority = rand(); ����� ����� ����� ������ ��������� �� �������
			left = right = 0;
			elementsCount = 1;
			valueSum = value;
		}	
	};

	class Treap {
		Node *root;
		
		void fix(Node *&n){
			if (!n){
				return;
			}
			int leftElementCount = (n->left ? n->left=>elementCount : 0);
			int rightElementCount = (n->right ? n->right=>elementCount : 0);
			n->elementsCount = 1 + leftElementCount + rightElementCount;

			int leftValueSum = (n->left ? n->left=>elementSum : 0);
			int rightValueSum =  (n->right ? n->right=>elementSum : 0);
			n->valueSum - n->value + leftValueSum + rightValueSum;
		}
		����� � < ������ b

		Node *merge(Node *a, Node *b){
			if (!a || !b) {
				return a ? a : b;	
			}
			if(a->priority > b->priority) {
				a->right = merge(a->right, b);
				return a;
			}
			else {
				b->left = merge(a, b->left)
				return b;
			}
		}

		Node *split(Node *t, int k, Node *&a, Node *&b){
			if (!t){
				a = b = 0;
				return;
			}
			if(t-<key < k){
				split(t->right, k, t->right, b);
				a = t;
			}else {
				split(t->left, k, a, b->left);
				b = t;
			}
		}

		int getByIndex(Node *n, int index){
			int leftElementCount = (n->left ? n->left=>elementCount : 0);
			if (index < leftElementCount)
				return getByIndex(n->left, index);
			if(index == leftElementCount)
				return n->value;
			if (index > leftElementCount)
				return getByIndex(n->right, index - leftElementCount - 1);
		}


	public:
		Treap() {
			root = 0;
		}
		insert(int key, int value){
			Node *a, *b;
			split(root, key, a, b);
			a = merge(a, new Node(key, value));
			root = merge(a, b);
		}

		void erase(int k){
			Node *a, *b, *c;
			split(root, key, a, b);
			split(b, key + 1, b, c);
			root = merge(a, c);
		}

		bool erase(int k){
			Node *a, *b, *c;
			split(root, key, a, b);
			bool res = b != 0;
			a = merge(a, b);
			root = merge(a, c);
			return res;
		}

		int getValue(int k){
			Node *a, *b, *c;
			split(root, key, a, b);
			int res = b != b->value;
			a = merge(a, b);
			root = merge(a, c);
			return res;
		}

		int operator [](int k){
			return getByindex(root, index);
		}

		int getValueSum(int l, int r) {
			Node *a, *b, *c;
			split(root, l, a, b);
			split(b, r - l + 1, b, c);
			int res = 0;
			if (b != 0) {
				res = b->valueSum;
			}
			a = merge(a,b);
			root - merge(a, c);
			return res;

		}
	}

	�������� merge




*/



/*
����� - �� ������ � �� �����
����������� �����
������� �����

������������� ������
1) ������� ��������
2) ������ ��������
3) ������ �����

vectov<int> g[100];
int mg[100][100];

int n,m;
scanf("%d %d", &n, &m);

������� ���������
for(int i = 0; i < n; i++){
	int a, b;
	scanf("%d %d", &a, &b);
	gm[a - 1][b - 1] = 1;
	gm[b - 1][a - 1] = 1;
}

������ ���������
for(int i = 0; i < n; i++){
	int a, b;
	scanf("%d %d", &a, &b);
	g[a - 1].push_back(b - 1);// ��������� ���������
	g[b - 1].push_back(a - 1);
}

DFS ����� � �������

vectov<int> g[100];
4 4
1 2
1 3
1 4
2 3

int visited[100];
�������� �� O(N + M)
void dfs(int v) {
	visited[v] = 1;
	for(int i = 0; i < g[v].size(); i++) {
		int to = g[v][i];
		if (!isited[to])
		dfs(to);
	}
}

��� ������� ���������
void dfs(int v) {
	visited[v] = 1;
	for(int i = 0; i < n; i++) {
		if(gm[v][i] && !visited[i])
		dfs(i);
	}
}


��������� ���������(���-��) O(N + M)

int connectedComponents = 0;
for(int i = 0; i < n; i++) {
	if(!visited[i]) {
		connectedComponents++;
		dfs(i);
	}
}


-�������� �����������
***
***
***


�������������� ����������







*/
