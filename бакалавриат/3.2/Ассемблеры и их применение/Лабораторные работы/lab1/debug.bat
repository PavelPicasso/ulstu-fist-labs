@echo off

mount d e:\tasm
mkdir debug

d:\tasm/zi/la mlab1.asm debug
if ERRORLEVEL 1 goto err1

d:\tasm/zi/la mlab1l.asm debug
if ERRORLEVEL 1 goto err2

d:\tlink/m/v debug\mlab1.obj debug\mlab1l.obj
if ERRORLEVEL 1 goto err3

echo Something is ok
pause
exit

:err1
echo there is error in mlab1
pause
exit

:err2
echo there is error in mlab1l
pause
exit

:err3
echo there is error in tlink
pause
exit