#include <windows.h>
#include <iostream>
#include <Lmcons.h>
using namespace std;

int main() {
	HKEY rKey, dkey;
	TCHAR Reget[256], Reget1[256], Reget2[256];
	DWORD RegetPath = sizeof(Reget);
	TCHAR name[UNLEN + 1];
	DWORD size = UNLEN + 1;

	RegOpenKeyEx(HKEY_LOCAL_MACHINE, "HARDWARE\\DESCRIPTION\\System\\BIOS", NULL, KEY_QUERY_VALUE, &rKey);
	RegQueryValueEx(rKey, "BIOSVersion", NULL, NULL, (LPBYTE)&Reget, &RegetPath);
	cout << "BIOSVersion " << Reget << "\n";

	RegetPath = sizeof(Reget1);
	RegOpenKeyEx(HKEY_LOCAL_MACHINE, "SYSTEM\\CurrentControlSet\\Control\\ComputerName\\ComputerName", NULL, KEY_QUERY_VALUE, &dkey);
	RegQueryValueEx(dkey, "ComputerName", NULL, NULL, (LPBYTE)&Reget1, &RegetPath);
	cout << "ComputerName " << Reget1 << "\n";

	RegetPath = sizeof(Reget2);
	RegOpenKeyEx(HKEY_LOCAL_MACHINE, "SYSTEM\\CurrentControlSet\\Control\\SystemInformation", NULL, KEY_QUERY_VALUE, &dkey);
	RegQueryValueEx(dkey, "SystemManufacturer", NULL, NULL, (LPBYTE)&Reget2, &RegetPath);
	cout << "SystemManufacturer " << Reget2 << "\n";

	GetUserName((TCHAR*)name, &size);
	cout << "UserName " << name << "\n";

	getchar();
	return 0;
}
/*
#include <Windows.h>
#include <SetupAPI.h>
#include <iostream>
#include <fstream>
#include <conio.h>
#include <string>

#pragma comment(lib, "SetupAPI.lib")

#define MAX_DEV_LEN 128

int main()
{
setlocale(LC_ALL, "Russian");

std::ofstream file;
file.open("tests.txt");

GUID ClassGuid;

HDEVINFO info = SetupDiGetClassDevs(&ClassGuid, 0, 0, DIGCF_ALLCLASSES | DIGCF_PRESENT);

PSP_DEVINFO_DATA data = new SP_DEVINFO_DATA();
data->cbSize = sizeof(SP_DEVINFO_DATA);

PBYTE output = new BYTE[MAX_DEV_LEN];
PDWORD datatype = new DWORD;
PDWORD RequiredSize = new DWORD;
BOOL result = TRUE;
DWORD i = 0;

while (result)
{
result = SetupDiEnumDeviceInfo(info, i, data);

if (result)
{
BOOL res = SetupDiGetDeviceRegistryProperty(info, data, SPDRP_CLASS, datatype, output, MAX_DEV_LEN, RequiredSize);

file << "Iteration #" << i << std::endl;

if (!res)
{
int error = GetLastError();
file << "Error - " << error << std::endl;
}
else
{
file << output << std::endl;
}

res = SetupDiGetDeviceRegistryProperty(info, data, SPDRP_DEVICEDESC, datatype, output, MAX_DEV_LEN, RequiredSize);

if (!res)
{
int error = GetLastError();
file << "Error - " << error << std::endl;
}
else
{
file << output << std::endl;
}

res = SetupDiGetDeviceRegistryProperty(info, data, SPDRP_FRIENDLYNAME, datatype, output, MAX_DEV_LEN, RequiredSize);

if (!res)
{
int error = GetLastError();
file << "Error - " << error << std::endl;
}
else
{
file << output << std::endl;
}
}
else
{
file << "SetupDiEnumDeviceInfo error\n";
}

file << std::endl;

++i;
}

file.close();

SetupDiDestroyDeviceInfoList(info);

delete datatype;
delete RequiredSize;
delete data;
delete[] output;

return 0;
}



*/