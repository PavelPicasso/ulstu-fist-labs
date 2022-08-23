.MODEL SMALL
.STACK 200h

.386

; Декларации данных
.DATA 
InputA db 13, 10, "Enter A: $"
InputB db 13, 10, "Enter B: $"
InputF db 13, 10, "Enter F: $"
MStep0_3 db 13, 10, "A*(B+P) NOT(A7*B7)$"
MStep1_3 db 13, 10, "A+NOT(B)+P+CI) NOT(A7)$"
MStep2_3 db 13, 10, "NOT(A)+P+B+NOT(P)+CI) NOT(A7)$"
MStep3_3 db 13, 10, "A-B*P-1+CI A7NOT(+)B7$"
MStep4_3 db 13, 10, "A+P+B*NOR(P)+CI 1$"
MStep5_3 db 13, 10, "A+B*NOT(P)+CI) A7$"
MStep6_3 db 13, 10, "A+NOT(P)+B*P+CI A7(+)B7$"
MStep7_3 db 13, 10, "A+B*P+CI A7(+)B7$"

MStep0_2 db 13, 10, "A*P NOT(A7*B7)$"
MStep1_2 db 13, 10, "A-1+CI NOT(A7)$"
MStep2_2 db 13, 10, "NOT(A)+CI) NOT(A7)$"
MStep3_2 db 13, 10, "A-P-1+CI A7NOT(+)B7$"
MStep4_2 db 13, 10, "CI-1 1$"
MStep5_2 db 13, 10, "A+CI A7$"
MStep6_2 db 13, 10, "A+P+CI A7(+)B7$"
MStep7_2 db 13, 10, "A+P+CI A7(+)B7$"

MStep0_1 db 13, 10, "A*B NOT(A7*B7)$"
MStep1_1 db 13, 10, "A-1+CI NOT(A7)$"
MStep2_1 db 13, 10, "NOT(A)+CI NOT(A7)$"
MStep3_1 db 13, 10, "A-B-1+CI A7NOT(+)B7$"
MStep4_1 db 13, 10, "CI-1 1$"
MStep5_1 db 13, 10, "A+CI) A7$"
MStep6_1 db 13, 10, "A+B+CI A7(+)B7$"
MStep7_1 db 13, 10, "A+B+CI A7(+)B7$"

Input db 13, 10, "Enter 1-ri 2-li 3-ci 4-co 5-clk 6-chb 7-cs 8-ed 9-chs: $"
Erorr db 13, 10, "Первый бит F должен быть 0 $"
A dw ?
B dw ?
P dw ?
F dw ?

Ri db ?
LI db ?
CI dw ?
CO db ?
CLK db ?
CHB db ?
myCS db ?
ED db ?
CHS db ?

NL db 13,10,'$'
;========================= Программа =========================
.CODE

BEGIN	LABEL	NEAR
    ; инициализация сегментного регистра
	mov	AX,	@DATA
	mov	DS,	AX
                    
    mov ah,9
    mov dx,offset InputA
    int 21h
 
NEXTA:
	mov ah, 01h
	int 21h
	cmp al, 0dh
	je GETOUTA
	sub al, 30h
	cmp al, 0
	je LA
	add bl, 1

LA:
	shl bx, 1
	jmp NEXTA

GETOUTA:
	shr bx, 1
	mov A, bx
	
	mov ah,9
    mov dx,offset InputB
    int 21h

NEXTB:
	mov ah, 01h
	int 21h
	cmp al, 0dh
	je GETOUTB
	sub al, 30h
	cmp al, 0
	je LB
	add cl, 1

LB:
	shl cx, 1
	jmp NEXTB

GETOUTB:
	shr cx, 1
    mov B ,cx
    mov P ,cx
	xor cx, cx
	
	mov ah,9
    mov dx,offset InputF
    int 21h
	
	;провевка на первый бит, проверить
	;mov ah, 01h
	;int 21h
	;sub al, 30h
	;bt cx, 1
	;jnc error1

NEXTF:
	mov ah, 01h
	int 21h
	cmp al, 0dh
	je GETOUTF
	sub al, 30h
	cmp al, 0
	je LF
	add cl, 1

LF:
	shl cx, 1
	jmp NEXTF

GETOUTF:
	shr cx, 1

	mov F, cx
	xor cx, cx
	
	mov ah,9
    mov dx,offset Input
    int 21h
	
	mov ah, 01h
	int 21h
	sub al, 30h
	mov ah, 0
	mov RI, ah
	
	mov ah, 01h
	int 21h
	sub al, 30h
	mov ah, 0
	mov LI, ah
	
	mov ah, 01h
	int 21h
	sub al, 30h
	mov ah, 0
	mov CI, ax
	
	mov ah, 01h
	int 21h
	sub al, 30h
	mov ah, 0
	mov CO, ah
	
	mov ah, 01h
	int 21h
	sub al, 30h
	mov ah, 0
	mov CLK, ah
	
	mov ah, 01h
	int 21h
	sub al, 30h
	mov ah, 0
	mov CHB, ah
	
	mov ah, 01h
	int 21h
	sub al, 30h
	mov ah, 0
	mov myCS, ah
	
	mov ah, 01h
	int 21h
	sub al, 30h
	mov ah, 0
	mov ED, ah
	
	mov ah, 01h
	int 21h
	sub al, 30h
	mov ah, 0
	mov CHS, ah
	
	
	mov ax, 00001111b
	and ax, F
	cmp ax, 00001111b
	jne next1
	
	mov ax, 00001111b
	or ax, F
	cmp ax, 00001111b
	jne step3_2
	; варианты для 1111
	; 1 вариант 0000
	mov cx, P
	not cx
	mov dx, B
	or cx, dx
	and cx, A
	
	mov ah,9
    mov dx,offset MStep0_3
    int 21h
	
	call Newline
	call print
	
	mov dl, ' '
    mov ah, 02h
    int 21h
	
	bt A,7
	jc A1
	mov cx, 0
	jmp A1next
A1:
	mov cx, 1
A1next:	
	bt B, 7
	jc A2
	mov bx, 0
	jmp A2next
A2:
	mov bx, 1
A2next:	
	and cx, bx
	not cx
	bt cx, 7
	jc A3
	mov dl, '0'
    mov ah, 02h
    int 21h
	jmp TOEND
A3:
	mov dl, '1'
    mov ah, 02h
    int 21h
	jmp TOEND

step3_2:	
	; варианты для 1111
	; 2 вариант 0001
	mov ax, F
	shr ax, 4
	cmp ax, 0001b
	jne step3_3
	
	mov cx, B
	not cx
	or cx, P
	add cx, A
	add cx, CI
	
	mov ah,9
    mov dx,offset MStep1_3
    int 21h
	
	call Newline
	call print
	
	mov dl, ' '
    mov ah, 02h
    int 21h
	
	bt A,7
	jc A1_1
	mov dl, '1'
    mov ah, 02h
    int 21h
	jmp TOEND
A1_1:
	mov dl, '0'
    mov ah, 02h
    int 21h
	jmp TOEND

step3_3:
	; варианты для 1111
	; 3 вариант 0010
	mov ax, F
	shr ax, 4
	cmp ax, 0010b
	jne step3_4
	
	mov cx, A
	not cx
	and cx, P
	mov bx, P
	not bx
	and bx, B
	add cx, bx
	add cx, CI
	
	mov ah,9
    mov dx,offset MStep2_3
    int 21h
	
	call Newline
	call print
	
	mov dl, ' '
    mov ah, 02h
    int 21h
	
	bt A,7
	jc A1_2
	mov dl, '1'
    mov ah, 02h
    int 21h
	jmp TOEND
A1_2:
	mov dl, '0'
    mov ah, 02h
    int 21h
	jmp TOEND
	
step3_4:
	; варианты для 1111
	; 4 вариант 0011
	mov ax, F
	shr ax, 4
	cmp ax, 0011b
	jne step3_5
	
	mov cx, A
	mov bx, B
	and bx, P
	sub cx, bx
	sub cx, 1
	add cx, CI
	
	mov ah,9
    mov dx,offset MStep3_3
    int 21h
	
	call Newline
	call print
	
	mov dl, ' '
    mov ah, 02h
    int 21h
	
	bt A,7
	jc A4_0
	mov cx, 0
	jmp A0next
A4_0:
	mov cx, 1
A0next:	
	bt B, 7
	jc A4_1
	mov bx, 0
	jmp A4_0next
A4_1:
	mov bx, 1
A4_0next:	
	xor cx, bx
	not cx
	
	bt cx, 7
	jc A4_3
	mov dl, '0'
    mov ah, 02h
    int 21h
	jmp TOEND
A4_3:
	mov dl, '1'
    mov ah, 02h
    int 21h
	jmp TOEND

step3_5:
	; варианты для 1111
	; 5 вариант 0100
	mov ax, F
	shr ax, 4
	cmp ax, 0100b
	jne step3_6
	
	mov cx, A
	or cx, P
	mov bx, P
	not bx
	and bx, B
	add cx, bx
	add cx, CI
	
	mov ah,9
    mov dx,offset MStep4_3
    int 21h
	
	call Newline
	call print
	
	mov dl, ' '
    mov ah, 02h
    int 21h
	mov dl, '1'
    mov ah, 02h
    int 21h
	
	jmp TOEND
	
step3_6:
	; варианты для 1111
	; 6 вариант 0101
	mov ax, F
	shr ax, 4
	cmp ax, 0101b
	jne step3_7
	
	mov cx, A
	mov bx, P
	not bx
	and bx, B
	add cx, bx
	add cx, CI
		
	mov ah,9
    mov dx,offset MStep5_3
    int 21h
	
	call Newline
	call print
	
	mov dl, ' '
    mov ah, 02h
    int 21h
	
	bt A,7
	jc A3_6
	mov dl, '0'
    mov ah, 02h
    int 21h
	jmp A3_6next
A3_6:
	mov dl, '1'
    mov ah, 02h
    int 21h
A3_6next:	
	jmp TOEND

step3_7:
	; варианты для 1111
	; 7 вариант 0110
	mov ax, F
	shr ax, 4
	cmp ax, 0110b
	jne step3_8
	
	mov cx, P
	not cx
	or cx, A
	mov bx, B
	and bx, P
	add cx, bx
	add cx, CI
	
	mov ah,9
    mov dx,offset MStep6_3
    int 21h
	
	call Newline
	call print
	
	mov dl, ' '
    mov ah, 02h
    int 21h
	
	bt A,7
	jc A5_0
	mov cx, 0
	jmp Aanext
A5_0:
	mov cx, 1
Aanext:	
	bt B, 7
	jc A7_1
	mov bx, 0
	jmp A7_0next
A7_1:
	mov bx, 1
A7_0next:	
	xor cx, bx
	call print
	jmp TOEND

step3_8:
	; варианты для 1111
	; 8 вариант 0111
	mov ax, F
	shr ax, 4
	cmp ax, 0111b
	jne TOEND
	
	mov cx, B
	and cx, P
	add cx, A
	add cx, CI
	
	mov ah,9
    mov dx,offset MStep7_3
    int 21h
	
	call Newline
	call print
	
	mov dl, ' '
    mov ah, 02h
    int 21h
	
	bt A,7
	jc A6_0
	mov cx, 0
	jmp Aa6next
A6_0:
	mov cx, 1
Aa6next:	
	bt B, 7
	jc A8_1
	mov bx, 0
	jmp A8_0next
A8_1:
	mov bx, 1
A8_0next:	
	xor cx, bx
	call print
	jmp TOEND
	
next1:
	mov ax, 00000001b
	and ax, F
	cmp ax, 00000001b
	jne next1_2
	jmp todo2
	
next1_2:
	mov ax, 00000011b
	and ax, F
	cmp ax, 00000011b
	jne todonext2

todo2:
	; варианты для 0011
	; 1 вариант 0000	
	mov ax, F
	shr ax, 4
	cmp ax, 0000b
	jne next2_1
	
	mov cx, A
	and cx, B
	
	mov ah,9
    mov dx,offset MStep0_2
    int 21h
	
	call Newline
	call print
	
	mov dl, ' '
    mov ah, 02h
    int 21h
	
	bt A,7
	jc A10_2
	mov cx, 0
	jmp A31next
A10_2:
	mov cx, 1
A31next:	
	bt B, 7
	jc A2_30
	mov bx, 0
	jmp A2_30next
A2_30:
	mov bx, 1
A2_30next:	
	and cx, bx
	not cx
	bt cx, 7
	jc A3_203
	mov dl, '0'
    mov ah, 02h
    int 21h
	jmp TOEND
A3_203:
	mov dl, '1'
    mov ah, 02h
    int 21h
	jmp TOEND

next2_1:
	; варианты для 0011
	; 2 вариант 0001	
	mov ax, F
	shr ax, 4
	cmp ax, 0001b
	jne next2_2
	
	mov cx, A
	sub cx, 1
	add cx, CI

	mov ah,9
    mov dx,offset MStep1_2
    int 21h
	
	call Newline
	call print
	
	mov dl, ' '
    mov ah, 02h
    int 21h

	bt A,7
	jc A1_20
	mov dl, '1'
    mov ah, 02h
    int 21h
	jmp TOEND
A1_20:
	mov dl, '0'
    mov ah, 02h
    int 21h
	jmp TOEND

next2_2:
	; варианты для 0011
	; 3 вариант 0010	
	mov ax, F
	shr ax, 4
	cmp ax, 0010b
	jne next2_3
	
	mov cx, A
	not cx
	add cx, CI
	
	mov ah,9
    mov dx,offset MStep2_2
    int 21h
	
	call Newline
	call print
	
	mov dl, ' '
    mov ah, 02h
    int 21h
	
	bt A,7
	jc A1_204
	mov dl, '1'
    mov ah, 02h
    int 21h
	jmp TOEND
A1_204:
	mov dl, '0'
    mov ah, 02h
    int 21h
	jmp TOEND

next2_3:	
	; варианты для 0011
	; 4 вариант 0011	
	mov ax, F
	shr ax, 4
	cmp ax, 0011b
	jne next2_4

	mov cx, A
	sub cx, P
	sub cx, 1
	add cx, CI
	
	mov ah,9
    mov dx,offset MStep3_2
    int 21h
	
	call Newline
	call print
	
	mov dl, ' '
    mov ah, 02h
    int 21h
	
	bt A,7
	jc A4_20
	mov cx, 0
	jmp A20next
A4_20:
	mov cx, 1
A20next:	
	bt B, 7
	jc A4_12
	mov bx, 0
	jmp A4_20next
A4_12:
	mov bx, 1
A4_20next:	
	xor cx, bx
	not cx
	
	bt cx, 7
	jc A4_32
	mov dl, '0'
    mov ah, 02h
    int 21h
	jmp TOEND
A4_32:
	mov dl, '1'
    mov ah, 02h
    int 21h
	jmp TOEND
	
next2_4:
	; варианты для 0011
	; 5 вариант 0100	
	mov ax, F
	shr ax, 4
	cmp ax, 0100b
	jne next2_5
	
	mov cx, CI
	sub cx, 1
	
	mov ah,9
    mov dx,offset MStep4_2
    int 21h
	
	call Newline
	call print
	
	mov dl, ' '
    mov ah, 02h
    int 21h
	mov dl, '1'
    mov ah, 02h
    int 21h
	
next2_5:
	; варианты для 0011
	; 6 вариант 0101	
	mov ax, F
	shr ax, 4
	cmp ax, 0101b
	jne next2_6
	
	mov cx, A
	add cx, CI
	
	mov ah,9
    mov dx,offset MStep5_2
    int 21h
	
	call Newline
	call print
	
	mov dl, ' '
    mov ah, 02h
    int 21h
	
	bt A,7
	jc A3_62
	mov dl, '0'
    mov ah, 02h
    int 21h
	jmp A3_62next
A3_62:
	mov dl, '1'
    mov ah, 02h
    int 21h
A3_62next:	
	jmp TOEND

next2_6:
	; варианты для 0011
	; 7 вариант 0110	
	mov ax, F
	shr ax, 4
	cmp ax, 0110b
	jne next2_7
	
	mov cx, A
	add cx, P
	add cx, CI
	
	mov ah,9
    mov dx,offset MStep6_2
    int 21h
	
	call Newline
	call print
	
	mov dl, ' '
    mov ah, 02h
    int 21h
	
	bt A,7
	jc A5_20
	mov cx, 0
	jmp Aa2next
A5_20:
	mov cx, 1
Aa2next:	
	bt B, 7
	jc A7_12
	mov bx, 0
	jmp A7_20next
A7_12:
	mov bx, 1
A7_20next:	
	xor cx, bx
	call print
	jmp TOEND
	
next2_7:
	; варианты для 0011
	; 8 вариант 0111	
	mov ax, F
	shr ax, 4
	cmp ax, 0111b
	jne TOEND
	
	mov cx, A
	add cx, P
	add cx, CI
	
	mov ah,9
    mov dx,offset MStep7_2
    int 21h
	
	call Newline
	call print
	
	mov dl, ' '
    mov ah, 02h
    int 21h
	
	bt A,7
	jc A5_202
	mov cx, 0
	jmp Aa22next
A5_202:
	mov cx, 1
Aa22next:	
	bt B, 7
	jc A7_212
	mov bx, 0
	jmp A7_205next
A7_212:
	mov bx, 1
A7_205next:	
	xor cx, bx
	call print
	jmp TOEND
	
	
todonext2:
	
	mov ax, F
	shr ax, 4
	; 1 вариант 0000	
	cmp ax, 0000b
	jne todo3_1

	mov cx, A
	and cx, B
	
	mov ah,9
    mov dx,offset MStep0_1
    int 21h
	
	call Newline
	call print
	
	mov dl, ' '
    mov ah, 02h
    int 21h
	
	bt A,7
	jc A1q
	mov cx, 0
	jmp A1qnext
A1q:
	mov cx, 1
A1qnext:	
	bt B, 7
	jc A2q
	mov bx, 0
	jmp A2qnext
A2q:
	mov bx, 1
A2qnext:	
	and cx, bx
	not cx
	bt cx, 7
	jc Aq3
	mov dl, '0'
    mov ah, 02h
    int 21h
	jmp TOEND
Aq3:
	mov dl, '1'
    mov ah, 02h
    int 21h
	jmp TOEND
	
todo3_1:	
	; 2 вариант 0001	
	mov ax, F
	shr ax, 4
	cmp ax, 0001b
	jne todo3_2

	mov cx, A
	sub cx, 1
	add cx, CI
	
	mov ah,9
    mov dx,offset MStep1_1
    int 21h
	
	call Newline
	call print
	
	mov dl, ' '
    mov ah, 02h
    int 21h
	
	bt A,7
	jc Aq1_1
	mov dl, '1'
    mov ah, 02h
    int 21h
	jmp TOEND
Aq1_1:
	mov dl, '0'
    mov ah, 02h
    int 21h
	jmp TOEND

todo3_2:	
	; 3 вариант 0010	
	mov ax, F
	shr ax, 4
	cmp ax, 0010b
	jne todo3_3
	
	mov cx, A
	not cx
	add cx, CI
	
	mov ah,9
    mov dx,offset MStep2_1
    int 21h
	
	call Newline
	call print
	
	mov dl, ' '
    mov ah, 02h
    int 21h
	
	bt A,7
	jc Aw1_1
	mov dl, '1'
    mov ah, 02h
    int 21h
	jmp TOEND
Aw1_1:
	mov dl, '0'
    mov ah, 02h
    int 21h
	jmp TOEND
	
todo3_3:
	; 4 вариант 0011	
	mov ax, F
	shr ax, 4
	cmp ax, 0011b
	jne todo3_4
	
	mov cx, A
	sub cx, B
	sub cx, 1
	add cx, CI
	
	mov ah,9
    mov dx,offset MStep3_1
    int 21h
	
	call Newline
	call print
	
	mov dl, ' '
    mov ah, 02h
    int 21h
	
	bt A,7
	jc Ae4_20
	mov cx, 0
	jmp Ae20next
Ae4_20:
	mov cx, 1
Ae20next:	
	bt B, 7
	jc Ae4_12
	mov bx, 0
	jmp Ae4_20next
Ae4_12:
	mov bx, 1
Ae4_20next:	
	xor cx, bx
	not cx
	
	bt cx, 7
	jc Ae4_32
	mov dl, '0'
    mov ah, 02h
    int 21h
	jmp TOEND
Ae4_32:
	mov dl, '1'
    mov ah, 02h
    int 21h
	jmp TOEND
	
todo3_4:
	; 4 вариант 0100	
	mov ax, F
	shr ax, 4
	cmp ax, 0100b
	jne todo3_5
	
	mov cx, CI
	sub cx, 1

	mov ah,9
    mov dx,offset MStep4_1
    int 21h
	
	call Newline
	call print
	
	mov dl, ' '
    mov ah, 02h
    int 21h
	mov dl, '1'
    mov ah, 02h
    int 21h

todo3_5:
	; 6 вариант 0101	
	mov ax, F
	shr ax, 4
	cmp ax, 0101b
	jne todo3_6
	
	mov cx, A
	add cx, CI
	
	mov ah,9
    mov dx,offset MStep5_1
    int 21h
	
	call Newline
	call print
	
	mov dl, ' '
    mov ah, 02h
    int 21h
	
	bt A,7
	jc As3_62
	mov dl, '0'
    mov ah, 02h
    int 21h
	jmp As3_62next
As3_62:
	mov dl, '1'
    mov ah, 02h
    int 21h
As3_62next:	
	jmp TOEND

todo3_6:
	; 7 вариант 0110	
	mov ax, F
	shr ax, 4
	cmp ax, 0110b
	jne todo3_7
	
	mov cx, A
	add cx, P
	add cx, CI
	
	mov ah,9
    mov dx,offset MStep6_1
    int 21h
	
	call Newline
	call print
	
	mov dl, ' '
    mov ah, 02h
    int 21h
	
	bt A,7
	jc Ad5_20
	mov cx, 0
	jmp Ada2next
Ad5_20:
	mov cx, 1
Ada2next:	
	bt B, 7
	jc Ad7_12
	mov bx, 0
	jmp Ad7_20next
Ad7_12:
	mov bx, 1
Ad7_20next:	
	xor cx, bx
	call print
	jmp TOEND

todo3_7:
	; 8 вариант 0111	
	mov ax, F
	shr ax, 4
	cmp ax, 0111b
	jne TOEND
	
	mov cx, A
	add cx, P
	add cx, CI
	
	mov ah,9
    mov dx,offset MStep7_1
    int 21h
	
	call Newline
	call print
	
	mov dl, ' '
    mov ah, 02h
    int 21h
	
	bt A,7
	jc Af5_202
	mov cx, 0
	jmp Afa22next
Af5_202:
	mov cx, 1
Afa22next:	
	bt B, 7
	jc Af7_212
	mov bx, 0
	jmp Af7_205next
Af7_212:
	mov bx, 1
Af7_205next:	
	xor cx, bx
	call print
	jmp TOEND
	
	
;;;;;;;;;;; перенос троки ;;;;;;;;;;;;;;;;	
Newline proc

    push ax
    push dx
    mov ah,9
    lea dx,NL
    int 21h
    pop dx
    pop ax

ret
;;;;;;;; Вывод числа ;;;;;;;;;;;;;;;;;;;;;
print:
    mov ax, cx
    xor     cx, cx
    mov     bx, 2
oi2:
    xor     dx,dx
    div     bx
    push    dx
    inc     cx
    test    ax, ax
    jnz     oi2
    mov     ah, 02h
oi3:
    pop     dx
    add     dl, '0'
    int     21h
    loop    oi3
ret 

;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;	
error1:
	mov ah,9
    mov dx,offset Erorr
    int 21h

TOEND:
	
	mov ax, 4c00h
    int 21h    
END	BEGIN