#include <cstdlib>
#include <cstdio>
#include <algorithm>
#include <vector>
#include <cmath>

using namespace std;

#define sqr(x) ((x) * (x))

const double inf = 1e100;


struct point {
	double x, y;

	void read() {
		scanf("%lf %lf", &x, &y);
	}

	bool operator<(const point& r) const {
		if (x < r.x) {
			return 1;
		}

		if (x > r.x) {
			return 0;
		}

		return y < r.y;
	}

	point operator-(point& r) {
		point res = { x - r.x, y - r.y };
		return res;
	}

	double slope() {
		if (x == 0.0 && y == 0.0) {
			return -inf;
		}
		if (x == 0.0) {
			return inf;
		}
		return y / x;
	}

	double operator*(const point& r) {
		return x * r.y - y * r.x;
	}

	double dist_to(point& r) {
		return sqrt(sqr(x - r.x) + sqr(y - r.y));
	}
};


point O; // left-most lower point
bool BY_SLOPE(point l, point r) {
	double ls = (l - O).slope(), rs = (r - O).slope();
	if (ls < rs) {
		return 1;
	}
	if (ls > rs) {
		return 0;
	}
	return l.dist_to(O) < r.dist_to(O);
}


// pre: N >= 0, [p, p + N) - points
vector<point> convex_hull(point *p, int N) {
	//����, ���� ����� 2 ����� -> �����
	if (N <= 2) {
		return vector<point>(p, p + N);
	}

	sort(p, p + N);
	//��������� �����
	O = p[0];
	sort(p + 1, p + N, BY_SLOPE);
	vector<point> hull;
	for (int i = 0; i < N; i++) {
		if (i < 3) {
			hull.push_back(p[i]);
		} 
		else {
			int sz = hull.size();
			while (sz >= 2 && (p[i] - hull[sz - 2])*(hull[sz - 1] - hull[sz - 2]) >= 0) {
				hull.pop_back(), sz--;
			}
			hull.push_back(p[i]);
		}
	}
	return hull;
}// post: convex hull in hull, given in ccw order


vector<point> v;
int  n;
point P[21100];

int main() {
	//����������� ��� �����
	scanf("%d", &n);

	//���������� ��� �����
	for (int i = 0; i < n; i++) {
		P[i].read();
	}

	//���� �������� ��������
	v = convex_hull(P, n);

	//����� ��� ���
	for (int i = 0; i < v.size(); i++) {
		printf("(%.lf;%.lf)\n", v[i].x, v[i].y);
	}

	return 0;
}