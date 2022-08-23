mount d e:\tasm

d:\tasm mlab2

if errorlevel 1 goto exit

d:\tlink mlab2


MLAB2 < lab2.txt

:exit
pause
exit