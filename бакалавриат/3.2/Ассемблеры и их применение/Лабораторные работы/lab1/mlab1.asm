;***************************************************************************************************
; MLAB1.ASM - �祡�� �ਬ�� ��� �믮������ 
; ������୮� ࠡ��� N1 �� ��設��-�ਥ��஢������ �ணࠬ��஢����
; 10.09.02: ������ �.�.
;***************************************************************************************************
        .MODEL SMALL
        .STACK 200h
	.386
;       �ᯮ������� ������樨 ����⠭� � ����ᮢ
        INCLUDE MLAB1.INC	
        INCLUDE MLAB1.MAC

; ������樨 ������
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
REQ	DB	"������� �.�.: ",0FFh
MINIS	DB	"������������ ����������� ���������� ���������",0
ULSTU	DB	"����������� ��������������� ����������� �����������",0
DEPT	DB	"��䥤� ���᫨⥫쭮� �孨��",0
MOP	DB	"��設��-�ਥ��஢����� �ணࠬ��஢����",0
LABR	DB	"������ୠ� ࠡ�� N 1",0
REQ1    DB      "���������(-),�᪮���(+),lab1(f),���(ESC)? ",0FFh
TACTS   DB	"�६� ࠡ��� � ⠪��: ",0FFh
EMPTYS	DB	0
BUFLEN = 70
BUF	DB	BUFLEN
LENS	DB	?
SNAME	DB	BUFLEN DUP (0)
PAUSE	DW	0, 0 ; ����襥 � ���襥 ᫮�� ����প� �� �뢮�� ��ப�
TI	DB	LENNUM+LENNUM/2 DUP(?), 0 ; ��ப� �뢮�� �᫠ ⠪⮢
                                          ; ����� ��� ࠧ����⥫��� "`"

;========================= �ணࠬ�� =========================
        .CODE
; ����� ���������� ��ப� LINE �� ����樨 POS ᮤ�ন�� CNT ��ꥪ⮢,
; ����㥬�� ���ᮬ ADR �� �ਭ� ���� �뢮�� WFLD
BEGIN	LABEL	NEAR
	; ���樠������ ᥣ���⭮�� ॣ����
	MOV	AX,	@DATA
	MOV	DS,	AX
	; ���樠������ ����প�
	MOV	PAUSE,	PAUSE_L
	MOV	PAUSE+2,PAUSE_H
	PUTLS	REQ	; ����� �����
	; ���� �����
	LEA	DX,	BUF
	CALL	GETS	
@@L:	; 横���᪨� ����� ����७�� �뢮�� ���⠢��
	; �뢮� ���⠢��
	; ��������� ������� ������ �����
	FIXTIME
	PUTL	EMPTYS
	PUTL	SLINE	; ࠧ����⥫쭠� ���
	PUTL	EMPTYS
	PUTLSC	MINIS	; ��ࢠ� 
	PUTL	EMPTYS
	PUTLSC	ULSTU	;  �  
	PUTL	EMPTYS
	PUTLSC	DEPT	;   ��᫥���騥 
	PUTL	EMPTYS
	PUTLSC	MOP	;    ��ப�  
	PUTL	EMPTYS
	PUTLSC	LABR	;     ���⠢��
	PUTL	EMPTYS
	; �ਢ���⢨�
	PUTLSC	SNAME   ; ��� ��㤥��
	PUTL	EMPTYS
	; ࠧ����⥫쭠� ���
	PUTL	SLINE
	; ��������� ������� ��������� ����� 
	DURAT    	; ������ ����祭���� �६���
	; �८�ࠧ������ �᫠ ⨪�� � ��ப� � �뢮�
	LEA	DI,	TI
	CALL	UTOA10	
	PUTL	TACTS
	PUTL	TI      ; �뢮� �᫠ ⠪⮢
	; ��ࠡ�⪠ �������
	PUTL	REQ1
	CALL	GETCH
	CMP	AL,	'-'    ; 㤫������ ����প�?
	JNE	CMINUS
	INC	PAUSE+2        ; �������� 65536 ���
	JMP	@@L
CMINUS:	CMP	AL,	'+'    ; 㪮�稢��� ����প�?
	JNE	Lab
	CMP	WORD PTR PAUSE+2, 0		
	JE	BACK
	DEC	PAUSE+2        ; 㡠���� 65536 ���
	
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
		; ��室 �� �ணࠬ��
@@E:	EXIT	
        EXTRN	PUTSS:  NEAR
        EXTRN	PUTC:   NEAR
	EXTRN   GETCH:  NEAR
	EXTRN   GETS:   NEAR
	EXTRN   SLEN:   NEAR
	EXTRN   UTOA10: NEAR
	EXTRN   Lab1: NEAR
	END	BEGIN
