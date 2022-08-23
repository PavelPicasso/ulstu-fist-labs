// Win32Project4.cpp: ���������� ����� ����� ��� ����������.
//

#include "stdafx.h"
#include "Win32Project4.h"

#define MAX_LOADSTRING 100

// ���������� ����������:
HINSTANCE hInst;                                // ������� ���������
WCHAR szTitle[MAX_LOADSTRING];                  // ����� ������ ���������
WCHAR szWindowClass[MAX_LOADSTRING];            // ��� ������ �������� ����

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
    LoadStringW(hInstance, IDC_WIN32PROJECT4, szWindowClass, MAX_LOADSTRING);
    MyRegisterClass(hInstance);

    // ��������� ������������� ����������:
    if (!InitInstance (hInstance, nCmdShow))
    {
        return FALSE;
    }

    HACCEL hAccelTable = LoadAccelerators(hInstance, MAKEINTRESOURCE(IDC_WIN32PROJECT4));

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
    wcex.hIcon          = LoadIcon(hInstance, MAKEINTRESOURCE(IDI_WIN32PROJECT4));
    wcex.hCursor        = LoadCursor(nullptr, IDC_ARROW);
    wcex.hbrBackground  = (HBRUSH)(COLOR_WINDOW+1);
    wcex.lpszMenuName   = MAKEINTRESOURCEW(IDC_WIN32PROJECT4);
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
//������ �������� ������������




//
//  �������: WndProc(HWND, UINT, WPARAM, LPARAM)
//
//  ����������:  ������������ ��������� � ������� ����.
//
//  WM_COMMAND � ���������� ���� ����������
//  WM_PAINT � ���������� ������� ����
//  WM_DESTROY � ��������� ��������� � ������ � ���������
//
//
LRESULT CALLBACK WndProc(HWND hWnd, UINT message, WPARAM wParam, LPARAM lParam)
{
    switch (message)
    {
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
			HBRUSH hOldBrush;
			POINT points[3];
			HPEN hPen; //����������� �����
			hPen = CreatePen(PS_SOLID, 30, RGB(35, 177, 77));

			hOldBrush = CreateSolidBrush(RGB(0, 0, 0));
			SelectObject(hdc, hOldBrush);

			//�������� ������� ������ ����
			Ellipse(hdc, 300, 200, 800, 700);

			//������ ������� ������������
			SelectObject(hdc, hPen); //������ �������� �������

			//������ �����������
			MoveToEx(hdc, 225, 715, NULL);
			  LineTo(hdc, 420, 410);
			 MoveToEx(hdc, 225, 715, NULL);
			  LineTo(hdc, 875, 715);
			  LineTo(hdc, 680, 410);

			//�������� �����������
			MoveToEx(hdc, 200, 625, NULL);
			 LineTo(hdc, 550, 100);
			 LineTo(hdc,900,625);
			 LineTo(hdc,200,625);

			hPen = CreatePen(PS_SOLID, 1, RGB(0, 0, 0));
			SelectObject(hdc, hPen);

			hOldBrush = CreateSolidBrush(RGB(255, 255, 255));
			SelectObject(hdc, hOldBrush);
			//����� ���� ������ �������
			Ellipse(hdc, 400, 300, 700, 600);

			hOldBrush = CreateSolidBrush(RGB(0, 0, 0));
			SelectObject(hdc, hOldBrush);
			//���� ������� ������
			Ellipse(hdc, 500, 400, 600, 500);
			//����-������ ������
			Ellipse(hdc, 500, 225, 600, 325);

			//������ ����� ������
			hOldBrush = CreateSolidBrush(RGB(255, 255, 255));
			SelectObject(hdc, hOldBrush);
			//�����
			Ellipse(hdc, 525, 425, 575, 475);
			//������ ���� ������
			Ellipse(hdc, 510, 235, 590, 315);
			//������ ���� ������
			Ellipse(hdc, 520, 167, 580, 227);

			// ������ ��������� ������ ������ �� 2 ����� 
			hOldBrush = CreateSolidBrush(RGB(0, 0, 0));
			SelectObject(hdc, hOldBrush);
			//����� ������� � �����
			Ellipse(hdc, 535, 182, 565, 212);
			//�������
			Ellipse(hdc, 525, 330, 575, 380);
			//������
			Ellipse(hdc, 445, 485, 495, 535);
			//�����
			Ellipse(hdc, 605, 485, 655, 535);

			//����������� � ������ �������� �����(������)
			points[0].x = 550;
			points[0].y = 440;
			points[1].x = 560;
			points[1].y = 457;
			points[2].x = 540;
			points[2].y = 457;
			SelectObject(hdc, CreateSolidBrush(RGB(0, 0, 0)));
			Polygon(hdc, points, 3);

			//����-1
			hPen = CreatePen(PS_SOLID, 3, RGB(0, 0, 0));
			SelectObject(hdc, hPen);
			MoveToEx(hdc, 21, 17, NULL);
			 LineTo(hdc, 26, 10);
			 LineTo(hdc, 40, 30);
			 LineTo(hdc, 10, 30);
			 LineTo(hdc, 18, 21);
		     LineTo(hdc, 28, 21);

			//����-2
		    MoveToEx(hdc, 23, 43, NULL);
			 LineTo(hdc, 10, 63);
			 LineTo(hdc, 38, 63);
		     LineTo(hdc, 27, 47);

			//����-3
		    MoveToEx(hdc, 40, 76,NULL);
			 LineTo(hdc, 10, 76);
		     LineTo(hdc, 25, 100);
		     LineTo(hdc, 36, 82);

			//����-4
		    MoveToEx(hdc, 40, 109, NULL);
		     LineTo(hdc, 10, 109);
		     LineTo(hdc, 25, 133);
		     LineTo(hdc, 32, 122);
		    MoveToEx(hdc, 40, 109, NULL);
			 LineTo(hdc, 35, 118);
		     LineTo(hdc, 25, 118);

		     //����-5
		    MoveToEx(hdc, 70, 142, NULL);
			 LineTo(hdc, 40, 142);
			 LineTo(hdc, 70, 166);
			 LineTo(hdc, 40, 166);
			MoveToEx(hdc, 70, 142, NULL);
			 LineTo(hdc, 61, 151);
			MoveToEx(hdc, 40, 166, NULL);
			 LineTo(hdc, 49, 157);

			//����-6
		    MoveToEx(hdc, 70, 189, NULL);
			 LineTo(hdc, 55, 179);
			 LineTo(hdc, 40, 193);
			 LineTo(hdc, 70, 193);
			 LineTo(hdc, 59, 209);
			 LineTo(hdc, 43, 199);

			//����-7
			MoveToEx(hdc, 55, 219, NULL);
			 LineTo(hdc, 55, 243);
			 LineTo(hdc, 70, 243);
			 LineTo(hdc, 40, 243);
			MoveToEx(hdc, 55, 228, NULL);
			 LineTo(hdc, 65, 228);
			 LineTo(hdc, 45, 228);

			//����-8
			MoveToEx(hdc, 55, 253, NULL);
			 LineTo(hdc, 55, 279);
			MoveToEx(hdc, 55, 261, NULL);
			 LineTo(hdc, 67, 261);
			 LineTo(hdc, 43, 261);
			 Arc(hdc, 25, 249, 55, 273, 40, 269, 55, 261);
			 Arc(hdc, 55, 249, 85, 273, 55, 261, 70, 269);

			 /*
			//���
			 hPen = CreatePen(PS_SOLID, 7, RGB(0, 0, 0));
			 SelectObject(hdc, hPen);
			//P
			 MoveToEx(hdc, 1005, 100, NULL);
			  LineTo(hdc, 1015, 53);
			  Arc(hdc,950,50,1050,74,1010,74,1014,50);

			//A
			 MoveToEx(hdc, 1030, 130, NULL);
			  LineTo(hdc, 1040, 105);
			  LineTo(hdc, 1060, 105);
			  LineTo(hdc, 1060, 130);
			 MoveToEx(hdc, 1040, 105, NULL);
			  LineTo(hdc, 1054, 80);
			  LineTo(hdc, 1060, 105);

		    //V
			 MoveToEx(hdc, 1070, 135, NULL);
			  LineTo(hdc, 1074, 183);
			  LineTo(hdc, 1100, 133);

		    //E
			 MoveToEx(hdc, 1100, 183, NULL);
			  LineTo(hdc,1095 ,233);
			  LineTo(hdc, 1125, 233);
			 MoveToEx(hdc, 1095, 208,NULL);
			  LineTo(hdc, 1125, 208);
			 MoveToEx(hdc, 1100, 183, NULL);
			  LineTo(hdc, 1125, 183);

			//L
			 MoveToEx(hdc, 1135, 223, NULL);
			  LineTo(hdc, 1130, 270);
			  LineTo(hdc, 1150, 270);
			  */

			//��������� �������� ���/����� ���������� ������ �� ����������� ������

			 hPen = CreatePen(PS_SOLID, 3, RGB(0, 0, 0));
			 SelectObject(hdc, hPen);

			 

			    
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
