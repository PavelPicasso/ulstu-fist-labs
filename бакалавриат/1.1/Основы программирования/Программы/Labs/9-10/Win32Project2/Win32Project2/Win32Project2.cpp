// Win32Project2.cpp: ���������� ����� ����� ��� ����������.
//

#include "stdafx.h"
#include "Win32Project2.h"
#include <stdio.h>
#include <Conio.h>
#include <windows.h>
using namespace std;
#define MAX_LOADSTRING 100

// ���������� ����������:
HINSTANCE hInst;                                // ������� ���������
WCHAR szTitle[MAX_LOADSTRING];                  // ����� ������ ���������
WCHAR szWindowClass[MAX_LOADSTRING];            // ��� ������ �������� ����
int sizeX = 36;
int sizeY = 30;
int a[100][100];
int N, M;
int lvl = 1;
int MAXlvl = 3;
// ��������� ���������� �������, ���������� � ���� ������ ����:
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

    // TODO: ���������� ��� �����.

    // ������������� ���������� �����
    LoadStringW(hInstance, IDS_APP_TITLE, szTitle, MAX_LOADSTRING);
    LoadStringW(hInstance, IDC_WIN32PROJECT2, szWindowClass, MAX_LOADSTRING);
    MyRegisterClass(hInstance);

    // ��������� ������������� ����������:
    if (!InitInstance (hInstance, nCmdShow))
    {
        return FALSE;
    }

    HACCEL hAccelTable = LoadAccelerators(hInstance, MAKEINTRESOURCE(IDC_WIN32PROJECT2));

    MSG msg;

    // ���� ��������� ���������:
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
//  �������: MyRegisterClass()
//
//  ����������: ������������ ����� ����.
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
//   �������: InitInstance(HINSTANCE, int)
//
//   ����������: ��������� ��������� ���������� � ������� ������� ����.
//
//   �����������:
//
//        � ������ ������� ���������� ���������� ����������� � ���������� ����������, � �����
//        ��������� � ��������� �� ����� ������� ���� ���������.
//
BOOL InitInstance(HINSTANCE hInstance, int nCmdShow)
{
   hInst = hInstance; // ��������� ���������� ���������� � ���������� ����������

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
	HBRUSH hBrushEmptyCell; //������ ����� ��� ������� ����
	hBrushEmptyCell = CreateSolidBrush(RGB(200, 200, 200)); // �����
	HBRUSH hBrushwin; //������ ����� ��� ���� � �������
	hBrushwin = CreateSolidBrush(RGB(255, 0, 0)); // �������
	HBRUSH hBrushWall; //������ ����� ��� �����
	hBrushWall = CreateSolidBrush(RGB(0, 0, 0)); // ������
	HBRUSH hBrushMan; //������ ����� ��� ������
	hBrushMan = CreateSolidBrush(RGB(0, 255, 0)); // �������
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
				// ��� ������� �� ������ ���������
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
//  �������: WndProc(HWND, UINT, WPARAM, LPARAM)
//
//  ����������:  ������������ ��������� � ������� ����.
//
//  WM_COMMAND � ���������� ���� ����������
//  WM_PAINT � ���������� ������� ����
//  WM_DESTROY � ��������� ��������� � ������ � ���������
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
            // ��������� ����� � ����:
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


				

            // TODO: �������� ���� ����� ��� ����������, ������������ HDC...
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

// ���������� ��������� ��� ���� "� ���������".
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
