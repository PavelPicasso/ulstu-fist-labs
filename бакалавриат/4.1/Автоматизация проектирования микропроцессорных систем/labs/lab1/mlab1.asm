.MODEL SMALL
.STACK 200h

.386

; ������樨 ������
.DATA 
OutPutText db 13, 10, "Testing...$"
OutPutError db 13, 10, "�ந��諠 �訡��...$"
OutPutSup db 13, 10, "�㭪�� �� �����ন������...$"
OutPutWork db 13, 10, "Working...$"
OutPut01 db 13, 10, "������ VGA � 梥⭮� ��������� ��ᯫ��$"
NL db 13,10,'$'
Video_Info db 512 dup (?)
Total_Memory dd ?
CUR_Y db ?
CUR_X db ?
GREEN db ?
BLUE db ?
RED db ?
MB db "MB$"
GREEN_INTENSITY db "����饭����� �������� 梥�: $" 
BLUE_INTENSITY db "����饭����� ᨭ��� 梥�: $" 
RED_INTENSITY db "����饭����� ��᭮�� 梥�: $"
;========================= �ணࠬ�� =========================
.CODE

BEGIN	LABEL	NEAR
    ; ���樠������ ᥣ���⭮�� ॣ����
	mov	AX,	@DATA
	mov	DS,	AX
	
	; ����㭪�� 00h(0bh) �।�����祭� ��� ��⠭���� 梥� ��ࠬ���饩 �࠭ ࠬ�� � ⥪�⮢�� ०��� � 梥� 䮭� � ����᪮�
	;mov ah, 0 ; ��⠭���� ���� ०��
	;mov al, 04h ; ����᪨� ०�� 04h, 320 x 200
	;int 10h 
	;mov ah,0bh
	;mov bh, 00h ; ��� ���䨣��樨 ��� ��⠭��� 梥�
	;mov bl, 14 ; ��⠭�������� ����� 梥� 䮭� [�� 0 �� 15]
	;int 10h
	
	; �㭪�� 0ch �������� �뢥�� �� �࠭ ��ᯫ�� ���� ���ᥫ�
	;mov ah, 0
	;mov al, 04h
	;int 10h 
	;mov ah, 0ch
	;mov al, 14 ;��⠭�������� ����� 梥�
	;mov cx, 50 ;�⮫��� 50
	;mov dx, 50 ;��ப� 50
	;int 10h
	
	; �㭪�� 0eh �������� �뢮���� �� �࠭ ��ᯫ��  � ०��� ⥫�⠩��. ��� �㭪�� 㤮��� �ᯮ�짮���� ��� �뢮�� ��ப� ᨬ����� ����� �㭪樨 09h
	;mov ah, 0eh
	;lea bx, OutPutText ; ����砥� ����� ��ப�
	;@repeat:
	;mov al, [bx] ;�����뢠�� ���� ᨬ���
	;cmp al, '$' ;�஢��� ����� ��ப�
	;je EXIT_CODE ;��室 �� 横��
	;int 10h ; �뢮��� ᨬ��� �� �࠭
	;inc bx
	;jmp short @repeat
	;EXIT_CODE:

	; �ਬ�� ��� ����祭�� ���ଠ樨 �� ��⠭�������� ���������஫���
	;mov bx, ds ; ����㥬 ᥣ���� ������
	;mov es, bx ; ��।��� ����
	;mov di, offset Video_Info
	;mov ax, 4f00h
	;int 10h
	;cmp al, 4fh
	;jne No_Sup ;�㭪�� �� �����ন������
	;cmp ah, 0
	;jnz Error_HND ; �ந��諠 �訡��
	;��࠭���� ����� ࠧ��� ���������� � ���������
	;xor eax, eax
	;mov ax, [word ptr Video_Info + 12h]
	;shl ax, 6
	;mov Total_Memory, eax
	;mov edx, eax
	;call print
	;mov ah,9
	;mov dx,offset MB
	;int 21h
	;jmp TOEND
	
	;No_Sup:
	;	mov ah,9
	;	mov dx,offset OutPutSup
	;	int 21h
	;	jmp TOEND
	;Error_HND:
	;	mov ah,9
	;	mov dx,offset OutPutError
	;	int 21h
	;	jmp TOEND
	
	; �������� ����� � �࠭�
	;xor al, al
	;mov al, 0ah
	;mov dx, 03d4h ;
	;out dx, al ;
	;xor bl, bl
	;mov dx, 03d5h ;
	;in bl, dx ;
	;test bl, 20h ;
	;je END_PROC ;
	;and bl, 20h ;
	;mov al, 0ah ;
	;mov dx, 03d4h ;
	;out dx, al ;
	;mov dx, 03d5h ;
	;mov al, bl ;
	;out dx, al ;
	;END_PROC:
	
	; ��⠭���� ⥪�⮢��� ��� ����᪮�� ०���
	;mov ah, 00h
	;mov al, 11h ; 11h - ����᪨� ०�� 640 x 480 (2 梥�) 01h - ⥪�⮢� ०�� 40 x 25 (16 梥�)
	;int 10h
	;cmp al, 30h
	;je Error_Mode
	;mov ah,9
	;mov dx,offset OutPutWork
	;int 21h
	;jmp TOEND
	;Error_Mode:
	;	mov ah,9
	;	mov dx,offset OutPutError
	;	int 21h
	
	; 01h �������� ��⠭����� ࠧ�� ����� ��� ⥪�⮢�� ०���� ࠡ��� ����������஢
	;mov ah, 01h
	;mov ch, 0 ;����� ��砫쭮� �����, ��稭�� ᢥ��� �࠭�
	;mov cl, 0ah ; ����� ����筮� ����� ࠢ�� 10
	;int 10h
	
	; 02h �������� ��⠭����� ⥪���� ������ ����࠭� �࠭� ��ᯫ�� 
	;mov ah, 02h
	;mov bh, 0 ; ����� ��࠭���
	;mov dh, 6 ; ����� ��ப�
	;mov dl, 27 ; ����� �⮫��
	;int 10h
	;mov ah,9
	;mov dx,offset OutPutWork
	;int 21h
	
	; 03h �������� ������� ࠧ��� � ������ ����� ��� 㪠������ ��࠭��� ����������
	;mov ah, 03h
	;mov bh, 0 ; ����� ��࠭���
	;int 10h
	; ����砥� ������ �����
	;mov CUR_Y, dh ; ����� ��ப�
	;mov CUR_X, dl ; ����� �⮫��
	
	; 05h �������� ����� ⥪���� ��࠭��� ���������� ��� ⥪�⮢��� ०��� ��������� �� 00h �� 07h
	;mov ah, 05h
	;mov bh, 01h ; ���� �⠭�� ����������
	;int 10h
	
	; 05h �������� �ப����� ��⨢��� ��࠭��� �����
	;mov ah, 05h
	;mov bh, 00h ; �롨ࠥ� ����� ��࠭���
	;int 10h
	;mov ah, 06h
	;mov al, 00h ; �᫮ ��ப
	;mov bh, 7 ; ��ਡ�� ���⪨
	;mov ch, 2 ; ���� ��ப� ����孥�� 㣫�
	;mov cl, 15 ; ����� �⮫�� ����孥�� 㣫�
	;mov dh, 10 ; ����� ��ப� ������� 㣫�
	;mov cl, 15 ; ����� �⮫�� ������� 㣫�
	;int 10h
	
	; 09h �।�����祭� ��� ����� ᨬ���� � 㪠����묨 ��ਡ�⠬� � ⥪��� ������ �����
	mov ah, 02h
	mov bh, 00h ; ����� ��࠭���
	mov dh, 1 ; ����� ��ப�
	mov dl, 10 ; ����� �⮫��
	int 10h
	mov ah, 9h 
	mov al, 'w' ; 㪠�뢠�� ᨬ���, ����� �㦭� �������
	mov bh, 00h ; ����� ��࠭���
	mov bl, 14 ; ����� 梥� ᨢ���
	mov cx, 5 ; ������� 5 ࠧ
	int 10h
	
	; 36h �������� �ࠢ���� ॣ����樥� (�����������) �࠭� ��ᯫ��
	;mov ah, 12h
	;mov bl, 36h
	;mov al, 00h ; ࠧ���� ॣ������ (01h - �������)
	;int 10h
	;cmp al, 12h
	;jne ERR
	;jmp TOEND
	;ERR:
	;	mov ah,9
	;	mov dx,offset OutPutWork
	;	int 21h
	
	; 1015h �������� ������� ���ଠ�� � 梥� ��� 㪠������� ॣ���� ���(����-��������� �८�ࠧ���⥫�) ����� ॣ���� �� 00h �� ffh
	;mov ax, 1015h
	;mov bl, 03h 
	;int 10h
	;mov GREEN, ch ; �⠥� ����饭����� �������� 梥� 
	;mov BLUE, cl ; �⠥� ����饭����� ᨭ��� 梥�
	;mov RED, dh ; �⠥� ����饭����� ��᭮�� 梥�
	
	;mov ah,9
	;mov dx,offset GREEN_INTENSITY
	;int 21h
	;mov dl, GREEN
	;call print
	;call Newline
	
	;mov ah,9
	;mov dx,offset BLUE_INTENSITY
	;int 21h
	;mov dl, BLUE
	;call print
	;call Newline
	
	;mov ah,9
	;mov dx,offset RED_INTENSITY
	;int 21h
	;mov dl, RED
	;call print
	;call Newline
	;jmp TOEND
	
	
	; 1a00h �������� ��।����� ⨯ ������祭�� ��ᯫ��
	;mov ax, 1a00h
	;int 10h
	;cmp bl, 08h
	;jne ER
	;mov ah,9
	;mov dx,offset OutPut01
	;int 21h
	;jmp TOEND
	
	;ER:
	;jmp TOEND
	
	
	jmp TOEND
;;;;;;;; �뢮� �᫠ ;;;;;;;;;;;;;;;;;;;;;
print:
    mov eax, edx
    xor     ecx, ecx
    mov     ebx, 10
oi2:
    xor     edx,edx
    div     ebx
    push    edx
    inc     ecx
    test    eax, eax
    jnz     oi2
    mov     ah, 02h
oi3:
    pop     edx
    add     dl, '0'
    int     21h
    loop    oi3
ret 
;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
	
;;;;;;;;;;; ��७�� �ப� ;;;;;;;;;;;;;;;;	
Newline:
    push ax
    push dx
    mov ah,9
    lea dx,NL
    int 21h
    pop dx
    pop ax

ret	
;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;

TOEND:
    mov ax, 4c00h
    int 21h    
END	BEGIN