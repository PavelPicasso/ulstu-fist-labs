#include <iostream>
#include <cstdio>
#include <vector>
#include <cmath>

using namespace std;

struct point {
	int x, y;
	point() {}
	point(int X, int Y) {
		x = X;
		y = Y;
	}
};

bool operator != (const point &a, const point &b) {
	return !(a.x == b.x && a.y == b.y);
}

double dist(const point &a, const point &b) {
	return sqrt(0.0 + (a.x - b.x) * (a.x - b.x) + (a.y - b.y) * (a.y - b.y));
}

int n;
vector<point> mas;
vector<int> convex_hull;
double P;

void input() {
	cin >> n;
	mas.resize(n);
	for (int i = 0; i<n; i++)
		scanf("%d %d", &mas[i].x, &mas[i].y);
}

int OrientTriangl2(const point &p1, const point &p2, const point &p3) {
	return p1.x * (p2.y - p3.y) + p2.x * (p3.y - p1.y) + p3.x * (p1.y - p2.y);
}

bool isInside(const point &p1, const point &p, const point &p2) {
	return (p1.x <= p.x && p.x <= p2.x &&
		p1.y <= p.y && p.y <= p2.y);
}

void ConvexHullJarvis(const vector<point> &mas, vector<int> &convex_hull) {
	// находим самую левую из самых нижних
	int base = 0;
	for (int i = 1; i<n; i++) {
		if (mas[i].y < mas[base].y)
			base = i;
		else
			if (mas[i].y == mas[base].y &&
				mas[i].x <  mas[base].x)
				base = i;
	}
	// эта точка точно входит в выпуклую оболочку
	convex_hull.push_back(base);

	int first = base;
	int cur = base;
	do {
		int next = (cur + 1) % n;
		for (int i = 0; i<n; i++) {
			int sign = OrientTriangl2(mas[cur], mas[next], mas[i]);
			// точка mas[i] находится левее прямой ( mas[cur], mas[next] )
			if (sign < 0) // обход выпуклой оболочки против часовой стрелки
				next = i;
			// точка лежит на прямой, образованной точками  mas[cur], mas[next]
			else if (sign == 0) {
				// точка mas[i] находится дальше, чем mas[next] от точки mas[cur]
				if (isInside(mas[cur], mas[next], mas[i]))
					next = i;
			}
		}
		cur = next;
		convex_hull.push_back(next);
	} while (cur != first);
}
void solve() {
	ConvexHullJarvis(mas, convex_hull);

	for (size_t i = 0; i<convex_hull.size() - 1; i++)
		P += dist(mas[convex_hull[i]], mas[convex_hull[i + 1]]);
}

void output() {
	printf("%0.1f", P);
}

int main() {

	input();
	solve();
	output();

	return 0;
}