#include <iostream>
#include <string>
#include <stack>
#include <sstream>

using namespace std;

class mat {
public:
	string s, Step = "S0 => ";
	bool flag = 1;

	void check(string str) {
		s = str;
		string st, error;
		int k = 0, parenthesis = 0;
		Step += "S2 => ";
		for (int i = 0; i <= s.size(); i++) {

			if (s[i] == '(') {
				parenthesis++;
				k++;
			}
			Step += "S3 => ";
			if (s[i] == ')') {
				parenthesis--;
			}
			Step += "S4 => ";
			if (s[i] == '+' || s[i] == '-' || s[i] == '/' || s[i] == '*' || s[i] == '\0') {
				st = s.substr(k, i - k);
				Step += "S5 => ";
				if (st == "")
					continue;
				k = i + 1;
				Step += "S6 => ";
				if (st != "(" && st.find('.') == std::string::npos) {
					error += "is not real value\n";
				}
				Step += "S7 => ";
				if (strtol(st.c_str(), NULL, 10) > 32767 || strtol(st.c_str(), NULL, 10) < -32768) {
					error += "the value of the number overflow\n";
				}
				Step += "S8 => ";
				st.clear();
			}
		}
		if (parenthesis != 0)
			error += "syntax error\n";
		Step += "S9 => ";
		if (error.empty()) {
			cout << "|True|\nNot error\n";
		}
		else {
			cout << "|False|\nError:\n" << error;
			flag = 0;
		}
		Step += "S10 => ";
	}

	void RPN(string str) {
		s = str;
		stack<pair<char, double>> p;
		string res;
		int k = 0, temp = 0;
		Step += "S11 => ";
		for (int i = 0; i < s.length(); i++) {
			if (s[i] == ' ')
				i++;
			Step += "S12 => ";
			if (isdigit(s[i]) || s[i] == '.') {
				do {
					res += s[i];
					i++;
				} while (isdigit(s[i]) || s[i] == '.');
				res += " ";
				i--;
			}
			else if (s[i] == '*' || s[i] == '/')
				k = 3;
			else if (s[i] == '+' || s[i] == '-')
				k = 2;
			else if (s[i] == '(' || s[i] == ')')
				k = 1;
			Step += "S13 => ";
			if (s[i] == '(') {
				p.push(make_pair(s[i], k));
				k = 0;
			}
			Step += "S14 => ";
			if ((s[i] == '(') && (s[i + 1] == '-')) {
				res += "0.0 ";
			}
			Step += "S15 => ";
			if (s[i] == ')') {
				while (p.top().first != '(') {
					res += p.top().first;
					res += " ";
					p.pop();
				}
				p.pop();
				k = 0;
			}
			Step += "S17 => ";
			if (k > 0 && (p.empty() || p.top().second < k)) {
				p.push(make_pair(s[i], k));
				k = 0;
			}
			if (k > 0 && p.top().second >= k) {
				Step += "S20 => ";
				while (p.top().second >= k) {
					res += " ";
					res += p.top().first;
					res += " ";
					p.pop();
					Step += "S18 => ";
					if (p.empty())
						break;
				}
				Step += "S19 => ";
				if (k > 0 && (p.empty() || p.top().second < k)) {
					p.push(make_pair(s[i], k));
					k = 0;
				}
			}
		}
		Step += "S21 => ";
		while (!p.empty()) {
			res += " ";
			res += p.top().first;
			p.pop();
		}
		s = res;
		Step += "S22 => ";
	}

	void RP() {
		stack<double> a;
		double p1, p2;
		istringstream is;
		is.str(s);
		Step += "S24 => ";
		while (is >> s) {
			if (s == "+") {
				p1 = a.top();
				a.pop();
				p2 = a.top();
				a.pop();
				a.push(p1 + p2);
			}
			else if (s == "*") {
				p1 = a.top();
				a.pop();
				p2 = a.top();
				a.pop();
				a.push(p1 * p2);
			}
			else if (s == "-") {
				p1 = a.top();
				a.pop();
				p2 = a.top();
				a.pop();
				a.push(p2 - p1);
			}
			else if (s == "/") {
				p1 = a.top();
				a.pop();
				p2 = a.top();
				a.pop();
				a.push(p2 / p1);
			}
			else a.push(stod(s));
		}
		cout << a.top();
	}

};

int main() {
	/*
	Test
	2.2 * (3.1 - 1.1) / 3.3 - 22.3 * (123.123 - 111.1)
	2.2 * (17.1 + (-2.1)) / 3.3 - 22.3 * (123.123 - 111.1)
	*/

	string s;
	getline(cin, s);
	mat d;
	d.Step += "S1 => ";
	d.check(s);
	if (d.flag == 0) {
		system("pause");
		exit(0);
	}
	d.RPN(s);
	d.Step += "S23 => ";
	cout << d.s << "\n";
	d.RP();
	cout << "\n" << d.Step;
}