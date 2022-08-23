#include <cstdio>
#include <iostream>
#include <chrono>

using namespace std;

const int N = 100; // max ���������� ������ � �����
const int INF = 1000000; // ����������� ����������, ��������� �� ��, ��� ����� ��������� ��� ����

int n; // ���-�� ������ � �����
int adj[N][N] = { { 0 } }; // ������� ���������
int path[N][N]; // ������� �����
int dist[N][N]; // ������� ����������

void read_graph()
{
    // ���� ����������� ��������� �������: ������� ����������� ���-�� ������ � ���-�� �����,
    // � ����� ����� � ��������� �������: <��������� �������> <�������� �������> <���������� ����� ����>
    // ���������: ������ ������� ��������� ������
    int m;

    printf("������� ���-�� <������> � ���-�� <�����>: ");
    scanf("%d%d", &n, &m);

    printf("\n������� ����� � ��������� �������: <��������� �������> <�������� �������> <���������� ����� ����>\n");
    for (int u, v, w; m--; adj[u][v] = w)
        scanf("%d%d%d", &u, &v, &w);
}

void floid()
{
    // ������������� ������ ����� � ����������
    for (int u = 0; u < n; ++u)
        for (int v = 0; v < n; ++v)
            if (adj[u][v] || u == v)
            {
                path[u][v] = v;
                dist[u][v] = adj[u][v];
            }
            else
            {
                path[u][v] = N;
                dist[u][v] = INF;
            }

    // ��� �������� ������
    for (int k = 0; k < n; ++k)
        for (int u = 0; u < n; ++u)
            if (dist[u][k] != INF)
                for (int v = 0; v < n; ++v)
                    if (dist[u][v] > dist[u][k] + dist[k][v])
                    {
                        dist[u][v] = dist[u][k] + dist[k][v];
                        path[u][v] = path[u][k];
                    }
}

void show_path(int u, int v)
{
    // ����� ���� �� ������� u � v � ���������� ����� ���� 
    if (path[u][v] == N)
    {
        printf("��� ����\n");
        return;
    }

    int x = u;

    printf("%d", x);
    while (x != v)
        printf(" %d", x = path[x][v]);
    printf(" (%d)\n", dist[u][v]);
}

int main()
{
    setlocale(LC_ALL, "Russian");
    clock_t clock_start, clock_time; // ��������� � ���������� ����� ��� clock()
    int u, v;
    read_graph();

    clock_start = clock(); // ��������� �������
    for (int k = 0; k < 100000; ++k)
        floid();
    clock_time = clock() - clock_start; // ����� �����������������
    cout << clock_time << " ���� " << endl;

    while (true) // ������������ ������� ������ ����� � ���������� ����� ��������� ���� �� ����� -1 ��� ���� �� ���� ������
    {
        printf("������� �������� � �������� �������: ");
        scanf("%d%d", &u, &v);
        if (u == -1 || v == -1)
            break;
        show_path(u, v);
    }

    return 0;
}
