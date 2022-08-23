;***************************************************************************************************
; MLAB1.ASM - учебный пример для выполнения 
; лабораторной работы N1 по машинно-ориентированному программированию
; 10.09.02: Негода В.Н.
;***************************************************************************************************
        .MODEL SMALL
        .STACK 200h
	.386
;       Используются декларации констант и макросов
        INCLUDE MLAB1.INC	
        INCLUDE MLAB1.MAC

; Декларации данных
.DATA 
f11 db "f11 = x1 !x2 x3 | x2 !x3 x4 | x3 !x4 | x1 x4 | !x2 !x3.", 0FFh
z db "Z = f11? X / 8 + Y * 8: X * 2 - Y * 8; z7 = z8; z11 &= z14; z18 |= z10;", 0FFh
SpecialForX dd ? 
SpecialForY dd ?
InX db "X: ",0FFh 
InY db "Y: ",0FFh       
f11_1 db "f11 = 1",0FFh 
f11_0 db "f11 = 0",0FFh
beforeZ11 DB "Z11 before: ",0FFh 
afterZ11 DB "Z11 after:  ",0FFh  


SLINE	DB	78 DUP (CHSEP), 0
REQ	DB	"Фимилия И.О.: ",0FFh
MINIS	DB	"МИНИСТЕРСТВО ОБРАЗОВАНИЯ РОССИЙСКОЙ ФЕДЕРАЦИИ",0
ULSTU	DB	"УЛЬЯНОВСКИЙ ГОСУДАРСТВЕННЫЙ ТЕХНИЧЕСКИЙ УНИВЕРСИТЕТ",0
DEPT	DB	"Кафедра вычислительной техники",0
MOP	DB	"Машинно-ориентированное программирование",0
LABR	DB	"Лабораторная работа N 1",0
REQ1    DB      "Замедлить(-),ускорить(+),lab1(f),выйти(ESC)? ",0FFh
TACTS   DB	"Время работы в тактах: ",0FFh
EMPTYS	DB	0
BUFLEN = 70
BUF	DB	BUFLEN
LENS	DB	?
SNAME	DB	BUFLEN DUP (0)
PAUSE	DW	0, 0 ; младшее и старшее слова задержки при выводе строки
TI	DB	LENNUM+LENNUM/2 DUP(?), 0 ; строка вывода числа тактов
                                          ; запас для разделительных "`"

;========================= Программа =========================
        .CODE
; Макрос заполнения строки LINE от позиции POS содержимым CNT объектов,
; адресуемых адресом ADR при ширине поля вывода WFLD
BEGIN	LABEL	NEAR
	; инициализация сегментного регистра
	MOV	AX,	@DATA
	MOV	DS,	AX
	; инициализация задержки
	MOV	PAUSE,	PAUSE_L
	MOV	PAUSE+2,PAUSE_H
	PUTLS	REQ	; запрос имени
	; ввод имени
	LEA	DX,	BUF
	CALL	GETS	
@@L:	; циклический процесс повторения вывода заставки
	; вывод заставки
	; ИЗМЕРЕНИЕ ВРЕМЕНИ НАЧАТЬ ЗДЕСЬ
	FIXTIME
	PUTL	EMPTYS
	PUTL	SLINE	; разделительная черта
	PUTL	EMPTYS
	PUTLSC	MINIS	; первая 
	PUTL	EMPTYS
	PUTLSC	ULSTU	;  и  
	PUTL	EMPTYS
	PUTLSC	DEPT	;   последующие 
	PUTL	EMPTYS
	PUTLSC	MOP	;    строки  
	PUTL	EMPTYS
	PUTLSC	LABR	;     заставки
	PUTL	EMPTYS
	; приветствие
	PUTLSC	SNAME   ; ФИО студента
	PUTL	EMPTYS
	; разделительная черта
	PUTL	SLINE
	; ИЗМЕРЕНИЕ ВРЕМЕНИ ЗАКОНЧИТЬ ЗДЕСЬ 
	DURAT    	; подсчет затраченного времени
	; Преобразование числа тиков в строку и вывод
	LEA	DI,	TI
	CALL	UTOA10	
	PUTL	TACTS
	PUTL	TI      ; вывод числа тактов
	; обработка команды
	PUTL	REQ1
	CALL	GETCH
	CMP	AL,	'-'    ; удлиннять задержку?
	JNE	CMINUS
	INC	PAUSE+2        ; добавить 65536 мкс
	JMP	@@L
CMINUS:	CMP	AL,	'+'    ; укорачивать задержку?
	JNE	Lab
	CMP	WORD PTR PAUSE+2, 0		
	JE	BACK
	DEC	PAUSE+2        ; убавить 65536 мкс
	
;=================================================================
Lab:	CMP	AL,	'f'
	JNE	CEXIT
	
	PUTL EMPTYS
	PUTL EMPTYS
	PUTLS f11 
    PUTL EMPTYS
	PUTLS z 
    PUTL EMPTYS
    PUTL EMPTYS  
	
	PUTLS InX
	MOV AH, 0
	MOV AH, 0
	MOV AH, 0
	MOV AH, 0
	MOV AH, 0
	MOV AH, 0
	MOV AH, 0
	MOV AH, 0
	MOV AH, 0
	MOV AH, 0
	MOV AH, 0
	MOV AH, 0
	MOV AH, 0
	MOV AH, 0
	MOV AH, 0
	MOV AH, 0
	MOV AH, 0
	MOV AH, 0
	MOV AH, 0
	MOV AH, 0
	MOV AH, 0
	MOV AH, 0
	MOV AH, 0
	MOV AH, 0
	MOV AH, 0
	MOV AH, 0
NEXTX:
	MOV AH, 01H
	INT 21H
	CMP AL, 0Dh
	JE GETOUTX
	SUB AL, 30H
	CMP AL, 0
	JE LX
	ADD BL, 1

LX:
	SHL EBX, 1
	JMP NEXTX

GETOUTX:
	SHR EBX, 1

	PUTL EMPTYS
	PUTLS InY

NEXTY:
	MOV AH, 01H
	INT 21H
	CMP AL, 0Dh
	JE GETOUTY
	SUB AL, 30H
	CMP AL, 0
	JE LY
	ADD CL, 1

LY:
	SHL ECX, 1
	JMP NEXTY

GETOUTY:
	SHR ECX, 1

f11part1:
	mov eax, 1b
	and eax, ebx
	cmp eax, 1
	jne f11part2

	mov eax, 10b
	and eax, ebx
	cmp eax, 0
	jne f11part2

	mov eax, 100b
	and eax, ebx
	cmp eax, 1
	je ifOne

f11part2:
	mov eax, 10b	
	and eax, ebx
	cmp eax, 1
	jne f11part3

	mov eax, 100b	
	and eax, ebx
	cmp eax, 0
	jne f11part3

	mov eax, 1000b	
	and eax, ebx
	cmp eax, 1
	je ifOne

f11part3:
	mov eax, 100b	
	and eax, ebx
	cmp eax, 1
	jne f11part4

	mov eax, 1000b	
	and eax, ebx
	cmp eax, 0
	je ifOne

f11part4:
	mov eax, 1b	
	and eax, ebx
	cmp eax, 1
	jne f11part5

	mov eax, 1000b	
	and eax, ebx
	cmp eax, 1
	je ifOne

f11part5:
	mov eax, 10b	
	and eax, ebx
	cmp eax, 0
	jne f11Zero

	mov eax, 100b	
	and eax, ebx
	cmp eax, 0
	je ifOne

f11Zero:
    push ECX
	PUTL EMPTYS
	PUTLS f11_0 
	PUTL EMPTYS
	pop ECX
	SHL EBX, 1
	SHL ECX, 3
	SUB EBX, ECX
	JMP out0

ifOne:
    push ECX
    PUTL EMPTYS 
	PUTLS f11_1 
	PUTL EMPTYS 
	pop ECX
	SHR EBX, 3
	SHL ECX, 3
	ADD EBX, ECX

out0:
    push ECX
	PUTL EMPTYS
	PUTLS beforeZ11
	pop ECX
	
	push eax
	push ebx
	
	 ;shl ebx, 1    
	 ;shl ebx, 1
	 ;shl ebx, 1
	 ;shl ebx, 1
	 ;shl ebx, 1
	 ;shl ebx, 1
	 ;shl ebx, 1    
	 ;shl ebx, 1
	 ;shl ebx, 1
	 ;shl ebx, 1
	 ;shl ebx, 1
	 ;shl ebx, 1
	mov cl, 12
    shl ebx, cl  
    
    mov cx, 20
    
cycle1:
    shl ebx, 1
    jc l1
    mov dl, '0'
    jmp l2
    
l1:
    mov dl, '1'
l2:
    mov ah, 02h
    int 21h
    
    dec cx
    cmp cx, 0
    jne cycle1   
	
	pop ebx
    pop eax
    
	mov eax, 10000000b	
	and eax, ebx
	cmp eax, 0
	jne K1
	btr ebx, 6
	jmp KNEXT1

K1:
	bts ebx, 6

KNEXT1:
	mov eax, 10000000000000b	
	and eax, ebx
	cmp eax, 1
	jne K2

	mov eax, 10000000000b	
	and eax, ebx
	cmp eax, 1
	jne K2
	bts ebx, 11
	jmp KNEXT2

K2:
	btr ebx, 11

KNEXT2:
	mov eax, 1000000000b	
	and eax, ebx
	cmp eax, 0
	jne K3
	mov eax, 100000000000000000b	
	and eax, ebx
	cmp eax, 0
	jne K3
	btr ebx, 18
	jmp KOUT

K3:
	bts ebx, 18

KOUT:
    push ECX
	PUTL EMPTYS
	PUTL afterZ11
	pop ECX 

    shl ebx, 12
    mov cx, 20
    
cycle2:
    shl ebx, 1
    jc l3
    mov dl, '0'
    jmp l4
    
l3:
    mov dl, '1'
l4:
    mov ah, 02h
    int 21h
    
    dec cx
    cmp cx, 0
    jne cycle2
    
    PUTL EMPTYS
    
	MOV AH, 0
	MOV AH, 0
	MOV AH, 0
	MOV AH, 0
	MOV AH, 0
	MOV AH, 0
	MOV AH, 0
	MOV AH, 0
	MOV AH, 0
	MOV AH, 0
	MOV AH, 0
	MOV AH, 0
	MOV AH, 0
	MOV AH, 0
	MOV AH, 0
	MOV AH, 0
	MOV AH, 0
	MOV AH, 0
	MOV AH, 0
	MOV AH, 0




	JE	@@E
;=================================================================
BACK:	JMP	@@L
CEXIT:	CMP	AL,	CHESC	
	JE	@@E
	TEST	AL,	AL
	JNE	BACK
	CALL	GETCH
	JMP	@@L
		; Выход из программы
@@E:	EXIT	
        EXTRN	PUTSS:  NEAR
        EXTRN	PUTC:   NEAR
	EXTRN   GETCH:  NEAR
	EXTRN   GETS:   NEAR
	EXTRN   SLEN:   NEAR
	EXTRN   UTOA10: NEAR
	EXTRN   Lab1: NEAR
	END	BEGIN
