#include <stdlib.h>
#include <stdio.h>
#include <iostream>
#include <math.h>
#include <time.h>
#include <random>
#include <algorithm>
#include <vector>

using namespace std;

#define N 22 // Степень порешности.
#define M 1000000 // Количество значений аргумента.
long long S = M * 60;

typedef long long ll;
const ll inf = 1000000007;
const float  lX = 0.000286102f, // левая граница интервала
		     bX = 1.0f;// првая граница интервала


typedef float(*PFLOAT)(float);
typedef int fix;
typedef  fix(*PFIX)(fix x);

float E = 1.0 / (1 << N),
A[N], // Массив коэффициентов схемы Горнера.
temp[N],
FixA[N]; // Массив коэффициентов схемы Горнера в формате с фиксированной точкой.

vector<float> Arg; // Значения аргумента.
vector<fix>  FixArg; // Значения аргумента в формате с фиксированной точкой
long long Z = 1 << N; //цена младшего разряда
					  //Встраиваемая функция преобразование числа формата float в формат с фиксированной точкой :

inline long long floatToFix(float arg) {
	return long long(arg * Z);
}
//Встраиваемая функция преобразование числа в формате с фиксированной точкой в формат float:
inline float fixToFloat(long long arg) {
	return (float)arg / Z;
}
//Встраиваемая функция умножения чисел  с фиксированной точкой::
inline long long fixMn(long long arg1, long long arg2) {
	long long rs = arg1 * arg2;
	rs = rs / Z;
	return rs;
}

float fS1(float x) {
	return (exp(x) + exp(-x)) / 2;
}

float fS2(float x) {
	return exp(x) / 2 - exp(-x) / 2;
}

float fS3(float x) {
	return exp(x) / 2 + exp(-x) / 2;
}

/* Вычисляет  значения аргумента. */
void initArg() {
	int  k = 0;
	float i;
	k = 0;
	for (i = lX; i < 1.0f; i += (bX - lX) / M) {
		Arg.push_back(i);
		FixArg.push_back(floatToFix(i));
	}
}

long long factr(int n) {
	int i;
	long long res = 1;
	for (i = 2; i <= n; i++)
		res *= i; // Вычисляем факториал.
	return res;
}

/* Определяет ch числа по разложению в ряд с циклом без схемы Горнера.*/
float simple_cycle_no_gorner(float x) {
	float p, // Степень аргумента.
		  member, // Член ряда.
		  result; // Значение косеканса.

	int n, // Подфакториальное выражение.
		i; // Номер члена ряда.

	result = member = p = 1; // Задаём нулевой член.

	/* Суммируем члены ряда. */
	for(i = 1, n = 1; i < 15; i++, n++) {
		p *= x * x;
		member = p / factr(n * 2); // Вычисляем член.
		result += member; // Прибавляем член к сумме ряда.
	}

	return result;
}

float simple_cycle_gorner(float x) {
	float s, p = x * x; /*Сумма первых членов ряда.*/
	unsigned i; /*Номер члена ряда.*/
	s = A[14]; /*Задаём нулевой член.*/

	for (i = 13; i >= 1; i--) /*Суммируем члены ряда.*/
		s = s * p + A[i];
	return s * x + 1.0f;
}

float simple_no_cycle_gorner(float x) {
	float p = x * x;

	return 1.0f + (((((((((((((A[14] * p + A[13]) *p + A[12]) * p + A[11]) * p + A[10]) * p + A[9]) * p + A[8]) * p + A[7]) *p + A[6]) * p +
		A[5]) * p + A[4]) * p + A[3]) * p + A[2]) * p + A[1])*x;
}

float simple_no_cycle_no_gorner(float x) {
	float p, member, result;
	result = member = p = 1;

	/* Суммируем члены ряда. */
	int n = 1;
	p *= x * x;
	member = p / factr(n * 2);
	result += member;
	
	n = 2;
	p *= x * x;
	member = p / factr(n * 2);
	result += member;

	n = 3;
	p *= x * x;
	member = p / factr(n * 2);

	n = 4;
	p *= x * x;
	member = p / factr(n * 2);
	result += member;
	result += member;

	n = 5;
	p *= x * x;
	member = p / factr(n * 2);
	result += member;

	n = 6;
	p *= x * x;
	member = p / factr(n * 2);
	result += member;

	n = 7;
	p *= x * x;
	member = p / factr(n * 2);
	result += member;

	n = 8;
	p *= x * x;
	member = p / factr(n * 2);
	result += member;

	n = 9;
	p *= x * x;
	member = p / factr(n * 2);
	result += member;

	n = 10;
	p *= x * x;
	member = p / factr(n * 2);
	result += member;

	n = 11;
	p *= x * x;
	member = p / factr(n * 2);
	result += member;

	n = 12;
	p *= x * x;
	member = p / factr(n * 2);
	result += member;

	n = 13;
	p *= x * x;
	member = p / factr(n * 2);
	result += member;

	n = 14;
	p *= x * x;
	member = p / factr(n * 2);
	result += member;

	n = 15;
	p *= x * x;
	member = p / factr(n * 2);
	result += member;

	return result;
}

/* Определяет ch числа с помощью циклов и схемы Горнера. */
fix FixCyclGorner(fix x) {
	fix s, p; /*Сумма первых членов ряда.*/

	int i; /*Номер члена ряда.*/
	s = FixA[14]; /*Задаём нулевой член.*/
	p = fixMn(x, x);

	for (i = 13; i >= 1; i--) /*Суммируем члены ряда.*/
		s = fixMn(s, p) + FixA[i];
	return (fixMn(s, x) + 1.0f);
}

void test(fix x) {
	fix x2 = fixMn(x, x);

	for (int i = 14; i >= 1; i--) {
		temp[i] = temp[i + 1] + fixMn(FixA[i], x2);
	}
}
fix TestFixNoCyclGorner(fix x) {
	fix x2 = fixMn(x, x);
	return  (temp[14] + temp[13] + temp[12] + temp[11] + temp[10] + temp[9] + temp[8] + temp[7] + temp[6] + temp[5] + temp[4] + temp[3] + temp[2] + temp[1] + 1.0f);
}


/* Определяет ch числа с помощью схемы Горнера без циклов. */
fix FixNoCyclGorner(fix x) {
	fix x2 = fixMn(x, x);
	return  (fixMn(fixMn(fixMn(fixMn(fixMn(fixMn(fixMn(fixMn(fixMn(fixMn(fixMn(fixMn(fixMn(fixMn(FixA[14], x2) + FixA[13], x2) + FixA[12], x2) +
		+FixA[11], x2) + FixA[10], x2) + FixA[9], x2) + FixA[8], x2) + FixA[7], x2) + FixA[6], x2) +
		FixA[5], x2) + FixA[4], x2) + FixA[3], x2) + FixA[2], x2) + FixA[1], x) + 1.0f);

}

int fixverify(PFIX  p) {
	float val, et, x;
	for (x = E; x < 1.0f; x += E) {
		val = p(floatToFix(x)); // Вычисляем значение проверяемой функции.
		et = (exp(fixToFloat(floatToFix(x))) + exp(-(fixToFloat(floatToFix(x))))) / 2; // Вычисляем значение эталонной функции.
		if (fabs(val - et) < E)
			return true;
	}
	return false;
}


/*Выполняет инициализацию коэффициентов схемы Горнера.*/
void initGorner() {
	int n, /*Подфакториальное выражение.*/
		i, /*Номер члена ряда.*/
		x;
	/* Расчёт коэффициентов. */
	for (i = 1, n = 1, x = 0; i < 15; i++, n++, x += E) {
		A[i] = 1.0f / factr(n * 2); /*Вычисляем коэффициент.*/
		FixA[i] = floatToFix(A[i]);

	}
}

/* Проверяет погрешность функции для заданного аргумента. */
int flverify(PFLOAT p) {
	float val, et, x;
	int kr = 0;
	for (x = 0; x < 1.0f; x += E) {
		val = p(x); // Вычисляем значение проверяемой функции.
		et = (exp(x) + exp(-x)) / 2; // Вычисляем значение эталонной функции.
		if (fabs(val - et) < E) {
			if (fabs(val - et) < E && kr == 1200) {
				cout << "lx=" << x << " ";
				return false;
			}
			kr++;
		}
	}
	return true;
}

/* Замер времени float */
float sm = 0;
long long ObTimeFS(PFLOAT func, long long cnt) {

	for (long long i = 0; i < cnt; ++i) {
		sm += func(Arg[i]);
	}
	return sm;
}

double SrTime(PFLOAT func) {
	double rs;
	double tm1 = 1000000000;
	long long cnt = size(Arg);
	for (int i = 0; i < 10; i++) {
		long long st = clock();
		rs = double(ObTimeFS(func, cnt) * 1000000) / cnt;
		long long end = clock();
		if (end - st < tm1)  tm1 = end - st;
	}
	return tm1;
}


/* Замер времени fix	*/
float sm2 = 0;
long long oBTimeFIXFS(PFIX func, long long cnt) {

	for (long long i = 0; i < cnt; ++i) {
		sm2 += func(FixArg[i]);
	}
	return sm2;
}

double SrTimeFIX(PFIX func) {
	double rs2;
	double tm2 = 100000000;
	long long cnt = size(Arg);
	for (int i = 0; i < 10; i++) {
		long long st2 = clock();
		rs2 = double(oBTimeFIXFS(func, cnt) * 1000000) / S;
		long long end2 = clock();
		if (end2 - st2 < tm2)  tm2 = end2 - st2;
	}
	return tm2;
}


float delta;
int szTable = 2221;
float table[3000][4], val[3000];
void stat(int szTable) {
	delta = 1.0f / szTable;
	cout << szTable << " " << (FixNoCyclGorner(floatToFix(bX)) < E ? 1 : 0) << " " << (fS1(bX) * delta < E ? 1 : 0)
		<< " " << (fS2(bX) * delta * delta < E ? 1 : 0) << " " << (fS3(bX) * delta * delta * delta < E ? 1 : 0);
	cout << "\n";
}

void genTable() {
	delta = 1.0 / szTable;
	float x = 0;
	for (int i = 0; i < szTable; ++i, x += delta) {
		val[i] = x;
		table[i][0] = FixNoCyclGorner(floatToFix(x));
		table[i][1] = fS1(x);
	}
}

float acosTable(float x) {
	int ind = (szTable - 1) * x;
	float h = x - val[ind];
	return table[ind][0] + table[ind][1] * h;
}


int main() {	
	setlocale(0, "RUS");

	initGorner();
	initArg();
	
	test(Arg[1]);



	/* Вывод значений функций flverify для чисел float */
	printf("Вывод значений функций flverify для чисел float:\n");
	printf("FS_math:%d\n", flverify(fS1));
	printf("simple_cycle_no_gorner:%d\n", flverify(simple_cycle_no_gorner));
	printf("simple_cycle_gorner:%d\n", flverify(simple_cycle_gorner));
	printf("simple_no_cycle_gorner:%d\n", flverify(simple_no_cycle_gorner));
	printf("simple_no_cycle_no_gorner:%d\n", flverify(simple_no_cycle_no_gorner));

	printf("\nВывод результатов замера времени для float:\n");
	double t1 = SrTime(simple_cycle_no_gorner);
	double t2 = SrTime(simple_cycle_gorner);
	double t3 = SrTime(simple_no_cycle_gorner);
	double t4 = SrTime(simple_no_cycle_no_gorner);
	double t5 = SrTime(fS1);
	printf("%0.0fнс\t%0.0fнс\t%0.0fнс\t%0.0fнс\t%0.0fнс\n", t1, t2, t3, t4, t5);

	/* Вывод значений функций fixverify для чисел fix */
	printf("Вывод значений функций flverify для чисел float:\n");
	printf("FixCyclGorner:%d\n", fixverify(FixCyclGorner));
	printf("FixNoCyclGorner:%d\n", fixverify(FixNoCyclGorner));
	
	/* Вывод результатов замера времени для fix */
	printf("Вывод результатов замера времени для fix:\n");
	
	double t6 = SrTimeFIX(FixCyclGorner);
	double t7 = SrTimeFIX(TestFixNoCyclGorner);
		
	printf("%0.0fнс\t%0.0fнс\n", t6/30, t7);

	/* Табличная реализация */

	/*
	for (; szTable < 5000; szTable += 1) {
		stat(szTable);
	}
	*/

	/* Вывод результатов замера времени для табличной реализации */
	double  t8 = SrTime(acosTable);
	printf("Вывод результатов табличной реализации:\n");
	printf("%0.0fнс\n", t8);
	system("pause");

	return 0;
}
