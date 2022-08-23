// Win32Project7.cpp: определяет точку входа для приложения.
//
#include "stdafx.h"
#include "Win32Project7.h"
#include <stdio.h>
#include <stdlib.h>
#define MAX_LOADSTRING 100
#include <ctime>
#include <time.h>
#include <string>
using namespace std;

// Глобальные переменные:
HINSTANCE hInst;                                // текущий экземпляр
WCHAR szTitle[MAX_LOADSTRING];                  // Текст строки заголовка
WCHAR szWindowClass[MAX_LOADSTRING];            // имя класса главного окна                

UINT_PTR Timer1 = 1, Timer2 = 2;
HDC hdc;
bool is_rotten_food_active = false;
int rotten_lifetime = 350;

#define max_rotten  10
int r_ex[max_rotten], r_ey[max_rotten], r_lifetime[max_rotten];


char direction = 'R';

int eat = 0;
int loser = 50;
int k = 20;
int fx = 450;
int fy = 470;
int ex = 120;
int ey = 140;

int num = 14;
POINT points[100];



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
	LoadStringW(hInstance, IDC_WIN32PROJECT7, szWindowClass, MAX_LOADSTRING);
	MyRegisterClass(hInstance);

	// Выполнить инициализацию приложения:
	if (!InitInstance(hInstance, nCmdShow))
	{
		return FALSE;
	}

	HACCEL hAccelTable = LoadAccelerators(hInstance, MAKEINTRESOURCE(IDC_WIN32PROJECT7));

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

	return (int)msg.wParam;
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

	wcex.style = CS_HREDRAW | CS_VREDRAW;
	wcex.lpfnWndProc = WndProc;
	wcex.cbClsExtra = 0;
	wcex.cbWndExtra = 0;
	wcex.hInstance = hInstance;
	wcex.hIcon = LoadIcon(hInstance, MAKEINTRESOURCE(IDI_WIN32PROJECT7));
	wcex.hCursor = LoadCursor(nullptr, IDC_ARROW);
	wcex.hbrBackground = (HBRUSH)(COLOR_WINDOW + 1);
	wcex.lpszMenuName = MAKEINTRESOURCEW(IDC_WIN32PROJECT7);
	wcex.lpszClassName = szWindowClass;
	wcex.hIconSm = LoadIcon(wcex.hInstance, MAKEINTRESOURCE(IDI_SMALL));

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

	if (MessageBox(NULL, L"Do you want to start the game?", L"Game", MB_YESNO) == IDNO) {
		exit(0);
	}

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


void New() {
	ex = 40 + (rand() % (1280 / 20 - 40 / 20 + 1)) * 20;
	ey = 40 + (rand() % 30) * 20;
}

void Newf() {
	fx = 40 + (rand() % (1280 / 20 - 40 / 20 + 1)) * 20;
	fy = 40 + (rand() % 30) * 20;
}


void DrawEat(HDC hdc, int ex, int ey, int k) {
	HBRUSH Hbrush;
	Hbrush = CreateSolidBrush(RGB(0, 187, 0));
	SelectObject(hdc, Hbrush);
	Ellipse(hdc, ex, ey, ex + k, ey + k);
	DeleteObject(Hbrush);
}

void DrawEatER(HDC hdc, int fx, int fy, int k) {
	HBRUSH Hbrush1;
	Hbrush1 = CreateSolidBrush(RGB(255, 0, 0));
	SelectObject(hdc, Hbrush1);
	Ellipse(hdc, fx, fy, fx + k, fy + k);
	DeleteObject(Hbrush1);
}


void DrawSnake(HDC hdc) {
	HBRUSH Hbrush;
	Hbrush = CreateSolidBrush(RGB(223, 112, 0));
	SelectObject(hdc, Hbrush);
	for (int i = 0; i < num; i++) {
		Rectangle(hdc, points[i].x, points[i].y, points[i].x + 20, points[i].y + 20);
	}
	DeleteObject(Hbrush);
}


void borde(HDC hdc) {
	HPEN hPen = CreatePen(PS_SOLID, 4, RGB(0, 0, 0));
	SelectObject(hdc, hPen);
	MoveToEx(hdc, 10, 30, NULL);
	LineTo(hdc, 1355, 30);
	LineTo(hdc, 1355, 710);
	LineTo(hdc, 10, 710);
	LineTo(hdc, 10, 30);
	DeleteObject(hPen);
}

void Tick() {

	for (int i = num - 1; i >= 1; i--) {
		points[i].x = points[i - 1].x;
		points[i].y = points[i - 1].y;
	}

	if (direction == 'D') points[0].y += 20;
	if (direction == 'L') points[0].x -= 20;
	if (direction == 'R') points[0].x += 20;
	if (direction == 'U') points[0].y -= 20;

	if ((abs(points[0].x - ex) <= 10) && (abs(points[0].y - ey) <= 10)) { eat += 250; num++; New(); }
	for (int i = 0; i < max_rotten; i++) {
		if ((abs(points[0].x - r_ex[i]) <= 10) && (abs(points[0].y - r_ey[i]) <= 10)) {
			eat -= 475;
			loser--;
			if (num > 4) {
				num -= 3;
			}
			r_lifetime[i] = 0;
		}
	}

	if (points[0].x > 1355) direction = 'L';  if (points[0].x < 10) direction = 'R';
	if (points[0].y > 710) direction = 'U';  if (points[0].y < 30) direction = 'D';


	for (int i = 1; i < num; i++)
		if (points[0].x == points[i].x && points[0].y == points[i].y) { num = i; --loser; eat -= 60; }
	for (int i = 0; i < max_rotten; i++) {
		if (r_lifetime[i] > 0) {
			r_lifetime[i]--;
		}
	}
}

void NewRotten() {
	Newf();
	for (int i = 0; i < max_rotten; i++) {
		if (r_lifetime[i] == 0) {
			r_ex[i] = fx;
			r_ey[i] = fy;
			r_lifetime[i] = 250;
			break;
		}
	}
}

/*

ФУНКЦИЯ: WndProc(HWND, UINT, WPARAM, LPARAM)

НАЗНАЧЕНИЕ:  обрабатывает сообщения в главном окне.

WM_COMMAND — обработать меню приложения
WM_PAINT — отрисовать главное окно
WM_DESTROY — отправить сообщение о выходе и вернуться

*/
LRESULT CALLBACK WndProc(HWND hWnd, UINT message, WPARAM wParam, LPARAM lParam)
{

	switch (message)
	{

	case WM_CREATE:
		srand(time(0));
		points[0].x = 80;
		points[0].y = 60;
		SetTimer(hWnd, Timer1, 40, 0);
		SetTimer(hWnd, Timer2, 1000 * 10, 0);
		break;

	case WM_TIMER:
		if (wParam == Timer1) {
			switch (direction)
			{
			case 'R':
			case 'D':
			case 'L':
			case 'U':
				Tick();
				break;
			}
			InvalidateRect(hWnd, NULL, TRUE);
			break;
		}
		if (wParam == Timer2) {
			for (int i = 0; i < max_rotten; i++) {
				NewRotten();
				break;
			}
		}
	case WM_KEYDOWN:
		switch (wParam)
		{
		case VK_RIGHT:
			if (direction != 'L')
				direction = 'R';
			break;
		case VK_DOWN:
			if (direction != 'U')
				direction = 'D';
			break;
		case VK_LEFT:
			if (direction != 'R')
				direction = 'L';
			break;
		case VK_UP:
			if (direction != 'D')
				direction = 'U';
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


		HFONT hFont = CreateFont(20, 0, 0, 0, 0, 0, 0, 0, DEFAULT_CHARSET, 0, 0, 0, 0, L"System");
		SetTextColor(hdc, RGB(0, 0, 128));
		SelectObject(hdc, hFont);
		TCHAR string1[] = _T("Счёт:");
		TextOut(hdc, 10, 10, string1, _tcslen(string1));
		TCHAR string2[] = _T("Жизнь:");
		TextOut(hdc, 140, 10, string2, _tcslen(string2));
		TCHAR string3[] = _T("Длинна:");
		TextOut(hdc, 300, 10, string3, _tcslen(string3));

		char Eat[50];
		TCHAR tsEat[50];
		sprintf(Eat, "%d", eat);
		OemToChar(Eat, tsEat);
		TextOut(hdc, 50, 10, tsEat, _tcslen(tsEat));

		char HP[50];
		TCHAR tsHP[50];
		sprintf(HP, "%d", loser);
		OemToChar(HP, tsHP);
		TextOut(hdc, 195, 10, tsHP, _tcslen(tsHP));

		char length[50];
		TCHAR tslength[50];
		sprintf(length, "%d", num);
		OemToChar(length, tslength);
		TextOut(hdc, 355, 10, tslength, _tcslen(tslength));



		DrawSnake(hdc);
		DrawEat(hdc, ex, ey, k);

		for (int i = 0; i < max_rotten; i++) {
			if (r_lifetime[i] > 0) {
				DrawEatER(hdc, r_ex[i], r_ey[i], k);
			}
		}

		borde(hdc);

		if (loser == 0) {
			loser = 20;
			string s = "record = " + to_string(eat) + "\nYou Lose!";
			wchar_t* wString = new wchar_t[4096];
			MultiByteToWideChar(CP_ACP, 0, s.c_str(), -1, wString, 4096);
			MessageBox(NULL, wString, L"Game", 0);
			exit(0);
		}


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
