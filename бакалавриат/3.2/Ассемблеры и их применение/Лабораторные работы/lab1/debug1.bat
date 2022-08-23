mount d e:\tasm

d:\tasm mlab1 
d:\tasm mlab1l 

if errorlevel 1 goto exit

d:\tlink mlab1 + mlab1l


d:\td MLAB1.exe

:exit
pause
exit