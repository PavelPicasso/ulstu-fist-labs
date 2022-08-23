#include <iostream>
#include <stdlib.h>
#include <math.h>
#include <cmath>
#include <iomanip>

using namespace std;

//�������, ���� ������� ����
double f(double x) { 
	return 0.2*exp(-pow(x, 2)) - sqrt(x) + 3;
	//return 1 - x*log(x) + 0.3*sqrt(x);
}
int main() {
	setlocale(LC_ALL, "Russian");
	double x[1000], e;
	cout << "������� ��������� �����������" << endl;
	cout << "x[0] = ";
	cin >> x[0];
	cout << "x[1] = ";
	cin >> x[1];
	cout << "������� �����������" << endl;
	cout << "e = ";
	cin >> e;
	cout << "\n";
	for (int i = 2; i < 15; i++) {
		x[i] = x[i - 1] - (f(x[i - 1]) * (x[i - 2] - x[i - 1])) / (f(x[i - 2]) - f(x[i - 1]));
		cout << "x[" << i;
		cout.precision(7);
		cout << "] = " << x[i] << endl;
		if (abs(x[i] - x[i - 1]) <= e) {
			cout << endl << "|x[" << i << "] - x[" << i - 1 << "]| <= e" << endl << endl;
			cout << "x ~ " << x[i] << "\n";
			break;
		}
	}
	system("pause");
	return 0;
}
/*
double df(double x) {
    return -0.4*exp(-pow(x,2))*x-1/(2*sqrt(x));
	//return -1 + (0.15 / sqrt(x)) - log(x);
}
 
double g(double x) {
    return x - f(x)/df(x);
}
 
int main() {
	setlocale(LC_ALL, "Russian");
    double x;
    double eps;
    cout<<"������� ��������� ����������� : ";cin>>x;
    cout<<"������� ����������� : ";cin>>eps;
	double r = 100000;
    for(double iter = 1; eps <= r; iter = iter + 1) {
        //system("cls");
		
        cout<<"Iteration : "<<setprecision(0)<<iter<<endl;
        if(df(x) == 0)//׸�������� ������ ������(!)
            break;//���� ���� df(x) == 0, �� ����� ������� �� ���� x - f(x)/df(x)
		cout.precision(7);
        cout<<"x    = "<<x    <<endl;
        cout<<"g(x) = "<<g(x) <<endl;
        cout<<"df(x)= "<<df(x)<<endl;
        cout<<"f(x) = "<<f(x) <<endl;
        x = g(x);
		r = fabs(x - g(x));
		cout << "\n";
    }
    system("pause");
    return 0;
}
*/