@echo off

mount d e:\tasm
mkdir build

d:\tasm/l mlab1.asm build
if ERRORLEVEL 1 goto err1

d:\tasm/l mlab1l.asm build
if ERRORLEVEL 1 goto err2

d:\tlink build\mlab1.obj build\mlab1l.obj
if ERRORLEVEL 1 goto err3

echo Something is ok
build\mlab1
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