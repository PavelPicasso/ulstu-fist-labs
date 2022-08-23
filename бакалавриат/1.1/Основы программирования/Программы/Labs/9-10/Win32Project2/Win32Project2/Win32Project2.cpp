// Win32Project2.cpp: определяет точку входа для приложения.
//

#include "stdafx.h"
#include "Win32Project2.h"
#include <stdio.h>
#include <Conio.h>
#include <windows.h>
using namespace std;
#define MAX_LOADSTRING 100

// Глобальные переменные:
HINSTANCE hInst;                                // текущий экземпляр
WCHAR szTitle[MAX_LOADSTRING];                  // Текст строки заголовка
WCHAR szWindowClass[MAX_LOADSTRING];            // имя класса главного окна
int sizeX = 36;
int sizeY = 30;
int a[100][100];
int N, M;
int lvl = 1;
int MAXlvl = 3;
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
    LoadStringW(hInstance, IDC_WIN32PROJECT2, szWindowClass, MAX_LOADSTRING);
    MyRegisterClass(hInstance);

    // Выполнить инициализацию приложения:
    if (!InitInstance (hInstance, nCmdShow))
    {
        return FALSE;
    }

    HACCEL hAccelTable = LoadAccelerators(hInstance, MAKEINTRESOURCE(IDC_WIN32PROJECT2));

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
    wcex.hIcon          = LoadIcon(hInstance, MAKEINTRESOURCE(IDI_WIN32PROJECT2));
    wcex.hCursor        = LoadCursor(nullptr, IDC_ARROW);
    wcex.hbrBackground  = (HBRUSH)(COLOR_WINDOW+1);
    wcex.lpszMenuName   = MAKEINTRESOURCEW(IDC_WIN32PROJECT2);
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

void input(){
	FILE *f;
	if (lvl == 1)
		fopen_s(&f, "C:\\te\\laber.txt", "rt");
	if (lvl == 2)
		fopen_s(&f, "C:\\te\\laber1.txt", "rt");
	if (lvl == 3)
		fopen_s(&f, "C:\\te\\laber2.txt", "rt");

	fscanf_s(f, "%d", &N);
	fscanf_s(f, "%d", &M);
	for (int i = 0; i < N; i++)
		for (int j = 0; j < M; j++) {
			fscanf_s(f, "%d", &a[i][j]);
		}
	fclose(f);
}




void moveToLeft() {
	int i, j;
	i = 0;
	while (i < N) {
		j = 1;
		while (j < M) {
			if (a[i][j] == 7) {
				if (a[i][j - 1] == 0) {
					a[i][j - 1] = 7;
					a[i][j] = 0;
				}

				if (a[i][j - 1] == 3) {
					if (MessageBox(NULL, L"You Win!", L"Game", 0) == IDOK) {
						lvl++;
						if (lvl > MAXlvl)
							exit(0);
					}
					input();
				}
				if (a[i][j - 1] == 2) {
					if (MessageBox(NULL, L"You Loser!", L"Game", 0) == IDOK) { lvl = 1; input(); }
				}
			}
			j++;
		}
		i++;
	}
}
void moveUp() {
	int i = 1;
	while (i < N) {
		int j = 0;
		while (j < M) {
			if (a[i][j] == 7) {
				if (a[i - 1][j] == 0) {
					a[i - 1][j] = 7;
					a[i][j] = 0;
				}
				if (a[i - 1][j] == 3) {
					if (MessageBox(NULL, L"You Win!", L"Game", 0) == IDOK) {
						lvl++;
						if (lvl > MAXlvl)
							exit(0);
					}
					input();
				}
				if (a[i - 1][j] == 2) {
					if (MessageBox(NULL, L"You Loser!", L"Game", 0) == IDOK) { lvl = 1; input(); }
				}
			}
			j++;
		}
		i++;
	}
}
void moveToRight() {
	int i = 0;
	while (i < N) {
		int j = M - 2;	
		while (j >= 0) {
			if (a[i][j] == 7) {
				if (a[i][j + 1] == 0) {
					a[i][j + 1] = 7;
					a[i][j] = 0;
				}
				if (a[i][j + 1] == 3) {
					if (MessageBox(NULL, L"You Win!", L"Game", 0) == IDOK) {
						lvl++;
						if (lvl > MAXlvl)
							exit(0);
					}
					input();
				}
				if (a[i][j + 1] == 2) {
					if (MessageBox(NULL, L"You Loser!", L"Game", 0) == IDOK) { lvl = 1; input(); }
				}
			}
			j--;
		}
		i++;
	}
}
void moveDown() {
	int i = N;
	while (i >= 0) {
		int j = 0;
		while (j < M) {
			if (a[i][j] == 7) {
				if (a[i + 1][j] == 0) {
					a[i + 1][j] = 7;
					a[i][j] = 0;
				}
				if (a[i + 1][j] == 3) {
					if (MessageBox(NULL, L"You Win!", L"Game", 0) == IDOK) {
						lvl++;
						if (lvl > MAXlvl)
							exit(0);
					}
					input();
				}
				if (a[i + 1][j] == 2) { 
					if (MessageBox(NULL, L"You Loser!", L"Game", 0) == IDOK) { lvl = 1; input(); }
				}
			}
			j++;
		}
		i--;
	}
}


void DrawField(HDC hdc) {
	HBRUSH hBrushEmptyCell; //создаём кисть для пустого поля
	hBrushEmptyCell = CreateSolidBrush(RGB(200, 200, 200)); // серый
	HBRUSH hBrushwin; //создаём кисть для поля с победой
	hBrushwin = CreateSolidBrush(RGB(255, 0, 0)); // красный
	HBRUSH hBrushWall; //создаём кисть для стены
	hBrushWall = CreateSolidBrush(RGB(0, 0, 0)); // черный
	HBRUSH hBrushMan; //создаём кисть для игрока
	hBrushMan = CreateSolidBrush(RGB(0, 255, 0)); // зеленый
	int i, j;
	i = 0;
	while (i < N) {
		j = 0;
		while (j < M) {
			RECT rect = { j * sizeX,i * sizeY, (j + 1) * sizeX,(i + 1) * sizeY };
			if (a[i][j] == 0) {
				FillRect(hdc, &rect, hBrushEmptyCell);
			}
			else if (a[i][j] == 2) {
				FillRect(hdc, &rect, hBrushEmptyCell);
			}
			else if (a[i][j] == 3) {
				FillRect(hdc, &rect, hBrushwin);
			}
			else if (a[i][j] == 1) {
				FillRect(hdc, &rect, hBrushWall);
			}
			else if (a[i][j] == 7) {
				FillRect(hdc, &rect, hBrushMan);
			}
			else {
				// тут никогда не должны оказаться
			}
			j++;
		}
		i++;
	}
	DeleteObject(hBrushEmptyCell);
	DeleteObject(hBrushwin);
	DeleteObject(hBrushWall);
	DeleteObject(hBrushMan);
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
//input();
LRESULT CALLBACK WndProc(HWND hWnd, UINT message, WPARAM wParam, LPARAM lParam)
{
    switch (message)
    {
	case WM_CREATE:{
			input(); 
			break;
		}
	case WM_KEYDOWN:
		switch (wParam)
		{
		case VK_DOWN:
			moveDown();
			InvalidateRect(hWnd, NULL, TRUE);
			break;
		case VK_LEFT:
			moveToLeft();
			InvalidateRect(hWnd, NULL, TRUE);
			break;
		case VK_UP:
			moveUp();
			InvalidateRect(hWnd, NULL, TRUE);
			break;
		case VK_RIGHT:
			moveToRight();
			InvalidateRect(hWnd, NULL, TRUE);
			break;
		}
		break;
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

			DrawField(hdc);


				

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
