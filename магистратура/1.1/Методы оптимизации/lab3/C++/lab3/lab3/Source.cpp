#include <cstdio>
#include <iostream>
#include <chrono>

using namespace std;

const int N = 100; // max количество вершин в графе
const int INF = 1000000; // бесконечное расстояние, указывает на то, что между вершинами нет пути

int n; // кол-во вершин в графе
int adj[N][N] = { { 0 } }; // матрица смежности
int path[N][N]; // матрица путей
int dist[N][N]; // матрица расстояний

void read_graph()
{
    // граф считывается следующим образом: сначала считывается кол-во вершин и кол-во ребер,
    // а затем ребра в следующем формате: <начальная вершина> <конечная вершина> <расстояние между ними>
    // замечание: данная функция считывает орграф
    int m;

    printf("Введите кол-во <вершин> и кол-во <ребер>: ");
    scanf("%d%d", &n, &m);

    printf("\nВведите ребра в следующем формате: <начальная вершина> <конечная вершина> <расстояние между ними>\n");
    for (int u, v, w; m--; adj[u][v] = w)
        scanf("%d%d%d", &u, &v, &w);
}

void floid()
{
    // инициализация матриц путей и расстояний
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

    // сам алгоритм флойда
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
    // вывод пути из вершины u в v и расстояния между ними 
    if (path[u][v] == N)
    {
        printf("Нет пути\n");
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
    clock_t clock_start, clock_time; // стартовое и измеренное время для clock()
    int u, v;
    read_graph();

    clock_start = clock(); // стартовая засечка
    for (int k = 0; k < 100000; ++k)
        floid();
    clock_time = clock() - clock_start; // замер продолжительности
    cout << clock_time << " мсек " << endl;

    while (true) // обрабатываем запросы вывода путей и расстояний между вершинами пока не ввели -1 для одно из двух вершин
    {
        printf("Введите исходную и конечную вершину: ");
        scanf("%d%d", &u, &v);
        if (u == -1 || v == -1)
            break;
        show_path(u, v);
    }

    return 0;
}
