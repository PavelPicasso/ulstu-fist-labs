// 9.cpp: определяет точку входа для приложения.
//

#include "stdafx.h"
#include "9.h"
#include <stdio.h>
#include <Conio.h>
#include <windows.h>
#define MAX_LOADSTRING 100

// Глобальные переменные:
HINSTANCE hInst;                                // текущий экземпляр
WCHAR szTitle[MAX_LOADSTRING];                  // Текст строки заголовка
WCHAR szWindowClass[MAX_LOADSTRING];            // имя класса главного окна
int a[100][100];
int min, n ,n1, i ,j;
int max;
int imin, jmin, k = 0;
// Отправить объявления функций, включенных в этот модуль кода:
ATOM                MyRegisterClass(HINSTANCE hInstance);
BOOL                InitInstance(HINSTANCE, int);
LRESULT CALLBACK    WndProc(HWND, UINT, WPARAM, LPARAM);
INT_PTR CALLBACK    About(HWND, UINT, WPARAM, LPARAM);

int APIENTRY wWinMain(_In_ HINSTANCE hInstance,
                     _In_opt_ HINSTANCE hPrevInstance,
                     _In_ LPWSTR    lpCmdLine,
                     _In_ int       nCmdShow)
{
    UNREFERENCED_PARAMETER(hPrevInstance);
    UNREFERENCED_PARAMETER(lpCmdLine);

    // TODO: разместите код здесь.

    // Инициализация глобальных строк
    LoadStringW(hInstance, IDS_APP_TITLE, szTitle, MAX_LOADSTRING);
    LoadStringW(hInstance, IDC_MY9, szWindowClass, MAX_LOADSTRING);
    MyRegisterClass(hInstance);

    // Выполнить инициализацию приложения:
    if (!InitInstance (hInstance, nCmdShow))
    {
        return FALSE;
    }

    HACCEL hAccelTable = LoadAccelerators(hInstance, MAKEINTRESOURCE(IDC_MY9));

    MSG msg;

    // Цикл основного сообщения:
    while (GetMessage(&msg, nullptr, 0, 0))
    {
        if (!TranslateAccelerator(msg.hwnd, hAccelTable, &msg))
        {
            TranslateMessage(&msg);
            DispatchMessage(&msg);
        }
    }

    return (int) msg.wParam;
}



//
//  ФУНКЦИЯ: MyRegisterClass()
//
//  НАЗНАЧЕНИЕ: регистрирует класс окна.
//
ATOM MyRegisterClass(HINSTANCE hInstance)
{
    WNDCLASSEXW wcex;

    wcex.cbSize = sizeof(WNDCLASSEX);

    wcex.style          = CS_HREDRAW | CS_VREDRAW;
    wcex.lpfnWndProc    = WndProc;
    wcex.cbClsExtra     = 0;
    wcex.cbWndExtra     = 0;
    wcex.hInstance      = hInstance;
    wcex.hIcon          = LoadIcon(hInstance, MAKEINTRESOURCE(IDI_MY9));
    wcex.hCursor        = LoadCursor(nullptr, IDC_ARROW);
    wcex.hbrBackground  = (HBRUSH)(COLOR_WINDOW+1);
    wcex.lpszMenuName   = MAKEINTRESOURCEW(IDC_MY9);
    wcex.lpszClassName  = szWindowClass;
    wcex.hIconSm        = LoadIcon(wcex.hInstance, MAKEINTRESOURCE(IDI_SMALL));

    return RegisterClassExW(&wcex);
}

//
//   ФУНКЦИЯ: InitInstance(HINSTANCE, int)
//
//   НАЗНАЧЕНИЕ: сохраняет обработку экземпляра и создает главное окно.
//
//   КОММЕНТАРИИ:
//
//        В данной функции дескриптор экземпляра сохраняется в глобальной переменной, а также
//        создается и выводится на экран главное окно программы.
//
BOOL InitInstance(HINSTANCE hInstance, int nCmdShow)
{
   hInst = hInstance; // Сохранить дескриптор экземпляра в глобальной переменной

   HWND hWnd = CreateWindowW(szWindowClass, szTitle, WS_OVERLAPPEDWINDOW,
      CW_USEDEFAULT, 0, CW_USEDEFAULT, 0, nullptr, nullptr, hInstance, nullptr);

   if (!hWnd)
   {
      return FALSE;
   }

   ShowWindow(hWnd, nCmdShow);
   UpdateWindow(hWnd);

   return TRUE;
}
void input()
{
	FILE *f;
	fopen_s(&f, "C:\\te\\text.txt", "rt");
	fscanf_s(f, "%d", &n);
	fscanf_s(f, "%d", &n1);
	for (i = 0; i < n; i++)
		for (j = 0; j < n1; j++){
			fscanf_s(f, "%d", &a[i][j]);
		}
	fclose(f);
}

void search()
{
	int min = a[0][0];
	max = a[0][0];
	imin = 0;
	jmin = 0;
	for (i = 0; i<n; i++)
		for (j = 0; j < n1; j++){
			if (min > a[i][j]){
				min = a[i][j];
			}
			if (max < a[i][j]) {
				max = a[i][j];
			}
		}
	for (i = 0; i<n; i++)
		for (j = 0; j < n1; j++){
			if (a[i][j] == min){
				k++;
			}
		}
	for (int k1 = 0; k1<k; k1++)
		for (i = 0; i<n; i++)
			for (j = 0; j < n1; j++){
				if (a[i][j] == min){
					imin = i;
					jmin = j;
					n--;
					n1--;
					for (i = imin; i < n; i++)
						for (j = 0; j<n1 + 1; j++)
							a[i][j] = a[i + 1][j];
					for (j = jmin; j < n1; j++)
						for (i = 0; i<n + 1; i++)
							a[i][j] = a[i][j + 1];
				}
			}
}

void output(HDC hdc, HBRUSH Hbrush, HBRUSH Hbrush1, HBRUSH Hbrush2, HBRUSH Hbrush3)
{
	FILE *fA;
	fopen_s(&fA, "C:\\te\\t.txt", "wt");
	for (i = 0; i < n; i++, fprintf(fA, "\n"))
		for (j = 0; j < n1; j++){
			if (a[i][j] < 0){
				fprintf(fA, "%d ", a[i][j]);
			}
			else{
				fprintf(fA, " %d ", a[i][j]);
			}
		}
	for (int i = 0; i < n; i++)
		for (int j = 0; j < n1; j++) {
			if (a[i][j] == max) {
				SelectObject(hdc, Hbrush3);
				Rectangle(hdc, (j * 40), (i * 40), 40 + (j * 40), 40 + (i * 40));
				continue;
			}
			if (a[i][j] % 2 == 0) {
				SelectObject(hdc, Hbrush);
				Rectangle(hdc, (j * 40), (i * 40), 40 + (j * 40), 40 + (i * 40));
			}
			else {
				SelectObject(hdc, Hbrush1);
				Rectangle(hdc, (j * 40), (i * 40), 40 + (j * 40), 40 + (i * 40));
			}
		}
	fclose(fA);
}


//
//  ФУНКЦИЯ: WndProc(HWND, UINT, WPARAM, LPARAM)
//
//  НАЗНАЧЕНИЕ:  обрабатывает сообщения в главном окне.
//
//  WM_COMMAND — обработать меню приложения
//  WM_PAINT — отрисовать главное окно
//  WM_DESTROY — отправить сообщение о выходе и вернуться
//
//
LRESULT CALLBACK WndProc(HWND hWnd, UINT message, WPARAM wParam, LPARAM lParam)
{
    switch (message)
    {
    case WM_COMMAND:
        {
            int wmId = LOWORD(wParam);
            // Разобрать выбор в меню:
            switch (wmId)
            {
            case IDM_ABOUT:
                DialogBox(hInst, MAKEINTRESOURCE(IDD_ABOUTBOX), hWnd, About);
                break;
            case IDM_EXIT:
                DestroyWindow(hWnd);
                break;
            default:
                return DefWindowProc(hWnd, message, wParam, lParam);
            }
        }
        break;
    case WM_PAINT:
        {
            PAINTSTRUCT ps;
            HDC hdc = BeginPaint(hWnd, &ps);

			HBRUSH Hbrush, Hbrush1, Hbrush2, Hbrush3;
			Hbrush = CreateSolidBrush(RGB(223, 112, 0));
			Hbrush1 = CreateSolidBrush(RGB(0, 0, 0));
			Hbrush2 = CreateSolidBrush(RGB(255, 0, 0));
			Hbrush3 = CreateSolidBrush(RGB(0, 255, 0));

			input();
			search();
			output(hdc,Hbrush, Hbrush1, Hbrush2, Hbrush3);


            // TODO: Добавьте сюда любой код прорисовки, использующий HDC...
            EndPaint(hWnd, &ps);
        }
        break;
    case WM_DESTROY:
        PostQuitMessage(0);
        break;
    default:
        return DefWindowProc(hWnd, message, wParam, lParam);
    }
    return 0;
}

// Обработчик сообщений для окна "О программе".
INT_PTR CALLBACK About(HWND hDlg, UINT message, WPARAM wParam, LPARAM lParam)
{
    UNREFERENCED_PARAMETER(lParam);
    switch (message)
    {
    case WM_INITDIALOG:
        return (INT_PTR)TRUE;

    case WM_COMMAND:
        if (LOWORD(wParam) == IDOK || LOWORD(wParam) == IDCANCEL)
        {
            EndDialog(hDlg, LOWORD(wParam));
            return (INT_PTR)TRUE;
        }
        break;
    }
    return (INT_PTR)FALSE;
}
